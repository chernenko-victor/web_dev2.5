<?php
//group_change.class.php

include_once("C:/Program Files/wamp/www/dev2.5/inc/markup_language.class.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/XML2Assoc.class.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/template.class.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/file.class.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/config.inc.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/util.class.php");
include_once("C:/Program Files/wamp/www/dev2.5/inc/storage.class.php");

//include_once("C:/Program Files/wamp/www/dev2.5/inc/storage.class.php");

class GroupChangePart extends Partit
{
  private /* Template */ $tTpl;
  private /* Storage */ $stStore;
  private /* string */ $sQueryTransaction;
  private /* array */ $asQTransact;
  
  //public function /* GroupChangePart */ __construct(/* MarkupLanguage */ $mlNewOutputFormat, /* String */ $sNewInputPartit, /* MarkupLanguage */ $mlNewInputFormat, /* String */ $sNewName = "", $aAddParamNew = false)
  //{
  //  parent::__construct(/* MarkupLanguage */ $mlNewOutputFormat, /* String */ $sNewInputPartit, /* MarkupLanguage */ $mlNewInputFormat, /* String */ $sNewName, $aAddParamNew);
  //  $this->stStore = new Storage();
  //}
  
  //public function /*~GroupChangePart */ __destruct()
  //{
  //  parent::__destruct();
  //  unset($this->stStore);
  //}
  
  public function /* void */ TranslatePartit()
  {
    //global $mlSVG, $mlHTML;
    global $mlGen, $mlHTML;
    
    $uUtil = new Util();
    /* array */ $aParamArr = $this->xaParser->parseString($this->sInputPartit, false);
    
    if($mlGen == $this->mlOutputFormat)
    {
      $data = array();
      //$data = $this->GetRndMap(0, 40, 8, 24);
      $data = $this->GetNewGroupInfo($aParamArr);
      //var_dump($data);
      $values = array();
      $coll = array();
      $count = 0;
      $aIdAlgo = $uUtil->GetItemByFieldNm($aParamArr, "id_algo");
      if(is_array($aIdAlgo) && count($aIdAlgo))
      {
        $values["id_algo"] = $aIdAlgo[0][0];
      }
      
      /* class FileAbstr */ $faGrChangeTplFile = new FileAbstr(_GROUP_CHANGE_TPL_NAME);
      $tTpl = new Template($mlHTML, /* FileAbstr */ $faGrChangeTplFile);
      $this->sOutputPartit = $tTpl->SerializeMap($data, $coll, $values, $count);
      
      //$this->sOutputPartit = "___GroupChangePart late______";
      $this->sOutputPartit = $this->GetSpecBlock(/* $aBlockContent */ array("title" => $mlGen->GetMLString(), "content" => $this->sOutputPartit), /* $sTplFileName */ _PART_ONE_TPL_NAME);
      //$this->sOutputPartit .= $uUtil->DecodeEntit($this->sInputPartit);
      //var_dump($aParamArr);
      //echo "<br />====================================";
      //print_r($aParamArr);
      //echo "<br />====================================";
    }
    else
    {
      $this->sOutputPartit = "((((((((((((((((((( OUCH! UNKNOWN FORMAT ___GroupChangePart late______".$uUtil->DecodeEntit($this->sInputPartit);
      //unset($alGrChange);
    }
    return;
  }
  
  private function GetRndMap($iMin, $iMax, $iCntX, $iCntY)
  {
    for($i = 0; $i<$iCntX; $i++)
    {
      for($j = 0; $j<$iCntY; $j++)
      {
        $data[$i]["num".$j] = $this->GetRndInt($iMin, $iMax);
      }
    }
    return $data;  
  }
  
  private function GetNewGroupInfo(/* array */ &$aParamArr)
  {
    $data = array();
    $uUtil = new Util();
    /* array*/ $aGenNew = $uUtil->GetItemByFieldNm($aParamArr, "gen");
    /* array*/ $aUser2gen = $uUtil->GetItemByFieldNm($aParamArr, "user2gen");
    /* array*/ $aGen_param = $uUtil->GetItemByFieldNm($aParamArr, "gen_param");
    //for($i = 0; $i<$iCntX; $i++)
    /* int */ $i = 0;
    foreach($aGenNew as $k => $v)
    {
        $data[$i]["id_gen"] = $v["id"];
        $data[$i]["nm_gen"] = $v["nm"];
        //$data[$i]["param2generator"] = $this->Param2Generator($aGen_param, $v["id"]);
        $i++;
    }
    return $data;  
  }
  
  /* string */ private function Param2Generator(/* array */ &$aGen_param, /* id */ $iIdGen)
  {
    global $mlHTML;
    $sRes = "";
    $data = array();
    $values = array();
    $values["nm_subtitle"] = "param2generator";
    $coll = array();
    $count = 0;
    $i = 0;    
    
    foreach($aGen_param as $k3 => $v3)
    {
      if($v3["id_generator"]==$iIdGen)
      {
        $data[$i]["key"] = $v3["key"];
        $data[$i]["val"] = $v3["value"];
        //$id_type = $v3["id_type"];
        //$id_parent = $v3["id_parent"];
        $i++;
      }
    }
    
    /* class FileAbstr */ $faGrChangeTplFile = new FileAbstr(_KEY_VAL_TPL_NAME);
    $tTpl = new Template($mlHTML, /* FileAbstr */ $faGrChangeTplFile);
    $sRes = $tTpl->SerializeMap($data, $coll, $values, $count);
    
    return $sRes; 
  }
  
  private function GetRndInt($iMin, $iMax)
  {
    return rand($iMin, $iMax);
  }
  
  public function /* bool */ Save2DB()
  {
    /* bool */ $bRes = false;
    global $mlGen;
    // надо обязательно задать уровень изолированности, чтобы изменения были видны непосредственно внутри транзакции
    $this->sQueryTransaction = "";
    $this->asQTransact = array();
    
    $this->asQTransact[] = "SET TRANSACTION ISOLATION LEVEL REPEATABLE READ; ";
    //$this->sQueryTransaction .= "SET TRANSACTION ISOLATION LEVEL REPEATABLE READ; ";
    $this->sQueryTransaction .= "START TRANSACTION; ";
    $this->asQTransact[] = "START TRANSACTION; ";
    
    /* array */ $aParamArr = $this->xaParser->parseString($this->sInputPartit, false);
    
    if($mlGen == $this->mlOutputFormat)
    {
      $uUtil = new Util();
      //. Вынимаем id_algo
      $aIdAlgo = $uUtil->GetItemByFieldNm($aParamArr, "id_algo");
      if(is_array($aIdAlgo) && count($aIdAlgo))
      {
        $iIdAlgo = $aIdAlgo[0][0];
      }
      else 
      {
        return $bRes;
      }
      $this->stStore = new Storage();
      $this->DelOldData($iIdAlgo, $aParamArr); //. Удаление старых данных
      $this->InsData($iIdAlgo, $aParamArr); // . Вставка новых данных
      $this->sQueryTransaction .= "COMMIT;";
      $this->asQTransact[] = "COMMIT;";
      //execute $this->sQueryTransaction
      //$this->stStore->ExecuteQuery($this->sQueryTransaction);
      foreach($this->asQTransact as $kQT => $vQT)
      {
        $this->stStore->ExecuteQuery($vQT);
        echo "<br />STRING = $vQT<br />";
      }
      unset($this->stStore);
      //echo $this->sQueryTransaction;
      $bRes = true;
    }
    return $bRes;
  }
  
  private function /* void */ DelOldData(/* int */ $iIdAlgo, /* array */ $aParamArr) //. Удаление старых данных
  {
    // По id_algo составим список СГст

    // SELECT id FROM generator WHERE generator.id_algo = id_algo
    /* array */ $aGen = $this->stStore->GetOldGenArray($iIdAlgo);

    if(count($aGen))
    {
      echo "<br />========================= OLD GEN ARRAY ===================================<br />";
      print_r($aGen);
      echo "<br />============================================================<br /><br />";
      
      // Удалим старые
      // DELETE FROM generator WHERE generator.id_algo = id_algo
      $this->sQueryTransaction .= "DELETE FROM r5u7_generator WHERE id_algo = $iIdAlgo; ";
      $this->asQTransact[] = "DELETE FROM r5u7_generator WHERE id_algo = $iIdAlgo; ";
    
      foreach($aGen as $k => $v)
      {
        // очистим generator2user по СГст
        $this->sQueryTransaction .= "DELETE FROM r5u7_generator2user WHERE id_gen = $v; ";
        $this->asQTransact[] = "DELETE FROM r5u7_generator2user WHERE id_gen = $v; ";
      }
    
      foreach($aGen as $k => $v)
      {
        // очистим param2generator по СГст
        $this->sQueryTransaction .= "DELETE FROM r5u7_param2generator WHERE id_generator = $v; ";
        $this->asQTransact[] = "DELETE FROM r5u7_param2generator WHERE id_generator = $v; ";
      }
    }
    return;
  }
  
  private function /* void */ InsData(/* int */ $iIdAlgo, /* array */ $aParamArr) // . Вставка новых данных
  {
    $uUtil = new Util();
    /* array */ $aParamArr = $this->xaParser->parseString($this->sInputPartit, false);
    //echo "<br />================ \$aParamArr ====================";
    //print_r($aParamArr);
    //echo "<br />====================================";
    
    /* array*/ $aGenNew = $uUtil->GetItemByFieldNm($aParamArr, "gen");
    //echo "<br />================ \$aGenNew ====================";
    //print_r($aGenNew);
    //echo "<br />====================================";
    
    /* array*/ $aUser2gen = $uUtil->GetItemByFieldNm($aParamArr, "user2gen");
    /* array*/ $aGen_param = $uUtil->GetItemByFieldNm($aParamArr, "gen_param");
    
    // Пока (массив gen непустой)
    /*
    Array 
( 
[0] => Array ( [0] => gen_algo1_1 [id] => 1 [id_algo] => 1 [nm] => gen_algo1_1 ) 
[1] => Array ( [0] => gen_algo1_2 [id] => 2 [id_algo] => 1 [nm] => gen_algo1_2 ) 
[2] => Array ( [0] => gen_algo1_3 [id] => 3 [id_algo] => 1 [nm] => gen_algo1_3 ) 
) 
    */
    $iDelta = 10;
    $iNextUnixTimestamp = time() + $iDelta;
    $iNextTime = date ( 'Y-m-d H:i:s', $iNextUnixTimestamp );
    
    $this->sQueryTransaction .= "SET @lid = 0; ";
    $this->asQTransact[] = "SET @lid = 0; ";
    
    foreach($aGenNew as $k => $v)
    {
      //   Берем новый генератор;

      //   Вставляем в generator, берем новый id (в базе);
      
      $this->sQueryTransaction .= "
      INSERT INTO r5u7_generator
      (
          nm
        , id_algo
        , partition_cash
        , next_gen_time
      )
      VALUES
      (
          '$v[nm]'
        , $iIdAlgo
        , ''
        , '$iNextTime'
      ); ";
      $this->asQTransact[] = "
      INSERT INTO r5u7_generator
      (
          nm
        , id_algo
        , partition_cash
        , next_gen_time
      )
      VALUES
      (
          '$v[nm]'
        , $iIdAlgo
        , ''
        , '$iNextTime'
      ); ";
      
      $this->sQueryTransaction .= "SELECT LAST_INSERT_ID() INTO @lid; ";
      $this->asQTransact[] = "SELECT LAST_INSERT_ID() INTO @lid; ";
      //   Берем по старый id генератора (в массиве) из массива user2gen пользователей для этого генератора, вставляем в БД таблицу generator2user с id_gen=новый id генератора (в базе) и id_user из массива;
      
      
      foreach($aUser2gen as $k2 => $v2)
      {
        if($v2["id_gen"]==$v["id"])
        {
          $this->sQueryTransaction .= "
          INSERT INTO r5u7_generator2user
          (
              id_user
            , id_gen
          )
          VALUES
          (
              $v2[0]
            , @lid
          ); ";
          $this->asQTransact[] = "
          INSERT INTO r5u7_generator2user
          (
              id_user
            , id_gen
          )
          VALUES
          (
              $v2[0]
            , @lid
          ); ";
        }
      }


      //   Берем по старый id генератора (в массиве) из массива gen_param параметр для этого генератора, вставляем в БД таблицу param2generator с id_gen=новый id (в базе) и данные параметра из массива;
      foreach($aGen_param as $k3 => $v3)
      {
        if($v3["id_generator"]==$v["id"])
        {
          $key = $v3["key"];
          $value = $v3["value"];
          $id_type = $v3["id_type"];
          $id_parent = $v3["id_parent"];
          $this->sQueryTransaction .= "
          INSERT INTO r5u7_param2generator
          (
              id_generator
            , `key`
            , `value`
            , id_type
            , id_parent
            
          )
          VALUES
          (
              @lid
            , '$key'
            , '$value'
            , $id_type
            , $id_parent
          ); ";
          $this->asQTransact[] = "
          INSERT INTO r5u7_param2generator
          (
              id_generator
            , `key`
            , `value`
            , id_type
            , id_parent
            
          )
          VALUES
          (
              @lid
            , '$key'
            , '$value'
            , $id_type
            , $id_parent
          ); ";
        }
      }
    }
    return;
  }
}
?>