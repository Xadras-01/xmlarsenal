## SQL file ##

The SQL file to make your database ready to work with XMLArsenal can be found here: [[of the arsenal database](SQL.md)]

<br />
## General Structure ##

  * Every table has the prefix `arsenal_` and afterwards a distinctive name, for example "characters" or "guilds".
  * Objects created by PHP are stored serialized (as a string) in the `data`-field if one is present.
  * Important attributes (i.e. the name of a character) can additionally be stored as an extra column.
  * Every single textual value is stored as UTF-8 for simplification

<br />
## `arsenal_arenaladder` ##

Only one entry: the serialized `Arenaladder`-object

<br />
## `arsenal_arenateams` ##

  * `entry_id` (PK): arsenal-internal id of the `Arenateam`-object
  * `arenateam_id_on_realm` : the realms own id for the team
  * `realm_id`: the realm the team resides on
  * `arenateam_name`: name of the arenateam
  * `data`: serialized `Arenateam`-object

<br />
## `arsenal_characters` ##

Same as `arsenal_arenateams` but for characters.

<br />
## `arsenal_guilds` ##

Same as `arsenal_arenateams` but for guilds.

<br />
## `arsenal_searches` ##

  * `entry_id` (PK): arsenal-internal id of the `Search`-object
  * `query_string`: text of the search query the user has started
  * `data`: serialized `Search`-object

<br />
## `arsenal_xmlcache` ##

  * `file_name` (PK): the name that the cache file would normally have
  * `timestamp`: the "last modified"-attribute that the cache file would normally have
  * `file_contents`: the actual xml data