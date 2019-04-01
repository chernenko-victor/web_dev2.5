<?php
//error.class.php

include_once("message.class.php");
include_once("markup_language.class.php");
include_once("file.class.php");
//include_once("bool.class.php"); -- для общности, класс встроенный
//include_once("string.class.php"); -- для общности, класс встроенный

class ErrorHandle extends Message
{
  public function /* ErrorHandle */ __construct() 
  {
    //...
  }
  
  public function /* ~ErrorHandle */ __destruct()
  {
    //...
  }
  
  public function /* void */ AddMessage(/* Message */ $mMsgNew)
  {
    //...
  }
  
  public function /* string */ GetBlock (/* MarkupLanguage */ $mlMarkupLangNew, /* FileAbstr */ $faTemplateFile)
  {
    /* string */ $sRes = "";
    //...
    return $sRes;
  }
  
  public function /* bool */ IsEmpty ()
  {
    /* bool */ $bRes = true;
    //...
    return $bRes;
  }
  
  public function /* int */ GetErrorCount ()
  {
    /* int */ $iRes = 0;
    //...
    return $iRes;
  }
}
?>