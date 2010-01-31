<?php

/**
 * Achievementladder.class.php
 * 
 * This Object represents the "realm first" achievements.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2010/01/30
 * @package XMLArsenal
 * @subpackage classes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

class Achievementladder{
	
	/**
	 * holds info about the achievements on the ladder
     * @access private
     * @var array
     */
	private $achievements;
	
	/**
	 * point in time (unix timestamp) when the last update was performed on this object
     * @access private
     * @var int
     */
	private $lastUpdate;
	
	
	/**
     * Constructor sets up the ladder (invokes fetch- and update-functions)
	 * @access public
	 * @uses Achievementladder::fetchCachedData()
	 * @uses Achievementladder::performUpdate()
	 * @return void
    */
	public function __construct() {
		
		$this->fetchCachedData();
		
		//update if necessarry
		if(($this->lastUpdate + 60*60*UPDATEINTERVAL) < time()){
			
			$this->performUpdate();
			
		}//if
		
	}//__construct()
	
	
	/**
     * This function fetches the serialized "Achievementladder"-object from the arsenal database (if it contains the specified object). Otherwise it invokes the {@link firstLoad()}-function
	 * @access private
	 * @uses Achievementladder::firstLoad()
	 * @global obj $arsenaldb
	 * @return void
    */
	private function fetchCachedData(){
		
		global $arsenaldb;
		
		//get player data from cache-db
		$sql = 'SELECT data FROM arsenal_achievementladder LIMIT 1;';
			
		$res =& $arsenaldb->query($sql);
		
		if (PEAR::isError($res)) {
			die($res->getMessage());
		}
		
		if($res->numRows() == 0){
			
			$this->firstLoad();
			
		}else{
			
			$arr = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
			$arr = unserialize($arr['data']);
			
			foreach($arr as $key=>$value){
				$this->$key = $value;
			}//foreach
			
		}//else
		
	}//fetchCachedData()
	
	
	/**
     * firstLoad() fetches data from the realm-grabbers the first time the ladder is loaded into the arsenal and saves it to the arsenals database.
	 * @access private
	 * @global obj $arsenaldb
	 * @global array $realms
	 * @return void
    */
	private function firstLoad(){
		
		global $realms;
		global $arsenaldb;
		
		//init achievements
		$this->achievements = array();
		
		foreach($realms as $realm){
			
			$realm->loadDataGrabber();
			$this->aquireData($realm->getDataGrabber(), $realm->realmName);
			
		}//foreach
		
		$sql = 'INSERT INTO arsenal_achievementladder VALUES ('. $arsenaldb->quote(serialize($this), 'text') .');';
		//echo $sql;die();
		
		$res =& $arsenaldb->exec($sql);
		if (PEAR::isError($res)) {
			die($res->getDebugInfo());
		}//if
		
	}//firstLoad()
	
	
	/**
     * If an update is requested by {@link __construct() the constructor} this function performs it
	 * @access private
	 * @global obj $arsenaldb
	 * @global array $realms
	 * @return void
    */
	private function performUpdate(){
		
		global $realms;
		global $arsenaldb;
		
		//reset old list
		$this->achievements = array();
		
		foreach($realms as $realm){
			
			$realm->loadDataGrabber();
			$this->aquireData($realm->getDataGrabber(), $realm->realmName);
			
		}//foreach
		
		$sql = 'UPDATE arsenal_achievementladder SET data = ' . $arsenaldb->quote(serialize($this), 'text') .' LIMIT 1';
		
		$res =& $arsenaldb->exec($sql);
		if (PEAR::isError($res)) {
			die($res->getUserInfo());
		}//if
		
	}//performUpdate()
	
	
	/**
     * this is the function that really invokes the datagrabbers functions and calculates the values that are not read from the grabber
	 * @access private
	 * @param obj $dataGrabber
	 * @param string $teamName
	 * @global obj $arsenaldata
	 * @return void
    */
	private function aquireData($dataGrabber = null, $realmName = null){
		
		global $arsenaldata;
		
		$firstIDs = array();
		$qry = "SELECT achievementid FROM achievements WHERE flags IN (256, 768);";
		$stmt =& $arsenaldata->query($qry);
		while($res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC)){
			
			$firstIDs[] = $res['achievementid'];
			
		}//while
		
		//get achievements for the ladder
		$achievements = $dataGrabber->getAchievementFirsts($realmName, $firstIDs);
		
		if(!empty($achievements)){
			
			foreach($achievements as $achievement){
				
				array_push($this->achievements, $achievement);
				
			}//foreach
		}//if
		
		usort($this->achievements, array($this, 'sortByTime'));
		
		//set last update time
		$this->lastUpdate = time();
		
	}//aquireData()
	
	
	/**
     * returns an array filled with several variables that are used in achievement-firsts.xml
	 * @access public
	 * @global array $realms
	 * @global string $language
	 * @global string $filterValue
	 * @global string $filterField
	 * @global obj $arsenaldata;
	 * @global int $teamSize
	 * @global array $DATA
	 * @global string $realmpool
	 * @global string $arsenaldataLang;
	 * @return array
    */
	public function prepareAchievementladderSheet($realmName = null){
		
		global $realms;
		global $language;
		global $DATA;
		global $realmpool;
		global $filterValue;
		global $filterField;
		global $arsenaldata;
		global $arsenaldataLang;
		
		$vars = array();
		$vars['LANGUAGE'] = $language;
		$vars['ACHIEVEMENTS'] = '';
		$vars['REALMINFO'] = '';
		if($realmName != null) $vars['REALMINFO'] = 'realm="'.$realmName.'"';
		
		$qry = "SELECT a.achievementid, a.flags, al.achievementdescription, al.achievementname, a.icon 
					FROM achievements a JOIN achievements_$arsenaldataLang al ON a.achievementid = al.achievementid 
					WHERE a.flags IN (256, 768);";
			
		$stmt =& $arsenaldata->query($qry);
		while($res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC)){
			
			//single "realm first" achievement
			if($res['a.flags'] == 256){
				
				
				foreach($this->achievements as $ach){
					
					if($res['a.achievementid'] == $ach['achievementid'] && (($ach['realmName'] == $realmName) || $realmName == null)){
						
						$achvars = array();
						$achvars['REALM'] = $ach['realmName'];
						$achvars['REALMURL'] = urlencode($ach['realmName']);
						$achvars['KEY'] = "";
						$achvars['DESC'] = $res['al.achievementdescription'];
						$achvars['TITLE'] = $res['al.achievementname'];
						$achvars['DATE'] = date('Y-m-d\TH:i:sP', $ach['date']);
						$achvars['ICON'] = $res['a.icon'];
						
						$achvars['NAME'] = $ach['name'];
						$achvars['CHARURL'] = "n=".urlencode($ach['name'])."&r=".$achvars['REALMURL'];
						$achvars['ACHPOINTS'] = 0;
						$achvars['FACTIONID'] = $DATA["factions"][$ach['race']];
						$achvars['CLASSID'] = $ach['class'];
						$achvars['RACEID'] = $ach['raceId'];
						$achvars['GENDERID'] = $ach['genderId'];
						$achvars['GUILD'] = $ach['guild'];
						$achvars['GUILDURL'] = "gn=".urlencode($ach['guild'])."&r=".$achvars['REALMURL'];
						$achvars['LEVEL'] = $ach['level'];
						
						$vars['ACHIEVEMENTS'] .= fgettemplate('./xml-templates/achievement-firsts-achievement-single.xml', $achvars);
						
					}//if
					
				}//foreach
				
			}//if
			
			//group "realm first" achievement
			else if($res['a.flags'] == 768){
				
				
				
			}//if
			
		}//while
		
		return $vars;
		
	}//prepareArenaladderSheet()
	
	
	/**
     * this is a callback function for sorting the achievements by their date
	 * @access private
	 * @param array $a
	 * @param array $b
	 * @return int
    */
	private function sortByTime($a, $b){
		
		if($a['date'] == $b['date']) return 0;
		else if($a['date'] > $b['date']) return 1;
		else return -1;
		
	}//sortByRating()
	
	
}//class Arenateam{}