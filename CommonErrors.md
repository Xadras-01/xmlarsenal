<br /><br />
### Empty page or "no element found" XML warning ###

Some PHP installations seem to have problems with set set\_error\_handler(). If you get a blank page please go to _includes/functions.inc.php_, line 77 and comment it out.
<br /><br />
<br />
### Error messages starting with "Notice:" or "PHP Deprecated:" ###

Please go to _includes/config.inc.php_, line 21 and set `ERROR_REPORING` to `E_ALL ^ E_NOTICE`
<br /><br />
<br />
### MDB2 Error: already exists ###

seems your php install has PEAR MDB2 already onboard. go to _includes/functions.inc.php_, line 73 and comment it like that:

`//set_include_path('./includes/pear');`

or simply remove the line.
<br /><br />
<br />
### Error loading stylesheets ###

You must NOT put XMLArsenal into a subdir like /armory. Instead you have to create a domain/subdomain + virtual host for it like "arsenal.yourdomain.com" or "yourdomain.com".