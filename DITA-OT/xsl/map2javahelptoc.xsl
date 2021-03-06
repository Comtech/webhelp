<?xml version="1.0" encoding="UTF-8" ?>
<!-- This file is part of the DITA Open Toolkit project hosted on 
     Sourceforge.net. See the accompanying license.txt file for 
     applicable licenses.-->
<!-- (c) Copyright IBM Corp. 2004, 2005 All Rights Reserved. -->

<xsl:stylesheet version="1.0" 
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- stylesheet imports -->
<xsl:import href="xslhtml/map2TOC.xsl"/>

<!-- default "output extension" processing parameter ('.html')-->
<xsl:param name="OUTEXT" select="'.html'"/><!-- "htm" and "html" are valid values -->

<xsl:output
    method="xml"
    omit-xml-declaration="no"
    encoding="UTF-8"
    doctype-public="-//Sun Microsystems Inc.//DTD JavaHelp TOC Version 1.0//EN"
    doctype-system="http://java.sun.com/products/javahelp/toc_1_0.dtd"
    indent="yes"
/>

<xsl:template match="*[contains(@class,' map/map ')]" mode="toctop">
  <toc version="1.0">
    <!-- OXYGEN PATCH START EXM-18359 -->
    <!-- 
          A map can have attribute 'title' or child element 'title. 
          A bookmap has child element 'booktitle'. 
    -->
    <xsl:choose>
      <xsl:when test="@title">
        <tocitem text="{@title}">
          <xsl:apply-templates select="*[contains(@class,' map/topicref ')]"/>
        </tocitem>
      </xsl:when>
      <xsl:when test="*[contains(@class,' topic/title ')]">
        <tocitem text="{normalize-space(*[contains(@class,' topic/title ')])}">
          <xsl:apply-templates select="*[contains(@class,' map/topicref ')]"/>
        </tocitem>
      </xsl:when>
    </xsl:choose>
    <!-- OXYGEN PATCH END EXM-18359 -->
  </toc>
</xsl:template>

<xsl:template match="*[contains(@class,' map/topicref ')]" 
              mode="tocentry">
  <xsl:param name="infile"/>
  <xsl:param name="outroot"/>
  <xsl:param name="outfile"/>
  <xsl:param name="nodeID"/>
  <xsl:param name="isFirst"/>
  <xsl:param name="subtopicNodes"/>
  <xsl:param name="title"/>
    <xsl:choose>
        <!-- OXYGEN PATCH START EXM-26443 - avoid elements <tocitem text="">. -->
        <xsl:when test="(@toc and translate(@toc, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')='no')
              		or (@processing-role='resource-only')
              		or contains(@class, ' bookmap/frontmatter ') 
              		or contains(@class, ' bookmap/backmatter ')
              		or contains(@class, ' bookmap/booklists ') 
              		or contains(@class, ' bookmap/toc ')
              		or contains(@class, ' bookmap/indexlist ')
              		or contains(@class, ' bookmap/part ')">
        <!-- OXYGEN PATCH END EXM-26443 - avoid elements <tocitem text="">. -->
        <!-- <xsl:variable name="results">  -->
        <!-- Process children nodes. -->
    <!--     <xsl:apply-templates select="$subtopicNodes"/> -->
        <xsl:apply-templates select="./*[contains(@class, ' map/topicref ')]"/>
        <!-- </xsl:variable>  -->
        <!-- <xsl:text/>  -->
      </xsl:when>
      <xsl:when test="$title">
        <!-- OXYGEN PATCH START EXM-21663 - must normalize the text from "text" attribute. -->
        <tocitem text="{normalize-space($title)}">
        <!-- OXYGEN PATCH END EXM-21663 - must normalize the text from "text" attribute. -->
            <xsl:if test="$outroot and $outroot!=''">
            <xsl:attribute name="target">
              <xsl:value-of select="translate($outroot, '\/.', '___')"/>
            </xsl:attribute>
          </xsl:if>
          <xsl:apply-templates select="$subtopicNodes"/>
        </tocitem>
      </xsl:when>
      <xsl:otherwise>
        <xsl:apply-templates select="$subtopicNodes"/>
      </xsl:otherwise>
    </xsl:choose>
</xsl:template>

<!-- do nothing when meeting with reltable -->
<xsl:template match="*[contains(@class,' map/reltable ')]"/>

</xsl:stylesheet>
