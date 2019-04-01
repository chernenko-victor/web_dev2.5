<?php
// Класс для работы с шаблонами v 2.1

class TemplateAbstr
{
    // Переменные
    var $master_tpl;
    var $templates;
    var $repeat;
    var $errors;
    var $err = false;
    var $dir; // Пример: temlate/
    var $dir_img;
    
    //--------------------------------------------------------------------------------------------------------------
    // Конструктор класса
    
    function /* Template2 */ __construct($dir = "", $dir_img = "")
    {
        if (empty($dir)) echo "Ошибка шаблонизации. ".$this->getError(404);
        if (empty($dir_img)) echo "Ошибка шаблонизации. ".$this->getError(405);
        
        if ($this->err) return false;
        
        $this->setDir($dir, $dir_img);
    }

    //--------------------------------------------------------------------------------------------------------------
    // Функция вывода ошибок
    
    function getError($id, $arg = "")
    {
        $this->errors[101] = "Неверный код ошибки.";
        
        $this->errors[201] = "Не найден блок шаблона ".$arg.".";
        $this->errors[202] = "Не найден clean_block ".$arg." для блока table_cols ".$arg.".";
        
        $this->errors[401] = "Каталога '".$arg."' с не существует.";
        $this->errors[402] = "Шаблона '".$arg."' не существует.";
        $this->errors[403] = "Указанного имени шаблона не существует.";
        $this->errors[404] = "Не указан каталог с шаблонами.";
        $this->errors[405] = "Не указан каталог с изображениями.";
        
        $this->errors[501] = "Не указан файл шаблона.";
        $this->errors[502] = "Не указано название шаблона.";
        $this->errors[503] = "Ошибка в массиве \$catalog.";
        $this->errors[504] = "Ошибка в массиве \$data.";
        
        $this->errors[601] = "Несуществующая переменная в условии: ".$arg.".";
        
        $this->err = true;       
         
        if (!isset($this->errors[$id])) $id = 101;
        
        return "<strong style=\"color:red\">Ошибка: ".$this->errors[$id]."</strong><br>";
    }

    //--------------------------------------------------------------------------------------------------------------
    // Функция загрузки файла шаблона в строку
    
    function loadFileString($filename = "")
    {
        if (empty($filename)) echo $this->getError(501);
        
        if ($this->err) return false; 
        
        if (file_exists($this->dir.$filename))
        {
            return implode("", file($this->dir.$filename));
        }
        else
        {
            echo $this->getError(402, $filename);
            return false;
        }
    }
    
    function loadFile($filename = "")
    {
        if (empty($filename)) echo $this->getError(501);
        
        if ($this->err) return false; 
        
        if (file_exists($filename))
        {
            return implode("", file($filename));
        }
        else
        {
            echo $this->getError(402, $filename);
            return false;
        }
    }
        
    //--------------------------------------------------------------------------------------------------------------
    // Функция установки каталога с шаблонами
    
    function setDir($dir, $dir_img)
    {
        if(file_exists($dir)) $this->dir = $dir;
        else 
        {
            $this->dir = "";
            echo $this->getError(401, $dir);
            return false;
        }
        
        if(file_exists($dir_img)) $this->dir_img = $dir_img;
        else
        {
            $this->dir_img = "";
            echo $this->getError(401, $dir_img);
            return false;
        }
    }

    //--------------------------------------------------------------------------------------------------------------
    // Фунция удаления шаблона
     
    function deleteTemplate($name = "")
    {
        if (empty($name)) echo $this->getError(502);
                
        if ($this->err) return false;  
                
        if (isset($this->templates[$name])) unset($this->templates[$name]);
        else 
        {
            echo $this->getError(403);
            return false;
        }
        
        return true;
    }

    //--------------------------------------------------------------------------------------------------------------
    // Удаление маркеров из шаблона

    function deleteNull($name = "")
    {
       if (empty($name)) echo $this->getError(502);
       
       if ($this->err) return false; 
       
       return preg_replace("/{\w+\s*}/", "", $this->templates[$name]);
    }

    //--------------------------------------------------------------------------------------------------------------    
    // Загрузить шаблон
    
    function setTemplate($name = "", $filename = "", $data = array(), $action = NULL, $coll = array())
    {
        if (empty($filename)) echo $this->getError(501);
        if (empty($name)) echo $this->getError(502);
                
        if ($this->err) return false;  
        
        $temp[0] = $data;
        if(is_array($coll) && count($coll)) $this->getSizeImg($temp, $coll);
        $data = $temp[0];
        
        $this->templates[$name] = $this->loadFileString($filename);
        
        $this->setCondition2($this->templates[$name], $data, $temp = array());
        
        if (count($data))
        {
            foreach ($data as $key => $value)
            {
                $this->templates[$name] = preg_replace ("/{".$key."}/", $value, $this->templates[$name]);
            }
        }
        
        if ($action == "print")
        {
            echo $this->deleteNull($name);
            return true;
        }
        elseif ($action == "not") //ничего не выводит, просто добавляте шаблон в массив.
        {
            return true; 
        }
        else
        {
            return $this->deleteNull($name);
        }
    }

    //--------------------------------------------------------------------------------------------------------------    
    // !!! Вывести шаблон -- незнаю мне кажется эта функция ненужна. (скрытая функция :)) )
        
    function getTemplate($name = "", $action = NULL)
    {
        if (empty($name)) echo $this->getError(502);
        
        if ($this->err) return false;       
    
        if ($action == "print")
        {
            echo $this->deleteNull($name);
            return true;
        }
        else
        {
            return $this->deleteNull($name);
        }
    }
    
    //--------------------------------------------------------------------------------------------------------------    
    // Вывод каталога   
    
    function getCatalog($name = "", $filename = "", $catalog = array(), $data = array(), $coll = array())
    {
    
        if (empty($filename)) echo $this->getError(501);
        if (empty($name)) echo $this->getError(502);
        if (!is_array($catalog)) echo $this->getError(503);
        if (!is_array($data)) echo $this->getError(504);
        
        if ($this->err) return false;            
        
        $this->templates[$name] = $this->loadFileString($filename);

        $this->findBlocks($name);
               
        if (count($catalog) > 0)
        {
            $this->checkCols($catalog);
            if(is_array($coll) && count($coll)) $this->getSizeImg($catalog, $coll);
            $this->templates[$name] = $this->generateCatalog($name, $catalog, $data, $coll);
        }
        else
        {
            foreach ($data as $key => $value)
            {
                $this->master_tpl["table_empty"] = preg_replace("/{".$key."}/", $value, $this->master_tpl["table_empty"]);  	
            }
                    
            $this->templates[$name] = $this->master_tpl["table_empty"];
        }
        
        $this->master_tpl = array();
        return $this->deleteNull($name);
    }
    
    //--------------------------------------------------------------------------------------------------------------    
    // Добавление размеров изображений
    
    function getSizeImg(&$catalog, &$coll)
    {
        $root = $_SERVER["DOCUMENT_ROOT"]."/";
        foreach ($catalog as $key => $value)
        {
            foreach ($coll as $k => $v)
            {
                if (isset($catalog[$key][$v]) && !empty($catalog[$key][$v]) && file_exists($root.$this->dir_img.$catalog[$key][$v]))
                {
                    $size = @GetImageSize($root.$this->dir_img.$catalog[$key][$v]);
                    $catalog[$key][$v."_width"] = $size[0];
                    $catalog[$key][$v."_height"] = $size[1];
                    $catalog[$key][$v."_src"] = $this->dir_img.$catalog[$key][$v];
                }
                else
                {
                    $catalog[$key][$v."_src"] = "";
                }
            }
        }
    }
    //--------------------------------------------------------------------------------------------------------------    
    // Проверка условий в шаблоне  
    
    function setCondition2(&$block, &$element, &$data)
    {
    	if (preg_match_all("/<%IF_(\d+)\((.*)\){(.*)}ELSE{(.*)}%>/Us", $block, $matches, PREG_SET_ORDER) &&  !isset($element["clean_block"])) 
    	{   		
        	foreach ($matches as $key => $value)
        	{   		
        		$conditions = preg_split("/(AND|OR)/", $matches[$key][2], -1, PREG_SPLIT_NO_EMPTY);
        		$count = count($conditions);
        		if (isset($operator)) unset($operator);
        		if($count > 1) preg_match("/\((.*)\)(AND|OR)\((.*)\)/Us", $matches[$key][2], $operator); 
        		$verify = true;
        		if (isset($operator[2]) && $operator[2] == "OR") $verify = false;
        		foreach ($conditions as $num => $condition)
        		{
   					preg_match_all("/\((.*)(=|>|!|<)(.*)\)/", $condition, $values, PREG_SET_ORDER); 
   				   					
   					if (substr($values[0][1], 0, 1) == "$")
					{
						$name = substr($values[0][1], 1);
						if (isset($element[$name])) $condition1 = $element[$name];
						elseif (isset($data[$name])) $condition1 = $data[$name];
						else echo $this->getError(601, $name);
					}
					else 
					{
						$condition1 = $values[0][1];
					}
					
					if (substr($values[0][3], 0, 1) == "$")
					{
						$name = substr($values[0][3], 1);
						if (isset($element[$name])) $condition2 = $element[$name];
						elseif (isset($data[$name])) $condition2 = $data[$name];
						else echo $this->getError(601, $name);
					}
					else 
					{
						$condition2 = $values[0][3];
					}
								
					if ($this->err) return false;					
		
                	if (($verify || (isset($operator[2]) && $operator[2] == "OR")) && $values[0][2] == "=") 
                	{
                	  	if ($condition1 == $condition2) {$verify = true;} elseif ($verify && (isset($operator[2]) && $operator[2] == "OR")) {$verify = true;} else {$verify = false;}
                	}
                	elseif (($verify || (isset($operator[2]) && $operator[2] == "OR")) && $values[0][2] == ">") 
                	{
                	  	if ($condition1 > $condition2) {$verify = true;} elseif ($verify && (isset($operator[2]) && $operator[2] == "OR")) {$verify = true;} else {$verify = false;}
                	}
                	elseif (($verify || (isset($operator[2]) && $operator[2] == "OR")) && $values[0][2] == "<") 
                	{
                	  	if ($condition1 < $condition2) {$verify = true;} elseif ($verify && (isset($operator[2]) && $operator[2] == "OR")) {$verify = true;} else {$verify = false;}
                	}
                	elseif (($verify || (isset($operator[2]) && $operator[2] == "OR")) && $values[0][2] == "!") 
                	{
                	  	if ($condition1 != $condition2) {$verify = true;} elseif ($verify && (isset($operator[2]) && $operator[2] == "OR")) {$verify = true;} else {$verify = false;}
                	}                	                	
                }
        		
        		if($verify) $true_value = $value[3]; else $true_value = $value[4];
        		
        		$block = preg_replace("/<%IF_".$value[1]."\((.*)\){(.*)}ELSE{(.*)}%>/Us", $true_value, $block);
        	}
        	
        }
    }   
    
    
    function setCondition3(&$block, &$element, &$data)
    {
        
        
    } 
    
    //--------------------------------------------------------------------------------------------------------------    
    // Генерация каталога

    function generateCatalog($n, &$catalog, &$data, &$coll)
    {
        $i = 0;
        $j = 0;
        $count = count($catalog);
        $output = "";
        $output_temp = "";

        $this->setCondition2($this->master_tpl["table_header"], $data, $temp_array=array());
        $this->setCondition2($this->master_tpl["table_footer"], $data, $temp_array=array());
        
        foreach ($catalog as $id => $element)
        {
            foreach ($this->master_tpl["table_cols"] as $num_tpl => $block)
            {
                $temp[$num_tpl] = $block;
                $this->setCondition2($temp[$num_tpl], $element, $data);
                
                foreach ($element as $key => $value)
                {
                    if ($key == "clean_block" && $value == "break")
                    {
                        $temp[$num_tpl] = $this->master_tpl["clean_block"][$num_tpl];
                    }
                    else
                    {
                        $temp[$num_tpl] = preg_replace("/{".$key."}/", $value, $temp[$num_tpl]);
                    }
                }
                
                foreach ($data as $key => $value)
                {
                    if ($key == "clean_block" && $value == "break")
                    {
                        $temp[$num_tpl] = $this->master_tpl["clean_block"][$num_tpl];
                    }
                    else
                    {
                        $temp[$num_tpl] = preg_replace("/{".$key."}/", $value, $temp[$num_tpl]);
                    }
                    $this->master_tpl["table_header"] = preg_replace("/{".$key."}/", $value, $this->master_tpl["table_header"]);  	
                    $this->master_tpl["table_footer"] = preg_replace("/{".$key."}/", $value, $this->master_tpl["table_footer"]);  	
                }
            }
            $i++;
            $j++;
            
            foreach ($temp as $key => $value)
            {
                if (isset($lines[$key])) $lines[$key] .= $temp[$key];
                else $lines[$key] = $temp[$key];
                
                if (isset($this->master_tpl["table_separator_v"][$key]) && $i != $this->repeat) $lines[$key] .= $this->master_tpl["table_separator_v"][$key];
            }
            
            if ($i == $this->repeat)
            {
                $line = $this->master_tpl["table_row"];
                foreach ($lines as $key => $value)
                {
                    $line = preg_replace("/<!-- start table_cols $key -->(.*)<!-- end table_cols $key -->/Us", $value, $line);
                }

                $lines = "";
                $i = 0;
                              
                $output_temp .= $line;
                
                if (isset($this->master_tpl["table_separator_h"]) && $j != $count) $output_temp .= $this->master_tpl["table_separator_h"];
            }
            

        }
        $output .= $this->master_tpl["table_header"];
        $output .= $output_temp;
        $output .= $this->master_tpl["table_footer"];

        return $output;
    }
    
    //--------------------------------------------------------------------------------------------------------------    
    // Добавление недостающих элементов       
    
    function checkCols(&$data)
    {
        $count = count($data);
        $cnt_el = 0;
        
        //if ($count < $this->repeat)
        {
            //$this->repeat = $count;
            //return true;
        }
        //else
        {
            $protect = $count / $this->repeat;
            if ($protect != intval($protect))
            {
                //if ($count > $this->repeat)
                {
                    $cnt_el = (ceil($protect) * $this->repeat) - $count;
                }
                
                while ($cnt_el > 0) 
                {
                    $data[]["clean_block"] = "break";
                    $cnt_el--;
                }
            }
            
            return true;
        }
    }
    
    //--------------------------------------------------------------------------------------------------------------    
    // Поиск блоков в шаблоне каталога
    
    function findBlocks($name = "")
    {
        if (empty($name)) echo $this->getError(502);
        if ($this->err) return false;
        
        //Уникальные блоки
        $blocks = array("table_header" => 1, "table_group" => 0, "table_separator_h" => 0, "table_footer" => 1, "table_empty" => 1);

        foreach ($blocks as $key => $value)
        {
            $reg["unic_blocks"] = "/<!-- start $key -->(.*)<!-- end $key -->/Us";
            
            preg_match($reg["unic_blocks"],  $this->templates[$name], $matches);
            
            if (count($matches)) $this->master_tpl[$key] = $matches[1];
            elseif ($value == 1) echo $this->getError(201, $key);
        }
        
        //Повторяющиеся блоки
        $blocks = array("table_cols" => 1, "clean_block" => 0, "table_image" => 0, "table_separator_v" => 0);
       
        foreach ($blocks as $key => $value)
        {
            $reg["multi_blocks"] = "/<!-- start $key (\d+) -->(.*)<!-- end $key [\d]+ -->/Us";
            preg_match_all($reg["multi_blocks"],  $this->templates[$name], $matches);
            if (count($matches[2]))
            {
                foreach ($matches[2] as $k => $v)
                {
                    $this->master_tpl[$key][$matches[1][$k]] = $matches[2][$k];
                }
            }
            elseif ($value == 1) echo $this->getError(201, $key);
        }

        foreach ($this->master_tpl["table_cols"] as $key => $value)
        {
            if(!isset($this->master_tpl["clean_block"][$key])) echo $this->getError(202, $key);
        }
        
        //table_row
        $reg["table_row"] = "/<!-- start table_row (\d+) -->(.*)<!-- end table_row -->/Us";
        preg_match($reg["table_row"],  $this->templates[$name], $matches);
        if (count($matches)) 
        {
            $this->master_tpl["table_row"] = $matches[2];
            $this->repeat = $matches[1];
        }
        else echo $this->getError(201, "table_row");

        if ($this->err) return false;
    }
}
?>