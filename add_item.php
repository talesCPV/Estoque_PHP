<?php 

include "conecta_mysql.inc";

if (!$conexao)
	die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

$destino = 'cad_item_ped.php';

function get_post_action($name)
{
    $params = func_get_args();

    foreach ($params as $name) {
        if (isset($_POST[$name])) {
            return $name;
        }
    }
}


if (IsSet($_POST ["cod_ped"])){
    $cod_ped = $_POST ["cod_ped"];
}

switch (get_post_action('adicionar', 'alterar', 'remover', 'encerrar')) {
    case 'adicionar':
    	$und = $_POST ["und"];
    	$tipo = $_POST ["tipo"];
	    $vol = $_POST ["vol"];
    	if (trim($tipo) == "TINTA"){
    		$und = trim($vol);
    		if($und == '0.5'){
    			$und = '450ml';
    		}
    		if($und == "1"){
    			$und = '900ml';
    		}
    		if($und == '2'){
    			$und = '1.8L';
    		}
    		if($und == '3'){
    			$und = '2.7L';
    		}
    		if($und == '4'){
    			$und = '3.6L';
    		}

    	}
	    $preco = $_POST ["preco"] * $vol;
    	$cod_prod = $_POST ["cod_prod"];
	    $qtd = $_POST ["qtd"];
		$query = "INSERT INTO tb_item_ped ( id_prod, id_ped, qtd, preco, und) VALUES ('$cod_prod', '$cod_ped', '$qtd', $preco, '$und')";   
        break;

    case 'alterar':
	    $cod_prod = $_POST ["cod_prod"];
	    $qtd = $_POST ["qtd"];
	    $preco = $_POST ["preco"];

		$query = "UPDATE tb_item_ped i
				  INNER JOIN tb_produto p
				  SET qtd = '$qtd', preco = '$preco'
				  WHERE i.id_ped = '$cod_ped'
				  AND p.cod = '$cod_prod'
				  AND p.id = i.id_prod;";
        break;

    case 'remover':
	    $cod_item = $_POST ["cod_prod"];
		$query = "DELETE i
				  FROM tb_item_ped i
				  INNER JOIN tb_produto p
				  ON p.id = i.id_prod
				  WHERE p.cod = '$cod_item' AND i.id_ped = '$cod_ped';";
        break;

    case 'encerrar':
		$query = "SELECT id_prod, qtd from tb_item_ped where id_ped = '$cod_ped';";		

          $result = mysqli_query($conexao, $query);

          while($fetch = mysqli_fetch_row($result)){
        	$id_prod = $fetch[0];
        	$qtd = $fetch[1];
			$query = "UPDATE tb_produto
					  SET estoque = estoque - '$qtd'
					  WHERE id = '$id_prod';";
			mysqli_query($conexao, $query);

        }
       	$pedido = $_POST ["pedido"];
		$query = "UPDATE tb_pedido
				  SET status = 'FECHADO', num_ped = '$pedido'
				  WHERE id = '$cod_ped';";

		$destino = 'main.php';
		setcookie("message", "Pedido encerrado com sucesso!", time()+3600);
        break;

    default:
        //no action sent
	}
	mysqli_query($conexao, $query);
	$conexao->close();
	header('Location: '. $destino);
?>