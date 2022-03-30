<?php
	
	$usuario = "flexib52_tales";
	$senha= "Xspider0";
	$servidor = "108.179.253.230";
	$banco = "flexib52_db_estoque";

	$conexao = new mysqli($servidor, $usuario, $senha, $banco);

	if ($conexao->connect_error) {
		echo "Connected fail";
		die("Connection failed: " . $conn->connect_error);
	}

?>
