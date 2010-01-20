<?php

/**
 * data.inc.php
 * 
 * This file contains static, mostly language-specific, data. The file MUST be UTF-8 (no BOM) encoded.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/10/28
 * @package XMLArsenal
 * @subpackage includes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/


//factions names
$DATA['factionNames_en_gb'] = array(1=>'Alliance', 2=>'Horde', 3=>'Alliance', 4=>'Alliance', 5=>'Horde', 6=>'Horde', 7=>'Alliance', 8=>'Horde', 10=>'Horde', 11=>'Alliance');
$DATA['factionNames_en_us'] = array(1=>'Alliance', 2=>'Horde', 3=>'Alliance', 4=>'Alliance', 5=>'Horde', 6=>'Horde', 7=>'Alliance', 8=>'Horde', 10=>'Horde', 11=>'Alliance');
$DATA['factionNames_de_de'] = array(1=>'Allianz', 2=>'Horde', 3=>'Allianz', 4=>'Allianz', 5=>'Horde', 6=>'Horde', 7=>'Allianz', 8=>'Horde', 10=>'Horde', 11=>'Allianz');
$DATA['factionNames_es_es'] = array(1=>'Alianza', 2=>'Horda', 3=>'Alianza', 4=>'Alianza', 5=>'Horda', 6=>'Horda', 7=>'Alianza', 8=>'Horda', 10=>'Horda', 11=>'Alianza');
$DATA['factionNames_es_mx'] = array(1=>'Alianza', 2=>'Horda', 3=>'Alianza', 4=>'Alianza', 5=>'Horda', 6=>'Horda', 7=>'Alianza', 8=>'Horda', 10=>'Horda', 11=>'Alianza');
$DATA['factionNames_fr_fr'] = array(1=>'Alliance', 2=>'Horde', 3=>'Alliance', 4=>'Alliance', 5=>'Horde', 6=>'Horde', 7=>'Alliance', 8=>'Horde', 10=>'Horde', 11=>'Alliance');
$DATA['factionNames_ru_ru'] = array(1=>'Альянс', 2=>'Орда', 3=>'Альянс', 4=>'Альянс', 5=>'Орда', 6=>'Орда', 7=>'Альянс', 8=>'Орда', 10=>'Орда', 11=>'Альянс');
$DATA['factionNames_ko_kr'] = array(1=>'얼라이언스', 2=>'호드', 3=>'얼라이언스', 4=>'얼라이언스', 5=>'호드', 6=>'호드', 7=>'얼라이언스', 8=>'호드', 10=>'호드', 11=>'얼라이언스');

//races
$DATA['races_en_gb'] = array(1=>'Human', 2=>'Orc', 3=>'Dwarf', 4=>'Night Elf', 5=>'Undead', 6=>'Taure', 7=>'Gnome', 8=>'Troll', 10=>'Blood Elf', 11=>'Draenei');
$DATA['races_en_us'] = array(1=>'Human', 2=>'Orc', 3=>'Dwarf', 4=>'Night Elf', 5=>'Undead', 6=>'Taure', 7=>'Gnome', 8=>'Troll', 10=>'Blood Elf', 11=>'Draenei');
$DATA['races_de_de'] = array(1=>'Mensch', 2=>'Orc', 3=>'Zwerg', 4=>'Nachtelf', 5=>'Untoter', 6=>'Taure', 7=>'Gnom', 8=>'Troll', 10=>'Blutelf', 11=>'Draenei');
$DATA['races_fr_fr'] = array(1=>'Humain', 2=>'Orc', 3=>'Nain', 4=>'Elfe de la nuit', 5=>'Mort-vivant', 6=>'Tauren', 7=>'Gnome', 8=>'Troll', 10=>'Elfe de sang', 11=>'Draeneï');
$DATA['races_es_es'] = array(1=>'Humano', 2=>'Orco', 3=>'Enano', 4=>'Elfo de la noche', 5=>'No-muerto', 6=>'Tauren', 7=>'Gnomo', 8=>'Trol', 10=>'Elfo de sangre', 11=>'Draenei');
$DATA['races_es_mx'] = array(1=>'Humano', 2=>'Orco', 3=>'Enano', 4=>'Elfo de la noche', 5=>'No-muerto', 6=>'Tauren', 7=>'Gnomo', 8=>'Trol', 10=>'Elfo de sangre', 11=>'Draenei');
$DATA['races_ru_ru'] = array(1=>'Человек', 2=>'Орк', 3=>'Дворф', 4=>'Ночной эльф', 5=>'Нежить', 6=>'Таурен', 7=>'Гном', 8=>'Тролль', 10=>'Эльф крови', 11=>'Дреней');
$DATA['races_ko_kr'] = array(1=>'인간', 2=>'오크', 3=>'드워프', 4=>'나이트 엘프', 5=>'언데드', 6=>'타우렌', 7=>'노움', 8=>'트롤', 10=>'블러드 엘프', 11=>'드레나이');

//classes
$DATA['classes_en_gb'] = array(1=>'Warrior', 2=>'Paladin', 3=>'Hunter', 4=>'Rogue', 5=>'Priest', 6=>'Death Knight', 7=>'Shaman', 8=>'Mage', 9=>'Warlock', 11=>'Druid');
$DATA['classes_en_us'] = array(1=>'Warrior', 2=>'Paladin', 3=>'Hunter', 4=>'Rogue', 5=>'Priest', 6=>'Death Knight', 7=>'Shaman', 8=>'Mage', 9=>'Warlock', 11=>'Druid');
$DATA['classes_de_de'] = array(1=>'Krieger', 2=>'Paladin', 3=>'Jäger', 4=>'Schurke', 5=>'Priester', 6=>'Todesritter', 7=>'Schamane', 8=>'Magier', 9=>'Hexenmeister', 11=>'Druide');
$DATA['classes_es_es'] = array(1=>'Guerrero', 2=>'Paladín', 3=>'Cazador', 4=>'Pícaro', 5=>'Sacerdote', 6=>'Caballero de la Muerte', 7=>'Chamán', 8=>'Mago', 9=>'Brujo', 11=>'Druida');
$DATA['classes_es_mx'] = array(1=>'Guerrero', 2=>'Paladín', 3=>'Cazador', 4=>'Pícaro', 5=>'Sacerdote', 6=>'Caballero de la Muerte', 7=>'Chamán', 8=>'Mago', 9=>'Brujo', 11=>'Druida');
$DATA['classes_fr_fr'] = array(1=>'Guerrier', 2=>'Paladin', 3=>'Chasseur', 4=>'Voleur', 5=>'Prêtre', 6=>'Chevalier de la mort', 7=>'Chaman', 8=>'Mage', 9=>'Démoniste', 11=>'Druide');
$DATA['classes_ru_ru'] = array(1=>'Воин', 2=>'Паладин', 3=>'Охотник', 4=>'Разбойник', 5=>'Жрец', 6=>'Рыцарь смерти', 7=>'Шаман', 8=>'Маг', 9=>'Чернокнижник', 11=>'Друид');
$DATA['classes_ko_kr'] = array(1=>'전사', 2=>'성기사', 3=>'사냥꾼', 4=>'도적', 5=>'사제', 6=>'죽음의 기사', 7=>'주술사', 8=>'마법사', 9=>'흑마법사', 11=>'드루이드');

//primary professions
$DATA['professions_en_gb'] = array(186=>'Mining', 182=>'Herbalism', 164=>'Blacksmithing', 165=>'Leatherworking', 171=>'Alchemy', 333=>'Enchanting', 755=>'Jewelcrafting', 202=>'Engineering', 393=>'Skinning', 197=>'Tailoring', 773=>'Inscription');
$DATA['professions_en_us'] = array(186=>'Mining', 182=>'Herbalism', 164=>'Blacksmithing', 165=>'Leatherworking', 171=>'Alchemy', 333=>'Enchanting', 755=>'Jewelcrafting', 202=>'Engineering', 393=>'Skinning', 197=>'Tailoring', 773=>'Inscription');
$DATA['professions_de_de'] = array(186=>'Bergbau', 182=>'Kräuterkunde', 164=>'Schmiedekunst', 165=>'Lederverarbeitung', 171=>'Alchimie', 333=>'Verzauberungskunst', 755=>'Juwelenschleifen', 202=>'Ingenieurskunst', 393=>'Kürschnerei', 197=>'Schneiderei', 773=>'Inschriftenkunde');
$DATA['professions_es_es'] = array(186=>'Minería', 182=>'Herboristería', 164=>'Herrería', 165=>'Peletería', 171=>'Alquimia', 333=>'Encantamiento', 755=>'Joyería', 202=>'Ingeniería', 393=>'Desollar', 197=>'Sastrería', 773=>'Inscripción');
$DATA['professions_es_mx'] = array(186=>'Minería', 182=>'Herboristería', 164=>'Herrería', 165=>'Peletería', 171=>'Alquimia', 333=>'Encantamiento', 755=>'Joyería', 202=>'Ingeniería', 393=>'Desollar', 197=>'Sastrería', 773=>'Inscripción');
$DATA['professions_fr_fr'] = array(186=>'Minage', 182=>'Herboristerie', 164=>'Forge', 165=>'Travail du cuir', 171=>'Alchimie', 333=>'Enchantement', 755=>'Joaillerie', 202=>'Ingénierie', 393=>'Dépeçage', 197=>'Couture', 773=>'Calligraphie');
$DATA['professions_ru_ru'] = array(186=>'Горное дело', 182=>'Травничество', 164=>'Кузнечное дело', 165=>'Кожевничество', 171=>'Алхимия', 333=>'Наложение чар', 755=>'Ювелирное дело', 202=>'Инженерное дело', 393=>'Снятие шкур', 197=>'Портняжное дело', 773=>'Начертание');
$DATA['professions_ko_kr'] = array(186=>'채광', 182=>'약초채집', 164=>'대장기술', 165=>'가죽세공', 171=>'연금술', 333=>'마법부여', 755=>'보석세공', 202=>'기계공학', 393=>'무두질', 197=>'재봉술', 773=>'주문각인');

//gender
$DATA['gender_en_gb'] = array(0=>'Male', 1=>'Female');
$DATA['gender_en_us'] = array(0=>'Male', 1=>'Female');
$DATA['gender_de_de'] = array(0=>'Männlich', 1=>'Weiblich');
$DATA['gender_es_es'] = array(0=>'Masculino', 1=>'Femenino');
$DATA['gender_es_mx'] = array(0=>'Masculino', 1=>'Femenino');
$DATA['gender_fr_fr'] = array(0=>'(M)', 1=>'(F)');
$DATA['gender_ru_ru'] = array(0=>'Мужчина', 1=>'Женщина');
$DATA['gender_ko_kr'] = array(0=>'남자', 1=>'여자');

//race-to-faction
$DATA['factions'] = array(1=>0, 2=>1, 3=>0, 4=>0, 5=>1, 6=>1, 7=>0, 8=>1, 10=>1, 11=>0);

//energy types (i.e. mana, energy, rage...)
$DATA['etypes'] = array(1=>'r', 2=>'m', 3=>'m', 4=>'e', 5=>'m', 6=>'p', 7=>'m', 8=>'m', 9=>'m', 11=>'m');

//talent-trees: class-to-tabinfo-ID connection
//don't change the order, it provides the info to which tree a spell belongs
$DATA['talentTrees'] = array(
		
		1 => array( /* arms */ 161, /* fury */ 164, /* protection */ 163),
		2 => array( /* holy */ 382, /* protection */ 383, /* retribution */ 381),
		3 => array( /* beast mastery */ 361, /* marksmanship */ 363, /* survival */ 362),
		4 => array( /* assassination */ 182, /* combat */ 181, /* subtlety */ 183),
		5 => array( /* discipline */ 201, /* holy */ 202, /* shadow */ 203),
		6 => array( /* blood */ 398, /* frost */ 399, /* unholy */ 400),
		7 => array( /* elemental combat */ 261, /* enhancement */ 263, /* restoration */ 262),
		8 => array( /* arcane */ 81, /* fire */ 41, /* frost */ 61),
		9 => array( /* affliction */ 302, /* demonology */ 303, /* destruction */ 301),
		11 => array( /* balance */ 283, /* feral combat */ 281, /* restoration */ 282)
);

//Spirit-based regeneration (Base_Regen is a variable dependent on character level and is summarized in the following table):
$DATA['spiritBaseRegen'] = array(
		
		1 =>	0.034965 , 	15=>	0.023345 ,	29=>	0.016581 ,	43=>	0.013363 ,	57=>	0.011342 ,	71=>	0.008859 ,
		2 =>	0.034191 ,	16=>	0.022748 ,	30=>	0.016233 ,	44=>	0.013175 ,	58=>	0.011245 ,	72=>	0.008415 ,
		3 =>	0.033465 ,	17=>	0.021958 ,	31=>	0.015994 ,	45=>	0.012996 ,	59=>	0.011110 ,	73=>	0.007993 ,
		4 =>	0.032526 ,	18=>	0.021386 ,	32=>	0.015707 ,	46=>	0.012853 ,	60=>	0.010999 ,	74=>	0.007592 ,
		5 =>	0.031661 ,	19=>	0.020790 ,	33=>	0.015464 ,	47=>	0.012687 ,	61=>	0.010700 ,	75=>	0.007211 ,
		6 =>	0.031076 ,	20=>	0.020121 ,	34=>	0.015204 ,	48=>	0.012539 ,	62=>	0.010522 ,	76=>	0.006849 ,
		7 =>	0.030523 ,	21=>	0.019733 ,	35=>	0.014956 ,	49=>	0.012384 ,	63=>	0.010290 ,	77=>	0.006506 ,
		8 =>	0.029994 ,	22=>	0.019155 ,	36=>	0.014744 ,	50=>	0.012233 ,	64=>	0.010119 ,	78=>	0.006179 ,
		9 =>	0.029307 ,	23=>	0.018819 ,	37=>	0.014495 ,	51=>	0.012113 ,	65=>	0.009968 ,	79=>	0.005869 ,
		10=>	0.028661 ,	24=>	0.018316 ,	38=>	0.014302 ,	52=>	0.011973 ,	66=>	0.009808 ,	80=>	0.005575 ,
		11=>	0.027584 ,	25=>	0.017936 ,	39=>	0.014094 ,	53=>	0.011859 ,	67=>	0.009651 ,
		12=>	0.026215 ,	26=>	0.017576 ,	40=>	0.013895 ,	54=>	0.011714 ,	68=>	0.009553 ,
		13=>	0.025381 ,	27=>	0.017201 ,	41=>	0.013724 ,	55=>	0.011575 ,	69=>	0.009445 ,
		14=>	0.024300 ,	28=>	0.016919 ,	42=>	0.013522 ,	56=>	0.011473 ,	70=>	0.009327
);

//stat-modifiers tags for xml-output
$DATA['mofifyerXMLTags'] = array(	0 => 'bonusMana',
									1 => 'bonusHealth',
									3 => 'bonusAgility',
									4 => 'bonusStrength',
									5 => 'bonusIntellect',
									6 => 'bonusSpirit',
									7 => 'bonusStamina',
									12 => "bonusDefenseSkillRating",
									13 => "bonusDodgeRating",
									14 => "bonusParryRating",
									15 => "bonusBlockRating", 
									16 => "bonusHitMeleeRating",
									17 => "bonusHitRangedRating",
									18 => "bonusHitSpellRating",
									19 => "bonusCritMeleeRating",
									20 => "bonusCritRangedRating",
									21 => "bonusCritSpellRating",
									22 => "bonusHitTakenMeleeRating",
									23 => "bonusHitTakenRangedRating",
									24 => "bonusHitTakenSpellRating",
									25 => "bonusCritTakenMeleeRating",
									26 => "bonusCritTakenRangedRating",
									27 => "bonusCritTakenSpellRating",
									28 => "bonusHasteMeleeRating",
									29 => "bonusHasteRangedRating",
									30 => "bonusHasteSpellRating",
									31 => "bonusHitRating",
									32 => "bonusCritRating" ,
									33 => "bonusHitTakenRating" ,
									34 => "bonusCritTakenRating" ,
									35 => "bonusResilienceRating",
									36 => "bonusHasteRating",
									37 => "bonusExpertiseRating",
									38 => "bonusAttackPower",
									39 => "bonusFeralAttackPower",
									43 => "bonusManaRegen",
									44 => "bonusArmorPenetration",
									45 => 'bonusSpellPower');

?>