<?php
//rnd_note.php

include_once("../inc/util.class.php");
include_once("../inc/XML2Assoc.class.php");
include_once("./inc/rnd.class.php");

$uUtil = new Util();

//$uUtil->ShowVar(/* sModuleName */ "/algo/rnd_note.php", /* sFuncName */ "/main/", /* sVarName */ "\$_POST[\"serilized_xml\"]", /* mVar */ $_POST["serilized_xml"]);

$xaParser = new Xml2Assoc();
//$aParamArr = $xaParser->parseString($uUtil->DecodeEntit($_POST["serilized_xml"]), true);
$aParamArr = $xaParser->parseString($_POST["serilized_xml"], true);
//echo "<br />\$aParamArr<br />";
//print_r($aParamArr);
//echo "<br /><br />";

$alRnd = new Rnd($aParamArr);
//echo "<br /><br />OUT = ".$alRnd->GetResXML();
//echo $alRnd->GetResXML(false, /* $iFixedPitch */ false, /* $fFixedDur */ 0.25);
echo $alRnd->GetResXML(false, /* $iFixedPitch */ false, /* $fFixedDur */ 1.0);
unset($alRnd);

unset($xaParser);
?>