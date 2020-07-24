<?php 
// RECEBENDO OS DADOS PREENCHIDOS DO FORMULÁRIO !
$user	= $_POST ["user"];
$pass	= $_POST ["pass"];
$cod_user = $_POST ["cod_user"];

include "conecta_mysql.inc";
if (!$conexao)
	die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

$query = "UPDATE tb_usuario SET pass = \"". $pass ." \" WHERE id = ". $cod_user .";"; 

mysqli_query($conexao, $query);

setcookie("message", "Perfil alterado com sucesso" , time()+3600);
header('Location: main.php');

$conexao->close();

?>