<?php
//transformation.class.php

//include_once("../../inc/message.class.php");
include_once("C:/Program Files/wamp/www/dev2.3/inc/message.class.php");

class Transformation extends Message
{
  protected $mlInputFormat;
  protected $mlOutputFormat;
  protected /* ?? */ $xMsg; //what about polymorphic?

  public function /* */  __construct()
  {
	//...
  }
  
  //public function SetMessage(/* ?? */ $xMsgNew)
  //{
  //  $this->xMsg = $sSerializedMsgNew;
  //}
  
  public function SetMessage(/* ?? */ $xMsgNew)
  {
    $this->xMsg = $xMsgNew;
  }
  
  /* ?? */ public function GetMessage()
  {
    ///...
  }
}
?>