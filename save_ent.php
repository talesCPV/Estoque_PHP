<?php 
// RECEBENDO OS DADOS PREENCHIDOS DO FORMULÁRIO !
    $nf = $_POST ["nf"];
    $forn = $_POST ["forn"];
    $data_ent = $_POST ["data_ent"];
    $resp = $_POST ["resp"];


	include "conecta_mysql.inc";

	if (!$conexao)
		die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

	$query = "INSERT INTO tb_entrada ( nf, id_emp, data_ent, resp)
			 VALUES ('$nf', '$forn', '$data_ent', '$resp')";   

//	echo $query . "<br>";

	mysqli_query($conexao, $query);

	$query = "SELECT MAX(Id) FROM tb_entrada;";

	$result = mysqli_query($conexao, $query);

	while($fetch = mysqli_fetch_row($result)){

	                $cod_ent = $fetch[0];
	      }

//	echo $query . "<br>";


	setcookie("message", "Entrada de material efetuada com sucesso!", time()+3600);
	setcookie("cod_ent", $cod_ent , time()+3600);

	$conexao->close();


	header('Location: cad_item_ent.php');

?>