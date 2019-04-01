<?php
//SEE php object oriented polymorphism
//http://code.tutsplus.com/tutorials/understanding-and-applying-polymorphism-in-php--net-14362

//generation of xml => musicXML class :: form of presentation
//generation of pitch and dur => rnd (or Markov chain, or formal grammar or ...) class :: generation algorythm

/* *****************************************************************

//include_once("config.inc.php");

//include_once("options.inc.php");

//Настройки для global базы данных
//$MYSQL["global"]["db_name"]
//$MYSQL["global"]["user"]
//$MYSQL["global"]["password"]
//$MYSQL["global"]["host"]
//$MYSQL["global"]["table_prefix"]


//parameter.class.php
class Parameter 
{
  var mlInputFormat;
  //"mlInputFormat" :: \www\dev2.3\inc\config.inc.php
	  //1:$mlHTML = new MarkupLanguage("HTML");
	  //2:$mlXML = new MarkupLanguage("XML");
	  //3:$mlVexflowNote = new MarkupLanguage("VexflowNote"); 
	  //4:$mlSVG = new MarkupLanguage("SVG");
	  //5:$mlUnknown = new MarkupLanguage("Unknown");
	  //6:$mlGen = new MarkupLanguage("Gen");
	
  var mlOutputFormat;    
  //"mlOutputFormat" :: \www\dev2.3\inc\config.inc.php
      //see above

  var iAlgoType;
  //"iAlgoType" :: C:\book\music\composition\Формальная композиция. Параметры и объекты.doc
      //1:math functions (linear, exponential, sinus etc)
      //2:rows (natural, fibonacci etc)
	  //3:iterative functions (fractal (strong self-simility, "fuzzy" self-simility etc), chaos (bufurcation etc))
	  //4:formal algebra, group theory, matrices
	  //5:graph theory
	  //6:formal grammar, cellular automata
	  //7:prob and stohastic
		  //71:different distribution function
			  //711:Uniform distribution
			  //712:Linear distribution
			  //713:Normal (gaussian) distribution
			  //etc
		  //72:Markov chain
		  //73:fuzzy logic
		  //etc
	  //8:evolutional and genetic algo
	  //9:self-asseьbly
	  
};

class ParameterIn extends Parameter 
{
  //algo specified in parameters
};

class ParameterOut extends Parameter 
{
  //algo specified Out parameters
};

$pi1 = new ParameterIn(); //input var in object
$po1 = new ParameterOut(); 


//include_once("db.class.php");
class globalDB extends DBA
{
  GetAlgoSrcByType($iAlgoType); //return $sAlgoSrcUrl;
}


//algo.class.php
abstract class Algo
{
  //var mlOutputFormat; //presentation not there!!
  var iAlgoType;
  
  __construct($piLocal) //ParameterIn
  {
    //...
  }
  
  virtual GetResult() {};
}



//...
//...
//...


//scalar_rnd.class.php
class ScalarRnd extends Algo
{
  var $xMaxVal;
  var $iDataType;
  var $xMinVal;
  
  __construct($piLocal) //ParameterIn
  {
    //algoType
		//711:Uniform distribution
		//712:Linear distribution
		//713:Normal (gaussian) distribution
  };
  SetDataType($iDataType);
  GetDataType($iDataType);
  
  SetMaxVal($xMaxVal);
  GetMaxVal($xMaxVal);
  
  SetMinVal($xMinVal);
  GetMinVal($xMinVal);
  
  GetValue() {}; //return $aParameterOut;
}


//message.class.php
abstract class Message
{
  var mlOutputFormat;

  __construct($piLocal) //ParameterIn
  {
    //scheme
    //...
  };
  virtual GetMessage() {};
}


//...
//...
//...


//xml_msg.class.php
class XMLMsg extends Message
{
  __construct($piLocal) //ParameterIn
  {
    //scheme
    //...
  };
  GetMessage() {};
}


//...
//...
//...


//music_xml_msg.class.php
class MusicXMLMsg extends XMLMsg
{
  __construct($piLocal) //ParameterIn
  {
    //scheme
    //...
  };
  GetMessage() {};
}


//midi.class.php
class Midi extends Message
{
  GetMidiNoteNum() {};
  GetDuration() {};
};


//message_by_param.class.php
class MessageByParam
{
  var $piLocal //ParameterIn
  var $data;
  
  __construct($piLocal) //ParameterIn
  {
    global $MYSQL["global"];
  };
  
  GetMsg() //void
  {
    //get $piLocal from Net
  
    //select Algo by $piLocal->iAlgoType
    //$gdb1 = new globalDB($MYSQL["global"]);
    //$sAlgoSrcUrl = $gdb1->GetAlgoSrcByType($$pi1->iAlgoType);
    //test $sAlgoSrcUrl
    //include_once($sAlgoSrcUrl);
  
    //generate $data by $piLocal->algo_specified_parameters from Algo
  
    //select Message by $piLocal->mlOutputFormat;
  
    //get formatted $message by $data from Message
  
    //send $message
  };
}

//transformation.class.php
class Transformation extends Message
{
  var mlInputFormat;
  var mlOutputFormat;

  __construct($pLocal) //Parameter
  {
    //scheme
    //...
  };
  virtual SetMessage() {};
  virtual GetMessage() {};
}

***************************************************************** */


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
      if(!$iFixedPitch)
      {
        //$iMidiNotePitch = $iTime + rand(0, 10*$iTime2);
         $iMidiNotePitch = (int) ($dTime + rand(0, 100*$dTime2));
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
	
	$aDoc = array
	(
      "score-partwise#1" => array
	  (
	      "@version" => "3.0"
		, "part-list#1" => array
		  (
		      "score-part#1" => array
			  (
			      "@id" => "psystem1"
				, "part-name#1" => "Pitch System"
			  )
		  )
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