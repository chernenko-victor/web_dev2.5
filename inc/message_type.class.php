<?php
// message_type.class.php

//include_once("string.class.inc"); -- для общности, класс встроенный

class MessageType
{
  private $sType;
  
  public function /* MessageType(); */ __construct(/* class String */ $sTypeNew)
  {
    $this->sType = $sTypeNew;
  }
  
  public function /* ~MessageType(); */ __destruct()
  {
    unset($this->sType);
  }
}
?>