<?php 
// RECEBENDO OS DADOS PREENCHIDOS DO FORMUL�RIO !
$nome	= $_POST ["nome"];
$emp	= $_POST ["emp"];
$dep	= $_POST ["dep"];
$email	= $_POST ["email"];
$cel	= $_POST ["fone1"];
$fone	= $_POST ["fone2"];
$dest = "main.php";

include "conecta_mysql.inc";
if (!$conexao)
	die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());


	if (IsSet($_POST ["hidDel"])){
		$id = $_POST ["id"];
		$dest	= "pesq_agenda.php";

		$link = 'pesq_anafin.php';

		if($_POST ["hidDel"] == '1'){
			$query = "DELETE FROM tb_agenda WHERE id = '$id';";   
		}else if(($_POST ["hidDel"] == '2')){
			$query = "UPDATE tb_agenda SET  nome='$nome', email='$email' , cel1='$cel', cel2='$fone', depart='$dep', id_emp='$emp' WHERE id = '$id';";   
		}else {
			$query = "INSERT INTO tb_agenda ( nome, email, cel1, cel2, id_emp, depart) VALUES ('$nome', '$email', '$cel', '$fone', '$emp', '$dep')";
		}

	}else{
		$forn	= $_POST ["forn"];	
		$query = "INSERT INTO tb_agenda ( nome, email, cel1, cel2, id_emp, depart) VALUES ('$nome', '$email', '$cel', '$fone', '$forn', '$dep')";
		
	}	

	mysqli_query($conexao, $query);
	$conexao->close();
	setcookie("message", "Contato cadastrado com sucesso!", time()+3600);
	header('Location:'.$dest);

//echo ($query);

?>