<?php

/**
 * readArsenalDBCs.php
 * 
 * Tool for reading DBC files and parsing them into the arsenaldata.sqlite3
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/12/11
 * @package XMLArsenal
 * @subpackage utils
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

header('Content-Type:text/plain;charset=utf-8');

//time starts
$time_start = time();

//include my parser
require_once 'DBCParser.class.php';

//give it some time
set_time_limit(3600);
//and some ram
ini_set('memory_limit', "2048M");

//open arsenaldb
$dbhandle = new PDO('sqlite:./sqlitemanager/arsenaldata.sqlite3');


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

if(0){

$file_name = 'Talent.dbc';
$talent_table = DBCParser::readDBC($file_name, array());
$file_name = 'TalentTab.dbc';
$talentTab_table = DBCParser::readDBC($file_name, range(1, 12));
$file_name = 'SpellIcon.dbc';
$spellicon_table = DBCParser::readDBC($file_name, array(1));

//var_dump($talent_table);die();
$classtabs = array(41=>8, 61=>8, 81=>8, 161=>1, 163=>1, 164=>1, 181=>4, 182=>4, 183=>4, 201=>5, 202=>5, 
						203=>5, 261=>7, 262=>7, 263=>7, 281=>11, 282=>11, 283=>11, 301=>9, 302=>9, 303=>9,
						361=>3, 362=>3, 363=>3, 381=>2, 382=>2, 383=>2, 398=>6, 399=>6, 400=>6);

$dbhandle->exec("DELETE FROM talents;");
$dbhandle->exec("DELETE FROM talenttabs;");
$dbhandle->exec("DELETE FROM talenttabs_en_gb;");
$dbhandle->exec("DELETE FROM talenttabs_es_es;");
$dbhandle->exec("DELETE FROM talenttabs_fr_fr;");
$dbhandle->exec("DELETE FROM talenttabs_de_de;");
$dbhandle->exec("VACUUM;");
$dbhandle->beginTransaction();



foreach($talent_table as $talent){
	
	if($classtabs[$talent[1]]) $classid = $classtabs[$talent[1]];
	
	
	if($classid){
		
		$qry = "INSERT INTO talents VALUES ('".$talent[0]."','".$talent[2]."','".$talent[3]."','".$classid."','".$talent[1]."','".$talent[4]."','".$talent[5]."','".$talent[6]."','".$talent[7]."','".$talent[8]."','".$talent[13]."');";
		//echo $qry."\n";
		
		$dbhandle->exec($qry);
		
	}
	
}

foreach($talentTab_table as $talenttab){
	
	$qry = "INSERT INTO talenttabs_en_gb VALUES ('".$talenttab[0]."','".$talenttab[1]."');";
	$dbhandle->exec($qry);
	
	foreach($spellicon_table as $spellicon_row){
		
		if($spellicon_row[0] == $talenttab[18]){
			
			$qry = "INSERT INTO talenttabs VALUES ('".$talenttab[0]."',".$dbhandle->quote(strtolower(str_replace('Interface\Icons\\', '', $spellicon_row[1]))).");";
			$dbhandle->exec($qry);
			break;
		}
		
	}//foreach
	
}

$file_name = 'deDE/TalentTab.dbc';
$talentTab_table = DBCParser::readDBC($file_name, range(1, 12));
foreach($talentTab_table as $talenttab){
	
	$qry = "INSERT INTO talenttabs_de_de VALUES ('".$talenttab[0]."','".$talenttab[4]."');";
	$dbhandle->exec($qry);
}

$file_name = 'esES/TalentTab.dbc';
$talentTab_table = DBCParser::readDBC($file_name, range(1, 12));
foreach($talentTab_table as $talenttab){
	
	$qry = "INSERT INTO talenttabs_es_es VALUES ('".$talenttab[0]."','".$talenttab[7]."');";
	$dbhandle->exec($qry);
}

$file_name = 'frFR/TalentTab.dbc';
$talentTab_table = DBCParser::readDBC($file_name, range(1, 12));
foreach($talentTab_table as $talenttab){
	
	$qry = "INSERT INTO talenttabs_fr_fr VALUES ('".$talenttab[0]."','".$talenttab[3]."');";
	$dbhandle->exec($qry);
}



$dbhandle->commit();

echo "`talents` table OK\n";

unset($talent_table);

}
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

if(0){

$file_name = 'Achievement.dbc';
$stringColumns = array_merge(range(4, 12), range(21, 29), range(43, 51));
$achievements_table = DBCParser::readDBC($file_name, $stringColumns);

$file_name = 'Achievement_Category.dbc';
$achievementcategory_table = DBCParser::readDBC($file_name, range(2, 10));

$file_name = 'SpellIcon.dbc';
$spellicon_table = DBCParser::readDBC($file_name, array(1));

$dbhandle->exec("DELETE FROM achievements;");
$dbhandle->exec("DELETE FROM achievementcategory;");
$dbhandle->exec("DELETE FROM achievementcategory_en_gb;");
$dbhandle->exec("DELETE FROM achievementcategory_de_de;");
$dbhandle->exec("DELETE FROM achievementcategory_es_es;");
$dbhandle->exec("DELETE FROM achievementcategory_fr_fr;");
$dbhandle->exec("DELETE FROM achievements_en_gb;");
$dbhandle->exec("DELETE FROM achievements_de_de;");
$dbhandle->exec("DELETE FROM achievements_es_es;");
$dbhandle->exec("DELETE FROM achievements_fr_fr;");


$dbhandle->exec("VACUUM;");
$dbhandle->beginTransaction();

foreach($achievementcategory_table as $rowid=>$values){

	$parent = $values[1];
	if($parent == 4294967295) $parent = 0;
	
	
	$dbhandle->exec("INSERT INTO achievementcategory VALUES ('".$values[0]."', '$parent');");
	$dbhandle->exec("INSERT INTO achievementcategory_en_gb VALUES ('".$values[0]."', '".$values[2]."');");
	
}

$file_name = 'deDE/Achievement_Category.dbc';
$achievementcategory_table = DBCParser::readDBC($file_name, range(2, 10));
foreach($achievementcategory_table as $rowid=>$values){
	$dbhandle->exec("INSERT INTO achievementcategory_de_de VALUES ('".$values[0]."', '".$values[5]."');");
}

$file_name = 'esES/Achievement_Category.dbc';
$achievementcategory_table = DBCParser::readDBC($file_name, range(2, 10));
foreach($achievementcategory_table as $rowid=>$values){
	$dbhandle->exec("INSERT INTO achievementcategory_es_es VALUES ('".$values[0]."', '".$values[8]."');");
}

$file_name = 'frFR/Achievement_Category.dbc';
$achievementcategory_table = DBCParser::readDBC($file_name, range(2, 10));
foreach($achievementcategory_table as $rowid=>$values){
	$dbhandle->exec("INSERT INTO achievementcategory_fr_fr VALUES ('".$values[0]."', '".$values[4]."');");
}


foreach($achievements_table as $rowid=>$values){
	
	foreach($values as $key=>$value){
		$values[$key] = sqlite_escape_string($value);
	}
	
	$faction = $values[1];
	if($faction == 0) $faction = 1;
	else if ($faction == 1) $faction = 0;
	else $faction = -1;
	
	foreach($spellicon_table as $spelliconrow){
		if($spelliconrow[0] == $values[42]) $icon = $dbhandle->quote(strtolower(str_replace('Interface\Icons\\', '', $spelliconrow[1])));
	}
	if(!$icon) $icon = 'NULL';
	
	$dbhandle->exec("INSERT INTO achievements VALUES ('".$values[0]."', '".$values[39]."', '".$values[38]."', '$faction', '".$values[3]."', '".$values[41]."', ".$icon.");");
	
	//english language (default as of the database)
	$dbhandle->exec("INSERT INTO achievements_en_gb VALUES ('".$values[0]."', '".$values[4]."', '".$values[21]."', '".$values[43]."');");
	
	
}//foreach
unset($achievements_table);

//german language
$file_name = 'deDE/Achievement.dbc';
$stringColumns = array_merge(range(4, 12), range(21, 29), range(43, 51));
$achievements_table = DBCParser::readDBC($file_name, $stringColumns);
foreach($achievements_table as $rowid=>$values){
	
	foreach($values as $key=>$value){
		$values[$key] = sqlite_escape_string($value);
	}
	
	$dbhandle->exec("INSERT INTO achievements_de_de VALUES ('".$values[0]."', '".$values[7]."', '".$values[24]."', '".$values[46]."');");
	
}//foreach
unset($achievements_table);

//french language
$file_name = 'frFR/Achievement.dbc';
$stringColumns = array_merge(range(4, 12), range(21, 29), range(43, 51));
$achievements_table = DBCParser::readDBC($file_name, $stringColumns);
foreach($achievements_table as $rowid=>$values){
	
	foreach($values as $key=>$value){
		$values[$key] = sqlite_escape_string($value);
	}
	
	$dbhandle->exec("INSERT INTO achievements_fr_fr VALUES ('".$values[0]."', '".$values[6]."', '".$values[23]."', '".$values[45]."');");
	
}//foreach
unset($achievements_table);

//spanish language
$file_name = 'esES/Achievement.dbc';
$stringColumns = array_merge(range(4, 12), range(21, 29), range(43, 51));
$achievements_table = DBCParser::readDBC($file_name, $stringColumns);
foreach($achievements_table as $rowid=>$values){
	
	foreach($values as $key=>$value){
		$values[$key] = sqlite_escape_string($value);
	}
	
	$dbhandle->exec("INSERT INTO achievements_es_es VALUES ('".$values[0]."', '".$values[10]."', '".$values[27]."', '".$values[49]."');");
	
}//foreach
unset($achievements_table);


$dbhandle->commit();
unset($achievements_table);
echo "`achievements` table OK\n";
echo "`achievements_en_gb` table OK\n";
echo "`achievements_de_de` table OK\n";
echo "`achievements_fr_fr` table OK\n";
echo "`achievements_es_es` table OK\n";

//UTF-8 test
//$statement = $dbhandle->query("select * from achievements_de_de where achievementid = 42;");
//var_dump($statement->fetchAll(PDO::FETCH_ASSOC));


}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

if(0){

$file_name = 'Item.dbc';
$item_table = DBCParser::readDBC($file_name, array());
$file_name = 'ItemDisplayInfo.dbc';
$itemDispInfo_table = DBCParser::readDBC($file_name, array(5));

$dbhandle->exec("DELETE FROM itemsdisplay;");
$dbhandle->exec("VACUUM;");


//this will hold the resultset
$items = array();
$displays = array();

foreach($item_table as $rowid=>$iteminfo){

	$items[$iteminfo[0]] = $iteminfo[5];

}//foreach

foreach($itemDispInfo_table as $rowid=>$itemdispinfo){

	$displays[$itemdispinfo[0]] = $itemdispinfo[5];

}//foreach

$dbhandle->beginTransaction();

foreach($items as $itemid=>$displayid){
	
	$qry = "INSERT INTO itemsdisplay VALUES ($itemid,'".strtolower($displays[$displayid])."');";
	$dbhandle->exec($qry);
	
}//foreach

$dbhandle->commit();
unset($item_table, $itemDispInfo_table);
echo "`itemsdisplay` table OK\n";

}
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

if(0){

$file_name = 'Spell.dbc';
$spell_table = DBCParser::readDBC($file_name, range(173, 188));
$file_name = 'ItemSet.dbc';
$set_table = DBCParser::readDBC($file_name, range(1, 16));
$file_name = 'SpellDuration.dbc';
$duration_table = DBCParser::readDBC($file_name, array());

$dbhandle->exec("DELETE FROM itemsets;");
$dbhandle->exec("DELETE FROM itemsets_en_gb;");
$dbhandle->exec("DELETE FROM itemsets_de_de;");
$dbhandle->exec("DELETE FROM itemsets_fr_fr;");
$dbhandle->exec("DELETE FROM itemsets_es_es;");
$dbhandle->exec("VACUUM;");


$dbhandle->beginTransaction();

foreach($set_table as $set){
	
	$qry = "INSERT INTO itemsets VALUES (".$set[0].",".$set[18].",".$set[19].",".$set[20].",".$set[21].",".$set[22].",".$set[23].",".$set[24].",".$set[25].",".$set[26].",".$set[27].",".$set[35].",".$set[36].",".$set[37].",".$set[38].",".$set[39].",".$set[40].",".$set[41].",".$set[42].",".$set[43].",".$set[44].",".$set[45].",".$set[46].",".$set[47].",".$set[48].",".$set[49].",".$set[50].");";
	
	foreach($spell_table as $spell){
		
		if($spell[0] == $set[35]) $spell1 = $spell;
		if($spell[0] == $set[36]) $spell2 = $spell;
		if($spell[0] == $set[37]) $spell3 = $spell;
		if($spell[0] == $set[38]) $spell4 = $spell;
		if($spell[0] == $set[39]) $spell5 = $spell;
		if($spell[0] == $set[40]) $spell6 = $spell;
		if($spell[0] == $set[41]) $spell7 = $spell;
		if($spell[0] == $set[42]) $spell8 = $spell;
		
	}
	
	$desc1 = ($spell1 > 0) ? $dbhandle->quote(lookup($spell1)) : 'NULL';
	$desc2 = ($spell2 > 0) ? $dbhandle->quote(lookup($spell2)) : 'NULL';
	$desc3 = ($spell3 > 0) ? $dbhandle->quote(lookup($spell3)) : 'NULL';
	$desc4 = ($spell4 > 0) ? $dbhandle->quote(lookup($spell4)) : 'NULL';
	$desc5 = ($spell5 > 0) ? $dbhandle->quote(lookup($spell5)) : 'NULL';
	$desc6 = ($spell6 > 0) ? $dbhandle->quote(lookup($spell6)) : 'NULL';
	$desc7 = ($spell7 > 0) ? $dbhandle->quote(lookup($spell7)) : 'NULL';
	$desc8 = ($spell7 > 0) ? $dbhandle->quote(lookup($spell8)) : 'NULL';
	
	$qry2 = "INSERT INTO itemsets_en_gb VALUES (".$set[0].",".$dbhandle->quote($set[1]).", $desc1, $desc2, $desc3, $desc4, $desc5, $desc6, $desc7, $desc8);";
	
	$dbhandle->exec($qry);
	$dbhandle->exec($qry2);
	
	unset($spell1,$spell2,$spell3,$spell4,$spell5,$spell6,$spell7,$spell8);
	unset($desc1,$desc2,$desc3,$desc4,$desc5,$desc6,$desc7,$desc8);
	
}
unset($set_table, $spell_table);

$dbhandle->commit();
$dbhandle->beginTransaction();

//german language
$file_name = 'deDE/ItemSet.dbc';
$set_table = DBCParser::readDBC($file_name, range(1, 16));
$file_name = 'deDE/Spell.dbc';
$spell_table = DBCParser::readDBC($file_name, range(173, 188));

foreach($set_table as $set){
	
	foreach($spell_table as $spell){
		
		if($spell[0] == $set[35]) $spell1 = $spell;
		if($spell[0] == $set[36]) $spell2 = $spell;
		if($spell[0] == $set[37]) $spell3 = $spell;
		if($spell[0] == $set[38]) $spell4 = $spell;
		if($spell[0] == $set[39]) $spell5 = $spell;
		if($spell[0] == $set[40]) $spell6 = $spell;
		if($spell[0] == $set[41]) $spell7 = $spell;
		if($spell[0] == $set[42]) $spell8 = $spell;
		
	}
	
	$desc1 = ($spell1 > 0) ? $dbhandle->quote(lookup($spell1, 'de_de')) : 'NULL';
	$desc2 = ($spell2 > 0) ? $dbhandle->quote(lookup($spell2, 'de_de')) : 'NULL';
	$desc3 = ($spell3 > 0) ? $dbhandle->quote(lookup($spell3, 'de_de')) : 'NULL';
	$desc4 = ($spell4 > 0) ? $dbhandle->quote(lookup($spell4, 'de_de')) : 'NULL';
	$desc5 = ($spell5 > 0) ? $dbhandle->quote(lookup($spell5, 'de_de')) : 'NULL';
	$desc6 = ($spell6 > 0) ? $dbhandle->quote(lookup($spell6, 'de_de')) : 'NULL';
	$desc7 = ($spell7 > 0) ? $dbhandle->quote(lookup($spell7, 'de_de')) : 'NULL';
	$desc8 = ($spell7 > 0) ? $dbhandle->quote(lookup($spell8, 'de_de')) : 'NULL';
	
	$qry2 = "INSERT INTO itemsets_de_de VALUES (".$set[0].",".$dbhandle->quote($set[4]).", $desc1, $desc2, $desc3, $desc4, $desc5, $desc6, $desc7, $desc8);";
	
	$dbhandle->exec($qry2);
	
	unset($spell1,$spell2,$spell3,$spell4,$spell5,$spell6,$spell7,$spell8);
	unset($desc1,$desc2,$desc3,$desc4,$desc5,$desc6,$desc7,$desc8);
	

}
unset($set_table, $spell_table);

$dbhandle->commit();
$dbhandle->beginTransaction();

//spanish language
$file_name = 'esES/ItemSet.dbc';
$set_table = DBCParser::readDBC($file_name, range(1, 16));
$file_name = 'esES/Spell.dbc';
$spell_table = DBCParser::readDBC($file_name, range(173, 188));

foreach($set_table as $set){
	
	foreach($spell_table as $spell){
		
		if($spell[0] == $set[35]) $spell1 = $spell;
		if($spell[0] == $set[36]) $spell2 = $spell;
		if($spell[0] == $set[37]) $spell3 = $spell;
		if($spell[0] == $set[38]) $spell4 = $spell;
		if($spell[0] == $set[39]) $spell5 = $spell;
		if($spell[0] == $set[40]) $spell6 = $spell;
		if($spell[0] == $set[41]) $spell7 = $spell;
		if($spell[0] == $set[42]) $spell8 = $spell;
		
	}
	
	$desc1 = ($spell1 > 0) ? $dbhandle->quote(lookup($spell1, 'es_es')) : 'NULL';
	$desc2 = ($spell2 > 0) ? $dbhandle->quote(lookup($spell2, 'es_es')) : 'NULL';
	$desc3 = ($spell3 > 0) ? $dbhandle->quote(lookup($spell3, 'es_es')) : 'NULL';
	$desc4 = ($spell4 > 0) ? $dbhandle->quote(lookup($spell4, 'es_es')) : 'NULL';
	$desc5 = ($spell5 > 0) ? $dbhandle->quote(lookup($spell5, 'es_es')) : 'NULL';
	$desc6 = ($spell6 > 0) ? $dbhandle->quote(lookup($spell6, 'es_es')) : 'NULL';
	$desc7 = ($spell7 > 0) ? $dbhandle->quote(lookup($spell7, 'es_es')) : 'NULL';
	$desc8 = ($spell7 > 0) ? $dbhandle->quote(lookup($spell8, 'es_es')) : 'NULL';
	
	$qry2 = "INSERT INTO itemsets_es_es VALUES (".$set[0].",".$dbhandle->quote($set[7]).", $desc1, $desc2, $desc3, $desc4, $desc5, $desc6, $desc7, $desc8);";
	
	$dbhandle->exec($qry2);
	
	unset($spell1,$spell2,$spell3,$spell4,$spell5,$spell6,$spell7,$spell8);
	unset($desc1,$desc2,$desc3,$desc4,$desc5,$desc6,$desc7,$desc8);
	

}
unset($set_table, $spell_table);

$dbhandle->commit();
$dbhandle->beginTransaction();

//french language
$file_name = 'frFR/ItemSet.dbc';
$set_table = DBCParser::readDBC($file_name, range(1, 16));
$file_name = 'frFR/Spell.dbc';
$spell_table = DBCParser::readDBC($file_name, range(173, 188));

foreach($set_table as $set){
	
	foreach($spell_table as $spell){
		
		if($spell[0] == $set[35]) $spell1 = $spell;
		if($spell[0] == $set[36]) $spell2 = $spell;
		if($spell[0] == $set[37]) $spell3 = $spell;
		if($spell[0] == $set[38]) $spell4 = $spell;
		if($spell[0] == $set[39]) $spell5 = $spell;
		if($spell[0] == $set[40]) $spell6 = $spell;
		if($spell[0] == $set[41]) $spell7 = $spell;
		if($spell[0] == $set[42]) $spell8 = $spell;
		
	}
	
	$desc1 = ($spell1 > 0) ? $dbhandle->quote(lookup($spell1, 'fr_fr')) : 'NULL';
	$desc2 = ($spell2 > 0) ? $dbhandle->quote(lookup($spell2, 'fr_fr')) : 'NULL';
	$desc3 = ($spell3 > 0) ? $dbhandle->quote(lookup($spell3, 'fr_fr')) : 'NULL';
	$desc4 = ($spell4 > 0) ? $dbhandle->quote(lookup($spell4, 'fr_fr')) : 'NULL';
	$desc5 = ($spell5 > 0) ? $dbhandle->quote(lookup($spell5, 'fr_fr')) : 'NULL';
	$desc6 = ($spell6 > 0) ? $dbhandle->quote(lookup($spell6, 'fr_fr')) : 'NULL';
	$desc7 = ($spell7 > 0) ? $dbhandle->quote(lookup($spell7, 'fr_fr')) : 'NULL';
	$desc8 = ($spell7 > 0) ? $dbhandle->quote(lookup($spell8, 'fr_fr')) : 'NULL';
	
	$qry2 = "INSERT INTO itemsets_fr_fr VALUES (".$set[0].",".$dbhandle->quote($set[3]).", $desc1, $desc2, $desc3, $desc4, $desc5, $desc6, $desc7, $desc8);";
	
	$dbhandle->exec($qry2);
	
	unset($spell1,$spell2,$spell3,$spell4,$spell5,$spell6,$spell7,$spell8);
	unset($desc1,$desc2,$desc3,$desc4,$desc5,$desc6,$desc7,$desc8);
	

}
unset($set_table, $spell_table);

$dbhandle->commit();

echo "`itemsets` table OK\n";
echo "`itemsets_en_gb` table OK\n";
echo "`itemsets_de_de` table OK\n";
echo "`itemsets_fr_fr` table OK\n";
echo "`itemsets_es_es` table OK\n";

}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


if(0){

$dbhandle->beginTransaction();
$file_name = 'SpellItemEnchantment.dbc';
$enchant_table = DBCParser::readDBC($file_name, range(14, 29));

foreach($enchant_table as $enchant){

	$qry = "INSERT INTO itemenchantments VALUES (".$enchant[0].",".$enchant[2].",".$enchant[3].",".$enchant[4].", NULL, NULL, ".$enchant[11].",".$enchant[12].",".$enchant[13].", NULL, NULL, ".$enchant[5].",".$enchant[6].",".$enchant[7].",NULL,NULL,".$enchant[8].",".$enchant[9].",".$enchant[10].",NULL,NULL,".$enchant[33].");";
	$qry2 = "INSERT INTO itemenchantments_en_gb VALUES (".$enchant[0].",".$dbhandle->quote($enchant[14]).");";
	$dbhandle->exec($qry);
	$dbhandle->exec($qry2);

}

//german language
$file_name = 'deDE/SpellItemEnchantment.dbc';
$enchant_table = DBCParser::readDBC($file_name, range(14, 29));
foreach($enchant_table as $enchant){

	$qry2 = "INSERT INTO itemenchantments_de_de VALUES (".$enchant[0].",".$dbhandle->quote($enchant[17]).");";
	$dbhandle->exec($qry2);

}

//spanish language
$file_name = 'esES/SpellItemEnchantment.dbc';
$enchant_table = DBCParser::readDBC($file_name, range(14, 29));
foreach($enchant_table as $enchant){

	$qry2 = "INSERT INTO itemenchantments_es_es VALUES (".$enchant[0].",".$dbhandle->quote($enchant[20]).");";
	$dbhandle->exec($qry2);

}

//french language
$file_name = 'frFR/SpellItemEnchantment.dbc';
$enchant_table = DBCParser::readDBC($file_name, range(14, 29));
foreach($enchant_table as $enchant){

	$qry2 = "INSERT INTO itemenchantments_fr_fr VALUES (".$enchant[0].",".$dbhandle->quote($enchant[16]).");";
	$dbhandle->exec($qry2);

}
$dbhandle->commit();

unset($enchant_table);


}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


if(0){
	
	
$file_name = 'Spell.dbc';
$spell_table = DBCParser::readDBC($file_name, array_merge(range(173, 188), range(139, 150)));
$file_name = 'GlyphProperties.dbc';
$glyph_table = DBCParser::readDBC($file_name, array());
$file_name = 'SpellDuration.dbc';
$duration_table = DBCParser::readDBC($file_name, array());

$dbhandle->exec("DELETE FROM glyphs;");
$dbhandle->exec("DELETE FROM glyphs_en_gb;");
$dbhandle->exec("DELETE FROM glyphs_de_de;");
$dbhandle->exec("DELETE FROM glyphs_fr_fr;");
$dbhandle->exec("DELETE FROM glyphs_es_es;");
$dbhandle->exec("VACUUM;");

$dbhandle->beginTransaction();
foreach($glyph_table as $glyph){
	
	$qry = "INSERT INTO glyphs VALUES (".$glyph[0].",".$glyph[2].", ".$glyph[1].", ".$glyph[3].");";
	$dbhandle->exec($qry);
	
	foreach($spell_table as $spell){
		
		if($spell[0] == $glyph[1]){
			
			$title = $spell[139]; //en=139, de=142, es=145, fr=141
			
			$desc = lookup($spell);
			//var_dump($desc.":".$spell[173]."\n");
			
			$qry2 = "INSERT INTO glyphs_en_gb VALUES (".$glyph[0].", ".$dbhandle->quote($title).", ".$dbhandle->quote($desc).");";
			$dbhandle->exec($qry2);
			
			break;
		}
		
	}
	
	
}//forach

//german
unset($spell_table);
$file_name = 'deDE/Spell.dbc';
$spell_table = DBCParser::readDBC($file_name, array_merge(range(173, 188), range(139, 150)));
foreach($glyph_table as $glyph){
	
	foreach($spell_table as $spell){
		
		if($spell[0] == $glyph[1]){
			
			$title = $spell[142]; //en=139, de=142, es=145, fr=141
			
			$desc = lookup($spell, "de_de");
			//var_dump($desc.":".$spell[173]."\n");
			
			$qry2 = "INSERT INTO glyphs_de_de VALUES (".$glyph[0].", ".$dbhandle->quote($title).", ".$dbhandle->quote($desc).");";
			$dbhandle->exec($qry2);
			
			break;
		}
		
	}
	
	
}//forach

//french
unset($spell_table);
$file_name = 'frFR/Spell.dbc';
$spell_table = DBCParser::readDBC($file_name, array_merge(range(173, 188), range(139, 150)));
foreach($glyph_table as $glyph){
	
	foreach($spell_table as $spell){
		
		if($spell[0] == $glyph[1]){
			
			$title = $spell[142]; //en=139, de=142, es=145, fr=141
			
			$desc = lookup($spell, "fr_fr");
			//var_dump($desc.":".$spell[173]."\n");
			
			$qry2 = "INSERT INTO glyphs_fr_fr VALUES (".$glyph[0].", ".$dbhandle->quote($title).", ".$dbhandle->quote($desc).");";
			$dbhandle->exec($qry2);
			
			break;
		}
		
	}
	
	
}//forach

//spanish
unset($spell_table);
$file_name = 'esES/Spell.dbc';
$spell_table = DBCParser::readDBC($file_name, array_merge(range(173, 188), range(139, 150)));
foreach($glyph_table as $glyph){
	
	foreach($spell_table as $spell){
		
		if($spell[0] == $glyph[1]){
			
			$title = $spell[142]; //en=139, de=142, es=145, fr=141
			
			$desc = lookup($spell, "es_es");
			//var_dump($desc.":".$spell[173]."\n");
			
			$qry2 = "INSERT INTO glyphs_es_es VALUES (".$glyph[0].", ".$dbhandle->quote($title).", ".$dbhandle->quote($desc).");";
			$dbhandle->exec($qry2);
			
			break;
		}
		
	}
	
	
}//forach

$dbhandle->commit();


}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////



if(0){

$file_name = 'Faction.dbc';
$faction_table = DBCParser::readDBC($file_name, range(19,30));

$dbhandle->exec("DELETE FROM factions_en_gb;");
$dbhandle->exec("DELETE FROM factions_es_es;");
$dbhandle->exec("DELETE FROM factions_fr_fr;");
$dbhandle->exec("DELETE FROM factions_de_de;");
$dbhandle->exec("VACUUM;");
$dbhandle->beginTransaction();

foreach($faction_table as $factionrow){
	$dbhandle->exec("INSERT INTO factions_en_gb VALUES (".$factionrow[0].",".$dbhandle->quote($factionrow[19]).");");
}
unset($faction_table);

$file_name = 'deDE/Faction.dbc';
$faction_table = DBCParser::readDBC($file_name, range(19,30));
foreach($faction_table as $factionrow){
	$dbhandle->exec("INSERT INTO factions_de_de VALUES (".$factionrow[0].",".$dbhandle->quote($factionrow[22]).");");
}
unset($faction_table);

$file_name = 'esES/Faction.dbc';
$faction_table = DBCParser::readDBC($file_name, range(19,30));
foreach($faction_table as $factionrow){
	$dbhandle->exec("INSERT INTO factions_es_es VALUES (".$factionrow[0].",".$dbhandle->quote($factionrow[25]).");");
}
unset($faction_table);

$file_name = 'frFR/Faction.dbc';
$faction_table = DBCParser::readDBC($file_name, range(19,30));
foreach($faction_table as $factionrow){
	$dbhandle->exec("INSERT INTO factions_fr_fr VALUES (".$factionrow[0].",".$dbhandle->quote($factionrow[21]).");");
}
unset($faction_table);


$dbhandle->commit();
echo "factions ok\n";

}



////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


if(0){

$file_name = 'Achievement_Criteria.dbc';
$ach_table = DBCParser::readDBC($file_name, range(9,24));

$dbhandle->exec("DELETE FROM achievementcriteria;");
$dbhandle->exec("DELETE FROM achievementcriteria_en_gb;");
$dbhandle->exec("DELETE FROM achievementcriteria_es_es;");
$dbhandle->exec("DELETE FROM achievementcriteria_fr_fr;");
$dbhandle->exec("DELETE FROM achievementcriteria_de_de;");
$dbhandle->exec("VACUUM;");
$dbhandle->beginTransaction();

foreach($ach_table as $achrow){
	$dbhandle->exec("INSERT INTO achievementcriteria VALUES (".$achrow[0].",".$achrow[1].", ".$achrow[30].");");
	$dbhandle->exec("INSERT INTO achievementcriteria_en_gb VALUES (".$achrow[0].",".$dbhandle->quote($achrow[9]).");");
}
unset($ach_table);

$file_name = 'deDE/Achievement_Criteria.dbc';
$ach_table = DBCParser::readDBC($file_name, range(9,24));
foreach($ach_table as $achrow){
	$dbhandle->exec("INSERT INTO achievementcriteria_de_de VALUES (".$achrow[0].",".$dbhandle->quote($achrow[12]).");");
}
unset($ach_table);

$file_name = 'esES/Achievement_Criteria.dbc';
$ach_table = DBCParser::readDBC($file_name, range(9,24));
foreach($ach_table as $achrow){
	$dbhandle->exec("INSERT INTO achievementcriteria_es_es VALUES (".$achrow[0].",".$dbhandle->quote($achrow[15]).");");
}
unset($ach_table);

$file_name = 'frFR/Achievement_Criteria.dbc';
$ach_table = DBCParser::readDBC($file_name, range(9,24));
foreach($ach_table as $achrow){
	$dbhandle->exec("INSERT INTO achievementcriteria_fr_fr VALUES (".$achrow[0].",".$dbhandle->quote($achrow[11]).");");
}
unset($ach_table);


$dbhandle->commit();
echo "achievementcriteria ok\n";
}


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


if(0){

$dbhandle->beginTransaction();
$file_name = 'ItemRandomProperties.dbc';
$enchant_table = DBCParser::readDBC($file_name, range(7, 22));

foreach($enchant_table as $enchant){

	$qry = "INSERT INTO randomproperties VALUES (".$enchant[0].",".$enchant[2].",".$enchant[3].",".$enchant[4].");";
	$qry2 = "INSERT INTO randomproperties_en_gb VALUES (".$enchant[0].",".$dbhandle->quote($enchant[7]).");";
	$dbhandle->exec($qry);
	$dbhandle->exec($qry2);

}

//german language
$file_name = 'deDE/ItemRandomProperties.dbc';
$enchant_table = DBCParser::readDBC($file_name, range(7, 22));
foreach($enchant_table as $enchant){

	$qry2 = "INSERT INTO randomproperties_de_de VALUES (".$enchant[0].",".$dbhandle->quote($enchant[10]).");";
	$dbhandle->exec($qry2);

}

//spanish language
$file_name = 'esES/ItemRandomProperties.dbc';
$enchant_table = DBCParser::readDBC($file_name, range(7, 22));
foreach($enchant_table as $enchant){

	$qry2 = "INSERT INTO randomproperties_es_es VALUES (".$enchant[0].",".$dbhandle->quote($enchant[13]).");";
	$dbhandle->exec($qry2);

}

//french language
$file_name = 'frFR/ItemRandomProperties.dbc';
$enchant_table = DBCParser::readDBC($file_name, range(7, 22));
foreach($enchant_table as $enchant){

	$qry2 = "INSERT INTO randomproperties_fr_fr VALUES (".$enchant[0].",".$dbhandle->quote($enchant[9]).");";
	$dbhandle->exec($qry2);

}
$dbhandle->commit();

unset($enchant_table);


}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////




if(0){

$dbhandle->beginTransaction();
$file_name = 'CharTitles.dbc';
$titles_table = DBCParser::readDBC($file_name, range(2, 35));
foreach($titles_table as $title){

	$qry = "INSERT INTO titles VALUES (".$title[0].",".$title[36].");";
	$qry2 = "INSERT INTO titles_en_gb VALUES (".$title[0].",".$dbhandle->quote($title[2]).", ".$dbhandle->quote($title[19]).");";
	$dbhandle->exec($qry);
	$dbhandle->exec($qry2);

}

//german language
$file_name = 'deDE/CharTitles.dbc';
$titles_table = DBCParser::readDBC($file_name, range(2, 35));
foreach($titles_table as $title){

	$qry2 = "INSERT INTO titles_de_de VALUES (".$title[0].",".$dbhandle->quote($title[5]).", ".$dbhandle->quote($title[22]).");";
	$dbhandle->exec($qry2);

}

//spanish language
$file_name = 'esES/CharTitles.dbc';
$titles_table = DBCParser::readDBC($file_name, range(2, 35));
foreach($titles_table as $title){

	$qry2 = "INSERT INTO titles_es_es VALUES (".$title[0].",".$dbhandle->quote($title[8]).", ".$dbhandle->quote($title[25]).");";
	$dbhandle->exec($qry2);

}

//french language
$file_name = 'frFR/CharTitles.dbc';
$titles_table = DBCParser::readDBC($file_name, range(2, 35));
foreach($titles_table as $title){

	$qry2 = "INSERT INTO titles_fr_fr VALUES (".$title[0].",".$dbhandle->quote($title[4]).", ".$dbhandle->quote($title[21]).");";
	$dbhandle->exec($qry2);

}
$dbhandle->commit();

unset($titles_table);


}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////




$mem = round((memory_get_peak_usage(true)/1024)/1024, 2);
$time = time() - $time_start;

echo "

All done.
runtime: $time sec.
memory-usage: $mem MB";


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

//thanks to sourcepeek for the basic idea (and the regexp ;)
function lookup($thespell, $language = "en_gb"){
	
	$signs = array('+', '-', '/', '*', '%', '^');
	
	if($language == "en_gb") $data = $thespell[173];
	if($language == "de_de") $data = $thespell[176];
	if($language == "es_es") $data = $thespell[179];
	if($language == "fr_fr") $data = $thespell[175];
	
	$pos = 0;
	$str = "";
	global $spell_table;
	global $duration_table;
	
	foreach($duration_table as $duration){
		if($thespell[37] == $duration[0]){
			$lastduration = ($duration[1] > 0 ? $duration[1] : 0);
		}
	}
	//if (!$spellRow['duration_base'])
	//  	$lastduration = $db_link->query_result("SELECT * FROM `db_spellduration` WHERE `durationID` = '{$spellRow[durationID]}' ORDER BY `versionID` DESC LIMIT 1");
	
	if(strstr($data, "$") === false) return $data;
	
	while (false!==($npos=strpos($data, "$", $pos)))
	{
		if ($npos!=$pos)
			$str .= substr($data, $pos, $npos-$pos);
		$pos = $npos+1;
		
		if ("$" == substr($data, $pos, 1))
		{
			$str .= "$";
			$pos++;
			continue;
		}	
		
		
		if (!preg_match('/^((([+\-\/*])(\d+);)?(\d*)(?:([lg].*?:.*?);|(\w\d*)))/', substr($data, $pos), $result)) continue;
		if (empty($exprData[0])) $exprData[0] = 1;
		
		$op = $result[3];
		$oparg = $result[4];
		$lookup = $result[5];
		$var = $result[6] ? $result[6] : $result[7];
		$pos += strlen($result[0]);
		
		if (!$var) continue;
		
		$exprType = strtolower(substr($var, 0, 1));
		$exprData = explode(':', substr($var, 1));
		
		switch ($exprType)
			{
				case 's':
					if ($lookup > 0){
						
						foreach($spell_table as $possiblespell){
							
							if($possiblespell[0] == $lookup){
								$spell = $possiblespell;
								break;
							}
							
						}
						
					}
					else
						$spell = $thespell;
					
					//$spell['effect'.$exprData[0].'BasePoints']+1;
					$base = $spell[82+$exprData[0]] + 1;
					
					if($base > 999999) $base = 4294967295 - $spell[82+$exprData[0]];
					//var_dump($result);
					
					if (@in_array($op, $signs) && is_numeric($oparg) && is_numeric($base))
					{
						$equation = $base.$op.$oparg;
						eval("\$base = $equation;");
					}

					//$str .= abs($base).($spell['effect'.$exprData[0].'DieSides'] > 1 ? '-'.abs(($base+$spell['effect'.$exprData[0].'DieSides'])) : '');
					$str .= abs($base).($spell[(70 + $exprData[0])] > 1 ? '-'.abs(($base+$spell[(70 + $exprData[0])])) : '');
					$lastvalue = $base;
					break;
				
				case 'm':
					if ($lookup > 0){
						
						foreach($spell_table as $possiblespell){
							
							if($possiblespell[0] == $lookup){
								$spell = $possiblespell;
								break;
							}
							
						}
						
					}
						//$spell = $db_link->query_result("SELECT * FROM `db_spell` WHERE `spellID` = '$lookup' ORDER BY `versionID` DESC LIMIT 1");
					else
						$spell = $thespell;
						
					//$base = $spell['effect'.$exprData[0].'BasePoints']+1;
					$base = $spell[(82+$exprData[0])] + 1; 
					if($base > 999999) $base = 4294967295 - $spell[82+$exprData[0]];
					
					//var_dump($result);
					
					if (@in_array($op, $signs) && is_numeric($oparg) && is_numeric($base))
					{
						$equation = $base.$op.$oparg;
						eval("\$base = $equation;");
					}
					
					//var_dump($result);
					//var_dump($equation);
					
					$str .= abs($base);
					$lastvalue = $base;
					break;
				
				case 'h':
					if ($lookup > 0){
						
						foreach($spell_table as $possiblespell){
							
							if($possiblespell[0] == $lookup){
								$spell = $possiblespell;
								break;
							}
							
						}
						
					}
					else
						$spell = $thespell;

					$base = $spell[32];

					if (@in_array($op, $signs) && is_numeric($oparg) && is_numeric($base))
					{
						$equation = $base.$op.$oparg;
						eval("\$base = $equation;");
					}
					$str .= abs($base);
					break;
				
				case 'd':
					if ($lookup > 0){
						
						foreach($spell_table as $possiblespell){
							
							if($possiblespell[0] == $lookup){
								$spell = $possiblespell;
								break;
							}
							
						}
						
					}else $spell = $thespell;
					
					foreach($duration_table as $duration){
						if($spell[37] == $duration[0]){
							$base = ($duration[1] > 0 ? $duration[1] : 0);
						}
					}
					//$lastduration = $db_link->query_result("SELECT * FROM `db_spellduration` WHERE `durationID` = '{$spell[durationID]}' ORDER BY `versionID` DESC LIMIT 1");
					
					if ($op && is_numeric($oparg) && is_numeric($base))
					{
						$equation = $base.$op.$oparg;
						eval("\$base = $equation;");
					}
					if($language == 'de_de') $str .= $base/1000 . " Sek";
					if($language == 'en_gb') $str .= $base/1000 . " sec";
					if($language == 'fr_fr') $str .= $base/1000 . " sec";
					if($language == 'es_es') $str .= $base/1000 . " s";
					break;
				
				case 'o':
					if ($lookup > 0){
						
						foreach($spell_table as $possiblespell){
							
							if($possiblespell[0] == $lookup){
								$spell = $possiblespell;
								break;
							}
							
						}
						
					}else $spell = $thespell;
					
					foreach($duration_table as $duration){
						if($spell[37] == $duration[0]){
							$lastduration = ($duration[1] > 0 ? $duration[1] : 0);
						}
					}
					
					$base = $spell[83] + 1;
					//$base = $spell['effect'.$exprData[0].'BasePoints']+1;
					
					$amp = $spell[101];
					if ($amp <= 0) $amp = 5000;
					//if ($spell['effect'.$exprData[0].'Amplitude'] <= 0) $spell['effect'.$exprData[0].'Amplitude'] = 5000;
					
					$str .= @(($lastduration / $amp) * abs($base).($spell[71] > 1 ? '-'.abs(($base+$spell[71])) : ''));
					//$str .= @(($lastduration['durationBase'] / $spell['effect'.$exprData[0].'Amplitude']) * abs($base).($spell['effect'.$exprData[0].'DieSides'] > 1 ? '-'.abs(($base+$spell['effect'.$exprData[0].'DieSides'])) : ''));
					break;
					
				case 't':
				if ($lookup > 0){
						
						foreach($spell_table as $possiblespell){
							
							if($possiblespell[0] == $lookup){
								$spell = $possiblespell;
								break;
							}
							
						}
						
					}else $spell = $thespell;
				
				//$base = $spell['effect'.$exprData[0].'Amplitude']/1000;
				$inpos = 102+$exprData[0];
				$base = $spell[$inpos]/1000;
				
				if (@in_array($op, $signs) && is_numeric($oparg) && is_numeric($base))
				{
					$equation = $base.$op.$oparg;
					eval("\$base = $equation;");
				}
				$str .= abs($base);
				$lastvalue = $base;
				break;

				default:
					$str .= "[{$var} ($op::$oparg::$lookup::$exprData[0])]";
			}
	}
	$str .= substr($data, $pos);
	$str = preg_replace_callback("|\{([^\}]+)\}(\.\d)?|", create_function(
		'$matches',
		'return eval("return abs(".$matches[1].");");'
	), $str);

	return($str);
	
}

?>