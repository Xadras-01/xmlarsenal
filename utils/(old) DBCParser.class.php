<?php

/*
**************************************************
**												**
** MangArsenal by AmrasTaralom					**
** amras-taralom@streber24.de					**
**												**
** last modified: 2009-02-24 by AmrasTaralom	**
**												**
**************************************************
*/


class DBCParser{

	//reading & parsing the dbc
	public static function readDBC($file_name, $stringColumns = null){
		
		$header = DBCParser::readHeader($file_name);
		
		//if there is a string block in the file
		if($header[4]>1){
			
			$table = DBCParser::readNonStringBlocks($file_name, $header);
			$strings = DBCParser::readStringBlock($file_name, $header);
			
			if($stringColumns == null){
				$stringColumns = DBCParser::tryDetectingStringColumns($table, $strings, $header);
			}//if
			
			foreach($table as $rowNum=>$row){
				
				foreach($row as $colNum=>$col){
					
					if(in_array($colNum, $stringColumns)){
						
						$table[$rowNum][$colNum] = $strings[$col];
						
					}//if
					
				}//foreach
				
			}//foreach 
			
		}else{
			
			$table = DBCParser::readNonStringBlocks($file_name, $header);
			
		}//else
		
		return $table;
		
	}//function readDBC($file_name, $stringColumns)


	//thanks to sourcepeek for this readHeader-function
	public static function readHeader($file_name){
		
		$fh = fopen('./dbc/'.$file_name, 'rb');
		$_hdr_data[] = $file_name; // just a little extra info; passed to $Header
		$file_format = fread($fh, 4); // be sure to fopen the file! 
		if($file_format == "WDBC") // is this a DBC file?
		{
			for($i = 0; $i < 4; $i++)
			{
				$bin = fread($fh, 4);
				$hex = bin2hex($bin);
				$hex = substr($hex, 6, 2).substr($hex, 4, 2).substr($hex, 2, 2).substr($hex, 0, 2);
				$_hdr_data[] = hexdec($hex);
			}
			
		}else{
			die($file_name." is not a DBC-file.");
		}//else
		
		return $_hdr_data; //0=>Name, 1=>Records, 2=>Fields, 3=>RecordSize, 4=>StringBlockSize
		
	}//function readHeader($file_name)


	//read the strings
	public static function readStringBlock($file_name, $header){
		
		$fp = fopen('./dbc/'.$file_name, 'rb');
		$bytesBeforeStringBlock = ($header[1] * $header[3]) + 20;
		$sizeOfFile = filesize('./dbc/'.$file_name);
		
		fseek($fp, $bytesBeforeStringBlock);
		
		$strings = array();
		$offset = 0;
		
		while((ftell($fp) < $sizeOfFile)){
			
			$char = fread($fp, 1);
			$readIndex = ftell($fp) - $bytesBeforeStringBlock;
			
			if($char == "\0"){
				$offset = $readIndex;
			}else{
				
				$strings[$offset] .= $char;
				
			}//else
			
		}//while
		
		return $strings;
		
	}//function readStringBlock($file_name)


	//read all other fields (treated as INTEGER for the moment)
	public static function readNonStringBlocks($file_name, $header){
		
		$fp = fopen('./dbc/'.$file_name, 'rb');
		fseek($fp, 20);
		$readBytes = filesize('./dbc/'.$file_name) - 20 - $header[4];
		
		$fields = array();
		$index = 0;
		
		while(ftell($fp) <= $readBytes){
			
			$val = fread($fp, 4);
			$hex = bin2hex($val);
			$hex = substr($hex, 6, 2).substr($hex, 4, 2).substr($hex, 2, 2).substr($hex, 0, 2);
			$fields[$index] = hexdec($hex);
			$index++;
		}
		
		$numRows = (count($fields)/$header[2]) - 1;
		$table = array();
		$row = -1;
		while($row < $numRows){
			
			for($column = 0; $column < $header[2]; $column++){
				
				if(($column%$header[2]) == 0){
					$row++;
				}
				
				$table[$row][$column] = $fields[$column + $row*$header[2]];
				
			}//for
		}//while
		
		unset($fields);
		return $table;
		
	}//function readNonStringBlocks($file_name)
	
	//this will be quite slow and might not work at all, better to specify the columns by hand
	//change the threshold-value (default is 0.95) to adjust the accuracy of detection
	public static function tryDetectingStringColumns($table, $strings, $header){
		
		$threshold = 0.95;
		$counter = 0;
		$stringCols = array();
		
		if($header[4]>1){
			
			$numRows = count($table);
			
			for($col = 0; $col < $header[2]; $col++){
				
				foreach($table as $rowId=>$rowValue){
					
					if(isset($strings[$table[$rowId][$col]])) $counter++;
					
				}
				
				if($counter >= $numRows * $threshold) $stringCols[] = $col;
				$counter = 0;
				
			}//for
			
			return $stringCols;
			
		}else{
			
			return array();
			
		}//else
		
	}//function tryDetectingStringColumns($file_name, $header)
	
}//class

?>