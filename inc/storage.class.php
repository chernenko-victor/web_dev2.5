<?php
//Класс Хранилище – высокоуровневая обертка для БД
include_once("db.class.php");
include_once("generator.class.php");
include_once("util.class.php");

class Storage
{
  private $dbDatabase;
  private /* int */ $iSessionTime;
  private $uUtil;
  private $sTblPref;

  public function /* Storage */ __construct()
  {
    global $MYSQL;
    $this->dbDatabase = new DBA();
    $this->dbDatabase->Connect();
    $this->iSessionTime = 7 * 24 * 60 * 60;
    $this->uUtil = new Util();
	$this->sTblPref = $MYSQL["table_prefix"];
  }

  public function /* ~Storage */ __destruct()
  {
    unset($this->uUtil);
    unset($this->iSessionTime);
    $this->dbDatabase->Disconnect();
    unset($this->dbDatabase);
  }

  public function /* void */ ClearSessionDB(/* void */)
  {
    $this->dbDatabase->Query("DELETE FROM ".$this->sTblPref."session WHERE last_activ_time>FROM_UNIXTIME(".(time() + $this->iSessionTime).")");
    $this->dbDatabase->Decount();
  }
  
  public function /* bool */ ClearUserSessionDataFromDB(/* int */ $iUid)
  {
    /* bool */ $bRes = true;
    $bRes = $this->dbDatabase->Query("DELETE FROM ".$this->sTblPref."session WHERE id_user=$iUid");
    $this->dbDatabase->Decount();
    return $bRes;
  }  
  
  public function /* bool */ GetUserDataBySid(/* string */ $sSid, /* int */ &$iUid, /* string */ &$sIp, /* string */ &$sName)
  {
    /* bool */ $bRes = true;
    //$iUid = 1;
    //$sIp = "127.0.0.1";
    $iUid = 0;
    $sIp = "";
    
    $sSid = $this->uUtil->EscapeStrDb($sSid);
    
    $bRes = $this->dbDatabase->Query("SELECT A.id, A.nm, B.user_ip FROM ".$this->sTblPref."user AS A, ".$this->sTblPref."session AS B WHERE B.id_user=A.id AND B.id_session='$sSid' AND B.last_activ_time<=FROM_UNIXTIME(".(time() + $this->iSessionTime).") LIMIT 0, 1");
    if($bRes)
    {
      $aRow = array();
      if ($this->dbDatabase->Num_rows())
      {
          $aRow = $this->dbDatabase->Get_row_one();
          $iUid = $aRow["id"];
          $sIp = $aRow["user_ip"];
          $sName = $aRow["nm"];
          if(!isset($iUid) || !$iUid || !isset($sIp) || $sIp==="")
          {
            $bRes = false;
          }
      }
      else
      {
        $bRes = false;
      }
    }
    return $bRes;
  }
  
  public function /* bool */ UpdateUserSessionData(/* string */ $sSid, /* int */ &$iUid, /* string */ &$sIp)
  {
    /* bool */ $bRes = true;
    
    $sSid = $this->uUtil->EscapeStrDb($sSid);
    $iUid = $this->uUtil->EscapeStrDb($iUid);
    
    $bRes = $this->dbDatabase->Query("UPDATE ".$this->sTblPref."session SET last_activ_time=FROM_UNIXTIME(".time().") WHERE id_session='$sSid' AND id_user=$iUid");
    $this->dbDatabase->Decount();
    return $bRes;
  }
  
  public function /* bool */ CheckUser(/* string */ $sLogin, /* string */ $sPwd)
  {
    /* bool */ $bRes = true;
    
    $sLogin = $this->uUtil->EscapeStrDb($sLogin);
    $sPwd = $this->uUtil->EscapeStrDb($sPwd);
    
    $bRes = $this->dbDatabase->Query("SELECT A.id FROM ".$this->sTblPref."user AS A WHERE A.login='".$sLogin."' AND A.pwd='".md5($sPwd)."'");
    if($bRes)
    {
      $aRow = array();
      if ($this->dbDatabase->Num_rows())
      {
          $aRow = $this->dbDatabase->Get_row_one();
          $iUid = $aRow["id"];
          if(!isset($iUid) || !$iUid)
          {
            $bRes = false;
          }
      }
      else
      {
        $bRes = false;
      }
    }
    return $bRes;
  }
  
  public function /* bool */ GetUserDataByPwd(/* string */ $sLogin, /* string */ $sPwd, /* int */ &$iUid, /* string */ &$sIp, /* string */ &$sName)
  {
    /* bool */ $bRes = true;
    $iUid = 0;
    $sIp = "";
    
    $sLogin = $this->uUtil->EscapeStrDb($sLogin);
    $sPwd = $this->uUtil->EscapeStrDb($sPwd);
    
    $bRes = $this->dbDatabase->Query("SELECT A.id, A.nm FROM ".$this->sTblPref."user AS A WHERE A.login='".$sLogin."' AND A.pwd='".md5($sPwd)."'");
    if($bRes)
    {
      $aRow = array();
      if ($this->dbDatabase->Num_rows())
      {
          $aRow = $this->dbDatabase->Get_row_one();
          $iUid = $aRow["id"];
          $sName = $aRow["nm"];
          $sIp = $_SERVER['REMOTE_ADDR'];
          if(!isset($iUid) || !$iUid || !isset($sIp) || $sIp==="")
          {
            $bRes = false;
          }
      }
      else
      {
        $bRes = false;
      }
    }
    return $bRes;
  }
  
  public function /* bool */ InsertUserSessionData(/* string */ $sSid, /* int */ $iUid, /* string */ $sIp)
  {
    /* bool */ $bRes = true;
    
    $sSid = $this->uUtil->EscapeStrDb($sSid);
    $sIp = $this->uUtil->EscapeStrDb($sIp);
    
    $query="INSERT ".$this->sTblPref."session (id_session, id_user, last_activ_time, user_ip) VALUES ('$sSid', $iUid, FROM_UNIXTIME(".time()."), '$sIp')";
    $bRes = $this->dbDatabase->Query($query);
    $this->dbDatabase->Decount();
    return $bRes;
  }
  
  public function /* int */ GetActionAllow(/* int */ $iActionId, /* int */ $iMsgId, /* int */ $iGrId)
  {
    /* int */ $iRes = 0;
    /* string */ /* echo */ $sQuery = 
    "
    SELECT allow 
    FROM ".$this->sTblPref."permission
    WHERE id_group = $iGrId
    AND id_msg = $iMsgId
    AND id_action = $iActionId
    ";
    
    /* bool */$bQueryRes = $this->dbDatabase->Query($sQuery);
    if($bQueryRes)
    {
      $aRow = array();
      if ($this->dbDatabase->Num_rows())
      {
          $aRow = $this->dbDatabase->Get_row_one();
          $iRes = $aRow["allow"];
      }
    }
    return $iRes;
  }
  
  public function /* int */ GetUserGrId($iUid)
  {
    /* int */ $iRes = 0;
    /* string */ $sQuery = 
    "
    SELECT A.id_group, B.nm AS group_name 
    FROM ".$this->sTblPref."user AS A, ".$this->sTblPref."group AS B
    WHERE A.id = $iUid
    AND A.id_group = B.id
    ";
    
    /* bool */$bQueryRes = $this->dbDatabase->Query($sQuery);
    if($bQueryRes)
    {
      $aRow = array();
      if ($this->dbDatabase->Num_rows())
      {
          $aRow = $this->dbDatabase->Get_row_one();
          $iRes = $aRow["id_group"];
      }
    }
    return $iRes;
  }
  
  public function /* void */ GetGenArray(/* int */ $iUid, /* array[Generator] */ &$agGen)
  {
    //
    /* string */ $sQuery = 
    "
    SELECT A.id_gen, B.* 
    FROM ".$this->sTblPref."generator2user AS A, ".$this->sTblPref."generator AS B WHERE A.id_user=$iUid AND A.id_gen=B.id
    ";
    
    $aRow = $this->dbDatabase->getDataArray($sQuery);
    if(count($aRow))
    {
      foreach($aRow as $k => $v)
      {
        $v["partition_cash"] = $this->uUtil->UnEscapeStrDb($v["partition_cash"]);
        if($v["nm"] && strlen($v["nm"]) && $v["next_gen_time"]) $agGen[]= new Generator($v["nm"], $v["next_gen_time"], $v["partition_cash"], $v["id"]);
      }
    }
  }
  
  
  public function /* void */ GetGenParamArray(/* int */ $iGenId, /* array[GenParam] */ &$agpGenParam)
  {
    //
    /* string */ $sQuery = 
    "
    SELECT A.*, B.nm AS nm_type 
    FROM ".$this->sTblPref."param2generator A, ".$this->sTblPref."data_type B 
    WHERE A.id_generator=$iGenId 
    AND A.id_type = B.id
    ";
    
    $aRow = $this->dbDatabase->getDataArray($sQuery);
    if(count($aRow))
    {
      foreach($aRow as $k => $v)
      {
        $agpGenParam[] = array (
            "key" => $v["key"]
          , "value" => $v["value"]
          , "id_type" => $v["id_type"]
          , "nm_type" => $v["nm_type"]
          , "id_parent" => $v["id_parent"] //test param
          , "id" => $v["id"] //test param
        );
      }
    }
  }
  
  public function /* void */ UpdGen(/* int */ $iGid, /* string */ $sPartit, /* int */ $iRefreshTime)
  {
    $sPartit = $this->uUtil->EscapeStrDb($sPartit);
    
    //Unix Timestamp = SECONDS_SINCE_THE_UNIX_EPOCH
    // $iCURR_UNIX_TIMESTAMP = time();
    // $iNEXT_UNIX_TIMESTAMP = $iCURR_UNIX_TIMESTAMP + $iRefreshTime
    $iNextUnixTimestamp = time() + $iRefreshTime;

    //echo "time() = ".time()." || ".date ( 'Y-m-d H:i:s', time() )."<br />";
    //echo "\$iRefreshTime = $iRefreshTime || ".date ( 'Y-m-d H:i:s', $iRefreshTime )."<br />";
    //echo "\$iNextUnixTimestamp = $iNextUnixTimestamp || ".date ( 'Y-m-d H:i:s', $iNextUnixTimestamp )."<br />";
    
    $iNextTime = date ( 'Y-m-d H:i:s', $iNextUnixTimestamp );
    
    /* string */ /* echo */ $sQuery = 
    "
    UPDATE ".$this->sTblPref."generator SET partition_cash = '$sPartit', next_gen_time = '$iNextTime' WHERE id = $iGid
    ";
    //echo "<br />";
    $bRes = $this->dbDatabase->Query($sQuery);
    $this->dbDatabase->Decount();
  }
  
  public function /* int */ GetAlgoUrlByGenId($iGid)
  {
    /* int */ $sRes = "";
    /* string */ $sQuery = 
    "
    SELECT A.url, B.id_algo
    FROM ".$this->sTblPref."algo AS A, ".$this->sTblPref."generator AS B
    WHERE B.id = $iGid
    AND A.id = B.id_algo
    ";
    
    /* bool */$bQueryRes = $this->dbDatabase->Query($sQuery);
    if($bQueryRes)
    {
      $aRow = array();
      if ($this->dbDatabase->Num_rows())
      {
          $aRow = $this->dbDatabase->Get_row_one();
          $sRes = $aRow["url"];
      }
    }
    return $sRes;
  }
  
  public function /* int */ GetMarkupLangNameByGenId($iGid)
  {
    /* int */ $iRes = "";
    /* string */ $sQuery = 
    "
    SELECT A.nm
    FROM ".$this->sTblPref."markup_lang AS A, ".$this->sTblPref."markup_lang2algo AS B, ".$this->sTblPref."generator AS C 
    WHERE A.id = B.id_ml AND B.id_algo = C.id_algo AND C.id = $iGid
    ";
    
    /* bool */$bQueryRes = $this->dbDatabase->Query($sQuery);
    if($bQueryRes)
    {
      $aRow = array();
      if ($this->dbDatabase->Num_rows())
      {
          $aRow = $this->dbDatabase->Get_row_one();
          $sRes = $aRow["nm"];
      }
    }
    return $sRes;
  }
  
  public function /* void */ DelGroupChangeParam(/* int */ $iIdGen)
  {
    $this->dbDatabase->Query("DELETE FROM ".$this->sTblPref."param2generator
    WHERE id_generator = $iIdGen
    AND `key`<>'id_algo'");
    $this->dbDatabase->Decount();
  }
  
  public function /* int */ InsertGroupChangeParam(/* int */ $iIdGen, /* string */ $sType, /* int */ $iLastIdPrev = 0, /* int */ $iCurrIdPrev = 0)
  {
    /*
    $sType = 
    {
        "user_cnt" 
      | "user_list"
      | "gen_list"
      | "gen_param"
    }
    */
    
    $aGenParam = array ();
    $iLastId = 0;
    
    //echo "<br /><br />\$sType = $sType";
    
    switch($sType)
    {
      case "user_list":
        $aGenParam = array (
            "id_generator" => $iIdGen
          , "key" => "user_list"
          , "value" => 0
          , "id_type" => 1
          , "id_parent" => 0
        );
      break;
      case "gen_list":
        $aGenParam = array (
            "id_generator" => $iIdGen
          , "key" => "gen_list"
          , "value" => 0
          , "id_type" => 1
          , "id_parent" => 0
        );
      break;
    }
    
    if(count($aGenParam))
    {
      $sQueryIns1="INSERT INTO ".$this->sTblPref."param2generator 
      (
        `id_generator`
      , `key`
      , `value`
      , `id_type`
      , `id_parent`
      ) 
      VALUES 
      (
          ".$aGenParam["id_generator"]."
        , '".$aGenParam["key"]."'
        , '".$aGenParam["value"]."'
        , ".$aGenParam["id_type"]."
        , ".$aGenParam["id_parent"]."
      )
      ";
      $this->dbDatabase->Query($sQueryIns1);
      $iLastId = $this->dbDatabase->getId();
      $this->dbDatabase->Decount();
    }
    
    switch($sType)
    {
      case "gen_param":
        $sQuery = 
        "
        SELECT D.*
        FROM ".$this->sTblPref."param2generator AS A, ".$this->sTblPref."generator AS B, ".$this->sTblPref."param2generator AS D
        WHERE A.key = 'id_algo'
          AND A.id_generator = $iIdGen
          AND A.value = B.id_algo
          AND B.id = D.id_generator
          AND B.id = $iCurrIdPrev
        ";
      break;
      
      case "gen_list":
        $sQuery = 
        "
        SELECT B.*
        FROM ".$this->sTblPref."param2generator AS A, ".$this->sTblPref."generator AS B
        WHERE A.key = 'id_algo'
          AND A.id_generator = $iIdGen
          AND A.value = B.id_algo
        ";
      break;
      
      case "user_list":
        $sQuery = 
        "
        SELECT C.id_user
        FROM ".$this->sTblPref."param2generator AS A, ".$this->sTblPref."generator AS B, ".$this->sTblPref."generator2user AS C
        WHERE A.key = 'id_algo'
          AND A.id_generator = $iIdGen
          AND A.value = B.id_algo
          AND B.id = C.id_gen
        ";
      break;
      
      case "user_cnt":
      default:
        $sQuery = 
        "
        SELECT COUNT(C.id_user) AS user_cnt 
        FROM ".$this->sTblPref."param2generator AS A, ".$this->sTblPref."generator AS B, ".$this->sTblPref."generator2user AS C
        WHERE A.key = 'id_algo'
          AND A.id_generator = $iIdGen
          AND A.value = B.id_algo
          AND B.id = C.id_gen
        LIMIT 0, 1
        ";
      break;
    }
    
    $aRow = $this->dbDatabase->getDataArray($sQuery);
    if(count($aRow))
    {
      $iLastId2 = 0;
      $iCurrId = 0;
      foreach($aRow as $k => $v)
      {
        $aGenParam = array ();
        switch($sType)
        {
          case "gen_param":
            $aGenParam = array (
                "id_generator" => $iIdGen
              , "key" => $v["key"]
              , "value" => $v["value"]
              , "id_type" => $v["id_type"]
              , "id_parent" => $iLastIdPrev
            );
          break;
          
          case "gen_list":
            $aGenParam = array (
                "id_generator" => $iIdGen
              , "key" => $v["id"]
              , "value" => $v["next_gen_time"]
              , "id_type" => 1
              , "id_parent" => $iLastId
            );
            $iCurrId = $v["id"];
          break;
          
          case "user_list":
            $aGenParam = array (
                "id_generator" => $iIdGen
              , "key" => "user"
              , "value" => $v["id_user"]
              , "id_type" => 1
              , "id_parent" => $iLastId
            );
          break;
          
          case "user_cnt":
          default:
            $aGenParam = array (
                "id_generator" => $iIdGen
              , "key" => "user_cnt"
              , "value" => $v["user_cnt"]
              , "id_type" => 1
              , "id_parent" => 0
            );
          break;
        }
        
        if(count($aGenParam))
        { 
          $sQueryIns2="INSERT INTO ".$this->sTblPref."param2generator
          (
            `id_generator`
          , `key`
          , `value`
          , `id_type`
          , `id_parent`
          ) 
          VALUES 
          (
              ".$aGenParam["id_generator"]."
            , '".$aGenParam["key"]."'
            , '".$aGenParam["value"]."'
            , ".$aGenParam["id_type"]."
            , ".$aGenParam["id_parent"]."
          )
          ";
          /* $bResInd2 = */ $this->dbDatabase->Query($sQueryIns2);
          $iLastId2 = $this->dbDatabase->getId();
          $this->dbDatabase->Decount();
        }

        if(($iLastId2)&&("gen_list"==$sType))
        {
          $this->InsertGroupChangeParam($iIdGen, "gen_param", $iLastId2, $iCurrId);
        }
      }
    }
    return $iLastId;
  }
  
  /* int */ public function /* bool */ GetAlgoParamId(/* int */ $iIdGen)
  {
    /* int */ $iRes = 0;
    /* bool */ $bRes = 0;
    
    $sQuery = "SELECT id FROM ".$this->sTblPref."param2generator
    WHERE id_generator = $iIdGen
    AND `key`='id_algo'";
    $bRes = $this->dbDatabase->Query($sQuery);
    if($bRes)
    {
      $aRow = array();
      if ($this->dbDatabase->Num_rows())
      {
          $aRow = $this->dbDatabase->Get_row_one();
          $iRes = $aRow["id"];
        
      }
    }
    return $iRes;
  }
  
  public function /* array */ GetOldGenArray(/* int */ $iIdAlgo)
  {
    /* array */ $aRes = array();
    
    $query = "SELECT id FROM ".$this->sTblPref."generator WHERE id_algo = ".$iIdAlgo;
    //echo "<br /><br />$query<br /><br />";
    $aRow = array();
    $aRow = $this->dbDatabase->getDataArray($query);
    //print_r($aRow);
    if(count($aRow))
    {
      foreach($aRow as $k => $v)
      {
        $aRes[] = $v["id"];
      }
    }
    return $aRes;
  }
  
  public function /* void */ ExecuteQuery(/* string */ $sQuery)
  {
    $this->dbDatabase->Query($sQuery);
  }
}
?>