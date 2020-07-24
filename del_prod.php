<?php 
	if (IsSet($_COOKIE["classe"])){
		$classe = $_COOKIE["classe"];
	}

	if ($classe == "10" or $classe == "4"){

		if (IsSet($_POST ["cod_prod"])){
			$cod_prod  = $_POST ["cod_prod"];


			include "conecta_mysql.inc";
			if (!$conexao)
				die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());

				$query = "DELETE FROM tb_produto  WHERE id = \"". $cod_prod ."\" ;";

			mysqli_query($conexao, $query);
			$conexao->close();

			setcookie("message", "Produto deletado com sucesso!", time()+3600);
			//setcookie("message", $query, time()+3600);

		}else{
			setcookie("message", "Houve algum problema no processo, registro nao deletado", time()+3600);	
		}
		
	}
	header('Location: main.php');

?>