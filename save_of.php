<?php 
// RECEBENDO OS DADOS PREENCHIDOS DO FORMUL�RIO !

if (IsSet($_POST ["novo"])){
    $novo    = $_POST ["novo"];

    include "conecta_mysql.inc";
	if (!$conexao)
		die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());

	if ($novo == 0){ // edit cabeçalho
		$cod_serv    = $_POST ["cod_serv"];	
		
		$query = "UPDATE tb_produto as p INNER JOIN tb_subconj as s INNER JOIN tb_item_serv as se
			SET p.estoque = (p.estoque - (s.qtd * se.qtd))
			WHERE p.id = s.id_peca and s.id_conj = se.id_item and se.id_serv = {$cod_serv};";   
			
   		mysqli_query($conexao, $query);

		$query = "UPDATE tb_produto as p INNER JOIN tb_item_serv as i INNER JOIN tb_servico as s 
				SET p.estoque = (p.estoque + i.qtd), s.status = 'FECHADO' 
				WHERE p.id = i.id_item AND i.id_serv =  s.id AND s.id = {$cod_serv};";
		mysqli_query($conexao, $query);

	}else if ($novo == 1){

		$num_of = $_POST ["num_of"];
		$data_of = $_POST ["data_of"];
		$func  = $_POST ["func"];
		$resp    = $_POST ["responsavel"];
		$tipo    = $_POST ["selTipo"];		

		$query = "INSERT INTO tb_servico ( num_serv, resp, func, tipo )
             VALUES ('$num_of', '$resp', '$func', '$tipo');";   
             	
		mysqli_query($conexao, $query);
		$query = "SELECT MAX(Id) FROM tb_servico;";

		$result = mysqli_query($conexao, $query);

		while($fetch = mysqli_fetch_row($result)){

            $cod_serv = $fetch[0];
        }
	}

// echo $query;


	setcookie("message", "Pedido cadastrado com sucesso!", time()+3600);
	setcookie("cod_serv", $cod_serv , time()+3600);

    $conexao->close();

    header('Location: cad_item_of.php');
    

}else{
	setcookie("message", "Você chegou até aqui por meios não oficiais."   , time()+3600);	
    header('Location: main.php');
}


?>