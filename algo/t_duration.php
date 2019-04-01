<?php
include_once("./inc/duration.class.php");

$duOne = new Duration();

$duOne->__set_dBegin(1.1);
$duOne->__set_dLen(0.33);
echo "sId = ".$duOne->__get_sId()."<br />";
echo "dBegin = ".$duOne->__get_dBegin()."<br />";
echo "dLen = ".$duOne->__get_dLen()."<br />";

unset($duOne);
?>