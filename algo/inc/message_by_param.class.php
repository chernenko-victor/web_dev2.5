<?php
//message_by_param.class.php

include_once("tr_xml2assoc.class.php");

class MessageByParam
{
  var $piLocal //ParameterIn
  var $data;
  var $trXML2Assoc1;

  __construct($piLocal) //ParameterIn
  {
    global $_POST["serilized_xml"];
    global $MYSQL["global"];
    
    $this->trXML2Assoc1 = new TrXml2Assoc();
    $this->trXML2Assoc1->SetMessage($_POST["serilized_xml"]);
  };
  
  function __destruct() {
    unlink($this->trXML2Assoc1);
  }
  
  GetMsg() //void
  {
    //get $piLocal from Net
    
    $this->piLocal = $this->trXML2Assoc1->GetMessage();
  
    //select Algo by $piLocal->iAlgoType
    //$gdb1 = new globalDB($MYSQL["global"]);
    //$sAlgoSrcUrl = $gdb1->GetAlgoSrcByType($$pi1->iAlgoType);
    //test $sAlgoSrcUrl
    //include_once($sAlgoSrcUrl);
  
    //generate $data by $piLocal->algo_specified_parameters from Algo
  
    //select Message by $piLocal->mlOutputFormat;
  
    //get formatted $message by $data from Message
  
    //send $message
  };
}
?>