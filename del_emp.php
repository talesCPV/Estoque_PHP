<?php 

//Gravando no banco de dados !

if (IsSet($_POST ["cod_emp"])){
	$cod_emp  = $_POST ["cod_emp"];

	include "conecta_mysql.inc";
	if (!$conexao)
		die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

	$query = "SELECT * FROM tb_produto  WHERE id_emp = \"". $cod_emp ."\" ;";

    $result = mysqli_query($conexao, $query);
  	$qtd_lin = $result->num_rows;
  	
//  	echo $qtd_lin ."<- <br>";
  	
  	if ($qtd_lin > 0){
  		setcookie("message", "Não foi possível deletar, existem produtos cadastrados para esta empresa.", time()+3600);
  	}else{
		$query = "SELECT * FROM tb_agenda  WHERE id_emp = \"". $cod_emp ."\" ;";

	    $result = mysqli_query($conexao, $query);
	  	$qtd_lin = $result->num_rows;


	  	if ($qtd_lin > 0){
	  		setcookie("message", "Não foi possível deletar, existem contatos cadastrados na agenda para esta empresa.", time()+3600);
	  	}else{ 
			$query = "DELETE FROM tb_empresa  WHERE id = \"". $cod_emp ."\" ;";
			mysqli_query($conexao, $query);			
			setcookie("message", "Empresa deletada com sucesso!", time()+3600);
		}
		
	}   

	$conexao->close();

	//setcookie("message", $query, time()+3600);

}else{
	setcookie("message", "Houve algum problema no processo,registro nao deletado" .$cod_emp , time()+3600);	
}

header('Location: main.php');
?>