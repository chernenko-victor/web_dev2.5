<?php
//t_assoc2musicXML.class.php

include_once("./inc/assoc2musicXML.class.php");

$a2mxNew = new Assoc2MusicXML();
echo $a2mxNew->GetMessage(/* Encode Entities? */ false);
unset($a2mxNew);
?>