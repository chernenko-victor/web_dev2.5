<?php
$INC_DIR = $_SERVER["DOCUMENT_ROOT"]."\\dev2.5\\inc\\";
include_once($INC_DIR."util.class.php");

abstract class Event
{
  protected /* String */ $sId /* GUID */; 
  protected /* double */ $dBegin /* ms */;
  protected /* double */ $dLen /* ms */;
  protected /* Array[double][int][double][double] */ $a3Spectral /* ms, bin_number, Re, Im */;
  protected /* Array[Event] */ $aEventInc /* */;
  protected $uUtil;
  
  public function /* Duration */ __construct()
  {
    $this->uUtil = new Util();
    /* String */ $this->sId = $this->uUtil->GetGUID() /* GUID */; 
    /* double */ $this->dBegin = 0.0 /* ms */;
    /* double */ $this->dLen = 0.0 /* ms */;
    /* Array[double][int][double][double] */ $a3Spectral = array() /* ms, bin_number, Re, Im */;
  }

  public function /*~Message */ __destruct()
  {
    unset($this->sId); 
    unset($this->dBegin);
    unset($this->dLen);
    unset($a3Spectral);
	unset($this->uUtil);
  }
  
  public function /* String */ __get_sId()
  {
    return $this->sId;
  }
  
  abstract public function /* String */ __set_aEventInc();
  
  
  public function /* double */ __get_dBegin()
  {
    return $this->dBegin;
  }
  
  public function /* void */ __set_dBegin(/* double */ $dBeginNew)
  {
    $this->dBegin = $dBeginNew;
  }
  
  public function /* double */ __get_dLen()
  {
    return $this->dLen;
  }
  
  public function /* void */ __set_dLen(/* double */ $dLenNew)
  {
    $this->dLen = $dLenNew;
  }
}
?>