<?php 
	if (IsSet($_POST ["cod_ped"])){
		$cod_ped  = $_POST ["cod_ped"];
		$status  = $_POST ["status"];



		if ($status == "COT"){

			include "conecta_mysql.inc";
			if (!$conexao)
				die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());

			$query = "DELETE FROM tb_item_ped  WHERE id_ped = \"". $cod_ped ."\" ;";
			mysqli_query($conexao, $query);

			$query = "DELETE FROM tb_pedido  WHERE id = \"". $cod_ped ."\" ;";
			mysqli_query($conexao, $query);

			setcookie("message", "Pedido e sub itens deletado com sucesso!", time()+3600);
			$conexao->close();
		}else{
			setcookie("message", "Pedido com status fechado nao pode ser deletado do sistema.", time()+3600);			
		}

//		setcookie("message", $query, time()+3600);

	}else{
		setcookie("message", "Houve algum problema no processo, registro nao deletado", time()+3600);	
	}

	header('Location: main.php');

?>