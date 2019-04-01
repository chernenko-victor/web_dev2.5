<?php
//template.class.php
include_once("markup_language.class.php");
include_once("file.class.php");
//include_once("class Array"); -- для общности, класс встроенный
include_once("message.class.php");
include_once("template_abstr.class.php");

class Template
{
  private /* TemplateAbstr */ $taHTMLTpl;
  private /* FileAbstr */ $faTplFile;
  
  public function /* Template */ __construct(/* MarkupLanguage */ $mlMarkupLangNew, /* FileAbstr */ $faTplFileNew = null, /* String */ $sFNm = null)
  {
    global $CONFIG;
    //$this->faTplFile = new FileAbstr("");
	if($faTplFileNew)
	{
	  $this->faTplFile = $faTplFileNew;
	}
	else if($sFNm)
	{
	  $this->faTplFile = new FileAbstr($sFNm);
	}
	else
	{
	  $this->faTplFile = new FileAbstr("");
	}
    
    $this->taHTMLTpl = new TemplateAbstr($CONFIG["tpl_dir"], $CONFIG["pic_dir"]);
  }
    
  public function /* ~Template */ __destruct() 
  {
    unset($this->taHTMLTpl);
    unset($this->faTplFile);
  }
  
  /* String */ public function SerializeArray(/* Array */ &$aArrToSeralizeNew)
  {
    /* String */ $sRes = "";
    $sRes = $this->taHTMLTpl->setTemplate("serialize_arr", $this->faTplFile->GetFileName(), $aArrToSeralizeNew);
    return $sRes;
  }
  
  /* String */ public function SerializeMessage(/* Message */ $mMsgToSeralizeNew)
  {
    /* String */ $sRes = "";
    //...
    return $sRes;
  }
  
  /* String */ public function SerializeMap($data, $coll, $values, &$count, $f = false)
  {
    /* String */ $sRes = "";
    if (function_exists($f)) $f($data, $values);
    $count = count($data);
    $sRes = $this->taHTMLTpl->getCatalog("catalog", $this->faTplFile->GetFileName(), $data, $values, $coll);
    return $sRes;
  }
  
  /* String */ public function /* String */ GetStdBlock(/* Array */ &$aStdBlockContent)
  {
  //  global $mlHTML;
    /* class String */ $sRes = "";
  //  /* class FileAbstr */ $faStdContentBlockTplFile = new FileAbstr(/* class String */ $sFNm);
  //  /* class Template */ $tStdContentBlockTpl = new Template($mlHTML, $faStdContentBlockTplFile);
  //  $aStdContentBlockContent = array("title" => $sTitle, "content" => $sContent);
    $sRes = $this->SerializeArray ($aStdBlockContent);
  //  unset($tStdContentBlockTpl);
  //  unset($faStdContentBlockTplFile);
    return $sRes;
  }
}
?>