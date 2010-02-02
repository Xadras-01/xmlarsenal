<?php

/** 
* Trinity_PVEDataGrabber.class.php 
* 
* This is the example implementation of a DataGrabber for XMLArsenal. 
* If you like to use your own DataGrabber-Class just implement the functions in such a way that they use 
* the same function-names and produce the same output as the functions shown in this file. 
* 
* @author Ytrosh 
* @author Amras Taralom <amras-taralom@streber24.de> 
* @version 1.0, last modified 2010/01/31
* @package XMLArsenal 
* @subpackage classes 
* @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3) 
* 
*/ 
  
  
class Trinity_PVEDataGrabber{ 
  
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
     private $pvedbconn; 
  
  
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
     
    //$this->pvedbconn = mysql_connect("localhost", "localuser", "userpassword", true) or die(get_class($this).": no connection to database."); 
    //@mysql_select_db("pve_char", $this->pvedbconn) or die (get_class($this).": not able to select specified database.");
	
	//extra passwords
	if(file_exists('./classes/dataGrabbers/GrabberConfig.php')) include './classes/dataGrabbers/GrabberConfig.php';
	
    @mysql_query("SET NAMES 'utf8'", $this->pvedbconn);
    @mysql_query("SET CHARACTER SET 'utf8'", $this->pvedbconn);
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
	$res = @mysql_query("SELECT c.race,c.class,c.gender,c.level,c.name, g.name as gname FROM `characters` c LEFT JOIN `guild_member` gm on gm.guid = c.guid LEFT JOIN `guild` g on g.guildid=gm.guildid WHERE account = ".$id.";", $this->pvedbconn); 
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
    $res = @mysql_query("SELECT guid, name, data, race, class FROM `characters` WHERE guid='".mysql_real_escape_string($player)."' LIMIT 1;", $this->pvedbconn); 
    $chartable2 = @mysql_fetch_assoc($res); 
    return $chartable2; 
    } 
  
  
private function CharTable2($player)    //chartable with name 
    { 
    $res = @mysql_query("SELECT guid, name, data, race, class FROM `characters` WHERE convert(name using utf8) COLLATE utf8_bin  = '".mysql_real_escape_string($player)."' LIMIT 1;", $this->pvedbconn); 
    $chartable2 = @mysql_fetch_assoc($res); 
    return $chartable2; 
    } 
  
  
private function GuildTable($guild) //guildtable with id 
    { 
    $res = @mysql_query("SELECT * FROM `guild` WHERE guildid='".mysql_real_escape_string($guild)."' LIMIT 1;", $this->pvedbconn); 
    $guildtable2 = @mysql_fetch_assoc($res); 
    return $guildtable2; 
    } 
  
private function GuildTable2($guild) //guildtable with name 
    { 
    $res = @mysql_query("SELECT * FROM `guild` WHERE convert( name USING utf8 ) COLLATE utf8_bin = '".mysql_real_escape_string($guild)."' LIMIT 1;", $this->pvedbconn); 
    $guildtable2 = @mysql_fetch_assoc($res); 
    return $guildtable2; 
    } 
  
  
private function ArenaTable2($team) //arenatable with name 
    { 
    $res = mysql_query("SELECT a.arenateamid, a.name,a.type,a.BackgroundColor,a.EmblemStyle,a.EmblemColor,a.BorderStyle,a.BorderColor, ats.rating, ats.rank, ats.games, ats.wins, ats.played, ats.wins2, c.race FROM `arena_team` a JOIN `arena_team_stats` ats on a.arenateamid = ats.arenateamid JOIN characters c ON c.guid = a.captainguid WHERE convert( a.name USING utf8 ) COLLATE utf8_bin = '".mysql_real_escape_string($team)."' LIMIT 1;", $this->pvedbconn); 
    $arenaTable2 = @mysql_fetch_assoc($res);
    return $arenaTable2; 
    } 
  
private function ArenaTable($team) //arenatable with id 
    { 
	$res = mysql_query("SELECT a.arenateamid, a.name,a.type,a.BackgroundColor,a.EmblemStyle,a.EmblemColor,a.BorderStyle,a.BorderColor, ats.rating, ats.rank, ats.games, ats.wins, ats.played, ats.wins2, c.race FROM `arena_team` a JOIN `arena_team_stats` ats on a.arenateamid = ats.arenateamid JOIN characters c ON c.guid = a.captainguid WHERE a.arenateamid='".mysql_real_escape_string($team)."' LIMIT 1;", $this->pvedbconn); 
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
    $res = mysql_query("SELECT guid, rank FROM `guild_member` WHERE guildid ='".$this->guildid."';", $this->pvedbconn); 
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

	$professions = array(171, 186, 202, 773, 755, 182, 393, 165, 164, 197, 333);
	$data = $this->data;
	$resultset = array();
	
	for($i = 610; $i < 694; $i+=3){
		
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
	
	//this is from CharTitles.dbc column 37: titleMaskID (Integer, used ingame in the drop down menu)
	return $this->data[296];
	
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
		
		$res = mysql_query("SELECT c.race,c.class,c.gender,c.level, c.name, g.name as gname, ca.achievement, ca.date FROM `characters` c LEFT JOIN character_achievement ca ON ca.guid = c.guid LEFT JOIN `guild_member` gm on gm.guid = c.guid LEFT JOIN `guild` g on g.guildid=gm.guildid WHERE ca.achievement IN (".$incond.");", $this->pvedbconn); 
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
    $res = mysql_query("SELECT c.race,c.class,c.gender,c.level,c.name, g.name as gname FROM `characters` c LEFT JOIN `guild_member` gm on gm.guid = c.guid LEFT JOIN `guild` g on g.guildid=gm.guildid WHERE c.name LIKE '%".$this->searchstring."%';", $this->pvedbconn); 
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
    $res = mysql_query("SELECT a.name,a.type,a.BackgroundColor,a.EmblemStyle,a.EmblemColor,a.BorderStyle,a.BorderColor, ats.rating, ats.rank, ats.games, ats.wins, ats.played, ats.wins2, c.race FROM `arena_team` a JOIN `arena_team_stats` ats on a.arenateamid = ats.arenateamid JOIN characters c ON c.guid = a.captainguid WHERE a.name LIKE '%".$this->searchstring."%';", $this->pvedbconn); 
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
    $res = mysql_query("SELECT g.BackgroundColor,g.BorderStyle, g.EmblemColor, g.EmblemStyle, g.BorderColor, g.name, c.race FROM `guild` g JOIN characters c on g.leaderguid = c.guid WHERE g.name LIKE '%".$this->searchstring."%';", $this->pvedbconn); 
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
    
		return array( 
					//Grundwerte
					'strength'      		=> $this->data[84], 
						'strengthPos'		=> round(int2float($this->data[89])), 
						'strengthNeg'		=> round(int2float($this->data[94])),
					'agility'      	 		=> $this->data[85], 
						'agilityPos'		=> round(int2float($this->data[90])),  
						'agilityNeg'		=> round(int2float($this->data[95])),
					'stamina'       		=> $this->data[86], 
						'staminaPos'		=> round(int2float($this->data[91])),  
						'staminaNeg'		=> round(int2float($this->data[96])),
					'int'           		=> $this->data[87], 
						'intPos'			=> round(int2float($this->data[92])),
						'intNeg'			=> round(int2float($this->data[97])), 
					'spirit'       			=> $this->data[88], 
						'spiritPos'			=> round(int2float($this->data[93])), 
						'spiritNeg'			=> round(int2float($this->data[98])),
					'armor'					=> $this->data[99],
					  
					//Nahkampf
					'mhMinDmg'     		 	=> round(int2float($this->data[69])),
					'mhMaxDmg'        	 	=> round(int2float($this->data[70])),
					'ohMinDmg'        	 	=> round(int2float($this->data[71])),
					'ohMaxDmg'        	 	=> round(int2float($this->data[72])),
					'mhTempo'				=> round((int2float($this->data[61]) / 1000), 2),					
					'ohTempo'				=> round((int2float($this->data[62]) / 1000), 2),
					'mAtp'				 	=> $this->data[123],
						'mAtpMod'			=> $this->data[124],
					'mHitRating'		 	=> $this->data[1208],
						'mHitPercent'		=> 0.00,
						'mExpRating'		=> $this->data[1227],
							'mExpPercent'	=> 0.00,
					'mCritPercent'		 	=> round(int2float($this->data[1003]), 2),
						'mCritRating'		=> $this->data[1211],					
					'mExpertise'			=> $this->data[1001],
					
					//Distanz
					'rMinDmg'   		 	=> round(int2float($this->data[129])),
					'rMaxDmg'  		 	 	=> round(int2float($this->data[130])),
					'rTempo'				=> round((int2float($this->data[63]) / 1000), 2),
					'rAtp'		 			=> $this->data[126],
						'rAtpMod'	 		=> $this->data[127],		
					'rHitRating'		 	=> $this->data[1209],
						'rHitPercent'		=> 0.00,
						'rExpRating'		=> $this->data[1227],
							'rExpPercent'	=> 0.00,	
					'rCritPercent'			=> round(int2float($this->data[1002]), 2),
						'rCritRating'		=> $this->data[1212],
					
					//Zauber
					'spellBonus'		 	=> $this->data[1145],
						'holyBonus'    		=> $this->data[1146], 
						'fireBonus'    		=> $this->data[1147], 
						'natureBonus'   	=> $this->data[1148], 
						'frostBonus'    	=> $this->data[1149], 
						'shadowBonus'  	 	=> $this->data[1150], 
						'arcaneBonus'    	=> $this->data[1151],
					'healBonus'				=> $this->data[1166],														
					'spHitRating'		 	=> $this->data[1210],
						'spHitPercent'		=> 0.00,
						'spExpRating'		=> 0,
							'spExpPercent'	=> 0.00,												
					'spCritRating'			=> 0,												
						'spCritPercent'	 	=> round(int2float($this->data[1004]), 2),
						'holyCritPercent' 	=> round(int2float($this->data[1007]), 2),
						'fireCritPercent' 	=> round(int2float($this->data[1008]), 2),
						'natureCritPercent' => round(int2float($this->data[1009]), 2),
						'frostCritPercent' 	=> round(int2float($this->data[1010]), 2),
						'shadowCritPercent' => round(int2float($this->data[1011]), 2),
						'arcaneCritPercent' => round(int2float($this->data[1012]), 2),
					'spHasteRating'			=> 0,
						'spHastePercent'	=> round(int2float($this->data[80]), 2),
					'spRegeneration'		=> 0,
					
					//Verteidigung
					'defense'				=> 0,					
					'dodgePercent'			=> round(int2float($this->data[999]), 2),
						'dodgeRating'		=> $this->getRatingFromPercent('dodge', round(int2float($this->data[999]), 2)),
					'parryPercent' 			=> round(int2float($this->data[1000]), 2),	
						'parryRating' 		=> $this->getRatingFromPercent('parry', round(int2float($this->data[1000]), 2)),
					'blockPercent'			=> round(int2float($this->data[998]), 2),
						'blockRating'		=> $this->getRatingFromPercent('block', round(int2float($this->data[998]), 2)),
					'resilienceRating'		=> 0,
						'resiliencePercent'	=> 0,
					
					//Sonstige
					'health'				=> $this->data[31],
					'mana'					=> $this->data[32],
					'rage'					=> $this->data[33],
					'energy'				=> $this->data[35],
					'rune'					=> $this->data[36]
					
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
    $this->resistances = array('holyRes'    =>$this->data[100], 
                                  'fireRes'    =>$this->data[101], 
                                  'natureRes'    =>$this->data[102], 
                                  'frostRes'    =>$this->data[103], 
                                  'shadowRes'    =>$this->data[104], 
                                  'arcaneRes'    =>$this->data[105]); 
  
    return $this->resistances; 
    } 
  
  
public function getReputation() 
    { 
    $res = @mysql_query("SELECT faction, standing FROM `character_reputation` WHERE `guid`='".$this->charid."' AND (flags & 1 = 1);", $this->pvedbconn); 
    while($arr = @mysql_fetch_assoc($res)) 
        {   
			if ($this->reputation[$arr['faction']]=='529')	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 200;//Argentumdämmerung
			if ($this->reputation[$arr['faction']]=='21')	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 500;//Beutebucht
			if ($this->reputation[$arr['faction']]=='87')	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] - 41500;//Blutsegelbukaniere
			if ($this->reputation[$arr['faction']]=='169')	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 500;//Dampfdruckkartell
			
			if (($this->chartable['race']==4)  && ($this->reputation[$arr['faction']]==69))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 4000; 
				else
				if (($this->reputation[$arr['faction']]==69) && (($this->chartable['race']==1) || ($this->chartable['race']==3) || ($this->chartable['race']==7) || ($this->chartable['race']==11)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 3100;//Darnassus
			
			if (($this->reputation[$arr['faction']]==932) && ($this->chartable['race']==10))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] - 5500;//Aldor Blutelf
			if (($this->reputation[$arr['faction']]==932) && ($this->chartable['race']==11))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 3500;//Aldor Draenei
			if (($this->reputation[$arr['faction']]==934) && ($this->chartable['race']==10))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 3500;//Seher Blutelf
			if (($this->reputation[$arr['faction']]==934) && ($this->chartable['race']==11))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] - 5500;//Seher Draenei
			if (($this->reputation[$arr['faction']]==930) && ($this->chartable['race']==11))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 4000;//Exodar Draenei
			if ($this->reputation[$arr['faction']]==941)	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] - 2500;//Mag'har 
			
			if (($this->chartable['race']==6) &&($this->reputation[$arr['faction']]==81))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 4000; 
				else
				if (($this->reputation[$arr['faction']]==81) &&(($this->chartable['race']==2) || ($this->chartable['race']==8)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 3100;//Donnerfels
					else if (($this->reputation[$arr['faction']]==81) &&(($this->chartable['race']==5) || ($this->chartable['race']==10)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 500;//Donnerfels
			
			if (($this->chartable['race']==8) &&($this->reputation[$arr['faction']]==530))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 4000; 
				else
				if (($this->reputation[$arr['faction']]==530) &&(($this->chartable['race']==2) || ($this->chartable['race']==6)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 3100;//Dunkelspeertrolle
					else if(($this->reputation[$arr['faction']]==530) &&(($this->chartable['race']==5) || ($this->chartable['race']==10)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 500;//Dunkelspeertrolle

			if (($this->chartable['race']==3)  &&($this->reputation[$arr['faction']]==47))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 4000; //Eisenschmiede
				else
				if (($this->reputation[$arr['faction']]==47) &&(($this->chartable['race']==1) || ($this->chartable['race']==4) || ($this->chartable['race']==7) || ($this->chartable['race']==11)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 3100;//Eisenschmiede

			if ($this->reputation[$arr['faction']]==577)	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 500;//Argentumdämmerung
			if ($this->reputation[$arr['faction']]==369)	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 500;//Gadgetzan
			if ($this->reputation[$arr['faction']]==92)	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 2000;//Gelkisklan
			
			if (($this->chartable['race']==7)  &&($this->reputation[$arr['faction']]==54))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 4000; //Gnomeregangnome
				else
				if (($this->reputation[$arr['faction']]=='47') &&(($this->chartable['race']==1) || ($this->chartable['race']==3) || ($this->chartable['race']==4) || ($this->chartable['race']==11)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 3100;//Gnomeregangnome
			
			if ($this->reputation[$arr['faction']]==576)	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] - 5500;//Holzschlundfeste
			if ($this->reputation[$arr['faction']]==978)	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] - 1800;//Holzschlundfeste
			if ($this->reputation[$arr['faction']]==93)	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 2000;//Magramklan
			
			if (($this->chartable['race']==2) &&($this->reputation[$arr['faction']]==76))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 4000; 
				else
				if (($this->reputation[$arr['faction']]==76) &&(($this->chartable['race']==6) || ($this->chartable['race']==8)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 3100;//||grimmar
					else if(($this->reputation[$arr['faction']]==76) &&(($this->chartable['race']==5) || ($this->chartable['race']==10)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 500;//Orgrimmar
			
			if ($this->reputation[$arr['faction']]==470)	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 500;//Ratschet
			if (($this->reputation[$arr['faction']]==1098) &&($this->chartable['class']==6))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 200;//Ritter der Schwarzen

			if (($this->chartable['race']==10) &&($this->reputation[$arr['faction']]==911))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 4000; 
				else
				if (($this->reputation[$arr['faction']]==911) &&($this->chartable['race']==5))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 3100;//Silbermond
					else if(($this->reputation[$arr['faction']]==911) &&(($this->chartable['race']==2) || ($this->chartable['race']==6) || ($this->chartable['race']==8)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 400;//Silbermond
			
			if ($this->reputation[$arr['faction']]==970)	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] - 500;//sporeggar

			if (($this->chartable['race']==1)  &&($this->reputation[$arr['faction']]==72))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 4000; //Sturmwind
				else
				if (($this->reputation[$arr['faction']]==72) &&(($this->chartable['race']==3) || ($this->chartable['race']==4) || ($this->chartable['race']==7) || ($this->chartable['race']==11)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 3100;//Sturmwind
			
			if (($this->chartable['race']==5) &&($this->reputation[$arr['faction']]==68))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 4000; 
				else
				if (($this->reputation[$arr['faction']]==68) &&($this->chartable['race']==5))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 3100;//Unterstadt
					else if(($this->reputation[$arr['faction']]==68) &&(($this->chartable['race']==2) || ($this->chartable['race']==6) || ($this->chartable['race']==8)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 500;//Unterstadt

			if (($this->chartable['race']==3)  &&($this->reputation[$arr['faction']]==471))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 500; //Wildhammerklan
				else
				if (($this->reputation[$arr['faction']]==471) &&(($this->chartable['race']==1)) || ($this->chartable['race']==4) || ($this->chartable['race']==7) || ($this->chartable['race']==11))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 150;//Wildhammerklan

			if (($this->reputation[$arr['faction']]==609) &&(($this->chartable['race']==4) || ($this->chartable['race']==6)))	$this->reputation[$arr['standing']]=$this->reputation[$arr['standing']] + 2000;//Zirkel des Cenarius

			
			//capping. else armory displays much too long status bars 
            if($arr['standing'] > 42999)     $arr['standing'] = 42999; 
            if($arr['standing'] < -42000)     $arr['standing'] = -42000; 
			
			
			$this->reputation[$arr['faction']] = $arr['standing']; 
             
        
        } 
    return $this->reputation; 
    } 
  
  
public function getItems(){ 
     
    $this->items = array(); 
    $res = @mysql_query("SELECT ii.data, ci.slot, ci.item_template FROM `item_instance` ii JOIN `character_inventory` ci ON ci.item = ii.guid WHERE ii.owner_guid=".$this->charid." AND ci.bag = 0 AND ci.slot >= 0 AND ci.slot < 19;", $this->pvedbconn); 
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
    $res = @mysql_query("SELECT achievement, date FROM `character_achievement` WHERE `guid`='".$this->charid."';", $this->pvedbconn); 
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
    $res = @mysql_query("SELECT criteria, counter, date FROM `character_achievement_progress` WHERE `guid`='".$this->charid."';", $this->pvedbconn); 
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
        $res=@mysql_query("SELECT spell, spec FROM `character_talent` WHERE `guid`='".$this->charid."';", $this->pvedbconn); 
        while($arr = @mysql_fetch_assoc($res)) 
            { 
            $spec = $arr['spec']; 
            if(!$this->talents[$spec]) $this->talents[$spec] = array(); 
            array_push($this->talents[$spec], $arr['spell']); 
            } 
         
    return $this->talents; 
    } 
  
public function getActiveSpec(){ 
    $res=@mysql_query("SELECT activespec FROM `characters` WHERE `guid`='".$this->charid."';", $this->pvedbconn); 
    $arr = @mysql_fetch_assoc($res); 
    if($arr['activespec']) return $arr['activespec']; 
    else return 0; 
} 
  
public function getGlyphs() 
    { 
     
        $res=@mysql_query("SELECT glyph1, glyph2, glyph3, glyph4, glyph5, glyph6, spec FROM `character_glyphs` WHERE `guid`='".$this->charid."';", $this->pvedbconn); 
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
    $res = mysql_query("SELECT c.race,c.class,c.gender,c.level,gm.rank,c.name FROM `characters` c JOIN `guild_member` gm on gm.guid = c.guid WHERE gm.guildid = ".$this->guildid.";", $this->pvedbconn); 
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
    $res = mysql_query("SELECT a.name,a.type,a.arenateamid FROM `arena_team` a JOIN `arena_team_member` atm on a.arenateamid = atm.arenateamid WHERE atm.guid='".$this->charid."';", $this->pvedbconn); 
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
SELECT atm.guid, atm.played_week, atm.wons_week, atm.played_season, atm.wons_season, atm.personal_rating, c.name, c.race, c.gender, c.class, g.name as gname FROM `arena_team_member` atm JOIN `characters` c on atm.guid=c.guid LEFT JOIN `guild_member` gm on gm.guid = c.guid LEFT JOIN `guild` g on g.guildid=gm.guildid WHERE atm.arenateamid='".$this->teamid."';", $this->pvedbconn);
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
						$this->pvedbconn); 
    
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