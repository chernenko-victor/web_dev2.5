<?php
//server.class.php

define("_ERROR_BLOCK_NAME", "error");
define("_MENU_BLOCK_NAME", "menu");
define("_AUTH_BLOCK_NAME", "auth");
define("_INFO_BLOCK_NAME", "info");
define("_PARTIT_BLOCK_NAME", "partit");
define("_NEXT_TIME_REFRESH", "next_time_refresh");
define("_BODY", "body");
define("_TITLE", "title");

//define("_FULL_PAGE_TPL_FILE", "index2.3.tpl");
//define("_FULL_PAGE_TPL_FILE", "index.tpl");
define("_DIRECTION_TPL_FILE", "direction.tpl");
define("_HEADER_TPL_FILE", "header_direct.tpl");
define("_INLINE_BLOCK_TPL_FILE", "inline_block.tpl");
//define("_PARTIT_BLOCK_TPL_FILE", "partit_block.tpl");
define("_STD_CONTENT_BLOCK_TPL_FILE", "std_content_block.tpl");

include_once("config.inc.php");
include_once("session.class.php");
include_once("message.class.php");
//include_once("string.class.inc"); -- для общности, класс встроенный
//include_once("class Array"); -- для общности, класс встроенный
include_once("error.class.php");
include_once("check_data.class.php");
include_once("permission.class.php");
include_once("file.class.php");
include_once("template.class.php");
//include_once("bool.class.php"); -- для общности, класс встроенный
include_once("storage.class.php");
include_once("algo.class.php");
include_once("util.class.php");
include_once("partit.class.php");
include_once("markup_language.class.php");

//maybe dynamic include later
include_once("vexflow/vexflownote.class.php");
//include_once("svg/svgpart.class.php");
include_once("svg/svgpart2.class.php");
include_once("group_change/group_change.class.php");

include_once("generator_ctrl.class.php"); //управление генераторами

class Server
{
  private /* Storage */ $stStore;
  private /* SessionHandle */ $shSession;
  private /* array(int) */$aiRefreshTimes;
  private /* Utility */ $uUtility;
    
  public function /* Server */ __construct(/* class SessionHandle */ &$shCurrentSession, /* class Message */ $mInput, /* class String */ &$sOut) 
  {
    //class String $sOut;
    /* class Array */ $aPagePart = array();
    /* class ErrorHandle */ $ehAllError = new ErrorHandle();
    /* class CheckData */ $cdCheckData = new CheckData();
    global $mtError, $mlHTML, $COMMON;
    $this->stStore = new Storage();
    $this->shSession = new SessionHandle();
    $this->shSession = $shCurrentSession;
    $this->aiRefreshTimes = array();
    $this->uUtility = new Util();

    // проверяет корректность данных (против инъекций).
    if (!$cdCheckData->CheckMessage($mInput))
    {
      //формируем сообщение об ошибке
      /* class Message */ $mUncorrectData = new Message($mtError, array("text" => "Некорректные данные"));
      $ehAllError->AddMessage($mUncorrectData);
    }
    else
    {
      //Сервер проверяет, зарегистрирован ли такой добрый молодец. Если да, то достает его права, в противном случае выставляются права по умолчанию. (В БД обновляются данные о его сессии - это происходи в классе сессия).
      /* class Permission */ $pClientPermission = new Permission($this->shSession);
      //if (!$this->IsRegistered($pClientPermission))
      //{
      //  /* class FileAbstr */ $faAuthFormTplFile = new FileAbstr();
      //  /* class Template */ $tAuthFormTpl = new Template($mlHTML, $faAuthFormTplFile);
      //  $sOut = $tAuthFormTpl->SerializeArray (array()) ;
      //  //return $sOut;
      //  return;
      //}
      //else
      //{
        //В зависимости от его прав форируется форма авторизации.
        /* class String */ $sAuthBlock = $this->GetAuthBlock($pClientPermission);
        //$aPagePart->Add(_MENU_BLOCK_NAME, $sMenuBlock);
        //$aPagePart[_AUTH_BLOCK_NAME] = $sAuthBlock;
      
        //В зависимости от его прав форируется меню.
        /* class String */ $sMenuBlock = $this->GetMenuBlock($pClientPermission);
        //$aPagePart->Add(_MENU_BLOCK_NAME, $sMenuBlock);
        //$aPagePart[_MENU_BLOCK_NAME] = $sMenuBlock;

        //В зависимости от его прав формируется информационный блок (ну это приветствие, сообщение что тебе есть сообщения и проч.)
        /* class String */ $sInfoBlock = $this->GetInfoBlock($pClientPermission);
        //$aPagePart->Add(_INFO_BLOCK_NAME, $sInfoBlock);
        //$aPagePart[_INFO_BLOCK_NAME] = $sInfoBlock;

        //_Смотрим есть ли генераторы, в которых он зареген, и есть ли какие-то ожидающие его сообщения от этих генераторов.
        //_В зависимости от его прав показываем $sPartitBlock._
        /* class String */ $sPartitBlock = $this->GetPartitBlock($pClientPermission);
        $aPagePart[_PARTIT_BLOCK_NAME] = $sPartitBlock;
      
        /* class String */ $sNextRefreshTime = $this->GetNextRefreshTime($pClientPermission);
        $aPagePart[_NEXT_TIME_REFRESH] = $sNextRefreshTime;
      //}
    }
    
    //Формируется сообщение об ошибках если нужно.
    if (!$ehAllError->IsEmpty()) 
    {
      /* class FileAbstr */ $faErrorMessageTplFile = new FileAbstr();
      /* class Template */ $tErrorTpl = new Template($mlHTML, $faErrorMessageTplFile);
      $aPagePart[_ERROR_BLOCK_NAME] = $tErrorTpl->SerializeMessage($ehAllError);
    }
    
    //Формируется и показывается пользователю цельная страница.
    $this->GetFullPage($aPagePart, $COMMON["site_name"]);
    
    /* class FileAbstr */ $faFullPageTplFile = new FileAbstr(/* class String */ _DIRECTION_TPL_FILE);
    /* class Template */ $tFullPageTpl = new Template($mlHTML, $faFullPageTplFile);
    $sOut = $tFullPageTpl->SerializeArray ($aPagePart);
    //return $sOut;
    return;
  }

  public function /* ~Server */ __destruct()
  {
    unset($this->uUtility);
    unset($this->aiRefreshTimes);
    unset($this->shSession);
    unset($this->stStore);
  }
  
  private function /* class Bool */ IsRegistered (/* class Permission */ &$pClientPermission) //заодно и разрешения 
  {
    /* class Bool */ $bRes = true;
    //...
    return $bRes;
  }
  
  private function /* class String */ GetMenuBlock(/* class Permission */ $pPerm)
  {
    global $mlHTML;
    /* class String */ $sRes = "";
    if($pPerm->AllowAction(_MENU_MSG_NAME, _READ_ACTION_NAME))
    {
      //Allow MENU
      /*
      $sRes = "
<br /><a href='?loguot=1'>Logout</a> | <a href='?show_partit=1'>Show partit</a> 
      ";
      */
      //посмотреть, как показывать разным пользователям разное меню. Для этого есть сообщение "Генераторы" и действие "Создавать/ Изменять"
      $sRes = $this->GetStdBlock("", "<a href='?loguot=1'>Logout</a> | <a href='?show_partit=1'>Show partit</a>");
    }
    return $sRes;
  }
  
  private function /* class String */ GetAuthBlock(/* class Permission */ &$pPerm)
  {
    global $mlHTML;
    /* class String */ $sRes = "";
    if($pPerm->AllowAction(_AUTH_MSG_NAME, _READ_ACTION_NAME) && ($pPerm->GetGrId()==4))
    {
      //Allow AUTH
      $sRes = "
<form method='post' action='index.php'>
Login <input type='text' id='auth_name' name='auth_name' size='32' value='' /><br />
Password <input type='password' id='auth_pass' name='auth_pass' size='32' value='' /><br />
<input type='submit' id='submit_button' name='submit_button' value='OK' />
</form>
      ";
      $sRes = $this->GetStdBlock("Welcome", $sRes);
    }
    return $sRes;
  }
  
  private function /* class String */ GetInfoBlock(/* class Permission */ &$pPerm)
  {
    global $mlHTML;
    /* class String */ $sRes = "";
    if($pPerm->AllowAction(_INFO_MSG_NAME, _READ_ACTION_NAME))
    {
      //Allow INFO
      //$sRes = $this->GetStdBlock("Info block", "Hello dude!");
      $sRes = $this->GetStdBlock("Info block", "Hello ".$pPerm->GetUserName()."!");
    }
    return $sRes;
  }
  
  private function /* class String */ GetPartitBlock(/* class Permission */ &$pPerm)
  {
    global $mlHTML;
    /* class String */ $sRes = "";
    if($pPerm->AllowAction(_PARTIT_MSG_NAME, _READ_ACTION_NAME))
    {
      //Allow Partit
      //$apPartit = array();
      //$apPartit = $this->GetPartit($this->aiRefreshTimes);
      $sRes = $this->GetPartit($this->aiRefreshTimes);
    }
    return $sRes;
  }
  
  private function /* class String */ GetNextRefreshTime(/* class Permission */ &$pPerm)
  {
    /* class String */ $sRes = "";
    if($pPerm->AllowAction(_NEXT_UPD_TIME_MSG_NAME, _READ_ACTION_NAME))
    {
      //Allow Next time
      
      //$sRes = "
      //120
      //";
      $sRes = $this->uUtility->MinInArray($this->aiRefreshTimes);
      if(!$sRes) $sRes = "60";
    }
    return $sRes;
  }  
  
  
  private function /* void */ GetFullPage(/* class Array */ &$aPagePart, /* String */ $sTitle)
  {
    global $mlHTML;
    $aPagePart[_BODY] = "";
    /* class FileAbstr */ $faInlineBlockTplFile = new FileAbstr(/* class String */ _INLINE_BLOCK_TPL_FILE);
    /* class Template */ $tInlineBlockTpl = new Template($mlHTML, $faInlineBlockTplFile);
    
    foreach($aPagePart as $k => $v)
    {
      if(isset($aPagePart[$k]) && !empty($aPagePart[$k]) && ($k != _NEXT_TIME_REFRESH) && ($k != _BODY) && ($k != _PARTIT_BLOCK_NAME))
      {
        $aInlineBlockContent = array("content" => $aPagePart[$k]);
        $aPagePart[_BODY] .= $tInlineBlockTpl->SerializeArray ($aInlineBlockContent);
        unset($aPagePart[$k]);
        unset($aInlineBlockContent);
      }
      if(isset($aPagePart[$k]) && !empty($aPagePart[$k]) && ($k == _PARTIT_BLOCK_NAME))
      {
        $aPagePart[_BODY] .= $aPagePart[$k];
      }
    }
    $aPagePart[_TITLE] = $sTitle;
    unset($tInlineBlockTpl);
    unset($faInlineBlockTplFile);
  }
  
  private function /* String */ GetStdBlock(/* String */ $sTitle, /* String */ $sContent)
  {
    global $mlHTML;
    /* class String */ $sRes = "";
    /* class FileAbstr */ $faStdContentBlockTplFile = new FileAbstr(/* class String */ _STD_CONTENT_BLOCK_TPL_FILE);
    /* class Template */ $tStdContentBlockTpl = new Template($mlHTML, $faStdContentBlockTplFile);
    $aStdContentBlockContent = array("title" => $sTitle, "content" => $sContent);
    $sRes = $tStdContentBlockTpl->SerializeArray ($aStdContentBlockContent);
    unset($tStdContentBlockTpl);
    unset($faStdContentBlockTplFile);
    return $sRes;
  }
  
  private function /* Массив [String] */ GetPartit(/* Массив [string] */ &$aiRefreshTimes)
  {
    global $mlXML, $mlVexflowNote, $mlSVG, $mlUnknown, $mlGen;
    global $mlHTML;
    /* array[Generator] */ $agGen = array();
    
    // Массив [String] $apPartit
    //$apPartit = array();
    $sPartitHtmlOne = "";
    $sPartitHtml = "";
    
    /* class FileAbstr */ $faInlineBlockTplFile = new FileAbstr(/* class String */ _INLINE_BLOCK_TPL_FILE);
    /* class Template */ $tInlineBlockTpl = new Template($mlHTML, $faInlineBlockTplFile);
      
    // _Смотрим есть ли генераторы, в которых он зареген, и есть ли какие-то ожидающие его сообщения от этих генераторов.
    // Массив [Генератор] мГен <= SELECT A.id_gen, B.* FROM generator2user AS A, generator AS B WHERE A.id_user=[$UID] AND A.id_gen=B.id
    $this->stStore->GetGenArray($this->shSession->GetUserId(), $agGen);
    
    if(count($agGen)>0)
    {
      //echo "<br />++++++++++++++++++++++++++++++++++<br />";
      //print_r($agGen);
      
      //echo "<br />++++++++++++++++++++++++++++++++++<br />";
      //var_dump($agGen);
      //echo "<br />++++++++++++++++++++++++++++++++++<br />";
      
      // Обходим массив (мГен AS $k => $v)
      foreach($agGen as $k => $v)
      {
        // $agGen[id]
        // $agGen[nm]
        // $agGen[id_algo]
        // $agGen[partition_cash]
        // $agGen[next_gen_time]
        //
        // if($v[gen_next_time]<=$CURR_TIME)
        //echo "\$v->GetGenNextTime() = ".$v->GetGenNextTime()."<br />";
        //echo "date('Y-m-d H:i:s') = ".date('Y-m-d H:i:s')."<br />";
        //$this->stStore->UpdGen($v->GetGenId(), "", 120);
        if(($iNextTime=$v->GetGenNextTime())<=date('Y-m-d H:i:s'))
        {
          //здесь надо поределить, если генератор $v->GetGenId() реализует метаалготритм, т.е. изменение групп.
          //будем считать, что у такого алгоритма обязательно определен параметр id_algo
          //и ни у каких других его нельзя определять
          $v->InsCalculatedData();
          
          /* Массив [GenParam] */ $agpGenParam = array();
          /* string */ $sPartit = "";
          
          // $agpGenParam <= SELECT * FROM param2generator WHERE id_generator=$v[id]
          $this->stStore->GetGenParamArray($v->GetGenId(), $agpGenParam);
          //print_r($agpGenParam);
          // $agpGenParam[key]
          // $agpGenParam[value]
          // $agpGenParam[type]
          // мГен[$k][gp] = $agpGenParam
          
          // echo "<br /><br />GenId = ".$v->GetGenId();
          // echo "<br /><br />";
          // print_r($agpGenParam);
          // echo "==============================================";
          
          $agGen[$k]->SetGenParam($agpGenParam);
          //echo "=================== test ".time()." ==============================<br />";
          
          /* Algo */ $alAlgo = new Algo($agGen[$k]);
          
          $sPartit = $alAlgo->GetPartit();
          //$apPartit.push($sPartit);
          //echo "<strong>NEW</strong><br />";
          
          //echo "<br /><strong>mlNewOutputFormat</strong><br />================================<br />";
          //var_dump($alAlgo->GetMarkupLang());
          //echo "<br />================================<br />";
          
          ///* Partit */ $pCurrPartit = new Partit (/* mlNewOutputFormat */ $alAlgo->GetMarkupLang(), /* sNewInputPartit */ $sPartit, /* mlNewInputFormat */ $mlXML, /* sNewName */ "");
          
          //echo "<br /><br />============= alAlgo->GetMarkupLang ======================<br />";
          //var_dump($alAlgo->GetMarkupLang());
          //echo "<br />============= / alAlgo->GetMarkupLang ======================<br /><br />";
          
          //echo "<br /><br />============= \$mlGen ======================<br />";
          //var_dump($mlGen);
          //echo "<br />============= / \$mlGen ======================<br /><br />";
          
          if($alAlgo->GetMarkupLang() == $mlVexflowNote)
          {
            $sDivUID = $this->uUtility->GetGUID();
            $aAddParam = array("div_outer" => "div_outer".$sDivUID, "div_inner" => "div_inner".$sDivUID);
            /* Partit */ $pCurrPartit = new VexFlowNote (/* mlNewOutputFormat */ $alAlgo->GetMarkupLang(), /* sNewInputPartit */ $sPartit, /* mlNewInputFormat */ $mlXML, /* sNewName */ "", $aAddParam);
            //echo "<br /><br />branch mlVexflowNote<br /><br />";
          }
          else if($alAlgo->GetMarkupLang() == $mlSVG)
          {
            /* Partit */ $pCurrPartit = new SVGPart (/* mlNewOutputFormat */ $alAlgo->GetMarkupLang(), /* sNewInputPartit */ $sPartit, /* mlNewInputFormat */ $mlXML, /* sNewName */ "");
            //echo "<br /><br />branch mlSVG<br /><br />";
          }
          else if($alAlgo->GetMarkupLang() == $mlGen)
          {
            /* Partit */ $pCurrPartit = new GroupChangePart (/* mlNewOutputFormat */ $alAlgo->GetMarkupLang(), /* sNewInputPartit */ $sPartit, /* mlNewInputFormat */ $mlGen, /* sNewName */ "");
            //echo "<br /><br />branch mlGen<br /><br />";
            $pCurrPartit->Save2DB();
          }
          else
          {
            /* Partit */ $pCurrPartit = new Partit (/* mlNewOutputFormat */ $mlUnknown, /* sNewInputPartit */ $sPartit, /* mlNewInputFormat */ $mlXML, /* sNewName */ "");
            //echo "<br /><br />branch DEFAULT<br /><br />";
          }
          $pCurrPartit->TranslatePartit();
          
          //$apPartit[] = $pCurrPartit;
          //echo "$sPartit<br /><br />";
          //$sPartitHtmlOne = $this->GetOnePartitHtml(/* Partit */ $pCurrPartit);
          $sPartitHtmlOne = $pCurrPartit->GetTranslatedPartit();
          $aInlineBlockContent = array("content" => $sPartitHtmlOne);
          //$sPartitHtml .= $sPartitHtmlOne;
          $sPartitHtml .= $tInlineBlockTpl->SerializeArray ($aInlineBlockContent);
          
          $iRefreshTime = $alAlgo->GenerateRefreshTime();
          
          unset($pCurrPartit);
          unset($alAlgo);
          // $aiRefreshTimes.push($iRefreshTime);
          $aiRefreshTimes[] = $iRefreshTime;
          
          //$this->stStore->UpdGen($v->GetGenId(), $sPartit, $iRefreshTime);
          $this->stStore->UpdGen($v->GetGenId(), $sPartitHtmlOne, $iRefreshTime);
          // {
          // make $sNextTime = $CURR_TIME + $iRefreshTime
          // UPDATE generator SET partition_cash = '$sPartit', next_gen_time = '$sNextTime' WHERE id = $v[id]
          // }
        }
        else
        {
          ///* Partit */ $pCurrPartit = new Partit (/* mlNewOutputFormat */ $mlVexflowNote, /* sNewInputPartit */ $v->GetPartitionCash(), /* mlNewInputFormat */ $mlXML, /* sNewName */ "");
          // $apPartit.push($v[partition_cash]);
          //$apPartit[] = $pCurrPartit;
          //unset($pCurrPartit);
          
          //$sPartitHtml .= $v->GetPartitionCash();
          
          $aInlineBlockContent = array("content" => $v->GetPartitionCash());
          $sPartitHtml .= $tInlineBlockTpl->SerializeArray ($aInlineBlockContent);
        }
      }
    }
    unset($tInlineBlockTpl);
    unset($faInlineBlockTplFile);
    
    //return $apPartit;
    return $sPartitHtml;
  }
  
  ///* String */ private function GetAllPartitHtml(/* array Partit */ $apPartit)
  //{
  //  /* String */ $sRes = "";
  //  global $mlVexflowNote;
  //  if(is_array($apPartit) && count($apPartit))
  //  {
  //    //print_r($apPartit);
  //    foreach($apPartit as $pPartit)
  //    {
  //      var_dump($pPartit->GetTranslatedPartit());
  //      //$sRes .= $pPartit->GetTranslatedPartit();
  //    }
  //  }
  //  else
  //  {
  //    $sRes .= $this->GetStdBlock("Partit block", "Play until have fun ))");
  //  }
  //  
  //  return $sRes;
  //}
  
  ///* String */ private function GetOnePartitHtml(/* Partit */ $pPartitOne)
  //{
  //  /* class String */ $sRes = "";
  //  $sRes = $pPartitOne->GetTranslatedPartit();
  //  return $sRes;
  //}
  
  private function /* String */ GetSpecBlock(/* String */ $sTplFileName, /* array */ &$aBlockContent)
  {
    global $mlHTML;
    /* class String */ $sRes = "";
    /* class FileAbstr */ $faStdContentBlockTplFile = new FileAbstr($sTplFileName);
    /* class Template */ $tStdContentBlockTpl = new Template($mlHTML, $faStdContentBlockTplFile);
    //$aStdContentBlockContent = array("title" => $sTitle, "content" => $sContent);
    $sRes = $tStdContentBlockTpl->SerializeArray ($aBlockContent);
    unset($tStdContentBlockTpl);
    unset($faStdContentBlockTplFile);
    return $sRes;
  }
}
?>