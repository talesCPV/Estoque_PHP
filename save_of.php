<?php 
// RECEBENDO OS DADOS PREENCHIDOS DO FORMUL�RIO !

if (IsSet($_POST ["num_of"])){
    $num_of = $_POST ["num_of"];
    $data_of = $_POST ["data_of"];
    $func  = $_POST ["func"];
    $resp    = $_POST ["responsavel"];
	$tipo    = $_POST ["selTipo"];
    $novo    = $_POST ["novo"];

    include "conecta_mysql.inc";
	if (!$conexao)
		die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());

	if ($novo == 0){ // edit cabeçalho

//		$query = "UPDATE tb_servico SET  id_emp = \"". $cliente ." \", data_ped = \"". $data_ped ." \", data_ent = \"". $data_ent ." \", resp = \"". $resp ." \", comp = \"". $comp ." \",
//			num_ped = \"". $num_ped ." \", desconto = \"". $desconto ." \", cond_pgto = \"". $cond_pgto ." \", obs = \"". $obs ." \", origem = \"". $origem ." \" WHERE id = \"". $id ."\" ;";

//		mysqli_query($conexao, $query);

//        $cod_ped = $id;
	}else{

		$query = "INSERT INTO tb_servico ( num_serv, resp, func, tipo )
             VALUES ('$num_of', '$resp', '$func', '$tipo');";   
             
//echo ($query);
	
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
	setcookie("message", "Houve algum problema na gravacao."   , time()+3600);	
    header('Location: main.php');
}


?>