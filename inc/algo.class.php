<?php
//algo.class.php

include_once("generator.class.php");
include_once("storage.class.php");
include_once("xml_construct.class.php");
include_once("util.class.php");
include_once("markup_language.class.php");

class Algo
{
  private /* Generator */ $gGen;
  private /* Storage */ $stStore;
  private /* string */ $sUrl;
  private /* Util */ $uUtil;
  private /* MarkupLanguage */ $ml2Algo;

  public function /* Algo */ __construct(/* Generator */ $gNewGen)
  {
    $this->gGen = new Generator("", 0, "", 0);
    $this->gGen = $gNewGen;
    $this->stStore = new Storage();
    //get algo url by GenId
    $this->sUrl = $this->stStore->GetAlgoUrlByGenId($this->gGen->GetGenId());
    $this->uUtil = new Util();
    $this->ml2Algo = new MarkupLanguage($this->stStore->GetMarkupLangNameByGenId($this->gGen->GetGenId()));
  }
  
  public function /* ~Algo */ __destruct()
  {
    unset($this->ml2Algo);
    unset($this->uUtil);
    unset($this->sUrl);
    unset($this->stStore);
    unset($this->gGen);
  }
  
  /* string */ public function GetPartit()
  {
    /* string */ $sRes = "";
    //convert param 2 xml
    $agpGenParam = array();
    $this->gGen->GetGenParam($agpGenParam);
    //echo "<br />================================<br />\$agpGenParam<br />================================<br />";
    //print_r($agpGenParam); 
    //echo "<br />================================<br />";
    $sPostXml = $this->GenParam2Xml($agpGenParam);
    //send param
    //echo "<br />algo url = ".$this->sUrl."<br />";
    
    //$this->uUtil->ShowVar(/* sModuleName */ "Algo", /* sFuncName */ "GetPartit()", /* sVarName */ "this->sUrl", /* mVar */ $this->sUrl);
    
    //get partit
    //$this->uUtil->ShowVar(/* sModuleName */ "Algo", /* sFuncName */ "GetPartit()", /* sVarName */ "sPostXml", /* mVar */ $sPostXml);
    
    $sPartitSerializedXML = $this->SendParam2Algo($sPostXml);
    //echo "<br />sPartitSerializedXML = $sPartitSerializedXML<br />";
    //convert 2 html
    $sRes = $sPartitSerializedXML;
    return $sRes;
  }
  
  /* int */ public function GenerateRefreshTime()
  {
    /* int */ $iRes = "";
    //$iRes = 120;
    $iRes = rand(10, 60*3);
    return $iRes;
  }
  
  /* string */ private function GenParam2Xml(/* array */ $agpGenParam)
  {
    /* string */ $sRes = "";
    //echo "agpGenParam<br />";
    //print_r($agpGenParam);
    //echo "<br /><br />";

    /*
    $contents = array(
      'page_title' => 'Generate a XHTML page from XML+XSLT files',
      'welcome_msg' => 'Simple XHTML document from XML+XSLT files!',
      'prova' => array(
        "gino" => array(
          "innergino" => "gino inner value"
        ),
        "filo" => "filodata"
      ),
    );
    */

    $XmlConstruct = new XmlConstruct("genParams");
    $XmlConstruct->fromArray($agpGenParam);
    //$sRes = $this->uUtil->EncodeEntit($XmlConstruct->getDocument());
    $sRes = $XmlConstruct->getDocument();
    return $sRes;
  }
  
  /* string */ private function SendParam2Algo(/* string */ $sSerilizedXML)
  {
      /* string */ $sRes = "";
      //$url = "http://192.168.1.18:8081/jhgyy2012/index.php"; // это адрес, по которому скрипт передаст данные методом POST. Как видно, здесь указаны переменные, которые будут переданы через GET 
      $aParseUrl = array();
      $aParseUrl = parse_url($this->sUrl); // при помощи этой функции разбиваем адрес на массив, который будет содержать хост, путь и список переменных. 
      $sPath = $aParseUrl["path"]; // путь до файла(/patch/file.php) 
      if(isset($aParseUrl["query"]) && $aParseUrl["query"]) // если есть список параметров 
      {
        $sPath .= "?" . $aParseUrl["query"]; // добавляем к пути до файла список переменных(?var=23&var2=54) 
      }
      $sHost = $aParseUrl["host"]; // тут получаем хост (test.ru) 
      
      /*
      $date_time = $_POST["date_time"];
      $name = $_POST["name"];
      $url2post = $_POST["url"];
      $send = "Опубликовать";
      $group_id = $_POST["group_id"];
      $text = $_POST["news_text"];
      
      $data = "date_time=".urlencode($date_time)."&name=".urlencode($name)."&url=".urlencode($url2post)."&send=".urlencode($send)."&group_id=".urlencode($group_id)."&text=".urlencode($text); // а вот тут создаем список переменных с параметрами. Эти данные будут переданы через POST. Все значения переменных обязательно нужно кодировать urlencode ("еще тест") 
      */
      $sData = "serilized_xml=".urlencode($sSerilizedXML);
      
      //$this->uUtil->ShowVar(/* sModuleName */ "Algo", /* sFuncName */ "SendParam2Algo(\$sSerilizedXML)", /* sVarName */ "sData", /* mVar */ $sData);

      //$fp = fsockopen($sHost, 8080, $sErrno, $sErrstr, 10); 
      $fp = fsockopen($sHost, 80, $sErrno, $sErrstr, 10); 
      if ($fp) 
      { 
        $sOut = "POST ".$sPath." HTTP/1.1\r\n"; 
        $sOut .= "Host: ".$sHost."\r\n"; 
        $sOut .= "Referer: ".$this->sUrl."/\r\n"; 
        $sOut .= "User-Agent: Opera\r\n"; 
        $sOut .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
        $sOut .= "Content-Length: ".strlen($sData)."\r\n\r\n"; 
        $sOut .= $sData."\r\n\r\n"; 

        //echo $sOut;
        //$this->uUtil->ShowVar(/* sModuleName */ "Algo", /* sFuncName */ "SendParam2Algo(\$sSerilizedXML)", /* sVarName */ "sOut", /* mVar */ $sOut);
        fputs($fp, $sOut); // отправляем данные 

        // после отправки данных можно получить ответ сервера и прочитать информацию выданную файлом, в который отправили данные... 
        // читаем данные построчно и выводим их. Конечно, эти данные можно использовать по своему усмотрению. 
        while($sGets=fgets($fp,2048)) //здесь надо предусмотреть ситуацию когда слишком большой объем или просто не отвечает
        { 
          //print $sGets; 
          $sRes.=$sGets;
        } 
        fclose($fp); 
        //$this->uUtil->ShowVar(/* sModuleName */ "Algo", /* sFuncName */ "SendParam2Algo(\$sSerilizedXML)", /* sVarName */ "sRes", /* mVar */ $sRes);
		//echo "<h1>RESULT</h1>";
        //echo "<br />\$sRes = ".$sRes."<br /><br />";
        $sRes = $this->GetXMLOlny($sRes);
      }
      else
      {
        $sRes = "<br />Во время отправки на сайт произошла ошибка ($sErrno, $sErrstr). Отправьте данные об ошибке разработчикам сайта.<br />";
      }
      return $sRes;
  }
  
  /* string */ private function GetXMLOlny(/* string */ $sSerilizedXML)
  {
    //$sBeginXMLText = "&lt;?xml";
    $sBeginXMLText = "<?xml";
    $iPos = strpos($sSerilizedXML, $sBeginXMLText);
    
    if ($iPos !== false) {
        //echo "The string '$findme' was not found in the string '$mystring'";
      $sSerilizedXML = substr($sSerilizedXML, $iPos);
    }
    return $sSerilizedXML; 
  }
  
  /* MarkupLanguage */ public function GetMarkupLang()
  {
    return $this->ml2Algo;    
  }
}
?>