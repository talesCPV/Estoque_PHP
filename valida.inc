<?php
	if (!IsSet($_COOKIE["logado"])){
		header('Location: login.php');
	}
?>