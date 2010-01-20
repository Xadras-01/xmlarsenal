<?php

/**
 * Search.class.php
 * 
 * The search class. Saves attributes of executed searches.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/09/06
 * @package XMLArsenal
 * @subpackage classes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/


class Search{
	
	/**
	 * the ID used by XMLArsenal in arsenal_searches
     * @access private
     * @var int
     */
	private $entryId;
	
	/**
	 * the request-string
     * @access private
     * @var int
     */
	private $queryString;
	
	/**
	 * point in time (unix timestamp) when the last update was performed on this object
     * @access private
     * @var int
     */
	private $lastUpdate;
	
	/**
	 * holds info about found chars
     * @access private
     * @var int
     */
	private $resultCharacters;
	
	/**
	 * holds info about found arenateams
     * @access private
     * @var int
     */
	private $resultTeams;
	
	/**
	 * holds info about found guilds
     * @access private
     * @var int
     */
	private $resultGuilds;
	
	
	/**
     * Constructor sets up the Search (invokes fetch- and update-functions)
	 * @access public
	 * @param string $queryString
	 * @uses Search::fetchCachedData()
	 * @uses Search::performUpdate()
	 * @return void
    */
	public function __construct($queryString = null){
		
		if(!$queryString){
			
			die("No query string given.");
			
		}//if
		
		$this->fetchCachedData($queryString);
		
		//update if necessarry
		if(($this->lastUpdate + 60*60*UPDATEINTERVAL) < time()){
			
			$this->performUpdate($queryString);
			
		}//if
		
	}
	
	/**
     * This function fetches the serialized "Search"-object from the arsenal database (if it contains the specified object). Otherwise it invokes the {@link firstLoad()}-function
	 * @access private
	 * @param string $queryString
	 * @uses Character::firstLoad()
	 * @global obj $arsenaldb
	 * @return void
    */
	private function fetchCachedData($queryString){
		
		global $arsenaldb;
		
		//get player data from cache-db
		$sql = 'SELECT entry_id, query_string, data FROM arsenal_searches WHERE 
					query_string='	.	$arsenaldb->quote($queryString,   'text')   .
					'LIMIT 1;';
			
		$res =& $arsenaldb->query($sql);
		
		if (PEAR::isError($res)) {
			die($res->getMessage());
		}
		
		if($res->numRows() == 0){
			
			$this->firstLoad($queryString);
			
		}else{
			
			$arr = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
			$currentEntryId = $arr['entry_id'];
			$arr = unserialize($arr['data']);
			
			foreach($arr as $key=>$value){
				$this->$key = $value;
			}//foreach
		}//else
		
	}//fetchCachedData()
	
	
	/**
     * firstLoad() fetches data from the realm-grabber the first time a search is performed and saves it to the arsenals database.
	 * @access private
	 * @param string $queryString
	 * @global obj $arsenaldb
	 * @global array $realms
	 * @return void
    */
	private function firstLoad($queryString = null){
		
		global $realms;
		global $arsenaldb;
		
		$this->resultCharacters = array();
		$this->resultTeams = array();
		$this->resultGuilds = array();
		
		foreach($realms as $realm){
			
			$realm->loadDataGrabber();
			$this->aquireData($realm->getDataGrabber(), $queryString, $realm->realmName);
			
		}//foreach
		
		$id = $arsenaldb->nextID('arsenal_searches', true);
		$this->entryId = $id;
		$sql = 'INSERT INTO arsenal_searches VALUES ('
					. $arsenaldb->quote($id,   'integer')   .', '
					. $arsenaldb->quote($this->queryString, 'text')      .', '
					. $arsenaldb->quote(serialize($this), 'text') .')';
					
		$res =& $arsenaldb->exec($sql);
		if (PEAR::isError($res)) {
			die($res->getMessage());
		}//if
		
	}//firstLoad()
	
	
	/**
     * If an update is requested by {@link __construct() the constructor} this function performs it
	 * @access private
	 * @param string $queryString
	 * @global obj $arsenaldb
	 * @global array $realms
	 * @return void
    */
	private function performUpdate($queryString = null){
		
		global $realms;
		global $arsenaldb;
		
		//empty last results
		$this->resultCharacters = array();
		$this->resultTeams = array();
		$this->resultGuilds = array();
		
		foreach($realms as $realm){
			
			$realm->loadDataGrabber();
			$this->aquireData($realm->getDataGrabber(), $queryString, $realm->realmName);
			
		}//foreach
		
		$sql = 'UPDATE arsenal_searches SET 
					data = ' . $arsenaldb->quote(serialize($this), 'text') .'
					WHERE entry_id = '. $arsenaldb->quote($this->entryId, 'integer') . '
					LIMIT 1';
			
		$res =& $arsenaldb->exec($sql);
		if (PEAR::isError($res)) {
			die($res->getMessage());
		}//if
		
	}//performUpdate()
	
	
	/**
     * this is the function that really invokes the datagrabbers functions
	 * @access private
	 * @param string $charName
	 * @param int $realmId
	 * @global obj $arsenaldata
	 * @return void
    */
	private function aquireData($dataGrabber = null, $queryString = null, $realmName = null){
		
		global $arsenaldata;
		
		$this->queryString = $queryString;
		
		//invoke data grabbing process
		$dataGrabber->setSearchString($queryString);
		
		//get chars
		$chars = $dataGrabber->getSearchCharactersResults($realmName);
		if(!empty($chars)){
			
			foreach($chars as $char){
				
				$char['relevance'] = $this->getRelevance('char', $char, $queryString);
				if($char['relevance'] > 0) array_push($this->resultCharacters, $char);
				
			}//foreach
		}//if
		
		//get arenateams
		$teams = $dataGrabber->getSearchTeamsResults($realmName);
		if(!empty($teams)){
			
			foreach($teams as $team){
				
				$team['relevance'] = $this->getRelevance('arenateam', $team, $queryString);
				if($team['relevance'] > 0) array_push($this->resultTeams, $team);
				
			}//foreach
		}//if
		
		//get guilds
		$guilds = $dataGrabber->getSearchGuildsResults($realmName);
		if(!empty($guilds)){
			
			foreach($guilds as $guild){
				
				$guild['relevance'] = $this->getRelevance('guild', $guild, $queryString);
				if($guild['relevance'] > 0) array_push($this->resultGuilds, $guild);
				
			}//foreach
		}//if
		
		//sorting the arrays by their relevance
		usort($this->resultCharacters, array($this, 'sortByRelevance'));
		usort($this->resultGuilds, array($this, 'sortByRelevance'));
		usort($this->resultTeams, array($this, 'sortByRelevance'));
		
		//set last update time
		$this->lastUpdate = time();
		
	}//aquireData()
	
	
	/**
     * this is a callback function for sorting the result arrays by their relevance
	 * @access private
	 * @param array $a
	 * @param array $b
	 * @return int
    */
	private function sortByRelevance($a, $b){
		
		if($a['relevance'] == $b['relevance']){
			
			if($a['level'] && $b['level']){
				
				if($a['level'] > $b['level']) return -1;
				if($a['level'] < $b['level']) return 1;
				
			}
			
			return 0;
		}
		else if($a['relevance'] > $b['relevance']) return -1;
		else return 1;
		
	}//sortByRelevance()
	
	
	/**
     * calculates the relevance of a found object according to the search-string
	 * @access private
	 * @param string $type
	 * @param array $b
	 * @param string $queryString
	 * @return int
    */
	private function getRelevance($type = null, $obj = null, $queryString = null){
		
		$lvRelevanceFactor = 10;
		
		$relevance = 0;
		if($type == 'char'){
			
			if(strlen($queryString) < 3) $lvRelevanceFactor *= 5;
			else if(strlen($queryString) < 4) $lvRelevanceFactor *= 4;
			else if(strlen($queryString) < 5) $lvRelevanceFactor *= 2;
			
			$relevance = 100 - ($lvRelevanceFactor * (levenshtein(strtolower($queryString), strtolower($obj['name']), 1, 2, 2))) + round($obj['level'] / 5);
			
		}//if
		
		if($type == 'arenateam'){
			$relevance = 100 - ($lvRelevanceFactor * (levenshtein(strtolower($queryString), strtolower($obj['name']), 1, 2, 2)));
		}//if
		
		if($type == 'guild'){
			$relevance = 100 - ($lvRelevanceFactor * (levenshtein(strtolower($queryString), strtolower($obj['name']), 1, 2, 2)));
		}//if
		
		//only precautions^^
		if($relevance < 0) 		$relevance = 0;
		if($relevance > 100) 	$relevance = 100;
		
		return $relevance;
		
	}//getRelevance()
	
	
	/**
     * getter for {@link $entryId}
	 * @access public
	 * @return int
    */
	public function getId(){
		
		return $this->entryId;
		
	}//getId()
	
	
	/**
     * returns the number of results for each category
	 * @access public
	 * @return int
    */
	public function getResultCount(){
		
		return array(	'characters' => count($this->resultCharacters),
						'guilds' => count($this->resultGuilds),
						'arenateams' => count($this->resultTeams)
					);
	}//getCount()
	
	
	/**
     * returns an array with variables used to fill the template search-arenateamTab.xml
	 * @access public
	 * @global array $DATA
	 * @global string $language;
	 * @global string $realmpool;
	 * @return array
    */
	public function prepareArenateamTab(){
		
		global $language;
		global $realmpool;
		global $DATA;
		
		$vars = array();
		$vars['LANGUAGE'] = $language;
		$vars['QUERYSTRING'] = urlencode($this->queryString);
		$vars['COUNTCHARS'] = count($this->resultCharacters);
		$vars['COUNTTEAMS'] = count($this->resultTeams);
		$vars['COUNTGUILDS'] = count($this->resultGuilds);
		$vars['COUNTALL'] = $vars['COUNTCHARS'] + $vars['COUNTTEAMS'] + $vars['COUNTGUILDS'];
		$vars['SEARCHTEXT'] = $this->queryString;
		
		if(!empty($this->resultTeams)){
			
			$vars['TEAMRESULTS'] = '';
			foreach($this->resultTeams as $resultPos => $team){
				
				$teamvars = array();
				$teamvars['REALMPOOL'] = $realmpool;
				$teamvars['REALMPOOLURL'] = urlencode($realmpool);
				$teamvars['REALM'] = $team['realmName'];
				$teamvars['REALMURL'] = urlencode($team['realmName']);
				$teamvars['FACTIONID'] = $DATA["factions"][$team['leaderRace']];
				$teamvars['FACTION'] = $DATA["factionNames_$language"][$team['leaderRace']];
				$teamvars['NAME'] = $team['name'];
				$teamvars['TEAMSIZE'] = $team['teamSize'];
				$teamvars['RANKING'] = $team['ranking'];
				$teamvars['RATING'] = $team['rating'];
				$teamvars['MEMBERCOUNT'] = 1; //not used?
				$teamvars['TEAMNAMEURL'] = urlencode($team['name']);
				$teamvars['PLAYED'] = $team['gamesPlayed'];
				$teamvars['WON'] = $team['gamesWon'];
				$teamvars['SEASONPLAYED'] = $team['seasonGamesPlayed'];
				$teamvars['SEASONWON'] = $team['seasonGamesWon'];
				$teamvars['RELEVANCE'] = $team['relevance'];
				
				$teamvars['EMBLEMBGCOLOR'] = $team['background'];
				$teamvars['EMBLEMBORDERCOLOR'] = $team['borderColor'];
				$teamvars['EMBLEMBORDERSTYLE'] = $team['borderStyle'];
				$teamvars['EMBLEMICONCOLOR'] = $team['iconColor'];
				$teamvars['EMBLEMICON'] = $team['iconStyle'];
				
				$vars['TEAMRESULTS'] .= fgettemplate('./xml-templates/search-arenateamTab-team.xml', $teamvars);
				
			}//foreach
		}//if
		
		return $vars;
		
	}//prepareArenateamTab()
	
	
	/**
     * returns an array with variables used to fill the template search-characterTab.xml
	 * @access public
	 * @global array $DATA
	 * @global string $language;
	 * @global string $realmpool;
	 * @return array
    */
	public function prepareCharacterTab(){
		
		global $language;
		global $realmpool;
		global $DATA;
		
		$vars = array();
		$vars['LANGUAGE'] = $language;
		$vars['QUERYSTRING'] = urlencode($this->queryString);
		$vars['COUNTCHARS'] = count($this->resultCharacters);
		$vars['COUNTTEAMS'] = count($this->resultTeams);
		$vars['COUNTGUILDS'] = count($this->resultGuilds);
		$vars['SEARCHTEXT'] = $this->queryString;
		$vars['COUNTALL'] = $vars['COUNTCHARS'] + $vars['COUNTTEAMS'] + $vars['COUNTGUILDS'];
		
		if(!empty($this->resultCharacters)){
			
			$vars['CHARRESULTS'] = '';
			foreach($this->resultCharacters as $resultPos => $char){
				
				$charvars = array();
				$charvars['NAME'] = $char['name'];
				$charvars['RACEID'] = $char['raceId'];
				$charvars['RACE'] = $DATA["races_$language"][$char['raceId']];
				$charvars['FACTIONID'] = $DATA["factions"][$char['raceId']];
				$charvars['FACTION'] = $DATA["factionNames_$language"][$char['raceId']];
				$charvars['LEVEL'] = $char['level'];
				$charvars['RELEVANCE'] = $char['relevance'];
				$charvars['SEARCHPOS'] = $resultPos + 1;
				$charvars['CLASSID'] = $char['classId'];
				$charvars['CLASS'] = $DATA["classes_$language"][$char['classId']];
				$charvars['GUILDID'] = 1; //not used?
				$charvars['CHARURL'] = 'r='.urlencode($char['realmName']).'&amp;n='.urlencode($char['name']);
				$charvars['GUILD'] = $char['guild'];
				$charvars['GENDERID'] = $char['genderId'];
				$charvars['GENDER'] = $DATA["gender_$language"][$char['genderId']];
				$charvars['REALMPOOL'] = $realmpool;
				$charvars['REALM'] = $char['realmName'];
				
				$vars['CHARRESULTS'] .= fgettemplate('./xml-templates/search-characterTab-character.xml', $charvars);
				
			}//foreach
		}//if
		
		return $vars;
		
	}//prepareCharacterTab()
	
	
	/**
     * returns an array with variables used to fill the template search-guildTab.xml
	 * @access public
	 * @global array $DATA
	 * @global string $language;
	 * @global string $realmpool;
	 * @return array
    */
	public function prepareGuildTab(){
		
		global $language;
		global $realmpool;
		global $DATA;
		
		$vars = array();
		$vars['LANGUAGE'] = $language;
		$vars['QUERYSTRING'] = urlencode($this->queryString);
		$vars['COUNTCHARS'] = count($this->resultCharacters);
		$vars['COUNTTEAMS'] = count($this->resultTeams);
		$vars['COUNTGUILDS'] = count($this->resultGuilds);
		$vars['SEARCHTEXT'] = $this->queryString;
		$vars['COUNTALL'] = $vars['COUNTCHARS'] + $vars['COUNTTEAMS'] + $vars['COUNTGUILDS'];
		
		if(!empty($this->resultGuilds)){
			
			$vars['GUILDRESULTS'] = '';
			foreach($this->resultGuilds as $resultPos => $guild){
				
				$guildvars = array();
				$guildvars['NAME'] = $guild['name'];
				$guildvars['GUILDURL'] = urlencode($guild['name']);
				$guildvars['FACTIONID'] = $DATA["factions"][$guild['leaderRace']];
				$guildvars['FACTION'] = $DATA["factionNames_$language"][$guild['leaderRace']];
				$guildvars['RELEVANCE'] = $guild['relevance'];
				$guildvars['REALMPOOL'] = $realmpool;
				$guildvars['REALM'] = $guild['realmName'];
				$guildvars['REALMURL'] = urlencode($guild['realmName']);
				
				$guildvars['EMBLEMBGCOLOR'] = $guild['emblemBackground'];
				$guildvars['EMBLEMBORDERCOLOR'] = $guild['emblemBorderColor'];
				$guildvars['EMBLEMBORDERSTYLE'] = $guild['emblemBorderStyle'];
				$guildvars['EMBLEMICONCOLOR'] = $guild['emblemIconStyle'];
				$guildvars['EMBLEMICON'] = $guild['iconStyle'];
				
				$vars['GUILDRESULTS'] .= fgettemplate('./xml-templates/search-guildTab-guild.xml', $guildvars);
				
			}//foreach
		}//if
		
		
		return $vars;
		
	}//prepareGuildTab()
	
}//class

?>