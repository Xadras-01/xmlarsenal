<?php

/**
 * talent-calc.php
 * 
 * outputs the talent calculator
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/09/19
 * @package XMLArsenal
 * @subpackage includes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

require_once './includes/autoload.inc.php';
require_once './includes/config.inc.php';
require_once './includes/db.inc.php';
require_once './includes/data.inc.php';
require_once './includes/functions.inc.php';

$varname = "classes_$language";
$classid = @array_search($_GET['c'], $DATA['classes_en_gb']);
if(!$classid) $classid = $_GET['cid'];

if($_GET['tal']) $tal = $_GET['tal'];
else $tal = '00000000000000000000000000000000000000000000000000000000000000000000000000000000';

echo fgettemplate('./xml-templates/talent-calc_'.$language.'.xml', array('CLASSID'=>$classid, 
																			'CLASS'=>$DATA["classes_$language"][$classid] , 
																			'TAL'=>$tal));

?>