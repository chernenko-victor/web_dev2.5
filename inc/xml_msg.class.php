<?php
include_once("message.class.php");
include_once("config.inc.php");

//xml_msg.class.php
class XMLMsg extends Message
{
  public function /* Message */ __construct(/* Array */ $piLocal) //ParameterIn
  {
	global $mlXML;
    parent::__construct(/* MessageType */ $mlXML, /* Array */ $piLocal);
  }
  
  /* string */ public function GetMessage() 
  {
	  $sRes = "";
	  //....
	  return $sRes;
  }
}
?>