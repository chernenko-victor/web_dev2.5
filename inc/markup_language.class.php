<?php
// markup_language.class.php
//include_once("string.class.inc"); -- для общности, класс встроенный

class MarkupLanguage
{
  private $sMarkupLang;
  
  public function /* MarkupLanguage(); */ __construct(/* class String */ $sMarkupLangNew)
  {
    $this->sMarkupLang = $sMarkupLangNew;
  }
  
  public function /* ~MarkupLanguage(); */ __destruct()
  {
    unset($this->sMarkupLang);
  }
  
  public function /* String */ GetMLString()
  {
    return $this->sMarkupLang;
  }
}
?>