<?php

/** 
* Trinity_PVPDataGrabber.class.php 
* 
* This is the example implementation of a DataGrabber for XMLArsenal. 
* If you like to use your own DataGrabber-Class just implement the functions in such a way that they use 
* the same function-names and produce the same output as the functions shown in this file. 
*
* @author Amras Taralom <amras-taralom@streber24.de> 
* @author Ytrosh 
* @version 0.6, last modified 2010/06/05
* @package XMLArsenal 
* @subpackage classes 
* @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3) 
* 
*/ 
  
require dirname(__FILE__).'/fielddefs.php'; //load fieddefs

class Trinity_PVPDataGrabber{ 
  
    /** 
     * the ID of Char 
    * @access private 
    * @var int 
    */ 
    private $charid;     
     
     
    /** 
     * the name of char 
    * @access private 
    * @var string 
    */ 
    private $charname; 
  
    /** 
     * the characters gender (0=male, 1=female) 
    * @access private 
    * @var int 
    */ 
    private $gender; 
     
     
    /** 
     * the chartable from characters Table 
    * guid, name, data, race, class 
     * @access private 
    * @var array 
     * i.e. $chartable2['name'] or $chartable2['race'] 
    */ 
    private $chartable2; 
     
     
    /** 
     * exploded datastring from characters 
    * @access private 
    * @var array 
    */ 
    private $data; 
  
  
    /** 
     * exploded datastring of Item from item_instance 
    * @access private 
    * @var array 
    */ 
    private $idata; 
     
     
    /** 
     * the level of a character 
    * @access private 
    * @var int 
    */ 
     private $level; 
     
     
     /** 
     * the instantaneous arenapoint of a character 
    * @access private 
    * @var int 
    */ 
    private $arenapoints; 
  
  
     /** 
     * the guildname 
    * @access private 
    * @var string 
    */ 
     private $guildname; 
  
  
     /** 
     * the guild id 
    * @access private 
    * @var int 
    */ 
     private $guildid; 
  
  
     /** 
     * all honorable kills of a character 
    * @access private 
    * @var int 
    */ 
    private $honorablekills; 
  
  
     /** 
     * ka, mal schauen....^^ 
    * @access private 
    * @var int 
    */ 
    private $title; 
  
  
     /** 
     * all basestats of a character 
    * strength, agility, stamina, int, spirit, arms 
     * @access private 
    * @var array 
     * i.e. $basestats['strength'] or $basestats['arms'] 
    */ 
     private $basestats; 
  
  
     /** 
     * all magic resistances of a character 
    * holy, fire, nature, frost, shadow, , arcane 
     * @access private 
    * @var array 
     * i.e. $resistances['holy'] or $resistances['frost'] 
    */ 
     private $resistances; 
  
  
     /** 
     * hitrating of a character 
    * melee, ranged, holyspell, spellfire, spellnature, spellfrost, spellshadow,  spellarcane 
     * @access private 
    * @var array 
     * i.e. $hitrating['melee'] or $hitratings['spellfire'] 
    */ 
     private $hitratings; 
  
  
     /** 
     * critrating of a character 
    * melee, ranged, holyspell, spellfire, spellnature, spellfrost, spellshadow,  spellarcane 
     * @access private 
    * @var array 
     * i.e. $critratings['melee'] or $critratings['spellfire'] 
    */ 
     private $critratings; 
  
  
     /** 
     * reputation of a character 
    * melee, ranged, holyspell, spellfire, spellnature, spellfrost, spellshadow,  spellarcane 
     * @access private 
    * @var array 
     * i.e. $x=reputition id ; $reputation[$x] 
    */ 
     private $reputation; 
  
  
     /** 
     * race of a character 
    * 1=Human; 2=Orc; 3=Dwarf; 4=Night Elf; 5=Undead; 6=Tauren; 7=Gnome; 8=Troll; 10=Blood Elf; 11=Draenei 
     * @access private 
    * @var int 
    */ 
     private $race; 
  
  
     /** 
     * items of a character 
    * position: 
     * head=0; neck=1; shoulder=2; shirt=3; chest=4; belt=5; legs=6; feet=7; wrist=8; 
     * gloves=9; finger 1 =10; finger 2 =11; trinket 1 =12; trinket 2=13; back=14; main hand=15 
     * off hand=16 ranged=17 atabard=18 
     * 
     * $y='id' or 'vz' or 'socket1' or 'socket2' or 'socket3' 
     * @access private 
    * @var array 
     * i.e. $x=position ; $items[$x][$y] 
    */ 
     private $items; 
  
  
     /** 
     * achievements of a character 
    * increment numbered 
     * @access private 
    * @var array 
     * i.e. $achievements[$x] 
    */ 
     private $achievements; 
	 
	  /** 
     * achievementcriteria of a character 
    * increment numbered 
     * @access private 
    * @var array 
     * i.e. $achievements[$x] */
	 private $achievementcriteria;
  
  
   /** 
     * the ladder
     * @access private 
    * @var array 
    */ 
     private $arenaLadder; 
	 
     /** 
     * all talents of a character 
    * increment numbered 
     * @access private 
    * @var array 
     * 
     * i.e. $talents[0][x] Speccung 1 ///  $talents[1][x] Speccung 2 
    */ 
     private $talents; 
  
     /** 
     * all glyphe of a character 
    * increment numbered 
     * @access private 
    * @var array 
     * 
     * i.e. $glyphe[0][x] Speccung 1 ///  $glyphe[1][x] Speccung 2 
    */ 
     private $glyphe; 
     /** 
     * db connecter 
    * @access private 
    * @var string 
    */ 
     private $pvpdbconn; 
  
  
     /** 
     * memberguid of a character 
    * increment numbered 
     * @access private 
    * @var array 
     * 
     * i.e. $memberguid[$x] 
    */ 
     private $memberguid; 
  
  
     /** 
     * membercount 
    * @access private 
    * @var int 
    */ 
     private $membercount; 
  
  
     /** 
     * memberguid of a character 
    * increment numbered $x 
     * @access private 
    * @var array 
     * $y=memberguid ; $y=memberrank ; $y=membername; 
     * i.e. $memberguid[$x][$y] 
    */ 
     private $memberstatus; 
  
  
     /** 
     * arenateams of a player 
    * @access private 
    * @var array 
     * $x=2 or $x=3 or $x=5 
     * i.e. $arenateamsidplayer[$x] 
    */ 
     private $arenateamsidbyplayer; 
     
     
     /** 
     * the table from arena_team 
    * arenateamid, name, captainguid, type, BackroundColor, EmblemStyle, EmblemColor, BorderStyle, BorderColor 
     * @access private 
    * @var array 
     * i.e. $arenaTable2['type'] or $arenaTable2['BackroundColor'] 
    */ 
    private $arenaTable2; 
  
     /** 
     * the arenateam id 
    * @access private 
    * @var int 
    */ 
     private $teamid; 
  
  
       /** 
     * the arenateam name 
    * @access private 
    * @var string 
    */ 
     private $teamname; 
	 
	 
	 
	 
	 
	 private $CharactersResults;
	 
	 private $SearchTeamsResults;
	 
	 private $getSearchGuildsResults;
	 
	 private $searchstring;
	 
	 private $ArenaTeammember;
	 
	 private $ArenaTeamproperties;
  
  
private function int2float($int) { 
      
      //convert to 32-bit binary 
      $bin = str_pad(decbin($int), 32, '0', STR_PAD_LEFT); 
      
      //extract float parts 
      $sign = bindec(substr($bin, 0, 1)); 
      $exp = bindec(substr($bin, 1, 8)); 
      $mant = bindec(substr($bin, 9)); 
      
      //calculate float value 
      $floatval = (1- 2*$sign) * (1+ $mant * pow(2, -23)) * pow(2, $exp - 127); 
      
      return $floatval; 
  } 
  
public function __construct(){ 
     
    //$this->pvpdbconn = mysql_connect("localhost", "localuser", "userpassword", true) or die(get_class($this).": no connection to database."); 
    //@mysql_select_db("pvp_char", $this->pvpdbconn) or die (get_class($this).": not able to select specified database."); 
	
	//extra passwords
	if(file_exists('./classes/dataGrabbers/GrabberConfig.php')) include './classes/dataGrabbers/GrabberConfig.php';
	
    @mysql_query("SET NAMES 'utf8'", $this->pvpdbconn); 
    @mysql_query("SET CHARACTER SET 'utf8'", $this->pvpdbconn); 
} 


/* account tables */

public function tryLogin($user,$password){
	
	if((strlen($user) < 3) || $password == null){
		return false;
	}//if
	
	//$logindb = mysql_connect("localhost", "localuser", "userpassword", true) or die(get_class($this).": no connection to login database.");
	//@mysql_select_db("logon", $logindb) or die (get_class($this).": not able to select specified database in login db.");
	
	//extra passwords
	if(file_exists('./classes/dataGrabbers/GrabberConfig.php')) include './classes/dataGrabbers/GrabberConfig.php';
	
	@mysql_query("SET NAMES 'utf8'", $logindb); 
    @mysql_query("SET CHARACTER SET 'utf8'", $logindb);
	
	$res = @mysql_query("SELECT id, username FROM `account` WHERE username = '".mysql_real_escape_string($user)."' AND sha_pass_hash = '".$this->sha_password($user,$password)."' LIMIT 1;", $logindb);
	if(mysql_num_rows($res) < 1) return array('valid'=>false);
	
	$row = @mysql_fetch_assoc($res);
	$id = (int)$row['id'];
	$username = $row['username'];
	
	//now fetch characters
	$characters = array();
	$res = @mysql_query("SELECT c.race,c.class,c.gender,c.level,c.name, g.name as gname FROM `characters` c LEFT JOIN `guild_member` gm on gm.guid = c.guid LEFT JOIN `guild` g on g.guildid=gm.guildid WHERE account = ".$id.";", $this->pvpdbconn); 
    while($row = @mysql_fetch_assoc($res)){
		
		$achpoints = 0;
		$characters[] = array('name'=>$row['name'], 'race'=>$row['race'], 'class'=>$row['class'], 'gender'=>$row['gender'], 'level'=>$row['level'], 'guild'=>$row['gname'], 'achpoints'=>$achpoints);
		
	}//while
	
	return array('valid'=>true, 'characters'=>$characters);
}

private function sha_password($user,$pass)
{
    $user = $this->fullUpper($user);
    $pass = $this->fullUpper($pass);
	return sha1($user.':'.$pass); 
}

private function fullUpper($str){
   
   // convert to entities
   $subject = htmlentities($str,ENT_QUOTES);
   $pattern = '/&([a-z])(uml|acute|circ';
   $pattern.= '|tilde|ring|elig|grave|slash|horn|cedil|th);/e';
   $replace = "'&'.strtoupper('\\1').'\\2'.';'";
   $result = preg_replace($pattern, $replace, $subject);
   
   // convert from entities back to characters
   $htmltable = get_html_translation_table(HTML_ENTITIES);
   foreach($htmltable as $key => $value) {
      $result = ereg_replace(addslashes($value),$key,$result);
   }
   
   return(strtoupper($result));
}



/* char tables */


private function CharTable($player)     //chartable with guid 
    { 
    $res = @mysql_query("SELECT guid, name, data, race, class FROM `characters` WHERE guid='".mysql_real_escape_string($player)."' LIMIT 1;", $this->pvpdbconn); 
    $chartable2 = @mysql_fetch_assoc($res); 
    return $chartable2; 
    } 
  
  
private function CharTable2($player)    //chartable with name 
    { 
    $res = @mysql_query("SELECT guid, name, data, race, class FROM `characters` WHERE convert(name using utf8) COLLATE utf8_bin  = '".mysql_real_escape_string($player)."' LIMIT 1;", $this->pvpdbconn); 
    $chartable2 = @mysql_fetch_assoc($res); 
    return $chartable2; 
    } 
  
  
private function GuildTable($guild) //guildtable with id 
    { 
    $res = @mysql_query("SELECT * FROM `guild` WHERE guildid='".mysql_real_escape_string($guild)."' LIMIT 1;", $this->pvpdbconn); 
    $guildtable2 = @mysql_fetch_assoc($res); 
    return $guildtable2; 
    } 
  
private function GuildTable2($guild) //guildtable with name 
    { 
    $res = @mysql_query("SELECT * FROM `guild` WHERE convert( name USING utf8 ) COLLATE utf8_bin = '".mysql_real_escape_string($guild)."' LIMIT 1;", $this->pvpdbconn); 
    $guildtable2 = @mysql_fetch_assoc($res); 
    return $guildtable2; 
    } 
  
  
private function ArenaTable2($team) //arenatable with name 
    { 
    $res = mysql_query("SELECT a.arenateamid, a.name,a.type,a.BackgroundColor,a.EmblemStyle,a.EmblemColor,a.BorderStyle,a.BorderColor, ats.rating, ats.rank, ats.games, ats.wins, ats.played, ats.wins2, c.race FROM `arena_team` a JOIN `arena_team_stats` ats on a.arenateamid = ats.arenateamid JOIN characters c ON c.guid = a.captainguid WHERE convert( a.name USING utf8 ) COLLATE utf8_bin = '".mysql_real_escape_string($team)."' LIMIT 1;", $this->pvpdbconn); 
    $arenaTable2 = @mysql_fetch_assoc($res);
    return $arenaTable2; 
    } 
  
private function ArenaTable($team) //arenatable with id 
    { 
	$res = mysql_query("SELECT a.arenateamid, a.name,a.type,a.BackgroundColor,a.EmblemStyle,a.EmblemColor,a.BorderStyle,a.BorderColor, ats.rating, ats.rank, ats.games, ats.wins, ats.played, ats.wins2, c.race FROM `arena_team` a JOIN `arena_team_stats` ats on a.arenateamid = ats.arenateamid JOIN characters c ON c.guid = a.captainguid WHERE a.arenateamid='".mysql_real_escape_string($team)."' LIMIT 1;", $this->pvpdbconn); 
    $arenaTable2 = @mysql_fetch_assoc($res); 
    return $arenaTable2; 
    } 
  
  
private function setData($player) 
    { 
    $this->data = explode(' ',$this->chartable['data']); 
    } 
  
private function setMembertable($guild) 
    { 
    $this->membercount=0; 
    $res = mysql_query("SELECT guid, rank FROM `guild_member` WHERE guildid ='".$this->guildid."';", $this->pvpdbconn); 
    while($arr = @mysql_fetch_assoc($res)) 
            { 
            $this->memberguid[$this->membercount]=$arr['guid']; 
            $this->memberrank[$this->membercount]=$arr['rank']; 
            $this->membercount=$this->membercount+1; 
            }     
    return $this->memberstatus; 
    } 
  
  
  
  
public function setSearchString($queryString)
	{
	$this->searchstring=$queryString;
	}

  
public function setGuildId($guild) 
    { 
        if (!ctype_digit($guild)) //$guild is a name 
        {             
        $this->guildtable=$this->GuildTable2($guild); 
        $this->guildid = $this->guildtable['guildid']; 
        $this->guildname=$guild; 
        } 
        else 
            {    //$guild is id 
            $this->setGuildName($guild); 
            $this->guildid=$guild; 
            } 
    $this->setMembertable($this->guildid); 
    } 
  
  
public function setGuildName($guild) 
    { 
        if (!ctype_digit($guild)) 
        {                            //$guild is a name 
        $this->guildname=$guild; 
        $this->setGuildId($guild); 
         
        }else 
            {    //$guild is id 
            $this->guildtable=$this->guildTable($guild); 
            $this->guildid = $guild; 
            $this->guildname=$this->guildtable['name']; 
            } 
    $this->setMembertable($this->guildid); 
    } 
  
  
public function setArenaTeamName($team) 
    { 
        if (!ctype_digit($team)) 
        {                            //$team is a name 
        $this->teamname=$team; 
        $this->setArenaTeamId($team); 
         
        }else 
            {    //$team is id 
            $this->teamtable=$this->ArenaTable($team); 
            $this->teamid = $team; 
            $this->teamname=$this->teamtable['name']; 
            } 
    } 
  
public function setArenaTeamId($team) 
    { 
        if (!ctype_digit($team)) //$teamis a name 
        {             
        $this->teamtable=$this->ArenaTable2($team); 
        $this->teamid = $this->teamtable['arenateamid']; 
        $this->teamname=$team; 
        } 
        else 
            {    //$team is id 
            $this->setArenaTeamName($team); 
            $this->teamid=$team; 
            } 
     
    } 
  
  
public function setCharId($player) 
    { 
    if (!ctype_digit($player)) //$player is a name 
        {             
        $this->chartable=$this->CharTable2($player); 
        $this->charid = $this->chartable['guid']; 
        $this->charname=$player; 
        } 
        else 
            {    //$player is id 
            $this->setCharName($player); 
            $this->charid=$player; 
            } 
    $this->setData($player); 
    } 
  
  
public function getCharId() 
    { 
    if($this->charid) return $this->charid; 
    else return null; 
    } 
  
public function setCharName($player) 
    { 
    if (!ctype_digit($player)) 
        {                            //$player is a name 
        $this->charname=$player; 
        $this->setCharId($player); 
         
        }else 
            {    //$player is id 
            $this->chartable=$this->CharTable($player); 
            $this->charid = $player; 
            $this->charname=$this->chartable['name']; 
            } 
    $this->setData($player); 
    } 
  
public function getName($player) 
    { 
    if (!ctype_digit($player)) 
        {                            //$player is a name 
        $this->charname=$player; 
        $this->setCharId($player); 
         
        }else 
            {    //$player is id 
            $this->chartable=$this->CharTable($player); 
            $this->charid = $player; 
            $this->charname=$this->chartable['name']; 
            } 
    $this->setData($player); 
    return $this->charname; 
    } 
  
public function getPrimaryProfessions(){
	
	$eUnitFields =& $GLOBALS['eUnitFields'];
	
	$professions = array(171, 186, 202, 773, 755, 182, 393, 165, 164, 197, 333);
	$data = $this->data;
	$resultset = array();
	
	for($i = $eUnitFields['PLAYER_SKILL_INFO_1_1']; $i < $eUnitFields['PLAYER_CHARACTER_POINTS1']; $i+=3){
		
		$priProfId = $data[$i];
		if($priProfId > 65536) $priProfId = $priProfId - 65536;
		
		if(in_array($priProfId, $professions)){
			
			$values = $data[$i+1];
			$values = base_convert($values, 10, 2);
			$values = str_pad($values, 32, 0, STR_PAD_LEFT);
			$max = bindec(substr($values, 0, 16));
			$cur = bindec(substr($values, 16, 16));
			
			array_push($resultset, array('prof'=>$priProfId, 'cur'=>$cur, 'max'=>$max));
		}//if
	}//for
	
	return $resultset;

}//getPrimaryProfessions()
  
//NUR zum testen 

public function getData($player) 
    { 
    $this->data = explode(' ',$this->chartable['data']); 
    return $this->data; 
    } 
  
//Nur zum Testen 

public function getChosenTitleMask(){
	
	$eUnitFields =& $GLOBALS['eUnitFields'];
	
	//this is from CharTitles.dbc column 37: titleMaskID (Integer, used ingame in the drop down menu)
	return $this->data[$eUnitFields['PLAYER_CHOSEN_TITLE']];
	
}//getChosenTitleMask()


public function getAchievementFirsts($realmName, $firstIDs){
	
	$achievements = array();
	
	if(!empty($firstIDs)){
		
		$incond = "";
		foreach($firstIDs as $id){
			$incond .= $id . ",";
		}//foreach
		
		//cut last comma
		$incond = substr($incond, 0 , -1);
		
		$res = mysql_query("SELECT c.race,c.class,c.gender,c.level, c.name, g.name as gname, ca.achievement, ca.date FROM `characters` c LEFT JOIN character_achievement ca ON ca.guid = c.guid LEFT JOIN `guild_member` gm on gm.guid = c.guid LEFT JOIN `guild` g on g.guildid=gm.guildid WHERE ca.achievement IN (".$incond.");", $this->pvpdbconn); 
		while($arr = mysql_fetch_assoc($res)){
			
			$achievements[] = array('realmName' 	=> $realmName,
									'classId'		=> $arr['class'],
									'genderId'		=> $arr['gender'],
									'level'			=> $arr['level'],
									'guild'			=> $arr['gname'],
									'name'			=> $arr['name'],
									'raceId'		=> $arr['race'],
									'date'			=> $arr['date'],
									'achievementid'	=> $arr['achievement']);
			
		}//while
	
	}//if
	
	return $achievements;
	
}//getAchievementFirsts()


public function getSearchCharactersResults($realmName)
	{
	$x=0;
	$this->SearchCharactersResults = array(); 
    $res = mysql_query("SELECT c.race,c.class,c.gender,c.level,c.name, g.name as gname FROM `characters` c LEFT JOIN `guild_member` gm on gm.guid = c.guid LEFT JOIN `guild` g on g.guildid=gm.guildid WHERE c.name LIKE '".$this->searchstring."%';", $this->pvpdbconn); 
	while($arr = mysql_fetch_assoc($res))
		{
		$this->SearchCharactersResults[$x]['realmName']=$realmName;
		$this->SearchCharactersResults[$x]['classId']=$arr['class'];
		$this->SearchCharactersResults[$x]['genderId']=$arr['gender'];
		$this->SearchCharactersResults[$x]['guild']=$arr['gname'];
		$this->SearchCharactersResults[$x]['level']=$arr['level'];
		$this->SearchCharactersResults[$x]['name']=$arr['name'];
		$this->SearchCharactersResults[$x]['raceId']=$arr['race'];
		$x++;
		}
	return $this->SearchCharactersResults;
	}

public function getSearchTeamsResults($realmName)
	{
	$x=0;
	$this->SearchSearchTeamsResults = array(); 
    $res = mysql_query("SELECT a.name,a.type,a.BackgroundColor,a.EmblemStyle,a.EmblemColor,a.BorderStyle,a.BorderColor, ats.rating, ats.rank, ats.games, ats.wins, ats.played, ats.wins2, c.race FROM `arena_team` a JOIN `arena_team_stats` ats on a.arenateamid = ats.arenateamid JOIN characters c ON c.guid = a.captainguid WHERE a.name LIKE '".$this->searchstring."%';", $this->pvpdbconn); 
    while($arr = mysql_fetch_assoc($res))
		{
		$this->SearchTeamsResults[$x]['realmName']=$realmName;
		$this->SearchTeamsResults[$x]['gamesPlayed']=$arr['games'];
		$this->SearchTeamsResults[$x]['gamesWon']=$arr['wins'];
		$this->SearchTeamsResults[$x]['name']=$arr['name'];
		$this->SearchTeamsResults[$x]['ranking']=$arr['rank'];
		$this->SearchTeamsResults[$x]['rating']=$arr['rating'];
		$this->SearchTeamsResults[$x]['seasonGamesPlayed']=$arr['played'];
		$this->SearchTeamsResults[$x]['seasonGamesWon']=$arr['wins2'];
		$this->SearchTeamsResults[$x]['teamSize']=$arr['type'];
		$this->SearchTeamsResults[$x]['background']= str_pad(base_convert($arr['BackgroundColor'], 10, 16), 8, 0, STR_PAD_LEFT);
		
		$this->SearchTeamsResults[$x]['borderColor']= str_pad(base_convert($arr['BorderColor'], 10, 16), 8, 0, STR_PAD_LEFT);
		$this->SearchTeamsResults[$x]['borderStyle']=$arr['BorderStyle'];
		$this->SearchTeamsResults[$x]['iconColor']=str_pad(base_convert($arr['EmblemColor'], 10, 16), 8, 0, STR_PAD_LEFT);
		$this->SearchTeamsResults[$x]['iconStyle']=$arr['EmblemStyle'];
		$this->SearchTeamsResults[$x]['leaderRace']=$arr['race'];
		$x++;
		}
	return $this->SearchTeamsResults;
	}

public function getSearchGuildsResults($realmName)
	{
	$x=0;
	$this->SearchCharactersResults = array(); 
    $res = mysql_query("SELECT g.BackgroundColor,g.BorderStyle, g.EmblemColor, g.EmblemStyle, g.BorderColor, g.name, c.race FROM `guild` g JOIN characters c on g.leaderguid = c.guid WHERE g.name LIKE '".$this->searchstring."%';", $this->pvpdbconn); 
    while($arr = mysql_fetch_assoc($res))
		{
		$this->SearchGuildsResults[$x]['realmName']=$realmName;
		$this->SearchGuildsResults[$x]['emblemBackground']=$arr['BackgroundColor'];
		$this->SearchGuildsResults[$x]['emblemBorderColor']=$arr['BorderColor'];
		$this->SearchGuildsResults[$x]['emblemBorderStyle']=$arr['BorderStyle'];
		$this->SearchGuildsResults[$x]['emblemIconColor']=$arr['EmblemColor'];
		$this->SearchGuildsResults[$x]['emblemIconStyle']=$arr['EmblemStyle'];
		$this->SearchGuildsResults[$x]['name']=$arr['name'];
		$this->SearchGuildsResults[$x]['leaderRace']=$arr['race'];
		$x++;
		}
	return $this->SearchGuildsResults;
	}

  
  
public function getClass() 
    { 
    return (int)$this->chartable['class']; 
    } 
  
public function getRace() 
    { 
    return (int)$this->chartable['race']; 
    } 
  
  
public function getGender() 
    { 
    $this->gender=($this->data[155]); 
    return (int)$this->gender; 
    } 
  
  
public function getLevel() 
    { 
    $this->level=$this->data[53]; 
    return (int)$this->level; 
    } 
     
     
public function getArenaPoints() 
    { 
    $this->arenapoints=$this->data[1247]; 
    return (int)$this->arenapoints; 
    } 
	
public function getHonorableKills() 
    { 
    return (int)$this->data[1200];
    } 
  
public function getTitle() 
    { 
    $this->title=$this->data[600]; 
    return $this->title; 
    } 
  
  
public function getGuildIdplayer() 
    { 
    $this->guildid=$this->data[151]; 
    return $this->guildid; 
    } 
     
     
     
public function getGuildNameplayer() 
    { 
    $this->guildtable3=$this->guildtable($this->getGuildIdplayer($player)); 
     
    return $this->guildtable3['name']; 
    } 
  
  
public function getCharStats() 
    { 
		
		$eUnitFields =& $GLOBALS['eUnitFields'];
		
		return array( 
					//Grundwerte
					'strength'      		=> $this->data[$eUnitFields['UNIT_FIELD_STAT0']], 
						'strengthPos'		=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_POSSTAT0']])), 
						'strengthNeg'		=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_NEGSTAT0']])),
					'agility'      	 		=> $this->data[$eUnitFields['UNIT_FIELD_STAT1']], 
						'agilityPos'		=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_POSSTAT1']])),  
						'agilityNeg'		=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_NEGSTAT1']])),
					'stamina'       		=> $this->data[$eUnitFields['UNIT_FIELD_STAT2']], 
						'staminaPos'		=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_POSSTAT2']])),  
						'staminaNeg'		=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_NEGSTAT2']])),
					'int'           		=> $this->data[$eUnitFields['UNIT_FIELD_STAT3']], 
						'intPos'			=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_POSSTAT3']])),
						'intNeg'			=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_NEGSTAT3']])), 
					'spirit'       			=> $this->data[$eUnitFields['UNIT_FIELD_STAT4']], 
						'spiritPos'			=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_POSSTAT4']])), 
						'spiritNeg'			=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_NEGSTAT4']])),
					'armor'					=> $this->data[$eUnitFields['UNIT_FIELD_RESISTANCES']],
					  
					//Nahkampf
					'mhMinDmg'     		 	=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_MINDAMAGE']])),
					'mhMaxDmg'        	 	=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_MAXDAMAGE']])),
					'ohMinDmg'        	 	=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_MINOFFHANDDAMAGE']])),
					'ohMaxDmg'        	 	=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_MAXOFFHANDDAMAGE']])),
					'mhTempo'				=> round((int2float($this->data[$eUnitFields['UNIT_FIELD_BASEATTACKTIME']]) / 1000), 2),					
					'ohTempo'				=> round((int2float($this->data[$eUnitFields['UNIT_FIELD_BASEATTACKTIME']+1]) / 1000), 2),
					'mAtp'				 	=> $this->data[$eUnitFields['UNIT_FIELD_ATTACK_POWER']],
						'mAtpMod'			=> $this->data[$eUnitFields['UNIT_FIELD_ATTACK_POWER_MODS']],
					'mHitRating'		 	=> $this->data[$eUnitFields['PLAYER_FIELD_COMBAT_RATING_1']+5],
						'mHitPercent'		=> 0.00,
						'mExpRating'		=> $this->data[$eUnitFields['PLAYER_FIELD_COMBAT_RATING_1'+23]],
							'mExpPercent'	=> 0.00,
					'mCritPercent'		 	=> round(int2float($this->data[$eUnitFields['PLAYER_CRIT_PERCENTAGE']]), 2),
						'mCritRating'		=> $this->data[$eUnitFields['PLAYER_FIELD_COMBAT_RATING_1']+8],					
					'mExpertise'			=> $this->data[$eUnitFields['PLAYER_EXPERTISE']],
					
					//Distanz
					'rMinDmg'   		 	=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_MINRANGEDDAMAGE']])),
					'rMaxDmg'  		 	 	=> round(int2float($this->data[$eUnitFields['UNIT_FIELD_MAXRANGEDDAMAGE']])),
					'rTempo'				=> round((int2float($this->data[$eUnitFields['UNIT_FIELD_RANGEDATTACKTIME']]) / 1000), 2),
					'rAtp'		 			=> $this->data[$eUnitFields['UNIT_FIELD_RANGED_ATTACK_POWER']],
						'rAtpMod'	 		=> $this->data[$eUnitFields['UNIT_FIELD_RANGED_ATTACK_POWER_MODS']],		
					'rHitRating'		 	=> $this->data[$eUnitFields['PLAYER_FIELD_COMBAT_RATING_1']+6],
						'rHitPercent'		=> 0.00,
						'rExpRating'		=> $this->data[$eUnitFields['PLAYER_FIELD_COMBAT_RATING_1'+23]],
							'rExpPercent'	=> 0.00,	
					'rCritPercent'			=> round(int2float($this->data[$eUnitFields['PLAYER_RANGED_CRIT_PERCENTAGE']]), 2),
						'rCritRating'		=> $this->data[$eUnitFields['PLAYER_FIELD_COMBAT_RATING_1']+9],
					
					//Zauber
					'spellBonus'		 	=> $this->data[$eUnitFields['PLAYER_FIELD_MOD_DAMAGE_DONE_POS']],
						'holyBonus'    		=> $this->data[$eUnitFields['PLAYER_FIELD_MOD_DAMAGE_DONE_POS']+1], 
						'fireBonus'    		=> $this->data[$eUnitFields['PLAYER_FIELD_MOD_DAMAGE_DONE_POS']+2], 
						'natureBonus'   	=> $this->data[$eUnitFields['PLAYER_FIELD_MOD_DAMAGE_DONE_POS']+3], 
						'frostBonus'    	=> $this->data[$eUnitFields['PLAYER_FIELD_MOD_DAMAGE_DONE_POS']+4], 
						'shadowBonus'  	 	=> $this->data[$eUnitFields['PLAYER_FIELD_MOD_DAMAGE_DONE_POS']+5], 
						'arcaneBonus'    	=> $this->data[$eUnitFields['PLAYER_FIELD_MOD_DAMAGE_DONE_POS']+6],
					'healBonus'				=> $this->data[$eUnitFields['PLAYER_FIELD_MOD_HEALING_DONE_POS']],
					'spHitRating'		 	=> $this->data[$eUnitFields['PLAYER_FIELD_COMBAT_RATING_1']+7],
						'spHitPercent'		=> 0.00,
						'spExpRating'		=> 0,
							'spExpPercent'	=> 0.00,												
					'spCritRating'			=> $this->data[$eUnitFields['PLAYER_FIELD_COMBAT_RATING_1']+10],
						'spCritPercent'	 	=> round(int2float($this->data[$eUnitFields['PLAYER_SPELL_CRIT_PERCENTAGE1']]), 2),
						'holyCritPercent' 	=> round(int2float($this->data[$eUnitFields['PLAYER_SPELL_CRIT_PERCENTAGE1']+1]), 2),
						'fireCritPercent' 	=> round(int2float($this->data[$eUnitFields['PLAYER_SPELL_CRIT_PERCENTAGE1']+2]), 2),
						'natureCritPercent' => round(int2float($this->data[$eUnitFields['PLAYER_SPELL_CRIT_PERCENTAGE1']+3]), 2),
						'frostCritPercent' 	=> round(int2float($this->data[$eUnitFields['PLAYER_SPELL_CRIT_PERCENTAGE1']+4]), 2),
						'shadowCritPercent' => round(int2float($this->data[$eUnitFields['PLAYER_SPELL_CRIT_PERCENTAGE1']+5]), 2),
						'arcaneCritPercent' => round(int2float($this->data[$eUnitFields['PLAYER_SPELL_CRIT_PERCENTAGE1']+6]), 2),
					'spHasteRating'			=> 0,
						'spHastePercent'	=> round(int2float($this->data[$eUnitFields['UNIT_MOD_CAST_SPEED']]), 2),
					'spRegeneration'		=> 0,
					
					//Verteidigung
					'defense'				=> $this->data[$eUnitFields['PLAYER_FIELD_COMBAT_RATING_1']+1],
					'dodgePercent'			=> round(int2float($this->data[$eUnitFields['PLAYER_DODGE_PERCENTAGE']]), 2),
						'dodgeRating'		=> $this->data[$eUnitFields['PLAYER_FIELD_COMBAT_RATING_1']+2],
					'parryPercent' 			=> round(int2float($this->data[$eUnitFields['PLAYER_PARRY_PERCENTAGE']]), 2),
						'parryRating' 		=> $this->data[$eUnitFields['PLAYER_FIELD_COMBAT_RATING_1']+3],
					'blockPercent'			=> round(int2float($this->data[$eUnitFields['PLAYER_BLOCK_PERCENTAGE']]), 2),
						'blockRating'		=> $this->data[$eUnitFields['PLAYER_FIELD_COMBAT_RATING_1']+4],
					'resilienceRating'		=> 0,
						'resiliencePercent'	=> 0,
					
					//Sonstige
					'health'				=> $this->data[$eUnitFields['UNIT_FIELD_MAXHEALTH']],
					'mana'					=> $this->data[$eUnitFields['UNIT_FIELD_MAXPOWER1']],
					'rage'					=> $this->data[$eUnitFields['UNIT_FIELD_MAXPOWER2']]/10,
					'energy'				=> $this->data[$eUnitFields['UNIT_FIELD_MAXPOWER4']],
					'rune'					=> $this->data[$eUnitFields['UNIT_FIELD_MAXPOWER5']]
					
					);
    } 

private function getRatingFromPercent($which, $percent){
	
	$val = 0;
	$bases = array('dodge'=>12,'parry'=>15,'defense'=>1.5,'block'=>5);
	
	if($which == 'dodge' || $which == 'parry' || $which == 'defense' || $which == 'block'){
		
		if($this->level < 35) $val = ($bases[$which]/2);
		if($this->level >= 35 && $this->level < 61) $val = $bases[$which] * (($this->level - 8) / 52);
		if($this->level >= 61 && $this->level < 71) $val = $bases[$which] * (82/ (282 - 3 * $this->level));
		if($this->level >= 71 && $this->level < 81) $val = $bases[$which] * (82/52) * pow((131/63), (($this->level - 70)/10));
		else $val = 0;
		
		
		
	}
	
	if(!$val) $val = 0;
	$val = $val * $percent;
	return round($val);
	
}
  
public function getResistances() 
    { 
	
	$eUnitFields =& $GLOBALS['eUnitFields'];
	
    $this->resistances = array('holyRes'    =>$this->data[$eUnitFields['UNIT_FIELD_RESISTANCES']+1], 
                                  'fireRes'    =>$this->data[$eUnitFields['UNIT_FIELD_RESISTANCES']+2], 
                                  'natureRes'    =>$this->data[$eUnitFields['UNIT_FIELD_RESISTANCES']+3], 
                                  'frostRes'    =>$this->data[$eUnitFields['UNIT_FIELD_RESISTANCES']+4], 
                                  'shadowRes'    =>$this->data[$eUnitFields['UNIT_FIELD_RESISTANCES']+5], 
                                  'arcaneRes'    =>$this->data[$eUnitFields['UNIT_FIELD_RESISTANCES']+6]); 
  
    return $this->resistances; 
    } 
  
public function getReputation() 
    { 
	
	$handle = fopen(dirname(__FILE__).'/Faction.csv', 'r');
	$factionTemplate = array();
	while(($row = fgetcsv($handle)) != false){
		$factionTemplate[$row[0]] = $row;
	};
	fclose($handle);

	$raceMask = 1 << ($this->getRace()-1);
	$classMask = 1 << ($this->getClass()-1);
	
    $res = @mysql_query("SELECT faction, standing FROM `character_reputation` WHERE `guid`='".$this->charid."' AND (flags & 1 = 1);", $this->pvpdbconn); 
    while($arr = @mysql_fetch_assoc($res)) 
        {
			
			$faction = $arr['faction'];
			$baseRep = 0;
			
			for ($i=0; $i < 4; $i++){
				if (($factionTemplate[$faction][2+$i] & $raceMask  ||
					($factionTemplate[$faction][2+$i] == 0  &&
					 $factionTemplate[$faction][6+$i] != 0)) &&
					($factionTemplate[$faction][6+$i] & $classMask ||
					 $factionTemplate[$faction][6+$i] == 0)){
					 
						$baseRep = $factionTemplate[$faction][10+$i];
						if($baseRep >= 1000000000) $baseRep = -1*(4294967296 - $baseRep);
						break;
					}
			}
			
			$standing = $baseRep + $arr['standing'];
			//capping. else armory displays much too long status bars 
            if($standing > 42999)     $standing = 42999; 
            if($standing < -42000)    $standing = -42000;
			
			$this->reputation[$faction] = $standing;
            
        } 
    return $this->reputation;
    } 
  
  
public function getItems(){ 
     
    $this->items = array(); 
    $res = @mysql_query("SELECT ii.data, ci.slot, ci.item_template FROM `item_instance` ii JOIN `character_inventory` ci ON ci.item = ii.guid WHERE ii.owner_guid=".$this->charid." AND ci.bag = 0 AND ci.slot >= 0 AND ci.slot < 19;", $this->pvpdbconn); 
    while($row = @mysql_fetch_assoc($res)){ 
         
        $idata = explode(' ',$row['data']); 
         
        $this->items[$row['slot']] = array( 'id'=>$row['item_template'], 
                                            'ench'=>$idata[22], 
                                            'socket1'=>$idata[28], 
                                            'socket2'=>$idata[31], 
                                            'socket3'=>$idata[34], 
                                            'dur'=>$idata[61],
											'randomEnchant'=>$idata[59]);
    }//while 
     
    return $this->items; 
}     
     
public function getAchievements() 
    { 
    $i=0; 
    $res = @mysql_query("SELECT achievement, date FROM `character_achievement` WHERE `guid`='".$this->charid."';", $this->pvpdbconn); 
    while($arr = @mysql_fetch_assoc($res)) 
        { 
        $this->achievements[$arr['achievement']]=$arr['date']; 
        $i++; 
        } 
    if($this->achievements) return $this->achievements; 
    else return array(); 
    } 
  
 public function getAchievementCriteria() 
    { 
    $i=0; 
    $res = @mysql_query("SELECT criteria, counter, date FROM `character_achievement_progress` WHERE `guid`='".$this->charid."';", $this->pvpdbconn); 
    while($arr = @mysql_fetch_assoc($res)) 
        { 
        $this->achievementcriteria[$arr['criteria']]=array(0=>$arr['counter'],1=>$arr['date']); 
        $i++; 
        } 
    if($this->achievementcriteria) return $this->achievementcriteria; 
    else return array(); 
    } 
  
public function getTalents() 
    { 
        $res=@mysql_query("SELECT spell, spec FROM `character_talent` WHERE `guid`='".$this->charid."';", $this->pvpdbconn); 
        while($arr = @mysql_fetch_assoc($res)) 
            { 
            $spec = $arr['spec']; 
            if(!$this->talents[$spec]) $this->talents[$spec] = array(); 
            array_push($this->talents[$spec], $arr['spell']); 
            } 
         
    return $this->talents; 
    } 
  
public function getActiveSpec(){ 
    $res=@mysql_query("SELECT activespec FROM `characters` WHERE `guid`='".$this->charid."';", $this->pvpdbconn); 
    $arr = @mysql_fetch_assoc($res); 
    if($arr['activespec']) return $arr['activespec']; 
    else return 0; 
} 
  
public function getGlyphs() 
    { 
     
        $res=@mysql_query("SELECT glyph1, glyph2, glyph3, glyph4, glyph5, glyph6, spec FROM `character_glyphs` WHERE `guid`='".$this->charid."';", $this->pvpdbconn); 
        while($arr = @mysql_fetch_assoc($res)) 
            { 
            $spec = $arr['spec']; 
            if(!$this->glyphe[$spec]) $this->glyphe[$spec] = array(); 
            array_push($this->glyphe[$spec], $arr['glyph1'], $arr['glyph2'], $arr['glyph3'], $arr['glyph4'], $arr['glyph5'], $arr['glyph6']); 
            } 
         
    return $this->glyphe; 
    } 
  
public function getGuildId(){ 
     
    return $this->guildid; 
     
} 
  
public function getGuildName($guild) 
    { 
    if (!ctype_digit($guild)) 
        {                            //$guild is a name 
        $this->guildname=$guild; 
        $this->setGuildId($guild); 
         
        }else 
            {    //$guild is id 
            $this->guildtable=$this->GuildTable($guild); 
            $this->guildid = $guild; 
            $this->guildname=$this->guildtable['name']; 
            } 
    return $this->guildname;     
    } 
  
  
public function getMembercount()     
    { 
    return $this->membercount; 
    } 
  
  
public function getGuildEmblem(){ 
     
    return array( 
         
        'emblemStyle' => $this->guildtable['EmblemStyle'], 
        'emblemColor' => $this->guildtable['EmblemColor'], 
        'borderStyle' => $this->guildtable['BorderStyle'], 
        'borderColor' => $this->guildtable['BorderColor'], 
        'backgroundColor' => $this->guildtable['BackgroundColor'] 
         
    ); 
     
} 
  
public function getMemberstatus()    //Array with PlayerGuid, Membername, Memberrank 
    { 
     
    $this->memberstatus = array(); 
    $res = mysql_query("SELECT c.race,c.class,c.gender,c.level,gm.rank,c.name FROM `characters` c JOIN `guild_member` gm on gm.guid = c.guid WHERE gm.guildid = ".$this->guildid.";", $this->pvpdbconn); 
    while($row = mysql_fetch_assoc($res)){ 
         
        array_push($this->memberstatus, array(  'rank' => $row['rank'], 
                                                'name' => $row['name'], 
                                                'race' => $row['race'], 
                                                'level' => $row['level'], 
                                                'gender' => $row['gender'], 
                                                'class' => $row['class'], 
                                                'achpoints' =>0 
                                        )); 
    }//while 
     
    return $this->memberstatus; 
    } 
  
  
public function getArenaTeamsIdByPlayer() 
    { 
    $res = mysql_query("SELECT a.name,a.type,a.arenateamid FROM `arena_team` a JOIN `arena_team_member` atm on a.arenateamid = atm.arenateamid WHERE atm.guid='".$this->charid."';", $this->pvpdbconn); 
    while($arr = @mysql_fetch_assoc($res)) 
        { 
        if ($arr['type']==2) {$this->arenateamsidbyplayer[2]['id']=$arr['arenateamid']; $this->arenateamsidbyplayer[2]['name']=$arr['name'];} 
            else { 
                 if ($arr['type']==3) 
                    {$this->arenateamsidbyplayer[3]['id']=$arr['arenateamid']; $this->arenateamsidbyplayer[3]['name']=$arr['name'];} 
                 
					else 
					{ 
					if ($arr['type']==5) 
					 {$this->arenateamsidbyplayer[5]['id']=$arr['arenateamid']; $this->arenateamsidbyplayer[5]['name']=$arr['name'];} 
					} 
                 } 
        } 
        if($this->arenateamsidbyplayer) return $this->arenateamsidbyplayer; 
    else return array(); 
    } 
  
   
public function getArenaName() 
    { 
    return $this->teamname; 
    } 
  

public function getArenaTeamproperties()
	{
	$this->ArenaTeamproperties = array(); 
 	$arr=$this->ArenaTable($this->teamid);
 	$this->ArenaTeamproperties['teamId'] = $this->teamid;
	$this->ArenaTeamproperties['gamesPlayed']=$arr['games'];
	$this->ArenaTeamproperties['gamesWon']=$arr['wins'];
	$this->ArenaTeamproperties['name']=$arr['name'];
	$this->ArenaTeamproperties['ranking']=$arr['rank'];
	$this->ArenaTeamproperties['rating']=$arr['rating'];
	$this->ArenaTeamproperties['seasonGamesPlayed']=$arr['played'];
	$this->ArenaTeamproperties['seasonGamesWon']=$arr['wins2'];
	$this->ArenaTeamproperties['teamSize']=$arr['type'];
	$this->ArenaTeamproperties['background']= str_pad(base_convert($arr['BackgroundColor'], 10, 16), 8, 0, STR_PAD_LEFT);
		
	$this->ArenaTeamproperties['borderColor']= str_pad(base_convert($arr['BorderColor'], 10, 16), 8, 0, STR_PAD_LEFT);
	$this->ArenaTeamproperties['borderStyle']=$arr['BorderStyle'];
	$this->ArenaTeamproperties['iconColor']=str_pad(base_convert($arr['EmblemColor'], 10, 16), 8, 0, STR_PAD_LEFT);
	$this->ArenaTeamproperties['iconStyle']=$arr['EmblemStyle'];
	$this->ArenaTeamproperties['leaderRace']=$arr['race'];
		
	return $this->ArenaTeamproperties;
	}

public function getArenaTeammember()
	{
	$this->ArenaTeammember = array();
	
	$res = mysql_query("
SELECT atm.guid, atm.played_week, atm.wons_week, atm.played_season, atm.wons_season, atm.personal_rating, c.name, c.race, c.gender, c.class, g.name as gname FROM `arena_team_member` atm JOIN `characters` c on atm.guid=c.guid LEFT JOIN `guild_member` gm on gm.guid = c.guid LEFT JOIN `guild` g on g.guildid=gm.guildid WHERE atm.arenateamid='".$this->teamid."';", $this->pvpdbconn);
	while($arr = @mysql_fetch_assoc($res))
		{
		array_push($this->ArenaTeammember, array('guid' => $arr['guid'], //Player Guid  
                                                 'name' => $arr['name'], //Player Name
                                                 'guild' => $arr['gname'], //Player Guild
                                                 'played_week' => $arr['played_week'], 
                                                 'class' => $arr['class'], 
                                                 'gender' => $arr['gender'], 
                                                 'race' => $arr['race'], 
                                                 'won_week' => $arr['wons_week'], 
                                                 'played_season' => $arr['played_season'], 
                                                 'won_season' => $arr['wons_season'], 
                                                 'personal_rating' =>$arr['personal_rating'] 
                                        )); 
		}
	
	return $this->ArenaTeammember;
	}

	
	public function getArenaladder($realmName)
	{
	$x=0;
	$this->arenaLadder = array(); 
    $res = mysql_query("(SELECT a.name,a.type,a.BackgroundColor,a.EmblemStyle,a.EmblemColor,a.BorderStyle,a.BorderColor, ats.rating, ats.rank, ats.games, ats.wins, ats.played, ats.wins2, c.race FROM `arena_team` a JOIN `arena_team_stats` ats on a.arenateamid = ats.arenateamid JOIN characters c ON c.guid = a.captainguid WHERE a.type = 3 ORDER BY ats.rating DESC LIMIT 20)
						UNION 
						(SELECT a.name,a.type,a.BackgroundColor,a.EmblemStyle,a.EmblemColor,a.BorderStyle,a.BorderColor, ats.rating, ats.rank, ats.games, ats.wins, ats.played, ats.wins2, c.race FROM `arena_team` a JOIN `arena_team_stats` ats on a.arenateamid = ats.arenateamid JOIN characters c ON c.guid = a.captainguid WHERE a.type = 2 ORDER BY ats.rating DESC LIMIT 20)
						UNION 
						(SELECT a.name,a.type,a.BackgroundColor,a.EmblemStyle,a.EmblemColor,a.BorderStyle,a.BorderColor, ats.rating, ats.rank, ats.games, ats.wins, ats.played, ats.wins2, c.race FROM `arena_team` a JOIN `arena_team_stats` ats on a.arenateamid = ats.arenateamid JOIN characters c ON c.guid = a.captainguid WHERE a.type = 5 ORDER BY ats.rating DESC LIMIT 20);", 
						$this->pvpdbconn); 
    
    while($arr = mysql_fetch_assoc($res))
		{
		$this->arenaLadder[$x]['realmName']=$realmName;
		$this->arenaLadder[$x]['gamesPlayed']=$arr['games'];
		$this->arenaLadder[$x]['gamesWon']=$arr['wins'];
		$this->arenaLadder[$x]['name']=$arr['name'];
		$this->arenaLadder[$x]['ranking']=$arr['rank'];
		$this->arenaLadder[$x]['rating']=$arr['rating'];
		$this->arenaLadder[$x]['seasonGamesPlayed']=$arr['played'];
		$this->arenaLadder[$x]['seasonGamesWon']=$arr['wins2'];
		$this->arenaLadder[$x]['teamSize']=$arr['type'];
		$this->arenaLadder[$x]['background']= str_pad(base_convert($arr['BackgroundColor'], 10, 16), 8, 0, STR_PAD_LEFT);
		$this->arenaLadder[$x]['borderColor']= str_pad(base_convert($arr['BorderColor'], 10, 16), 8, 0, STR_PAD_LEFT);
		$this->arenaLadder[$x]['borderStyle']=$arr['BorderStyle'];
		$this->arenaLadder[$x]['iconColor']=str_pad(base_convert($arr['EmblemColor'], 10, 16), 8, 0, STR_PAD_LEFT);
		$this->arenaLadder[$x]['iconStyle']=$arr['EmblemStyle'];
		$this->arenaLadder[$x]['leaderRace']=$arr['race'];
		$x++;
		}
	return $this->arenaLadder;
	}
     
} 
  
  
?>