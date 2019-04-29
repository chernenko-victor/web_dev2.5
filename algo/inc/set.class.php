<?php

$INC_DIR = $_SERVER["DOCUMENT_ROOT"]."\\dev2.5\\inc\\";

include_once("algo.class.php");
//include_once("C:/Program Files/wamp/www/dev2.3/inc/math.inc.php");
include_once($INC_DIR."math.inc.php");
//include_once("C:/Program Files/wamp/www/dev2.3/algo/inc/stochastic.inc.php");
include_once("stochastic.class.php");

class Set extends Algo
{
  private $iCardinality;
  private $aStepPitch; 
  /* 
  (
    (pitch1, p_condition1, count1, c_condition1), 
	(pitch2, p_condition2, count2, c_condition2),
	...
  )

  means
  
  (count(intervals[i] p_condition1 pitch1)) c_condition1 count1
  
  e.g.
  
  (
    (1, "==", 2, "<="), 
	(2, ">=", 3, "=="),
	(3, "<=", 3, "=="),
	(3, ">", 2, ">="),
  )
  м2 меньше или равно 2 штуки, от б2 до м3 3 штуки, больше м3 не меньше 2-х штук
  
  */
  private $aDistanceStepPitch; 
  /* 
  (
    (pitch1, p_condition1, count1, c_condition1), 
	(pitch2, p_condition2, count2, c_condition2),
	...
  )
  
  e.g.
  
  (
    (1, "=", 2), 
	(2, ">=", 3),
	(2, "<=", 4),
	(3, ">", 5),
  )
  
  расстояние м/у ступенями м2 равно двум ступеням, расстояния м/у б2 - 3 или 4 ступени, расстояние м/у м3 - больше 5
  */
  
  //2DO: insert 5th parameter foe logical combination like AND OR
  
  private $mMath1;
  private $aSubst; //множество подстановок $aS[]
  private $iMinLenNSubset;
  private $iMaxLenNSubset;
  private $iMinVolNSubset;
  private $iMaxVolNSubset;
  private $st1;
  private $aStepPitchFiltered; /* результат фильтрации по Кол-ву указанных высот ступеней */
  private $aStepDistanceFiltered; /* результат фильтрации по расстоянию между одинаковыми высотами ступеней */
  
  public function /* Set */ __construct
  (
	  $iCardinalityNew = 3
	, $aStepPitchNew = array()
	, $aDistanceStepPitchNew = array()
	, $iMinLenNSubsetNew = 3
	, $iMaxLenNSubsetNew = 5
	, $iMinVolNSubsetNew = 8
	, $iMaxVolNSubsetNew = 11
  )
  {
    $this->iCardinality = $iCardinalityNew;
    $this->aStepPitch = $aStepPitchNew; 
	$this->aDistanceStepPitch = $aDistanceStepPitchNew; 
	$this->mMath1 = new Math();
	$this->aSubst = array();
	$this->iMinLenNSubset = $iMinLenNSubsetNew;
	$this->iMaxLenNSubset = $iMaxLenNSubsetNew;
	$this->iMinVolNSubset = $iMinVolNSubsetNew;
	$this->iMaxVolNSubset = $iMaxVolNSubsetNew;
	$this->st1 = new Stochastic();
	$this->aStepPitchFiltered = array();
	$this->aStepDistanceFiltered = array();
  }
  
  public function /* ~Set */ __destruct()
  {
    unset($this->aStepDistanceFiltered);
    unset($this->aStepPitchFiltered);
    unset($this->st1);
    unset($this->iMaxVolNSubset);
    unset($this->iMinVolNSubset);
    unset($this->iMaxLenNSubset);
    unset($this->iMinLenNSubset);
    unset($this->aSubst);
	unset($this->mMath1); 
	unset($this->aDistanceStepPitch); 
	unset($this->aStepPitch); 
	unset($this->iCardinality);
  }
  
  public function GetStepPitchFiltered()
  {
    $aRes = array();
	if(isset($this->aStepPitchFiltered) && count($this->aStepPitchFiltered))
	{
		$aRes = $this->aStepPitchFiltered;
	}
    return $aRes;
  }
  
  public function GetStepDistanceFiltered()
  {
    $aRes = array();
	if(isset($this->aStepDistanceFiltered))
	{
	  $aRes = $this->aStepDistanceFiltered;
	}
    return $aRes;
  }
  
  public function GetResult()
  {
    //echo "test".time();
    $this->GetReplacementsWithRules();
	if(count($this->aSubst))
	{
		echo "All sets<br />";
		print_r($this->aSubst);
	}
	
	if(count($this->aStepPitch))
	{
		echo "<br />=================================<br />Pitch Step Filter<br /><br />";
		echo "Rules";
		print_r($this->aStepPitch);
		echo ")";
		echo "<br />";
		echo "(pitch1, p_condition1, count1, c_condition1), <br />
	(pitch2, p_condition2, count2, c_condition2),<br />
	...<br />
  )<br />
<br />
  means<br />
  <br />
  (count(intervals[i] p_condition1 pitch1)) c_condition1 count1<br />";
		echo "<br />=================================<br />Sets Filtered by Pitch Step";
		if(isset($this->aStepPitchFiltered) && count($this->aStepPitchFiltered))
		{
			print_r($this->aStepPitchFiltered);
		}
		else
		{
			echo "empty";
		}
	}
	else
	{
		echo "<br />=================================<br />No Pitch Step Filter conditions are set<br />";
	}
	
	if(count($this->aDistanceStepPitch))
	{
		echo "<br />=================================<br />Distance Pitch Step Filter<br /><br />";
		echo "Rules";
		print_r($this->aDistanceStepPitch);
		echo ")";
		echo "<br />";
		/*
		echo "(<br />
		(pitch1, c_condition1, count1), <br />
		(pitch2, c_condition2, count2),<br />
		...<br />
	  )<br />
	  <br />
	  e.g.<br />
	  <br />
	  (<br />
		(1, "=", 2), <br />
		(2, ">=", 3),<br />
		(2, "<=", 4),<br />
		(3, ">", 5),<br />
	  )<br />
	  <br />
	  расстояние м/у ступенями м2 равно двум ступеням, расстояния м/у б2 - 3 или 4 ступени, расстояние м/у м3 - больше 5<br />";
	  */
		echo "<br />=================================<br />Sets Filtered by Distance Pitch Step";
		if(isset($this->aStepDistanceFiltered) && count($this->aStepDistanceFiltered))
		{
			print_r($this->aStepDistanceFiltered);
		}
		else
		{
			echo "empty";
		}
	}
	else
	{
		echo "<br />=================================<br />No Distance Pitch Step Filter conditions are set<br />";
	}
  }
  
  //2do: realize c:\book\music\composition\algorithmic\::Композиция как сеть объектов.doc::CfS  
  
  /* Мощность моножества */
  private function SetCardinality($iCardinalityNew)
  {
    //echo "test".time();
  }
  
  private function GetCardinality()
  {
    //echo "test".time();
  }
  
  /* Кол-во указанных высот ступеней */
  private function SetStepPitch($aStepPitchNew)
  {
    //...
  }
  
  /* Расстояния от ступени заданного шага высоты до след. с таким же шагом */
  private function SetDistanceStepPitch($aDistanceStepPitchNew)
  {
    //...
  }
  
  /* Подстановки с правилами */
  public function GetReplacementsWithRules()
  {
      /*
	  $iMinLenNSubset = 3;
	  //$iMaxLenNSubset = 8;
	  $iMaxLenNSubset = 5;
	  $iMinVolNSubset = 8;
	  $iMaxVolNSubset = 11;
	  */
	  
	  //множество подстановок (подмножесто натуральных чисел)
	  
	  //подмножесто натуральных чисел $aNSubset[]
	  //0 - неизменность, 1 - движение на минимальный "интервал" вверх
	  
	  //$iMaxLenNSubset >= len($aNSubset[]) >= $iMinLenNSubset
	  //$iMaxVolNSubset >= each($aNSubset[])) >= $iMaxVolNSubset >= 0
	  
	  /* ===========================================================================
	  ========================	Generate Number Set	================================
	  =========================================================================== */
	  $aNSubset[] = array();
	 
	  $iCount = $this->st1->get_number($this->iMinLenNSubset, $this->iMaxLenNSubset);
	  for($i = 0; $i < $iCount; $i++)
	  {
	    $aNSubset[$i] = $this->st1->get_number($this->iMinVolNSubset, $this->iMaxVolNSubset);		
	  }
	  asort($aNSubset);
	  $aNSubset = array_values($aNSubset);
	  //print_r($aNSubset);
	  	  
	  /* ===========================================================================
	  ========================	test count pitch steps once	========================
	  ===============	(because steps are constant after replacements)	============
	  =========================================================================== */
	  
	  $bIfStepPitchFiltered = false;
	  if(count($this->aStepPitch))
	  {
		  if($this->TestStepPitch($aNSubset))
		  {
			$bIfStepPitchFiltered = true;	
		  }
	  }	 
	  
	  /* ===========================================================================
	  ================================	Replace	====================================
	  =========================================================================== */
	  
	  $iNSubsetCnt = count($aNSubset);
	  $iSetCnt = $this->mMath1->factLoop($iNSubsetCnt);
      //echo $iSetCnt;
	  for($i = 0; $i < $iSetCnt; $i++)
	  {	  
	      //echo "<br />count(\$aNSubset) = ".count($aNSubset)."<br />";
	      //echo "<br />\$aNSubset[0] = __".$aNSubset[0]."__<br />";
		  if((count($aNSubset)==1)&&(!$aNSubset[0]))
		  {
			//echo "__".$aNSubset[0]."__<br />";
			break;
		  }
		  //echo "Number = $i<br />";
		  //print_r($aNSubset);
		  $this->aSubst[] = $aNSubset;
		  //echo "count(\$aNSubset) = ".count($aNSubset)."<br />";
		  //echo "<br /><br />";
		  $aNSubset = $this->mMath1->narayana($aNSubset, $iNSubsetCnt);
	  }
	  
	  //foreach($this->aSubst as $k => $v)
	  //{
	  //    echo "Number = $k<br />";
	  //	print_r($v);
	  //    echo "<br /><br />";
	  //}
	  
	  
	  unset($this->aStepPitchFiltered);
	  if($bIfStepPitchFiltered)
	  {
	    $this->aStepPitchFiltered = $this->aSubst; //if test posityve simply $this->aStepPitchFiltered = $this->aSubst;
	  }
	  	
	  /* ===========================================================================
	  ===========================	test distance steps ============================
	  ======================	(for each subset separately)	====================
	  =========================================================================== */
	  
	  if(count($this->aSubst))
	  {
		  if($iDistanceStepPitchCnt = count($this->aDistanceStepPitch))
		  {
			  unset($this->aStepDistanceFiltered);
			  foreach($this->aSubst as $v)
			  {
				  //$aStepPitchCnt4EachCond = array();
				  //if($iDistanceStepPitchCnt)
				  //{		
				    //$bIsPass = $this->TestDistanceStepPitch($v, $iMaxStepCnt);
				    $bIsPass = $this->TestDistanceStepPitch($v);
					
					/*
					echo "<br />\$iMaxStepCnt = $iMaxStepCnt<br />";
										
					if($iMaxStepCnt<=1)
					{
					  break;
					}
					*/
					
					if($bIsPass)
					{
					  $this->aStepDistanceFiltered[]=$v; 
					}
				  //}
			  }
		  }
	  }
	  
	  //echo "<br />======================================<br />\$this->aStepDistanceFiltered<br />";
	  //if(isset($this->aStepDistanceFiltered) && count($this->aStepDistanceFiltered))
	  //{
	  //	print_r($this->aStepDistanceFiltered);
	  //}  
	  //else
	  //{
	  //  echo "EMPTY";
	  //}
	  //echo "<br />======================================<br />";
	  return $this->aSubst[0];
  }
  
  /*
  private function TestStepPitch($aNSubset) 
	{  
	  $bTest = true;
	  foreach($aStepPitch)
	  {
		count[i] = count_in_arr($aStepPitch[i][1]." ".$aStepPitch[i][0], $aNSubset) // = 1 | > 3
		{
		  //$aStepPitch[i][1]." ".$aStepPitch[i][0]
		}
		//count[i] 
		cond[i] = " ".$aStepPitch[i][3]." ".$aStepPitch[i][2];
		//if(eval('return($total'.$i.');')) http://php.net/manual/ru/function.eval.php
		if(!eval('return('.count[i].cond[i].');')) http://php.net/manual/ru/function.eval.php //5 <= 2 | 5 = 3 | 5 >= 2
		{
		  $bTest = false;
		  break;
		}
	  }
	  return $bTest;
	}
	//DONE: insert TestStepPitch from C:\Program Files\wamp\www\test\t_eval.php
  */
  
  private function /* int */ count_in_arr(/* String */ $sCondition, /* Array */ $aNSubset) // = 1 | > 3
  {
	  //$aStepPitch[i][1]." ".$aStepPitch[i][0]
	  $iRet = 0;
	  foreach($aNSubset as $val)
	  {
		//echo "\$val \$sCondition : $val $sCondition<br />";
		if(eval("return ($val $sCondition);")) 
		{ 
		  $iRet++;
		}
	  }  
	  return $iRet;
  }

  private function TestStepPitch($aNSubset) 
  {  
	  $bTest = true;
	  foreach($this->aStepPitch as $val)
	  {
		$iCnt = $this->count_in_arr($val[1]." ".$val[0], $aNSubset);
		$sCond = " ".$val[3]." ".$val[2];
		echo "\$iCnt \$sCond $iCnt $sCond<br />";
		//if(eval('return($total'.$i.');')) http://php.net/manual/ru/function.eval.php
		if(!eval("return ($iCnt $sCond);")) //5 <= 2 | 5 = 3 | 5 >= 2 "return('.count[i].cond[i].');'
		{
		  $bTest = false;
		  break;
		}
	  }
	  return $bTest;
  }
  
  //private function /* bool */ TestDistanceStepPitch($aNSubset, &$iMaxStepCnt) 
  private function /* bool */ TestDistanceStepPitch($aNSubset) 
  { 
	  //echo "<br />================= TestDistanceStepPitch ========================== <br />";
	  
	  //echo "<br />for test purposes fixed \$this->aSubst<br />";
					
	  //no step 1
	  //$aNSubset = array(0 => 2, 1 => 3, 2 => 4, 3 => 6);
	  
	  //step 1 = 4
	  //$aNSubset = array(0 => 1, 1 => 3, 2 => 4, 3 => 6);
	  
	  //step 1 = 2
	  //$aNSubset = array(0 => 1, 1 => 3, 2 => 1, 3 => 6);
	  
	  //step 1 = 1
	  //$aNSubset = array(0 => 1, 1 => 3, 2 => 1, 3 => 1);
	  
	  
	  //$aNSubset = array(0 => 3, 1 => 5, 2 => 5);
	  //$aNSubset = array(0 => 3, 1 => 5, 2 => 1, 3 => 2, 4 => 5);
					
	  /* bool */ $bRes = false;
	  /* 
	  (
		(pitch1, c_condition1, count1), 
		(pitch2, c_condition2, count2),
		...
	  )
	  
	  e.g.
	  
	  (
		(1, "=", 2), 
		(2, ">=", 3),
		(2, "<=", 4),
		(3, ">", 5),
	  )
	  
	  расстояние м/у ступенями м2 равно двум ступеням, расстояния м/у б2 - 3 или 4 ступени, расстояние м/у м3 - больше 5
	  
	  if(eval("return ($val $sCondition);")) 
		{ 
		  $iRet++;
		}
	  */
	  
	  //echo "<br />aNSubset array:<br />";
	  //print_r($aNSubset);
	  //echo "<br />";
	  
	  //echo "<br />\$this->aDistanceStepPitch array:<br />";
	  //print_r($this->aDistanceStepPitch);
	  //echo "<br />";
	  
	  $aStep = array();
	  $aStepCnt = 0;
	  
	  $aRes = array();
	  $aNSubsetLen = count($aNSubset);
	  
	  foreach($this->aDistanceStepPitch as $k => $v) 
	  {
	    
		$sCondition = $v[1]." ".$v[2]; // current condition
				
		//echo "<br /> current step = ".$v[0]."<br />"; //$v[0] = current step
		unset($aStep);	
		$i = 1;
		foreach($aNSubset as $iCurIndx => $iCurStep)
		{
		  //find $v[0] in $aNSubset => $aStep
		  //take indices
		  //0, 3, 7, 100
		  if($iCurStep == $v[0])
		  {
		    $aStep[$i] = $iCurIndx;
			$i++;
		  }
		}
		
		if(!isset($aStep))
		{
		  $aStepCount = 0;
		}
		else
		{
		  $aStepCount = count($aStep);
		}
		
		
		if($aStepCount>0)
		{
		  //extend array
		  if($aStepCount==1)
		  {
		    //first==last
			$aStep[0] = $aStep[1] - $aNSubsetLen;
			$aStep[2] = $aStep[1] + $aNSubsetLen;
		  } 
		  else
		  {
		    $aStep[0] = $aStep[$aStepCount] - $aNSubsetLen; //last-count 
			$aStep[$aStepCount+1] = $aStep[1] + $aNSubsetLen; //first+count
		  }
		}
		
		//if(isset($aStep))
		//{
		//	echo "<br />\$aStep array:<br />";
		//	print_r($aStep);
		//	echo "<br />";
		//}
		//else
		//{
		//	echo "<br />no \$aStep array:<br />";
		//}
		
		//echo "<br />\$aRes[] = ";
		//echo $aRes[] = $this->TestDestanceOne($v[1], $v[2], $aStep);		
		//echo"<br />";
		
		/*
		if(isset($aStep) && ($aStepCnt = count($aStep)>1))
		{		  
		  //cycle it
		  //$aNewInd = 0, 3, 7, 100, [last]+[fst]+1 wut??? 
		  $aStep[] = $aStep[$aStepCnt-1] + $aStep[0] + 1;
		
		  echo "<br />step indices array:<br />";
		  print_r($aStep);
		  echo "<br />";
		
		  //if(foreach ($i, $i+1) {$aNewInd[$i+1]-$aNewInd[$i] $sCondition}) OK
		  //else return false;
		  
		  
		}
		else
		{
		  echo "<br />this step found less or equal 1 times<br />";
		  $aStepCnt = 0;
		}
		*/
	  }
	  
	  //echo "<br />aRes array:<br />";
	  //print_r($aRes);
	  //echo "<br />";
	  
	  if(isset($aRes) && is_array($aRes) && count($aRes))
	  {
		  $sTest = implode(" && ", $aRes);
		  //echo "<br />\$sTest = $sTest<br />";
		  if(eval("return ($sTest);")) 
		  { 
			$bRes = true;
		  }
	  }
	  return $bRes;
  }
  
  private function /* string */ TestDestanceOne($sConditionOp, $iCount, &$aStep)
  {
  
    echo "<br />================= TestDestanceOne ========================== <br />";
	
    /* bool */ $sRes = "true";
	$aStepCount = count($aStep);
	//$sCondition = $v[1]." ".$v[2]; // current condition
	$sCondition = $sConditionOp." ".$iCount; // current condition
	
	if(($sConditionOp == "==")||($sConditionOp == "<")||($sConditionOp == "<="))
	{
	  if($aStepCount<=1) //there no chance $aStepCount==1
	  {
	    return "false";
	  }
	}
	else if(($sConditionOp == "!=")||($sConditionOp == ">")||($sConditionOp == ">="))
	{
	  if($aStepCount<=1) //there no chance $aStepCount==1
	  {
	    return "true";
	  }
	}
	for($i = 1; $i < $aStepCount; $i++)
	{
	  $iCurrCnt = $aStep[$i] - $aStep[$i - 1];
	  echo "<br />return ($iCurrCnt $sCondition);<br />";
	  if(!eval("return ($iCurrCnt $sCondition);")) 
	  { 
	    $sRes = "false";
		break;
	  }
	}
	
	echo "<br />\$sRes = $sRes<br />";
	
    return $sRes;
  }
}
?>