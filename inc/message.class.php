<?php
// message.class.php
include_once("message_type.class.php");
//include_once("class Array"); -- для общности, класс встроенный

class Message
{
  //private $mtMessageType;
  protected $mlOutputFormat;
  protected $aVars; //ParameterIn

  //public function /* Message */ __construct()
  //{
  //  /* MessageType */ $this->mtMessageType = new MessageType("");
  //  /* Array */ $this->aVars = Array();
  //}
  
  public function /* Message */ __construct(/* MessageType */ $mlOutputFormatNew, /* Array */ $aVarsNew)
  {
    /* MessageType */ $this->mlOutputFormat = $mlOutputFormatNew;
    /* Array */ $this->aVars = $aVarsNew;
  }
  
  public function /*~Message */ __destruct()
  {
    unset($this->mlOutputFormat);
    unset($this->aVars);
  }
  
  /* string */ public function GetMessage()
  {}
}
?>