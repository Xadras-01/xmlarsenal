<?php

/**
 * Arenaladder.class.php
 * 
 * This Object represents the arena ladder. There is only one Ladder atm because XMLArsenal does 
 * not support multiple battlegroups yet.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/11/18
 * @package XMLArsenal
 * @subpackage classes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

class Arenaladder{
	
	/**
	 * holds info about the teams on the ladder
     * @access private
     * @var array
     */
	private $teams;
	
	/**
	 * point in time (unix timestamp) when the last update was performed on this object
     * @access private
     * @var int
     */
	private $lastUpdate;
	
	
	/**
     * Constructor sets up the ladder (invokes fetch- and update-functions)
	 * @access public
	 * @uses Arenaladder::fetchCachedData()
	 * @uses Arenaladder::performUpdate()
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
     * This function fetches the serialized "Arenaladder"-object from the arsenal database (if it contains the specified object). Otherwise it invokes the {@link firstLoad()}-function
	 * @access private
	 * @uses Arenaladder::firstLoad()
	 * @global obj $arsenaldb
	 * @return void
    */
	private function fetchCachedData(){
		
		global $arsenaldb;
		
		//get player data from cache-db
		$sql = 'SELECT data FROM arsenal_arenaladder LIMIT 1;';
			
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
		
		//init teams
		$this->teams = array();
		
		foreach($realms as $realm){
			
			$realm->loadDataGrabber();
			$this->aquireData($realm->getDataGrabber(), $realm->realmName);
			
		}//foreach
		
		$sql = 'INSERT INTO arsenal_arenaladder VALUES ('. $arsenaldb->quote(serialize($this), 'text') .');';
		
		$res =& $arsenaldb->exec($sql);
		if (PEAR::isError($res)) {
			die($res->getMessage());
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
		$this->teams = array();
		
		foreach($realms as $realm){
			
			$realm->loadDataGrabber();
			$this->aquireData($realm->getDataGrabber(), $realm->realmName);
			
		}//foreach
		
		$sql = 'UPDATE arsenal_arenaladder SET data = ' . $arsenaldb->quote(serialize($this), 'text') .' LIMIT 1';
		
		$res =& $arsenaldb->exec($sql);
		if (PEAR::isError($res)) {
			die($res->getMessage());
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
		
		//get arenateams that may belong on the ladder
		$teams = $dataGrabber->getArenaLadder($realmName);
		if(!empty($teams)){
			
			foreach($teams as $team){
				
				array_push($this->teams, $team);
				
			}//foreach
		}//if
		
		usort($this->teams, array($this, 'sortByRating'));
		
		//set last update time
		$this->lastUpdate = time();
		
	}//aquireData()
	
	
	/**
     * returns an array filled with several variables that are used in arena-ladder.xml
	 * @access public
	 * @global array $realms
	 * @global string $language
	 * @global string $filterValue
	 * @global string $filterField
	 * @global int $teamSize
	 * @global array $DATA
	 * @global string $realmpool
	 * @return array
    */
	public function prepareArenaladderSheet(){
		
		global $realms;
		global $language;
		global $DATA;
		global $realmpool;
		global $teamSize;
		global $filterValue;
		global $filterField;
		
		foreach($realms as $realm){
			if($realm->realmId == $this->realmId){
				$realmName = $realm->realmName;
				break;
			}//if
		}//foreach
		
		$vars = array();
		$vars['REALMPOOL'] = $realmpool;
		$vars['REALMPOOLURL'] = urlencode($realmpool);
		$vars['REALM'] = $realmName;
		$vars['REALMURL'] = urlencode($realmName);
		$vars['LANGUAGE'] = $language;
		$vars['TEAMSIZE'] = $teamSize;
		$vars['FILTERFIELD'] = $filterField;
		$vars['FILTERVALUE'] = $filterValue;
		
		$vars['TEAMS'] = '';
		if(!empty($this->teams)){
			
			$rank = 1;
			foreach($this->teams as $team){
				
				//only the right teamsize-ladder
				if($teamSize == $team['teamSize'] && $rank < 21){
					
					if(($filterField == 'realm' && $team['realmName'] == $filterValue) || !isset($filterField)){
						$teamvars = array();
						$teamvars['REALMPOOL'] = $realmpool;
						$teamvars['REALMPOOLURL'] = urlencode($realmpool);
						$teamvars['REALM'] = $team['realmName'];
						$teamvars['REALMURL'] = urlencode($team['realmName']);
						$teamvars['NAME'] = $team['name'];
						$teamvars['NAMEURL'] = urlencode($team['name']);
						$teamvars['FACTIONID'] = $DATA["factions"][$team['leaderRace']];
						$teamvars['FACTION'] = $DATA["factionNames_$language"][$team['leaderRace']];
						$teamvars['WON'] = $team['gamesWon'];
						$teamvars['SEASONWON'] = $team['seasonGamesWon'];
						$teamvars['TEAMSIZE'] = $team['teamSize'];
						$teamvars['PLAYED'] = $team['gamesPlayed'];
						$teamvars['SEASONPLAYED'] = $team['seasonGamesPlayed'];
						$teamvars['RATING'] = $team['rating'];
						$teamvars['RANKING'] = $rank;
						$teamvars['MEMBERCOUNT'] = 1; //not used?
						$teamvars['EMBLEMBG'] = $team['background'];
						$teamvars['EMBLEMBORDERCOL'] = $team['borderColor'];
						$teamvars['EMBLEMBORDERSTYLE'] = $team['borderStyle'];
						$teamvars['EMBLEMICONCOL'] = $team['iconColor'];
						$teamvars['EMBLEMICONSTYLE'] = $team['iconStyle'];
						$rank++;
						
						$vars['TEAMS'] .= fgettemplate('./xml-templates/arena-ladder-team.xml', $teamvars);
						
					}//if
				}//if
				
			}//foreach
		}//if
		
		return $vars;
		
	}//prepareArenaladderSheet()
	
	
	/**
     * this is a callback function for sorting the teams by their rating
	 * @access private
	 * @param array $a
	 * @param array $b
	 * @return int
    */
	private function sortByRating($a, $b){
		
		if($a['rating'] == $b['rating']) return 0;
		else if($a['rating'] > $b['rating']) return -1;
		else return 1;
		
	}//sortByRating()
	
	
}//class Arenateam{}