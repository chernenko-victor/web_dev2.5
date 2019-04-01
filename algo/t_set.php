<?php
include_once("C:/Program Files/wamp/www/dev2.3/algo/inc/set.class.php");

/*
$aDistanceStepPitch1 = array 
(
		array (1, "==", 2)
	  //, array (2, ">=", 3)
	  //, array (2, "<=", 4)
	  //, array (3, ">", 5)
);
*/

$aDistanceStepPitch1 = array 
(
		array (5, ">", 2)
	  //, array (2, ">=", 3)
	  //, array (2, "<=", 4)
	  //, array (3, ">", 5)
);

$aStepPitch = Array
(
  //  Array(1, "==", 2, "<=") // м2 меньше или равно 2 штуки
      Array(2, "==", 2, ">=") // б2 больше или равно 2 штуки
  //, Array(2, ">=", 3, "==") // от б2 до м3 3 штуки
  //, Array(3, "<=", 3, "==") // от б2 до м3 3 штуки
  //, Array(3, ">", 2, ">=") // больше м3 не меньше 2-х штук
);

/*
$aStepPitch = Array
(
     Array(1, "==", 2, "<=")
  ,  Array(2, ">=", 3, "==")
  ,	 Array(3, "<=", 3, "==")
  ,	 Array(3, ">", 2, ">=")
);
*/

/*
$aStepPitch = Array
(
     Array(1, "==", 2, "<=")
  ,  Array(2, ">=", 6, "==")
  ,	 Array(3, "<=", 3, "==")
  ,	 Array(3, ">", 2, ">=")
);
*/


$stSet1 = new Set
(
      /* $iCardinalityNew = */ 3
	, /* $aStepPitchNew = */ $aStepPitch
	, /* $aDistanceStepPitchNew = */ $aDistanceStepPitch1
	, /* $iMinLenNSubsetNew = */ 3
	, /* $iMaxLenNSubsetNew = */ 5
	, /* $iMinVolNSubsetNew = */ 1
	, /* $iMaxVolNSubsetNew = */ 6
);

/*
$aNSubset[] = 3;
$aNSubset[] = 3;
$aNSubset[] = 5;
$aNSubset[] = 2;

$aNSubsetCnt  = count($aNSubset);
//echo factLoop($aNSubsetCnt);
for($i = 0; $i < $mMath1->factLoop($aNSubsetCnt); $i++)
{	  
  if((count($aNSubset)==1)&&(!$aNSubset[0]))
  {
    //echo "__".$aNSubset[0]."__<br />";
    break;
  }
  echo "Number = $i<br />";
  print_r($aNSubset);
  //echo "count(\$aNSubset) = ".count($aNSubset)."<br />";
  echo "<br /><br />";
  $aNSubset = $mMath1->narayana($aNSubset, $aNSubsetCnt);
}
*/

$stSet1->GetResult();

unset($stSet1);
?>