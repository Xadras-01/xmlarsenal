<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- tabs -->
<xsl:template name="newCharTabs"><link rel="stylesheet" href="/css/character/global.css" type="text/css" />

	<xsl:call-template name="tabs">
		<xsl:with-param name="tabGroup" select="/page/tabInfo/@tabGroup" />
		<xsl:with-param name="currTab" select="/page/tabInfo/@tab" />
		<xsl:with-param name="subTab" select="/page/tabInfo/@subTab" />
		<xsl:with-param name="tabUrlAppend" select="/page/tabInfo/@tabUrl" />
		<xsl:with-param name="subtabUrlAppend" select="/page/tabInfo/@tabUrl" />
	</xsl:call-template>

</xsl:template>


<!-- character header -->
<xsl:template name="newCharHeader">

	<script type="text/javascript">
		var charUrl = "<xsl:value-of select="/page/characterInfo/character/@charUrl"/>";
		var bookmarkMaxedToolTip = "<xsl:value-of select="$loc/strs/common/str[@id='user.bookmark.maxedtooltip']"/>";
		var bookmarkThisChar = "<xsl:value-of select="$loc/strs/common/str[@id='user.bookmark.character']" />";	
	</script>


	<xsl:variable name="c" select="character[1]" />
	<xsl:variable name="level" select="$c/@level" />
	<xsl:variable name="points" select="$c/@points" />
	
	<xsl:variable name="IS_LOGGED_IN" select="not(document('/login-status.xml')/page/loginStatus/@username = '')"/>

	<div class="faction-horde">	
		<xsl:choose>
			<xsl:when test="$c/@tournamentRealm = '1'">
				<xsl:attribute name="class">faction-propass</xsl:attribute>
			</xsl:when>
			<xsl:when test="$c/@factionId=0">
				<xsl:attribute name="class">faction-alliance</xsl:attribute>
			</xsl:when>			
		</xsl:choose>
		<div id="profileRight" class="profile-right">				
			<xsl:choose>
				<xsl:when test="/page/characterInfo/bookmark/@bookmarked">
                    <div class="bmcEnabled"></div>
                </xsl:when>
				<xsl:when test="$IS_LOGGED_IN = 'true'">
					<a id="bmcLink" href="javascript:ajaxBookmarkChar()">					
						<span><xsl:value-of select="$loc/strs/common/str[@id='user.bookmark.character']" /></span><em /></a>
				</xsl:when>
				<xsl:when test="/page/characterInfo/bookmark/@count &gt;= /page/characterInfo/bookmark/@max">
                    <div class="bmcMaxed staticTip" onmouseover="setTipText(bookmarkMaxedToolTip)"></div>
                </xsl:when>
				<xsl:otherwise>
					<a id="bmcLink" class="bmcLink">
						<span><xsl:value-of select="$loc/strs/common/str[@id='user.bookmark.logintobookmark']" /></span><em /></a>
				</xsl:otherwise>
			</xsl:choose>
		</div>
		<div class="profile-achieve">			
			<xsl:choose>
				<xsl:when test="$level &lt; 60">
					<img src="/images/portraits/wow-default/{$c/@genderId}-{$c/@raceId}-{$c/@classId}.gif" />
				</xsl:when>
				<xsl:when test="$level &lt; 70">
					<img src="/images/portraits/wow/{$c/@genderId}-{$c/@raceId}-{$c/@classId}.gif" />
				</xsl:when>
				<xsl:when test="$level &lt; 80">
					<img src="/images/portraits/wow-70/{$c/@genderId}-{$c/@raceId}-{$c/@classId}.gif" />
				</xsl:when>
				<xsl:otherwise>
					<img src="/images/portraits/wow-80/{$c/@genderId}-{$c/@raceId}-{$c/@classId}.gif" />
				</xsl:otherwise>
			</xsl:choose>
			
			<!-- achievement points -->
			<div class="points">
				<a href="character-achievements.xml?{$c/@charUrl}"><xsl:value-of select="$points" /></a>
			</div>
			<!-- level -->
			<xsl:call-template name="flash">
				<xsl:with-param name="id" select="'leveltext'"/>
				<xsl:with-param name="src" select="concat('/images/',$lang,'/flash/level.swf')"/>
				<xsl:with-param name="base" select="concat('/images/',$lang,'/flash')"/>
				<xsl:with-param name="wmode" select="'transparent'"/>
				<xsl:with-param name="width" select="'38'"/>
				<xsl:with-param name="height" select="'38'"/>
				<xsl:with-param name="quality" select="'best'"/>
				<xsl:with-param name="flashvars" select="concat('charLevel=', $level,'&amp;pts=',$points)"/>
				<xsl:with-param name="noflash" select="concat('&lt;div class=level-noflash&gt;',$level,'&lt;em&gt;',$level,'&lt;/em&gt;&lt;/div&gt;')"/>
			</xsl:call-template>
		</div>		
		
		<!-- shows name, suffix/prefix, guild, and class and level -->	
		<div id="charHeaderTxt_Dark">
			<span class="prefix">
				<xsl:if test="$c/@prefix = ''"><xsl:attribute name="class">emptyPrefix</xsl:attribute></xsl:if>
				<xsl:value-of select="$c/@prefix" />
			</span>			
			<div class="charNameHeader"><xsl:value-of select="/page/characterInfo/character/@name" />
				<span class="suffix"><xsl:value-of select="$c/@suffix" /></span></div>
			<xsl:if test="not(character/@guildName = '')">
				<a href="/guild-info.xml?{$c/@guildUrl}" class="charGuildName"><xsl:value-of select="$c/@guildName" /></a>
			</xsl:if>
			<span class="charLvl">
				<xsl:apply-templates mode="printf" select="$loc/strs/character/str[@id='charLevelStr']">
					<xsl:with-param name="param1" select="$c/@level" />
					<xsl:with-param name="param2" select="$c/@race" />
					<xsl:with-param name="param3" select="$c/@class" />
				</xsl:apply-templates>
			</span></div>
		
		<div id="charHeaderTxt_Light">
			<span class="prefix">
				<xsl:if test="$c/@prefix = ''"><xsl:attribute name="class">emptyPrefix</xsl:attribute></xsl:if>
				<xsl:value-of select="$c/@prefix" />
			</span>
			<div class="charNameHeader"><xsl:value-of select="/page/characterInfo/character/@name" />
				<span class="suffix"><xsl:value-of select="$c/@suffix" /></span></div>
			<xsl:if test="not($c/@guildName = '')">
				<a href="/guild-info.xml?{$c/@guildUrl}" class="charGuildName"><xsl:value-of select="$c/@guildName" /></a>
			</xsl:if>
			<span class="charLvl">
				<xsl:apply-templates mode="printf" select="$loc/strs/character/str[@id='charLevelStr']">
					<xsl:with-param name="param1" select="$c/@level" />
					<xsl:with-param name="param2" select="$c/@race" />
					<xsl:with-param name="param3" select="$c/@class" />
				</xsl:apply-templates>
			</span></div>		
	
		
		<div id="forumLinks">
			<!-- ahref around the whole block so the entire thing is clickable -->			
			<a class="smFrame staticTip" href="{$regionForumsURL}board.html?sid=1&amp;forumName={$c/@realm}" onmouseover="setTipText('{$loc/strs/unsorted/str[@id='armory.forum.link.realm']}');">
				<!-- <xsl:if test="not($region = 'US' or $region = 'EU')"> -->
					<xsl:attribute name="href">javascript:void(0)</xsl:attribute>
					<xsl:attribute name="class">smFrame</xsl:attribute>
					<xsl:attribute name="onmouseover"></xsl:attribute>						
				<!-- </xsl:if> -->
				<div><xsl:value-of select="$c/@realm" /></div>
				<img src="/images/icon-header-realm.gif" />
			</a>
			<a class="smFrame staticTip" href="{$regionForumsURL}board.html?sid=1&amp;forumName={character/@battleGroup}" onmouseover="setTipText('{$loc/strs/unsorted/str[@id='armory.forum.link.battleGroup']}');">
				<!-- <xsl:if test="not($region = 'US' or $region = 'EU')"> -->
					<xsl:attribute name="href">javascript:void(0)</xsl:attribute>
					<xsl:attribute name="class">smFrame</xsl:attribute>
					<xsl:attribute name="onmouseover"></xsl:attribute>						
				<!-- </xsl:if> -->
				<div><xsl:value-of select="$c/@battleGroup" /></div>
				<img src="/images/icon-header-battlegroup.gif" />
			</a>			
		</div>
		
	</div>
</xsl:template>

</xsl:stylesheet>
