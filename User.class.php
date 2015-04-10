<?php
include "Verification.class.php";
class User extends Verification{
	private $_idUser;
	private $_login;
	private $_password;
	private $_email;
	private $_activ;
	
	private $_db;
	public function __construct(SeaFightDB $db){
		$this->_db = $db;
	}
	
	public function RegistrUser($login, $password, $email){
		/*$login = $this->_login;
		$password = $this->_password;
		$email = $this->_email;*/
			
		$arr = array();
		$arr['login'] = $login;
		$arr['password'] = $password;
		$arr['email'] = $email;
		$arr['activ'] = true;
		$newId = $this->_db->insert("users" , $arr);
		return $newId;
	}
	
	public function SelectAllUsers($fields, $where=''){
		$result = $this->_db->Select("users", $fields, $where);
		return $result;
	}
	
	public function UpdateUser($id, $data = array()){
		$result = $this->_db->Update("users", $id, $data);
	}
	
	public function DeleteUser($id){
		$result = $this->_db->Delete("users", $id);
	}
	
	public function Logout(){
		unset($_SESSION['id']);
		unset($_SESSION['login']);
		unset($_SESSION['password']);
		session_destroy();
	}

	//функция проверки залогиненого пользователя
	public function LoggedIn(){
		if (!empty($_SESSION['logged_in']))
		{
			return true;
		}
	}
}

?>