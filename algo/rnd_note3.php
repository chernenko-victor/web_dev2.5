<?php
//echo "before<br />";
//rnd_note2.php

include_once("../inc/util.class.php");
include_once("../inc/XML2Assoc.class.php");
include_once("./inc/rnd3.class.php");

$uUtil = new Util();


/*
$xaParser = new Xml2Assoc();
//$aParamArr = $xaParser->parseString($uUtil->DecodeEntit($_POST["serilized_xml"]), true);
$aParamArr = $xaParser->parseString($_POST["serilized_xml"], true);
//echo "<br />\$aParamArr<br />";
//print_r($aParamArr);
//echo "<br /><br />";
*/

$aParamArr = array();
$alRnd = new Rnd($aParamArr);
//echo "<br /><br />OUT = ".$alRnd->GetResXML();
//echo $alRnd->GetResXML(false, /* $iFixedPitch */ false, /* $fFixedDur */ 0.25);
echo $alRnd->GetResXML(false, /* $iFixedPitch */ false, /* $fFixedDur */ 1.0);
unset($alRnd);

unset($xaParser);
//echo "after<br />";
?>