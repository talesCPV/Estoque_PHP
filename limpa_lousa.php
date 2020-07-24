<?php
  
  	if (IsSet($_POST ["file"])){
  		$file = $_POST ["file"];
		unlink($file);
		setcookie("message","Lousa Limpa!!!", time()+3600);
	}

	header('Location: main.php');
?>