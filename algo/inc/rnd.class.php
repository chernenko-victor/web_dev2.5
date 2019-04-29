<?php
//rnd.class.php

include_once("../inc/util.class.php");
include_once("config.inc.php");
//include_once("C:/Program Files/wamp/www/dev2.3/algo/inc/stochastic.class.php");
include_once("stochastic.class.php");
//include_once("C:/Program Files/wamp/www/dev2.3/algo/inc/combinatoric.class.php");
include_once("combinatoric.class.php");

class Rnd
{
  protected /* array */ $agpGenParam;
  protected /* Util */ $uUtil;
  protected /* XmlConstruct */ $XmlConstruct;
  protected /* Stochastic */ $st1;
  protected /* Combinatoric */ $cmb1;
  
  public function /* Rnd */ __construct(/* array */ $agpGenParamNew)
  {
    $this->agpGenParam = array();
    $this->agpGenParam = $agpGenParamNew;
    $this->uUtil = new Util();
    $this->XmlConstruct = new XMLWriter();
	$this->st1 = new Stochastic();
	$this->cmb1 = new Combinatoric();
  }
  
  public function /* ~Rnd */ __destruct()
  {
    unset($this->cmb1);
    unset($this->st1);
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
  
  protected function /* String */ GetNote(/* int */$iPitch, /* int */$iDur)
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
  
  public function /* String */ GetResXML($bEncode = true, $iFixedPitch = false, $fFixedDur = false)
  {
    /* String */ $sRes = "";

    //$aCoord = array();
    $iTime = $this->GetParamValByKey("time"); //8
    $iTime2 = $this->GetParamValByKey("time2"); //2
	$this->st1->SetMinI($iTime);
	$this->st1->SetMaxI(100*$iTime2);
	
	$aPtch = array();
	for($i=0; $i<=11; $i++)
	{
	  $aPtch[] = $iTime + 50 + $i;
	}
	
	$aPthcSel = $this->cmb1->get_array_wout_repeat($aPtch, 4);
    
	/*
    for($i = 0; $i < 8; $i++)
    {
      $aCoord[] = $iTime + rand(0, 10*$iTime2);
    }
	*/
    
    $this->XmlConstruct->openMemory();
    $this->XmlConstruct->setIndent(true);
    $this->XmlConstruct->setIndentString(' ');
    $this->XmlConstruct->startDocument('1.0', 'UTF-8');

    $this->XmlConstruct->startElement("notes");
    
    $j = 0; 
    //while($j < 8) //01 23 45 67
    while($j < 4) //01 23 45 67
    {
      if(!$iFixedPitch)
      {
        //$iMidiNotePitch = $iTime + rand(0, 10*$iTime2);
        //$iMidiNotePitch = $iTime + rand(0, 100*$iTime2);
		//$iMidiNotePitch = $this->st1->UniformI();
		$iMidiNotePitch = $aPthcSel[$j];
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

    $this->XmlConstruct->endElement();
       
    $this->XmlConstruct->endDocument();
    
    //header('Content-type: text/xml');
    $sRes = $this->XmlConstruct->outputMemory();

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