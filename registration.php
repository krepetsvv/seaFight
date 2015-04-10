<?php
include "User.class.php";
include "SeaFightDB.class.php";

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$sea = new SeaFightDB();
		$user = new User($sea);
				
		if(!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['email'])){
			$login = $user->ClearData($_POST['login'], 's');
			$password = $user->ClearData($_POST['password'], 's');
			$email = $user->ClearData($_POST['email'], 's');			
			$fields = array('id', 'login', 'password', 'email', 'activ');		
			$result = $user->SelectAllUsers($fields, "login='$login'");
			$num_rows = count($result);
			
			if($num_rows != 0){
				echo 'User with this login already exists!';
			}
			else{			
				$user->RegistrUser($login, $password, $email);
				echo 'registered user!';
				header("Location: index.php");
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
		<title>Регистрация пользователя</title>
	</head>
	<body>
		<form method='post' action='registration.php'>
			<b>Логин</b>: 
			<input type='text' name='login' ><br>
			<b>Пароль</b>: 
			<input type='text' name='password' ><br>
			<b>Email</b>: 
			<input type='text' name='email' ><br>
			<input type='submit' value='Зарегистрировать'>		
		</form>
		<br><br>
		<a href='authorization.php'>Authorization</a>
	</body>
</html>