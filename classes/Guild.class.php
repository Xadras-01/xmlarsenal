<?php

/**
 * Guild.class.php
 * 
 * Every guild is represented by an object of this class. It stores all attributes concerning
 * the guild and handles the preparation of guild-info.xml and guild-stats.xml variables.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/11/18
 * @package XMLArsenal
 * @subpackage classes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

class Guild{

	/**
	 * the ID used by XMLArsenal in arsenal_guilds
     * @access private
     * @var int
     */
	private $entryId;
	
	/**
	 * the guild-ID on a realm
     * @access private
     * @var int
     */
	private $guildId;
	
	/**
	 * the realm where the guild resides
     * @access private
     * @var int
     */
	private $realmId;
	
	/**
	 * the guilds name
     * @access private
     * @var string
     */
	private $guildName;
	
	/**
	 * the emblem (shown i.e. on the tabard)
     * @access private
     * @var array
     */
	private $guildEmblem;
	
	/**
	 * point in time (unix timestamp) when the last update was performed on this object
     * @access private
     * @var int
     */
	private $lastUpdate;
	
	/**
	 * membercount of the guild
     * @access private
     * @var int
     */
	private $memberCount;
	
	/**
	 * the guild member with their respective attributes (only the ones that are needed in guild info pages)
	 * stored as (int)arrayIndex=>(array)memberData
     * @access private
     * @var array
     */
	private $guildMember;
	
	
	/**
     * Constructor sets up the guild (invokes fetch- and update-functions)
	 * @access public
	 * @param string $guildName
	 * @param int $realmId
	 * @uses Guild::fetchCachedData()
	 * @uses Guild::performUpdate()
	 * @return void
    */
	public function __construct($guildName = null, $realmId=null) {
		
		if(!$guildName || !$realmId){
			
			die("No guild name or realm name given.");
			
		}
		
		$this->fetchCachedData($guildName, $realmId);
		
		//update if necessarry
		if(($this->lastUpdate + 60*60*UPDATEINTERVAL) < time()){
			
			$this->performUpdate($guildName, $realmId);
			
		}//if
		
	}//__construct()
	
	
	public function __destruct() {
		
	}//__destruct()
	
	
	/**
     * This function fetches the serialized "Guild"-object from the arsenal database (if it contains the specified object). Otherwise it invokes the {@link firstLoad()}-function
	 * @access private
	 * @param string $guildName
	 * @param int $realmId
	 * @uses Guild::firstLoad()
	 * @global array $arsenaldata
	 * @return void
    */
	private function fetchCachedData($guildName = null, $realmId = null){
		
		global $arsenaldb;
		
		//get guild data from cache-db
		$sql = 'SELECT entry_id, guild_id_on_realm, data FROM arsenal_guilds WHERE 
					guild_name COLLATE utf8_bin = '.	$arsenaldb->quote($guildName,   'text')   .' AND 
					realm_id='	.	$arsenaldb->quote($realmId,   'integer')   .' 
					LIMIT 1;';
			
		$res =& $arsenaldb->query($sql);
		
		if (PEAR::isError($res)) {
			die($res->getMessage());
		}
		
		if($res->numRows() == 0){
			
			$this->firstLoad($guildName, $realmId);
			
		}else{
			
			$arr = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
			$guildIdInTable = $arr['guild_id_on_realm'];
			$currentEntryId = $arr['entry_id'];
			$arr = unserialize($arr['data']);
			
			foreach($arr as $key=>$value){
				$this->$key = $value;
			}//foreach
			
			
			//guild has changed its id (maybe its now a new guild named like a deleted one)
			if($this->guildId != $guildIdInTable){
				
				$sql = 'DELETE FROM arsenal_guilds WHERE entry_id = '.$arsenaldb->quote($currentEntryId, 'integer').' LIMIT 1;';
				$res =& $arsenaldb->exec($sql);
				if (PEAR::isError($res)) {
					die($res->getMessage());
				}//if
				
				$this->firstLoad($guildName, $realmId);
				
			}//if
			
			
		}//else
		
	}//fetchCachedData()
	
	
	/**
     * firstLoad() fetches data from the realm-grabber the first time a guild is loaded into the arsenal and saves it to the arsenals database.
	 * @access private
	 * @param string $guildName
	 * @param int $realmId
	 * @global array $arsenaldata
	 * @global array $realms
	 * @return void
    */
	private function firstLoad($guildName = null, $realmId = null){
		
		global $realms;
		global $arsenaldb;
		
		$this->realmId = $realmId;
		$realms[$realmId]->loadDataGrabber();
		$this->aquireData($realms[$realmId]->getDataGrabber(), $guildName);
		
		$id = $arsenaldb->nextID('arsenal_guilds', true);
		$this->entryId = $id;
		$sql = 'INSERT INTO arsenal_guilds VALUES ('
					. $arsenaldb->quote($id,   'integer')   .', '
					. $arsenaldb->quote($this->guildId, 'integer')      .', '
					. $arsenaldb->quote($this->realmId, 'integer')      .', '
					. $arsenaldb->quote($this->guildName, 'text')      .', '
					. $arsenaldb->quote(serialize($this), 'text') .')';
					
		$res =& $arsenaldb->exec($sql);
		if (PEAR::isError($res)) {
			die($res->getMessage());
		}//if
		
	}//firstLoad()
	
	
	/**
     * If an update is requested by {@link __construct() the constructor} this function performs it
	 * @access private
	 * @global array $arsenaldata
	 * @global array $realms
	 * @return void
    */
	private function performUpdate($guildName, $realmId){
		
		global $realms;
		global $arsenaldb;
		
		$realms[$this->realmId]->loadDataGrabber();
		$this->aquireData($realms[$this->realmId]->getDataGrabber(), $this->guildName);
		
		$sql = 'UPDATE arsenal_guilds SET 
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
	 * @param string $guildName
	 * @param int $realmId
	 * @global obj $arsenaldata
	 * @return void
    */
	private function aquireData($dataGrabber = null, $guildName = null){
		
		global $arsenaldata;
		
		//invoke data grabbing process
		$dataGrabber->setGuildName($guildName);
		
		$this->memberCount = $dataGrabber->getMembercount();
		$this->guildMember = $dataGrabber->getMemberstatus();
		$this->guildName = $guildName;
		$this->guildId = $dataGrabber->getGuildId();
		$this->guildEmblem = $dataGrabber->getGuildEmblem();
		
		//set last update time
		$this->lastUpdate = time();
		
	}//aquireData()
	
	
	/**
     * the function prepares all variables to be filled in guild-sheet-basic.xml
	 * @access public
	 * @global $language
	 * @global $realmpool
	 * @global $realms
	 * @global $DATA
	 * @return array
    */
	public function prepareGuildSheet(){
		
		global $realms;
		global $language;
		global $realmpool;
		global $DATA;
		
		foreach($realms as $realm){
			if($realm->realmId == $this->realmId){
				$realmName = $realm->realmName;
				break;
			}//if
		}//foreach
		
		$vars = array();
		
		$vars['LANGUAGE'] = $language;
		$vars['TABURL'] = $vars['TABURL'] = "r=".urlencode($realmName)."&amp;gn=".urlencode($this->guildName);
		$vars['TABGROUP'] = 'guild';
		if($_GET['cn']){
			$vars['TABURL'] .= "&amp;cn=".urlencode($_GET['cn']);
			$vars['TABGROUP'] = 'character';
		}
		else if ($_GET['n']){
			$vars['TABURL'] .= "&amp;cn=".urlencode($_GET['n']);
			$vars['TABGROUP'] = 'character';
		}//if
		
		$vars['REALMPOOL'] = $realmpool;
		$vars['REALM'] = $realmName;
		$vars['GUILDNAME'] = $this->guildName;
		$vars['GUILDNAMEURL'] = urlencode($this->guildName);
		$vars['REALMURL'] = urlencode($realmName);
		$vars['GUILDURL'] = "r=". urlencode($realmName)."&amp;gn=".urlencode($this->guildName);
		$vars['FACTIONID'] = $DATA["factions"][$this->guildMember[0]['race']];
		$vars['MEMBERCOUNT'] = $this->memberCount;
		
		$vars['EMBLEMBG'] = $this->guildEmblem['backgroundColor'];
		$vars['EMBLEMBORDERCOL'] = $this->guildEmblem['borderColor'];
		$vars['EMBLEMBORDERSTYLE'] = $this->guildEmblem['borderStyle'];
		$vars['EMBLEMICONCOL'] = $this->guildEmblem['emblemColor'];
		$vars['EMBLEMICONSTYLE'] = $this->guildEmblem['emblemStyle'];
		
		$vars['MEMBER'] = '';
		foreach($this->guildMember as $member){
			
			$membervars = array();
			
			$membervars['CHARACHPOINTS'] = $member['achpoints'];
			$membervars['CLASSID'] = $member['class'];
			$membervars['GENDERID'] = $member['gender'];
			$membervars['CHARLEVEL'] = $member['level'];
			$membervars['NAME'] = $member['name'];
			$membervars['RACEID'] = $member['race'];
			$membervars['RANKID'] = $member['rank'];
			$membervars['CHARURL'] = "r=".urlencode($realmName)."&amp;n=".urlencode($member['name']);
			
			$vars['MEMBER'] .= fgettemplate('./xml-templates/guild-info-member.xml', $membervars);
			
		}
		
		return $vars;
		
	}//prepareGuildSheet()
	
	
	/**
     * getter for {@link $entryId}
	 * @access public
	 * @return int
    */
	public function getId(){
		
		return $this->entryId;
		
	}//getId()
	
}

?>