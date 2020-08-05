<?php 

	include "conecta_mysql.inc";

	if (!$conexao)
		die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());

	$destino = 'cad_item_ent.php';

	function get_post_action($name)
	{
	    $params = func_get_args();

	    foreach ($params as $name) {
	        if (isset($_POST[$name])) {
	            return $name;
	        }
	    }
	}


	if (IsSet($_COOKIE["cod_ent"])){
		$cod_ent = $_COOKIE["cod_ent"];
	}

	switch (get_post_action('adicionar', 'alterar', 'remover', 'encerrar')) {
	    case 'adicionar':
		    $cod_prod = $_POST ["cod_prod"];
			$ipi = $_POST ["ipi"];
			$icms = $_POST ["icms"];
			$mva = $_POST ["mva"];
			$preco = $_POST ["preco"];
			if(trim($icms) == ''){
				$icms = 0;
			}
			if(trim($mva) == ''){
				$mva = 0;
			}
			if(trim($preco) == ''){
				$preco = 0;
			}
			if(trim($ipi) == ''){
				$ipi = 0;
			}

			$val_icms = ($icms/100)*$preco;
			$val_ipi = ($ipi/100)*$preco;
			$base_mva = ($preco + $val_ipi) * (1+$mva/100);
			$val_icms_st = ($icms/100)*$base_mva;
			$preco_custo =  number_format($preco + ($val_icms_st - $val_icms) + $val_ipi, 2, '.', '');
			
//		    echo ($preco);
			$qtd = $_POST ["qtd"];
			if(trim($qtd) == ''){
				$qtd = 1;
			}
			
			$query = "INSERT INTO tb_item_compra ( id_prod, id_ent, qtd, preco) VALUES ('$cod_prod', '$cod_ent', '$qtd', '$preco_custo')";   
	        break;

	    case 'alterar':
		    $cod_prod = $_POST ["cod_prod"];
		    $qtd = $_POST ["qtd"];
		    $preco = $_POST ["preco"];

			$query = "UPDATE tb_item_compra i
					  INNER JOIN tb_produto p
					  SET qtd = '$qtd', preco = '$preco'
					  WHERE i.id_ent = '$cod_ent'
					  AND p.cod = '$cod_prod'
					  AND p.id = i.id_prod;";
//				echo $query ."<br>";

	        break;

	    case 'remover':
		    $cod_item = $_POST ["cod_prod"];
			$query = "DELETE i
					  FROM tb_item_compra i
					  INNER JOIN tb_produto p
					  ON p.id = i.id_prod
					  WHERE p.cod = '$cod_item' AND i.id_ent = '$cod_ent';";

//				echo $query ."<br>";

	        break;

	    case 'encerrar':
			$query = "SELECT id_prod, qtd, preco from tb_item_compra where id_ent = '$cod_ent';";		
//				echo $query ."<br>";

	          $result = mysqli_query($conexao, $query);

	          while($fetch = mysqli_fetch_row($result)){
	        	$id_prod = $fetch[0];
	        	$qtd = $fetch[1];
	        	$preco = $fetch[2];
				$query = "UPDATE tb_produto
						  SET estoque = estoque + '$qtd', preco_comp = '$preco'
						  WHERE id = '$id_prod';";						  
				mysqli_query($conexao, $query);

				$query = "SET SQL_SAFE_UPDATES = 0;";
				mysqli_query($conexao, $query);
				$query = " UPDATE tb_produto as pd 
					INNER JOIN
					(SELECT item.id_conj, sum(p.preco_comp * s.qtd) as custo
					FROM (SELECT id_conj FROM tb_subconj where id_peca = {$id_prod}) as item 
					inner join tb_subconj as s 
					inner join tb_produto as p 
					on  p.id = s.id_peca 
					and s.id_conj = item.id_conj
					group by item.id_conj
					) as val 
					SET pd.preco_comp = val.custo
					WHERE pd.id = val.id_conj;";
					mysqli_query($conexao, $query);				
				

				
//				echo $query ."<br>";

	        }

			$query = "UPDATE tb_entrada
					  SET status = 'FECHADO'
					  WHERE id = '$cod_ent';";
//			echo "<br>".$query ."<br>";


			$destino = 'main.php';
			setcookie("message", "NF entrada com sucesso!", time()+3600);
	        break;
	    default:
	        //no action sent
		}
		mysqli_query($conexao, $query);
		$conexao->close();
		header('Location: '. $destino);
?>