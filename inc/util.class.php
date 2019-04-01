<?php
//util.class.php

//include_once("generator.class.php");

class Util
{
  //private /* Generator */ $gGen;

  public function /* Util */ __construct()
  {
    //...
  }
  
  public function /* ~Util */ __destruct()
  {
    //..
  }
  
  /* string */ public function MinInArray($aArr)
  {
    /* string */ $sRes = "";
    if(is_array($aArr) && count($aArr))
    {
      sort($aArr);
      $sRes = "".$aArr[0];
    }
    return $sRes;
  }
  
  /* string */ public function EncodeEntit($sInStr)
  {
    /* string */ $sRes = "";
    $patterns = array();
    $patterns[0] = '/</';
    $patterns[1] = '/>/';
    $replacements = array();
    $replacements[2] = '&lt;';
    $replacements[1] = '&gt;';
    $sRes = preg_replace($patterns, $replacements, $sInStr);
    return $sRes;
  }
  
  /* string */ public function DecodeEntit($sInStr)
  {
    /* string */ $sRes = "";
    $patterns = array();
    $patterns[0] = '/&lt;/';
    $patterns[1] = '/&gt;/';
    $replacements = array();
    $replacements[2] = '<';
    $replacements[1] = '>';
    $sRes = preg_replace($patterns, $replacements, $sInStr);
    return $sRes;
  }
  
  /* void */ public function ShowVar(/* String */ $sModuleName, /* String */ $sFuncName, /* String */ $sVarName, /* mixed */ $mVar)
  {
    echo "<br /><i>===========================================<br />$sModuleName::$sFuncName</i><br /><strong>$sVarName</strong>";
    var_dump($mVar);
    echo "<br /><i>===========================================<br /><br />";
  }
  
  public function EscapeStrDb($str)
  {
          $search=array("\\","\0","\n","\r","\x1a","'",'"');
          $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
          return str_replace($search,$replace,$str);
  }
  public function UnEscapeStrDb($str)
  {
          $search=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
          $replace=array("\\","\0","\n","\r","\x1a","'",'"');
          return str_replace($search,$replace,$str);
  }
  
  /* string */ public function RndNum2VexflowDur($fDur)
  {
    global $afDur;
    $sRes = "q";
    
    if($fDur>0)
    {
      $iSign = 1;
    }
    else
    {
      $iSign = -1;
    }
    
    $fDur = abs($fDur);
    if(($fDur<=$afDur["fWholeDur"])&&($fDur>$afDur["fHalfDotDur"]))
    {
      $sRes = "w";
    }
    else if(($fDur<=$afDur["fHalfDotDur"])&&($fDur>$afDur["fHalfDur"]))
    {
      $sRes = "hd";
    }
    else if(($fDur<=$afDur["fHalfDur"])&&($fDur>$afDur["fQuartDotDur"]))
    {
      $sRes = "h";
    }
    else if(($fDur<=$afDur["fQuartDotDur"])&&($fDur>$afDur["fQuartDur"]))
    {
      $sRes = "qd";
    } 
    else if(($fDur<=$afDur["fQuartDur"])&&($fDur>$afDur["f8thDotDur"]))
    {
      $sRes = "q";
    }
    else if(($fDur<=$afDur["f8thDotDur"])&&($fDur>$afDur["f8thDur"]))
    {
      $sRes = "8d";
    }
    else if(($fDur<=$afDur["f8thDur"])&&($fDur>$afDur["f16thDotDur"]))
    {
      $sRes = "8";
    }
    else if(($fDur<=$afDur["f16thDotDur"])&&($fDur>$afDur["f16thDur"]))
    {
      $sRes = "16d";
    }
    else if(($fDur<=$afDur["f16thDur"])&&($fDur>$afDur["f32thDur"]))
    {
      $sRes = "16";
    }
    else if($fDur<=$afDur["f32thDur"])
    {
      $sRes = "32";
    }
    
    if($iSign<0)
    {
      $sRes .= "r";
    }
    return $sRes;
  }
  
  public function GetGUID()
  {
    //if (function_exists('com_create_guid'))
    //{
    //    return com_create_guid();
    //}
    //else
    //{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        //$uuid = chr(123)// "{"
        $uuid = ""
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
        //        .chr(125);// "}"
        return $uuid;
    //}
  }
  
  public function /* mixed */ GetItemByFieldNm(/* array */ $aIn, /* String */ $sFieldNm = "key")
  {
    $mRes = NULL;
    if(is_array($aIn) && count($aIn))
    {
      foreach($aIn as $k => $v)
      {
        //echo "<br />\$k => \$v : $k => $v<br /><br />";
        if($sFieldNm === $k) 
        {
          /*
          echo "<br />\$sFieldNm = $sFieldNm<br /><br />";
          echo "<br />\$k = $k<br /><br />";
          echo "<br />(\$sFieldNm == \$k) = ".($sFieldNm == $k)."<br /><br />";
          $test = ($sFieldNm === $k);
          echo "<br />(\$sFieldNm === \$k) = $test<br /><br />";
          var_dump(0 == "gen");
          var_dump(0 === "gen");
          */
          $mRes = $v;
          break;
        }
        else
        {
          $mRes = $this->GetItemByFieldNm($v, $sFieldNm);
          if(!is_null($mRes))
          {
            break;
          }
        }
      }
    }
    return $mRes;
  }
  
  public function /* string */ after ($this_char, $inthat)
    {
        if (!is_bool(strpos($inthat, $this_char)))
        return substr($inthat, strpos($inthat,$this_char)+strlen($this_char));
    }
	
  public function /* string */ before ($this_char, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $this_char));
    }
  
  public function /* int */ MidiNum2PitchOct($iMidiNum, &$sPitchLetter, &$iOctNum, &$iAlter)
  { 
    $iRes = -1;
	//...C4 = 60
	//...C3 = 48
	//...C2 = 36
	//...C1 = 24
	//...C0 = 12
	//...C-1 = 0
    $iOctNum = floor($iMidiNum/12)-1;
	switch($iMidiNum%12)
	{
	  case 0:
	    $sPitchLetter = "C";
		$iAlter = 0;
	  break;
	  case 1:
	    $sPitchLetter = "D";
		$iAlter = -1;
	  break;
	  case 2:
	    $sPitchLetter = "D";
		$iAlter = 0;
	  break;
	  case 3:
	    $sPitchLetter = "E";
		$iAlter = -1;
	  break;
	  case 4:
	    $sPitchLetter = "E";
		$iAlter = 0;
	  break;
	  case 5:
	    $sPitchLetter = "F";
		$iAlter = 0;
	  break;
	  case 6:
	    $sPitchLetter = "F";
		$iAlter = 1;
	  break;
	  case 7:
	    $sPitchLetter = "G";
		$iAlter = 0;
	  break;
	  case 8:
	    $sPitchLetter = "A";
		$iAlter = -1;
	  break;
	  case 9:
	    $sPitchLetter = "A";
		$iAlter = 0;
	  break;
	  case 10:
	    $sPitchLetter = "B";
		$iAlter = -1;
	  break;
	  case 11:
	    $sPitchLetter = "B";
		$iAlter = 0;
	  break;
	}
	$iRes = 1;
	return $iRes;
  }
  
  public function /* void */ GetRndValueFromArray(/* array */ &$aIn, /* array */ &$aOut)
  {
    /* int */ $iCnt = count($aIn);
    //echo "\$aIn ";
    //print_r($aIn);
    //echo "<br />";
    //echo "\$iCnt = $iCnt<br />";
    
    if($iCnt>0)
    {
      mt_srand((double)microtime()*10000);
      $iIndex = mt_rand(0, $iCnt-1);
      //echo "\$iIndex = $iIndex<br />";
      $i = 0;
      foreach($aIn as $k => $v)
      {
        if($i == $iIndex)
        {
          $aOut = $v;
          break;
        }
        $i++;
      }
    }
    
    //echo "\$aOut ";
    //print_r($aOut);
    //echo "<br />";
  }
  
    public function get_default_val($xVal, $xValDef, $sType)
	{ 
	  $xRes = 0;
	  
	  //echo "<br /><br />\$xVal = $xVal, \$xValDef = $xValDef, \$sType = $sType<br /><br />";
	  
	  if(!isset($xVal))
	  {
	    //echo "!isset<br /><br />";
		$xRes = $xValDef;
	  }
	  else
	  {
		  switch ($sType) 
		  {
			case "int":
				if(!(int)($xVal)) 
				{
				  $xRes = $xValDef;
				}
				else
				{
				  $xRes = $xVal;
				}
				break;
			case "double":
				if(!(double)($xVal)) 
				{
				  $xRes = $xValDef;
				}
				else
				{
				  $xRes = $xVal;
				}
				break;
			default:
				$xRes = $xValDef;
				break;
		  }
	  }
	  return $xRes;
	}
	
	public function /* Array */ array_merge_unic($a1, $a2)
	{
	  //echo "<br /><br />array_merge_unic =======================================================<br /><br />";
	  //echo "<br /><br />a1 =======================================================<br /><br />";
	  //print_r($a1);
	  //echo "<br /><br />a2 =======================================================<br /><br />";
	  //print_r($a2);
	  //echo "<br /><br />array_diff(a2, a1) =======================================================<br /><br />";
	  //print_r(array_diff($a2, $a1));
	  return array_merge($a1, array_diff($a2, $a1));
	}
	
	/*
	public function IsHomogenous($arr, $testValue) 
	{
      //$firstValue = current($arr);
      foreach ($arr as $val) 
	  {
        if ($testValue !== $val) 
		{
            return false;
        }
      }
      return true;
    }
	*/
}
?>