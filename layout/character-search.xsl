<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"/>

<xsl:include href="includes.xsl"/>
<xsl:include href="character-header.xsl"/>
<xsl:include href="character-utils.xsl"/>
<xsl:include href="mini-search-templates.xsl"/>

<xsl:template match="page/characterSearch">


<script type="text/javascript">
	<!-- when page loads -->
	$(document).ready(function() {

		location.href = "/index.xml";
	
	});
</script>



</xsl:template>

</xsl:stylesheet>
