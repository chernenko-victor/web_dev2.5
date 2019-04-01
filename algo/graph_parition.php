<?php
//graph_parition.php

//include_once("../inc/util.class.php");

//$uUtil = new Util();

//echo "<br /><br />\$_POST[\"serilized_xml\"] = ".$_POST["serilized_xml"]."<br /><br />";

/*
<partition type="graph">
<forms>
  <form type="line">
    <point>
      <x>10</x>
      <y>100</y>
    </point>
    <point>
      <x>100</x>
      <y>13</y>
    </point>
  <form>
  <form type="line">
    <point>
      <x>1</x>
      <y>123</y>
    </point>
    <point>
      <x>10</x>
      <y>130</y>
    </point>
    <point>
      <x>100</x>
      <y>253</y>
    </point>
    <point>
      <x>600</x>
      <y>800</y>
    </point>
    <point>
      <x>1</x>
      <y>123</y>
    </point>
  <form>
  <form type="poly">
    <item>
      <coef>100</coef>
      <deg>0</deg>
    </item>
    <item>
      <coef>3</coef>
      <deg>5</deg>
    </item>
    <item>
      <coef>-17</coef>
      <deg>7</deg>
    </item>
    <argSpace>
      <point type="begin">-5.008</point>
      <point type="end">-5.008</point>
      <point type="begin">181.0</point>
      <point type="end">1810.0</point>
      <point type="begin">45.54</point>
      <point type="end">3020.9</point>
    <argSpace>
  <form>
</forms>
</partition>
*/

$aCoord = array();
for($i = 0; $i < 8; $i++)
{
  $aCoord[] = rand(0, 1000);
}

$XmlConstruct = new XMLWriter();

$XmlConstruct->openMemory();
$XmlConstruct->setIndent(true);
$XmlConstruct->setIndentString(' ');
$XmlConstruct->startDocument('1.0', 'UTF-8');

$XmlConstruct->startElement("forms");

$XmlConstruct->startElement("form");
  $XmlConstruct->startElement("point");
    $XmlConstruct->startAttribute("type");
      $XmlConstruct->text("line");
    $XmlConstruct->endAttribute();
    $XmlConstruct->startElement("x");
      $XmlConstruct->text($aCoord[0]);
    $XmlConstruct->endElement();
    $XmlConstruct->startElement("y");
      $XmlConstruct->text($aCoord[1]);
    $XmlConstruct->endElement();
  $XmlConstruct->endElement();
  $XmlConstruct->startElement("point");
    $XmlConstruct->startElement("x");
      $XmlConstruct->text($aCoord[2]);
    $XmlConstruct->endElement();
    $XmlConstruct->startElement("y");
      $XmlConstruct->text($aCoord[3]);
    $XmlConstruct->endElement();
  $XmlConstruct->endElement();
$XmlConstruct->endElement();

$XmlConstruct->startElement("form");
  $XmlConstruct->startElement("point");
    $XmlConstruct->startAttribute("type");
      $XmlConstruct->text("line");
    $XmlConstruct->endAttribute();
    $XmlConstruct->startElement("x");
      $XmlConstruct->text($aCoord[4]);
    $XmlConstruct->endElement();
    $XmlConstruct->startElement("y");
      $XmlConstruct->text($aCoord[5]);
    $XmlConstruct->endElement();
  $XmlConstruct->endElement();
  $XmlConstruct->startElement("point");
    $XmlConstruct->startElement("x");
      $XmlConstruct->text($aCoord[6]);
    $XmlConstruct->endElement();
    $XmlConstruct->startElement("y");
      $XmlConstruct->text($aCoord[7]);
    $XmlConstruct->endElement();
  $XmlConstruct->endElement();
$XmlConstruct->endElement();

$XmlConstruct->endDocument();
//header('Content-type: text/xml');
$out = $XmlConstruct->outputMemory();

/*
$patterns = array();
$patterns[0] = '/</';
$patterns[1] = '/>/';
$replacements = array();
$replacements[2] = '&lt;';
$replacements[1] = '&gt;';
echo preg_replace($patterns, $replacements, $out);
*/
//echo $uUtil->EncodeEntit($out);
echo $out;

/*
надо сделать перевод из массива

$element = array
{
    1 => array
    (
        "name" => "form"
      , "text" => "new form"
      , "atribute" => array
      (
          "type" => "line"
        , "mode" => "standart"
      )
    )
  , 2 => array
    (
        "name" => "form"
      , "text" => array
      (
          1 => array
          (
            "name" => "point"
            // ... 
          )
      )
      , "atribute" => array
      (
          "type" => "line"
        , "mode" => "standart"
      )
    )
}

хотя впрочем это слишком сложно, оставим просто в форме $XmlConstruct->startElement("form");
*/

/*
echo "<strong>ALGO::graph parition</strong><br />";

$aCoord = array();
for($i = 0; $i < 4; $i++)
{
  $aCoord[] = rand(0, 1000);
}


$string = "<partition type=\"graph\">
<forms>
  <form type=\"line\">
    <point>
      <x>".$aCoord[0]."</x>
      <y>".$aCoord[1]."</y>
    </point>
    <point>
      <x>".$aCoord[2]."</x>
      <y>".$aCoord[3]."</y>
    </point>
  <form>
</forms>
</partition>";
$patterns = array();
$patterns[0] = '/</';
$patterns[1] = '/>/';
$replacements = array();
$replacements[2] = '&lt;';
$replacements[1] = '&gt;';
echo preg_replace($patterns, $replacements, $string);
*/
?>