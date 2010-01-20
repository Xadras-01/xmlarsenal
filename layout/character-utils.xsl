<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template name="replace-string">
	<xsl:param name="str"/>
	<xsl:param name="old"/>
	<xsl:param name="new"/>

	<xsl:choose>
		<xsl:when test="contains($str, $old)">
			<xsl:value-of select="substring-before($str, $old)"/>
			<xsl:value-of select="$new"/>
			<xsl:call-template name="replace-string">
				<xsl:with-param name="str" select="substring-after($str, $old)"/>
				<xsl:with-param name="old" select="$old"/>
				<xsl:with-param name="new" select="$new"/>
			</xsl:call-template>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="$str"/>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="character-tab">
	<xsl:param name="name"/>
	<xsl:param name="pageName"/>
	<xsl:param name="selected" select="$pageName = concat('character-', $name)"/>
	<xsl:param name="stringKey" select="$name"/>
	<xsl:param name="path" select="concat('/character-', $name, '.xml')"/>
	<xsl:param name="parameters"/>
	<xsl:param name="url" select="concat($path, '?', $parameters)"/>

	<div class="select0">
		<xsl:if test="$selected">
			<xsl:attribute name="class">select1</xsl:attribute>
		</xsl:if>
		<div>
			<a class="tab" href="{$url}">
				<em>
					<span>
						<xsl:value-of select="$loc/strs/unsorted/str[@id = concat('armory.tabs.', $stringKey)]"/>
					</span>
				</em>
			</a>
		</div>
	</div>
</xsl:template>

<xsl:template name="character-tabs">
	<xsl:param name="thePage"/>
	<xsl:param name="charUrl"/>
	<xsl:param name="guildUrl"/>
	<xsl:param name="charLevel"/>
	<xsl:param name="isPropass" select="'false'"/>
	<xsl:param name="pathPropass" select="''"/>
    
    <xsl:variable name="isActiveCharacter" select="document(concat('/login-status.xml?',$charUrl))/page/loginStatus/@character"/>


	<div id="divCharTabs">
		<div class="tabs">
			<div class="hide">
				<xsl:call-template name="character-tab">
					<xsl:with-param name="name">sheet</xsl:with-param>
					<xsl:with-param name="pageName" select="$thePage"/>
					<xsl:with-param name="stringKey">characterSheet</xsl:with-param>
					<xsl:with-param name="parameters" select="$charUrl"/>
				</xsl:call-template>

				<xsl:call-template name="character-tab">
					<xsl:with-param name="name">talents</xsl:with-param>
					<xsl:with-param name="pageName" select="$thePage"/>
					<xsl:with-param name="parameters" select="$charUrl"/>
				</xsl:call-template>

				<xsl:if test="(character/arenaTeams or arenaTeams) and $isPropass != 'true'">
					<xsl:call-template name="character-tab">
						<xsl:with-param name="name">arenateams</xsl:with-param>
						<xsl:with-param name="pageName" select="$thePage"/>
						<xsl:with-param name="parameters" select="$charUrl"/>
					</xsl:call-template>
				</xsl:if>

				<xsl:if test="$isPropass = 'true' and string-length($pathPropass) &gt; 0">
					<div class="select2">
						<div>
							<a class="tab staticTip" onmouseover="setTipText('{$loc/strs/unsorted/str[@id='armory.tabs.character.arenateams.mouseover']}');" href="/team-info.xml?{$pathPropass/@url}">
								<em>
									<span>
										<xsl:value-of select="$loc/strs/unsorted/str[@id='armory.tabs.arenateams']"/>
									</span>
								</em>
							</a>
						</div>
					</div>
				</xsl:if>

				<xsl:if test="$charLevel &gt;= 10 and $isPropass != 'true'">
					<xsl:variable name="loginStatusParams">
						<xsl:choose>
							<!-- Double-URI encode to work around unconfirmed MSXML bug with parameter encoding in document() URIs -->
							<xsl:when test="system-property('xsl:vendor') = 'Microsoft'">
								<xsl:call-template name="replace-string">
									<xsl:with-param name="str" select="$charUrl"/>
									<xsl:with-param name="old" select="'%'"/>
									<xsl:with-param name="new" select="'%25'"/>
								</xsl:call-template>
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="$charUrl"/>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:variable>

					<xsl:variable name="profile" select="document(concat('/login-status.xml?', $loginStatusParams))/page/loginStatus"/>

					<!-- If you are logged in and viewing a character that is not yours, compare to your primary character if possible -->
					<xsl:variable name="compareParameters">
						<xsl:choose>
							<xsl:when test="count(/page/characterInfo/character[not(@errorCode)]) &gt; 1">
								<xsl:variable name="urls" select="/page/characterInfo/character[not(@errorCode)]/@charUrl"/>r=<xsl:for-each select="$urls">
									<xsl:if test="position() != 1">,</xsl:if>
									<xsl:value-of select="substring-before(substring(., 3), '&amp;n=')"/>
								</xsl:for-each>&amp;n=<xsl:for-each select="$urls">
									<xsl:if test="position() != 1">,</xsl:if>
									<xsl:value-of select="substring-after(., '&amp;n=')"/>
								</xsl:for-each>
							</xsl:when>
							<!-- TODO encoded name/realm should be available as separate parameters -->
							<xsl:when test="$profile/@username != ''">
								<xsl:variable name="characters" select="document('/vault/character-select.xml')/page/characters/character"/>
								<xsl:choose>
									<xsl:when test="not($charUrl = $characters/@url)">
										<xsl:variable name="mainCharacterUrl" select="$characters[@selected = 1]/@url"/>
										<xsl:value-of select="concat('r=', substring-before(substring($charUrl, 3), '&amp;n='), ',',             substring-before(substring($mainCharacterUrl, 3), '&amp;n='), '&amp;n=',             substring-after($charUrl, '&amp;n='), ',', substring-after($mainCharacterUrl, '&amp;n='))"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:value-of select="$charUrl"/>
									</xsl:otherwise>
								</xsl:choose>
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="$charUrl"/>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:variable>

					<xsl:call-template name="character-tab">
						<xsl:with-param name="name">reputation</xsl:with-param>
						<xsl:with-param name="pageName" select="$thePage"/>
						<xsl:with-param name="parameters" select="$charUrl"/>
					</xsl:call-template>

					<xsl:call-template name="character-tab">
						<xsl:with-param name="name">achievements</xsl:with-param>
						<xsl:with-param name="pageName" select="$thePage"/>
						<xsl:with-param name="parameters" select="$compareParameters"/>
					</xsl:call-template>

					<xsl:call-template name="character-tab">
						<xsl:with-param name="name">statistics</xsl:with-param>
						<xsl:with-param name="pageName" select="$thePage"/>
						<xsl:with-param name="parameters" select="$compareParameters"/>
					</xsl:call-template>

					<div class="select0">                    	
						<xsl:if test="$thePage='character-calendar'">
							<xsl:attribute name="class">select1</xsl:attribute>
						</xsl:if>
						<xsl:if test="not($profile/@character) and string-length($profile/@username) != 0">
							<xsl:attribute name="style">display:none</xsl:attribute>
						</xsl:if>
                        
						<div>
							<a class="tab" href="/vault/character-calendar.xml?{$charUrl}">
                            	<xsl:if test="$isActiveCharacter = '2'">
                                    <xsl:attribute name="class">tab disabledTab staticTip</xsl:attribute>
                                    <xsl:attribute name="onmouseover">setTipText('<xsl:value-of select="$loc/strs/unsorted/str[@id='armory.tabs.disableTab']"/>');</xsl:attribute>
                                    <xsl:attribute name="href">javascript:void(0);</xsl:attribute>
                                </xsl:if>
								<em>
									<span>
										<xsl:if test="string-length($profile/@username) = 0">
										<div style="display:block; float:left; vertical-align:baseline; width:21px; height:21px; background:url('../images/tab-key-2.gif') no-repeat left center; margin:11px 8px 0px -5px"/>
										</xsl:if>
										<xsl:value-of select="$loc/strs/unsorted/str[@id='armory.tabs.calendar']"/>
									</span>
								</em>
							</a>
						</div>
					</div>
				</xsl:if>

				<xsl:if test="$guildUrl != '' and $isPropass != 'true'">
					<div class="select2">
						<div>
							<a class="tab staticTip" onmouseover="setTipText('{$loc/strs/unsorted/str[@id='armory.tabs.character.guild.mouseover']}')" href="/guild-info.xml?{$guildUrl}"><em><span><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.tabs.guild']"/></span></em></a>
						</div>
					</div>
				</xsl:if>
			</div>
		</div>
	</div>
</xsl:template>

<!-- FIXME link tags must go in the head, not body. See head-content. -->
<!-- FIXME center tags are not valid -->

<xsl:template name="charSheetMiniSearchPanel">
	<xsl:param name="searchNode"/>

	<xsl:choose>
		<xsl:when test="$searchNode">
			<xsl:call-template name="lastSearchTemplate">
				<xsl:with-param name="searchNode" select="$searchNode"/>
			</xsl:call-template>
		</xsl:when>
		<xsl:otherwise>
			<xsl:choose>
				<xsl:when test="miniSearch">
					<xsl:variable name="guildRoster" select="document(concat('/guild-info.xml?r=',character/@realm,'&amp;n=',character/@guildName,'&amp;p=',miniSearch/@page,'&amp;select=',character/@name))/page/guildInfo"/>

					<xsl:choose>
						<xsl:when test="$guildRoster/guild">
							<!-- set the expanded search panel style -->
							<link rel="stylesheet" type="text/css" media="screen, projection" href="css/mini-search-expand.css"/>

							<xsl:call-template name="miniGuildRosterTemplate">
								<xsl:with-param name="path" select="$guildRoster"/>
							</xsl:call-template>
						</xsl:when>
						<xsl:otherwise>
							<!-- set the collapsed search panel style -->
							<link rel="stylesheet" type="text/css" media="screen, projection" href="css/mini-search-collapse.css"/>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:when>
				<xsl:otherwise>
					<xsl:variable name="guildRoster" select="document(concat('/guild-info.xml?r=',character/@realm,'&amp;n=',character/@guildName,'&amp;p=1','&amp;select=',character/@name))/page/guildInfo"/>

					<xsl:choose>
						<xsl:when test="$guildRoster/guild">
							<!-- set the expanded search panel style -->
							<link rel="stylesheet" type="text/css" media="screen, projection" href="css/mini-search-expand.css"/>

							<xsl:call-template name="miniGuildRosterTemplate">
								<xsl:with-param name="path" select="$guildRoster"/>
							</xsl:call-template>
						</xsl:when>
						<xsl:otherwise>
							<!-- set the collapsed search panel style -->
							<link rel="stylesheet" type="text/css" media="screen, projection" href="css/mini-search-collapse.css"/>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="propassRight">
	<xsl:param name="pathPropass"/>

	<link rel="stylesheet" type="text/css" media="screen, projection" href="css/mini-search-expand.css"/>

	<div class="results-side">
		<div class="results-list">
			<div class="result-banner">
				<h3 class="results-header"><em><xsl:value-of select="$loc/strs/propass/str[@id='teaminfo']"/></em></h3>
			</div>
			<div class="ps">
				<div class="ps-bot">
					<div class="ps-top">
						<div class="data">
							<center><strong class="propassName"><xsl:value-of select="$pathPropass/@name"/></strong></center>

							<xsl:variable name="rankingPropass" select="$pathPropass/@ranking"/>

							<xsl:variable name="standingPropass">
								<xsl:if test="$rankingPropass != 0 and $rankingPropass &lt;= 1000 ">
									<xsl:value-of select="$rankingPropass"/><xsl:call-template name="positionSuffix">
										<xsl:with-param name="pos" select="$rankingPropass"/>
									</xsl:call-template>
								</xsl:if>
							</xsl:variable>

							<ul class="badges-pvp">
								<li style="width: 170px">
									<div class="arenacontainer" style="width: 100%">
										<br/>
										<span style="font-size: 12px; font-weight: bold; line-height: 140%">
											<xsl:value-of select="$loc/strs/propass/str[@id='arena']"/>
											<br/>
											<xsl:value-of select="$loc/strs/propass/str[@id='tournament']"/>
											<br/>
											<xsl:value-of select="$loc/strs/propass/str[@id='rating']"/><xsl:value-of select="$pathPropass/@rating"/>
										</span>
										<div class="icon" id="iconPropassteam" onclick="window.location.href = 'team-info.xml?{$pathPropass/@url}'" style="cursor: pointer">
											<img src="/images/pixel.gif" id="badgeBorderPropassteam" class="p" border="0"/>

											<div class="rank-num" id="arenarank5">
												<xsl:call-template name="flash">
													<xsl:with-param name="id" select="'arenarank5'"/>
													<xsl:with-param name="src" select="'/images/rank.swf'"/>
													<xsl:with-param name="wmode" select="'transparent'"/>
													<xsl:with-param name="width" select="'100'"/>
													<xsl:with-param name="height" select="'40'"/>
													<xsl:with-param name="quality" select="'best'"/>
													<xsl:with-param name="flashvars" select="concat('rankNum=', $standingPropass)"/>
												</xsl:call-template>
											</div>
										</div>
									</div>
								</li>
							</ul>

							<script type="text/javascript">
								getArenaIconBorder(<xsl:value-of select="$pathPropass/@rating"/>, 'badgeBorderPropassteam')
							</script>

							<center>
								<xsl:value-of select="$loc/strs/propass/str[@id='winloseone']"/>
								<xsl:value-of select="$pathPropass/@seasonGamesWon"/>
								<xsl:value-of select="$loc/strs/propass/str[@id='winlosetwo']"/>
								<xsl:value-of select="$pathPropass/@seasonGamesPlayed - $pathPropass/@seasonGamesWon"/>
								<xsl:value-of select="$loc/strs/propass/str[@id='winlosethree']"/>
								<br/>
								<xsl:value-of select="$loc/strs/propass/str[@id='winpercentageone']"/>
								<xsl:value-of select="round($pathPropass/@seasonGamesWon div $pathPropass/@seasonGamesPlayed * 100)"/>
								<xsl:value-of select="$loc/strs/propass/str[@id='winpercentagetwo']"/>
							</center>
							<p/>

		    				<table class="data-table">
								<xsl:for-each select="$pathPropass/members/character">
		    						<tr class="data0">
										<xsl:if test="position() mod 2 = 0">
											<xsl:attribute name="class">data1</xsl:attribute>
										</xsl:if>
										<td><div><p/></div></td>
										<td>
											<a>
												<strong> </strong>
											</a>
										</td>
										<td>
											<q>
												<xsl:choose>
													<xsl:when test="@charUrl">
														<a><strong>
															<xsl:call-template name="nameAbbreviator">
																<xsl:with-param name="name" select="@name"/>
																<xsl:with-param name="link">character-sheet.xml?<xsl:value-of select="@charUrl"/></xsl:with-param>
															</xsl:call-template>
														</strong></a>
													</xsl:when>
													<xsl:otherwise>
														<a><strong>
															<xsl:call-template name="nameAbbreviator">
																<xsl:with-param name="name" select="@name"/>
															</xsl:call-template>
														</strong></a>
													</xsl:otherwise>
												</xsl:choose>
											</q>
										</td>
										<td align="center">
											<xsl:variable name="raceIdStringGender" select="concat('armory.races.race.', @raceId,'.', @genderId)"/>
											<xsl:variable name="classIdStringGender" select="concat('armory.classes.class.', @classId,'.', @genderId)"/>
											<img onmouseover="setTipText('{$loc/strs/classes/str[@id=$classIdStringGender]}')" class="ci staticTip" src="/images/icons/class/{@classId}.gif"/>
										</td>
      									<td align="center">
      										<q style="font-size: 11px; font-weight: bold; top: 1px"><xsl:value-of select="@contribution"/></q>
      									</td>
      									<td align="right"><div><b/></div></td>
    								</tr>
								</xsl:for-each>
    						</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</xsl:template>

</xsl:stylesheet>