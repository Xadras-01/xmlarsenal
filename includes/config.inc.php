<?php

/**
 * config.inc.php
 * 
 * This file holds the configuration parameters of XMLArsenal
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2010/06/06
 * @package XMLArsenal
 * @subpackage includes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/


//how many errors-messages do you need?
//values range from 0 (don't report errors at all) to 4 (report all errors, notices, warnings including E_STRICT)
define('ERROR_REPORTING_LEVEL', 2);

//debug mode on?
define("DEBUGMODE", false);

//this is the default language
$language = "de_de";

//set timezone (a list of locations is available at http://php.net/manual/timezones.php)
@date_default_timezone_set('Europe/Berlin');

$realmpool = "Your Project";
$realms = array(
			
			//insert the following line for every realm you want to appear in the arsenal:
			//number => new Realm($realmId = null, $realmType = null, $realmName = null)
			//Type MUST match the name of a [Type]DataGrabber.class.php for the Arsenal to work (you can write your own!)
			
			1=>new Realm(1, 'Trinity_PVP', 'PvP-Realm'),
			2=>new Realm(2, 'Trinity_PVE', 'PvE-Realm')
			
		);

$blacklistedChars = array(
			
			//insert names of chars that should not be displayed
			//for each realm-id
			
			1 => array('Admin','Serveradmin','Developerchar'),
			2 => array('Admin','Serveradmin','Developerchar')
			
		);


//should we use cache-files?
//it is VERY RECOMMENDED to turn this on, it saves A LOT of processing time, turn OFF for testing purposes
define("USEFILECACHE", false);
define("FILECACHEFOLDER", './cache/');		//don't forget the tailing slash
define("ALTERNATEDBCACHE", false);			//save xml in the database instead of files (not implemented yet)
define("UPDATEINTERVAL", 12);				//interval for cached information (in hours)


/* the databases:

The currently supported database backends (MDB2) are:

fbsql  -> FrontBase
ibase  -> InterBase / Firebird (requires PHP 5)
mssql  -> Microsoft SQL Server (NOT for Sybase. Compile PHP --with-mssql)
mysql  -> MySQL
mysqli -> MySQL (supports new authentication protocol) (requires PHP 5)
oci8   -> Oracle 7/8/9/10
pgsql  -> PostgreSQL
querysim -> QuerySim
sqlite -> SQLite 2
pdoSqlite -> SQLite 3 by Marin Ivanov <metala@metala.org>

*/

$cache_db_type = 'mysql';			//one of the above types
$cache_db_host = 'localhost';  		//db ip or hostname (with port or without); leave empty for i.e. sqlite
$cache_db_user = 'root';			//the username; leave empty for i.e. sqlite
$cache_db_pass = 'root';			//the password; leave empty for i.e. sqlite
$cache_db_base = 'arsenal';			//the db-name; for sqlite this would be the filepath (remember using forward-slashes in windows, too!)

$data_db_type = 'pdoSqlite';		//if you set this to mysql and have both arsenal dbs on the same host (i.e. localhost) be sure 
$data_db_host = '';					//to set one to "localhost" and one to "127.0.0.1" for otherwise it won't open a new connection
$data_db_user = '';					//and this may have unforseen consequences
$data_db_pass = '';
$data_db_base = './utils/arsenaldata.sqlite3';





/**************************************************************************
 *                                                                        *
 *    don't mofify below this line unless you know what you're doing!     *
 *                                                                        *
/**************************************************************************/

//set error handler to more modern, exception-based (and catchable!) behavior
switch(ERROR_REPORTING_LEVEL){
	
	case 0:	 $ERROR_REPORING = 0; break;
	case 1:	 $ERROR_REPORING = E_ALL ^ E_NOTICE ^ E_WARNING; break;
	case 2:	 $ERROR_REPORING = E_ALL ^ E_NOTICE; break;
	case 3:	 $ERROR_REPORING = E_ALL; break;
	case 4:	 $ERROR_REPORING = -1; break;
	default: $ERROR_REPORING = E_ALL ^ E_NOTICE ^ E_WARNING;
	
}

//add deprecated for error levels smaller than 3
if ((version_compare(PHP_VERSION, '5.3.0') >= 0) && ERROR_REPORTING_LEVEL < 3) {
	$ERROR_REPORING = $ERROR_REPORING ^ E_DEPRECATED;
	//var_dump($ERROR_REPORING);
}

@error_reporting($ERROR_REPORING);

?>