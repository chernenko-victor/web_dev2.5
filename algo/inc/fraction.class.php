<?php
include_once("number_theory.class.php");

class Fraction
{	
	private $iNumerator;
	private $iDenominator;
	private /* NumberTheory */ $ntEucl;
	private $iGCD;
	
  public function __construct($iNumeratorNew, $iDenominatorNew)
  {
    $this->iNumerator = $iNumeratorNew;
	$this->iDenominator = $iDenominatorNew;
	$this->ntEucl = new NumberTheory();
	$aTrace = array();
	$this->iGCD = $this->ntEucl->EucleadGCD(abs($this->iNumerator), abs($this->iDenominator), $aTrace);
  }
  
  public function __destruct()
  {
	unset($this->iGCD);
	unset($this->ntEucl);
	unset($this->iDenominator);
	unset($this->iNumerator);
  }
  
  public function GetVal($bSimplified = false)
  {
    return $bSimplified ? ($this->iNumerator/$this->iGCD)."/".($this->iDenominator/$this->iGCD) : $this->iNumerator."/".$this->iDenominator;
  }
  
  public function GetGCD()
  {
    return $this->iGCD;
  }
  
  public function Simplify()
  {
	if($this->iGCD!=1)
	{
		$this->iNumerator /= $this->iGCD;
		$this->iDenominator /= $this->iGCD;
	}
    return $this->iNumerator."/".$this->iDenominator;
  }
  
  private function GetSeparated(&$iNumeratorNew, &$iDenominatorNew)
  {
	$iNumeratorNew = $this->iNumerator;
	$iDenominatorNew = $this->iDenominator;
    return 0;
  }
  
  public function Add(/* Fraction */ $fr)
  {
	$iNumeratorNew = 0;
	$iDenominatorNew = 0;
	$fr->GetSeparated($iNumeratorNew, $iDenominatorNew);
	$iNumeratorNew = $iNumeratorNew * $this->iDenominator + $this->iNumerator * $iDenominatorNew;
	$iDenominatorNew *= $this->iDenominator;
	$frRes = new Fraction($iNumeratorNew, $iDenominatorNew);
    return $frRes;
  }
  
  public function Mult(/* Fraction */ $fr)
  {
	$iNumeratorNew = 0;
	$iDenominatorNew = 0;
	$fr->GetSeparated($iNumeratorNew, $iDenominatorNew);
	$iNumeratorNew *= $this->iNumerator;
	$iDenominatorNew *= $this->iDenominator;
	$frRes = new Fraction($iNumeratorNew, $iDenominatorNew);
    return $frRes;
  }
  
  public function Sub(/* Fraction */ $fr)
  {
	$frMinusOne = new Fraction(-1, 1);
	$fr = $fr->Mult($frMinusOne);
	$frRes = $this->Add($fr);
    return $frRes;
  }
  
  public function Div(/* Fraction */ $fr)
  {
	$iNumeratorNew = 0;
	$iDenominatorNew = 0;
	$fr->GetSeparated($iNumeratorNew, $iDenominatorNew);
	//echo $iNumeratorNew, $iDenominatorNew
	
	$iNumerator3 = $this->iNumerator * $iDenominatorNew;
	$iDenominator3 = $this->iDenominator * $iNumeratorNew;
	if($iDenominatorNew<0)
	{
		$iNumerator3 *= -1;
		$iDenominator3 *= -1;
	}
	$frRes = new Fraction($iNumerator3, $iDenominator3);
    return $frRes;
  }
  
  /* bool */ public function Eq(/* Fraction */ $fr, $bSimplified = true)
  {
    $iNumeratorNew = 0;
	$iDenominatorNew = 0;
	$fr->GetSeparated($iNumeratorNew, $iDenominatorNew);
    return $bSimplified ? ((($this->iNumerator/$this->iGCD) == ($iNumeratorNew/$fr->GetGCD())) && (($this->iDenominator/$this->iGCD) == ($iDenominatorNew/$fr->GetGCD()))) : (($this->iNumerator == $iNumeratorNew) && ($this->iDenominator == $iDenominatorNew));
  }
}
?>