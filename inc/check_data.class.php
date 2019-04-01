<?php
// check_data.class.php
include_once("message.class.php");
include_once("config.inc.php");

class CheckData
{
  private $mMsgToCheck;

  public function /* CheckData */ __construct() 
  {
    global $mtUnknown;
    $mMsgToCheck = new Message($mtUnknown, array());
  }
  
  public function /* ~CheckData */ __destruct()
  {
    unset($this->mMsgToCheck);
  }
  
  public function /* bool */ CheckMessage (/* Message */ $mMsgToCheckNew)
  {
    /* bool */ $bRes = true;
    $this->mMsgToCheck = $mMsgToCheckNew;
    //...
    return $bRes;
  }
  
  public function /* bool */ CheckString (/* string */ $sToCheck)
  {
    /* bool */ $bRes = true;
    //...
    return $bRes;
  }
}
?>