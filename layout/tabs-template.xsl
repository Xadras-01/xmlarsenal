<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"/>

<xsl:template name="tabsTemplate">
	<xsl:param name="whichXml"></xsl:param>	
	<xsl:param name="pageId"></xsl:param>
	<xsl:param name="otherParam"></xsl:param>	

<script type="text/javascript" src = "/js/tabs.js"></script>
<script type="text/javascript">
	
	var tabsArray = new Array;
	var tabsPosition = 1;
	var tabCounter = 1;
	
	//populate the array with tab names
	var selectedValue = '<xsl:value-of select="$pageId" />';

	if (selectedValue == '')
		selectedValue = '<xsl:value-of select="../tabs/@selected" />';	

	<xsl:for-each select="$whichXml/tabs/tab">
	

		<xsl:variable name="strLabel" select="@key" />
		<xsl:variable name="strTooltip" select="@keyTooltip" />

			tabType = '<xsl:value-of select="@pageId" />';
			tabLabel = '<xsl:value-of select="$loc/strs/unsorted/str[@id=$strLabel]"/>';		
			tabClass = '<xsl:value-of select="@class"/>';
			<xsl:choose>
			  <xsl:when test = "string-length($otherParam) = 0">
				tabLink = "<xsl:value-of select="@link"/>";
			  </xsl:when>
			  <xsl:otherwise>
				tabLink = "<xsl:value-of select="@link"/>?" + "<xsl:value-of select="$otherParam"/>";
			  </xsl:otherwise>
		    </xsl:choose>
			tabTooltip = '<xsl:value-of select="$loc/strs/unsorted/str[@id=$strTooltip]"/>';			
			tabsArray[tabCounter - 1] = [tabType, tabLabel, tabClass, tabLink, tabTooltip];
			if (selectedValue == tabType)
				tabsPosition = tabCounter;
			tabCounter++;

	</xsl:for-each>

	var tabsCount = tabsArray.length; //how many tabs there are
	var replaceWithThis ="";
	//replaceWithThis = '&lt;table&gt;&lt;tr&gt;&lt;td&gt;';
	replaceWithThis += '&lt;div id="wrapperTabs" class="tabs"&gt;';
	replaceWithThis += '&lt;div class="hide"&gt;';
	for (x = 1; x != tabsCount + 1; x++) {
		replaceWithThis += printOutTabTemplate(tabsArray[x-1][1], tabsArray[x-1][0], x, tabsArray[x-1][2], tabsArray[x-1][3], tabsArray[x-1][4]);
	}

	replaceWithThis +='&lt;/div&gt;';	
	replaceWithThis +='&lt;/div&gt;';	
	//replaceWithThis += '&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;';	

	//print out all the tab info
	
//	alert(replaceWithThis);
			
	
	document.getElementById("replaceMe").innerHTML = replaceWithThis;

	//select the correct tab

	if (selectedValue != 'none')
		changeTabClassname(tabsPosition);

	//see if two rows are needed
	addEvent(window, 'load', rulerTest);

</script>


</xsl:template>

</xsl:stylesheet>
