<?php
include_once("algo.class.php");

class ScalarRnd extends Algo
{
  protected $xMaxVal;
  protected $iDataType;
  protected $xMinVal;
  protected $iDistribFuncType;
  
  function ScalarRnd($aParameter) 
  {
    //algoType
		//711:Uniform distribution
		//712:Linear distribution
		//713:Normal (gaussian) distribution
  }
  
  public function SetDataType($iDataType) {}
  public function GetDataType($iDataType) {}
  
  public function SetMaxVal($xMaxVal) {}
  public function GetMaxVal($xMaxVal) {}
  
  public function SetMinVal($xMinVal) {}
  public function GetMinVal($xMinVal) {}
  
  public function GetValue() 
  {
    echo "test".time();
  }
}
?>