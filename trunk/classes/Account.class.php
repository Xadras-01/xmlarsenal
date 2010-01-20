<?php

/**
 * Account.class.php
 * 
 * Every object oft this class represents an ingame-account. The class provides the 
 * accounts database persistence layer.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/12/10
 * @package XMLArsenal
 * @subpackage classes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

class Account{
	
	/**
	 * the ID used by XMLArsenal in arsenal_areanateams
     * @access private
     * @var int
     */
	private $entryId;
	
	/**
	 * the username
     * @access private
     * @var string
     */
	private $userName;
	
	/**
	 * the hashed password of the user (salted sha1)
     * @access private
     * @var string
     */
	private $userPassword;
	
	/**
	 * boolean to distinguis between valid and invalid accounts
     * @access private
     * @var string
     */
	private $valid;
	
	/**
	 * all the bookmarks stored for this account
     * @access private
     * @var array
     */
	private $bookmarks;
	
	/**
	 * the characters of this account (over all realms)
     * @access private
     * @var array
     */
	private $characters;
	
	/**
	 * the players settings in XMLArsenal
     * @access private
     * @var array
     */
	private $settings;
	
	
	/**
     * Constructor sets up the account (invokes fetch- and update-functions)
	 * @access public
	 * @param string $userName
	 * @param string $userPassword
	 * @global array $realms
	 * @global obj $arsenaldb
	 * @uses Account::fetchCachedData()
	 * @return void
    */
	public function __construct($userName = null, $userPassword = null) {
		
		global $realms;
		global $arsenaldb;
		
		if(!$userName || !$userPassword){
			
			die("No user name or password given.");
			
		}//if
		
		//load settings from arsenal db, performUpdate is NOT needed as we update every time a session is over
		$isNewAcc = $this->fetchCachedData($userName, $userPassword);
		
		//reset & read everything anew
		$this->valid = false;
		$this->characters = array();
		
		foreach($realms as $realm){
			
			$this->characters[$realm->realmId] = array();
			$realm->loadDataGrabber();
			$grabber = $realm->getDataGrabber();
			
			$loginData = $grabber->tryLogin($userName, $userPassword);
			if($loginData['valid'] == true){
				
				$this->valid = true;
				$this->characters[$realm->realmId] = array_merge($this->characters[$realm->realmId], $loginData['characters']);
				
			}//if
			
		}//foreach
		
		//update the object in db
		if($this->valid == true){
			
			$this->userName = $userName;
			//use "modern" salted sha256 algo
			$this->userPassword = hash('sha256', $userName.$userPassword);
			
			//set a default selected char
			if($isNewAcc == true){
				
				//determine most important character
				$tmp_level = 0;
				foreach($this->characters as $realmId=>$charsOnRealm){
					
					if(!empty($charsOnRealm)){
						
						foreach($charsOnRealm as $char){
							
							if($char['level'] > $tmp_level){
								
								$selectedName = $char['name'];
								$selectedRealm = $realmId;
								$tmp_level = $char['level'];
								
							}//if
						}//foreach
					}//if
				}//foreach
				
				$this->settings['selectedChars'][1]['name'] = $selectedName;
				$this->settings['selectedChars'][1]['realmId'] = $selectedRealm;
				
			}//if
			
			$sql = 'UPDATE arsenal_accounts SET 
						data = ' . $arsenaldb->quote(serialize($this), 'text') .'
						WHERE entry_id = '. $arsenaldb->quote($this->entryId, 'integer') . '
						LIMIT 1';
				
			$res =& $arsenaldb->exec($sql);
			if (PEAR::isError($res)) {
				die($res->getUserinfo());
			}//if
			
		}//if
		
	}//__construct()
	
	
	/**
     * This function fetches the serialized "Account"-object from the arsenal database (if it contains the specified object). Otherwise it invokes the {@link firstLoad()}-function
	 * @access private
	 * @param string $userName
	 * @param string $userPassword
	 * @global obj $arsenaldb
	 * @return bool $isNewAcc
    */
	private function fetchCachedData($userName = null, $userPassword = null){
		
		global $arsenaldb;
		
		//use "modern" salted sha256 algo
		$password_hash = hash('sha256', $userName.$userPassword);
		
		//get player data from cache-db
		$sql = 'SELECT entry_id, user_name, data FROM arsenal_accounts WHERE 
					user_name = '	.	$arsenaldb->quote($userName,   'text')   .' AND 
					user_password_hash ='	.	$arsenaldb->quote($password_hash,   'integer')   .' 
					LIMIT 1;';
			
		$res =& $arsenaldb->query($sql);
		
		if (PEAR::isError($res)) {
			die($res->getUserinfo());
		}
		
		if($res->numRows() == 0){
			
			$id = $arsenaldb->nextID('arsenal_accounts', true);
			$this->entryId = $id;
			$this->settings = array();
			$this->settings['selectedChars'] = array();
			
			$sql = 'INSERT INTO arsenal_accounts VALUES ('
						. $arsenaldb->quote($id,   'integer')   .', '
						. $arsenaldb->quote($userName, 'text')      .', '
						. $arsenaldb->quote($password_hash, 'text')      .', '
						. $arsenaldb->quote(serialize($this), 'text') .')';
				
			$res =& $arsenaldb->exec($sql);
			if (PEAR::isError($res)) {
				die($res->getUserinfo());
			}//if
			
			return true;
			
		}else{
			
			$arr = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
			$arr = unserialize($arr['data']);
			
			foreach($arr as $key=>$value){
				$this->$key = $value;
			}//foreach
			
			return false;
			
		}//else
		
	}//fetchCachedData()
	
	
	/**
     * getter for {@link $valid}
	 * @access public
	 * @return bool
    */
	public function isValid(){
		
		return $this->valid;
		
	}//isValid()
	
	
	/**
     * saves the object data to db, called explicitly
	 * @access private
	 * @global obj $arsenaldb
	 * @return void
    */
	private function saveSettings(){
		
		global $arsenaldb;
		
		$sql = 'UPDATE arsenal_accounts SET 
					data = ' . $arsenaldb->quote(serialize($this), 'text') .'
					WHERE entry_id = '. $arsenaldb->quote($this->entryId, 'integer') . '
					LIMIT 1';
			
		$res =& $arsenaldb->exec($sql);
		if (PEAR::isError($res)) {
			die($res->getUserinfo());
		}//if
		
		
	}//saveSettings()
	
	
	/**
     * adds a bookmark
	 * @access public
	 * @global array $realms
	 * @return void
    */
	public function addBookmark($charName, $realmName){
		
		global $realms;
		
		if(!isset($this->bookmarks)) $this->bookmarks = array();
		
		$this->bookmarks[] = array();
		
		
	}//addBookmark()
	
	
	/**
     * selects a character and saves the selection to the db
	 * @access public
	 * @return void
    */
	public function resetSelectedCharacters(){
		
		$this->settings['selectedChars'] = array();
		
	}//resetSelectedCharacters()
	
	
	/**
     * selects a character and saves the selection to the db
	 * @access public
	 * @global array $realms
	 * @uses Account::saveSettings()
	 * @return void
    */
	public function selectCharacter($realmName = null, $charName = null, $slot = null){
		
		global $realms;
		
		//select a char
		foreach($realms as $realm){
			
			if($realm->realmName == $realmName){
				
				$this->settings['selectedChars'][$slot]['name'] = $charName;
				$this->settings['selectedChars'][$slot]['realmId'] = $realm->realmId;
				
			}//if
		}//foreach
		
		$this->saveSettings();
		
	}//selectCharacter()
	
	
	/**
     * returns an array filled with several variables that are used in character-select.xml
	 * @access public
	 * @global array $realms
	 * @global string $language
	 * @global string $realmpool
	 * @global array $DATA
	 * @return array
    */
	public function prepareCharacterSelectSheet(){
		
		global $realms;
		global $language;
		global $realmpool;
		global $DATA;
		
		$vars['LANGUAGE'] = $language;
		$vars['NUMCHARS'] = 0; //only init, see below
		$vars['SELECTED'] = 0; //only init, see below
		$vars['USERNAME'] = $this->userName;
		
		$vars['CHARACTERS'] = '';
		if(!empty($this->characters)){
			
			foreach($this->characters as $realmId=>$charsOnRealm){
				
				if(!empty($charsOnRealm)){
					
					foreach($charsOnRealm as $char){
						
						$charvars = array();
						$charvars["USERNAME"] = $this->userName;
						$charvars["ACHPOINTS"] = $char['achpoints'];
						$charvars["CLASSID"] = $char['class'];
						$charvars["FACTIONID"] = $DATA["factions"][$char['race']];
						$charvars["GENDERID"] = $char['gender'];
						$charvars["LEVEL"] = $char['level'];
						$charvars["NAME"] = $char['name'];
						$charvars["RACEID"] = $char['race'];
						$charvars["GUILD"] = $char['guild'];
						$charvars["GUILDURL"] = urlencode($char['guild']);
						$charvars["RELEVANCE"] = round($char['level'] * 100 / 80);
						$charvars["REALM"] = $realms[$realmId]->realmName;
						$charvars["CHARURL"] = "r=".urlencode($realms[$realmId]->realmName)."&amp;n=".urlencode($char['name']);
						$vars['NUMCHARS']++;
						$charvars["CHARSELECTED"] = '';
						
						if($this->settings['selectedChars'][1]['name'] == $char['name'] && 
							$this->settings['selectedChars'][1]['realmId'] == $realmId){
							
							$charvars["CHARSELECTED"] = 'selected="1"';
							$vars['SELECTED']++;
							
						}//if
						
						if($this->settings['selectedChars'][2]['name'] == $char['name'] && 
							$this->settings['selectedChars'][2]['realmId'] == $realmId){
							
							$charvars["CHARSELECTED"] = 'selected="2"';
							$vars['SELECTED']++;
							
						}//if
						
						if($this->settings['selectedChars'][3]['name'] == $char['name'] && 
							$this->settings['selectedChars'][3]['realmId'] == $realmId){
							
							$charvars["CHARSELECTED"] = 'selected="2"';
							$vars['SELECTED']++;
							
						}//if
						
						$vars['CHARACTERS'] .= fgettemplate('./xml-templates/character-select-character.xml', $charvars);
						
					}//foreach
					
				}//if
			}//foreach
		}//if
		
		return $vars;
		
	}//prepareCharacterSelectSheet()
	
	
}//class Account{}