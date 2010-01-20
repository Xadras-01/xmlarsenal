<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="/layout/includes.xsl" />
<xsl:import href="/layout/character/header.xsl" />
<xsl:output method="html" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"/>

<xsl:template match="characterInfo"><link rel="stylesheet" type="text/css" href="/css/character/sheet.css" />	
		
	<xsl:choose>
		<xsl:when test="@errCode">
			<div id="dataElement">
				<div class="parchment-top">
					<div class="parchment-content">
						<div class="list">
							<div class="player-side notab">
								<div class="info-pane">									
									<xsl:call-template name="errorSection" />									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>			
		</xsl:when>
		<xsl:otherwise>
			<div id="dataElement">
				<div class="parchment-top">
					<div class="parchment-content">
						<div class="list">					
							<xsl:variable name="theClassId" select="character/@classId" />
							<xsl:variable name="theRaceId" select="character/@raceId" />
							<xsl:variable name="theGenderId" select="character/@genderId" />
							<xsl:variable name="theFactionId" select="character/@factionId" />
							<xsl:variable name="charUrl" select="character/@charUrl" />
							<xsl:variable name="level" select="character/@level" />
							<xsl:variable name="lastModified" select="character/@lastModified" />
							<xsl:variable name="guildUrl" select="character/@guildUrl" />
							
							<script type="text/javascript">	
								var theClassId 		= <xsl:value-of select="$theClassId" />;
								var theRaceId 		= <xsl:value-of select="$theRaceId" />;
								var theClassName 	= "<xsl:value-of select="character/@class" />";
								var theLevel 		= <xsl:value-of select="$level" />;		
								var theRealmName 	= "<xsl:value-of select="character/@realm" />";
								var theCharName 	= "<xsl:value-of select="character/@name" />";		
						
								var talentsTreeArray = new Array;
										
								$(document).ready(function(){
								
									talentsTreeArray["group1"] = [];
									talentsTreeArray["group2"] = [];
									
									talentsTreeArray["group1"][0] = [1, <xsl:value-of select="characterTab/talentSpecs/talentSpec[@group='1']/@treeOne" />, 
																"<xsl:value-of select="$loc/strs/talents/str[@id=concat('armory.class', $theClassId, '.talents.tree.one')]"/>"];
									talentsTreeArray["group1"][1] = [2, <xsl:value-of select="characterTab/talentSpecs/talentSpec[@group='1']/@treeTwo" />, 
																"<xsl:value-of select="$loc/strs/talents/str[@id=concat('armory.class', $theClassId, '.talents.tree.two')]"/>"];
									talentsTreeArray["group1"][2] = [3, <xsl:value-of select="characterTab/talentSpecs/talentSpec[@group='1']/@treeThree" />, 
																"<xsl:value-of select="$loc/strs/talents/str[@id=concat('armory.class', $theClassId, '.talents.tree.three')]"/>"];
									
									talentsTreeArray["group2"][0] = [1, <xsl:value-of select="characterTab/talentSpecs/talentSpec[@group='2']/@treeOne" />, 
																"<xsl:value-of select="$loc/strs/talents/str[@id=concat('armory.class', $theClassId, '.talents.tree.one')]"/>"];
									talentsTreeArray["group2"][1] = [2, <xsl:value-of select="characterTab/talentSpecs/talentSpec[@group='2']/@treeTwo" />, 
																"<xsl:value-of select="$loc/strs/talents/str[@id=concat('armory.class', $theClassId, '.talents.tree.two')]"/>"];
									talentsTreeArray["group2"][2] = [3, <xsl:value-of select="characterTab/talentSpecs/talentSpec[@group='2']/@treeThree" />, 
																"<xsl:value-of select="$loc/strs/talents/str[@id=concat('armory.class', $theClassId, '.talents.tree.three')]"/>"];	
								
									calcTalentSpecs();
									
									setCharSheetUpgradeMenu();
								
								});	
							</script>		
			
							<xsl:call-template name="newCharTabs" />
							<div class="full-list">
								<div class="info-pane">
									<div class="profile-wrapper">
										<div class="profile">											
											<xsl:call-template name="charContent" />
										</div>
									</div>
								</div>
							</div>					
						</div>
					</div>
				</div>
			</div>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>


<xsl:template name="charContent">

	<!-- character header -->
	<xsl:call-template name="newCharHeader" />
	
	<xsl:variable name="cUrl" select="character/@charUrl" />
	<xsl:variable name="isPropass" select="character/@tournamentRealm = ''" />
	<xsl:variable name="theCharName" select="character/@name" />	
	<xsl:variable name="pathPropass" select="/page/characterInfo/character/arenaTeams/arenaTeam[@size=3]" />
	<xsl:variable name="pathPropassChar" select="/page/characterInfo/character/arenaTeams/arenaTeam[@size=3]/members/character[@name=$theCharName]" />
	
	<xsl:variable name="recUpgradeUrlPre">
		<xsl:call-template name="search-and-replace">
			<xsl:with-param name="input" select="$cUrl" />
			<xsl:with-param name="search-string" select="'r='" />
			<xsl:with-param name="replace-string" select="'pr='" />
		</xsl:call-template>
	</xsl:variable>
	
	<xsl:variable name="recUpgradeUrl">
		<xsl:call-template name="search-and-replace">
			<xsl:with-param name="input" select="$recUpgradeUrlPre" />
			<xsl:with-param name="search-string" select="'n='" />
			<xsl:with-param name="replace-string" select="'pn='" />
		</xsl:call-template>
	</xsl:variable>
	
	
	<!-- gear -->
	<div class="profile-master2">				
		<!-- left items -->
		<div class="leftItems">
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'0'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'1'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'2'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'14'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'4'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'3'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'18'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'8'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
		</div>
		<!-- right items -->
		<div class="rightItems">
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'9'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'5'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'6'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'7'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'10'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'11'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'12'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'13'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
		</div>
		<div class="bottomItems">
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'15'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'16'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>
			<xsl:call-template name="gearSlot"><xsl:with-param name="slotNum" select="'17'" /><xsl:with-param name="recUrl" select="$recUpgradeUrl" /></xsl:call-template>		
		</div>
		
		<div class="profileCenter">
			<div class="statsLeft">
				<!-- talent spec -->
				<div class="brownBox" style="height: 120px;">			
					<em class="ptl"><xsl:comment/></em><em class="ptr"><xsl:comment/></em><em class="pbl"><xsl:comment/></em><em class="pbr"><xsl:comment/></em>
					<div class="spec">
						<h4><xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character.talent-specialization']"/></h4>
						
						<xsl:variable name="groupOne" select="characterTab/talentSpecs/talentSpec[@group='1']" />
						<xsl:variable name="groupTwo" select="characterTab/talentSpecs/talentSpec[@group='2']" />
						
						<div class="spec-wrapper disabledSpec">
							<xsl:if test="$groupOne[@active='1']">
								<xsl:attribute name="class">spec-wrapper</xsl:attribute>
								<xsl:if test="not($lang = 'ko_kr' or $lang = 'zh_cn' or $lang = 'zh_tw')">
									<div class="activeSpecTxt"><xsl:value-of select="$loc/strs/unsorted/str[@id='activeSpecTxt']" /></div>
								</xsl:if>
							</xsl:if>
							<div style="position:absolute; left:12px;"><img id="talentSpecImage" src="/images/icons/class/talents/untalented.gif" /></div>
							<h4><a id="replaceTalentSpecText" href="character-talents.xml?{$cUrl}&amp;group=1"><xsl:value-of select="$groupOne/@prim" /></a></h4>
							<span><xsl:value-of select="$groupOne/@treeOne" /> / <xsl:value-of select="$groupOne/@treeTwo" /> / <xsl:value-of select="$groupOne/@treeThree" /></span></div>
						<div class="spec-wrapper disabledSpec">
							<xsl:if test="$groupTwo[@active='1']">
								<xsl:attribute name="class">spec-wrapper</xsl:attribute>
								<xsl:if test="not($lang = 'ko_kr' or $lang = 'zh_cn' or $lang = 'zh_tw')">
									<div class="activeSpecTxt"><xsl:value-of select="$loc/strs/unsorted/str[@id='activeSpecTxt']" /></div>
								</xsl:if>
							</xsl:if>								
							<xsl:choose>
								<xsl:when test="$groupTwo">
									<div style="position:absolute; left:12px;"><img id="talentSpecImage2" src="/images/icons/class/talents/untalented.gif" /></div>
									<h4><a id="replaceTalentSpecText2" href="character-talents.xml?{$cUrl}&amp;group=2"><xsl:value-of select="$groupTwo/@prim" /></a></h4>								
									<span><xsl:value-of select="$groupTwo/@treeOne" /> / <xsl:value-of select="$groupTwo/@treeTwo" /> / <xsl:value-of select="$groupTwo/@treeThree" /></span>
								</xsl:when>
								<xsl:otherwise>
									<div style="position:absolute; left:12px;"><img src="/images/icons/professions/none-sm.gif" /></div>
									<h4 style="text-transform:lowercase;"><a id="replaceTalentSpecText2" class="staticTip" onmouseover="setTipText('{$loc/strs/unsorted/str[@id='notApplicable.tooltip']}');" href="javascript:void(0)"><xsl:value-of select="$loc/strs/unsorted/str[@id='notApplicable']" /></a></h4>
									<span>0 / 0 / 0</span>
								</xsl:otherwise>
							</xsl:choose>
						</div>
					</div>
					
					<div class="clear"></div>
				</div>			
				
				<!-- PROFESSIONS or pvp info -->
				<div class="brownBox" style="margin-top: 2px; height: 70px;">
					<em class="ptl"><xsl:comment/></em><em class="ptr"><xsl:comment/></em><em class="pbl"><xsl:comment/></em><em class="pbr"><xsl:comment/></em>								
				
					<!-- pro pass chars -->
					<xsl:choose>
						<xsl:when test="$isPropass='true'">						
							<h4><xsl:value-of select="$loc/strs/propass/str[@id='pvpinfo']"/></h4>						
							<div class="tooltipContentSpecial" style="padding-left: 10px; padding-top: 4px;">
								<span style="font-size: 12px; font-weight: bold;"><xsl:value-of select="$loc/strs/propass/str[@id='personalrating']"/>&#160;</span>
								<span style="color:#FFFFFF; font-size: 14px;">
									<xsl:choose>
										<xsl:when test="$pathPropassChar"><xsl:value-of select="$pathPropassChar/@contribution" /></xsl:when>
										<xsl:otherwise><span style="font-size: 11px;">N/A</span></xsl:otherwise></xsl:choose>
								</span><br />
								
								<span style="font-size: 10px;"><xsl:value-of select="$loc/strs/propass/str[@id='winrecord']"/>&#160;</span>
								<xsl:choose><xsl:when test="$pathPropassChar"><span style="color:#FFFFFF; "><span style="color:#00CC00"><xsl:value-of select="$pathPropassChar/@seasonGamesWon" /></span> - <span style="color:#FF0000 "><xsl:value-of select="$pathPropassChar/@seasonGamesPlayed - $pathPropassChar/@seasonGamesWon" /></span></span></xsl:when><xsl:otherwise><span style="color:#FFFFFF">N/A</span></xsl:otherwise></xsl:choose> <br />
								<span style="font-size: 10px;"><xsl:value-of select="$loc/strs/propass/str[@id='winpercentage']"/>&#160;</span>
								<span style="color:#FFFFFF; ">
									<xsl:choose>
										<xsl:when test="not ($pathPropassChar)"><span style="color:#FFFFFF">N/A</span></xsl:when>
										<xsl:when test="@seasonGamesPlayed=0">0&#37;</xsl:when>
										<xsl:otherwise><xsl:value-of select="round($pathPropassChar/@seasonGamesWon div $pathPropassChar/@seasonGamesPlayed * 100)" />&#37;</xsl:otherwise>
									</xsl:choose>
								</span>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<h4><xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character.professions-primary']"/></h4>						
							<!-- loop through professions -->
							<xsl:for-each select="characterTab/professions/skill">
								<xsl:variable name="profValue" select="@value" />
								<xsl:variable name="profMax" select="@max" />
								
								<div class="prof1" style="background:url('/images/icons/professions/{@key}-sm.gif') 0 50% no-repeat;">
									<div class="bar-container staticTip" onmouseover="setTipText('{@name}')">										
										<b><xsl:attribute name="style"> width: <xsl:choose><xsl:when test="$profValue &lt; $profMax"><xsl:value-of select="number(100 * $profValue div $profMax)" /></xsl:when><xsl:otherwise>100</xsl:otherwise></xsl:choose>%</xsl:attribute>
										<xsl:value-of select="@value" /> / <xsl:value-of select="@max" /></b></div>
								</div>
							</xsl:for-each>
							
							<!-- show blank professions if there are none or only 1 -->
							<xsl:if test="count(characterTab/professions/skill)=0">
								<div class="prof1" style="background:url('/images/icons/professions/none-sm.gif') 0 0 no-repeat;">
									<div class="bar-container staticTip" onmouseover="setTipText('{$loc/strs/common/str[@id='armory.none']}')">										
										<b style="width: 0%"></b>
										<xsl:value-of select="$loc/strs/general/str[@id='armory.labels.na']"/></div>
								</div>
							</xsl:if>														
							<xsl:if test="count(characterTab/professions/skill)=1 or count(characterTab/professions/skill)=0">
								<div class="prof1" style="background:url('/images/icons/professions/none-sm.gif') 0 0 no-repeat;">									
									<div class="bar-container staticTip" onmouseover="setTipText('{$loc/strs/common/str[@id='armory.none']}')">										
										<b style="width: 0%"></b>
										<xsl:value-of select="$loc/strs/general/str[@id='armory.labels.na']"/></div>
								</div>
							</xsl:if>
						</xsl:otherwise>
					</xsl:choose>
					<div class="clear"></div>
				</div>
				
				<!-- health and energy / rage, etc -->
				<div class="brownBox hp_mana_stats">
					<em class="ptl"><xsl:comment/></em><em class="ptr"><xsl:comment/></em><em class="pbl"><xsl:comment/></em><em class="pbr"><xsl:comment/></em>
					<div class="health-stat">
						<p><span><xsl:value-of select="$loc/strs/character/str[@id='character.health']" />&#160;&#160;<xsl:value-of select="characterTab/characterBars/health/@effective" /></span></p>
					</div>
					<div>
						<xsl:variable name="theSecondBar" select="characterTab/characterBars/secondBar/@type" />
						<xsl:attribute name="class">
							<xsl:choose>
								<xsl:when test="$theSecondBar='r'">rage-stat</xsl:when>
								<xsl:when test="$theSecondBar='e'">energy-stat</xsl:when>
								<xsl:when test="$theSecondBar='p'">runic-stat</xsl:when>
								<xsl:otherwise>mana-stat</xsl:otherwise>
							</xsl:choose>
						</xsl:attribute>
						<!-- choose whether to display mana text, rage text, etc -->
						<xsl:variable name="secondBarTtxt">
							<xsl:choose>
								<xsl:when test="$theSecondBar='r'">
									<xsl:value-of select="$loc/strs/character/str[@id='character.rage']"/>
								</xsl:when>
								<xsl:when test="$theSecondBar='e'">
									<xsl:value-of select="$loc/strs/character/str[@id='character.energy']"/>
								</xsl:when>
								<xsl:when test="$theSecondBar='p'">
									<xsl:value-of select="$loc/strs/character/str[@id='character.runicpower']"/>
								</xsl:when>
								<xsl:otherwise>
									<xsl:value-of select="$loc/strs/character/str[@id='character.mana']"/>
								</xsl:otherwise>
							</xsl:choose>
						</xsl:variable>

						<p><span><xsl:value-of select="$secondBarTtxt" />&#160;&#160;<xsl:value-of select="characterTab/characterBars/secondBar/@effective" /></span></p>
					</div>
					<div class="clear"></div>
				</div>
					
			</div>
			<div class="achRight">
				<em class="ptl"><xsl:comment/></em><em class="ptr"><xsl:comment/></em><em class="pbl"><xsl:comment/></em><em class="pbr"><xsl:comment/></em>
				<h4><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.faq.achievements']"/></h4>
				<a class="achPointsLink" href="/character-achievements.xml?{$cUrl}">
					<span style="color: #FFF; float: right; margin: 0 0px 0 0;"><xsl:value-of select="summary/c/@points" /></span>
					<xsl:value-of select="$loc/strs/achievements/str[@id='points']"/>					
				</a>
				
				<div class="achieve-bar" style="text-align:center;">					
					<div class="progressTxt">&#160;&#160;<xsl:value-of select="$loc/strs/achievements/str[@id='progress']"/>&#160;<xsl:value-of select="summary/c/@earned" />/<xsl:value-of select="summary/c/@total" /></div>
					<b style="width: {(summary/c/@earned div summary/c/@total) * 100}%;"></b>
				</div>
						
				<xsl:for-each select="summary/category">
					<div class="achList">
						<span>
							<xsl:choose>
								<xsl:when test="not(c/@earnedPoints)">
									<xsl:value-of select="c/@earned" />
								</xsl:when>
								<xsl:otherwise>
									<xsl:value-of select="c/@earned" />/<xsl:value-of select="c/@total" />
								</xsl:otherwise>
							</xsl:choose>
						</span>
						<xsl:value-of select="@name" /></div>
				</xsl:for-each>					
			</div>
			<xsl:call-template name="statsDropDownMenu" />			
		</div>
		
	</div>
	<div style="width: 500px; margin: 5px auto 20px;">
		<xsl:if test="characterTab/pvp/lifetimehonorablekills/@value &gt; 1337">
		<span style="float: right">
			<xsl:value-of select="$loc/strs/unsorted/str[@id='armory.labels.hks']" />
			<strong><xsl:value-of select="characterTab/pvp/lifetimehonorablekills/@value" /></strong>
		</span>
		</xsl:if>
		<xsl:value-of select="$loc/strs/general/str[@id='armory.character.lastModified']" />&#160;
		<strong><xsl:value-of select="character/@lastModified" /></strong>
	</div>
		
	<!-- arena/pvp information -->
	<xsl:if test="count(character/arenaTeams/arenaTeam) &gt; 0">
		<div class="bonus-stats">
			<table class="deco-frame">
				<thead><tr><td class="sl"></td><td class="ct st"></td><td class="sr"></td></tr></thead>
				<tbody><tr>
					<td class="sl"><b><em class="port"></em></b></td>
					<td class="ct"><xsl:call-template name="pvpAndArenaInfo" /></td>
				<td class="sr"><b><em class="star"></em></b></td>				
				</tr></tbody>
				<tfoot><tr><td class="sl"></td><td class="ct sb" align="center"></td><td class="sr"></td></tr></tfoot>
			</table>
		</div>
		<div class="clear"></div>
		<br /><br />
	</xsl:if>
</xsl:template>

<!-- prints a gear slot icon on the character sheet -->
<xsl:template name="gearSlot">
	<xsl:param name="slotNum" />
	<xsl:param name="recUrl" />
	<xsl:variable name="slotInfo" select="characterTab/items/item[@slot=$slotNum]" />		
	<div class="gearItem">
		<xsl:choose>
			<xsl:when test="$slotInfo">
				<xsl:attribute name="style">
					background-image: url('/wow-icons/_images/51x51/<xsl:value-of select="$slotInfo/@icon"/>.jpg')
				</xsl:attribute>
			</xsl:when>
			<xsl:otherwise>
				<!-- show relic icon for druids/pallys/shaman/dk -->
				<xsl:if test="$slotNum = '17' and (character/@classId = '11' or character/@classId = '2' or character/@classId = '7' or character/@classId = '6')">
					<xsl:attribute name="style">background: url('/images/relic.jpg') no-repeat 2px 4px</xsl:attribute>
				</xsl:if>
			</xsl:otherwise>
		</xsl:choose>
		<a id="i={$slotInfo/@id}&amp;{character/@charUrl}&amp;s={$slotNum}" href="http://eu.wowarmory.com/item-info.xml?i={$slotInfo/@id}" class="staticTip itemToolTip gearFrame">		
			<xsl:if test="not($slotInfo) and not($slotNum = '3' or $slotNum = '18')">
				<xsl:attribute name="href">javascript:void(0)</xsl:attribute>
				<xsl:attribute name="onmouseover">setTipText(getEmptySlotToolTip('<xsl:value-of select="$slotNum" />','<xsl:value-of select="character/@classId" />'))</xsl:attribute>
				<xsl:attribute name="class">staticTip gearFrame</xsl:attribute>
			</xsl:if>			
			<!-- tabard and shirt dont have upgrades -->
			<xsl:choose>
				<xsl:when test="not($slotInfo)">
					<xsl:attribute name="class">staticTip noUpgrade gearFrame</xsl:attribute>
					<xsl:attribute name="onmouseover">setTipText(getEmptySlotToolTip('<xsl:value-of select="$slotNum" />','<xsl:value-of select="character/@classId" />'))</xsl:attribute>					
				</xsl:when>
				<xsl:when test="$slotInfo and ($slotNum = '3' or $slotNum = '18')">
					<xsl:attribute name="class">staticTip noUpgrade itemToolTip gearFrame</xsl:attribute>
				</xsl:when>
				<xsl:otherwise>
					<div class="upgradeBox"> </div>
				</xsl:otherwise>		
			</xsl:choose>				
		</a>
		<xsl:if test="$slotInfo and not($slotNum = '3' or $slotNum = '18')">
			<div class="fly-horz">
				<a class="upgrd" style="font-size:90%;line-height:120%" href="http://eu.wowarmory.com/search.xml?searchType=items&amp;pi={$slotInfo/@id}">
					<xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character-sheet.findupgrade']" />
				</a>
			</div>
		</xsl:if>
	</div>
</xsl:template>



<xsl:template name="pvpAndArenaInfo">
	<!-- arena info -->	
	<div class="arena-ranking">
		<h2><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.labels.header.arena']"/></h2>
	</div>
	
	<xsl:variable name="charName" select="character/@name" />
	<xsl:variable name="path2v2" select="character/arenaTeams/arenaTeam[@size='2']" />
	<xsl:variable name="path3v3" select="character/arenaTeams/arenaTeam[@size='3']" />
	<xsl:variable name="path5v5" select="character/arenaTeams/arenaTeam[@size='5']" />
	
	<ul class="badges-pvp">								
		<li><xsl:call-template name="singleArenaBadge"><xsl:with-param name="teamSize" select="'2'" /></xsl:call-template></li>
		<li><xsl:call-template name="singleArenaBadge"><xsl:with-param name="teamSize" select="'3'" /></xsl:call-template></li>
		<li><xsl:call-template name="singleArenaBadge"><xsl:with-param name="teamSize" select="'5'" /></xsl:call-template></li>
	</ul>
							
	<ul class="badges-pvp personalrating">
		<li><div><em><span>
			<xsl:if test="$path2v2">
				<b><xsl:value-of select="$path2v2/@name" /></b><br />
				<xsl:value-of select="$loc/strs/unsorted/str[@id='armory.labels.personalratingcolon']"/>
				<xsl:value-of select="$path2v2/members/character[@name=$charName]/@contribution" /><br />
			</xsl:if>
		</span></em></div></li>
		<li><div><em><span>
			<xsl:if test="$path3v3">
				<b><xsl:value-of select="$path3v3/@name" /></b><br />
				<xsl:value-of select="$loc/strs/unsorted/str[@id='armory.labels.personalratingcolon']"/>
				<xsl:value-of select="$path3v3/members/character[@name=$charName]/@contribution" /><br />
			</xsl:if>
		</span></em></div></li>
		<li><div><em><span>
			<xsl:if test="$path5v5">
				<b><xsl:value-of select="$path5v5/@name" /></b><br />
				<xsl:value-of select="$loc/strs/unsorted/str[@id='armory.labels.personalratingcolon']"/>
				<xsl:value-of select="$path5v5/members/character[@name=$charName]/@contribution" /><br />					
			</xsl:if>
		</span></em></div></li>
	</ul>		

</xsl:template>

<!-- single arena badge (prints icon and rating) -->
<xsl:template name="singleArenaBadge">
	<xsl:param name="teamSize" />	
	
	<xsl:variable name="teamNode" select="character/arenaTeams/arenaTeam[@size=$teamSize]" />
	<xsl:variable name="teamRank" select="$teamNode/@ranking" />


	<xsl:choose>
		<xsl:when test="$teamNode">
			<div class="arenacontainer">
				<h4><xsl:value-of select="$teamSize" /><xsl:value-of select="$loc/strs/arenaReport/str[@id='versus']" /><xsl:value-of select="$teamSize" /></h4>
				<em><span><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.labels.ratingcolon']" /><xsl:value-of select="$teamNode/@rating" /></span></em>
				
				<div class="icon" onclick="window.location='/team-info.xml?{$teamNode/@teamUrl}'">
					<xsl:choose>
						<xsl:when test="$teamRank &gt; '500'">								
							<xsl:attribute name="style">background-image: url('/images/icons/badges/arena/arena-5.jpg'); cursor: pointer;</xsl:attribute>
							<img src="/images/badge-border-pvp-bronze.gif" border="0" />
						</xsl:when>
						<xsl:when test="$teamRank &gt; '100'">								
							<xsl:attribute name="style">background-image: url('/images/icons/badges/arena/arena-4.jpg'); cursor: pointer;</xsl:attribute>
							<img src="/images/badge-border-pvp-bronze-large.gif" border="0" />
						</xsl:when>
						<xsl:when test="$teamRank &gt; '10'">								
							<xsl:attribute name="style">background-image: url('/images/icons/badges/arena/arena-3.jpg'); cursor: pointer;</xsl:attribute>
							<img src="/images/badge-border-pvp-silver.gif" border="0" />
						</xsl:when>
						<xsl:when test="$teamRank &gt; '1'">
							<xsl:attribute name="style">background-image: url('/images/icons/badges/arena/arena-2.jpg'); cursor: pointer;</xsl:attribute>
							<img src="/images/badge-border-pvp-gold.gif" border="0" />
						</xsl:when>
						<xsl:when test="$teamRank = '1'">								
							<xsl:attribute name="style">background-image: url('/images/icons/badges/arena/arena-1.jpg'); cursor: pointer;</xsl:attribute>
							<img src="/images/badge-border-pvp-gold-large.gif" border="0" />
						</xsl:when>
						<xsl:otherwise>								
							<xsl:attribute name="style">background-image: url('/images/icons/badges/arena/arena-6.jpg'); cursor: pointer;</xsl:attribute>	
							<img src="/images/badge-border-pvp-parchment.gif" border="0" />
						</xsl:otherwise>
					</xsl:choose>
					
					<xsl:variable name="standingTxt">						
						<xsl:if test="$teamRank != 0 and $teamRank &lt;= 1000">
							<xsl:value-of select="$teamRank" />
							<xsl:call-template name="positionSuffix"><xsl:with-param name="pos" select="$teamRank"/></xsl:call-template>
						</xsl:if>
					</xsl:variable>
						
					<div class="rank-num" id="arenarank2_{$teamSize}">
						<xsl:call-template name="flash">
							<xsl:with-param name="id" select="concat('arenarank2_',$teamSize)"/>
							<xsl:with-param name="src" select="'/images/rank.swf'"/>
							<xsl:with-param name="wmode" select="'transparent'"/>
							<xsl:with-param name="width" select="'100'"/>
							<xsl:with-param name="height" select="'40'"/>
							<xsl:with-param name="quality" select="'best'"/>
							<xsl:with-param name="flashvars" select="concat('rankNum=', $standingTxt)"/>
							<xsl:with-param name="noflash" select="concat('&lt;div class=teamicon-noflash&gt;&lt;a target=_blank href=http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash&gt;&lt;img src=images/',$lang,'/getflash.png class=p/&gt;&lt;/a&gt;&lt;/div&gt;')"/>
						</xsl:call-template>
					</div>
					
				</div>				
			</div>
		</xsl:when>
		<xsl:otherwise>
			<!-- no arena team of this size -->
			<div class="arena-team-faded">
				<h4><xsl:value-of select="$teamSize" /><xsl:value-of select="$loc/strs/arenaReport/str[@id='versus']" /><xsl:value-of select="$teamSize" /></h4>
				<em><span><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.labels.noteam']"/></span></em>
			</div>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>



<xsl:template name="statsDropDownMenu">	

	<!-- drop down menus: left -->
	<xsl:call-template name="dropdownMenu">
		<xsl:with-param name="hiddenId" select="'Left'"/>
		<xsl:with-param name="defaultValue" select="$loc/strs/characterSheet/str[@id='armory.character-sheet.base-stats.display']" />
		<xsl:with-param name="divClass" select="'dropdown1'" />
		<xsl:with-param name="anchorClass" select="'profile-stats'" />
	</xsl:call-template>
	
	<div class="drop-stats" style="display: none; z-index: 99999;" id="dropdownHiddenLeft" onMouseOver="javascript: varOverLeft=1;" onMouseOut="javascript: varOverLeft=0;">
		<div class="tooltip">
		<table width="98%"><tr><td class="tl" /><td class="t" /><td class="tr" /></tr><tr><td class="l" /><td class="bg">
			<ul>
				<li>
					<a href="#" onClick="changeStats('Left', replaceStringBaseStats, 'BaseStats', baseStatsDisplay); return false;">
						<xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character-sheet.base-stats.display']"/>
						<img id="checkLeftBaseStats" src="/images/icons/icon-check.gif" style="visibility: visible;" class="checkmark" /></a></li>
				<li>
					<a href="#" onClick="changeStats('Left', replaceStringMelee, 'Melee', meleeDisplay); return false;">
						<xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character-sheet.melee.display']"/>
						<img id="checkLeftMelee" src="/images/icons/icon-check.gif" style="visibility: hidden;" class="checkmark" /></a></li>
				<li>
					<a href="#" onClick="changeStats('Left', replaceStringRanged, 'Ranged', rangedDisplay); return false;">
					<xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character-sheet.ranged.display']"/>
					<img id="checkLeftRanged" src="/images/icons/icon-check.gif" style="visibility: hidden;" class="checkmark" /></a></li>
				<li>
					<a href="#" onClick="changeStats('Left', replaceStringSpell, 'Spell', spellDisplay); return false;">
					<xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character-sheet.spell.display']"/>
					<img id="checkLeftSpell" src="/images/icons/icon-check.gif" style="visibility: hidden;" class="checkmark" /></a></li>
				<li>
					<a href="#" onClick="changeStats('Left', replaceStringDefenses, 'Defenses', defensesDisplay); return false;">
					<xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character-sheet.defenses.display']"/>
					<img id="checkLeftDefenses" src="/images/icons/icon-check.gif" style="visibility: hidden;" class="checkmark" /></a></li>
			</ul>
		</td><td class="r" /></tr><tr><td class="bl" /><td class="b" /><td class="br" /></tr></table>
		</div>
	</div>

	<!-- drop down menus: right -->
	<xsl:call-template name="dropdownMenu">
		<xsl:with-param name="hiddenId" select="'Right'"/>
		<xsl:with-param name="defaultValue" select="$loc/strs/characterSheet/str[@id='armory.character-sheet.base-stats.display']" />
		<xsl:with-param name="divClass" select="'dropdown2'" />
		<xsl:with-param name="anchorClass" select="'profile-stats'" />
	</xsl:call-template>

	<div class="drop-stats" style="display: none; z-index: 9999999; left: 190px;" id="dropdownHiddenRight" onMouseOver="javascript: varOverRight=1;" onMouseOut="javascript: varOverRight=0;">
		<div class="tooltip">
			<table width="98%"><tr><td class="tl" /><td class="t" /><td class="tr" /></tr><tr><td class="l" /><td class="bg">
				<ul>
					<li><a href="#" onClick="changeStats('Right', replaceStringBaseStats, 'BaseStats', baseStatsDisplay); return false;"><xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character-sheet.base-stats.display']"/><img id="checkRightBaseStats" src="/images/icons/icon-check.gif" style="visibility: hidden;" class="checkmark" /></a></li>
					<li><a href="#" onClick="changeStats('Right', replaceStringMelee, 'Melee', meleeDisplay); return false;"><xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character-sheet.melee.display']"/><img id="checkRightMelee" src="/images/icons/icon-check.gif" style="visibility: hidden;" class="checkmark" /></a></li>
					<li><a href="#" onClick="changeStats('Right', replaceStringRanged, 'Ranged', rangedDisplay); return false;"><xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character-sheet.ranged.display']"/><img id="checkRightRanged" src="/images/icons/icon-check.gif" style="visibility: hidden;" class="checkmark" /></a></li>
					<li><a href="#" onClick="changeStats('Right', replaceStringSpell, 'Spell', spellDisplay); return false;"><xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character-sheet.spell.display']"/><img id="checkRightSpell" src="/images/icons/icon-check.gif" style="visibility: hidden;" class="checkmark" /></a></li>
					<li><a href="#" onClick="changeStats('Right', replaceStringDefenses, 'Defenses', defensesDisplay); return false;"><xsl:value-of select="$loc/strs/characterSheet/str[@id='armory.character-sheet.defenses.display']"/><img id="checkRightDefenses" src="/images/icons/icon-check.gif" style="visibility: hidden;" class="checkmark" /></a></li>
				</ul>
			</td><td class="r" /></tr><tr><td class="bl" /><td class="b" /><td class="br" /></tr></table>
		</div>
	</div>

	<div class="stats1">
		<em class="ptl"><xsl:comment/></em><em class="ptr"><xsl:comment/></em><em class="pbl"><xsl:comment/></em><em class="pbr"><xsl:comment/></em>
		<div class="character-stats">
			<div id="replaceStatsLeft"></div>		
		</div>
	</div>

	<div class="stats2">
		<em class="ptl"><xsl:comment/></em><em class="ptr"><xsl:comment/></em><em class="pbl"><xsl:comment/></em><em class="pbr"><xsl:comment/></em>
		<div class="character-stats">
			<div id="replaceStatsRight"></div>	
		</div>
	</div>


	<xsl:variable name="resistArcaneValue" select="characterTab/resistances/arcane/@value" />
	<xsl:variable name="resistFireValue" select="characterTab/resistances/fire/@value" />
	<xsl:variable name="resistNatureValue" select="characterTab/resistances/nature/@value" />
	<xsl:variable name="resistShadowValue" select="characterTab/resistances/shadow/@value" />
	<xsl:variable name="resistFrostValue" select="characterTab/resistances/frost/@value" />

	
	<script type="text/javascript" src="/js/character/functions.js"></script>
	
	<script type="text/javascript">
	
		function strengthObject() {
			this.base="<xsl:value-of select="characterTab/baseStats/strength/@base" />";
			this.effective="<xsl:value-of select="characterTab/baseStats/strength/@effective" />";
			this.block="<xsl:value-of select="characterTab/baseStats/strength/@block" />";
			this.attack="<xsl:value-of select="characterTab/baseStats/strength/@attack" />";
		
			this.diff=this.effective - this.base;
		}
		
		function agilityObject() {
			this.base="<xsl:value-of select="characterTab/baseStats/agility/@base" />";
			this.effective="<xsl:value-of select="characterTab/baseStats/agility/@effective" />";
			this.critHitPercent="<xsl:value-of select="characterTab/baseStats/agility/@critHitPercent" />";
			this.attack="<xsl:value-of select="characterTab/baseStats/agility/@attack" />";
			this.armor="<xsl:value-of select="characterTab/baseStats/agility/@armor" />";
		
			this.diff=this.effective - this.base;
		}
		
		function staminaObject(base, effective, health, petBonus) {
			this.base="<xsl:value-of select="characterTab/baseStats/stamina/@base" />";
			this.effective="<xsl:value-of select="characterTab/baseStats/stamina/@effective" />";
			this.health="<xsl:value-of select="characterTab/baseStats/stamina/@health" />";
			this.petBonus="<xsl:value-of select="characterTab/baseStats/stamina/@petBonus" />";
		
			this.diff=this.effective - this.base;
		}
		
		function intellectObject() {
			this.base="<xsl:value-of select="characterTab/baseStats/intellect/@base" />";
			this.effective="<xsl:value-of select="characterTab/baseStats/intellect/@effective" />";
			this.mana="<xsl:value-of select="characterTab/baseStats/intellect/@mana" />";
			this.critHitPercent="<xsl:value-of select="characterTab/baseStats/intellect/@critHitPercent" />";
			this.petBonus="<xsl:value-of select="characterTab/baseStats/intellect/@petBonus" />";
		
			this.diff=this.effective - this.base;
		}
		
		function spiritObject() {
			this.base="<xsl:value-of select="characterTab/baseStats/spirit/@base" />";
			this.effective="<xsl:value-of select="characterTab/baseStats/spirit/@effective" />";
			this.healthRegen="<xsl:value-of select="characterTab/baseStats/spirit/@healthRegen" />";
			this.manaRegen="<xsl:value-of select="characterTab/baseStats/spirit/@manaRegen" />";
		
			this.diff=this.effective - this.base;
		}
		
		function armorObject() {
			this.base="<xsl:value-of select="characterTab/baseStats/armor/@base" />";
			this.effective="<xsl:value-of select="characterTab/baseStats/armor/@effective" />";
			this.reductionPercent="<xsl:value-of select="characterTab/baseStats/armor/@percent" />";
			this.petBonus="<xsl:value-of select="characterTab/baseStats/armor/@petBonus" />";
		
			this.diff=this.effective - this.base;
		}
		
		function resistancesObject() {
			this.arcane=new resistArcaneObject("<xsl:value-of select="$resistArcaneValue" />", "<xsl:value-of select="characterTab/resistances/arcane/@petBonus" />");
			this.nature=new resistNatureObject("<xsl:value-of select="$resistNatureValue" />", "<xsl:value-of select="characterTab/resistances/nature/@petBonus" />");
			this.fire=new resistFireObject("<xsl:value-of select="$resistFireValue" />", "<xsl:value-of select="characterTab/resistances/fire/@petBonus" />");
			this.frost=new resistFrostObject("<xsl:value-of select="$resistFrostValue" />", "<xsl:value-of select="characterTab/resistances/frost/@petBonus" />");
			this.shadow=new resistShadowObject("<xsl:value-of select="$resistShadowValue" />", "<xsl:value-of select="characterTab/resistances/shadow/@petBonus" />");
		}
		
		function meleeMainHandWeaponSkillObject() {
			this.value="<xsl:value-of select="characterTab/melee/expertise/@value" />";
			this.rating="<xsl:value-of select="characterTab/melee/expertise/@rating" />";
			this.additional="<xsl:value-of select="characterTab/melee/expertise/@additional" />";
			this.percent="<xsl:value-of select="characterTab/melee/expertise/@percent" />";
		}
		
		function meleeOffHandWeaponSkillObject() {
			this.value="<xsl:value-of select="characterTab/melee/offHandWeaponSkill/@value" />";
			this.rating="<xsl:value-of select="characterTab/melee/offHandWeaponSkill/@rating" />";
		}	
		
		function meleeMainHandDamageObject() {
			this.speed="<xsl:value-of select="characterTab/melee/mainHandDamage/@speed" />";
			this.min="<xsl:value-of select="characterTab/melee/mainHandDamage/@min" />";
			this.max="<xsl:value-of select="characterTab/melee/mainHandDamage/@max" />";
			this.percent="<xsl:value-of select="characterTab/melee/mainHandDamage/@percent" />";
			this.dps="<xsl:value-of select="characterTab/melee/mainHandDamage/@dps" />";
		
			if (this.percent &gt; 0)		this.effectiveColor="class='mod'";
			else if (this.percent &lt; 0)	this.effectiveColor="class='moddown'";
		}
		
		function meleeOffHandDamageObject() {
			this.speed="<xsl:value-of select="characterTab/melee/offHandDamage/@speed" />";
			this.min="<xsl:value-of select="characterTab/melee/offHandDamage/@min" />";
			this.max="<xsl:value-of select="characterTab/melee/offHandDamage/@max" />";
			this.percent="<xsl:value-of select="characterTab/melee/offHandDamage/@percent" />";
			this.dps="<xsl:value-of select="characterTab/melee/offHandDamage/@dps" />";
		}
		
		
		function meleeMainHandSpeedObject() {
			this.value="<xsl:value-of select="characterTab/melee/mainHandSpeed/@value" />";
			this.hasteRating="<xsl:value-of select="characterTab/melee/mainHandSpeed/@hasteRating" />";
			this.hastePercent="<xsl:value-of select="characterTab/melee/mainHandSpeed/@hastePercent" />";
		}
		
		function meleeOffHandSpeedObject() {
			this.value="<xsl:value-of select="characterTab/melee/offHandSpeed/@value" />";
			this.hasteRating="<xsl:value-of select="characterTab/melee/offHandSpeed/@hasteRating" />";
			this.hastePercent="<xsl:value-of select="characterTab/melee/offHandSpeed/@hastePercent" />";
		}
		
		function meleePowerObject() {
			this.base="<xsl:value-of select="characterTab/melee/power/@base" />";
			this.effective="<xsl:value-of select="characterTab/melee/power/@effective" />";
			this.increasedDps="<xsl:value-of select="characterTab/melee/power/@increasedDps" />";
		
			this.diff=this.effective - this.base;
		}
		
		function meleeHitRatingObject() {
			this.value="<xsl:value-of select="characterTab/melee/hitRating/@value" />";
			this.increasedHitPercent="<xsl:value-of select="characterTab/melee/hitRating/@increasedHitPercent" />";
			this.armorPenetration="<xsl:value-of select="characterTab/melee/hitRating/@penetration" />";	
			this.reducedArmorPercent="<xsl:value-of select="characterTab/melee/hitRating/@reducedArmorPercent" />";
		}
		
		function meleeCritChanceObject() {
			this.percent="<xsl:value-of select="characterTab/melee/critChance/@percent" />";
			this.rating="<xsl:value-of select="characterTab/melee/critChance/@rating" />";
			this.plusPercent="<xsl:value-of select="characterTab/melee/critChance/@plusPercent" />";
		}
		
		function rangedWeaponSkillObject() {
			this.value=<xsl:value-of select="characterTab/ranged/weaponSkill/@value" />;
			this.rating=<xsl:value-of select="characterTab/ranged/weaponSkill/@rating" />;
		}
		
		function rangedDamageObject() {
			this.speed=<xsl:value-of select="characterTab/ranged/damage/@speed" />;
			this.min=<xsl:value-of select="characterTab/ranged/damage/@min" />;
			this.max=<xsl:value-of select="characterTab/ranged/damage/@max" />;
			this.dps=<xsl:value-of select="characterTab/ranged/damage/@dps" />;
			this.percent=<xsl:value-of select="characterTab/ranged/damage/@percent" />;
		
			if (this.percent &gt; 0)		this.effectiveColor="class='mod'";
			else if (this.percent &lt; 0)	this.effectiveColor="class='moddown'";
		
		}
		
		function rangedSpeedObject() {
			this.value=<xsl:value-of select="characterTab/ranged/speed/@value" />;
			this.hasteRating=<xsl:value-of select="characterTab/ranged/speed/@hasteRating" />;
			this.hastePercent=<xsl:value-of select="characterTab/ranged/speed/@hastePercent" />;
		}
		
		function rangedPowerObject() {
			this.base=<xsl:value-of select="characterTab/ranged/power/@base" />;
			this.effective=<xsl:value-of select="characterTab/ranged/power/@effective" />;
			this.increasedDps=<xsl:value-of select="characterTab/ranged/power/@increasedDps" />;
			this.petAttack=<xsl:value-of select="characterTab/ranged/power/@petAttack" />;
			this.petSpell=<xsl:value-of select="characterTab/ranged/power/@petSpell" />;
		
			this.diff=this.effective - this.base;
		}
		
		function rangedHitRatingObject() {
			this.value="<xsl:value-of select="characterTab/ranged/hitRating/@value" />";
			this.increasedHitPercent="<xsl:value-of select="characterTab/ranged/hitRating/@increasedHitPercent" />";
			this.armorPenetration="<xsl:value-of select="characterTab/ranged/hitRating/@penetration" />";
			this.reducedArmorPercent="<xsl:value-of select="characterTab/ranged/hitRating/@reducedArmorPercent" />";
		}
		
		function rangedCritChanceObject() {
			this.percent=<xsl:value-of select="characterTab/ranged/critChance/@percent" />;
			this.rating=<xsl:value-of select="characterTab/ranged/critChance/@rating" />;
			this.plusPercent=<xsl:value-of select="characterTab/ranged/critChance/@plusPercent" />;
		}
		
		function spellBonusDamageObject() {
			this.holy=<xsl:value-of select="characterTab/spell/bonusDamage/holy/@value" />;
			this.arcane=<xsl:value-of select="characterTab/spell/bonusDamage/arcane/@value" />;
			this.fire=<xsl:value-of select="characterTab/spell/bonusDamage/fire/@value" />;
			this.nature=<xsl:value-of select="characterTab/spell/bonusDamage/nature/@value" />;
			this.frost=<xsl:value-of select="characterTab/spell/bonusDamage/frost/@value" />;
			this.shadow=<xsl:value-of select="characterTab/spell/bonusDamage/shadow/@value" />;
			this.petBonusAttack=<xsl:value-of select="characterTab/spell/bonusDamage/petBonus/@attack" />;
			this.petBonusDamage=<xsl:value-of select="characterTab/spell/bonusDamage/petBonus/@damage" />;
			this.petBonusFromType="<xsl:value-of select="characterTab/spell/bonusDamage/petBonus/@fromType" />";
		
			this.value=this.holy;
			
			if (this.value &gt; this.arcane)	this.value=this.arcane;
			if (this.value &gt; this.fire)		this.value=this.fire;
			if (this.value &gt; this.nature)	this.value=this.nature;
			if (this.value &gt; this.frost)		this.value=this.frost;
			if (this.value &gt; this.shadow)	this.value=this.shadow;
		}
		
		function spellBonusHealingObject() {
			this.value=<xsl:value-of select="characterTab/spell/bonusHealing/@value" />;
		}
		
		function spellHasteRatingObject(){
			this.value=<xsl:value-of select="characterTab/spell/hasteRating/@hasteRating" />;
			this.percent=<xsl:value-of select="characterTab/spell/hasteRating/@hastePercent" />;
		}
		
		function spellHitRatingObject() {
			this.value=<xsl:value-of select="characterTab/spell/hitRating/@value" />;
			this.increasedHitPercent=<xsl:value-of select="characterTab/spell/hitRating/@increasedHitPercent" />;
			this.spellPenetration= <xsl:value-of select="characterTab/spell/penetration/@value" />;	
		}
		
		function spellCritChanceObject() {
			this.rating=<xsl:value-of select="characterTab/spell/critChance/@rating" />;
			this.holy=<xsl:value-of select="characterTab/spell/critChance/holy/@percent" />;
			this.arcane=<xsl:value-of select="characterTab/spell/critChance/arcane/@percent" />;
			this.fire=<xsl:value-of select="characterTab/spell/critChance/fire/@percent" />;
			this.nature=<xsl:value-of select="characterTab/spell/critChance/nature/@percent" />;
			this.frost=<xsl:value-of select="characterTab/spell/critChance/frost/@percent" />;
			this.shadow=<xsl:value-of select="characterTab/spell/critChance/shadow/@percent" />;
		
			this.percent=this.holy;
			
			if (this.percent &gt; this.arcane)	this.percent=this.arcane;
			if (this.percent &gt; this.fire)	this.percent=this.fire;
			if (this.percent &gt; this.nature)	this.percent=this.nature;
			if (this.percent &gt; this.frost)	this.percent=this.frost;
			if (this.percent &gt; this.shadow)	this.percent=this.shadow;
		}
		
		function spellPenetrationObject() {
			this.value=<xsl:value-of select="characterTab/spell/penetration/@value" />;
		}
		
		function spellManaRegenObject() {
			this.casting=<xsl:value-of select="characterTab/spell/manaRegen/@casting" />;
			this.notCasting=<xsl:value-of select="characterTab/spell/manaRegen/@notCasting" />;
		}
		
		function defensesArmorObject() {
			this.base=<xsl:value-of select="characterTab/defenses/armor/@base" />;
			this.effective=<xsl:value-of select="characterTab/defenses/armor/@effective" />;
			this.percent=<xsl:value-of select="characterTab/defenses/armor/@percent" />;
			this.petBonus=<xsl:value-of select="characterTab/defenses/armor/@petBonus" />;
		
			this.diff=this.effective - this.base;
		}
		
		function defensesDefenseObject() {
			this.rating=<xsl:value-of select="characterTab/defenses/defense/@rating" />;
			this.plusDefense=<xsl:value-of select="characterTab/defenses/defense/@plusDefense" />;
			this.increasePercent=<xsl:value-of select="characterTab/defenses/defense/@increasePercent" />;
			this.decreasePercent=<xsl:value-of select="characterTab/defenses/defense/@decreasePercent" />;
			this.value=<xsl:value-of select="characterTab/defenses/defense/@value" /> + this.plusDefense;
		}
		
		function defensesDodgeObject() {
			this.percent=<xsl:value-of select="characterTab/defenses/dodge/@percent" />;
			this.rating=<xsl:value-of select="characterTab/defenses/dodge/@rating" />;
			this.increasePercent=<xsl:value-of select="characterTab/defenses/dodge/@increasePercent" />;
		}
		
		function defensesParryObject() {
			this.percent=<xsl:value-of select="characterTab/defenses/parry/@percent" />;
			this.rating=<xsl:value-of select="characterTab/defenses/parry/@rating" />;
			this.increasePercent=<xsl:value-of select="characterTab/defenses/parry/@increasePercent" />;
		}
		
		function defensesBlockObject() {
			this.percent=<xsl:value-of select="characterTab/defenses/block/@percent" />;
			this.rating=<xsl:value-of select="characterTab/defenses/block/@rating" />;
			this.increasePercent=<xsl:value-of select="characterTab/defenses/block/@increasePercent" />;
		}
		
		function defensesResilienceObject() {
			this.value=<xsl:value-of select="characterTab/defenses/resilience/@value" />;
			this.hitPercent=<xsl:value-of select="characterTab/defenses/resilience/@hitPercent" />;
			this.damagePercent=<xsl:value-of select="characterTab/defenses/resilience/@damagePercent" />;
		}	
		
		var theCharacter = new characterObject();		
		var theCharUrl = "<xsl:value-of select="character/@charUrl" />";
	
	</script>
	
	<script type="text/javascript" src="/js/_lang/{$lang}/character-sheet.js"></script>
	<script type="text/javascript" src="/js/character/textObjects.js"></script>

</xsl:template>


</xsl:stylesheet>