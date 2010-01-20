<?php

/**
 * team-info.php
 * 
 * The script handles caching and output of the team-info.xml with respective values.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/10/10
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
$cachefile = FILECACHEFOLDER. md5("team-info.xml_".$teamName."_on_".$realmName."_lang_".$language) .".cache";
if(USEFILECACHE && file_exists($cachefile) && (filemtime($cachefile) + 60*60*UPDATEINTERVAL > time())){
	echo file_get_contents($cachefile)."<!-- cached file. cache valid until ".date('d.m.Y H:i:s',filemtime($cachefile) + 60*60*UPDATEINTERVAL).". -->";
	die();
}

$team = new Arenateam($teamName, $usedRealm);

if($team->getId()){
	
	$xml = fgettemplate('./xml-templates/team-info-basic.xml', $team->prepareArenateamSheet());
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