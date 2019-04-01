<?php
include_once("C:/Program Files/wamp/www/dev2.3/algo/inc/rnd.class.php");

class RndRythm extends Rnd
{
  public function /* String */ GetResXML($bEncode = true, $iFixedPitch = false, $fFixedDur = false)
  {
    //echo "\$iFixedPitch = $iFixedPitch || \$fFixedDur = $fFixedDur<br /><br />";

    /* String */ $sRes = "";

    /*
    $iTime = $this->GetParamValByKey("time"); //8
    $iTime2 = $this->GetParamValByKey("time2"); //2
	$this->st1->SetMinI($iTime);
	$this->st1->SetMaxI(100*$iTime2);
	
	$aPtch = array();
	for($i=0; $i<=11; $i++)
	{
	  $aPtch[] = $iTime + 50 + $i;
	}
	
	$aPthcSel = $this->cmb1->get_array_wout_repeat($aPtch, 4);
    */
    
    $this->XmlConstruct->openMemory();
    $this->XmlConstruct->setIndent(true);
    $this->XmlConstruct->setIndentString(' ');
    $this->XmlConstruct->startDocument('1.0', 'UTF-8');

    $this->XmlConstruct->startElement("notes");
    
	$aDurRaw = array();
	for($i=0; $i<4; $i++)
	{
	  $aDurRaw[$i] = 2.0*$this->GetFloatRndNorm2One()-1.0;
	}
	
	$this->cmb1->no_neighbor_same_class($aDurRaw, $aDur);
	
    $j = 0; 
    while($j < 4) 
    {
      
      $iMidiNotePitch = $iFixedPitch;
      //$fMidiNoteDur = 2.0*$this->GetFloatRndNorm2One()-1.0;
	  $fMidiNoteDur = $aDur[$j];      
      $this->GetNote($iMidiNotePitch, $fMidiNoteDur);
      $j++;
    }
	
    $this->XmlConstruct->endElement();
       
    $this->XmlConstruct->endDocument();
    
    //header('Content-type: text/xml');
    $sRes = $this->XmlConstruct->outputMemory();

    if($bEncode)
    {
        $sRes = $this->uUtil->EncodeEntit($sRes);
    }
    return $sRes;
  }
}
?>