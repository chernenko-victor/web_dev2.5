<?php
//group_change.php

//include_once("../inc/util.class.php");
include_once("../inc/XML2Assoc.class.php");
include_once("./inc/group_change.class.php");

//$uUtil = new Util();

//$uUtil->ShowVar(/* sModuleName */ "/algo/rnd_note.php", /* sFuncName */ "/main/", /* sVarName */ "\$_POST[\"serilized_xml\"]", /* mVar */ $_POST["serilized_xml"]);

$xaParser = new Xml2Assoc();
//$aParamArr = $xaParser->parseString($uUtil->DecodeEntit($_POST["serilized_xml"]), true);
$aParamArr = $xaParser->parseString($_POST["serilized_xml"], true);
//echo "<br />\$aParamArr<br />";
//print_r($aParamArr);
//echo "<br /><br />";

$alGrChange = new GroupChange($aParamArr);
//echo "<br /><br />OUT = ".$alRnd->GetResXML();
//echo $alGrChange->GetResXML(false, /* $iFixedPitch */ 60);
echo $alGrChange->GetResXMLNew(false);
unset($alGrChange);

unset($xaParser);
?>