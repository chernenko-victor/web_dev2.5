<?php
//session.class.php

include_once("storage.class.php");
include_once("error.class.php");
include_once("message.class.php");
include_once("check_data.class.php");


class SessionHandle
{
  private /* int */ $iUid;
  private /* string */ $sName;
  private /* string */ $sIp;
  private /* Storage */ $stSessionStore;
  private /* ErrorHandle */ $eSessionErr;
  private /* CheckData */ $cdLoginStr;
  
  public function /* SessionHandle */ __construct()
  {
    $this->iUid = 0;
    $this->sIp = "";
    $this->sName = "";
    $this->stSessionStore = new Storage();
    $this->eSessionErr = new ErrorHandle();
    $this->cdLoginStr = new CheckData();
  }
  
  public function /* ~SessionHandle */ __destruct()
  {
    unset($this->cdLoginStr);
    unset($this->eSessionErr);
    unset($this->stSessionStore);
    unset($this->iUid);
    unset($this->sIp);
  }
  
  public function /* void */ Dispatch()
  {
    global $_mSessWrongUserAddr, $_mSessWrongDataInDB, $_mInsertUserSessionData, $_mNoUserInDB, $_mSessWrongUserData;
    //происходит чистка таблицы сессий в БД от устаревших данных
    $this->ClearSessionDB();
    
    //echo "session_name=".session_name();
    
    //echo "<br />var_dump(\$_REQUEST)";
    //var_dump($_REQUEST);
    
    //echo "<br />var_dump(\$_COOKIE)";
    //var_dump($_COOKIE);
    
    //Если (есть сессия для данного пользователя), то
    if(isset($_REQUEST[session_name()]) || isset($_COOKIE[session_name()]) || isset($_GET[session_name()]))
    { 
      if(isset($_POST['auth_name']) || isset($_POST['auth_pass']))
      {
        unset($_POST['auth_name']);
        unset($_POST['auth_pass']);
      }
      
      //echo "<br />EST SESSIYA<br />";
      //Стартует сессия
      session_start();
      
      //echo "<br />var_dump(\$_SESSION)";
      //var_dump($_SESSION);
      
      //Извлекается ИД пользователя по ИД сессии
      $sSid=session_id();
      if(!$this->cdLoginStr->CheckString($sSid))
      {
        //Создается сообщение об ошибке,
        $this->eSessionErr->AddMessage($_mSessWrongSessionData);
        //извлекаются разрешения для пользователя по умолчанию из БД,
        //На этом все закончилось.
        return;
      }
      //echo "<br />SESSID KORREKTEN<br />";
      if($this->GetUserDataBySidFromDB($sSid))
      {
        //echo "<br />EST DANNYE O SESSII V BD<br />";
        //Если (удаленный адрес пользователя не совпадает с сохраненным в БД)
        if(!$this->CheckUserAddr())
        {
            //echo "<br />udalennyj adres NE sovpadaet s sohranennym<br />";
        //  формируется сообщение об ошибке
            $this->eSessionErr->AddMessage($_mSessWrongUserAddr);
        //данные о его сессии из БД удаляются,
            $this->ClearUserSessionDataFromDB();
        //  удвляется сессия
            $this->SessDestr();
        //  На этом все закончилось.
            return;
        }
        //echo "<br />udalennyj adres sovpadaet s sohranennym<br />";
        //Если (есть входная переменная Разлогин), то
        if(isset($_GET['loguot']))
        {
          //echo "LOGOUT";
          //данные о его сессии из БД удаляются,
          $this->ClearUserSessionDataFromDB();
          //Удаляется сессия,
          $this->SessDestr();
          //извлекаются разрешения для пользователя по умолчанию из БД,
          //На этом все закончилось.
          return;
        }
        else
        //В противном случае [Если (НЕТ входная переменная Разлогин), то]
        {
          $sSid=session_id();
          if(!$this->cdLoginStr->CheckString($sSid))
          {
            //Создается сообщение об ошибке,
            $this->eSessionErr->AddMessage($_mSessWrongSessionData);
            //извлекаются разрешения для пользователя по умолчанию из БД,
            //На этом все закончилось.
            return;
          }
          //извлекаются разрешения для этого пользователя из БД, 
          //данные о его сессии в БД обновляются, 
          $this->UpdateUserSessionDataDB($sSid);
          //На этом все закончилось.
        }
      }
      else
      //Данных о сессии нет в БД
      {
        //  формируется сообщение об ошибке
            $this->eSessionErr->AddMessage($_mSessWrongDataInDB);
        //данные о его сессии из БД удаляются,
            $this->ClearUserSessionDataFromDB();
        //  удвляется сессия
            $this->SessDestr();
        //  На этом все закончилось.
            return;  
      }
    }
    else
    //В противном случае [Если (нет сессии для данного пользователя), то]
    {
      //echo "<br />NET SESSIYA<br />";
      //Если (есть входные переменные Логин и Пароль) то,
      if(isset($_POST['auth_name']) && $_POST['auth_name'] && isset($_POST['auth_pass']) && $_POST['auth_pass'])
      {
        //echo "<br />var_dump(\$_POST)";
        //var_dump ($_POST);
        //echo "<br />";
        
        //Проверяются переменные Логин и Пароль,
        //Если (переменные Логин и Пароль в порядке)
        if($this->cdLoginStr->CheckString($_POST['auth_name']) && $this->cdLoginStr->CheckString($_POST['auth_pass']))
        {
          //echo "<br />PEREMENNYE PRAVILNYE<br />";
          //Проверяется есть ли пользователь с такими данными в БД
          //Если (есть такой пользователь)
          if($this->CheckUserInDB($_POST['auth_name'], $_POST['auth_pass']))
          {
            //echo "<br />EST POLZOVALEL<br />";
            //Стартует сессия
            session_start();
            //данные о его сессии заносятся в БД, если произойдет ошибка
            if(!$this->InsertUserSessionDataDB(session_id()))
            {
              //Создается сообщение об ошибке,
              $this->eSessionErr->AddMessage($_mInsertUserSessionData);
              //На этом все закончилось.
              return;
            }
            //извлекаются разрешения для этого пользователя из БД, 
            //На этом все закончилось.
          }
          else
          //В противном случае
          {
            //echo "<br />NET POLZOVALELYA<br />";
            //Создается сообщение об ошибке,
            $this->eSessionErr->AddMessage($_mNoUserInDB);
            //извлекаются разрешения для пользователя по умолчанию из БД,
            //На этом все закончилось.
            return;
          }
        }
        else
        //В противном случае [переменные Логин и Пароль НЕПРАВИЛЬНЫЕ]
        {
          //Создается сообщение об ошибке,
          $this->eSessionErr->AddMessage($_mSessWrongUserData);
          //извлекаются разрешения для пользователя по умолчанию из БД,
          //На этом все закончилось.
          return;
        }
      }
      //else
      //В противном случае [Если (НЕТ входные переменные Логин и Пароль) то,]
      //{
        //извлекаются разрешения для пользователя по умолчанию из БД,
        //(Пользователю показывают форму Авторизации – это будет сделано позднее при формировании страницы)
        //На этом все закончилось.
      //}
    }
  }
  
  private function SessDestr()
  {
    if (ini_get('session.use_cookies'))
    {
      //setcookie(session_name(), session_id(), NULL, '/');
      setcookie(session_name(), session_id(), time() - 3600, '/');
      unset($_COOKIE[session_name()]);
    }
    
    session_destroy();
    //echo "session destroy<br />";
    //echo "session_id=".session_id()."<br />";
    /* Create a new session, deleting the previous session data. */
    session_regenerate_id(TRUE);
    /* erase data carried over from previous session */
    $_SESSION=array();
    
    //echo "<br />var_dump(\$_COOKIE)";
    //var_dump($_COOKIE);
    
    $this->iUid = 0;
    $this->sIp = "";
    $this->sName = "";
  }
  
  private function /* void */ ClearSessionDB(/* void */)
  {
    $this->stSessionStore->ClearSessionDB();
  }
  
  private function /* bool */ ClearUserSessionDataFromDB(/* void */)
  {
    return $this->stSessionStore->ClearUserSessionDataFromDB($this->iUid);
  }
  
  private function /* bool */ UpdateUserSessionDataDB(/* string */ $sSid)
  {
    return $this->stSessionStore->UpdateUserSessionData($sSid, $this->iUid, $this->sIp);
  }
  
  private function /* bool */ GetUserDataBySidFromDB(/* string */ $sSid)
  {
    /* bool */ $bRes = true;
    if(!isset($sSid) || !$sSid)
    {
      $bRes = false;
    }
    else
    {
      if(!$this->stSessionStore->GetUserDataBySid($sSid, $this->iUid, $this->sIp, $this->sName))
      {
        $bRes = false;
      }
      if(!isset($this->iUid) || !$this->iUid || !isset($this->sIp) || !strlen($this->sIp))
      {
        $bRes = false;
      }
    }
    return $bRes;
  }
  
  private function /* bool */ CheckUserAddr()
  {
    /* bool */ $bRes = true;   
    if($this->sIp != $_SERVER['REMOTE_ADDR'])
    {
      $bRes = false;
    }
    return $bRes;
  }
  
  public function /* int */ GetError(&$eSessionError)
  {
    $eSessionError = $this->eSessionErr;    
    return $this->eSessionErr->GetErrorCount();
  }
  
  private function /* bool */ CheckUserInDB(/* string */ $sLogin, /* string */ $sPwd)
  {
    /* bool */ $bRes = true;   
    if(!$this->stSessionStore->CheckUser($sLogin, $sPwd))
    {
      $bRes = false;
    }
    else
    {
      if(!$this->stSessionStore->GetUserDataByPwd($sLogin, $sPwd, $this->iUid, $this->sIp, $this->sName))
      {
        $bRes = false;
      }
      if(!isset($this->iUid) || !$this->iUid || !isset($this->sIp) || !strlen($this->sIp))
      {
        $bRes = false;
      }
    }
    return $bRes;
  }
  
  private function /* bool */ InsertUserSessionDataDB(/* string */ $sSid)
  {
    return $this->stSessionStore->InsertUserSessionData($sSid, $this->iUid, $this->sIp);
  }
  
  public function /* int */ GetUserGrIdFromDb()
  {
    /* int */ $iRes = 4;
    if($this->iUid)
    {
      $iRes = $this->stSessionStore->GetUserGrId($this->iUid);
    }
    return $iRes;
  }
  
  public function /* string */ GetUserName()
  {
    return $this->sName;
  }
  
  public function /* int */ GetUserId()
  {
    return $this->iUid;
  }
}
?>