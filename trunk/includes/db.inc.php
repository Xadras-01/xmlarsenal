<?php

/**
 * db.inc.php
 * 
 * provides an extra layer for the database persistance via PEAR and the arsenaldata.sqlite3
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/11/18
 * @package XMLArsenal
 * @subpackage includes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/


require_once './includes/pear/MDB2.php';



//internal database (items, enchants...)
if($data_db_type == 'pdoSqlite' || $data_db_type == 'sqlite') $dsn = "$data_db_type://$data_db_base";
else $dsn = "$data_db_type://$data_db_user:$data_db_pass@$data_db_host/$data_db_base";

$db_options = array(
    'debug'       => 2,
    'portability' => MDB2_PORTABILITY_NUMROWS,
	'result_buffering' => true,
	'persistent' => true,
	'result_buffering' => true
);

$arsenaldata =& MDB2::factory($dsn, $db_options);
if (PEAR::isError($arsenaldata)) {
    die($arsenaldata->getMessage());
}



//arsenal cache database (players, guilds etc.)
if($cache_db_type == 'pdoSqlite' || $cache_db_type == 'sqlite') $dsn = "$cache_db_type://$cache_db_base";
else $dsn = "$cache_db_type://$cache_db_user:$cache_db_pass@$cache_db_host/$cache_db_base";

$db_options = array(
    'debug'       => 2,
    'portability' => MDB2_PORTABILITY_NUMROWS,
	'result_buffering' => true,
	'persistent' => true,
	'result_buffering' => true
);

$arsenaldb =& MDB2::factory($dsn, $db_options);
if (PEAR::isError($arsenaldb)) {
    die($arsenaldb->getMessage());
}



//settings to utf8 for dbms that understand it
if($cache_db_type != 'pdoSqlite' || $cache_db_type != 'sqlite'){
	$arsenaldb->query('SET NAMES utf8');
	$arsenaldata->query('SET NAMES utf8');
}


?>