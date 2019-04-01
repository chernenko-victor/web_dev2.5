<?php
//rnd.class.php

include_once("../inc/util.class.php");
include_once("config.inc.php");

class GroupChange
{
  private /* array */ $agpGenParam;
  private /* Util */ $uUtil;
  private /* XmlConstruct */ $XmlConstruct;
  
  public function /* GroupChange */ __construct(/* array */ $agpGenParamNew)
  {
    $this->agpGenParam = array();
    $this->agpGenParam = $agpGenParamNew;
    $this->uUtil = new Util();
    $this->XmlConstruct = new XMLWriter();
  }
  
  public function /* ~GroupChange */ __destruct()
  {
    unset($this->XmlConstruct);
    unset($this->agpGenParam);
    unset($this->uUtil);
  }
  
  protected function /* int */ GetParamValByKey(/* String */$sKey)
  {
    /* int */ $iRes = 0;
    if(!is_array($this->agpGenParam))
    {
      return $iRes;
    }
    if(!isset($this->agpGenParam["genParams"]) || !is_array($aItems = $this->agpGenParam["genParams"]))
    {
      return $iRes;
    }
    
    if(!isset($aItems["item"]) || !is_array($aItems["item"]))
    {
      return $iRes;
    }
    foreach($aItems["item"] as $k => $v)
    {
      //echo "<br /><strong>$k</strong><br />";
      //print_r($v);
      //echo "<br /><br />";
      
      if(!is_array($v))
      {
        return $iRes;
      }
      if(isset($v) && $v["key"] == $sKey)
      {
        $iRes = $v["value"];
        break;
      }
    }
    return $iRes;
  }
  
  protected function /* array */ GetItemByKey(/* String */$sKey)
  {
    /* int */ $aRes = array();
    if(!is_array($this->agpGenParam))
    {
      return $aRes;
    }
    if(!isset($this->agpGenParam["genParams"]) || !is_array($aItems = $this->agpGenParam["genParams"]))
    {
      return $aRes;
    }
    
    if(!isset($aItems["item"]) || !is_array($aItems["item"]))
    {
      return $aRes;
    }
    foreach($aItems["item"] as $k => $v)
    {
      //echo "<br /><strong>$k</strong><br />";
      //print_r($v);
      //echo "<br /><br />";
      
      if(!is_array($v))
      {
        return $aRes;
      }
      if(isset($v) && $v["key"] == $sKey)
      {
        $aRes = $v;
        break;
      }
    }
    return $aRes;
  }
  
  protected function /* array */ GetParamArrByKey(/* String */$sKey, /* String */ $sFieldNm = "key")
  {
    /* array */ $aRes = array();
    if(!is_array($this->agpGenParam))
    {
      return $aRes;
    }
    if(!isset($this->agpGenParam["genParams"]) || !is_array($aItems = $this->agpGenParam["genParams"]))
    {
      return $aRes;
    }
    
    if(!isset($aItems["item"]) || !is_array($aItems["item"]))
    {
      return $aRes;
    }
    foreach($aItems["item"] as $k => $v)
    {
      //echo "<br /><strong>$k</strong><br />";
      //print_r($v);
      //echo "<br /><br />";
      
      if(!is_array($v))
      {
        return $aRes;
      }
      if(isset($v) && $v[$sFieldNm] == $sKey)
      {
        $aRes[] = $v;
      }
    }
    return $aRes;
  }
  
  protected function /* String */ GetNote(/* int */$iPitch, /* int */$iDur)
  {
    $this->XmlConstruct->startElement("note");
      $this->XmlConstruct->startElement("pitch");
        $this->XmlConstruct->startAttribute("type");
          $this->XmlConstruct->text("midi_num");
        $this->XmlConstruct->endAttribute();
        $this->XmlConstruct->text($iPitch);
      $this->XmlConstruct->endElement();
      $this->XmlConstruct->startElement("dur");
        $this->XmlConstruct->startAttribute("type");
          $this->XmlConstruct->text("sec");
        $this->XmlConstruct->endAttribute();
        $this->XmlConstruct->text($iDur);
      $this->XmlConstruct->endElement();
    $this->XmlConstruct->endElement();
  }
  
  protected function /* void */ GetAttr(/* string */ $sAttrNm, /* string */ $sAttrTxt)
  {
    if(!strlen($sAttrNm) || !strlen($sAttrTxt))
    {
      return;
    }
    $this->XmlConstruct->startAttribute($sAttrNm);
      $this->XmlConstruct->text($sAttrTxt);
    $this->XmlConstruct->endAttribute();
    return;
  }
  
  protected function /* void */ GetElement(/* string */ $sElNm, /* string */ $sElTxt, /* array */ $aAttr)
  {
    if(!strlen($sElNm) || !strlen($sElTxt))
    {
      return;
    }
    $this->XmlConstruct->startElement($sElNm);
      if(is_array($aAttr) && count($aAttr))
      {
        foreach($aAttr as $k => $v)
        {
          $this->GetAttr($k, $v);
        }
      }
      $this->XmlConstruct->text($sElTxt);
    $this->XmlConstruct->endElement();
    return;
  }
  
  public function /* String */ GetResXML($bEncode = true, $iFixedPitch = false, $fFixedDur = false)
  {
    /* String */ $sRes = "";
    
    $iIdAlgo = $this->GetParamValByKey("id_algo");
    //SELECT gen BY id_algo
    //SELECT user2gen BY id_algo
    //SELECT user.id AS uid, 
    
    //CHANGE gen
    //CHANGE user2gen
    
    //MAKE user2gen map

    $aCoord = array();
    //$iTime = $this->GetParamValByKey("time");
    //$iTime2 = $this->GetParamValByKey("time2");
    $iTime = 20;
    $iTime2 = 40;
    
    for($i = 0; $i < 8; $i++)
    {
      $aCoord[] = $iTime + rand(0, 10*$iTime2);
    }
    
    $this->XmlConstruct->openMemory();
    $this->XmlConstruct->setIndent(true);
    $this->XmlConstruct->setIndentString(' ');
    $this->XmlConstruct->startDocument('1.0', 'UTF-8');

    $this->XmlConstruct->startElement("notes");
    
    $j = 0; 
    //while($j < 8) //01 23 45 67
    while($j < 4) //01 23 45 67
    {
      if(!$iFixedPitch)
      {
        $iMidiNotePitch = $iTime + rand(0, 10*$iTime2);
      }
      else
      {
        $iMidiNotePitch = $iFixedPitch;
      }
      
      if(!$fFixedDur)
      {
        $fMidiNoteDur = 2.0*$this->GetFloatRndNorm2One()-1.0;
      }
      else
      {
        $fMidiNoteDur = $fFixedDur;
      }
      $this->GetNote($iMidiNotePitch, $fMidiNoteDur);
      //++$j;
      $j++;
    }

    $this->XmlConstruct->endElement();
       
    $this->XmlConstruct->endDocument();
    
    //header('Content-type: text/xml');
    $sRes = $this->XmlConstruct->outputMemory();

    if($bEncode)
    {
        $sRes = $this->uUtil->EncodeEntit($sRes);
    }
    return $sRes;
  }
  
  public function /* String */ GetResXMLNew(/* bool */ $bEncode = true, /* String */ $sRootElNm = "rows")
  {
    $this->XmlConstruct->openMemory();
    $this->XmlConstruct->setIndent(true);
    $this->XmlConstruct->setIndentString(' ');
    $this->XmlConstruct->startDocument('1.0', 'UTF-8');

    $this->XmlConstruct->startElement($sRootElNm);
    
    //body there
    /* array */ $aIdAlgo = $this->GetParamArrByKey("id_algo");
    if(isset($aIdAlgo[0]["value"]))
    {
      $this->GetElement($aIdAlgo[0]["key"], $aIdAlgo[0]["value"], $aIdAlgo[0]);
    }
  
    /* int */ $i = 0;
    /*
    генерация числа генераторов от 1 до кол-ва пользователей
    
    Генерируем число генераторов, генерируем их имена (поле nm), id_algo известно. В упрощенной реализации (т.е. сейчас) partition_cash оставляем пустым, next_gen_time делаем небольшим от текущего (а может пока тоже пустым? непонятно как соотносится время на серваке и на локали). Вставляем в generator и generator2user. В результате получим список генераторов (СГнов). Вставляем в БД.
    */
    /* array */ $aUserCnt = $this->GetParamArrByKey("user_cnt");
    if(isset($aUserCnt[0]["value"]))
    {
      $iNewGenNum = mt_rand(1, $aUserCnt[0]["value"]);
    }
    $aNewGen = array();
    $aNewGenOne = array();
    for($i = 1; $i <= $iNewGenNum; $i++)
    {
      /*
      1 	rnd note 1 	1 	<em>VexflowNote</em><br /><br /><div class="descri...	2014-01-21 15:32:59
      id    nm 	id_algo 	partition_cash 	next_gen_time
      */
      $sElNm = "gen";
      $sElTxt = "gen_algo".$aIdAlgo[0]["value"]."_".$i;
      $aNewGenOne = array(
          "id" => $i
        , "id_algo" => $aIdAlgo[0]["value"]
        , "nm" => $sElTxt
        , "partition_cash" => ""
        , "next_gen_time" => ""
      );
      $this->GetElement($sElNm, $sElTxt, $aNewGenOne);
      $aNewGen[] = $aNewGenOne;
    }
    
    /*
    распределение параметров генераторам (пока считаем, что нет изменения параметров) 
    
    Из СПст заключаем, какие типы параметров используются алгоритмом. Генерируем такие же (в данной реализации случайно с небольшой дельтой, чтобы не очень резко менять, в будущем надо будет как-то определять для каждого вида параметра допустимые границы изменения). Добавляем в param2generator, значение поля id_generator берем из СГнов. В результате получим список параметров для этих генераторов (СПнов). Вставляем в БД.
    */
    
    /*
    $aGenParam - массив названий параметров для данного алгоритма. Может ли быть для разных генераторов разные? В общем случае да, но пока считаем что нет. Как получить?
    
    1. Берем первый но порядку генератор - первый (или любой) потомок элемента gen_list
    
    2. берем ее непосредственные потомки
    
    */
    
    $aFstGenParamList = $this->GetParamNameArray();
    if(is_array($aFstGenParamList) && count($aFstGenParamList))
    {
      /*
      $i++;
      $sElNm = "gen_list";
      $sElTxt = 0;
      $aParam = array(
          "id" => $i
        , "id_generator" => $aIdAlgo[0]["value"]
        , "key" => "gen_list"
        , "value" => $sElTxt
        , "id_type" => 1
        , "id_parent" => 0
      );
      $this->GetElement($sElNm, $sElTxt, $aParam);
      */
      
      //3. берем новое кол-во генераторов $iNewGenNum
      //4. в цикле далаем небольшую вариацию каждому параметру
      
      foreach($aNewGen as $k1 => $v1)
      {
        //5. сохраняеи строку в $aGenParam
        foreach($aFstGenParamList as $k2 => $v2)
        {
          $i++;
          //id = <AUTO>	
          //id_generator = $iIdGen	_
          //key = ROW3[key]	_
          //ROW3[value]	
          //_ROW3[id_type]	
          //_LAST_ID2
          $sElNm = "gen_param";
          $sElTxt = $this->GetParamVar($v2["value"], $v2["id_type"], 1.01);
          //$sElTxt = $v2["value"];
          $aParam = array(
              "id" => $i
            , "id_generator" => $v1["id"]
            , "key" => $v2["key"]
            , "value" => $sElTxt
            , "id_type" => $v2["id_type"]
            , "id_parent" => 0
          );
          $this->GetElement($sElNm, $sElTxt, $aParam);
        }  
      }
    }
    
    /*
    распределение генераторам пользователей

    По СПОЛст и СГнов производим взаимное распределение, в текущей реализации как-нибудь простенько. Получаем СПОЛнов. Вставляем в БД (в смысле передаем на сервер, чтобы он вставил).
    */
    /* array */ $aUser = $this->GetParamArrByKey("user");
    if(is_array($aUser) && count($aUser))
    {
      $this->SetUser2GenDistr($aUser, $aNewGen);
    }
    
    /*
    генерация времени следующей генерации (перераспределения групп)
    */
    
    $iNextGenTime = rand(10, 60*3);
    $sElNm = "next_gen_time";
    $sElTxt = $iNextGenTime;
    $aParam = array();
    $this->GetElement($sElNm, $sElTxt, $aParam);

    $this->XmlConstruct->endElement();
       
    $this->XmlConstruct->endDocument();

    //header('Content-type: text/xml');
    $sRes = $this->XmlConstruct->outputMemory();

    if($bEncode)
    {
        $sRes = $this->uUtil->EncodeEntit($sRes);
    }
    return $sRes;
  }

  protected function /* void */ SetUser2GenDistr(/* array */ $aUser, /* array */ $aNewGen)
  {
    $i = 0;
    while (count($aUser)) 
    {
      foreach($aNewGen as $k => $v)
      {
        $aUser = array_values($aUser);
        $aUserOne = array();
        /* int */ $iCurrUserIndx = $this->GetRndUser($aUser, $aUserOne);
        if($iCurrUserIndx!=-1)
        {
          $this->SetUser2Gen($aUserOne, $v);
          //$this->DelUserFromArray($aUser, $aUserOne); //you're idiots... how can i unset var by ref inside the function?
          unset($aUser[$iCurrUserIndx]);
        }
        unset($iCurrUserIndx);
        unset($aUserOne);
      }
      if($i++ > 10) break;
    }
    return;
  }
  
  protected function /* int */ GetRndUser(/* array */ $aUser, /* array */  &$aUserOne)
  {
    /* int */ $iCurrUserIndx = 0;
    // $aUser 
    // [] => Array ( [key] => user [value] => 1 [id_type] => 1 [nm_type] => int [id_parent] => 2555 [id] => 2556 ) 
    // [] => Array ( [key] => user [value] => 1 [id_type] => 1 [nm_type] => int [id_parent] => 2555 [id] => 2556 ) 
    if(!count($aUser))
    {
      $iCurrUserIndx = -1;
    }
    else
    {
      $iCurrUserIndx = mt_rand(0, count($aUser)-1);
      $aUserOne = $aUser[$iCurrUserIndx];
    }
    return $iCurrUserIndx;
  }
  
  protected function /* void */ SetUser2Gen(/* array */ $aUserOne, /* array */ $aNewGenOne)
  {
    //$aNewGenOne = array(
    //      "id" => $i
    //    , "id_algo" => $aIdAlgo[0]["value"]
    //    , "nm" => $sElTxt
    //    , "partition_cash" => ""
    //    , "next_gen_time" => ""
    //  );

    $sElNm = "user2gen";
    $sElTxt = $aUserOne["value"]; //id_user
    $aParam = array(
        "id_gen" => $aNewGenOne["id"] //id_gen
    );
    $this->GetElement($sElNm, $sElTxt, $aParam);
    return;
  }
  
  //protected function /* void */ DelUserFromArray(/* array */ &$aUser, /* array */ $aUserOne);
  //{
  //  //...
  //  return;
  //}
  
  protected function /* array */ GetParamNameArray()
  {
    $aRes = array();
    $aGenListItem = $this->GetItemByKey("gen_list");
    if(!is_array($aGenListItem) || !count($aGenListItem))
    {
      return $aRes;
    }
    
    //[key] => gen_list [value] => 0 [id_type] => 1 [nm_type] => int [id_parent] => 0 [id] => 2559
    $aGenList = $this->GetParamArrByKey($aGenListItem["id"], "id_parent");
    if(!is_array($aGenList) || !count($aGenList))
    {
      return $aRes;
    }
    
    $aRes = $this->GetParamArrByKey($aGenList[0]["id"], "id_parent");

    /*
    [6] => Array ( [key] => gen_list [value] => 0 [id_type] => 1 [nm_type] => int [id_parent] => 0 [id] => 2559 ) 
 [7] => Array ( [key] => 1 [value] => 2014-01-14 21:06:37 [id_type] => 1 [nm_type] => int [id_parent] => 2559 [id] => 2560 ) 
 [8] => Array ( [key] => time [value] => 50 [id_type] => 1 [nm_type] => int [id_parent] => 2560 [id] => 2561 ) 
 [9] => Array ( [key] => time2 [value] => 4 [id_type] => 1 [nm_type] => int [id_parent] => 2560 [id] => 2562 ) 
 [10] => Array ( [key] => 2 [value] => 2014-01-14 18:07:29 [id_type] => 1 [nm_type] => int [id_parent] => 2559 [id] => 2563 ) 
 [11] => Array ( [key] => time [value] => 40 [id_type] => 1 [nm_type] => int [id_parent] => 2563 [id] => 2564 ) 
 [12] => Array ( [key] => time2 [value] => 4 [id_type] => 1 [nm_type] => int [id_parent] => 2563 [id] => 2566 ) 
 [13] => Array ( [key] => hash [value] => qwertyasdfg [id_type] => 3 [nm_type] => string [id_parent] => 2563 [id] => 2565 ) 
    */
    return $aRes;
  }
  
  protected function GetFloatRndNorm2One()
  {
    $fRndNormal2One = 1.0;
    $iUpLim = pow(2,16);
    $iRndNum = mt_rand(1, $iUpLim);
    $fRndNormal2One = ((float)$iRndNum)/((float)$iUpLim);
    return $fRndNormal2One;
  }
  
  protected function /* mixed */ GetParamVar(/* mixed */ $mInitVal, /* int */ $iType, /* float */ $fVariationMeasure = 0.5)
  {
    $mInitVal += $fVariationMeasure;
    switch($iType)
    {
      case 1:
        //int
        $mRes = (int)((float)$mInitVal * (1 + $fVariationMeasure * (2.0*$this->GetFloatRndNorm2One() - 1.0)));
      break;
      case 2:
        //float
        $mRes = (float)$mInitVal * (1 + $fVariationMeasure * (2.0*$this->GetFloatRndNorm2One() - 1.0));
      break;
      case 3:
        //string
        $mRes = $this->GetStrVariation($mInitVal, $fVariationMeasure);
      break;
    }
    return $mRes;
  }
  
  protected function /* String */ GetStrVariation(/* String */ $sIn, /* float */ $fVariationMeasure = 0.5)
  {
    /* String */ $sRes = "";
    $offset = 0;
    while ($offset >= 0) 
    {
        /* int */ $iLettrCode = $this->ordutf8($sIn, $offset);
        $iMewCode = (int)((float)$iLettrCode * (1 + $fVariationMeasure * (2.0*$this->GetFloatRndNorm2One() - 1.0)));
        $sRes .= chr($iMewCode);
        // http://ru2.php.net/manual/en/function.chr.php#88611
        // Another quick and short function to get unicode char by its code.
    }
    return $sRes;
  }
  
  protected function ordutf8($string, &$offset) {
    // http://ru2.php.net/manual/en/function.ord.php#109812
    // arglanir+phpnet at gmail dot com
    $code = ord(substr($string, $offset,1));
    if ($code >= 128) {        //otherwise 0xxxxxxx
        if ($code < 224) $bytesnumber = 2;                //110xxxxx
        else if ($code < 240) $bytesnumber = 3;        //1110xxxx
        else if ($code < 248) $bytesnumber = 4;    //11110xxx
        $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
        for ($i = 2; $i <= $bytesnumber; $i++) {
            $offset ++;
            $code2 = ord(substr($string, $offset, 1)) - 128;        //10xxxxxx
            $codetemp = $codetemp*64 + $code2;
        }
        $code = $codetemp;
    }
    $offset += 1;
    if ($offset >= strlen($string)) $offset = -1;
    return $code;
  }
}
?>