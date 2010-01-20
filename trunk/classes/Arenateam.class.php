<?php

/**
 * Arenateam.class.php
 * 
 * Every object oft this class represents an arenateam ingame. The class provides the 
 * arenateams database persistence layer.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/11/18
 * @package XMLArsenal
 * @subpackage classes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

class Arenateam{
	
	/**
	 * the ID used by XMLArsenal in arsenal_areanateams
     * @access private
     * @var int
     */
	private $entryId;
	
	/**
	 * the team-ID on a realm
     * @access private
     * @var int
     */
	private $teamId;
	
	/**
	 * the realm where the team resides
     * @access private
     * @var int
     */
	private $realmId;
	
	/**
	 * the teams name
     * @access private
     * @var string
     */
	private $teamName;
	
	/**
	 * the emblem
     * @access private
     * @var array
     */
	private $teamEmblem;
	
	/**
	 * the teams member with several data
     * @access private
     * @var array
     */
	private $teamMember;
	
	/**
	 * won matches count
     * @access private
     * @var int
     */
	private $teamWon;
	
	/**
	 * played matches count
     * @access private
     * @var int
     */
	private $teamPlayed;
	
	/**
	 * won matches (season) count
     * @access private
     * @var int
     */
	private $teamSeasonWon;
	
	/**
	 * played matches (season) count
     * @access private
     * @var int
     */
	private $teamSeasonPlayed;
	
	/**
	 * current rank of the arenateam
     * @access private
     * @var int
     */
	private $ranking;
	
	/**
	 * team rating
     * @access private
     * @var int
     */
	private $rating;
	
	/**
	 * the race-id of the leader
     * @access private
     * @var int
     */
	private $leaderRace;
	
	/**
	 * point in time (unix timestamp) when the last update was performed on this object
     * @access private
     * @var int
     */
	private $lastUpdate;
	
	
	/**
     * Constructor sets up the arenateam (invokes fetch- and update-functions)
	 * @access public
	 * @param string $realmName
	 * @param int $realmId
	 * @uses Arenateam::fetchCachedData()
	 * @uses Arenateam::performUpdate()
	 * @return void
    */
	public function __construct($teamName = null, $realmId = null) {
		
		if(!$teamName || !$realmId){
			
			die("No team name or realm name given.");
			
		}//if
		
		$this->fetchCachedData($teamName, $realmId);
		
		//update if necessarry
		if(($this->lastUpdate + 60*60*UPDATEINTERVAL) < time()){
			
			$this->performUpdate($teamName, $realmId);
			
		}//if
		
	}//__construct()
	
	
	/**
     * This function fetches the serialized "Arenateam"-object from the arsenal database (if it contains the specified object). Otherwise it invokes the {@link firstLoad()}-function
	 * @access private
	 * @param string $teamName
	 * @param int $realmId
	 * @uses Arenateam::firstLoad()
	 * @global obj $arsenaldb
	 * @return void
    */
	private function fetchCachedData($teamName = null, $realmId = null){
		
		global $arsenaldb;
		
		//get player data from cache-db
		$sql = 'SELECT entry_id, arenateam_id_on_realm, data FROM arsenal_arenateams WHERE 
					arenateam_name COLLATE utf8_bin = '	.	$arsenaldb->quote($teamName,   'text')   .' AND 
					realm_id='	.	$arsenaldb->quote($realmId,   'integer')   .' 
					LIMIT 1;';
			
		$res =& $arsenaldb->query($sql);
		
		if (PEAR::isError($res)) {
			die($res->getMessage());
		}
		
		if($res->numRows() == 0){
			
			$this->firstLoad($teamName, $realmId);
			
		}else{
			
			$arr = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
			$teamIdInTable = $arr['arenateam_id_on_realm'];
			$currentEntryId = $arr['entry_id'];
			$arr = unserialize($arr['data']);
			
			foreach($arr as $key=>$value){
				$this->$key = $value;
			}//foreach
			
			//team has changed its id (maybe its now a new team named like a deleted one)
			if($this->teamId != $teamIdInTable){
				
				$sql = 'DELETE FROM arsenal_arenateams WHERE entry_id = '.$arsenaldb->quote($currentEntryId, 'integer').' LIMIT 1;';
				$res =& $arsenaldb->exec($sql);
				if (PEAR::isError($res)) {
					die($res->getMessage());
				}//if
				
				$this->firstLoad($teamName, $realmId);
				
			}//if
			
			
		}//else
		
	}//fetchCachedData()
	
	
	/**
     * firstLoad() fetches data from the realm-grabber the first time a team is loaded into the arsenal and saves it to the arsenals database.
	 * @access private
	 * @param string $teamName
	 * @param int $realmId
	 * @global obj $arsenaldb
	 * @global array $realms
	 * @return void
    */
	private function firstLoad($teamName = null, $realmId = null){
		
		global $realms;
		global $arsenaldb;
		
		$this->realmId = $realmId;
		$realms[$realmId]->loadDataGrabber();
		$this->aquireData($realms[$realmId]->getDataGrabber(), $teamName);
		
		$id = $arsenaldb->nextID('arsenal_arenateams', true);
		$this->entryId = $id;
		$sql = 'INSERT INTO arsenal_arenateams VALUES ('
					. $arsenaldb->quote($id,   'integer')   .', '
					. $arsenaldb->quote($this->teamId, 'integer')      .', '
					. $arsenaldb->quote($this->realmId, 'integer')      .', '
					. $arsenaldb->quote($this->teamName, 'text')      .', '
					. $arsenaldb->quote(serialize($this), 'text') .')';
			
		$res =& $arsenaldb->exec($sql);
		if (PEAR::isError($res)) {
			die($res->getMessage());
		}//if
		
	}//firstLoad()
	
	
	/**
     * If an update is requested by {@link __construct() the constructor} this function performs it
	 * @access private
	 * @param string $teamName
	 * @param int $realmId
	 * @global obj $arsenaldb
	 * @global array $realms
	 * @return void
    */
	private function performUpdate($teamName, $realmId){
		
		global $realms;
		global $arsenaldb;
		
		$realms[$this->realmId]->loadDataGrabber();
		$this->aquireData($realms[$this->realmId]->getDataGrabber(), $this->teamName);
		
		$sql = 'UPDATE arsenal_arenateams SET 
					data = ' . $arsenaldb->quote(serialize($this), 'text') .'
					WHERE entry_id = '. $arsenaldb->quote($this->entryId, 'integer') . '
					LIMIT 1';
			
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
	private function aquireData($dataGrabber = null, $teamName = null){
		
		global $arsenaldata;
		
		//invoke data grabbing process
		$dataGrabber->setArenaTeamName($teamName);
		
		$props = $dataGrabber->getArenaTeamproperties();
		$this->teamId = $props['teamId'];
		$this->teamName = $props['name'];
		$this->leaderRace = $props['leaderRace'];
		$this->teamSize =  $props['teamSize'];
		$this->teamPlayed = $props['gamesPlayed'];
		$this->teamSeasonPlayed = $props['seasonGamesPlayed'];
		$this->teamWon = $props['gamesWon'];
		$this->teamSeasonWon = $props['seasonGamesWon'];
		$this->ranking = $props['ranking'];
		$this->rating = $props['rating'];
		
		$this->teamEmblem = array('borderColor' => $props['borderColor'],
									'backgroundColor' => $props['background'],
									'borderStyle' => $props['borderStyle'],
									'emblemColor' => $props['iconColor'],
									'emblemStyle' => $props['iconStyle']);
		
		$this->teamMember = $dataGrabber->getArenaTeammember();
		
		//set last update time
		$this->lastUpdate = time();
		
	}//aquireData()
	
	
	/**
     * getter for {@link $teamId}
	 * @access public
	 * @return int
    */
	public function getId(){
		
		return $this->teamId;
		
	}//getId()
	
	
	/**
     * returns an array filled with several variables that are used in team-info-basic.xml
	 * @access public
	 * @global array $realms
	 * @global string $language
	 * @global array $DATA
	 * @global string $realmpool
	 * @return array
    */
	public function prepareArenateamSheet(){
		
		global $realms;
		global $language;
		global $DATA;
		global $realmpool;
		
		foreach($realms as $realm){
			if($realm->realmId == $this->realmId){
				$realmName = $realm->realmName;
				break;
			}//if
		}//foreach
		
		$vars = array();
		$vars['NAME'] = $this->teamName;
		$vars['NAMEURL'] = urlencode($this->teamName);
		$vars['REALMPOOL'] = $realmpool;
		$vars['REALMPOOLURL'] = urlencode($realmpool);
		$vars['REALM'] = $realmName;
		$vars['REALMURL'] = urlencode($realmName);
		$vars['FACTIONID'] = $DATA["factions"][$this->leaderRace];
		$vars['FACTION'] = $DATA["factionNames_$language"][$this->leaderRace];
		$vars['LANGUAGE'] = $language;
		
		$vars['TEAMSIZE'] = $this->teamSize;
		$vars['MEMBERCOUNT'] = count($this->teamMember);
		$vars['WON'] = $this->teamWon;
		$vars['SEASONWON'] = $this->teamSeasonWon;
		$vars['PLAYED'] = $this->teamPlayed;
		$vars['SEASONPLAYED'] = $this->teamSeasonPlayed;
		$vars['RANKING'] = $this->ranking;
		$vars['RATING'] = $this->rating;
		
		$vars['EMBLEMBG'] = $this->teamEmblem['backgroundColor'];
		$vars['EMBLEMBORDERCOL'] = $this->teamEmblem['borderColor'];
		$vars['EMBLEMBORDERSTYLE'] = $this->teamEmblem['borderStyle'];
		$vars['EMBLEMICONCOL'] = $this->teamEmblem['emblemColor'];
		$vars['EMBLEMICONSTYLE'] = $this->teamEmblem['emblemStyle'];
		
		$vars['MEMBER'] = '';
		if(!empty($this->teamMember)){
			foreach($this->teamMember as $member){
				
				$membervars = array();
				$membervars['REALMPOOL'] = $realmpool;
				$membervars['REALMPOOLURL'] = urlencode($realmpool);
				$membervars['REALM'] = $realmName;
				$membervars['REALMURL'] = urlencode($realmName);
				$membervars['CLASSID'] = $member['class'];
				$membervars['CLASS'] = $DATA["classes_$language"][$member['class']];
				$membervars['GENDERID'] = $member['gender'];
				$membervars['GENDER'] = $DATA["gender_$language"][$member['gender']];
				$membervars['CHARLEVEL'] = $member['level'];
				$membervars['NAME'] = $member['name'];
				$membervars['NAMEURL'] = urlencode($member['name']);
				$membervars['RACEID'] = $member['race'];
				$membervars['RACE'] = $DATA["races_$language"][$member['race']];
				$membervars['TEAMRANK'] = 1; //not used?
				$membervars['GUILD'] = $member['guild'];
				$membervars['GUILDURL'] = urlencode($member['guild']);
				
				$membervars['WON'] = $member['won_week'];
				$membervars['SEASONWON'] = $member['won_season'];
				$membervars['PLAYED'] = $member['played_week'];
				$membervars['SEASONPLAYED'] = $member['played_season'];
				$membervars['RATING'] = $member['personal_rating'];
				
				$vars['MEMBER'] .= fgettemplate('./xml-templates/team-info-member.xml', $membervars);
				
			}//foreach
		}//if
		
		return $vars;
		
	}//prepareArenateamSheet()
	
	
}//class Arenateam{}