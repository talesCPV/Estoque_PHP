<?php 
// RECEBENDO OS DADOS PREENCHIDOS DO FORMUL�RIO !
$user	= $_POST ["user"];
$pass	= $_POST ["pass"];
$classe	= $_POST ["classe"];

include "conecta_mysql.inc";
if (!$conexao)
	die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());


if (IsSet($_POST ["cod_user"])){
	$cod_user = $_POST ["cod_user"];
	$nome = $_POST ["nome"];
	$email = $_POST ["email"];
	$email_pass = $_POST ["emailpass"];
	$tel = $_POST ["tel"];	

	$query = "UPDATE tb_usuario SET  pass = \"". $pass ." \", nome = \"". $nome ." \", email = \"". $email ." \", cel = \"". $tel ." \", mail_pass = \"". $email_pass ." \"   WHERE id = \"". $cod_user ."\" ;";
}else{

	$query = "INSERT INTO tb_usuario ( user, pass, class) VALUES ('$user', '$pass', '$classe')";
}

mysqli_query($conexao, $query);
$conexao->close();

setcookie("message", "Usu�rio cadastrado com sucesso", time()+3600);
header('Location: main.php');
?>