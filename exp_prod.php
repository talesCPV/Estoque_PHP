<?php
    include "conecta_mysql.inc";
    if (!$conexao)
    	die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

    $query = "SELECT cod, descricao, ncm, unidade, preco_comp, margem from tb_produto";
    $result = mysqli_query($conexao, $query);
    $qtd_itens = $result->num_rows;
	$fp = fopen("backup/PRODUTO.txt", "w");
    $texto = "PRODUTO|".$qtd_itens."\r\n";
	fwrite($fp, $texto);
    while($fetch = mysqli_fetch_row($result)){
    	$preco =  number_format(($fetch[4]*(1+ $fetch[5]/100)), 4, '.', '');
    	$texto = "A|1.02\r\nI|".trim($fetch[0])."|".trim($fetch[1])."||".trim($fetch[2])."|||".trim($fetch[3])."|".$preco."||".trim($fetch[3])."|".$preco."||\r\nM|0|0\r\n";
		fwrite($fp, $texto);
    }
	fclose($fp);
    $conexao->close();
	
	function download($arquivo){
    	header("Content-Type: application/force-download");
      	header("Content-Type: application/octet-stream;");
      	header("Content-Length:".filesize($arquivo));
      	header("Content-disposition: attachment; filename=".$arquivo);
      	header("Pragma: no-cache");
      	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
      	header("Expires: 0");
      	readfile($arquivo);
      	flush();
	}

    download("backup/PRODUTO.txt");
//	header('Location: '."backup/PRODUTO.txt");
?>