<?php 

//Gravando no banco de dados !

if (IsSet($_POST ["cod_agenda"])){
	$cod_agenda  = $_POST ["cod_agenda"];

	include "conecta_mysql.inc";
	if (!$conexao)
		die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

	$query = "DELETE FROM tb_agenda  WHERE id = \"". $cod_agenda ."\" ;";

	mysqli_query($conexao, $query);
	
	setcookie("message", "Contato deletado com sucesso!", time()+3600);

	$conexao->close();

}else{
	setcookie("message", "Houve algum problema no processo,registro nao deletado" .$cod_emp , time()+3600);	
}

header('Location: main.php');


?>