<?php
$afDur = array();
$afDur["f32thDur"] = (float)1/(float)32;
$afDur["f16thDur"] = (float)1/(float)16;
$afDur["f16thDotDur"] = $afDur["f16thDur"] + $afDur["f32thDur"]; // 1/16+1/32
$afDur["f8thDur"] = (float)1/(float)8;
$afDur["f8thDotDur"] = $afDur["f8thDur"] + $afDur["f16thDur"]; // 1/8+1/16
$afDur["fQuartDur"] = (float)1/(float)4;
$afDur["fQuartDotDur"] = $afDur["fQuartDur"] + $afDur["f8thDur"] ; // 1/4+1/8
$afDur["fHalfDur"] = (float)1/(float)2;
$afDur["fHalfDotDur"] = $afDur["fHalfDur"] + $afDur["fQuartDur"]; // 1/2+1/4
$afDur["fWholeDur"] = (float)1;
?>