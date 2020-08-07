<?php 

function clear_num($num){
	$out = "";
	$perm = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0"); 
	for($i=0;$i<strlen($num);$i++){
		if (in_array($num[$i], $perm)) { 
			$out = $out . $num[$i];
		}        
	}
	return $out;
}

// RECEBENDO OS DADOS PREENCHIDOS DO FORMUL�RIO !
$nome	= strtoupper($_POST ["nome"]);
$endereco	= strtoupper($_POST ["endereco"]);
$cidade	= strtoupper($_POST ["cidade"]);
$estado	= $_POST ["estado"];
$cep    = $_POST ["cep"];
$cnpj	= clear_num($_POST ["cnpj"]);
$ie  	= clear_num($_POST ["ie"]);
$tipo  	= $_POST ["tipo"];
$fone  	= $_POST ["fone"];
$bairro = strtoupper($_POST ["bairro"]);
$num  	= $_POST ["num"];


//Gravando no banco de dados !
include "conecta_mysql.inc";
if (!$conexao)
	die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());

if (IsSet($_POST ["cod_emp"])){
	$cod_emp  = $_POST ["cod_emp"];

	$query = "UPDATE tb_empresa SET  nome = \"". $nome ." \", endereco = \"". $endereco ." \", cidade = \"". $cidade ." \", estado = \"". $estado ." \", cep = \"". $cep ." \",
			cnpj = \"". $cnpj ." \", ie = \"". $ie ." \", tipo = \"". $tipo ." \", tel = \"". $fone ." \", bairro = \"". $bairro ." \", num = \"". $num ." \" WHERE id = \"". $cod_emp ."\" ;";
}else{

	$query = "INSERT INTO tb_empresa ( nome, endereco, cidade, estado, cep, cnpj, ie, tipo, tel, bairro, num) 
	VALUES ('$nome', '$endereco', '$cidade','$estado', '$cep', '$cnpj', '$ie','$tipo','$fone','$bairro','$num')";

}

mysqli_query($conexao, $query);
$conexao->close();

setcookie("message", "Nova empresa cadastrada com sucesso!", time()+3600);
//setcookie("message", $query, time()+3600);
header('Location: main.php');
//echo ($query);

?>