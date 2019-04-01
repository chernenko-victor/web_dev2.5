<?php
//Класс Хранилище – высокоуровневая обертка для БД
include_once("db.class.php");
//include_once("generator.class.php");
//include_once("util.class.php");

class Storage
{
  private $dbDatabase;
  //private /* int */ $iSessionTime;
  //private $uUtil;

  public function /* Storage */ __construct()
  {
    $this->dbDatabase = new DBA();
    $this->dbDatabase->Connect();
    //$this->iSessionTime = 7 * 24 * 60 * 60;
    //$this->uUtil = new Util();
  }

  public function /* ~Storage */ __destruct()
  {
    //unset($this->uUtil);
    //unset($this->iSessionTime);
    $this->dbDatabase->Disconnect();
    unset($this->dbDatabase);
  }

  //$aNextNode["id_node"]
  public function /* void */ GetNextNodes(/* int */ $iIdNodeCurrent, /* array */ &$aRes, /* bool */ $is_fst = false)
  {    
    //echo "<br /><strong>public function /* void */ Storage::GetNextNodes</strong><br />";
    //echo "\$iIdNodeCurrent = $iIdNodeCurrent<br />";
    
    //echo "\$aRes ";
    //print_r($aRes);
    //echo "<br />";
    
    //echo "\$is_fst = $is_fst<br />";
    
    ///* string */ $sQuery = 
    //"
    //SELECT id, id_node, id_next 
    //FROM node_sequence 
    //WHERE id='$iIdNodeCurrent'
    //"; //WHERE id_node='$iIdNodeCurrent'
    //if($is_fst) 
    //{
    //  $sQuery .= " AND is_right=0";
    //}
    
    /* string */ $sQuery = 
    "
    SELECT NEXT_NODES.id, NEXT_NODES.id_node, NEXT_NODES.id_next 
    FROM node_sequence AS NEXT_NODES, node_sequence AS CURRENT_NODES 
    WHERE NEXT_NODES.id = CURRENT_NODES.id_next  
    AND CURRENT_NODES.id_node = '$iIdNodeCurrent' 
    ";
    if($is_fst) 
    {
      $sQuery .= " AND CURRENT_NODES.is_right=0";
    }
    
    //echo "\$sQuery = $sQuery<br />";
    
    $aRow = $this->dbDatabase->getDataArray($sQuery);
    if(count($aRow))
    {
      foreach($aRow as $k => $v)
      {
        $aRes[] = Array
        (
            "id" => $v["id"]
          , "id_node" => $v["id_node"]
          , "id_next" => $v["id_next"]
        );
      }
    }
    //echo "<strong>EXIT public function /* void */ Storage::GetNextNodes</strong><br /><br />";
    return;
  }  
  
  public function /* bool */ IsNodeFinite(/* String */ $sIdNode)
  {
    //echo "<br /><strong>public function /* bool */ Storage::IsNodeFinite</strong><br />";
    /* bool */ $bRes = false;
    /* string */ $sQuery = 
    "
    SELECT is_finite 
    FROM node 
    WHERE id='$sIdNode'
    ";
    $bQueryRes = $this->dbDatabase->Query($sQuery);
    //echo "\$sQuery = $sQuery<br />";
    if($bQueryRes)
    {
      $aRow = array();
      if ($this->dbDatabase->Num_rows())
      {
          $aRow = $this->dbDatabase->Get_row_one();
          if($aRow["is_finite"])
          {
            $bRes = true;
          }
      }
      else
      {
        $bRes = false;
      }
    }
    //echo "\$bRes = $bRes<br />";
    //echo "<strong>EXIT public function /* bool */ Storage::IsNodeFinite</strong><br /><br />";
    return $bRes;
  }
  
  public function /* String */ GetStartNodeId(/* String */ $sGrammarNm)
  {
    /* String */ $sRes = "";
    ///* string */ $sQuery = 
    //"
    //SELECT node_sequence.id AS node_sequence_id
    //FROM node_sequence, grammar
    //WHERE grammar.nm='$sGrammarNm' AND node_sequence.id_node = grammar.id_node_start
    //";
    /* string */ $sQuery = 
    "
    SELECT id_node_start
    FROM grammar
    WHERE nm='$sGrammarNm'
    ";
    
    /* bool */ $bQueruRes = false;
    $bQueruRes = $this->dbDatabase->Query($sQuery);
    if($bQueruRes)
    {
      $aRow = array();
      if ($this->dbDatabase->Num_rows())
      {
          $aRow = $this->dbDatabase->Get_row_one();
          //$sRes = $aRow["node_sequence_id"];
          $sRes = $aRow["id_node_start"];
          //echo "Node Name = ".$this->GetNodeNmById($sRes)."<br />";
      }
    }
    return $sRes;
  }

  public function /* String */ GetNodeNmById(/* String */ $sNodeId)
  {
    /* String */ $sRes = "";
    /* string */ $sQuery = 
    "
    SELECT nm
    FROM node
    WHERE id='$sNodeId'
    ";
    
    /* bool */ $bQueruRes = false;
    $bQueruRes = $this->dbDatabase->Query($sQuery);
    if($bQueruRes)
    {
      $aRow = array();
      if ($this->dbDatabase->Num_rows())
      {
          $aRow = $this->dbDatabase->Get_row_one();
          $sRes = $aRow["nm"];
      }
    }
    return $sRes;
  }

}
?>
