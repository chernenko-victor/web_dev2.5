<?php
// Название сайта
$COMMON["site_name"] = "algo DEv2.3";

// Общие настройки
$CONFIG["root"]    = $_SERVER["DOCUMENT_ROOT"];
$CONFIG["pic_dir"] = $CONFIG["root"]."dev2.3/algo/img/";
$CONFIG["file_dir"] = $CONFIG["root"]."/files/";
$CONFIG["main_email"] = "chernenko.victor@gmail.com";
$CONFIG["site"]="http://localhost/dev2.3/algo";
$CONFIG["admin_email"]="chernenko.victor@gmail.com";
$CONFIG["tmp_dir"] = "/home/u52669/litradio.ru/tmp";
$CONFIG["tpl_dir"] = $CONFIG["root"]."dev2.3/algo/tpl/";
$CONFIG["admin_contact_disclamer"] = "свяжитесь с администратором сайта ".$CONFIG["admin_email"];
$CONFIG["wrong_login_disclamer"] = " Повторите попытку залогиниться еще один раз, в случае повторения ошибки ".$CONFIG["admin_contact_disclamer"];

// Настройки для функции upLoadFile()
$CONFIG["uploads_type"]["img"] = array("jpg", "jpeg", "gif", "png");
$CONFIG["uploads_type"]["file"] = array("zip", "rar", "txt", "doc", "pdf");

$CONFIG["max_img_size"] = array("1600", "1600");

// Настройки для базы данных
$MYSQL["db_name"]  = "generative_grammar";
$MYSQL["user"]     = "root";
$MYSQL["password"] = "";
$MYSQL["host"]     = "localhost";
$MYSQL["table_prefix"]     = "";

/*
// Настройки для базы данных
$MYSQL["db_name"]  = "dev2v2_3";
$MYSQL["user"]     = "root";
$MYSQL["password"] = "";
$MYSQL["host"]     = "localhost";
$MYSQL["table_prefix"]     = "r5u7_";
*/

// -- Subscribe

//$form_param["subscr_form"]["lenght"] = array("email" => 255);
//$form_param["subscr_form"]["type"]   = array("email" => 3);
//$form_param["subscr_form"]["empty"]  = array("email" => 1);
//$form_param["subscr_form"]["name"]   = array("email" => "Email");
?>