<?php
//include_once("C:/Program Files/wamp/www/dev2.5/algo/inc/stochastic.class.php");
include_once($_SERVER["DOCUMENT_ROOT"]."\\algo\\inc\\stochastic.class.php");

class TimelineNode
{
  private $iBegin;
  private $iEnd;
  private $iNm;
  private $sGUID;
  private $sApexPath;
  
  public function /* TimelineNode */ __construct(
	    $iBeginNew
	  , $iEndNew
	  , $iNmNew
	  , $sGUIDNew
	  , $sApexPathNew
  )
  {
	  $this->iBegin = iBeginNew;
	  $this->iEnd = iEndNew;
	  $this->iNm = iNmNew;
	  $this->sGUID = sGUIDNew;  
	  $this->sApexPath = sApexPathNew;  
  }
  
  public function /* ~TimelineNode */ __destruct()
  {
	  //...
  }
}

class Timeline 
{
    private $aLine;
	private $stNew;
	private $aXml;
	private $iMinOccurs;
	private $iMaxOccurs;
	private $iRepeatCnt;
	private $aCurrNode;
  
    public function /* Timeline */ __construct(
		  $sFileFullPathNm
		, $sType = "form"
		, $iBegin = 0
		, $iEndMin = 3
		, $iEndMax = 10
	)
    {
	  $this->stNew = new Stochastic();
      $this->aLine = array();
      $this->aCurrNode = array();
	  
	  $bOpenSuccess = true;
	  if (file_exists($sFileFullPathNm)) {
	    $this->aXml = simplexml_load_file($sFileFullPathNm);
		//print_r($xml);
	  } else {
		exit('Ошибка открытия файла $sFileFullPathNm\n');
		$bOpenSuccess = false;
	  }
	  
	  if($bOpenSuccess)
	  {
	    $sApexPath = "/schema/element[@name='".$sType."']";
	    $iEnd = $this->get_length($iEndMin, $iEndMax);
	    //echo "$iEnd = iEnd\n\n";
	    $this->get_node($sApexPath, $iBegin, $iEnd);
	  }
    }
  
	public function /* ~Timeline */ __destruct()
	{
		unset($this->aLine);
		unset($this->stNew);
	}
	
	private function get_length($iEndMin, $iEndMax)
	{
	  return $this->stNew->get_number($iEndMin, $iEndMax);
	}
	
	/*
	private function get_attr_val($sApexPath, $sAttrNm)
	{
	  //...
	}
	
	private function get_guid()
	{
	  //....
	}
	
	private function get_child_array($sApexPath, &$aNodeChild))
	{
	  //....
	}
	*/
  
    private function review_line()
	{
	  if(count($this->aLine)>0)
	  {
		  foreach($this->aLine as $aCurrNode)
		  {
			get_node($aCurrNode["sApexPath"], $iBegin, $iEnd);
		  }
	  }
	}
	
	private function /* int */ get_child_cnt($sApexPath)
	{
		$iRes = 0;
	    $aResult = $this->aXml->xpath($sApexPath);
		//print_r($aResult);
		
		while(list( , $xmleChildNode) = each($aResult)) 
		{
			//echo '/a/b/c: ',$node,"\n";
			//print_r($aChildNode);
			
			$aTmp = $xmleChildNode->xpath("complexType");
			$aTmp = $aTmp[0]->xpath("choice");	
			//print_r($aTmp);
			//foreach($aTmp[0]->foo[0]->attributes() as $a => $b) 
			//foreach($aTmp[0]->attributes() as $a => $b) 
			//{
				//echo $a,'="',$b,"\"\n";
				//echo "$a = $b <br />";
			//}
			$aAttr = $aTmp[0]->attributes();
			$this->iMinOccurs = $aAttr["minOccurs"];
			$this->iMaxOccurs = $aAttr["maxOccurs"];
			$this->iRepeatCnt = $aAttr["repetition"];
			$aTmp = $aTmp[0]->xpath("element");
			$iRes = count($aTmp);
			$this->aCurrNode = $aTmp;
			
			//print_r($aChildNode['complexType']);
			//if(array_key_exists('complexType', $aChildNode))
			//{
			//	print_r($aChildNode['complexType']);
			//}
			
			//foreach ($aChildNode->children() as $aChildNextLev) 
			//{
			//	echo '\$aChildNextLev: ',$aChildNextLev['name'],"\n";
			//}
		}
		return $iRes;
	}
	
	private function /* int */ get_child_to_gen_cnt($sApexPath)
	{
	  return $this->iMaxOccurs;
	}
		
	private function /* int */ get_repeat_cnt($sApexPath)
	{
	  return $this->iRepeatCnt;
	}
	
	private function gen_child($sApexPath, $iChildCnt, $iChildToGenCnt, $iRepeatCnt, $iParentBegin, $iParentEnd)
	{
	  //print_r($this->aCurrNode);
	  //generate specefied children, delete parent from $this->aLine, add generated to $this->aLine
	  
	  echo "\$this->iMinOccurs = ".$this->iMinOccurs."<br />";
	  echo "\$this->iMaxOccurs = ".$this->iMaxOccurs."<br />";
	  
	  //number from $this->iMinOccurs to $this->iMaxOccurs nodes
	  $iNumberOfNode = $this->get_length($this->iMinOccurs, $this->iMaxOccurs);
	  exit;
	  
	  for($i=0; $i < $iNumberOfNode; $i++)
	  {
	    //type from 0 to $iChildCnt 
		$iCurrType = $this->get_length(0, $iChildCnt);
		print_r($this->aCurrNode[$iCurrType]->element[0]);
		
		//with $iRepeatCnt repetition
		//time_begin
		//time_end
		//has_intersect	  
	  }
	  
	}
	
  
	function get_node($sApexPath, $iParentBegin, $iParentEnd) //
	{
	  //how many child type has $sApexPath element?
	  $iChildCnt = $this->get_child_cnt($sApexPath); //clear $sApexPath from <complexType> etc.
	  echo "\$iChildCnt = $iChildCnt<br />";
	  
	  if($iChildCnt>0)
	  {
	    //how many child must be generated?
	    $iChildToGenCnt = $this->get_child_to_gen_cnt($sApexPath); //from $sApexPath element attr
		
		echo "\$iChildToGenCnt = $iChildToGenCnt<br />";
	    	  
	    //how many repeat must be?
	    $iRepeatCnt = $this->get_repeat_cnt($sApexPath); //from $sApexPath element attr
		echo "\$iRepeatCnt = $iRepeatCnt<br />";
	  
	    //generate specefied children, delete parent from $this->aLine, add generated to $this->aLine
		//number from $this->iMinOccurs to $this->iMaxOccurs nodes
		//type from 0 to $iChildCnt 
		//with $iRepeatCnt repetition
		//time_begin
		//time_end
		//has_intersect
		$this->gen_child($sApexPath, $iChildCnt, $iChildToGenCnt, $iRepeatCnt, $iParentBegin, $iParentEnd);	
		exit;
		
		//repeat again
		$this->review_line();
	  }
	  /*
	  else
	  {
	    $this->aLine[] = new TimelineNode($iBegin, $iEnd, $this->get_attr_val($sApexPath, "name"), $this->get_guid());
	  }
	  */
	}

};
?>