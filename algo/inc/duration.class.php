<?php
include_once("event.class.php");

class Duration extends Event
{
  
  public function /* String */ __get_sId()
  {
    return $this->sId;
  }
  
  public function /* String */ __set_aEventInc()
  {
    //...
  }
}
?>