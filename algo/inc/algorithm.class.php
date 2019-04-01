<?php
//algorithm.class.php

include_once("../inc/util.class.php");
include_once("config.inc.php");

class algorithm
{
  protected /* array */ $agpGenParam;
  protected /* Util */ $uUtil;
  protected /* XmlConstruct */ $XmlConstruct;
  
  public function /* Rnd */ __construct(/* array */ $agpGenParamNew)
  {
    $this->agpGenParam = array();
    $this->agpGenParam = $agpGenParamNew;
    $this->uUtil = new Util();
    $this->XmlConstruct = new XMLWriter();
  }
  
  public function /* ~Rnd */ __destruct()
  {
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
    
     
    //распределение параметров генераторам (пока считаем, что нет изменения параметров) 
    //распределение генераторам пользователей
    //генерация времени следующей генерации.
      
    $iIdAlgo = $this->GetParamValByKey("id_algo");
    
    //генерация числа генераторов от 1 до кол-ва пользователей,
      
    //кол-во пользователей
    //SELECT COUNT(r5u7_user.id) AS user_cnt
    //FROM r5u7_user
    //WHERE r5u7_generator.id_algo = r5u7_algo.id = $iIdAlgo
    //AND r5u7_generator2user.id_gen = r5u7_generator.id
    //AND r5u7_generator2user.id_user = r5u7_user.id
    
    //CHANGE gen
    //CHANGE user2gen
    
    //MAKE user2gen map

    $aCoord = array();
    //$iTime = $this->GetParamValByKey("time");
    //$iTime2 = $this->GetParamValByKey("time2");
    $iTime = 20;
    $iTime2 = 40;
    
    for($i = 0; $i < 8; $i++)
    {
      $aCoord[] = $iTime + rand(0, 10*$iTime2);
    }
    
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
        $iMidiNotePitch = $iTime + rand(0, 10*$iTime2);
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