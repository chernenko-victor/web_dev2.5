<?php
//permission.class.php

include_once("session.class.php");
include_once("storage.class.php");

class Permission
{
  private /* SessionHandle */ $shSession;
  private /* Storage */ $stStore;
  private /* int */ $iGrId;
  
  public function /* Permission */ __construct(/* SessionHandle */ $shSessionNew)
  {
    $this->shSession = new SessionHandle();
    $this->shSession = $shSessionNew;
    $this->stStore = new Storage();
    $this->iGrId = $this->shSession->GetUserGrIdFromDb();
  }
  
  public function /* ~Permission */ __destruct()
  {
    unset($this->iGrId);
    unset($this->stStore);
    unset($this->shSession);
  }
  
  public function /* bool */ AllowAction($sMsgName, $sActionName)
  {
    /* bool */ $bRes = false;
    /* int */ $iActionId = 0;
    /* int */ $iMsgId = 0;
    switch($sActionName)
    {
      case _READ_ACTION_NAME:
        $iActionId = 1;
      break;
      case _WRITE_ACTION_NAME:
        $iActionId = 2;
      break;
      case _DEL_ACTION_NAME:
        $iActionId = 3;
      break;
    };
    switch($sMsgName)
    {
      case _AUTH_MSG_NAME:
        $iMsgId = 3;
      break;
      case _MENU_MSG_NAME:
        $iMsgId = 8;
      break;
      case _INFO_MSG_NAME:
        $iMsgId = 4;
      break;
      case _PARTIT_MSG_NAME:
        $iMsgId = 1;
      break;
      case _NEXT_UPD_TIME_MSG_NAME:
        $iMsgId = 7;
      break;
    };
    $bRes = $this->stStore->GetActionAllow($iActionId, $iMsgId, $this->iGrId);
    return $bRes;
  }
  
  public function /* int */ GetGrId()
  {
    return $this->iGrId;
  }
  
  public function /* string */ GetUserName()
  {
    return $this->shSession->GetUserName();
  }
}
?>