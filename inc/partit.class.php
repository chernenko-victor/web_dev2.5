<?php

include_once("config.inc.php");
include_once("file.class.php");
include_once("template.class.php");
include_once("markup_language.class.php");
include_once("XML2Assoc.class.php");

class Partit
{
  protected $mlInputFormat;
  protected $mlOutputFormat;
  protected $sInputPartit;
  protected $sOutputPartit;
  protected $sName;
  protected $xaParser;
  protected $aAddParam;

  public function /* Partit */ __construct(/* MarkupLanguage */ $mlNewOutputFormat, /* String */ $sNewInputPartit, /* MarkupLanguage */ $mlNewInputFormat, /* String */ $sNewName = "", $aAddParamNew = false)
  {
    /* MarkupLanguage */ $this->mlInputFormat = $mlNewInputFormat;
    /* MarkupLanguage */ $this->mlOutputFormat = $mlNewOutputFormat;
    /* String */ $this->sInputPartit = $sNewInputPartit;
    /* String */ $this->sOutputPartit = "";
    /* String */ $this->sName = $sNewName;
    $this->xaParser = new Xml2Assoc();
    //$this->TranslatePartit();
    $this->aAddParam = array();
    if($aAddParamNew)
    {
      $this->aAddParam = $aAddParamNew;
    }
  }
  
  public function /*~Partit */ __destruct()
  {
    unset($this->aAddParam);
    unset($this->xaParser);
    unset($this->sOutputPartit);
    unset($this->sInputPartit);
    unset($this->mlOutputFormat);
    unset($this->mlInputFormat);
  }
  
  public function /* void */ TranslatePartit()
  {
    //global $mlVexflowNote;
    
    //echo "<br /><strong>before parsing</strong><br />===============================================<br />";
    //var_dump($this->sInputPartit);
    //echo "<br />===============================================<br /><br />";
    
    /* array */ $aParamArr = $this->xaParser->parseString($this->sInputPartit, false);
    
    //var_dump($mlVexflowNote);
    //var_dump($this->mlOutputFormat);
    //echo "<br />INSIDE Partit<br />";
        
    //if($mlVexflowNote == $this->mlOutputFormat)
    //{
    //  echo "<br /><strong>after parsing</strong><br />===============================================<br />";
    //  print_r($aParamArr);
    //  echo "<br />===============================================<br /><br />";
    //  $this->sOutputPartit = $mlVexflowNote->GetMLString()." ++==++== ".$this->sInputPartit;
    //}
    //else if($mlSVG == $this->mlOutputFormat) // redifine == operator? it's impossible
    //{
    //  echo "<br /><strong>after parsing</strong><br />===============================================<br />";
    //  print_r($aParamArr);
    //  echo "<br />===============================================<br /><br />";
    //  $this->sOutputPartit = $mlVexflowNote->GetMLString()." )()()()()()()) ".$this->sInputPartit;
    //}
    //else
    //{
    //  $this->sOutputPartit = $this->sInputPartit;
    //}
    $this->sOutputPartit = "((((((((((((((((((( OUCH! UNKNOWN FORMAT ".$this->sInputPartit;
    return;
  }
  
  public function /* String */ GetTranslatedPartit()
  {
    //echo "<br />========= \$this->sOutputPartit ===============<br />";
    //echo $this->sOutputPartit;
    //echo "<br />========= /\$this->sOutputPartit ===============<br />";
    return $this->sOutputPartit;
  }
  
  public function /* String */ GetPartitName()
  {
    return $this->sName;
  }
  
  public function /* String */ GetInputFormat()
  {
    return $this->mlInputFormat;
  }
  
  public function /* String */ GetOutputFormat()
  {
    return $this->mlOutputFormat;
  }
  
  protected function /* String */ GetSpecBlock(/* Array */ $aBlockContent, /* string */ $sTplFileName)
  {
    global $mlHTML;
    /* class String */ $sRes = "";
    /* class FileAbstr */ $faStdContentBlockTplFile = new FileAbstr(/* String */ $sTplFileName);
    /* class Template */ $tStdContentBlockTpl = new Template($mlHTML, $faStdContentBlockTplFile);
    $sRes = $tStdContentBlockTpl->SerializeArray ($aBlockContent);
    unset($tStdContentBlockTpl);
    unset($faStdContentBlockTplFile);
    return $sRes;
  }
}

?>