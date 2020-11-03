<?php
	session_start(); 
	session_destroy();
	unset($_SESSION);
	setcookie("logado");
	header('Location: login.php');
?>
