<?php
//include_once("C:/Program Files/wamp/www/dev2.3/algo/inc/set.class.php");
include_once("./inc/set.class.php");

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
  //  interval for condition, sign of interval condition, number for interval count condition, sing for count interval count condition
  //  Array(1, "==", 2, "<=") // интервалов м2 меньше или равно 2 штуки
      Array(2, "==", 2, ">=") // интервалов б2 больше или равно 2 штуки
  //, Array(2, ">=", 3, "==") // интервалов от б2 до м3 3 штуки
  //, Array(3, "<=", 3, "==") // интервалов от б2 до м3 3 штуки
  //, Array(3, ">", 2, ">=") // интервалов больше м3 не меньше 2-х штук
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


//$stSet1->GetResult();
//
//$a = $stSet1->GetStepPitchFiltered();
//echo "+++++++++++++++++++++++";
//print_r($a);


$stSet1->GetReplacementsWithRules();

echo "<br />================================= StepPitchFiltered Array ================================= <br />";
print_r($stSet1->GetStepPitchFiltered());

echo "<br />================================= GetStepDistanceFiltered Array ================================= <br />";
print_r($stSet1->GetStepDistanceFiltered());



unset($stSet1);
?>