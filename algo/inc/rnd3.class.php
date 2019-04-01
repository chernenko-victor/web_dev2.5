<?php
//rnd.class.php

include_once("../inc/util.class.php");
include_once("../inc/assoc2XML.class.php");
include_once("config.inc.php");

class Rnd
{
  private /* array */ $agpGenParam;
  private /* Util */ $uUtil;
  private /* assoc2Xml */ $axNew;
  
  public function /* Rnd */ __construct(/* array */ $agpGenParamNew)
  {
    $this->agpGenParam = array();
    $this->agpGenParam = $agpGenParamNew;
    $this->uUtil = new Util();
    $this->axNew = new assoc2Xml();
  }
  
  public function /* ~Rnd */ __destruct()
  {
    unset($this->axNew);
    unset($this->agpGenParam);
    unset($this->uUtil);
  }
  
  protected function /* int */ GetParamValByKey(/* String */$sKey)
  {
    /* int */ $iRes = 0;
    if(!is_array($this->agpGenParam))
    {
      return $iRes;
    }
    if(!isset($this->agpGenParam["genParams"]) || !is_array($aItems = $this->agpGenParam["genParams"]))
    {
      return $iRes;
    }
    
    if(!isset($aItems["item"]) || !is_array($aItems["item"]))
    {
      return $iRes;
    }
    foreach($aItems["item"] as $k => $v)
    {
      //echo "<br /><strong>$k</strong><br />";
      //print_r($v);
      //echo "<br /><br />";
      
      if(!is_array($v))
      {
        return $iRes;
      }
      if(isset($v) && $v["key"] == $sKey)
      {
        $iRes = $v["value"];
        break;
      }
    }
    return $iRes;
  }
  
  public function /* String */ GetResXML($bEncode = true, $iFixedPitch = false, $fFixedDur = false)
  {
    /* String */ $sRes = "";

	/*
    $aCoord = array();
    $iTime = $this->GetParamValByKey("time");
    $iTime2 = $this->GetParamValByKey("time2");
    
    for($i = 0; $i < 8; $i++)
    {
      $aCoord[] = $iTime + rand(0, 10*$iTime2);
    }
	*/
	
	/*
    $j = 0; 
    //while($j < 8) //01 23 45 67
    while($j < 4) //01 23 45 67
    {
      if(!$iFixedPitch)
      {
        //$iMidiNotePitch = $iTime + rand(0, 10*$iTime2);
        $iMidiNotePitch = $iTime + rand(0, 100*$iTime2);
      }
      else
      {
        $iMidiNotePitch = $iFixedPitch;
      }
      
      if(!$fFixedDur)
      {
        $fMidiNoteDur = 2.0*$this->GetFloatRndNorm2One()-1.0;
      }
      else
      {
        $fMidiNoteDur = $fFixedDur;
      }
      //$this->GetNote($iMidiNotePitch, $fMidiNoteDur);
      $j++;
    }
	*/	
	
	$aDoc = array
	(
      "score-partwise#1" => array
	  (
	      "@version" => "3.0"
		, "part-list#1" => array
		  (
		      "score-part#1" => array
			  (
			      "@id" => "P1"
				, "part-name#1" => "Music1"
			  )
			, "score-part#2" => array
			  (
			      "@id" => "P2"
				, "part-name#1" => "Music2"
			  )
		  )
		, "part#1" => array
		  (
		      "@id" => "P1"
			, "measure#1" => array
			  (
			      "@number" => "1"
				, "attributes#1" => array
				  (
				      "divisions#1" => "1"
					, "key#1" => array
					  (
					      "fifths#1" => "0"
					  )
					, "time#1" => array
					  (
					      "beats#1" => "4"
					    , "beat-type#1" => "4"
					  )
					, "clef#1" => array
					  (
					      "sign#1" => "G"
					    , "line#1" => "2"
					  )
				  )
				, "note#1" => ""
				, "note#2" => ""
			  )
			, "measure#2" => array
			  (
			      "@number" => "2"
				, "attributes#1" => array
				  (
				      "divisions#1" => "1"
					, "key#1" => array
					  (
					      "fifths#1" => "3"
					  )
					, "time#1" => array
					  (
					      "beats#1" => "3"
					    , "beat-type#1" => "4"
					  )
					, "clef#1" => array
					  (
					      "sign#1" => "G"
					    , "line#1" => "2"
					  )
				  )
				, "note#1" => ""
				, "note#2" => ""
			  )
		  )
		, "part#2" => array
		  (
		      "@id" => "P2"
			, "measure#1" => array
			  (
			      "@number" => "2"
				, "attributes#1" => array
				  (
				      "divisions#1" => "1"
					, "key#1" => array
					  (
					      "fifths#1" => "0"
					  )
					, "time#1" => array
					  (
					      "beats#1" => "4"
					    , "beat-type#1" => "4"
					  )
					, "clef#1" => array
					  (
					      "sign#1" => "F"
					    , "line#1" => "4"
					  )
				  )
				, "note#1" => array
				(
				    "pitch#1" => array
				  (
				      "step#1" => "C"	
				    , "octave#1" => "4"	
				  )
				  , "duration#1" => "4"
				  , "type#1" => "whole"
				)
				, "note#2" => ""
			  )
		  )
	  )
    );
	  
	$sRes = $this->axNew->getPartit($aDoc);

    if($bEncode)
    {
        $sRes = $this->uUtil->EncodeEntit($sRes);
    }
    return $sRes;
  }
  
  protected function GetFloatRndNorm2One()
  {
    $fRndNormal2One = 1.0;
    $iUpLim = pow(2,16);
    $iRndNum = mt_rand(1, $iUpLim);
    $fRndNormal2One = ((float)$iRndNum)/((float)$iUpLim);
    return $fRndNormal2One;
  }
}
?>