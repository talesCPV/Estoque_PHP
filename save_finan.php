<?php 
// RECEBENDO OS DADOS PREENCHIDOS DO FORMUL�RIO !
$user	= $_POST ["user"];
$pass	= $_POST ["pass"];
$classe	= $_POST ["classe"];

include "conecta_mysql.inc";
if (!$conexao)
	die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());

         
$tipo = $_POST ["tipo"];
$ref = $_POST ["ref"];
$dest = $_POST ["dest"];
$valor = $_POST ["valor"];
$venc = $_POST ["data_venc"];
$orig = $_POST ["origem"];
$pgt = $_POST ["selPgt"];


if (IsSet($_POST ["hidDel"])){
	$id = $_POST ["id"];
	$link = 'pesq_anafin.php';
	if($_POST ["hidDel"] == '1'){
		$query = "DELETE FROM tb_financeiro WHERE id = '$id';";   
	}else{
		$query = "UPDATE tb_financeiro SET  tipo='$tipo', ref='$ref' , emp='$dest', preco='$valor', data_pg='$venc', origem='$orig', pgto='$pgt' WHERE id = '$id';";   
	}
}else{
	$link = 'cad_lanc.php';
	$lanc = date('Y-m-d');
	$resp = $_POST ["resp"];
	$query = "INSERT INTO tb_financeiro ( tipo, ref, emp, preco, data_pg, data_ini, resp, origem, pgto) VALUES ('$tipo', '$ref', '$dest', '$valor','$venc', '$lanc' ,'$resp','$orig','$pgt');";   
}

mysqli_query($conexao, $query);
$conexao->close();            

//echo($query);

header('Location: '.$link);
?>

