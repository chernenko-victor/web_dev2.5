<?php
//rnd.class.php

include_once("../inc/util.class.php");
include_once("../inc/assoc2XML.class.php");
include_once("config.inc.php");

class Rnd
{
  private /* array */ $agpGenParam;
  private /* Util */ $uUtil;
  private /* XmlConstruct */ $XmlConstruct;
  private /* assoc2Xml */ $axNew;
  
  public function /* Rnd */ __construct(/* array */ $agpGenParamNew)
  {
    $this->agpGenParam = array();
    $this->agpGenParam = $agpGenParamNew;
    $this->uUtil = new Util();
    $this->XmlConstruct = new XMLWriter();
    $this->axNew = new assoc2Xml();
  }
  
  public function /* ~Rnd */ __destruct()
  {
    unset($this->axNew);
    unset($this->XmlConstruct);
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
  
  //protected function /* String */ GetNote(/* int */$iPitch, /* int */$iDur)
  /*
  {
    $this->XmlConstruct->startElement("note");
      $this->XmlConstruct->startElement("pitch");
        $this->XmlConstruct->startAttribute("type");
          $this->XmlConstruct->text("midi_num");
        $this->XmlConstruct->endAttribute();
        $this->XmlConstruct->text($iPitch);
      $this->XmlConstruct->endElement();
      $this->XmlConstruct->startElement("dur");
        $this->XmlConstruct->startAttribute("type");
          $this->XmlConstruct->text("sec");
        $this->XmlConstruct->endAttribute();
        $this->XmlConstruct->text($iDur);
      $this->XmlConstruct->endElement();
    $this->XmlConstruct->endElement();
  }
  */
  
  public function /* String */ GetResXML($bEncode = true, $iFixedPitch = false, $fFixedDur = false)
  {
    /* String */ $sRes = "";

    $aCoord = array();
	/*
    $iTime = $this->GetParamValByKey("time");
    $iTime2 = $this->GetParamValByKey("time2");
    
    for($i = 0; $i < 8; $i++)
    {
      $aCoord[] = $iTime + rand(0, 10*$iTime2);
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
				, "note#1" => ""
				, "note#2" => ""
			  )
		  )
	  )
    );
    
	/*
    $this->XmlConstruct->openMemory();
    $this->XmlConstruct->setIndent(true);
    $this->XmlConstruct->setIndentString(' ');
    $this->XmlConstruct->startDocument('1.0', 'UTF-8');

	$this->Array2Xml($aDoc);
	*/
	
	/*
    $this->XmlConstruct->startElement("score-partwise");
	  
	  $this->XmlConstruct->startAttribute("version");
        $this->XmlConstruct->text("3.0");
      $this->XmlConstruct->endAttribute();
	  
	  $aPartListNew = array(
	      0 => array(
		      "id" => "P1"
			, "part-name" => "Music1"
		  )
		, 1 => array(
		      "id" => "P2"
			, "part-name" => "Music2"
		  ) 
	  );
	  $this->GetPartList($aPartListNew);
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
      $this->GetNote($iMidiNotePitch, $fMidiNoteDur);
      //++$j;
      $j++;
    }
	*/

	/*
    $this->XmlConstruct->endElement();
       
    $this->XmlConstruct->endDocument();
    
    //header('Content-type: text/xml');
    $sRes = $this->XmlConstruct->outputMemory();
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
  
  //protected function GetPartList(/* array */ $aPartList)
  /*
  {
      $this->XmlConstruct->startElement("part-list");
	  if(is_array($aPartList) && count($aPartList))
	  {
	    foreach($aPartList as $k => $v)
		{
		  $this->XmlConstruct->startElement("score-part");
		    $this->XmlConstruct->startAttribute("id");
              $this->XmlConstruct->text($v["id"]);
            $this->XmlConstruct->endAttribute();
		    $this->XmlConstruct->startElement("part-name");
		      $this->XmlConstruct->text($v["part-name"]);
		    $this->XmlConstruct->endElement();
	      $this->XmlConstruct->endElement();
		}
	  }
	  $this->XmlConstruct->endElement();
  }
  */
  
  protected function Array2Xml($aArr)
  {
	  if(is_array($aArr) && count($aArr))
	  {
	    foreach($aArr as $k => $v)
		{
		    $mystring = $k;
			$findme   = '@';
			$pos = strpos($mystring, $findme);

			// Note our use of ===.  Simply == would not work as expected
			// because the position of 'a' was the 0th (first) character.
			if ($pos === false) 
			{
				//echo "The string '$findme' was not found in the string '$mystring'";
				//simple or element content
				$hash_sign_pos = strpos($k, "#");
				if ($hash_sign_pos !== false) 
				{
				  $k = $this->uUtil->before  ('#', $k);
				}
				//echo "$k<br />";
				$this->XmlConstruct->startElement($k);
				  if(is_array($v) && count($v))
				  {
				    //element content
					$this->Array2Xml($v);
				  }
				  else
				  {
				    //simple content
				    $this->XmlConstruct->text($v);
				  }
		        $this->XmlConstruct->endElement();
			} else {
				//echo "The string '$findme' was found in the string '$mystring'";
				//echo " and exists at position $pos";
				//attribute
				$k = $this->uUtil->after  ('@', $k);
				$this->XmlConstruct->startAttribute($k);
                  $this->XmlConstruct->text($v);
                $this->XmlConstruct->endAttribute();
			}
		}
      }
  }  
  
}
?>