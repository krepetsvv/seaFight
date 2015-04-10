<?php
//create configuration file
class SeaFightDB {
	const DB_HOST = 'localhost';//адрес сервера баз данных МуСКЛ
	const DB_LOGIN = 'root';//логин для соединения с сервером БД
	const DB_PASSWORD = '123';//пароль для соединения с сервером БД
	const DB_NAME = 'SeaFightDB';//имя БД

	private $_conn;//для хранения объекта соединения с базой данных
	private $_db;//для хранения объекта базой данных
	
	private function CreateDBandTables(){
		//create db 
		mysql_query("Create database `SeaFightDB`;");
		//select db
		mysql_query("use `SeaFightDB`;");
		//create tables
		$queryUsers = "CREATE TABLE `users`(
				`id` INT auto_increment,
				`login` VARCHAR(30) NOT NULL,
				`password` VARCHAR(30) NOT NULL,
				`email` VARCHAR(100) NOT NULL,
				`activ` BOOL NOT NULL,
				PRIMARY KEY(`id`));";
		
		$queryMatches = "CREATE TABLE `matches`(
				`id` INT auto_increment,
				`datePlay` timestamp NOT NULL,
				`idUserPrimary` INT NOT NULL,
				`idUserSecondary` INT NOT NULL,
				`result` INT NOT NULL,
				`activ` BOOL NOT NULL,
				PRIMARY KEY(`id`));";
				
		$queryMatchesDetails = "CREATE TABLE `matchesDetails`(
				`id` INT auto_increment,
				`idMatch` INT NOT NULL,
				`snapshot` VARCHAR(1000) NOT NULL,
				`activ` BOOL NOT NULL,
				PRIMARY KEY(`id`));";
				
		$queryShip = "CREATE TABLE `ships`(
				`id` INT auto_increment,
				`nameShip` VARCHAR(30) not null,
				`classShip` VARCHAR(50) NOT NULL,
				`activ` BOOL NOT NULL,
				PRIMARY KEY(`id`));";
				
		$createTblU = mysql_query($queryUsers);
		if(!$createTblU) throw new Exception('Invalid queryUsers: ' . mysql_error());
		
		$createTblM = mysql_query($queryMatches);
		if(!$createTblM) throw new Exception('Invalid queryMatches: ' . mysql_error());
		
		$createTblMD = mysql_query($queryMatchesDetails);
		if(!$createTblMD) throw new Exception("Invalid queryMatchesDetails: " . mysql_error());
		
		$createTblS = mysql_query($queryShip);
		if(!$createTblS) throw new Exception("Invalid queryShip: " . mysql_error());
			
		echo 'Create all<br>';
	}
	
	//соединение с сервером БД МуСКЛ
	public function __construct(){
		$this->_conn = mysql_connect(self::DB_HOST, self::DB_LOGIN, self::DB_PASSWORD);
		if(!$this->_conn)
			throw new Exception('Ошибка соединения: ' . mysql_error());
		
		$this->_db = mysql_select_db(self::DB_NAME, $this->_conn);
		if(!$this->_db){
			//CreateDBandTables();
			
			//create db 
			$createDB = mysql_query("Create database `SeaFightDB`;");
			if(!$createDB) throw new Exception('Ошибка создания БД: ' . mysql_error());
			//select db
			$useDB = mysql_query("use `SeaFightDB`;");
			if(!$useDB) throw new Exception('Ошибка выбора БД: ' . mysql_error());
			//create tables
			$queryUsers = "CREATE TABLE `users`(
					`id` INT auto_increment,
					`login` VARCHAR(30) NOT NULL,
					`password` VARCHAR(30) NOT NULL,
					`email` VARCHAR(100) NOT NULL,
					`activ` BOOL NOT NULL,
					PRIMARY KEY(`id`));";
			
			$queryMatches = "CREATE TABLE `matches`(
					`id` INT auto_increment,
					`datePlay` timestamp NOT NULL,
					`idUserPrimary` INT NOT NULL,
					`idUserSecondary` INT NOT NULL,
					`result` INT NOT NULL,
					`activ` BOOL NOT NULL,
					PRIMARY KEY(`id`));";
					
			$queryMatchesDetails = "CREATE TABLE `matchesDetails`(
					`id` INT auto_increment,
					`idMatch` INT NOT NULL,
					`snapshot` VARCHAR(1000) NOT NULL,
					`activ` BOOL NOT NULL,
					PRIMARY KEY(`id`));";
					
			$queryShip = "CREATE TABLE `ship`(
					`id` INT auto_increment,
					`nameShip` VARCHAR(30) not null,
					`classShip` VARCHAR(50) NOT NULL,
					`activ` BOOL NOT NULL,
					PRIMARY KEY(`id`));";
					
			$createTblU = mysql_query($queryUsers);
			if(!$createTblU) throw new Exception('Invalid queryUsers: ' . mysql_error());
			
			$createTblM = mysql_query($queryMatches);
			if(!$createTblM) throw new Exception('Invalid queryMatches: ' . mysql_error());
			
			$createTblMD = mysql_query($queryMatchesDetails);
			if(!$createTblMD) throw new Exception("Invalid queryMatchesDetails: " . mysql_error());
			
			$createTblS = mysql_query($queryShip);
			if(!$createTblS) throw new Exception("Invalid queryShip: " . mysql_error());
			
			echo 'Create all<br>';
			}
	}
	
	//закрывает соединение с server
	public function __destruct(){
		mysql_close($this->_conn);
	}
	
	//выбор БД для работы
	public function SelectDB()
	{
		$useDB = mysql_select_db(self::DB_NAME);
		if(!$useDB) throw new Exception('Ошибка выбора БД: ' . mysql_error());
	}
		
	//добавление значений в бд
	public function Insert($table , $data = array())
	{
		$insertItem = mysql_query("INSERT INTO `" . $table . "` (`" . implode("`, `", array_keys($data)) . "`) VALUES('" . implode("', '", array_values($data)) . "');", $this->_conn);
		if(!$insertItem) throw new Exception('Ошибка записи данных в таблицу: ' . mysql_error());
		return mysql_insert_id($this->_conn);
	}
	
	//выборка значений из бд
	public function Select($table, $fields = array(), $where='')
	{
		if(!empty($where)){
			$result = mysql_query("SELECT `" . implode("`, `", $fields) . "` FROM `$table` WHERE `activ`=1 and $where;", $this->_conn);
			if(!$result) throw new Exception('Ошибка выборки данных из таблици: ' . mysql_error());
		}
		else{
			$result = mysql_query("SELECT `" . implode("`, `", $fields) . "` FROM `$table` WHERE `activ`=1;", $this->_conn);
			if(!$result) throw new Exception('Ошибка выборки данных из таблици: ' . mysql_error());
		}
		
		$rows = array();
		while($row = mysql_fetch_assoc($result))
		{
			$rows[] = $row;
		}
		return $rows;
	}
	
	public function SelectOne($table, $fields=array(), $where=''){
		if(!empty($where)){
			$result = mysql_query("SELECT `" . implode("`, `", $fields) . "` FROM `$table` WHERE `activ`=1 and  $where LIMIT 1;", $this->_conn);
			if(!$result) throw new Exception('Ошибка выборки данных из таблици: ' . mysql_error());
		}
		else{
			$result = mysql_query("SELECT `" . implode("`, `", $fields) . "` FROM `$table` WHERE `activ`=1  LIMIT 1;", $this->_conn);
			if(!$result) throw new Exception('Ошибка выборки данных из таблици: ' . mysql_error());
		}
		
		$rows = array();
		while($row = mysql_fetch_assoc($result))
		{
			$rows[] = $row;
		}
		return $rows;
	}
	
	public function Update($table, $id, $data = array()){
		$str = '';
		foreach($data as $k => $v){
			$str .= " `" . $k . "` = '" . $v . "',";
		}
		$str = substr($str, 0, -1);
		$q = "UPDATE $table SET " . $str . " WHERE `id` = " . $id . " LIMIT 1;";
		//var_dump($q);
		$queryUpd = mysql_query($q, $this->_conn);
		if(!$queryUpd) throw new Exception('Ошибка редактирования данных из таблици: ' . mysql_error());
	}
	
	public function Delete($table, $id){
		$queryDel = mysql_query("UPDATE $table SET `activ`=0 WHERE `id` = " . $id . " LIMIT 1;", $this->_conn);
		if(!$queryDel) throw new Exception('Ошибка удаления данных из таблици: ' . mysql_error());
	}
	
		
	public function Execute($qwery){
		$q = mysql_query($qwery, $this->_conn);
		if(!$q) throw new Exception('Ошибка: ' . mysql_error());
		return $q;
	}
}
?>