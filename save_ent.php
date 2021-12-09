<?php 
// RECEBENDO OS DADOS PREENCHIDOS DO FORMUL�RIO !
    $nf = $_POST ["nf"];
    $forn = $_POST ["forn"];
    $data_ent = $_POST ["data_ent"];
    $resp = $_POST ["resp"];
    $obs = $_POST ["obs"];


	include "conecta_mysql.inc";

	if (!$conexao)
		die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());

	$query = "INSERT INTO tb_entrada ( nf, id_emp, data_ent, resp, OBS)
			 VALUES ('$nf', '$forn', '$data_ent', '$resp', '$obs')";   

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