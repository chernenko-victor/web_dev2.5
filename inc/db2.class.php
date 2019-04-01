<?php

//http://www.php.net/manual/ru/function.mysql-close.php
//Данное расширение устарело, начиная с версии PHP 5.5.0, и будет удалено в будущем. Используйте вместо него MySQLi или PDO_MySQL. Смотрите также инструкцию MySQL: выбор API и соответствующий FAQ для получения более подробной информации.

class DBA
{
    private $connectID;
    private $sql_res;
    private $c;

    public function /* DBA(); */ __construct()
    {
      $this->connectID=0;
      $this->sql_res;
      $this->c=0;
    }
  
    public function /* ~DBA(); */ __destruct()
    {
      if(isset($this->connectID)) $this->connectID=null;
      $this->sql_res=null;
      $this->c=null;
    }
    
    public function Connect()
    {
        global $MYSQL;
        if (!$this->connectID) 
        {
            $this->connectID=mysql_connect($MYSQL["host"],$MYSQL["user"],$MYSQL["password"])
            or die('Could not connect to database server');
            /*
            mysql_query ("set character_set_client='cp1251'");
            mysql_query ("set character_set_results='cp1251'");
            mysql_query ("set collation_connection='cp1251_general_ci'");
            */
        }
        mysql_select_db($MYSQL["db_name"],$this->connectID)
        or die('Could not select database');
    }

    public function Disconnect()
    {
        //if ($this->connectID) 
        //{
        //    mysql_close($this->connectID);
        //}
    }

    public function Show_db_error($q_str)
    {
        echo "<div class=dbError>MySQL Error: <b>$q_str</b> :: ".mysql_error()."</div>";
    }

    public function /* bool */ Query($q_str)
    {
        $this->c++;
        if(!$this->sql_res[$this->c]=mysql_query($q_str, $this->connectID)) 
        {
            $this->Show_db_error($q_str);
            return false;
        }
        return true;
    }

    public function Get_row($f_name)
    {
        $row='';
        while ($row=mysql_fetch_array($this->sql_res[$this->c]))
        {
            $f_name($row);
        }
        mysql_free_result($this->sql_res[$this->c]);
        $this->c--;
    }

    public function Get_row_one()
    {
        $row=mysql_fetch_array($this->sql_res[$this->c], MYSQL_ASSOC);
        mysql_free_result($this->sql_res[$this->c]);
        $this->c--;
        return $row;
    }

    public function Decount()
    {
        $this->c--;
    }

    function Num_rows()
    {
        return mysql_num_rows($this->sql_res[$this->c]);
    }

    public function Get_row_limit($f,$from,$to)
    {
        $i=1;
        $row='';
        while ($row=mysql_fetch_array($this->sql_res[$this->c]))
        {
            if ($i>=$from && $i<=$to) {$f($row);}
            $i++;
        }
        mysql_free_result($this->sql_res[$this->c]);
        $this->c--;
    }
    
    public function getId()
    {
        return mysql_insert_id($this->connectID);
    }
    
    public function getArray($result)
    {
        $i = 0;

        while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
        {
            foreach ($row as $key => $value)
            {
                $data[$i][$key] = $value;
            }
            $i++;
        }
        return $data;  
    } 

    public function getDataArray($query) //$table, $id, $coll = "id", $name = "title"
    {
        //$query = $this->setSqlQuery($name, $table, $coll, $id);
        $this->Query($query);
        $data = array();
        if ($this->Num_rows())
        {
            $data = $this->getArray($this->sql_res[$this->c]);
            $this->Decount();
        }
        return $data;
    }
}
?>