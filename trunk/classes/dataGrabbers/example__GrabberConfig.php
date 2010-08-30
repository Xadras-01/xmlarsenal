<?php

/* This is a multi realm example for grabber config. 
   rename to GrabberConfig if you want to use it in your data grabbers */

$logindb = @mysql_connect("hsgvha.com", "arsenal", "senzusubdrz5", true) or die(get_class($this).": no connection to login database.");
@mysql_select_db("logon", $logindb) or die (get_class($this).": not able to select specified database in login db.");

$this->pvpdbconn = @mysql_connect("189.184.158.1", "hans", "suitgustacg", true) or die(get_class($this).": no connection to database."); 
@mysql_select_db("pvp_char", $this->pvpdbconn) or die (get_class($this).": not able to select specified database."); 

$this->pvedbconn = @mysql_connect("ns734265825.ovh.net", "arsenal", "gszhrt99eahtg", true) or die(get_class($this).": no connection to database."); 
@mysql_select_db("characters", $this->pvedbconn) or die (get_class($this).": not able to select specified database.");

?>