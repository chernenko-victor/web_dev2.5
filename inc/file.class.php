<?php
//ВКЛЮЧИТЬ("Класс Строка"); -- для общности, класс встроенный

class FileAbstr
{
  private /* String */ $sURL;
  
  public function /* FileAbstr(); */ __construct(/* class String */ $sURLNew)
  {
    $this->sURL = $sURLNew;
  }
  
  public function /* ~FileAbstr(); */ __destruct()
  {
    unset($this->sURL);
  }
  
  public function /* String */ GetFileName()
  {
    return $this->sURL;
  }
}

?>