<?php
include_once("xml_construct.class.php");

//xml_attrib_construct.class.php
class XmlAttribConstruct extends XmlConstruct
{
  public function __construct($prm_rootElementName, $prm_xsltFilePath='')
  {
    parent::__construct($prm_rootElementName, $prm_xsltFilePath='');
  }
  
  public function fromArrayAttrib($prm_array){
      if(is_array($prm_array)){
        foreach ($prm_array as $index => $element){
          if(is_array($element)){
            if(is_int($index))
            {
              $index = "item";
            }
            $this->startElement($index);
            $this->fromArray($element);
            $this->endElement();
          }
          else
            $this->setElement($index, $element);
         
        }
      }
    }
}
?>