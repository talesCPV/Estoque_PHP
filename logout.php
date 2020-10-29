<?php 
	session_destroy();
	setcookie("logado");
	header('Location: login.php');
?>
