<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"/>

<xsl:template name="faq-template">
  <xsl:param name = "faqTitle" />
  <xsl:param name = "faqPath" />

<div id="dataElement">
 <div class="parchment-top">
  <div class="parchment-content">	
<div class="list">
<div class="full-list notab">
<div class="info-pane">
			<div class="profile-wrapper">
<div class="generic-wrapper"><div class="generic-right"><div class="genericHeader">


<div class="faq-nav">
	<div class="nav-links">
<xsl:for-each select = "document('/data/faqs.xml')/faqs/faq">
  <xsl:variable name = "theKey" select = "@key" />
  <a href = "{@temp}"><xsl:value-of select = "$loc/strs/unsorted/str[@id=$theKey]" /></a>
	<xsl:choose><xsl:when test = "position() != last()"> | </xsl:when></xsl:choose>
	<xsl:choose>
		<xsl:when test="$lang='en_us' or $lang='en_gb'">
			<xsl:if test = "position() = 9"><br/></xsl:if>
		</xsl:when>
		<xsl:when test="$lang='es_mx'">
			<xsl:if test = "position() = 6"><br/></xsl:if>
		</xsl:when>
		<xsl:when test="$lang='ru_ru'">
			<xsl:if test = "position() = 7"><br/></xsl:if>
		</xsl:when>
		<xsl:otherwise>
			<xsl:if test = "position() = 8"><br/></xsl:if>
		</xsl:otherwise>
	</xsl:choose>
</xsl:for-each>
	</div>
</div>

<div class="faq-content">
  <h2><xsl:value-of select="$faqTitle" /></h2>

				<xsl:for-each select="document(concat('../strings/',$lang,'/', $faqPath))/relatedinfo/faqlist/faq"><div class="faq-body">
				<a name = "anchor{@key}"></a>				
				<xsl:variable name="positionNum"><xsl:number value="position()" format="1" /></xsl:variable>				
				<p class="question"><xsl:value-of select="@question"/></p>
							<div>
								<xsl:apply-templates/>
							</div></div>
				</xsl:for-each>

</div><!--/end faq/-->

  </div></div></div> <!--/generic Wrapper-->
</div><!--/end profile-wrapper/-->
</div>
</div><!--/end player-side/-->

</div><!--/end list/-->
 </div>
</div>

</div>


</xsl:template>

</xsl:stylesheet>
