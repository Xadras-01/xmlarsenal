| ![http://grundschulwiki.zum.de/images/thumb/f/ff/Important.png/48px-Important.png](http://grundschulwiki.zum.de/images/thumb/f/ff/Important.png/48px-Important.png) | **XMLArsenal needs to run in a "virtual" root folder, i.e. `http://domain.countrycode/` or `http://subdomain.domain.countrycode/` but not `http://domain.countrycode/arsenal/`.** <br />See the [Apache Docs](http://httpd.apache.org/docs/2.0/en/mod/core.html#virtualhost) (de, en, es, ja, tr) or [Ubuntu Wiki](http://wiki.ubuntuusers.de/Apache/Virtual_Hosts) (de) how to accomplish this. |
|:--------------------------------------------------------------------------------------------------------------------------------------------------------------------|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|

<br />

## With the full package (release package) ##

  * Download the package from the project website and unzip it to a location of your choice.

  * After you have created a domain or subdomain, move the unzipped contents into the "DocumentRoot"-folder of this domain, for example ''/var/www/vhosts/domain.countrycode/httpdocs/''.
  * See the [Config](Config.md) for the next steps.

<br />

### With SVN + individual packages ###

The "SVN + individual packages"-method has several advantages. You have to download images and sqlite-database only once and can keep up2date concerning the project sourcecode. This means smaller download sizes. Furthermore the SVN is updated more often sou you always benefit from the latest fixes and additions.

On the other hand there is little downside, too. SVN builds are probably not tested thoroughly (and therefore contain hidden bugs) while release builds are tested by the rising-gods.de community.

  * Checkout the SVN trunk folder to the "DocumentRoot"-Folder of your new domain/subdomain
  * Download the images-package and unzip the contents into the "DocumentRoot"-Folder of your new domain/subdomain
  * Download arsenaldata.sqlite3 and place it in the ''utils''-folder.
  * See the [Config](Config.md) for the next steps.