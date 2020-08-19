<?php 
	// RECEBENDO OS DADOS PREENCHIDOS DO FORMUL�RIO !
	$nome	= strtoupper($_POST ["nome"]);
	$forn	= $_POST ["forn"];
	$estoque	= $_POST ["estoque"];
	$est_min	= $_POST ["est_min"];
	$unidade    = strtoupper($_POST ["und"]);
	$cod_bar  	= strtoupper($_POST ["cod_bar"]);
	$cod_cli  	= strtoupper($_POST ["cod_cli"]);
	$ncm  	= $_POST ["ncm"];
	$compra  	= $_POST ["compra"];
	$margem  	= $_POST ["margem"];
	$tipo  	= strtoupper($_POST ["tipo"]);
	// SELECT cod FROM tb_produto WHERE tipo NOT LIKE '%TINTA%' order by cod desc limit 1;

	include "conecta_mysql.inc";
	if (!$conexao)
		die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());

		$query = "SELECT cod FROM tb_produto WHERE tipo NOT LIKE '%TINTA%' order by cod desc limit 1;";
		$result = mysqli_query($conexao, $query);
		while($fetch = mysqli_fetch_row($result)){
			$new_cod = $fetch[0] + 1 ;
		}

		echo($new_cod."<br>");

	if (IsSet($_COOKIE["classe"])){
		$classe = $_COOKIE["classe"];
	}


	if (IsSet($_POST ["cod_prod"])){
		$cod_prod = $_POST ["cod_prod"];
		$cod = $_POST ["cod"];

		$query = "UPDATE tb_produto SET  descricao = \"". $nome ." \", estoque = \"". $estoque ." \", etq_min = \"". $est_min ." \", unidade = \"". $unidade ." \", cod = \"". $cod ." \",
				cod_bar = \"". $cod_bar ." \", id_emp = \"". $forn ." \", ncm = \"". $ncm ." \", preco_comp = \"". $compra ." \", margem = \"". $margem ." \", tipo = \"". $tipo ." \", cod_cli = \"". $cod_cli ." \"  WHERE id = \"". $cod_prod ."\" ;";
		mysqli_query($conexao, $query);
		$query = "SET SQL_SAFE_UPDATES = 0;";
		mysqli_query($conexao, $query);
		$query = " UPDATE tb_produto as pd 
			INNER JOIN
			(SELECT item.id_conj, sum(p.preco_comp * s.qtd) as custo
			FROM (SELECT id_conj FROM tb_subconj where id_peca = {$cod_prod}) as item 
			inner join tb_subconj as s 
			inner join tb_produto as p 
			on  p.id = s.id_peca 
			and s.id_conj = item.id_conj
			group by item.id_conj
			) as val 
			SET pd.preco_comp = val.custo
			WHERE pd.id = val.id_conj;";
	//		echo $query;

	}else{

		$query = "INSERT INTO tb_produto ( descricao, estoque, etq_min, unidade, cod, cod_bar, id_emp, ncm, preco_comp, margem, tipo, cod_cli) 
		VALUES ('$nome', '$estoque', '$est_min','$unidade', '$new_cod', '$cod_bar', '$forn', '$ncm', '$compra', '$margem', '$tipo', '$cod_cli')";
	}

//	echo $query;

	mysqli_query($conexao, $query);
	$conexao->close();

	setcookie("message", "Novo produto cadastrado com sucesso!", time()+3600);
	//setcookie("message", $query, time()+3600);

	header('Location: pesq_prod.php');


	//header('Location: ' . $_SERVER['HTTP_REFERER']);

?>