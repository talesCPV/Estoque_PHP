<?php 
// RECEBENDO OS DADOS PREENCHIDOS DO FORMUL�RIO !
    $num_ped = $_POST ["num_ped"];
    $cliente = $_POST ["cliente"];
    $data_ped = $_POST ["data_ped"];
    $data_ent  = $_POST ["data_ent"];
    $comp  = $_POST ["comprador"];
    $resp    = $_POST ["responsavel"];
    $novo    = $_POST ["novo"];
	$origem    = $_POST ["selOrigem"];



include "conecta_mysql.inc";

if (IsSet($_POST ["num_ped"])){
	$cod_prod = $_POST ["num_ped"];
	if (!$conexao)
		die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());

	if ($novo == 0){
	    $desconto    = $_POST ["desconto"];
	    $cond_pgto    = $_POST ["pgto"];
	    $obs    = $_POST ["obs"];
	    $id    = $_POST ["id_ped"];

		$query = "UPDATE tb_pedido SET  id_emp = \"". $cliente ." \", data_ped = \"". $data_ped ." \", data_ent = \"". $data_ent ." \", resp = \"". $resp ." \", comp = \"". $comp ." \",
			num_ped = \"". $num_ped ." \", desconto = \"". $desconto ." \", cond_pgto = \"". $cond_pgto ." \", obs = \"". $obs ." \", origem = \"". $origem ." \" WHERE id = \"". $id ."\" ;";

		mysqli_query($conexao, $query);

        $cod_ped = $id;
	}else{

		$query = "INSERT INTO tb_pedido ( id_emp, data_ped, data_ent, resp, comp, num_ped, origem)
			 VALUES ('$cliente', '$data_ped', '$data_ent','$resp', '$comp', '$num_ped', '$origem')";   
	
		mysqli_query($conexao, $query);
		$query = "SELECT MAX(Id) FROM tb_pedido;";

		$result = mysqli_query($conexao, $query);

		while($fetch = mysqli_fetch_row($result)){

            $cod_ped = $fetch[0];
        }
	}

// echo $query;


	setcookie("message", "Pedido cadastrado com sucesso!", time()+3600);
	setcookie("cod_ped", $cod_ped , time()+3600);

	$conexao->close();

}else{
	setcookie("message", "Houve algum problema na grava��o."   , time()+3600);	
}

header('Location: cad_item_ped.php');

?>