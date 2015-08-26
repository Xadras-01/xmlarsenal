The config is kept quite simple, it does not involve much data and does not require too much knowledge about php or databases.

The configuration file is called **"config.inc.php"** and can be found in "/includes".


```
define('ERROR_REPORING', E_ALL ^ E_NOTICE);
```

By canging the value (`E_ALL ^ E_NOTICE`) to 0 you can disable all error reporting. This means that in case of an error your visitors will only get a blank page. This is most secure for productive systems as it doesn't reveal any info about the system the arsenal runs on in case of an error.

<br />
```
define("DEBUGMODE", false);
```

Setting this to true will erase your stored grabbed data from the arsenal every time you visit a page (so it has to be fetched anew). This is very expensive concerning processing time, RAM, database access and so on. **Turn off (false) for general usage.**

<br />
```
$language = "de_de";
```

Your arsenals default language. Currently supported: de\_de, en\_gb, en\_us, es\_es, es\_mx, fr\_fr

<br />
```
$realmpool = "Your projects name";
```

The name of your project or realmpool.

<br />
```
$realms = array(
			
	1=>new Realm(1, 'Mangos3_1_PVPRealm', 'PvP-Realm'),
	2=>new Realm(2, 'Mangos3_1_PVERealm', 'PvE-Realm')
			
	);
```

Ok, now it gets harder... but not too much ;)

For every realm you want supported in your arsenal there must be an entry here. As shown in the example we've got 2 realms here. Realm 1 is called **PvP-Realm** and grabs its data from the <strong>Mangos3_1_PVPRealm</strong>DataGrabber.class.php, respectivly the 2nd realm is called PvE-Realm (you can choose this freely) and uses Mangos3\_1\_PVERealmDataGrabber.class.php as its info source. So for every realm there must be a <strong>NameOfTheGrabber</strong>DataGrabber.class.php in "/classes/dataGrabbers".

<br />
```
define("USEFILECACHE", true);
```

If turned to false the arsenal will not save outputted xml to cache files. It will only use its internal cache but much of the data has to be re-calculated every time a visitor comes to a page. Using **true** will instead save a lot of processing time.

<br />
```
define("FILECACHEFOLDER", './cache/');
```

Where do you want to save the generated xmls? Please remember that your webserver-user (i.e. www-data) must have **write-permissions** in this folder. This is important for Unix as well as for modern (Vista or newer) Windows. You can use either relative or absolute pathnames.

<br />
```
define("ALTERNATEDBCACHE", false);
```

When using the file cache if you turn this option on the outputted xml will be saved in the database instead of files. This may be more performant in some installations, in any case it saves disk space (not so many small files) and keeps directory-sizes small. On the other hand the load of the database will be increased. So it's a matter of taste and  conditions whether to turn this on or off.

<br />
```
define("UPDATEINTERVAL", 12);
```

How often do you want your arsenal to update (in hours)?

XMLArsenals caching mechanism works like this: When you request a page the system first looks for a cached file (if file caching is enabled). If there is a file and the time since the last modification of this file is less than `UPDATEINTERVAL` hours it will simply output the cached version. Otherwise the system tries to use the arsenal's in-built caching if the last modification of the i.e. Character-Object from the arsenal-db is not longer in the past than `UPDATEINTERVAL` hours. Only if this fails (first time a char is read into the db or more than `UPDATEINTERVAL` hours since the last update) the DataGrabber will be invoked. It fetches fresh data and updates the modification dates of the objects.

<br />
```
$cache_db_type = 'mysql';
$cache_db_host = 'localhost';
$cache_db_user = 'root';
$cache_db_pass = 'root';
$cache_db_base = 'arsenal';

$data_db_type = 'pdoSqlite';
$data_db_host = '';
$data_db_user = '';
$data_db_pass = '';
$data_db_base = './utils/arsenaldata.sqlite3';
```

This last but not least section is quite self-explanatory. XMLArsenal uses PEAR:MDB2 for the database persistence layer so that you may use a variety of database systems to store the information that XMLArsenal needs to work. The supported DB-Systems are listed in the config-file. After selecting a type you must of course give the adress of the system the database is running on (host) as well as the credentials (user and password) to access the DB. Last one is the name of the database itself.

Host, user and password may be omitted in case of SQLite of course. db\_base then is the path to the SQLite-File.

The $cache\_db is where XMLArsenal stores characters, guilds etc. that have already been viewed, the $data\_db is where all the static arsenal data (i.e. language-specific strings, talents etc.) reside. By default $data\_db points to the arsenaldata.sqlite3 file.