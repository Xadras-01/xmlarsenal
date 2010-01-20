<?php

/**
 * login-status.php
 * 
 * The script handles caching and output of the login-status.xml with respective values.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/11/27
 * @package XMLArsenal
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

require_once './includes/autoload.inc.php';
require_once './includes/config.inc.php';
require_once './includes/db.inc.php';
require_once './includes/data.inc.php';
require_once './includes/functions.inc.php';

if($_SESSION['loggedIn'] == true) $username = $_SESSION['userName'];
else $username = '';

echo fgettemplate('./xml-templates/login-status.xml', array('LANGUAGE'=>$language, 'USERNAME'=>$username));

?>