<?php

/**
 * battlegroups.php
 * 
 * The script handles caching and output of the battlegroups.xml with the respective values.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/09/17
 * @package XMLArsenal
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

require_once './includes/autoload.inc.php';
require_once './includes/config.inc.php';
require_once './includes/db.inc.php';
require_once './includes/data.inc.php';
require_once './includes/functions.inc.php';

$vars = array();
$vars['LANGUAGE'] = $language;
$vars['REALMS'] = '';
$vars['REALMPOOL'] = $realmpool;
$vars['REALMPOOLURL'] = urlencode($realmpool);

foreach($realms as $realm){
	
	$realmvars = array();
	$realmvars['NAME'] = $realm->realmName;
	$realmvars['NAMEURL'] = urlencode($realm->realmName);
	
	$vars['REALMS'] .= fgettemplate('./xml-templates/battlegroups-realm.xml', $realmvars);
	
}//foreach

echo fgettemplate('./xml-templates/battlegroups.xml', $vars);

?>