<?php
class NumberTheory
{
  //private /* Stochastic */ $st1;
	
  public function /* NumberTheory */ __construct()
  {
    //$this->st1 = new Stochastic();
  }
  
  public function /* ~NumberTheory */ __destruct()
  {
    //unset($this->st1);
  }
  
  /* int */ public function EucleadGCD(/* int */ $a, /* int */ $b, &$aTrace = array())
  {
	while($a!=0 && $b!= 0)
	{
		if($a>=$b) 
		{
			$a = $a % $b;
			$aTrace[] = $a;
		}
		else
		{
			$b = $b % $a;
			$aTrace[] = $b;
		}
	}
	return $a + $b;
  }
 }
?>