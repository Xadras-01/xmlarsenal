<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:include href="includes.xsl"/>
<xsl:include href="tabs-template.xsl"/>
<xsl:template name = "dungeonLevel">
  <xsl:param name = "levelMin" />
  <xsl:param name = "levelMax" />
  <xsl:choose><xsl:when test = "$levelMin != -1"> <xsl:value-of select = "$levelMin" /><xsl:choose><xsl:when test = "$levelMin != $levelMax"> - <xsl:value-of select = "$levelMax" /></xsl:when></xsl:choose></xsl:when></xsl:choose>
</xsl:template>

<xsl:template name = "dungeonType">
  <xsl:param name = "raid" />
  <xsl:param name = "party" />
<xsl:choose><xsl:when test = "$raid = 1"> </xsl:when></xsl:choose>
</xsl:template>

<xsl:template match="dungeons">

<xsl:variable name="dungeonsXml" select="document('/data/dungeons.xml')" />
<xsl:variable name="locDungeons" select="document('/data/dungeonStrings.xml')" />
<xsl:variable name="textDungeons" select="$loc/strs/dungeons/str[@id='dungeonsandraids']" />
<xsl:variable name="curPage" select="/page/@pageId"/>
<div id="dataElement">
 <div class="parchment-top">
  <div class="parchment-content">
			
<!--// index content //-->			
<div class="list">
	<div class="tabs"> 
		<!-- print top-level tabs -->
		<xsl:variable name="tabData" select="document(concat('/data/_region/', translate($region,'CEKNORSTUW','ceknorstuw'), '/tabs-releases.xml'))" />		
		<xsl:for-each select="$tabData/tabs/tab">
			<div class="tab">
				<xsl:if test="@pageId = $curPage">
					<xsl:attribute name="class">selected-tab</xsl:attribute>
				</xsl:if>
				<a href="{@link}"><xsl:value-of select="$loc/strs/unsorted/str[@id=current()/@key]" /></a>
			</div>
		</xsl:for-each>
		<div class="clear"></div>
	</div>
	<!-- still print subtabs so the curved corners are there -->
	<div class="subTabs" style="height: 1px;">
		<div class="upperLeftCorner" style="height: 5px;"></div>
		<div class="upperRightCorner" style="height: 5px;"></div>			
	</div>
<div class="full-list">
<div class="info-pane">
	<div class="profile-wrapper">
      <xsl:for-each select="document('/data/releases.xml')/releases/release">
        <xsl:variable name = "releaseKey" select = "@key" />
        <xsl:variable name = "releaseId" select = "@id" />
<div style="display:none">
  <xsl:attribute name="id">dungeonRelease<xsl:value-of select = "$releaseId" /></xsl:attribute>
	<xsl:if test="$curPage = $releaseId"><xsl:attribute name="style">display:block</xsl:attribute></xsl:if>
<div class="generic-wrapper"><div class="generic-right"><div class="genericHeader">
<!--<div>
  <xsl:attribute name = "class">dungeon-header-<xsl:value-of select = "$releaseKey" /></xsl:attribute>
</div>-->
<div class="dungeon-container">
	<div class="info-header dhbg{$releaseKey}">
		<h1><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.dungeons']"/></h1>
		<h2><xsl:value-of select="$loc/strs/unsorted/str[@id=$releaseKey]"/></h2>
	</div>
	<div class="dungeon-header">
	<table>
	 <tr>
		<td width="25"></td>
		<td width="250"><span><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.dungeons.dungeon']"/></span></td>
		<td width="120"><span><xsl:value-of select="$loc/strs/dungeons/str[@id='bosses']"/></span></td>
		<td width="80"><span><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.labels.level']"/></span></td>
		<td width="150"><span><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.labels.type']"/></span></td>
	 </tr>
	</table>
	</div>

<script type="text/javascript">

function toggleDungeon(dungeonId) {
	elementId = $blizz('showHideBosses'+ dungeonId);
	if (elementId.style.display == 'block') {
		$blizz('toggleExpand'+dungeonId).className = "expand-list";
		elementId.style.display = 'none';
	} else {
		$blizz('toggleExpand'+dungeonId).className = "collapse-list";	
		elementId.style.display = 'block';
	}
}
</script>

<div class="dungeon-content">
	<table>
    <xsl:for-each select="$locDungeons/page/dungeons/dungeon[@release=$releaseId]">
			<xsl:variable name = "dungeonId" select = "@id" />
      <xsl:variable name = "dungeonName" select = "@name" />
      <xsl:variable name = "dungeonPath" select = "$dungeonsXml/dungeons/dungeon[@id=$dungeonId]" />			
      <xsl:variable name = "isRaid" select = "$dungeonPath/@raid" />
			<xsl:variable name="altId">
				<xsl:choose>
					<xsl:when test="position() mod 2 != 1">rc1</xsl:when>
					<xsl:otherwise>rc0</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
		<tr id="toggleExpand{$dungeonId}" class="expand-list">
			<td width="25" valign="top" class="{$altId}">		
			  <a class="expandToggle" href="javascript: javascript: toggleDungeon({$dungeonId});"></a>
		  </td>
		  <td width="250" valign="top" class="{$altId}"><a href = "/search.xml?fl%5Bsource%5D=dungeon&amp;fl%5Bdungeon%5D={$dungeonId}&amp;fl%5Bboss%5D=all&amp;fl%5Bdifficulty%5D=all&amp;searchType=items"><xsl:value-of select = "$dungeonName" /></a>
				<div class="d-bosses" id = "showHideBosses{$dungeonId}" style="display:none; ">
				  <xsl:for-each select="$locDungeons/page/dungeons/dungeon[@id=$dungeonId]/boss">
				    <em></em><a href = "/search.xml?fl%5Bsource%5D=dungeon&amp;fl%5Bdungeon%5D={$dungeonId}&amp;fl%5Bboss%5D={@id}&amp;fl%5Bdifficulty%5D=all&amp;searchType=items"><xsl:value-of select = "@name" /></a>
				  </xsl:for-each>
				</div>
		  </td>
		  <td width="120" valign="top" class="{$altId}"><a href="javascript: toggleDungeon({$dungeonId});"><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.dungeons.viewbosses']"/></a><span>(<xsl:for-each select="$locDungeons/page/dungeons/dungeon[@id=$dungeonId]"><xsl:value-of select="count(*)"/></xsl:for-each>)</span>
  		</td>
		  <td width="80" valign="top" class="{$altId}">
				<xsl:call-template name = "dungeonLevel">
				  <xsl:with-param name = "levelMin" select = "$dungeonPath/@levelMin" />
			  	<xsl:with-param name = "levelMax" select = "$dungeonPath/@levelMax" />
				</xsl:call-template>
		  </td>
		  <td width="150" valign="top" class="{$altId}">			
				<xsl:choose>
				  <xsl:when test = "$isRaid = 1"><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.dungeons.partysize.before']"/><xsl:value-of select = "$dungeonPath/@partySize" /><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.dungeons.partysize.after']"/></xsl:when>
			  	<xsl:otherwise><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.dungeons.dungeon']"/></xsl:otherwise>
				</xsl:choose>
  		</td>
		</tr>
    </xsl:for-each>
	</table>
		<xsl:choose>
		  <xsl:when test = "$releaseId = 1">
				<div class="table-footnote">
            <span style="padding-left: 30px;">		  
		    <xsl:value-of select="$loc/strs/unsorted/str[@id='armory.dungeons.seealsocolon']"/>&#160;<a href = "/search.xml?fl%5Bsource%5D=dungeon&amp;fl%5Bdungeon%5D=badgeofjustice&amp;fl%5Bboss%5D=all&amp;fl%5Bdifficulty%5D=all&amp;searchType=items"><img src="/wow-icons/_images/21x21/spell_holy_championsbond.png" class="boj" /><xsl:value-of select="$loc/strs/itemsOptions/str[@id='armory.item-search.select.bojr']"/></a></span><br /><br />
				</div>
		  </xsl:when>
		  <xsl:when test = "$releaseId = 2">
				<div class="table-footnote">
            <span style="padding-left: 30px;">		  
		    <xsl:value-of select="$loc/strs/unsorted/str[@id='armory.dungeons.seealsocolon']"/>&#160;<a href = "/search.xml?fl%5Bsource%5D=dungeon&amp;fl%5Bdungeon%5D=emblemofheroism&amp;fl%5Bboss%5D=all&amp;fl%5Bdifficulty%5D=all&amp;searchType=items"><img src="/wow-icons/_images/21x21/spell_holy_proclaimchampion.png" class="boj" /><xsl:value-of select="$loc/strs/itemsOptions/str[@id='armory.item-search.select.eohr']"/></a></span>&#160;&#160;<span><a href = "/search.xml?fl%5Bsource%5D=dungeon&amp;fl%5Bdungeon%5D=emblemofvalor&amp;fl%5Bboss%5D=all&amp;fl%5Bdifficulty%5D=all&amp;searchType=items"><img src="/wow-icons/_images/21x21/spell_holy_proclaimchampion_02.png" class="boj" /><xsl:value-of select="$loc/strs/itemsOptions/str[@id='armory.item-search.select.eovr']"/></a></span><br/><br/>
				</div>
		  </xsl:when>
	    </xsl:choose>


</div>
</div>
</div></div></div> <!--/generic Wrapper-->
</div>
</xsl:for-each>
</div><!--/end profile-wrapper/-->
</div><!--/end tip/-->				
</div><!--/end player-side/-->


</div><!--/end list/-->
 </div>
</div>
</div>


</xsl:template>

</xsl:stylesheet>
