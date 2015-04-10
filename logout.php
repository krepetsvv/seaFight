<?php
include "User.class.php";
include "SeaFightDB.class.php";

session_start();

$sea = new SeaFightDB();
$user = new User($sea);

$user->Logout();
header('Location:index.php');
?>