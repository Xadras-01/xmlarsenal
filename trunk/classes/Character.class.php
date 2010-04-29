<?php

/**
 * Character.class.php
 * 
 * This class is the central class of the XMLArsenal. It stores all attributes concerning
 * players characters and provides the characters database persistence layer.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/12/11
 * @package XMLArsenal
 * @subpackage classes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

class Character{
	
	/**
	 * the ID used by XMLArsenal in arsenal_characters
     * @access private
     * @var int
     */
	private $entryId;
	
	/**
	 * the character-ID on a realm
     * @access private
     * @var int
     */
	private $charId;
	
	/**
	 * the realm where the char resides
     * @access private
     * @var int
     */
	private $realmId;
	
	/**
	 * the characters name
     * @access private
     * @var string
     */
	private $charName;
	
	/**
	 * the characters level
     * @access private
     * @var int
     */
	private $level;
	
	/**
	 * the characters gender (0=male, 1=female)
     * @access private
     * @var int
     */
	private $gender;
	
	/**
	 * the characters race
     * @access private
     * @var int
     */
	private $race;
	
	/**
	 * the characters class
     * @access private
     * @var int
     */
	private $class;
	
	/**
	 * the characters professions
	 * stored as (int)professionid=>(int)skillvalue
     * @access private
     * @var array
     */
	private $professions;
	
	/**
	 * the characters reputation with different factions
	 * stored as (int)faction=>(int)rep-value
     * @access private
     * @var array
     */
	private $reputation;			//array of type 	(int)faction=>(int)rep-value
	
	/**
	 * the characters arena currency
	 * stored as (int)faction=>(int)rep-value
     * @access private
     * @var array
     */
	private $arenaPoints;
	
	/**
	 * the characters achievements
	 * stored as (int)achievementId=>(int)timestamp
     * @access private
     * @var array
     */
	private $achievements;
	
	/**
	 * the characters achievement-points, this is calculated by going through {@link $achievements}
     * @access private
     * @var int
     */
	private $achievementPoints;
	
	/**
	 * the characters achievement-criteria (non-finished achievements)
	 * stored as (int)criteriaId=>array((int)counter, (int)timestamp))
     * @access private
     * @var array
     */
	private $achievementCriteria;
	
	/**
	 * the characters guild-id (if he/she has one)
     * @access private
     * @var int
     */
	private $guildId;
	
	/**
	 * the characters guild name (if he/she has one)
     * @access private
     * @var string
     */
	private $guildName;
	
	/**
	 * the characters guild URI (if he/she has one)
     * @access private
     * @var string
     */
	private $guildURL;
	
	/**
	 * the characters 1st talenttree, i.e. 5033203130320512031023201051205551000020000000000000000000000000000000000000000000
     * @access private
     * @var string
     */
	private $talents1;
	
	/**
	 * the characters 2nd talenttree (dualspec)
     * @access private
     * @var string
     */
	private $talents2;
	
	/**
	 * number of talent points in each tree for spec1
     * @access private
     * @var array
     */
	private $numTalents1;
	
	/**
	 * number of talent points in each tree for spec2
     * @access private
     * @var array
     */
	private $numTalents2;
	
	/**
	 * the characters 1st glyph-set (in combination with $talents1)
     * @access private
     * @var array
     */
	private $glyphs1;
	
	/**
	 * the characters 2nd glyph-set (in combination with $talents2)
     * @access private
     * @var array
     */
	private $glyphs2;
	
	/**
	 * stores which spec is active at themoment
     * @access private
     * @var int
     */
	private $activeSpec;
	
	/**
	 * Num. of the characters kills that were honorable
     * @access private
     * @var int
     */
	private $honorableKills;
	
	/**
	 * the characters titles
	 * stored as (int)arrayindex=>(string)titlename
     * @access private
     * @var array
     */
	private $titles;
	
	/**
	 * title, the one the player has selected to be displayed
     * @access private
     * @var string
     */
	private $chosenTitle;
	
	/**
	 * the characters statistical values (i.e. strength, stamina, resistances etc.)
     * @access private
     * @var array
     */
	private $charStats;
	
	/**
	 * the characters statmods 
	 * stored as (int)arrayindex=>array(0=>enchantType, 1=>stattype, 2=>amount)
     * @access private
     * @var array
     */
	private $statModifiers;
	
	/**
	 * the characters items
	 * stored as (int)slotIndex=>(Item)slotItem
     * @access private
     * @var array
     */
	private $items;
	
	/**
	 * the characters arenateams (if any)
	 * stored as (int)arenateamId=>(string)arenateamName
     * @access private
     * @var array
     */
	private $arenaTeams;
	
	/**
	 * point in time (unix timestamp) when the last update was performed on this object
     * @access private
     * @var int
     */
	private $lastUpdate;
	
	
	/**
     * Constructor sets up the character (invokes fetch- and update-functions)
	 * @access public
	 * @param string $charName
	 * @param int $realmId
	 * @global $md5
	 * @uses Character::fetchCachedData()
	 * @uses Character::performUpdate()
	 * @return void
    */
	public function __construct($charName = null, $realmId=null) {
		
		global $md5;
		
		if(!$charName || !$realmId){
			
			die("No player name or realm name given.");
			
		}//if
		
		$this->fetchCachedData($charName, $realmId);
		
		//update if necessarry
		if(($this->lastUpdate + 60*60*UPDATEINTERVAL) < time()){
			
			$this->performUpdate($charName, $realmId);
			
		}//if
		
		$md5 = md5(serialize($this));
		
	}//__construct()
	
	
	/**
     * Destructor checks wether someting has changed. If yes put it to the db.
	 * @access public
	 * @global $md5
	 * @global $arsenaldb;
	 * @return void
    */
	public function __destruct() {
		
		global $md5;
		global $arsenaldb;
		
		if(md5(serialize($this)) != $md5){
			
			$sql = 'UPDATE arsenal_characters SET 
						data = ' . $arsenaldb->quote(serialize($this), 'text') .'
						WHERE entry_id = '. $arsenaldb->quote($this->entryId, 'integer') . '
						LIMIT 1';
				
			$res =& $arsenaldb->exec($sql);
			if (PEAR::isError($res)) {
				die($res->getUserinfo());
			}//if
		}//if
		
	}//__destruct()
	
	
	/**
     * This function fetches the serialized "Character"-object from the arsenal database (if it contains the specified object). Otherwise it invokes the {@link firstLoad()}-function
	 * @access private
	 * @param string $charName
	 * @param int $realmId
	 * @uses Character::firstLoad()
	 * @global obj $arsenaldb
	 * @return void
    */
	private function fetchCachedData($charName = null, $realmId = null){
		
		global $arsenaldb;
		
		//get player data from cache-db
		$sql = 'SELECT entry_id, char_id_on_realm, data FROM arsenal_characters WHERE 
					char_name COLLATE utf8_bin = '	.	$arsenaldb->quote($charName,   'text')   .' AND 
					realm_id='	.	$arsenaldb->quote($realmId,   'integer')   .' 
					LIMIT 1;';
			
		$res =& $arsenaldb->query($sql);
		
		if (PEAR::isError($res)) {
			die($res->getUserinfo());
		}
		
		if($res->numRows() == 0){
			
			$this->firstLoad($charName, $realmId);
			
		}else{
			
			$arr = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
			$charIdInTable = $arr['char_id_on_realm'];
			$currentEntryId = $arr['entry_id'];
			$arr = unserialize($arr['data']);
			
			foreach($arr as $key=>$value){
				$this->$key = $value;
			}//foreach
			
			//char has changed its id (maybe its now a new char named like a deleted one)
			if($this->charId != $charIdInTable){
				
				$sql = 'DELETE FROM arsenal_characters WHERE entry_id = '.$arsenaldb->quote($currentEntryId, 'integer').' LIMIT 1;';
				$res =& $arsenaldb->exec($sql);
				if (PEAR::isError($res)) {
					die($res->getUserinfo());
				}//if
				
				$this->firstLoad($charName, $realmId);
				
			}//if
			
			
		}//else
		
	}//fetchCachedData()
	
	
	/**
     * firstLoad() fetches data from the realm-grabber the first time a char is loaded into the arsenal and saves it to the arsenals database.
	 * @access private
	 * @param string $charName
	 * @param int $realmId
	 * @global array $arsenaldata
	 * @global array $realms
	 * @return void
    */
	private function firstLoad($charName = null, $realmId = null){
		
		global $realms;
		global $arsenaldb;
		
		$this->realmId = $realmId;
		$realms[$realmId]->loadDataGrabber();
		$this->aquireData($realms[$realmId]->getDataGrabber(), $charName);
		
		$id = $arsenaldb->nextID('arsenal_characters', true);
		$this->entryId = $id;
		$sql = 'INSERT INTO arsenal_characters VALUES ('
					. $arsenaldb->quote($id,   'integer')   .', '
					. $arsenaldb->quote($this->charId, 'integer')      .', '
					. $arsenaldb->quote($this->realmId, 'integer')      .', '
					. $arsenaldb->quote($this->charName, 'text')      .', '
					. $arsenaldb->quote(serialize($this), 'text') .')';
			
		$res =& $arsenaldb->exec($sql);
		if (PEAR::isError($res)) {
			die($res->getUserinfo());
		}//if
		
	}//firstLoad()
	
	
	/**
     * If an update is requested by {@link __construct() the constructor} this function performs it
	 * @access private
	 * @param string $charName
	 * @param int $realmId
	 * @global array $arsenaldata
	 * @global array $realms
	 * @return void
    */
	private function performUpdate($charName, $realmId){
		
		global $realms;
		global $arsenaldb;
		
		$realms[$this->realmId]->loadDataGrabber();
		$this->aquireData($realms[$this->realmId]->getDataGrabber(), $this->charName);
		
		$sql = 'UPDATE arsenal_characters SET 
					data = ' . $arsenaldb->quote(serialize($this), 'text') .'
					WHERE entry_id = '. $arsenaldb->quote($this->entryId, 'integer') . '
					LIMIT 1';
			
		$res =& $arsenaldb->exec($sql);
		if (PEAR::isError($res)) {
			die($res->getUserinfo());
		}//if
		
	}//performUpdate()
	
	
	/**
     * this is the function that really invokes the datagrabbers functions and calculates the values that are not read from the grabber
	 * @access private
	 * @param string $charName
	 * @param obj $dataGrabber
	 * @global obj $arsenaldata
	 * @return void
    */
	private function aquireData($dataGrabber = null, $charName = null){
		
		global $arsenaldata;
		
		//invoke data grabbing process
		$dataGrabber->setCharName($charName);
		
		$this->charId = $dataGrabber->getCharId();
		if(!$this->charId) die("No such char.");
		$this->charName = $charName;
		$this->level = $dataGrabber->getLevel();
		$this->gender = $dataGrabber->getGender();
		$this->race = $dataGrabber->getRace();
		$this->class = $dataGrabber->getClass();
		$this->chosenTitle = $dataGrabber->getChosenTitleMask();
		
		//basic stats & values
		$stats = $dataGrabber->getCharStats();
		$stats = array_merge($stats, $dataGrabber->getResistances());
		$this->charStats = $stats;
		
		//arena teams
		$teams = $dataGrabber->getArenaTeamsIdByPlayer();
		$this->arenaTeams = array();
		if(!empty($teams)){
			
			foreach($teams as $team){
				
				$dataGrabber->setArenaTeamName($team['name']);
				$teamData = $dataGrabber->getArenaTeamproperties();
				$teamData['teamMember'] = $dataGrabber->getArenaTeammember();
				
				$this->arenaTeams[$teamData['teamSize']] = $teamData;
				
			}//foreach
			
		}//if
		
		//reputation
		$this->reputation = $dataGrabber->getReputation();
		
		//pvp
		$this->arenaPoints = $dataGrabber->getArenaPoints();
		$this->honorableKills = $dataGrabber->getHonorableKills();
		
		//professions
		$this->professions = $dataGrabber->getPrimaryProfessions();
		
		//guild
		$this->guildId = $dataGrabber->getGuildIdplayer();
		$this->guildName = $dataGrabber->getGuildNameplayer();
		$this->guildURL = urlencode($this->guildName);
		
		//achievements
		$this->achievements = $dataGrabber->getAchievements();
		$this->achievementCriteria = $dataGrabber->getAchievementCriteria();
		$this->achievementPoints = 0;
		
		$qry = "SELECT * FROM achievements;";
		$stmt =& $arsenaldata->query($qry);
		while($res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC)){
			
			//add points
			if(!empty($this->achievements)){
				if(array_key_exists($res['achievementid'], $this->achievements)) $this->achievementPoints += $res['achievementpoints'];
			}//if
			
		}//while
		
		//get talents
		$this->talents1 = $this->talents2 = '';
		$tal_spells = $dataGrabber->getTalents();
		$this->numTalents1 = $this->numTalents2 = array(1=>0, 2=>0, 3=>0);
		$this->activeSpec = $dataGrabber->getActiveSpec();
		
		//spec1
		if(!empty($tal_spells[0]))	$this->parseTalents($tal_spells[0], 1);
		
		//spec2
		if(!empty($tal_spells[1]))	$this->parseTalents($tal_spells[1], 2);
		
		//fix for chars < Level 10 (which have no talents)
		if($this->talents1 == '') $this->talents1 = '0000000000000000000000000000000000000000000000000000000000000000000000000000000000';
		
		
		//get glyphs
		$glyphs = $dataGrabber->getGlyphs();
		if(!empty($glyphs[0]))	$this->glyphs1 = $glyphs[0];
		else $this->glyphs1 = array();
		
		if(!empty($glyphs[1]))	$this->glyphs2 = $glyphs[1];
		else $this->glyphs2 = array();
		
		
		//the items
		$this->items = array();
		$items = $dataGrabber->getItems();
		foreach($items as $slot=>$itemarr){
			$this->items[$slot] = new Item($slot, $itemarr);
		}
		
		//set last update time
		$this->lastUpdate = time();
		
		
	}//aquireData()
	
	
	/**
     * getter for {@link $charId}
	 * @access public
	 * @return int
    */
	public function getId(){
		
		return $this->charId;
		
	}//getId()
	
	
	/**
     * getter for {@link $race}
	 * @access public
	 * @return int
    */
	public function getRace(){
		
		return $this->race;
		
	}//getId()
	
	
	/**
     * returns an array with variables used to fill the template character-sheet.xml
	 * @access public
	 * @global array $DATA
	 * @global obj $arsenaldata;
	 * @global string $arsenaldataLang;
	 * @global string $realmpool;
	 * @global string $language;
	 * @global array $realms;
	 * @return array
    */
	public function prepareCharacterSheet(){
		
		global $DATA;
		global $arsenaldata;
		global $arsenaldataLang;
		global $realms;
		global $language;
		global $realmpool;
		
		foreach($realms as $realm){
			if($realm->realmId == $this->realmId){
				$realmName = $realm->realmName;
				break;
			}//if
		}//foreach
		
		//vars used in many templates
		$vars = $this->prepareBasicTemplateVars();
		$vars['LIFETIMEHONORABLEKILLS'] = $this->honorableKills;
		$vars['ARENACURRENCY'] = $this->arenaPoints;
		
		//stats
		$vars['STAT_POWER_TYPE'] = $DATA['etypes'][$this->class];
		if ($DATA['etypes'][$this->class] == 'r') {		
			$vars['STAT_POWER_AMOUNT'] =  $this->charStats['rage'];
		}elseif ($DATA['etypes'][$this->class] == 'e') {		
			$vars['STAT_POWER_AMOUNT'] =  $this->charStats['energy'];
		}elseif ($DATA['etypes'][$this->class] == 'r') {			
			$vars['STAT_POWER_AMOUNT'] =  $this->charStats['rune'];
		}else {			
			$vars['STAT_POWER_AMOUNT'] =  $this->charStats['mana'];
		}
		
		$vars['STAT_HEALTH'] = $this->charStats['health'];
		$vars['STAT_ARMOR'] =  $this->charStats['armor'];
		
		$vars['STAT_STRENGTH'] =  $this->charStats['strength'];
		$vars['STAT_AGILITY'] =  $this->charStats['agility'];
        $vars['STAT_STAMINA'] =  $this->charStats['stamina'];
        $vars['STAT_INTELLECT'] =  $this->charStats['int'];
        $vars['STAT_SPIRIT'] =  $this->charStats['spirit'];
		
		$vars['STAT_STRENGTH_BASE'] = $this->charStats['strength'] - $this->charStats['strengthPos'] + $this->charStats['strengthNeg'];
		$vars['STAT_AGILITY_BASE'] = $this->charStats['agility'] - $this->charStats['agilityPos'] + $this->charStats['agilityNeg'];
		$vars['STAT_STAMINA_BASE'] = $this->charStats['stamina'] - $this->charStats['staminaPos'] + $this->charStats['staminaNeg'];
		$vars['STAT_INTELLECT_BASE'] = $this->charStats['int'] - $this->charStats['intPos'] + $this->charStats['intNeg'];
		$vars['STAT_SPIRIT_BASE'] = $this->charStats['spirit'] - $this->charStats['spiritPos'] + $this->charStats['spiritNeg'];
		
		$vars['STAT_RES_ARCANE'] = $this->charStats['arcaneRes'];
		$vars['STAT_RES_HOLY'] = $this->charStats['holyRes'];
		$vars['STAT_RES_FIRE'] = $this->charStats['fireRes'];
		$vars['STAT_RES_FROST'] = $this->charStats['frostRes'];
		$vars['STAT_RES_NATURE'] = $this->charStats['natureRes'];
		$vars['STAT_RES_SHADOW'] = $this->charStats['shadowRes'];
		
		//Warriors, death knights, druids in all feral forms, and paladins gain 2 melee attack power per point of strength. 
		//Rogues, hunters, shamans, mages, priests, warlocks and druids in druid form gain 1 melee attack power per point of strength. 
		$vars['STAT_STRENGTH_ATTACK_POWER'] = (in_array($this->class, array(1, 2, 6, 11))) ? ($this->charStats['strength'] * 2) : ($this->charStats['strength']);
		//Improves block value by (Strength / 2), rounding down, for all classes capable of blocking (warrior, paladin, and shaman).
		$vars['STAT_STRENGTH_BLOCK_VALUE'] = (in_array($this->class, array(1, 2, 7))) ? (round($this->charStats['strength'] / 2)) : 0;
		
		//Increases armor by 2 per point. 
		$vars['STAT_AGILITY_ARMOR'] = $this->charStats['agility'] * 2;
		//Increases attack power with ranged weapons (not including wands) or melee weapons for certain classes. 
		//Warriors, hunters[1] and rogues gain 1 ranged attack power for each point of agility. 
		//Hunters,[2] rogues, shamans (as of 3.0.2) and druids in Cat Form[3] gain 1 melee attack power per point of agility. 
		$vars['STAT_AGILITY_ATTACK_POWER'] = $this->charStats['agility'];
		
		//Warriors: For Warriors tanking in a 5-man party: 1 Agility = 2 Strength = .33 Stamina = .25% Crit. = .07% Dodge = .07% Parry = 4 Attack Power = 10 Armor = 0.25 DPS = 4 Shield Block = 1 Weapon Skill = 4 Health bonus 
		//Hunters: Formula: 1 Agility = .552 Crit Rating = 1 Attack Power
		//Rogues: Formula: 1 Agility = 1 Stamina = 2 Strength = .1% Crit. = .2% Dodge = .13% Parry = .13% To Hit = 2 Attack Power = 5 Daggers = 4 Any Resistance = 5 Health/5 Sec. = 50 Armor
		$vars['STAT_AGILITY_CRIT_HIT_PERCENT'] = 0;
		if($this->class == 1) $vars['STAT_AGILITY_CRIT_HIT_PERCENT'] = round(($this->charStats['agility'] * 0.250), 2);
		if($this->class == 3) $vars['STAT_AGILITY_CRIT_HIT_PERCENT'] = round(($this->charStats['agility'] * 0.552), 2);
		if($this->class == 4) $vars['STAT_AGILITY_CRIT_HIT_PERCENT'] = round(($this->charStats['agility'] * 0.100), 2);
		
		//Stamina provides 10 health for each point for all Classes (except from the first 20 points of Stamina that provide 1 health for each point instead). 
		$vars['STAT_STAMINA_HEALTH_BONUS'] = ($this->charStats['stamina'] <= 20) ? ($this->charStats['stamina']) : ((($this->charStats['stamina'] - 20) * 10) + 20);
		
		//Increases mana points. Each point of intellect gives player characters 15 mana points (except from the first 20 points of Intellect that provide 1 mana for each point instead).
		$vars['STAT_INTELLECT_MANA_BONUS'] = ($this->charStats['stamina'] <= 20) ? ($this->charStats['stamina']) : ((($this->charStats['stamina'] - 20) * 15) + 20);
		//not found yet
		$vars['STAT_INTELLECT_CRIT_HIT_PERCENT'] = 0;
		
		//Every two seconds, there is one tick of health regeneration, the value of depending on class and spirit
		if($this->class == 1) $vars['STAT_SPIRIT_HEALTH_REGEN'] = round(($this->charStats['spirit'] * 0.50) + 6.5);
		if($this->class == 2) $vars['STAT_SPIRIT_HEALTH_REGEN'] = round(($this->charStats['spirit'] * 0.25) + 6.0);
		if($this->class == 3) $vars['STAT_SPIRIT_HEALTH_REGEN'] = round(($this->charStats['spirit'] * 0.25) + 6.0);
		if($this->class == 4) $vars['STAT_SPIRIT_HEALTH_REGEN'] = round(($this->charStats['spirit'] * 0.50) + 2.0);
		if($this->class == 5) $vars['STAT_SPIRIT_HEALTH_REGEN'] = round(($this->charStats['spirit'] * 0.10) + 6.0);
		if($this->class == 6) $vars['STAT_SPIRIT_HEALTH_REGEN'] = round(($this->charStats['spirit'] * 0.50) + 6.0);
		if($this->class == 7) $vars['STAT_SPIRIT_HEALTH_REGEN'] = round(($this->charStats['spirit'] * 0.11) + 7.0);
		if($this->class == 8) $vars['STAT_SPIRIT_HEALTH_REGEN'] = round(($this->charStats['spirit'] * 0.10) + 6.0);
		if($this->class == 9) $vars['STAT_SPIRIT_HEALTH_REGEN'] = round(($this->charStats['spirit'] * 0.07) + 6.0);
		if($this->class == 11) $vars['STAT_SPIRIT_HEALTH_REGEN'] = round(($this->charStats['spirit'] * 0.09) + 6.5);
		//The main base stat which regenerates mana is spirit. To a lesser extent, intellect also contributes, and the character level adversely affects regeneration.
		//The mana regeneration formula is uniform across all classes
		$vars['STAT_SPIRIT_MANA_REGEN'] = round(5 * (0.001 + sqrt($this->charStats['int']) * $this->charStats['spirit'] * $DATA['spiritBaseRegen'][$this->level]) * 0.60);
		$vars['STAT_POWER_REGEN_CASTING'] = $vars['STAT_SPIRIT_MANA_REGEN'];
		$vars['STAT_POWER_REGEN_NOTCASTING'] = $vars['STAT_SPIRIT_MANA_REGEN'];
		
		//Armor Damage Reduction Formula 
		if($this->level < 60)	$vars['STAT_ARMOR_PERCENT'] = round(($this->charStats['armor'] / ($this->charStats['armor'] + 400 + 85 * $this->level)) * 100, 2);
		else					$vars['STAT_ARMOR_PERCENT'] = round(($this->charStats['armor'] / ($this->charStats['armor'] + 400 + 85 * ($this->level + 4.5 * ($this->level - 59)))) * 100, 2);
		
		$vars['STAT_ATTACK_POWER'] = $this->charStats['mAtp'] + $this->charStats['mAtpMod'];
		$vars['STAT_ATTACK_POWER_DPS'] = 0;
		
		$vars['STAT_DODGE'] = $this->charStats['dodgeRating'];
		$vars['STAT_DODGE_PERCENT'] = $this->charStats['dodgePercent'];
		$vars['STAT_PARRY'] = $this->charStats['parryRating'];
		$vars['STAT_PARRY_PERCENT'] = $this->charStats['parryPercent'];
		$vars['STAT_BLOCK'] = $this->charStats['blockRating'];
		$vars['STAT_BLOCK_PERCENT'] = $this->charStats['blockPercent'];
		$vars['STAT_RESILIENCE'] = $this->charStats['resilience'];
		$vars['STAT_RESILIENCE_PERCENT'] = $this->charStats['resiliencePercent'];
		$vars['STAT_DEFENSE_RATING'] = $this->charStats['defense'];
		$vars['STAT_DEFENSE_RATING_ITEMBONI'] = 0;
		$vars['STAT_DEFENSE_DECREASE_HIT'] = 0.04 * $this->charStats['defense'];
		$vars['STAT_DEFENSE_INCREASE_BLOCK'] = 0.04 * $this->charStats['defense'];
		$vars['STAT_RESILIENCE'] = 0;
		
		//hit ratings (hit is now the same for spell and physical dmg)
		$vars['STAT_HIT_RATING'] = $this->charStats['spHitRating'];
		$vars['STAT_HIT_INCREASE_PERCENT'] = 0;
		$vars['STAT_HIT_PENETRATION'] = 0;
		$vars['STAT_HIT_DECREASE_ARMOR_PERCENT'] = 0;
		$vars['STAT_HIT_DECREASE_RESIST'] = 0;
		
		//melee crit
		$vars['STAT_MELEE_CRIT_RATING'] = $this->charStats['mCritRating'];
		$vars['STAT_MELEE_CRIT_PERCENT'] = $this->charStats['mCritPercent'];
		$vars['STAT_MELEE_CRIT_POS_PERCENT'] = 0;
		$vars['STAT_RANGED_CRIT_RATING'] = $this->charStats['rCritRating'];
		$vars['STAT_RANGED_CRIT_PERCENT'] = $this->charStats['rCritPercent'];
		$vars['STAT_RANGED_CRIT_POS_PERCENT'] = 0;
		
		//expertise
		$vars['STAT_EXPERTISE_RATING'] = $this->charStats['mExpertise'];
		$vars['STAT_EXPERTISE_VALUE'] = 0;
		$vars['STAT_EXPERTISE_PERCENT'] = 0;
		$vars['STAT_EXPERTISE_ADD'] = 0;
		
		//mainhand data (if equipped)
		if(!empty($this->items[15]) && $this->charStats['mhTempo'] != 0){
			
			$vars['STAT_MAINHAND_DPS'] = round((($this->charStats['mhMinDmg'] + $this->charStats['mhMaxDmg']) / 2) /  $this->charStats['mhTempo'], 2);
			$vars['STAT_MAINHAND_MAXDMG'] = $this->charStats['mhMaxDmg'];
			$vars['STAT_MAINHAND_MINDMG'] = $this->charStats['mhMinDmg'];
			$vars['STAT_MAINHAND_SPEED']  = $this->charStats['mhTempo'];
			$vars['STAT_MAINHAND_HASTE_RATING']  = $this->charStats['spHasteRating'];
			$vars['STAT_MAINHAND_HASTE_PERCENT'] = $this->charStats['spHastePercent'];
			
		}else{
			
			$vars['STAT_MAINHAND_DPS'] = $vars['STAT_MAINHAND_MAXDMG'] = $vars['STAT_MAINHAND_MINDMG'] = $vars['STAT_MAINHAND_SPEED'] = 
				$vars['STAT_MAINHAND_HASTE_RATING'] = $vars['STAT_MAINHAND_HASTE_PERCENT'] = 0;
			
		}//if
		
		//offhand data (if equipped)
		if(!empty($this->items[16]) && $this->charStats['ohTempo'] != 0){
			//((Min Weapon Damage + Max Weapon Damage) / 2) / Weapon Speed
			$vars['STAT_OFFHAND_DPS'] = round((($this->charStats['ohMinDmg'] + $this->charStats['ohMaxDmg']) / 2) /  $this->charStats['ohTempo'], 2);
			$vars['STAT_OFFHAND_MAXDMG'] = $this->charStats['ohMaxDmg'];
			$vars['STAT_OFFHAND_MINDMG'] = $this->charStats['ohMinDmg'];
			$vars['STAT_OFFHAND_SPEED']  = $this->charStats['ohTempo'];
			$vars['STAT_OFFHAND_HASTE_RATING']  = $this->charStats['spHasteRating'];
			$vars['STAT_OFFHAND_HASTE_PERCENT'] = $this->charStats['spHastePercent'];
			
		}else{
			
			$vars['STAT_OFFHAND_DPS'] = $vars['STAT_OFFHAND_MAXDMG'] = $vars['STAT_OFFHAND_MINDMG'] = $vars['STAT_OFFHAND_SPEED'] = 
				$vars['STAT_OFFHAND_HASTE_RATING'] = $vars['STAT_OFFHAND_HASTE_PERCENT'] = 0;
			
		}//if
		
		//ranged weapon (if equipped)
		if(!empty($this->items[17]) && $this->charStats['rTempo']){
			//((Min Weapon Damage + Max Weapon Damage) / 2) / Weapon Speed
			$vars['STAT_RANGED_DPS'] = round((($this->charStats['rMinDmg'] + $this->charStats['rMaxDmg']) / 2) /  $this->charStats['rTempo'], 2);
			$vars['STAT_RANGED_MAXDMG'] = $this->charStats['rMaxDmg'];
			$vars['STAT_RANGED_MINDMG'] = $this->charStats['rMinDmg'];
			$vars['STAT_RANGED_SPEED']  = $this->charStats['rTempo'];
			$vars['STAT_RANGED_HASTE_RATING']  = $this->charStats['spHasteRating'];
			$vars['STAT_RANGED_HASTE_PERCENT'] = $this->charStats['spHastePercent'];
			
			$vars['STAT_RANGED_RATING'] = 0;
			$vars['STAT_RANGED_VALUE'] = 0;
			
			
		}else{
			
			$vars['STAT_RANGED_DPS'] = $vars['STAT_RANGED_MAXDMG'] = $vars['STAT_RANGED_MINDMG'] = $vars['STAT_RANGED_SPEED'] = 
				$vars['STAT_RANGED_HASTE_RATING'] = $vars['STAT_RANGED_HASTE_PERCENT'] = $vars['STAT_RANGED_RATING'] = $vars['STAT_RANGED_VALUE'] = 0;
			
		}//if
		
		$vars['STAT_BONUSDMG_ARCANE'] = $this->charStats['arcaneBonus'];
		$vars['STAT_BONUSDMG_FIRE'] = $this->charStats['fireBonus'];
		$vars['STAT_BONUSDMG_FROST'] = $this->charStats['frostBonus'];
		$vars['STAT_BONUSDMG_HOLY'] = $this->charStats['holyBonus'];
		$vars['STAT_BONUSDMG_NATURE'] = $this->charStats['natureBonus'];
		$vars['STAT_BONUSDMG_SHADOW'] = $this->charStats['shadowBonus'];
		
		$vars['STAT_HEALBONUS'] = $this->charStats['healBonus'];
		$vars['STAT_SPELL_PENETRATION'] = 0;
		
		$vars['STAT_HIT_PERCENT'] = $this->charStats['spHitPercent'];
		$vars['STAT_HIT_RATING'] = $this->charStats['spHitRating'];
		
		$vars['STAT_SPELL_CRIT_RATING'] = $this->charStats['spCritRating'];
		$vars['STAT_SPELL_CRIT_PERCENT'] = $this->charStats['spCritPercent'];
		$vars['STAT_CRIT_ARCANE_PERCENT'] = $this->charStats['arcaneCritPercent'];
		$vars['STAT_CRIT_FIRE_PERCENT'] = $this->charStats['fireCritPercent'];
		$vars['STAT_CRIT_FROST_PERCENT'] = $this->charStats['frostCritPercent'];
		$vars['STAT_CRIT_HOLY_PERCENT'] = $this->charStats['holyCritPercent'];
		$vars['STAT_CRIT_NATURE_PERCENT'] = $this->charStats['natureCritPercent'];
		$vars['STAT_CRIT_SHADOW_PERCENT'] = $this->charStats['shadowCritPercent'];
		
		$vars['STAT_HASTE_PERCENT'] = $this->charStats['spHastePercent'];
		$vars['STAT_HASTE_RATING'] = $this->charStats['spHasteRating'];
		
		
		//professions
		$vars['PROFESSIONS'] = '';
		if(!empty($this->professions)){
			
			foreach($this->professions as $prof){
				
				$profvals = array();
				$profvals['PROFID'] = $prof['prof'];
				$profvals['KEY'] = strtolower($DATA['professions_en_gb'][$prof['prof']]);
				$profvals['PROFNAME'] = $DATA["professions_$arsenaldataLang"][$prof['prof']];
				$profvals['MAX'] = $prof['max'];
				$profvals['CUR'] = $prof['cur'];
				
				$vars['PROFESSIONS'] .= fgettemplate('./xml-templates/character-sheet-professions.xml', $profvals);
				
			}//foreach
		}//if
		
		//talents
		if((int)$this->talents1){
			
			$talents = array();
			if($this->activeSpec == 0) $talents['ACTIVE'] = 'active="1"';
			$talents['TALENTGROUP'] = 1;
			$talents['TALENTGROUPICON'] = "inv_misc_questionmark";
			$talents['TREEONE'] = $this->numTalents1[1];
			$talents['TREETWO'] = $this->numTalents1[2];
			$talents['TREETHREE'] = $this->numTalents1[3];
			$tabnames = $tabicons = array();
			
			$qry = "SELECT * FROM talenttabs t JOIN talenttabs_$arsenaldataLang tl on t.tabId = tl.TabId;";
			$stmt =& $arsenaldata->query($qry);
			while($res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC)){
				$tabnames[$res['tabId']] = $res['tabName'];
				$tabicons[$res['tabId']] = $res['tabIcon'];
			}//while
			
			//which tree has most & get its name
			if(($talents['TREEONE'] > $talents['TREETWO']) && ($talents['TREEONE'] > $talents['TREETHREE'])){
				$talents['TALENTGROUPNAME'] = $tabnames[$DATA['talentTrees'][$this->class][0]];
				$talents['TALENTGROUPICON'] = $tabicons[$DATA['talentTrees'][$this->class][0]];
			}else if(($talents['TREETWO'] > $talents['TREEONE']) && ($talents['TREETWO'] > $talents['TREETHREE'])){
				$talents['TALENTGROUPNAME'] = $tabnames[$DATA['talentTrees'][$this->class][1]];
				$talents['TALENTGROUPICON'] = $tabicons[$DATA['talentTrees'][$this->class][1]];
			}else{
				$talents['TALENTGROUPNAME'] = $tabnames[$DATA['talentTrees'][$this->class][2]];
				$talents['TALENTGROUPICON'] = $tabicons[$DATA['talentTrees'][$this->class][2]];
			}//if
			
			$vars['TALENTS'] = fgettemplate('./xml-templates/character-sheet-talents.xml', $talents);
			
		}//if
		
		if((int)$this->talents2){
			
			$talents = array();
			if($this->activeSpec == 1) $talents['ACTIVE'] = 'active="1"';
			$talents['TALENTGROUP'] = 2;
			$talents['TALENTGROUPICON'] = "inv_misc_questionmark";
			$talents['TREEONE'] = $this->numTalents2[1];
			$talents['TREETWO'] = $this->numTalents2[2];
			$talents['TREETHREE'] = $this->numTalents2[3];
			$tabnames = array();
			
			$qry = "SELECT * FROM talenttabs_$arsenaldataLang;";
			$stmt =& $arsenaldata->query($qry);
			while($res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC)){
				$tabnames[$res['tabId']] = $res['tabName'];
			}
			
			//which tree has most & get its name
			if(($talents['TREEONE'] > $talents['TREETWO']) && ($talents['TREEONE'] > $talents['TREETHREE'])){
				$talents['TALENTGROUPNAME'] = $tabnames[$DATA['talentTrees'][$this->class][0]];
				$talents['TALENTGROUPICON'] = $tabicons[$DATA['talentTrees'][$this->class][0]];
			}else if(($talents['TREETWO'] > $talents['TREEONE']) && ($talents['TREETWO'] > $talents['TREETHREE'])){
				$talents['TALENTGROUPNAME'] = $tabnames[$DATA['talentTrees'][$this->class][1]];
				$talents['TALENTGROUPICON'] = $tabicons[$DATA['talentTrees'][$this->class][1]];
			}else{
				$talents['TALENTGROUPNAME'] = $tabnames[$DATA['talentTrees'][$this->class][2]];
				$talents['TALENTGROUPICON'] = $tabicons[$DATA['talentTrees'][$this->class][2]];
			}//if
			
			$vars['TALENTS'] .= fgettemplate('./xml-templates/character-sheet-talents.xml', $talents);
			
		}//if
		
		//achievements
		$tree = new ParentBasedTree();
		$vars = array_merge($vars, $this->prepareAchievementTemplateVars(&$tree));
		
		//arena
		if(!empty($this->arenaTeams)){
			
			$vars['ARENATEAMSSTART'] = '<arenaTeams>';
			$vars['ARENATEAMSEND'] = '</arenaTeams>';
			$vars['ARENATEAMS'] = '';
			
			foreach($this->arenaTeams as $team){
				
				$arenateamvars = array();
				$arenateamvars['REALM'] = $realmName;
				$arenateamvars['REALMPOOL'] = $realmpool;
				$arenateamvars['REALMPOOLURL'] = urlencode($realmpool);
				$arenateamvars['REALMURL'] = urlencode($realmName);
				$arenateamvars['FACTIONID'] = $DATA["factions"][$team['leaderRace']];
				$arenateamvars['FACTION'] = $DATA["factionNames_$language"][$team['leaderRace']];
				$arenateamvars['NAME'] = $team['name'];
				$arenateamvars['NAMEURL'] = urlencode($team['name']);
				$arenateamvars['TEAMSIZE'] = count($team['teamMember']);
				$arenateamvars['SIZE'] =  $team['teamSize'];
				$arenateamvars['PLAYED'] = $team['gamesPlayed'];
				$arenateamvars['SEASONPLAYED'] = $team['seasonGamesPlayed'];
				$arenateamvars['WON'] = $team['gamesWon'];
				$arenateamvars['SEASONWON'] = $team['seasonGamesWon'];
				$arenateamvars['RANKING'] = $team['ranking'];
				$arenateamvars['RATING'] = $team['rating'];
				$arenateamvars['EMBLEMBG'] = $team['background'];
				$arenateamvars['EMBLEMBORDERCOL'] = $team['borderColor'];
				$arenateamvars['EMBLEMBORDERSTYLE'] = $team['borderStyle'];
				$arenateamvars['EMBLEMICONCOL'] = $team['iconColor'];
				$arenateamvars['EMBLEMICONSTYLE'] = $team['iconStyle'];
				
				$arenateamvars['MEMBER'] = '';
				if(!empty($team['teamMember'])){
					
					foreach($team['teamMember'] as $member){
						
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
						
						$arenateamvars['MEMBER'] .= fgettemplate('./xml-templates/team-info-member.xml', $membervars);
						
					}//foreach
					
				}//if
				
				$vars['ARENATEAMS'] .= fgettemplate('./xml-templates/character-sheet-arenateam.xml', $arenateamvars);
				
			}//foreach
		}//if
		
		//items handeling
		$vars['ITEMS'] = '';
		foreach($this->items as $item){
			
			$itemvars['DUR'] = $item->currentDurability;
			$itemvars['ITEMID'] = $item->itemId;
			$itemvars['SLOT'] = $item->itemSlot;
			$itemvars['GEM0'] = $item->socket1;
			$itemvars['GEM1'] = $item->socket2;
			$itemvars['GEM2'] = $item->socket3;
			$itemvars['GEM3'] = $item->socket4;
			$itemvars['GEM4'] = $item->socket5;
			$itemvars['ENCHANT'] = $item->enchant;
			
			$qry = "SELECT itemIcon, maxDurability FROM itemsdata WHERE itemID = ".$item->itemId.";";
			$stmt =& $arsenaldata->query($qry);
			$res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC);
			
			$itemvars['ICON'] = $res['itemIcon'];
			$itemvars['MAXDUR'] = $res['maxDurability'] ? $res['maxDurability'] : 0;
			
			
			$vars['ITEMS'] .= fgettemplate('./xml-templates/character-sheet-items.xml', $itemvars);
			
		}
		
		return $vars;
		
		
	}//prepareCharacterSheet()
	
	
	/**
     * returns an array with variables used to fill the template character-reputation.xml
	 * @access public
	 * @global array $DATA
	 * @global obj $arsenaldata;
	 * @global string $arsenaldataLang;
	 * @return array
    */
	public function prepareCharacterReputationSheet(){
		
		global $DATA;
		global $arsenaldata;
		global $arsenaldataLang;
		
		
		//vars used in many templates
		$vars = $this->prepareBasicTemplateVars();
		
		$qry = "SELECT * FROM factions_$arsenaldataLang;";
		$stmt =& $arsenaldata->query($qry);
		while($res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC)){
			
			$factionid = $res['factionid'];
			$vars["NAME$factionid"] = $res['factionname'];
			$vars["REP$factionid"] = 0;
			if($this->reputation[$factionid]) $vars["REP$factionid"] = $this->reputation[$factionid];
			
		}//while
		
		return $vars;
		
	}//prepareCharacterReputationSheet()
	
	
	/**
     * returns an array with variables used to fill the template character-arenateams.xml
	 * @access public
	 * @global array $DATA
	 * @global obj $arsenaldata;
	 * @global string $arsenaldataLang;
	 * @global string $realmpool;
	 * @global string $language;
	 * @global array $realms;
	 * @return array
    */
	public function prepareCharacterArenaSheet(){
		
		global $DATA;
		global $arsenaldata;
		global $arsenaldataLang;
		global $realms;
		global $language;
		global $realmpool;
		
		foreach($realms as $realm){
			if($realm->realmId == $this->realmId){
				$realmName = $realm->realmName;
				break;
			}//if
		}//foreach
		
		//vars used in many templates
		$vars = $this->prepareBasicTemplateVars();
		
		if(!empty($this->arenaTeams)){
			
			$vars['ARENATEAMS'] = '';
			foreach($this->arenaTeams as $team){
				
				$arenateamvars = array();
				$arenateamvars['REALM'] = $realmName;
				$arenateamvars['REALMPOOL'] = $realmpool;
				$arenateamvars['REALMPOOLURL'] = urlencode($realmpool);
				$arenateamvars['REALMURL'] = urlencode($realmName);
				$arenateamvars['FACTIONID'] = $DATA["factions"][$team['leaderRace']];
				$arenateamvars['FACTION'] = $DATA["factionNames_$language"][$team['leaderRace']];
				$arenateamvars['NAME'] = $team['name'];
				$arenateamvars['NAMEURL'] = urlencode($team['name']);
				$arenateamvars['TEAMSIZE'] = count($team['teamMember']);
				$arenateamvars['SIZE'] =  $team['teamSize'];
				$arenateamvars['PLAYED'] = $team['gamesPlayed'];
				$arenateamvars['SEASONPLAYED'] = $team['seasonGamesPlayed'];
				$arenateamvars['WON'] = $team['gamesWon'];
				$arenateamvars['SEASONWON'] = $team['seasonGamesWon'];
				$arenateamvars['RANKING'] = $team['ranking'];
				$arenateamvars['RATING'] = $team['rating'];
				$arenateamvars['EMBLEMBG'] = $team['background'];
				$arenateamvars['EMBLEMBORDERCOL'] = $team['borderColor'];
				$arenateamvars['EMBLEMBORDERSTYLE'] = $team['borderStyle'];
				$arenateamvars['EMBLEMICONCOL'] = $team['iconColor'];
				$arenateamvars['EMBLEMICONSTYLE'] = $team['iconStyle'];
				
				$arenateamvars['MEMBER'] = '';
				if(!empty($team['teamMember'])){
					
					foreach($team['teamMember'] as $member){
						
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
						
						$arenateamvars['MEMBER'] .= fgettemplate('./xml-templates/team-info-member.xml', $membervars);
						
					}//foreach
					
				}//if
				
				$vars['ARENATEAMS'] .= fgettemplate('./xml-templates/character-sheet-arenateam.xml', $arenateamvars);
				
			}//foreach
		}//if
		
		return $vars;
		
	}//prepareCharacterArenaSheet()
	
	
	/**
     * returns an array with variables used to fill the template character-achievements.xml
	 * @access public
	 * @global array $DATA
	 * @global obj $arsenaldata;
	 * @global string $arsenaldataLang;
	 * @return array
    */
	public function prepareCharacterAchievementSheet($catagory = null){
		
		global $DATA;
		global $arsenaldata;
		global $arsenaldataLang;
		
		
		//vars used in many templates
		$vars = $this->prepareBasicTemplateVars();
		
		//achievements
		$tree = new ParentBasedTree();
		$vars = array_merge($vars, $this->prepareAchievementTemplateVars(&$tree));
		
		//root
		if($catagory === null){
			
			ob_start();
			$tree->getCategoryStructure();
			$vars['CATEGORIES'] = ob_get_clean();
			
			$vars['LATESTACH'] = '';
			
			$thelatest = $this->achievements;
			arsort(&$thelatest, SORT_NUMERIC);
			
			$i = 0;
			foreach($thelatest as $achid=>$date){
				
				$i++;
				if($i > 5)  break;
				
				$achvars = array();
				$qry = "SELECT * FROM achievements a 
							JOIN achievements_$arsenaldataLang al ON al.achievementid = a.achievementid WHERE a.achievementid = ".$achid.";";
					
				$stmt =& $arsenaldata->query($qry);
				$res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC);
				
				$achvars['ACHTITLE'] = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $res['achievementname']);
				$achvars['ACHDESC'] = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $res['achievementdescription']);
				$achvars['ACHID'] = $achid;
				$achvars['ACHICON'] = $res['icon'];
				$achvars['ACHCATEGORY'] = $res['achievementcategory'];
				
				if($res['achievementpoints'] > 0) $achvars['ACHPOINTS'] = 'points="'.$res['achievementpoints'].'"';
				else $achvars['ACHPOINTS'] = '';
				
				//date-format is something like 2009-04-27T05:02:00+02:00
				$achvars['ACHDATECOMPLETED'] = 'dateCompleted="'.date('Y-m-d\TH:i:sP', $date).'"';
				
				$vars['LATESTACH'] .= fgettemplate('./xml-templates/character-achievements-achievement.xml', $achvars);
				
			}//foreach
			
		}else{
			
			$node = &$tree->getChildById($catagory);
			$data = $node->data;
			$vars['ACHIEVEMENTS'] = '';
			
			foreach($data as $key=>$achievement){
				
				//exclude the category name
				if(is_int($key)){
					
					$achvars = array();
					$achvars['ACHCATEGORY'] = $catagory;
					$achvars['ACHDESC'] = $achievement['achievementdescription'];
					$achvars['ACHICON'] = $achievement['achievementicon'];
					$achvars['ACHID'] = $achievement['achid'];
					$achvars['ACHTITLE'] = $achievement['achname'];
					
					if($achievement['achievementpoints'] > 0) $achvars['ACHPOINTS'] = 'points="'.$achievement['achievementpoints'].'"';
					else $achvars['ACHPOINTS'] = '';
					
					//date-format is something like 2009-04-27T05:02:00+02:00
					if($achievement['achieved'] == true) $achvars['ACHDATECOMPLETED'] = 'dateCompleted="'.date('Y-m-d\TH:i:sP', $this->achievements[$achievement['achid']]).'"';
					else $achvars['ACHDATECOMPLETED'] = '';
					
					if(($catagory == 81 && $achievement['achieved'] == true) || $catagory != 81){
						
						$vars['ACHIEVEMENTS'] .= fgettemplate('./xml-templates/character-achievements-achievement.xml', $achvars);
						
					}//if
					
				}//if
				
			}//forech
			
			if($node->numChildren() > 0){
				
				foreach($node->children as $child){
					
					$vars['ACHIEVEMENTS'] .= '<category>';
					$data = $child->data;
					
					foreach($data as $key=>$achievement){
						
						//exclude the category name
						if(is_int($key)){
							
							$achvars = array();
							$achvars['ACHCATEGORY'] = $catagory;
							$achvars['ACHDESC'] = $achievement['achievementdescription'];
							$achvars['ACHICON'] = $achievement['achievementicon'];
							$achvars['ACHID'] = $achievement['achid'];
							$achvars['ACHTITLE'] = $achievement['achname'];
							
							if($achievement['achievementpoints'] > 0) $achvars['ACHPOINTS'] = 'points="'.$achievement['achievementpoints'].'"';
							else $achvars['ACHPOINTS'] = '';
							
							//date-format is something like 2009-04-27T05:02:00+02:00
							if($achievement['achieved'] == true) $achvars['ACHDATECOMPLETED'] = 'dateCompleted="'.date('Y-m-d\TH:i:sP', $this->achievements[$achievement['achid']]).'"';
							else $achvars['ACHDATECOMPLETED'] = '';
							
							$vars['ACHIEVEMENTS'] .= fgettemplate('./xml-templates/character-achievements-achievement.xml', $achvars);
							
						}//if
						
					}//forech
					
					$vars['ACHIEVEMENTS'] .= '</category>';
					
				}//foreach
				
			}
			
		}//if
		
		return $vars;
		
	}//prepareCharacterAchievementSheet()
	
	
	/**
     * returns an array with variables used to fill the template character-talents.xml
	 * @access public
	 * @global array $DATA
	 * @global obj $arsenaldata
	 * @global string $arsenaldataLang
	 * @return array
    */
	public function prepareCharacterTalentSheet(){
		
		global $DATA;
		global $arsenaldata;
		global $arsenaldataLang;
		
		$vars = $this->prepareBasicTemplateVars();
		$vars['TALENTS'] = '';
		
		//talents1
		$talents = array();
		if(empty($this->talents2) || $this->activeSpec == 0) $talents['ACTIVE'] = 'active="1"';
		$talents['TALENTGROUP'] = 1;
		$talents['TALENTGROUPICON'] = "inv_misc_questionmark";
		
		$talents['TREEONE'] = $this->numTalents1[1];
		$talents['TREETWO'] = $this->numTalents1[2];
		$talents['TREETHREE'] = $this->numTalents1[3];
		
		$talents['TALENTTREEVALUES'] = $this->talents1;
		
		$tabnames = $tabicons = array();
		$qry = "SELECT * FROM talenttabs t JOIN talenttabs_$arsenaldataLang tl on t.tabId = tl.TabId;";
		$stmt =& $arsenaldata->query($qry);
		while($res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC)){
			
			$tabnames[$res['tabId']] = $res['tabName'];
			$tabicons[$res['tabId']] = $res['tabIcon'];
			
		}//while
		
		//which tree has most & get its name
		if(($talents['TREEONE'] > $talents['TREETWO']) && ($talents['TREEONE'] > $talents['TREETHREE'])){
			$talents['TALENTGROUPNAME'] = $tabnames[$DATA['talentTrees'][$this->class][0]];
			$talents['TALENTGROUPICON'] = $tabicons[$DATA['talentTrees'][$this->class][0]];
		}else if(($talents['TREETWO'] > $talents['TREEONE']) && ($talents['TREETWO'] > $talents['TREETHREE'])){
			$talents['TALENTGROUPNAME'] = $tabnames[$DATA['talentTrees'][$this->class][1]];
			$talents['TALENTGROUPICON'] = $tabicons[$DATA['talentTrees'][$this->class][1]];
		}else{
			$talents['TALENTGROUPNAME'] = $tabnames[$DATA['talentTrees'][$this->class][2]];
			$talents['TALENTGROUPICON'] = $tabicons[$DATA['talentTrees'][$this->class][2]];
		}//if
		
		if(!empty($this->glyphs1)){
			
			$glyphvars = array();
			$glyphs = '';
			
			foreach($this->glyphs1 as $glyph){
				
				if($glyph == 0) continue;
				
				$qry = "SELECT * FROM glyphs g JOIN glyphs_$arsenaldataLang gl ON g.glyphid = gl.glyphid WHERE g.glyphid = $glyph;";
				$stmt =& $arsenaldata->query($qry);
				$res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC);
				
				$glyphvars['GLYPHID'] = $res['glyphid'];
				$glyphvars['GLYPHEFFECT'] = $res['glyphDesc'];
				$glyphvars['GLYPHICON'] = '';
				$glyphvars['GLYPHNAME'] = $res['glyphName'];
				if($res['glyphflags'] == 0) $glyphvars['GLYPHTYPE'] = 'major';
				if($res['glyphflags'] == 1) $glyphvars['GLYPHTYPE'] = 'minor';
				
				$glyphs .= fgettemplate('./xml-templates/character-talents-glyphs.xml', $glyphvars);
				
			}//foreach
			
			$talents['GLYPHS'] = $glyphs;
			
		}//if
		
		$vars['TALENTS'] .= fgettemplate('./xml-templates/character-talents-talentgroup.xml', $talents);
		
		//talents2
		if(!empty($this->talents2)){
			
			$talents = array();
			if(empty($this->talents1) || $this->activeSpec == 1) $talents['ACTIVE'] = 'active="1"';
			$talents['TALENTGROUP'] = 2;
			$talents['TALENTGROUPICON'] = "inv_misc_questionmark";
			
			$talents['TREEONE'] = $this->numTalents2[1];
			$talents['TREETWO'] = $this->numTalents2[2];
			$talents['TREETHREE'] = $this->numTalents2[3];
			
			$talents['TALENTTREEVALUES'] = $this->talents2;
			
			//which tree has most & get its name
			if(($talents['TREEONE'] > $talents['TREETWO']) && ($talents['TREEONE'] > $talents['TREETHREE'])){
				$talents['TALENTGROUPNAME'] = $tabnames[$DATA['talentTrees'][$this->class][0]];
				$talents['TALENTGROUPICON'] = $tabicons[$DATA['talentTrees'][$this->class][0]];
			}else if(($talents['TREETWO'] > $talents['TREEONE']) && ($talents['TREETWO'] > $talents['TREETHREE'])){
				$talents['TALENTGROUPNAME'] = $tabnames[$DATA['talentTrees'][$this->class][1]];
				$talents['TALENTGROUPICON'] = $tabicons[$DATA['talentTrees'][$this->class][1]];
			}else{
				$talents['TALENTGROUPNAME'] = $tabnames[$DATA['talentTrees'][$this->class][2]];
				$talents['TALENTGROUPICON'] = $tabicons[$DATA['talentTrees'][$this->class][2]];
			}//if
			
			if(!empty($this->glyphs2)){
				
				$glyphvars = array();
				$glyphs = '';
				
				foreach($this->glyphs2 as $glyph){
					
					if($glyph == 0) continue;
					
					$qry = "SELECT * FROM glyphs g JOIN glyphs_$arsenaldataLang gl ON g.glyphid = gl.glyphid WHERE g.glyphid = $glyph;";
					$stmt =& $arsenaldata->query($qry);
					$res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC);
					
					$glyphvars['GLYPHID'] = $res['glyphid'];
					$glyphvars['GLYPHEFFECT'] = $res['glyphDesc'];
					$glyphvars['GLYPHICON'] = '';
					$glyphvars['GLYPHNAME'] = $res['glyphName'];
					if($res['glyphflags'] == 0) $glyphvars['GLYPHTYPE'] = 'major';
					if($res['glyphflags'] == 1) $glyphvars['GLYPHTYPE'] = 'minor';
					
					$glyphs .= fgettemplate('./xml-templates/character-talents-glyphs.xml', $glyphvars);
					
				}//foreach
				
				$talents['GLYPHS'] = $glyphs;
				
			}//if
			
			$vars['TALENTS'] .= fgettemplate('./xml-templates/character-talents-talentgroup.xml', $talents);
			
		}
		
		return $vars;
		
	}//prepareCharacterTalentSheet()
	
	/**
     * returns an array with variables used to fill the template item-tooltip.xml
	 * @access public
	 * @global array $DATA
	 * @global obj $arsenaldata
	 * @global string $arsenaldataLang
	 * @global string $language;
	 * @return array
    */
	public function prepareItemTooltip($slot){
		
		global $DATA;
		global $arsenaldata;
		global $arsenaldataLang;
		global $language;
		
		//check if there is an item in the slot
		$item = $this->items[$slot];
		if(!($item instanceof Item)) return array();
		
		$vars = array();
		$vars['LANGUAGE'] = $language;
		
		$qry = "SELECT * FROM itemsdata i JOIN itemsdata_$arsenaldataLang il ON i.itemid = il.itemid WHERE i.itemid = ".$item->itemId." LIMIT 1;";
		$stmt =& $arsenaldata->query($qry);
		$res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC);
		
		$vars['ITEMID'] = $item->itemId;
		$vars['ITEMNAME'] = $res['itemName'];
		$vars['ITEMICON'] = $res['itemIcon'];
		$vars['QUALITYID'] = $res['quality'];
		$vars['BONDING'] = $res['bonding'];
		$vars['INVENTORYTYPE'] = $res['inventoryType'];
		$vars['SUBCLASSNAME'] = $res['itemSubclassName'];
		$vars['SOURCETYPE'] = $res['sourceType'];
		
		
		//randomly enchanted item?
		$vars['RANDOMENCHANT'] = '';
		$item = $this->items[$slot];
		
		if($item->randomEnchantId > 0){
			
			$qry2 = "SELECT * FROM randomproperties p JOIN randomproperties_$arsenaldataLang pl ON p.randomPropertyId = pl.randomPropertyId WHERE p.randomPropertyId = ".$item->randomEnchantId." LIMIT 1;";
			$stmt2 = $arsenaldata->query($qry2);
			$res2 = $stmt2->fetchRow(MDB2_FETCHMODE_ASSOC);
			
			$vars['RANDOMENCHANT'] .= '<randomEnchantData>';
			$vars['RANDOMENCHANT'] .= '<suffix>'.$res2['name'].'</suffix>';
			
			for($i = 1; $i < 4; $i++){
				
				if($res2["enchantid$i"] > 0){
				
				$qry3 = "SELECT * FROM itemenchantments i JOIN itemenchantments_$arsenaldataLang il ON i.enchantid = il.enchantid WHERE i.enchantid = ".$res2["enchantid$i"]." LIMIT 1;";
				$stmt3 = $arsenaldata->query($qry3);
				$res3 = $stmt3->fetchRow(MDB2_FETCHMODE_ASSOC);
				$vars['RANDOMENCHANT'] .= '<enchant>'.$res3['enchantname'].'</enchant>';
				
				}//if
				
			}//for
			
			$vars['RANDOMENCHANT'] .= '</randomEnchantData>';
			
		}//if
		
		
		//unique item?
		$vars['MAXCOUNT'] = '';
		if($res['maxCount'] > 0) $vars['MAXCOUNT'] = '<maxCount>'.$res['maxCount'].'</maxCount>';
		if(($res['maxCount'] == 1) && ($res['maxCountEquippable'] == 1)) $vars['MAXCOUNT'] = '<maxCount uniqueEquippable="1">1</maxCount>';
		
		if($res['maxDurability']) 		$vars['DURABILITY'] = '<durability current="'.$item->currentDurability.'" max="'.$res['maxDurability'].'"/>';
		if($res['requiredLevel']) 		$vars['REQUIREDLEVEL'] = '<requiredLevel>'.$res['requiredLevel'].'</requiredLevel>';
		if($res['allowableClasses']) 	$vars['ALLOWABLECLASSES'] = $res['allowableClasses'];
		
		//addittional source info
		if($res['itemSourceCreatureId']){
			$vars['SOURCEINFO'] = 'areaId="'.$res['itemSourceAreaId'].'" areaName="'.$res['objectAreaName'].'" 
									creatureId="'.$res['itemSourceCreatureId'].'" creatureName="'.$res['objectCreatureName'].'" 
									difficulty="'.$res['itemSourceDifficulty'].'" dropRate="'.$res['itemSourceDropRate'].'"';
		}
		
		//statModifiers
		$statmodifiers = unserialize($res['statmodifiers']);
		if(!empty($statmodifiers)){
			
			$vars['STATMODIFIERS'] = '';
			$mofifyerXMLTags = $DATA['mofifyerXMLTags'];
			
			foreach($statmodifiers as $statmodifier){
				
				//pure stats add (wotlk)
				if($statmodifier[0] == 5){
					
					//fix for haste
					if($statmodifier[1] == 28) $statmodifier[1] = 36;
					if($statmodifier[1] == 29) $statmodifier[1] = 999;
					
					//protect from unclean XML-code
					if($mofifyerXMLTags[$statmodifier[1]]){
						
						$vars['STATMODIFIERS'] .= '<'.$mofifyerXMLTags[$statmodifier[1]].'>'.$statmodifier[2].'</'.$mofifyerXMLTags[$statmodifier[1]].'>';
						
					}//if
				}//if
				
				//armor + resistances
				if($statmodifier[0] == 4){
					
					if($statmodifier[1] == 0) $vars['STATMODIFIERS'] .= '<armor armorBonus="0">'.$statmodifier[2].'</armor>';
					if($statmodifier[1] == 2) $vars['STATMODIFIERS'] .= '<fireResist>'.$statmodifier[2].'</fireResist>';
					if($statmodifier[1] == 3) $vars['STATMODIFIERS'] .= '<natureResist>'.$statmodifier[2].'</natureResist>';
					if($statmodifier[1] == 4) $vars['STATMODIFIERS'] .= '<frostResist>'.$statmodifier[2].'</frostResist>';
					if($statmodifier[1] == 5) $vars['STATMODIFIERS'] .= '<shadowResist>'.$statmodifier[2].'</shadowResist>';
					if($statmodifier[1] == 6) $vars['STATMODIFIERS'] .= '<arcaneResist>'.$statmodifier[2].'</arcaneResist>';
					if($statmodifier[1] == 7) $vars['STATMODIFIERS'] .= '<holyResist>'.$statmodifier[2].'</holyResist>';
					
				}
				
			}
			
		}
		
		//spells
		$spells = unserialize($res['spells']);
		if(!empty($spells)){
			
			$vars['SPELLS'] = '<spellData>';
			
			foreach($spells as $spellNo=>$spell){
				
				$vars['SPELLS'] .= '<spell><trigger>'.key($spell).'</trigger><desc>'.$res["spellDesc$spellNo"].'</desc></spell>';
				
			}//foreach
			
			$vars['SPELLS'] .= '</spellData>';
			
		}//if
		
		//damage data (for weapons)
		if($res['damageMax']){
			
			$dmgType = $res['damageType'];
			if(!$dmgType) $dmgType = 0;
			
			$vars['DAMAGEDATA'] =   '<damage><type>'.$dmgType.'</type><min>'.$res['damageMin'].'</min><max>'.$res['damageMax'].'</max></damage>
										<speed>'.$res['damageSpeed'].'</speed><dps>'.$res['damageDPS'].'</dps>';
		}//if
		
		//item sets
		$item = $this->items[$slot];
		if($res['itemsetId'] > 0){
			
			$vars['ITEMSETDATA'] = '<setData>';
			
			$qry2 = "SELECT * FROM itemsets i 
								JOIN itemsets_$arsenaldataLang il ON i.setid = il.setid 
								WHERE i.setid = ".$res['itemsetId'].";";
			$stmt2 = $arsenaldata->query($qry2);
			$res2 = $stmt2->fetchRow(MDB2_FETCHMODE_ASSOC);
			
			$vars['ITEMSETDATA'] .= '<name>'.$res2['setname'].'</name>';
			
			$setitems = array();
			array_push($setitems, $res2['item1'], $res2['item2'], $res2['item3'], $res2['item4'], 
						$res2['item5'], $res2['item6'], $res2['item7'], $res2['item8'], $res2['item9'], $res2['item10']);
			
			foreach($setitems as $setitem){
				
				if($setitem){
					
					$qry3 = "SELECT itemName FROM itemsdata_$arsenaldataLang WHERE itemid = ".$setitem." LIMIT 1;";
					$stmt3 = $arsenaldata->query($qry3);
					$res3 = $stmt3->fetchRow(MDB2_FETCHMODE_ASSOC);
					
					$equipped = '';
					foreach($this->items as $item){
						if($item->itemId == $setitem) $equipped = ' equipped="1"';
					}
					$vars['ITEMSETDATA'] .= '<item'.$equipped.' name="'.$res3['itemName'].'"/>';
					
				}//if
				
			}//foreach
			
			for($i = 1; $i<9; $i++){
				if($res2["reqNoForSpell$i"]) $vars['ITEMSETDATA'] .= '<setBonus desc="'.$res2["spellDesc$i"].'" threshold="'.$res2["reqNoForSpell$i"].'"/>';
			}//for
			
			$vars['ITEMSETDATA'] .= '</setData>';
			
		}//if
		
		//enchant
		$item = $this->items[$slot];
		if($item->enchant){
			
			$qry2 = "SELECT * FROM itemenchantments i JOIN itemenchantments_$arsenaldataLang il ON i.enchantid = il.enchantid WHERE i.enchantid = ".$item->enchant.";";
			$stmt2 = $arsenaldata->query($qry2);
			$res2 = $stmt2->fetchRow(MDB2_FETCHMODE_ASSOC);
			
			$vars['ENCHANT'] = '<enchant>'.$res2['enchantname'].'</enchant>';
			
		}
		
		//sockets
		$item = $this->items[$slot];
		$sockets = unserialize($res['sockets']);
		if(!empty($sockets)){
			
			$vars['SOCKETDATA'] = '<socketData>';
			
			foreach($sockets as $socketslot=>$socket){
				
				if($socketslot == 'socketMatchEnchantSpell'){
					
					$vars['SOCKETDATA'] .= '<socketMatchEnchant>'.$res['socketMatchEnchant'].'</socketMatchEnchant>';
					
				}else{
					
					if($socketslot == 1) $socketenchant = $item->socket1;
					if($socketslot == 2) $socketenchant = $item->socket2;
					if($socketslot == 3) $socketenchant = $item->socket3;
					if($socketslot == 4) $socketenchant = $item->socket4;
					if($socketslot == 5) $socketenchant = $item->socket5;
					
					$qry2 = "SELECT * FROM itemenchantments i JOIN itemenchantments_$arsenaldataLang il ON i.enchantid = il.enchantid WHERE i.enchantid = ".$socketenchant.";";
					$stmt2 = $arsenaldata->query($qry2);
					if($stmt2) $res2 = $stmt2->fetchRow(MDB2_FETCHMODE_ASSOC);
					
					$enchant = $res2['enchantname'];
					
					$qry3 = "SELECT i.itemIcon, il.desc FROM itemsdata i JOIN itemsdata_en_gb il ON i.itemId = il.itemId WHERE i.itemid = ".$res2['socketRef'].";";
					$stmt3 = $arsenaldata->query($qry3);
					if($stmt3) $res3 = $stmt3->fetchRow(MDB2_FETCHMODE_ASSOC);
					
					$icon = $res3['itemIcon'];
					$desc = $res3['desc'];
					$socket = substr($socket, 1, -1);
					if($socket == "Meta" && stristr($desc, "Meta")) $match = 1;
					if($socket == "Yellow" && stristr($desc, "Yellow")) $match = 1;
					if($socket == "Red" && stristr($desc, "Red")) $match = 1;
					if($socket == "Blue" && stristr($desc, "Blue")) $match = 1;
					if(!$match) $match = 0;
					
					$vars['SOCKETDATA'] .= '<socket color="'.$socket.'" enchant="'.$enchant.'" icon="'.$icon.'" match="'.$match.'"/>';
					
				}
				
			}
			
			$vars['SOCKETDATA'] .= '</socketData>';
			
		}
		
		return $vars;
		
		
	}//prepareItemTooltip()
	
	
	/**
     * returns an array prefilled with several variables that are used in nearly all xml-templates
	 * @access private
	 * @global array $realms
	 * @global string $language
	 * @global array $DATA
	 * @global string $realmpool
	 * @global obj $arsenaldata
	 * @global string $arsenaldataLang
	 * @return array
    */
	private function prepareBasicTemplateVars(){
		
		global $realms;
		global $language;
		global $DATA;
		global $realmpool;
		global $arsenaldata;
		global $arsenaldataLang;
		
		foreach($realms as $realm){
			if($realm->realmId == $this->realmId){
				$realmName = $realm->realmName;
				break;
			}//if
		}//foreach
		
		$vars = array();
		$vars['NAME'] = $this->charName;
		$vars['CLASSID'] = $this->class;
		$vars['CLASS'] = $DATA["classes_$language"][$this->class];
		$vars['CLASSURL'] = "c=" . $DATA["classes_$language"][$this->class];
		$vars['RACEID'] = $this->race;
		$vars['RACE'] = $DATA["races_$language"][$this->race];
		$vars['GENDERID'] = $this->gender;
		$vars['GENDER'] = $DATA["gender_$language"][$this->gender];
		$vars['FACTIONID'] = $DATA["factions"][$this->race];
		$vars['FACTION'] = $DATA["factionNames_$language"][$this->race];
		$vars['MODIFIED'] = date("d.m.Y H:i", $this->lastUpdate);
		$vars['LEVEL'] = $this->level;
		$vars['REALMPOOL'] = $realmpool;
		$vars['ACHIEVEMENTPOINTS'] = $this->achievementPoints;
		$vars['REALM'] = $realmName;
		$vars['TITLEID'] = 0;
		
		if($this->chosenTitle > 0){
			
			$qry = "SELECT * FROM titles t
						JOIN titles_$arsenaldataLang tl ON t.titleId = tl.titleId
						WHERE t.titleMask = ".$this->chosenTitle." LIMIT 1;";
			
			$stmt =& $arsenaldata->query($qry);
			$res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC);
			
			if($this->gender == 0 && $res['titleMale']){
				
				if(substr($res['titleMale'], -2) == '%s')	$vars['PREFIX'] = substr($res['titleMale'], 0, -2);
				if(substr($res['titleMale'], 0, 2) == '%s')	$vars['SUFFIX'] = substr($res['titleMale'], 2);
				$vars['TITLEID'] = $res['titleId'];
				
			}else if($this->gender == 1 && $res['titleFemale']){
				
				if(substr($res['titleFemale'], -2) == '%s')		$vars['PREFIX'] = substr($res['titleFemale'], 0, -2);
				if(substr($res['titleFemale'], 0, 2) == '%s')	$vars['SUFFIX'] = substr($res['titleFemale'], 2);
				$vars['TITLEID'] = $res['titleId'];
				
			}//if
			
		}//if
		
		$vars['LANGUAGE'] = $language;
		$vars['TABURL'] = "r=".urlencode($realmName)."&amp;cn=".urlencode($this->charName);
		$vars['CHARURL'] = "r=".urlencode($realmName)."&amp;n=".urlencode($this->charName);
		if($this->guildId) $vars['TABURL'] .= "&amp;gn=".$this->guildURL;
		if($this->guildId) $vars['GUILDVALUES'] = 'guildName="'.$this->guildName.'" guildUrl="r='.urlencode($realmName).'&amp;gn='.$this->guildURL.'"';
		
		return $vars;
		
	}//prepareBasicTemplateVars()
	
	
	/**
     * returns an array prefilled with several variables that are used in character-sheet.xml and character-achievements.xml
	 * @access private
	 * @param ParentBasedTree $tree
	 * @global array $DATA
	 * @global obj $arsenaldata
	 * @global string $arsenaldataLang
	 * @return array
    */
	private function prepareAchievementTemplateVars($tree){
		
		global $DATA;
		global $arsenaldata;
		global $arsenaldataLang;
	 
		$results = array();
		$categories = array();
		
		
		$vars["ACHIEVEMENTSNUM"] = $vars["ACHIEVEMENTPOINTS"] = 
			$vars["ACHIEVEMENTSNUMTOTAL"] = $vars["ACHIEVEMENTPOINTSTOTAL"] = 0;
		
		$qry = "SELECT * FROM achievementcategory ac 
							JOIN achievementcategory_$arsenaldataLang acl ON ac.achievementcategory = acl.achievementcategory
							ORDER BY ac.parentid ASC, ac.achievementcategory ASC;";
			
		$stmt =& $arsenaldata->query($qry);
		while($res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC)){
			
			$catid = $res['achievementcategory'];
			
			if($tree->getChildById($res['achievementcategory']) === false){
				
				if($res['parentid'] == 0) $parentNode = &$tree;
				else $parentNode = &$tree->getChildById($res['parentid']);
				
				if($parentNode === false || $parentNode === null){
					
					echo "\nerror in building tree: trying to add child with id: ". $res['achievementcategory'] ."\n";
					echo "but missing parent with id: ".$res['parentid']. "\n\n";
					print_r($tree);
					die();
					
				}//if
				
				$child = &$parentNode->addChild($res['achievementcategory'], array('categoryname' => $res['categoryname']));
				
			}//if
			
			$vars["NAMECAT$catid"] = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $res['categoryname']);
			$vars["ACHIEVEMENTSNUM$catid"] = $vars["ACHIEVEMENTPOINTS$catid"] = 
				$vars["ACHIEVEMENTSNUMTOTAL$catid"] = $vars["ACHIEVEMENTPOINTSTOTAL$catid"] = 0;
			
		}//while
		
		
		$qry = "SELECT * FROM achievements a 
							JOIN achievements_$arsenaldataLang al ON al.achievementid = a.achievementid;";
							
		$stmt =& $arsenaldata->query($qry);
		while($res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC)){
			
			//only add achievements to the tree that are reachable
			if($res['faction'] == -1 || $res['faction'] == $DATA['factions'][$this->race]){
				
				$achieved = (array_key_exists($res['achievementid'], $this->achievements)) ? true : false;
				$node = &$tree->getChildById($res['achievementcategory']);
				
				$node->data[] = array('achid'=>$res['achievementid'], 
										'achname'=>str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $res['achievementname']),
										'achievementpoints'=>$res['achievementpoints'], 
										'achievementdescription'=>str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $res['achievementdescription']),
										'achievementicon'=>$res['icon'],
										'achieved'=>$achieved);
				
				if($node instanceof ParentBasedTree){
					
					$categories = $node->getPath();
					if(!empty($categories)){
						
						foreach($categories as $category){
							
							$tmp = &$tree->getChildById($category);
							$tmp->achPoints += $res['achievementpoints'];
							$tmp->numAch += 1;
							
							if($achieved) $tmp->reachedPoints += $res['achievementpoints'];
							if($achieved) $tmp->reachedAch += 1;
							
							$vars["ACHIEVEMENTSNUM$category"] = $tmp->reachedAch;
							$vars["ACHIEVEMENTPOINTS$category"] = $tmp->reachedPoints;
							$vars["ACHIEVEMENTSNUMTOTAL$category"] = $tmp->numAch;
							$vars["ACHIEVEMENTPOINTSTOTAL$category"] = $tmp->achPoints;
							
						}//foreach
					}//if
				}//if
			}//if
		}//while
		
		//the root categories
		$rootChildren = $tree->getChildrenIds();
		foreach($rootChildren as $child){
			
			if($child != 81 && $child != 1){
				
				$vars["ACHIEVEMENTPOINTS"] += $vars["ACHIEVEMENTPOINTS$child"];
				$vars["ACHIEVEMENTSNUM"] += $vars["ACHIEVEMENTSNUM$child"];
				$vars["ACHIEVEMENTSNUMTOTAL"] += $vars["ACHIEVEMENTSNUMTOTAL$child"];
				$vars["ACHIEVEMENTPOINTSTOTAL"] += $vars["ACHIEVEMENTPOINTSTOTAL$child"];
				
			}//if
			
		}//foreach
		
		return $vars;
		
	}//prepareAchievementTemplateVars()
	
	
	/**
     * calculates the talent-tree
	 * @access private
	 * @global obj $arsenaldata
	 * @global array $DATA
	 * @return void
    */
	private function parseTalents($spells, $spec){
		
		global $arsenaldata;
		global $DATA;
		
		$qry = "SELECT * FROM talents WHERE classId=".$this->class." ORDER BY tabId, rowNo, colNo;";
		$stmt =& $arsenaldata->query($qry);
		while($res = $stmt->fetchRow(MDB2_FETCHMODE_ASSOC)){
			
			$points = 0;
			if(in_array($res['spellIdRank5'], $spells)) $points = 5;
			else if(in_array($res['spellIdRank4'], $spells)) $points = 4;
			else if(in_array($res['spellIdRank3'], $spells)) $points = 3;
			else if(in_array($res['spellIdRank2'], $spells)) $points = 2;
			else if(in_array($res['spellIdRank1'], $spells)) $points = 1;
			else $points = 0;
			
			if($spec == 1){
				
				$this->talents1[$res['tabId']] .= $points;
				if($res['tabId'] == $DATA['talentTrees'][$this->class][0]) $this->numTalents1[1] += $points;
				if($res['tabId'] == $DATA['talentTrees'][$this->class][1]) $this->numTalents1[2] += $points;
				if($res['tabId'] == $DATA['talentTrees'][$this->class][2]) $this->numTalents1[3] += $points;
				
			}else if($spec == 2){
				
				$this->talents2[$res['tabId']] .= $points;
				if($res['tabId'] == $DATA['talentTrees'][$this->class][0]) $this->numTalents2[1] += $points;
				if($res['tabId'] == $DATA['talentTrees'][$this->class][1]) $this->numTalents2[2] += $points;
				if($res['tabId'] == $DATA['talentTrees'][$this->class][2]) $this->numTalents2[3] += $points;
				
			}//if
		}//while
		
		$tree1 = $DATA['talentTrees'][$this->class][0];
		$tree2 = $DATA['talentTrees'][$this->class][1];
		$tree3 = $DATA['talentTrees'][$this->class][2];
		
		if($spec == 1) $this->talents1 = $this->talents1[$tree1] . $this->talents1[$tree2] . $this->talents1[$tree3];
		if($spec == 2) $this->talents2 = $this->talents2[$tree1] . $this->talents2[$tree2] . $this->talents2[$tree3];
		
	}//parseTalents()

}//class Character{}

?>