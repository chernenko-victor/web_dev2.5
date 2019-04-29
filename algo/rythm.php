<?php
//rythm.php
//echo "ALGO::rythm";
include_once("../inc/util.class.php");
include_once("../inc/XML2Assoc.class.php");
//include_once("./inc/rnd.class.php");
//include_once("C:/Program Files/wamp/www/dev2.3/algo/inc/rnd_rythm.class.php");
include_once("./inc/rnd_rythm.class.php");

$uUtil = new Util();

//$uUtil->ShowVar(/* sModuleName */ "/algo/rnd_note.php", /* sFuncName */ "/main/", /* sVarName */ "\$_POST[\"serilized_xml\"]", /* mVar */ $_POST["serilized_xml"]);

$xaParser = new Xml2Assoc();
//$aParamArr = $xaParser->parseString($uUtil->DecodeEntit($_POST["serilized_xml"]), true);
$aParamArr = $xaParser->parseString($_POST["serilized_xml"], true);
//echo "<br />\$aParamArr<br />";
//print_r($aParamArr);
//echo "<br /><br />";

//$alRnd = new Rnd($aParamArr);
$alRnd = new RndRythm($aParamArr);
//echo "<br /><br />OUT = ".$alRnd->GetResXML();
echo $alRnd->GetResXML(false, /* $iFixedPitch */ 60);
unset($alRnd);

unset($xaParser);
?>