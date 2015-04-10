<?php 
include "User.class.php";
include "SeaFightDB.class.php";

session_start();

$sea = new SeaFightDB();
$user = new User($sea);

if ($user->LoggedIn())
{
	$login = $user->ClearData($_SESSION['login'], 's');
	echo "<br/>HELLO " . $login . "! <p>here is my super content</p><p><a href='logout.php'>LOGOUT</a></p>"; 
}
else
{
	$login = '';
	echo "<br/>You are not logged in. <a href='authorization.php'>Click here</a> to log in.Or registry - <a href='registration.php'>Click here</a>";
}
?>