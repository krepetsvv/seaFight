<?php
include "User.class.php";
include "SeaFightDB.class.php";

session_start();

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$sea = new SeaFightDB();
	$user = new User($sea);	
	
	if(!empty($_POST['login']) && !empty($_POST['password'])){
		$login = $user->ClearData($_POST['login'], 's');
		$password = $user->ClearData($_POST['password'], 's');
		$fields = array('id', 'login', 'password', 'email', 'activ');
		$result = $user->SelectAllUsers($fields, "login='$login'");
		$num_rows = count($result);

		if($num_rows == 0){
			echo 'User with this login don`t exists!';
		}
		else{
			foreach($result as $item){
				if($item['password'] == $password){
					$_SESSION['id'] = $item['id'];
					$_SESSION['login'] = $item['login'];
					$_SESSION['password'] = $item['password'];
					$_SESSION['logged_in'] = true;
					echo 'user is authenticated!';
					header("Location: index.php");
				}
				else{
					echo 'wrong password!';
				}
			}
		}
	}
	else{
		echo 'empty * fields';
	}
}
else{
	echo 'no data';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>Авторизация пользователя</title>
	</head>
	<body>
		<form method='post' action='authorization.php'>
			<b>Логин</b>: 
			<input type='text' name='login' ><br>
			<b>Пароль</b>: 
			<input type='text' name='password' ><br>
			<input type='submit' value='Вход'>		
		</form>
		<br><br>
		<a href='registration.php'>Registration</a>
	</body>
</html>