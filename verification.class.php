<?php 
abstract class Verification{
	private $_data;
	
	//функция фильтрации данных
	public function ClearData($d, $type){
		$this->_data = $d;
		switch($type){
			case 's'://string
				return mysql_real_escape_string(trim(strip_tags($this->_data)));
			case 'sf'://string from file
				return trim(strip_tags($this->_data));
			case 'i'://integer
				return abs((int)$this->_data);
		}
	}
}
?>