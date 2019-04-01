<?php
define("_CONTENT_TPL_FILE", "search.tpl");

if(isset($_GET["search_string"]))
{
  $sSearchString = $_GET["search_string"];
}
else
{
  $sSearchString = "";
}

$iPageId = 8;
$aContentNew = array(
    "search_string" => $sSearchString
);
$sTitleNew = "Search result";
$sSubTitleNew = "Find what you want in system";

include_once("./inc/show_page.class.php");
?>