<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"/>
<xsl:include href="includes.xsl"/>
<xsl:template match="page">

<html id="errorpage">
<head>
<title><xsl:value-of select="$loc/strs/str[@id='armory.labels.wow-the-armory']"/></title>
<style type="text/css">
@import "/css/_lang/<xsl:value-of select="$lang"/>/language.css";
@import "/css/errors.css";
</style>
</head>
<body class="errorpage">

	
	<div class="container">
		<h1><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.labels.error.filenotfound']"/></h1>
		<ul>
			<li><a href="{$regionArmoryURL}"><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.labels.returntoprevious']"/></a></li>
			<li><a href="{$regionWoWURL}"><xsl:value-of select="$loc/strs/unsorted/str[@id='armory.labels.returntomain']"/></a></li>
		</ul>
	</div>
</body>
</html>

</xsl:template>
</xsl:stylesheet>