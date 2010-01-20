<?php

/**
 * search.php
 * 
 * The script handles caching and output of the search.xml with respective values.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/10/15
 * @package XMLArsenal
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

require_once './includes/autoload.inc.php';
require_once './includes/config.inc.php';
require_once './includes/db.inc.php';
require_once './includes/data.inc.php';
require_once './includes/functions.inc.php';

//try using cache
$cachefile = FILECACHEFOLDER. md5("search.xml_".$queryString."_selectedTab_".$_GET['selectedTab']."_lang_".$language) .".cache";
if(USEFILECACHE && file_exists($cachefile) && (filemtime($cachefile) + 60*60*UPDATEINTERVAL > time())){
	echo file_get_contents($cachefile)."<!-- cached file. cache valid until ".date('d.m.Y H:i:s',filemtime($cachefile) + 60*60*UPDATEINTERVAL).". -->";
	die();
}

$search = new Search($queryString);

if($search->getId() && strlen($queryString) >= 2){
	
	//only display appropriate tabs
	$count = $search->getResultCount();
	if(!$_GET['selectedTab']){
		
		if($count['characters'] > 0) $_GET['selectedTab'] = 'characters';
		else if($count['guilds'] > 0) $_GET['selectedTab'] = 'guilds';
		else if($count['arenateams'] > 0) $_GET['selectedTab'] = 'arenateams';
		
	}//if
	
	if($_GET['selectedTab'] == 'arenateams')	$xml = fgettemplate('./xml-templates/search-arenateamTab.xml', $search->prepareArenateamTab());
	else if($_GET['selectedTab'] == 'guilds')	$xml = fgettemplate('./xml-templates/search-guildTab.xml', $search->prepareGuildTab());
	else										$xml = fgettemplate('./xml-templates/search-characterTab.xml', $search->prepareCharacterTab());
	
	$xml = str_replace(array("\n", "\r", "\t", "\o", "\xOB", "      "), '', $xml);
	$doc = new DOMDocument('1.0');
	$doc->preserveWhiteSpace = false;
	$doc->loadXML($xml);
	$doc->formatOutput = true;
	$xml = $doc->saveXML();
	
	if(USEFILECACHE) file_put_contents($cachefile, $xml);
	echo $xml;
	
}else{
	echo fgettemplate('./xml-templates/error404.xml', array('LANGUAGE'=>$language));
}


?>