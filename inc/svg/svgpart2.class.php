<?php
//svgpart2.class.php

include_once("C:/Program Files/wamp/www/dev2.5/inc/markup_language.class.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/XML2Assoc.class.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/template.class.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/file.class.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/config.inc.php");

class SVGPart extends Partit
{
  private /* Template */ $tTpl;
  
  public function /* void */ TranslatePartit()
  {
    global $mlSVG, $mlHTML;
    
    /* array */ $aParamArr = $this->xaParser->parseString($this->sInputPartit, false);
    
    if($mlSVG == $this->mlOutputFormat)
    {
      
      //$aStdContentBlockContent = array();
      //$this->sOutputPartit = $this->GetSpecBlock(/* $aBlockContent */ $aStdContentBlockContent, /* $sTplFileName */ _CHART_SVG_TPL_FILE); //_SVG_TPL_FILE 
      
      $data = array();
      $data = $this->GetRndMap(0, 40, 8, 24);
      //var_dump($data);
      $values = array();
      $coll = array();
      $count = 0;
      ///* class FileAbstr */ $faSVGTplFile = new FileAbstr(_CHART_SVG_NEW_TPL_FILE);
      /* class FileAbstr */ $faSVGTplFile = new FileAbstr(_SVG_GRAPH_PARTIT_TPL_FILE);
      $tTpl = new Template($mlHTML, /* FileAbstr */ $faSVGTplFile);
      //TPL->getCatalog("chart", _CHART_SVG_NEW_TPL_FILE, $data, $values, $coll);
      //$this->sOutputPartit = $tTpl->SerializeMap($data, $coll, $values, $count);
      $data2 = array();
      $this->sOutputPartit = $tTpl->SerializeArray($data2);
      
      $this->sOutputPartit = $this->GetSpecBlock(/* $aBlockContent */ array("title" => $mlSVG->GetMLString(), "content" => $this->sOutputPartit), /* $sTplFileName */ _PART_ONE_TPL_NAME);
    }
    else
    {
      $this->sOutputPartit = "((((((((((((((((((( OUCH! UNKNOWN FORMAT ".$this->sInputPartit;
    }
    return;
  }
  
  private function GetRndMap($iMin, $iMax, $iCntX, $iCntY)
  {
    for($i = 0; $i<$iCntX; $i++)
    {
      for($j = 0; $j<$iCntY; $j++)
      {
        $data[$i]["num".$j] = $this->GetRndInt($iMin, $iMax);
      }
    }
    return $data;  
  }
  
  private function GetRndInt($iMin, $iMax)
  {
    return rand($iMin, $iMax);
  }
}
?>