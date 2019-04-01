<?php
//generator.class.php

include_once("storage.class.php");

class Generator
{
  private /* int */ $iGenNextTime;
  private /* string */ $sPartitionCash;
  private /* string */ $sName;
  private /* int */ $iId;
  private /* array */ $agpGenParam;
  private /* Storage */ $stStore;
    
  public function /* Generator */ __construct(/* string */ $sNewName, /* int */ $iNewGenNextTime, /* int */ $sNewPartitionCash, /* int */ $iNewId)
  {
    $this->iGenNextTime = $iNewGenNextTime;
    $this->sPartitionCash = $sNewPartitionCash;
    $this->sName = $sNewName;
    $this->iId = $iNewId;
    $this->agpGenParam = array();
    $this->stStore = new Storage();
  }
  
  public function /* ~Generator */ __destruct()
  {
    unset($this->iId);
    unset($this->sName);
    unset($this->sPartitionCash);
    unset($this->iGenNextTime);
    unset($this->agpGenParam);
    unset($this->stStore);
  }
  
  public function SetGenNextTime(/* int */ $iNewGenNextTime)
  {
    $this->iGenNextTime = $iNewGenNextTime;
  }
  
  public function SetPartitionCash(/* int */ $sNewPartitionCash)
  {
    $this->sPartitionCash = $sNewPartitionCash;
  }
  
  public function GetGenNextTime()
  {
    return $this->iGenNextTime;
  }
  
  public function GetPartitionCash()
  {
    return $this->sPartitionCash;
  }  
  
  public function GetGenId()
  {
    return $this->iId;
  }
  
  public function SetGenParam(/* array Gen Param */ &$agpNewGenParam)
  {
    $this->agpGenParam = $agpNewGenParam;
  }
  
  public function GetGenParam(/* array Gen Param */ &$agpNewGenParam)
  {
     $agpNewGenParam = $this->agpGenParam;
  }
  
  public function /* void */ InsCalculatedData()
  {
    /* int */ $iIdGen = $this->iId;
    //$iIdGen = 7 для тестов 

    //здесь надо поределить, если генератор $v->GetGenId() реализует метаалготритм, т.е. изменение групп.
    //будем считать, что у такого алгоритма обязательно определен параметр id_algo
    //и ни у каких других его нельзя определять
    /*
    SELECT id FROM r5u7_param2generator
    WHERE id_generator = $iIdGen
    AND `key` = 'id_algo'
    */
    if($this->stStore->GetAlgoParamId($iIdGen)==0)
    {
      return;
    }
    
    /*    
    DELETE FROM r5u7_param2generator
    WHERE id_generator = $iIdGen
    AND `key`<>'id_algo'
    */
    $this->stStore->DelGroupChangeParam($iIdGen);
    
    /*
    //1. кол-во пользователей (через таблицы generator и generator2user) КПОЛ, 
        
    _ARRAY[_ROW] = 
    
    SELECT COUNT(C.id_user) AS user_cnt 
    FROM r5u7_param2generator AS A, r5u7_generator AS B, r5u7_generator2user AS C
    WHERE A.key = 'id_algo'
      AND A.id_generator = $iIdGen
      AND A.value = B.id_algo
      AND B.id = C.id_gen
    LIMIT 0, 1

    foreach(_ARRAY[_ROW])
    {
      INSERT r5u7_param2generator AS A 
      (
        A.id_generator
      , A.key
      , A.value
      , A.id_type
      , A.id_parent
      ) 
      VALUES 
      (
        $iIdGen
        , 'user_cnt'
        , _ROW[user_cnt]
        , 1
        , 0
      )
    }
    */
    
    $this->stStore->InsertGroupChangeParam($iIdGen, "user_cnt");
    /*
    //2. список пользователей (СПОЛст), 
    
    INSERT r5u7_param2generator AS A 
      (
          A.id_generator
        , A.key
        , A.value
        , A.id_type
        , A.id_parent
      ) 
      VALUES 
      (
        $iIdGen
        , 'user_list' // $11
        , 0
        , 1
        , 0
      )
      
      _LAST_ID


    _ARRAY[_ROW] =
    
    SELECT C.id_user
    FROM r5u7_param2generator AS A, r5u7_generator AS B, r5u7_generator2user AS C
    WHERE A.key = 'id_algo'
      AND A.id_generator = $iIdGen
      AND A.value = B.id_algo
      AND B.id = C.id_gen
    
    foreach(_ARRAY[_ROW])
    {
      INSERT r5u7_param2generator AS A 
      (
        A.id_generator
      , A.key
      , A.value
      , A.id_type
      , A.id_parent
      ) 
    VALUES 
      (
        $iIdGen
        , 'user' // $12
        , _ROW[id_user] // $13
        , 1
        , _LAST_ID
      )
    }
    */
    $this->stStore->InsertGroupChangeParam($iIdGen, "user_list");
    
    /*
    //3. списко генераторов (СГст), 
    
    INSERT r5u7_param2generator AS A 
      (
          A.id_generator
        , A.key
        , A.value
        , A.id_type
        , A.id_parent
      ) 
      VALUES 
      (
        $iIdGen
        , 'gen_list' // $21
        , 0
        , 1
        , 0
      )
      
      _LAST_ID 


    _ARRAY[_ROW] =
    
    SELECT B.*
    FROM r5u7_param2generator AS A, r5u7_generator AS B
    WHERE A.key = 'id_algo'
      AND A.id_generator = $iIdGen
      AND A.value = B.id_algo

    foreach(_ARRAY[_ROW])
    {
      INSERT r5u7_param2generator AS A 
      (
        A.id_generator
      , A.key
      , A.value
      , A.id_type
      , A.id_parent
      ) 
    VALUES 
      (
        $iIdGen
        , _ROW[id] // $22
        , _ROW[next_gen_time] // $23
        , 1
        , _LAST_ID
      )
      
      _LAST_ID2
    
      //4. список параметров для этих генераторов (СПст)
    
      _ARRAY3[_ROW3] =
    
      SELECT D.*
      FROM r5u7_param2generator AS A, r5u7_generator AS B, r5u7_param2generator AS D
      WHERE A.key = 'id_algo'
        AND A.id_generator = $iIdGen
        AND A.value = B.id_algo
        AND B.id = D.id_generator
        AND B.id = _ROW[id]
        
      foreach(_ARRAY3[_ROW3])
      {
        INSERT r5u7_param2generator AS A 
        (
          A.id_generator
        , A.key
        , A.value
        , A.id_type
        , A.id_parent
        ) 
      VALUES 
        (
          $iIdGen
          , _ROW3[key] // $32
          , _ROW3[value] // $33
          , _ROW3[id_type]
          , _LAST_ID2
        )
      }
       
    }
    */
    $this->stStore->InsertGroupChangeParam($iIdGen, "gen_list");
    
    return;
  }
}
?>