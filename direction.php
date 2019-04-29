<?php
include_once("./inc/session.class.php");
include_once("./inc/message.class.php");


define("_SESSION_STARTED", 1);

define("_CONTENT_TPL_FILE", "direction.tpl");

$iPageId = 7;
$aContentNew = array();
$sTitleNew = "Direction";
$sSubTitleNew = "See multimedia input/ output of objects and patches";

include_once("./inc/server.class.php");

/* class SessionHandle */ $shCurrentSession = new SessionHandle();
$shCurrentSession->Dispatch();
/* class Message */ $mInput = new Message($mlXML, /* Входные_перемнные : class Array */ array());
/* class String */ $sOut = "";
/* class Server */ $sSrv = new Server($shCurrentSession, $mInput, $sOut);
//echo $sOut;


$aContentNew = array(
	"body" => $sOut,
	"NextRefreshTime" => $sSrv->GetNextRefreshTimePub()
);
//print_r($aContentNew);
include_once("./inc/show_page.class.php");

unset($sSrv);
unset($mInput);
unset($shCurrentSession);
?>