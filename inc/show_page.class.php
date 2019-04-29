<?php
if (!defined('_FULL_PAGE_TPL_FILE'))
{
    define("_FULL_PAGE_TPL_FILE", "index.tpl");
}
if (!defined('_HEADER_TPL_FILE'))
{
	define("_HEADER_TPL_FILE", "header.tpl");
}
define("_FOOTER_TPL_FILE", "footer.tpl");

include_once("config.inc.php");
include_once("options.inc.php");
include_once("session.class.php");
include_once("message.class.php");
//include_once("class Array"); -- ??? ????????, ????? ??????????
//include_once("string.class.inc"); -- ??? ????????, ????? ??????????
include_once("file.class.php");
include_once("template.class.php");

class ShowPage
{
  private $sPage;

  public function /* Server */ __construct($sContenetTplFile, &$aContentNew, $sTitleNew, $sSubTitleNew, $iIdNew = 0, $shCurrentSession = null)
  {
    global $mlXML, $mlHTML;
	if($shCurrentSession == null)
	{
		/* class SessionHandle */ $shCurrentSession = new SessionHandle();
		$shCurrentSession->Dispatch();
	}
	/* class Message */ $mInput = new Message($mlXML, /* ???????_????????? : class Array */ array());
	if(($iUID = $shCurrentSession->GetUserId())==0)
	{
	  $sUNm = "";
	}
	else
	{
	  $sUNm = $shCurrentSession->GetUserName();
	}

	$aHeader = array(
	    "id" => $iIdNew
	  , "user_id" => $iUID
	  , "user_nm" => $sUNm
	  , "next_time_refresh" => $aContentNew["NextRefreshTime"]
	);
	/* class Template */ $tHeaderTpl = new Template($mlHTML, null, _HEADER_TPL_FILE);
	$sHeader = $tHeaderTpl->GetStdBlock($aHeader);

	$aFooter = array();
	/* class Template */ $tFooterTpl = new Template($mlHTML, null, _FOOTER_TPL_FILE);
	$sFooter = $tFooterTpl->GetStdBlock($aFooter);

	//$aContent = array();
	$aContent = $aContentNew;
	/* class Template */ $tContentTpl = new Template($mlHTML, null, $sContenetTplFile);
	$sContent = $tContentTpl->GetStdBlock($aContent);

	//$sTitle = "COIMS overview";
	//$sSubTitle = "Main elements of system";
	$sTitle = $sTitleNew;
	$sSubTitle = $sSubTitleNew;

	$aPagePart = array(
		"header" => $sHeader
	  , "footer" => $sFooter
	  , "content" => $sContent
	  , "title" => $sTitle
	  , "subtitle" => $sSubTitle
	);
	///* class FileAbstr */ $faFullPageTplFile = new FileAbstr(/* class String */ _FULL_PAGE_TPL_FILE);
	///* class Template */ $tFullPageTpl = new Template($mlHTML, $faFullPageTplFile);
	//echo $sOut = $tFullPageTpl->SerializeArray ($aPagePart);

	/* class Template */ $tFullPageTpl = new Template($mlHTML, null, _FULL_PAGE_TPL_FILE);
	$this->sPage = $tFullPageTpl->GetStdBlock($aPagePart);

	unset($tFullPageTpl);
	unset($mInput);
	unset($shCurrentSession);
  }
  
  public function /* string */ GetPage()
  { 
    return $this->sPage;
  }
}

/*
if(!isset($_GET["id"])) 
{
  $id = 1;
}
else
{
  $id = $_GET["id"];
}
*/

if(!isset($iPageId)) 
{
  $iPageId = 0;
}

if(!isset($shCurrentSession))
{
	$shCurrentSession = null;
}

$spCurrent = new ShowPage(_CONTENT_TPL_FILE, $aContentNew, $sTitleNew, $sSubTitleNew, $iPageId, $shCurrentSession);
echo $spCurrent->GetPage();
?>