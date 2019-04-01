<?php
//config.inc.php

include_once("options.inc.php");
include_once("message_type.class.php");
include_once("markup_language.class.php");
include_once("message.class.php");

/* MessageType */ $mtError = new MessageType("Error");
/* MessageType */ $mtUnknown = new MessageType("Unknown");
/* class MarkupLanguage */ $mlHTML = new MarkupLanguage("HTML");
/* class MarkupLanguage */ $mlXML = new MarkupLanguage("XML");
/* class MarkupLanguage */ $mlVexflowNote = new MarkupLanguage("VexflowNote");
/* class MarkupLanguage */ $mlSVG = new MarkupLanguage("SVG");
/* class MarkupLanguage */ $mlUnknown = new MarkupLanguage("Unknown");
/* class MarkupLanguage */ $mlGen = new MarkupLanguage("Gen");

//удаленный адрес пользователя не совпадает с сохраненным в БД
/* class Message */ $_mSessWrongUserAddr = new Message($mtError, /* Входные_перемнные : class Array */ array("text" => "Удаленный адрес пользователя не совпадает с сохраненным в БД.".$CONFIG["wrong_login_disclamer"]));

//Некорректно введены логин и/ или пароль.
/* class Message */ $_mSessWrongUserData = new Message($mtError, /* Входные_перемнные : class Array */ array("text" => "Некорректно введены логин и/ или пароль.".$CONFIG["wrong_login_disclamer"]));

//Данных о сессии нет в БД.
/* class Message */ $_mSessWrongDataInDB = new Message($mtError, /* Входные_перемнные : class Array */ array("text" => "Данных о сессии нет в БД.".$CONFIG["wrong_login_disclamer"]));

//Ошибка записи данных о сессии в БД.
/* class Message */ $_mInsertUserSessionData = new Message($mtError, /* Входные_перемнные : class Array */ array("text" => "Ошибка записи данных о сессии в БД.".$CONFIG["wrong_login_disclamer"]));

//Пользователь с такими данными не зарегистрирован.
/* class Message */ $_mNoUserInDB = new Message($mtError, /* Входные_перемнные : class Array */ array("text" => "Пользователь с такими данными не зарегистрирован.".$CONFIG["wrong_login_disclamer"]));

//Некорректные данные сессии.
/* class Message */ $_mSessWrongSessionData = new Message($mtError, /* Входные_перемнные : class Array */ array("text" => "Некорректные данные сессии.".$CONFIG["wrong_login_disclamer"]));

define("_AUTH_MSG_NAME", "auth");
define("_MENU_MSG_NAME", "menu");
define("_INFO_MSG_NAME", "info");
define("_PARTIT_MSG_NAME", "partit");
define("_NEXT_UPD_TIME_MSG_NAME", "next_upd_time");

define("_READ_ACTION_NAME", "read");
define("_WRITE_ACTION_NAME", "write");
define("_DEL_ACTION_NAME", "del");

define("_VEXFLOW_NOTE_TPL_FILE", "test_vexflow2.tpl");
define("_SVG_TPL_FILE", "test_svg.tpl");
define("_CHART_SVG_TPL_FILE", "chart_svg.tpl");
define("_CHART_SVG_NEW_TPL_FILE", "chart_svg_new.tpl");
define("_SVG_GRAPH_PARTIT_TPL_FILE", "svg_graph_partit.tpl");
define("_PART_ONE_TPL_NAME", "part_one.tpl");
define("_GROUP_CHANGE_TPL_NAME", "group_change.tpl");
define("_KEY_VAL_TPL_NAME", "key_val_tbl.tpl");

$afDur = array();
$afDur["f32thDur"] = (float)1/(float)32;
$afDur["f16thDur"] = (float)1/(float)16;
$afDur["f16thDotDur"] = $afDur["f16thDur"] + $afDur["f32thDur"]; // 1/16+1/32
$afDur["f8thDur"] = (float)1/(float)8;
$afDur["f8thDotDur"] = $afDur["f8thDur"] + $afDur["f16thDur"]; // 1/8+1/16
$afDur["fQuartDur"] = (float)1/(float)4;
$afDur["fQuartDotDur"] = $afDur["fQuartDur"] + $afDur["f8thDur"] ; // 1/4+1/8
$afDur["fHalfDur"] = (float)1/(float)2;
$afDur["fHalfDotDur"] = $afDur["fHalfDur"] + $afDur["fQuartDur"]; // 1/2+1/4
$afDur["fWholeDur"] = (float)1;
?>