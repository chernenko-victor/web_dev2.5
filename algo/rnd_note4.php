<?php
//rnd_note4.php

include_once("../inc/util.class.php");
include_once("../inc/XML2Assoc.class.php");
include_once("./inc/rnd4.class.php");

$uUtil = new Util();
$aParamArr = array();
$alRnd = new Rnd($aParamArr);
//echo "<br /><br />OUT = ".$alRnd->GetResXML();
//echo $alRnd->GetResXML(false, /* $iFixedPitch */ false, /* $fFixedDur */ 0.25);
echo $alRnd->GetResXML(false, /* $iFixedPitch */ false, /* $fFixedDur */ 1.0);
unset($alRnd);

unset($xaParser);
?>