<?php

/**
 * character-select-submit.php
 * 
 * The script handles char selection via /vault/character-select-submit.xml in XMLArsenal.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/12/10
 * @package XMLArsenal
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

require_once './includes/autoload.inc.php';
require_once './includes/config.inc.php';
require_once './includes/db.inc.php';
require_once './includes/data.inc.php';
require_once './includes/functions.inc.php';


//check if logged in
if($_SESSION['loggedIn'] == false){
	
	header("Location: /login.xml");
	
}//if

$acc = unserialize($_SESSION['account']);
$acc->resetSelectedCharacters();

if($_GET['r1'] && $_GET['n1'])	$acc->selectCharacter($_GET['r1'], $_GET['n1'], 1);
if($_GET['r2'] && $_GET['n2'])	$acc->selectCharacter($_GET['r2'], $_GET['n2'], 2);
if($_GET['r3'] && $_GET['n3'])	$acc->selectCharacter($_GET['r3'], $_GET['n3'], 3);

$_SESSION['account'] = serialize($acc);

echo '<page globalSearch="1" lang="'.$language.'"></page>';

?>