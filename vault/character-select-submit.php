<?php

/**
 * character-select-submit.php
 * 
 * Just a redirect to not mess up the internal structure of XMLArsenal.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/12/10
 * @package XMLArsenal
 * @subpackage vault
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

$parameters = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
header("Location: /character-select-submit.xml?$parameters");

?>