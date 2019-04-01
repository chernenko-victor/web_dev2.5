<?php 
//stochastic.class.php

class Stochastic
{
  private $iMinInner;
  private $iMaxInner;
  private $dMinInner;
  private $dMaxInner;
	
  public function /* Stochastic */ __construct()
  {
    //...
  }
  
  public function /* ~Stochastic */ __destruct()
  {
    //..
  }
  
	public function /* int */ get_number(/* int */ $iMin, /* int */ $iMax)
	{
		  return mt_rand($iMin, $iMax);
	}
		
	public function /* double */ get_double_rnd(/* double */ $dMin, /* double */ $dMax)
	{
		  return (float)(mt_rand())*($dMax - $dMin)/(float)(mt_getrandmax()) + $dMin;
	}
		
	/*
		types of distribution
		1 = uniform
		2 = linrnd_low ;linear random with precedence of lower values
		3 = linrnd_high ;linear random with precedence of higher values
		4 = trirnd ;for triangular distribution

		5 = linrnd_low_depth ;linear random with precedence of lower values with depth
		6 = linrnd_high_depth ;linear random with precedence of higher values with depth
		7 = trirnd_depth ;for triangular distribution with depth
	*/
		
	public function /* int */ SetMinI($iMinNew)
	{
		  $this->iMinInner = $iMinNew;
	}
	public function /* int */ SetMaxI($iMaxNew)
	{
		  $this->iMaxInner = $iMaxNew;
	}
	public function /* float */ SetMinF($dMinNew)
	{
		  $this->dMinInner = $dMinNew;
	}
	public function /* float */ SetMaxF($dMaxNew)
	{
		  $this->dMaxInner = $dMaxNew;
	}
		
	public function /* int */ UniformI() 
	{ 
		  return $this->get_number($this->iMinInner, $this->iMaxInner);
	}
	public function /* double */ UniformD() 
	{ 
		  return $this->get_double_rnd($this->dMinInner, $this->dMaxInner);
	}
		
	public function /* int */ LinearI($bIsLow = true)
	{ 
		  $iOne = $this->get_number($this->iMinInner, $this->iMaxInner);
		  $iTwo = $this->get_number($this->iMinInner, $this->iMaxInner);
		  if($bIsLow)
		  {
		    $iRes = $iOne < $iTwo ? $iOne : $iTwo;
		  }
		  else
		  {
			$iRes = $iOne > $iTwo ? $iOne : $iTwo;
		  }		   			
		  return $iRes;
	}
	public function /* double */ LinearD($bIsLow = true)
	{ 
		  $dOne = $this->get_double_rnd($this->dMinInner, $this->dMaxInner);
		  $dTwo = $this->get_double_rnd($this->dMinInner, $this->dMaxInner);
		  if($bIsLow)
		  {
		    $dRes = $dOne < $dTwo ? $dOne : $dTwo;
		  }
		  else
		  {
			$dRes = $dOne > $dTwo ? $dOne : $dTwo;
		  }		   			
		  return $dRes;
	}
		
	public function /* int */ TriangleI(){ /* … */}
	public function /* double */ TriangleD(){ /* … */}
	
	public function /* int */ NormalI(){ /* … */}
	public function /* double */ NormalD(){ /* … */}
}
?>