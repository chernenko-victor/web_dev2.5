<?php
//generative grammar class

include_once("options.inc.php");
include_once("storage.class.php");
//include_once("util.class.php");
include_once("C:/Program Files/wamp/www/dev2.3/inc/util.class.php");

class GenerativeGrammar
{
  //private /* String */ $sNodeTypeNm;
  private /* String */ $sGrammarNm;
  //private /* List*/ $lNodeSeq;
  private /* Array */ $aNodeSeq;
  private /* Storage */ $stStor;
  private /* Util */ $uUtil;
  
  public function /* GenerativeGrammar */ __construct(/* String */ $sGrammarNmNew) ///* String */ $sNodeTypeNmNew, 
  {
    //$this->sNodeTypeNm = $sNodeTypeNmNew;
    $this->sGrammarNm = $sGrammarNmNew;
    //$this->lNodeSeq = new List();
    $this->aNodeSeq = array();
    $this->stStor = new Storage();
    $this->uUtil = new Util();
  }
  
  public function /* ~GenerativeGrammar */ __destruct()
  {
    unset($this->uUtil);
    //unset($this->sNodeTypeNm);
    unset($this->sGrammarNm);
    //unset($this->lNodeSeq);
    unset($this->aNodeSeq);
    unset($this->stStor);
  }
  
  public function /* array */ GetFiniteNodeSetXML()
  {
    //$this->agpGenParam = array();
    $this->GetFiniteNodeSeq();
    //echo "<strong>\$this->aNodeSeq</strong> ";
    //print_r($this->aNodeSeq);
    //echo "<br />";
    foreach($this->aNodeSeq as $k => $v)
    {
      echo "Node Name = ".$this->stStor->GetNodeNmById($v["id_node"])."<br />";
    }
  }
  
  private function /* array */ GetFiniteNodeSeq()
  {
    $this->GetNextNode($this->stStor->GetStartNodeId($this->sGrammarNm));
  }
  
  //node 1 -> node_sequence N
  //надо из node_seq выбирать по id_node
  private function /* void */ GetNextNode(/* String */ &$sIdNodeCurrent)
  {
    /* bool */ $is_fst = true;
    
    //echo "<br /><strong>GetNextNode</strong><br />";
    //echo "\$sIdNodeCurrent = $sIdNodeCurrent<br />";
    //echo "Node Name = ".$this->stStor->GetNodeNmById($sIdNodeCurrent)."<br />";
    
    if(!strlen($sIdNodeCurrent))
    {
      return;
    }
    $aNextNode = Array
    (
        "id" => -1
      , "id_node" => $sIdNodeCurrent
      , "id_next" => -1
    );
    
    //echo "\$aNextNode ";
    //print_r($aNextNode);
    //echo "<br />";
    
    while($aNextNode["id_node"]) //lets get whole seq
    {
      // select id, id_node, id_next from node_sequence where id_node='$aNextNode["id_node"]'
      // if($is_fst) concat and is_right=0      => result_set
      $aRes = Array();
      $this->stStor->GetNextNodes($aNextNode["id_node"], $aRes, $is_fst);
      
      //echo "\$aRes ";
      //print_r($aRes);
      //echo "<br />";
      
      if(count($aRes))
      {
        //$aNode = Array();
        foreach($aRes as $k => $v)
        {
          //$aNextNode = Array
          //(
          //    "id" => $aRes["id"]
          //  , "id_node" => $aRes["id_node"]
          //  , "id_next" => $aRes["id_next"]
          //);
          //$aNextNode = $v;
          //$aNode->push_back($aNextNode);
          
          //echo "Node Name = ".$this->stStor->GetNodeNmById($v["id_node"])."<br />";
        }
        $aOut = Array();
        $this->uUtil->GetRndValueFromArray($aRes, $aOut); // get one branch
        $aNextNode = $aOut;
        
        //echo "\$aNextNode ";
        //print_r($aNextNode);
        //echo "<br />";
        //echo "Node Name = ".$this->stStor->GetNodeNmById($aNextNode["id_node"])."<br />";
      }
      else
      {
        break;
      }

      //if(!$is_fst) //only right part of rule we need to save
      //{
        if($this->stStor->IsNodeFinite($aNextNode["id_node"])) //this is final essence, not metasymbol
        {
          //vector->push_back($aNextNode);
          //echo "<strong>-== this is final essence, not metasymbol ==-</strong><br />";
          $this->aNodeSeq[] = $aNextNode;
        }
        else //this is metasymbol, lets look deeper
        {
          //echo "<strong>-== this is metasymbol, lets look deeper ==-</strong><br />";
          $this->GetNextNode($aNextNode["id_node"]);
        }
      //}
      //else //we need not to save left part of rule
      //{
      //  $is_fst = false;
      //}
      if($is_fst)
      {
        $is_fst = false;
      }
    }
  }
}
?>