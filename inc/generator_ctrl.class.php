<?php
//generator_ctrl.class.php

//include_once("storage.class.php");

class GeneratorCtrl
{
  //private /* Storage */ $stStore;

  public function /* Generator */ __construct()
  {
    //$this->stStore = new Storage();
  }
  
  public function /* ~Generator */ __destruct()
  {
    //unset($this->stStore);
  }
  
  public function /* string */ GetGenList()
  {
    /* string */ $sRes ="<a href='#'>Генеративная грамматика</a><br />";
    //...
    return $sRes;
  }
}
?>