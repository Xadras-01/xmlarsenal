<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"/>

<xsl:include href="includes.xsl"/>
<xsl:include href="faq-template.xsl"/>

<xsl:template match="faq">

<xsl:variable name = "filename" select = "'ri-guild-info.xml'" />

<xsl:call-template name = "faq-template">
  <xsl:with-param name = "faqTitle" select = "document(concat('../strings/',$lang,'/', $filename))/relatedinfo/@topic" />
  <xsl:with-param name = "faqPath" select = "$filename" />  
</xsl:call-template>

</xsl:template>

</xsl:stylesheet>
