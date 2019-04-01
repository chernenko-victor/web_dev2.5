<?php
//VexFlowNote.class.php

include_once("C:/Program Files/wamp/www/dev2.5/inc/config.inc.php");
//include_once("..\\config.inc.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/markup_language.class.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/XML2Assoc.class.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/util.class.php");

class VexFlowNote extends Partit
{
  //private $uUtil;
  
  public function /* void */ TranslatePartit()
  {
    global $mlVexflowNote;
        
    /* array */ $aParamArr = $this->xaParser->parseString($this->sInputPartit, false);
    ///* array */ $aParamArr = parent::$xaParser->parseString($this->sInputPartit, false);
     
    if($mlVexflowNote == $this->mlOutputFormat)
    {
      
      //print_r($aParamArr);
      
      $aStdContentBlockContent = array();
      $aStdContentBlockContent = $this->ParseInputPartit($aParamArr, $fTotalDur);
      
      //echo "<br />+++++++++++++++++++++++++++++++++++++<br />";
      //print_r($this->aAddParam["div_outer"]);
      //echo "<br />+++++++++++++++++++++++++++++++++++++<br />";
      
      if(isset($this->aAddParam["div_outer"]))
      {
        $aStdContentBlockContent["div_outer"] = $this->aAddParam["div_outer"];
      }
      else
      {
        $aStdContentBlockContent["div_outer"] = "one";
      }
      
      if(isset($this->aAddParam["div_inner"]))
      {
        $aStdContentBlockContent["div_inner"] = $this->aAddParam["div_inner"];
      }
      else
      {
        $aStdContentBlockContent["div_inner"] = "a";
      }
      
      //$this->sOutputPartit = $mlVexflowNote->GetMLString()." VexFlowNote ".$this->sInputPartit;
      //$this->sOutputPartit = $mlVexflowNote->GetMLString()." VexFlowNote ".$this->GetStdBlock($aStdContentBlockContent);
      $this->sOutputPartit = $this->GetSpecBlock(/* $aBlockContent */ $aStdContentBlockContent, /* $sTplFileName */ _VEXFLOW_NOTE_TPL_FILE);
      $this->sOutputPartit = $this->GetSpecBlock(/* $aBlockContent */ array("title" => $mlVexflowNote->GetMLString(), "content" => $this->sOutputPartit), /* $sTplFileName */ _PART_ONE_TPL_NAME);
    }
    else
    {
      $this->sOutputPartit = "((((((((((((((((((( OUCH! UNKNOWN FORMAT ".$this->sInputPartit;
    }
    return;
  }
  
  private function /* String */ GetVexflowNoteByMidi(/* int */ $iMidiNoteNum, /* float */ $fMidiNoteDur, &$sNote)
  {
    /* String */ $sRes = "";
    $uUtil = new Util();
    //keys: ["c/4"], duration: "q"
    $aDiatonicScale = array("a", "b", "c", "d", "e", "f", "g");
    $aHromaticScale = array("c", "c#", "d", "d#", "e", "f", "f#", "g", "g#", "a", "a#", "b");
    //$sNote = $aDiatonicScale[$iMidiNoteNum%7];
    //echo "<br />\$iMidiNoteNum = $iMidiNoteNum<br /><br />";
    $sNote = $aHromaticScale[$iMidiNoteNum%12];
    //echo "<br />\$sNote = $sNote<br /><br />";
    //$iOct = rand(3, 5);
    $iOct = 4;
    $uUtil = new Util();
    
    //$sRes = "{ keys: [\"".$sNote."/".$iOct."\"], duration: \"q\" }";
    $sRes = "{ keys: [\"".$sNote."/".$iOct."\"], duration: \"".$uUtil->RndNum2VexflowDur($fMidiNoteDur)."\" }";
    return $sRes;
  }
  
  private function /* array */ ParseInputPartitStd(/* array */ $aParamArr)
  {
    $aRes = array();
    for($i = 0; $i < 4; $i++)
    {
      $iMidiNoteNum = rand(0, 12)+50;
      $aRes["note".$i] = $this->GetVexflowNoteByMidi(/* $iMidiNoteNum */ $iMidiNoteNum, /* $iMidiNoteDur */ 15, $sNote);
    }
    return $aRes;
  }
  
  private function /* array */ ParseInputPartit(/* array */ $aParamArr, /* float */ &$fTotalDur)
  {
    $aRes = array();
    $aRes = $this->ParseInputPartitStd($aParamArr);
    $fTotalDur = 0;

    if(is_array($aParamArr) && count($aParamArr))
    {
      $aParamArr = $aParamArr["notes"];
      if(is_array($aParamArr) && count($aParamArr))
      {
        $aParamArr = $aParamArr["note"];
        
        if(is_array($aParamArr) && count($aParamArr))
        {
          $aRes = array();
          $aAccid = array();
          foreach($aParamArr as $k => $v)
          {
            if((isset($v["pitch"][0][0])) && (isset($v["dur"][0][0])))
            {
              $iMidiNoteNum = $v["pitch"][0][0];
              $fMidiNoteDur = $v["dur"][0][0];
              //$fMidiNoteDur = 1.0;
              
              //echo "ParseInputPartit \$fMidiNoteDur => $fMidiNoteDur<br />";
              //echo "ParseInputPartit \$iMidiNoteNum => $iMidiNoteNum<br />";
              $fTotalDur += $fMidiNoteDur;
              $sNote = "";
              $sNoteCurrent = $this->GetVexflowNoteByMidi(/* $iMidiNoteNum */ abs($iMidiNoteNum), /* $fMidiNoteDur */ $fMidiNoteDur, $sNote);
              //$aRes["note".$k] = $this->GetVexflowNoteByMidi(/* $iMidiNoteNum */ abs($iMidiNoteNum), /* $fMidiNoteDur */ $fMidiNoteDur, $$sNote);
              $aRes["note".$k] = $sNoteCurrent;
              if(strpos($sNoteCurrent, "#") !== false)
              {
                $aRes["accid".$k] = ".addAccidental(0, new Vex.Flow.Accidental(\"#\"))";
                $aAccid[] = $sNote;
              }
              //else
              //if(strpos($sNoteCurrent, "b") !== false)
              //{
              //  $aRes["accid".$k] = ".addAccidental(0, new Vex.Flow.Accidental(\"b\"))";
              //}
              else
              {
                if (count($aAccid) && (in_array($sNote."#", $aAccid))) 
                {
                  $aRes["accid".$k] = ".addAccidental(0, new Vex.Flow.Accidental(\"n\"))";
                }
              }
            }
          }
        }
      }
    }
    //echo "<br />+++++++++++++++++++++++++++++++<br />";
    //print_r($aRes);
    //echo "<br />+++++++++++++++++++++++++++++++<br />";
    //var_dump($aRes);
    //echo "<br />+++++++++++++++++++++++++++++++<br />";
    return $aRes;
  }
}
?>