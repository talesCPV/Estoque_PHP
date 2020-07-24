<?php 
	if (IsSet($_POST ["cod_ent"])){
		$cod_ent  = $_POST ["cod_ent"];
		$status  = $_POST ["status"];



		if ($status == "ABERTO"){

			include "conecta_mysql.inc";
			if (!$conexao)
				die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

			$query = "DELETE FROM tb_item_compra  WHERE id_ent = \"". $cod_ent ."\" ;";
			mysqli_query($conexao, $query);

			$query = "DELETE FROM tb_entrada  WHERE id = \"". $cod_ent ."\" ;";
			mysqli_query($conexao, $query);

			setcookie("message", "NF e sub itens deletada com sucesso!", time()+3600);
			$conexao->close();
		}else{
			setcookie("message", "NF com status 'Fechado' nao pode ser deletada do sistema.", time()+3600);			
		}

	}else{
		setcookie("message", "Houve algum problema no processo, registro nao deletado", time()+3600);	
	}

	header('Location: main.php');

?>