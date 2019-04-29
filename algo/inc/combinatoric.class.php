<?php
//include_once("C:/Program Files/wamp/www/dev2.5/algo/inc/stochastic.class.php");
include_once("stochastic.class.php");

class Combinatoric
{
  private /* Stochastic */ $st1;
	
  public function /* Combinatoric */ __construct()
  {
    $this->st1 = new Stochastic();
  }
  
  public function /* ~Combinatoric */ __destruct()
  {
    unset($this->st1);
  }

	public function /* array */ get_array_diff_recount($aSuperSet, $aSubSet)
	{
		  $aRet = array();
		  $aRet = array_diff($aSuperSet, $aSubSet);
		  //recount $aRet
		  /*
		  $i = 0;
		  foreach($aSubSet as $k => $v)
		  {
			$aRet[$i] = $v;
		  }
		  */	  
		  
		  return array_values($aRet);
	}
		
	public function /* array */ get_array_wout_repeat_modif($aIn, /* int */ $iNum, /* superset of aIn */ $aSuperSet, $dCorrel = 1.)
	{
		  $aRet = $aIn;
		  //mod array	

		  if($iNum < count($aRet))
		  {
			  //trincate
			  $aRet = array_slice($aRet, 0, (count($aRet) - $iNum));
		  }
		  else if($iNum > count($aRet))
		  {
			  //add ($iNum-count($aRet)) item w/out repeat
			  $aAddWoutRep = $this->get_array_wout_repeat($this->get_array_diff_recount($aSuperSet, $aRet), ($iNum-count($aRet)));
			  //union and reindex array
			  $aRet = array_values(array_merge($aRet, $aAddWoutRep));
		  }
			
		  $iArrCnt = count($aRet)-1;
		  for($i = 0; $i < $iArrCnt; $i++)
		  {
			$dCurrentRnd = $this->st1->get_double_rnd(0., 1.);
			//echo "\$aRet[\$i] = ".$aRet[$i]."<br />";
			//echo "\$dCorrel = ".$dCorrel."<br />";
			//echo "\$dCurrentRnd = ".$dCurrentRnd."<br />";
			if($dCurrentRnd<=$dCorrel)
			{
			  //echo "change<br />";
			  $aRet[$i] = get_val_from_array($this->get_array_diff_recount($aSuperSet, $aRet)); //2DO: extract get_val_from_array from get_form.php Put it there
			}
		  }	  
		  return $aRet;
	}
		
	public function /* array */ get_array_wout_repeat($aIn, /* int */ $iNum)
	{
		  $aRet = $aIn;
		  //shuffle array
		  $iArrCnt = count($aRet)-1;
		  for($i = 0; $i < $iArrCnt; $i++)
		  {
			//echo $aRet[$i]."<br />";
			$tmp = $aRet[$i];
			$iRndIndx = $this->st1->get_number(0, $iArrCnt);
			$aRet[$i] = $aRet[$iRndIndx]; 
			$aRet[$iRndIndx] = $tmp;
		  }
		  return array_slice($aRet, 0, $iNum);
	}
	
	public function /* array */ no_neighbor_same_class(&$aIn, &$aOut) 
	{
	  //no same elements to the right
	  //e.g. if there is a negative number, the next one should be positive
	  $i=0;
	  foreach($aIn as $v)
	  {
	    if(($v<0)&&($i>0)&&($aOut[$i-1]<0)) $aOut[$i]=abs($v);
		else $aOut[]=$v;
	  }
	}
}
?>