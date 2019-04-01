<?php
//assoc2musicXML.class.php

include_once("transformation.class.php");
//include_once("../inc/util.class.php");
include_once("C:/Program Files/wamp/www/dev2.3/inc/util.class.php");
include_once("C:/Program Files/wamp/www/dev2.3/inc/assoc2XML.class.php");
include_once("C:/Program Files/wamp/www/dev2.3/algo/inc/set.class.php");

class Assoc2MusicXML extends Transformation
{
  private /* Util */ $uUtil;
  private /* assoc2Xml */ $axNew;
  private /* Set */ $sSetPitchSet; /* set of set pitch*/
  
  public function /* */ __construct()
  {
	$this->uUtil = new Util();
	$this->axNew = new assoc2Xml();
	$this->sSetPitchSet = new Set();
  }
  
  public function /* ~Rnd */ __destruct()
  {
    unset($this->sSetPitchSet);
    unset($this->axNew);
    unset($this->uUtil);
  }
  
  public function /* String */ GetMessage($bEncode = true)
  {
    //echo $this->sSetPitchSet->GetResult();
  
    //print_r($this->xMsg);

    /* String */ $sRes = "";
	
	$dTime = 60.;
    $dTime2 = (11./100.);
	
	//$iMidiNum = 0;
	$sPitchLetter = "";
	$iOctNum = 0;
	$iAlter = 0;
	
    $j = 0; 
    //while($j < 8) //01 23 45 67
    while($j < 4) //01 23 45 67
    {
      
      $iMidiNotePitch = (int) ($dTime + rand(0, 100*$dTime2));
      
      $fMidiNoteDur = 2.0*$this->GetFloatRndNorm2One()-1.0;
      
      //$this->GetNote($iMidiNotePitch, $fMidiNoteDur);
	  
	  $this->uUtil->MidiNum2PitchOct($iMidiNotePitch, $sPitchLetter, $iOctNum, $iAlter);
	  //$this->uUtil->MidiNum2PitchOct($iMidiNum, $sPitchLetter, $iOctNum, $iAlter);
	  $aNote[($j+1)] = array
	  (
	      "pitch#1" => array
		  (
		      "step#1" => $sPitchLetter	
		    , "alter#1" => $iAlter	
		    , "octave#1" => $iOctNum	
		  )
		  , "duration#1" => "1"
		  , "type#1" => "whole"
	  );
	
      $j++;
    }
		
	$aScorePartwise1 = array(
	      "@version" => "3.0"
		, "part-list#1" => $this->GetPartList()
	);
	
	//print_r($aScorePartwise1["part-list#1"]);
	$iPart = 1;
	foreach($aScorePartwise1["part-list#1"] as $aScorePart)
	{
		  //echo $aScorePart["@id"]."<br />";
		  $aCurrPart = array
		  (
		      "@id" => $aScorePart["@id"]
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
					    , "beat-type#1" => "1"
					  )
					, "clef#1" => array
					  (
					      "sign#1" => "G"
					    , "line#1" => "2"
					  )
				  )		
				, "direction#1" => array
				(
				    "@placement" => "below"
				  , "direction-type#1" => array
				  (
				      "dynamics#1" => array
					  (
					      "@default-x" => "129"
					    , "@default-y" => "-75" 
						, "pp#1" => ""
					  )
				  )
				)				
				, "note#1" => $aNote[1]
				, "note#2" => $aNote[2]
				, "note#3" => $aNote[3]
				, "note#4" => $aNote[4]
			  )
		  );
		  
		  $aScorePartwise1["part#".$iPart] = $aCurrPart;
		  $iPart++;
	}
	
	$aDoc = array();
	$aDoc["score-partwise#1"] = $aScorePartwise1;
	/*
	$aDoc = array
	(
      "score-partwise#1" => array
	  (
	      "@version" => "3.0"
		, "part-list#1" => $this->GetPartList()
		// =========== insert part based on part-list
		, "part#1" => array
		  (
		      "@id" => "psystem1"
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
					    , "beat-type#1" => "1"
					  )
					, "clef#1" => array
					  (
					      "sign#1" => "G"
					    , "line#1" => "2"
					  )
				  )
				, "note#1" => $aNote[1]
				, "note#2" => $aNote[2]
				, "note#3" => $aNote[3]
				, "note#4" => $aNote[4]
			  )
		  )
	  )
    );
	*/
	  
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
  
  private function GetPartList()
  {
		  //$this->xMsg;
		  //echo "<br /><br />=======================================================<br /><br />";
		  $aInstr = array();
		  foreach($this->xMsg as $aPart)
		  {
			$aInstr = $this->uUtil->array_merge_unic($aInstr, $aPart["instr"]);
		  }
		  //print_r($aInstr);
		  //echo "<br /><br />=======================================================<br /><br />";
		  
		  //$aRet = array
		  //(
		  //    "score-part#1" => array
		  //	  (
		  //	      "@id" => "psystem1"
		  //		, "part-name#1" => "Pitch System"
		  //	  )
		  //);
		  
		  
		  // ========================== sort by groups and/ or pitch
		  
		  $aRet = array();
		  $i=1;
		  foreach($aInstr as $sInstrName)
		  {
		      $aRet["score-part#".$i] = array
		  	  (
		  	      "@id" => "part".$i
		  		, "part-name#".$i => $sInstrName
		  	  );
			  $i++;
		  }
		  
		  return $aRet;
  }
}
?>