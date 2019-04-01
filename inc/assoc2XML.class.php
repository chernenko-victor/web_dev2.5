<?php
class assoc2Xml {
  private /* XmlConstruct */ $XmlConstruct;
  private /* Util */ $uUtil;
  
  public function /* assoc2Xml */ __construct()
  {
    $this->uUtil = new Util();
    $this->XmlConstruct = new XMLWriter();
  }
  
  public function /* ~assoc2Xml */ __destruct()
  {
    unset($this->XmlConstruct);
	unset($this->uUtil);
  }
  
  public function Array2Xml($aArr)
  {
	  if(is_array($aArr) && count($aArr))
	  {
	    foreach($aArr as $k => $v)
		{
		    $mystring = $k;
			$findme   = '@';
			$pos = strpos($mystring, $findme);

			// Note our use of ===.  Simply == would not work as expected
			// because the position of 'a' was the 0th (first) character.
			if ($pos === false) 
			{
				//echo "The string '$findme' was not found in the string '$mystring'";
				//simple or element content
				$hash_sign_pos = strpos($k, "#");
				if ($hash_sign_pos !== false) 
				{
				  $k = $this->uUtil->before  ('#', $k);
				}
				//echo "$k<br />";
				$this->XmlConstruct->startElement($k);
				  if(is_array($v) && count($v))
				  {
				    //element content
					$this->Array2Xml($v);
				  }
				  else
				  {
				    //simple content
				    $this->XmlConstruct->text($v);
				  }
		        $this->XmlConstruct->endElement();
			} else {
				//echo "The string '$findme' was found in the string '$mystring'";
				//echo " and exists at position $pos";
				//attribute
				$k = $this->uUtil->after  ('@', $k);
				$this->XmlConstruct->startAttribute($k);
                  $this->XmlConstruct->text($v);
                $this->XmlConstruct->endAttribute();
			}
		}
      }
  }
  
  public function /* string */ getPartit(/* array */ $aPartit)
  {
    /* string */ $sRes = "";
    $this->XmlConstruct->openMemory();
    $this->XmlConstruct->setIndent(true);
    $this->XmlConstruct->setIndentString(' ');
    $this->XmlConstruct->startDocument('1.0', 'UTF-8');

	$this->Array2Xml($aPartit);
	//$this->XmlConstruct->endElement();
       
    $this->XmlConstruct->endDocument();
    $sRes = $this->XmlConstruct->outputMemory();
	return $sRes;
  }
}
?>