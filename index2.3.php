<?php
//index2.3.php

include_once("./inc/config.inc.php");
include_once("./inc/options.inc.php");
include_once("./inc/session.class.php");
include_once("./inc/message.class.php");
//include_once("./inc/class Array"); -- ��� ��������, ����� ����������
//include_once("./inc/string.class.inc"); -- ��� ��������, ����� ����������
include_once("./inc/server.class.php");

/* class SessionHandle */ $shCurrentSession = new SessionHandle();
$shCurrentSession->Dispatch();
/* class Message */ $mInput = new Message($mlXML, /* �������_��������� : class Array */ array());
/* class String */ $sOut = "";
/* class Server */ $sSrv = new Server($shCurrentSession, $mInput, $sOut);
echo $sOut;
unset($sSrv);
unset($mInput);
unset($shCurrentSession);
?>