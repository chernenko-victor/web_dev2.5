<?php
echo "before<br />";
include_once("xml_attrib_construct.class.php");

$XAC = new XmlAttribConstruct("score-partwise");

$aMusicXml = array();

$aMusicXml["part-list"] = array(
    "score-part1" => ""
  , "score-part2" => ""
);

/*
how can we push into array elements with identical name?
*/

unset($XAC);

echo "after<br />";
?>