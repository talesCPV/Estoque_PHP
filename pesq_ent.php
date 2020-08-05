<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>NFs de Compra</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/funcoes.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Pesquisa por NFs de Compra</p> <br>
  	  	<form class="login-form" method="POST" action="#">
  	  		<table class="search-table"  border="0"><tr><td>
  		  <label> Busca por: </label> </td><td>
	      <select name="campo">
	        <option value="todos">Todos</option>
	        <option value="cod">Cod.</option>
	        <option value="forn">Fornecedor</option>
	        <option value="nf">Num. NF</option>
	        <option value="nome_prod">Nome do Produto</option>
	        <option value="cod_int_prod">Cod. Interno Prod.</option>
	        <option value="cod_prod">Cod. Produto.</option>
	        <option value="aberta">Aberta</option>
	    </select></td><td>
      <input type="text" name="valor" maxlength="12"/></td><td>
	  <button class="botao_inline" type="submit">OK</button></td></tr>  </table>
		<label for="ckbDatas">Início / Final</label>			
		<table class="search-table"  border="0"><tr>
			<td> <input type="date" name="data_ini" id="selData_ini" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date('m') , 1 , date('Y'))); ?>"> </td>
			<td> <input type="date" name="data_fin" id="selData_fin" value="<?php echo date('Y-m-d',mktime(23, 59, 59, date('m'), date("t"), date('Y'))); ?>"> </td></tr>
		</table>
    	</form>
	  </div>

	  <?php

	  		$qtd_lin = 0;
		    if (IsSet($_POST ["campo"])){

				$dat_ini = $_POST ["data_ini"];
				$dat_fin = $_POST ["data_fin"];		

				include "conecta_mysql.inc";
				if (!$conexao)
					die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

			  	$campo = $_POST ["campo"];
				  $valor = $_POST ["valor"];
				  
			  	if ($campo == "todos"){
	              $query =  "SELECT en.id, en.nf, e.nome, en.data_ent, en.status, en.resp, en.path, e.id
	                         FROM tb_entrada AS en INNER JOIN tb_empresa AS e 
	                         ON en.id_emp = e.id AND data_ent >= '". $dat_ini ."' AND data_ent <= '". $dat_fin ."' order by en.data_ent desc;";
			  	}
			  	else
			  	if ($campo == "cod"){
	              $query =  "SELECT en.id, en.nf, e.nome, en.data_ent, en.status, en.resp, en.path, e.id
	                         FROM tb_entrada AS en INNER JOIN tb_empresa AS e 
	                         ON en.id_emp = e.id
	                         AND en.id = '".$valor."' ;";
			  	}
			  	else
			  	if ($campo == "forn"){
	              $query =  "SELECT en.id, en.nf, e.nome, en.data_ent, en.status, en.resp, en.path, e.id
	                         FROM tb_entrada AS en INNER JOIN tb_empresa AS e 
	                         ON en.id_emp = e.id
	                         AND e.nome LIKE '%".$valor."%' AND data_ent >= '". $dat_ini ."' AND data_ent <= '". $dat_fin ."';";

			  	}
			  	else
			  	if ($campo == "nf"){
	              $query =  "SELECT en.id, en.nf, e.nome, en.data_ent, en.status, en.resp, en.path, e.id
	                         FROM tb_entrada AS en INNER JOIN tb_empresa AS e 
	                         ON en.id_emp = e.id
	                         AND en.nf = '".$valor."' ;";
			  	}
			  	else
			  	if ($campo == "nome_prod"){
					$query =  "SELECT en.id, en.nf, e.nome, en.data_ent, en.status, en.resp, en.path, e.id
							  FROM tb_entrada AS en 
							  INNER JOIN tb_empresa AS e 
							  INNER JOIN tb_item_compra AS i
							  INNER JOIN tb_produto AS p
							  ON en.id_emp = e.id 
							  AND en.id = i.id_ent
							  AND i.id_prod = p.id
							  AND p.descricao LIKE '%".$valor."%' ;";
					}
					else
			  	if ($campo == "cod_int_prod"){
	              $query =  "SELECT en.id, en.nf, e.nome, en.data_ent, en.status, en.resp, en.path, e.id
							FROM tb_entrada AS en 
							INNER JOIN tb_empresa AS e 
							INNER JOIN tb_item_compra AS i
							INNER JOIN tb_produto AS p
							ON en.id_emp = e.id 
							AND en.id = i.id_ent
							AND i.id_prod = p.id
							AND p.cod = '".$valor."' ;";
			  	}
			  	else
			  	if ($campo == "cod_prod"){
					$query =  "SELECT en.id, en.nf, e.nome, en.data_ent, en.status, en.resp, en.path, e.id
							  FROM tb_entrada AS en 
							  INNER JOIN tb_empresa AS e 
							  INNER JOIN tb_item_compra AS i
							  INNER JOIN tb_produto AS p
							  ON en.id_emp = e.id 
							  AND en.id = i.id_ent
							  AND i.id_prod = p.id
							  AND p.cod_bar LIKE '%".$valor."%' ;";
				}
			  	else
			  	if ($campo == "aberta"){
	              $query =  "SELECT en.id, en.nf, e.nome, en.data_ent, en.status, en.resp, en.path, e.id
	                         FROM tb_entrada AS en INNER JOIN tb_empresa AS e 
	                         ON en.id_emp = e.id
	                         AND en.status = 'ABERTO' ;";
			  	}

//			  	echo $query;

			  	$result = mysqli_query($conexao, $query);

			  	$qtd_lin = $result->num_rows;



				echo"  <div class=\"page_form\" id=\"no_margin\">
						<table class=\"search-table\" id=\"tabItens\" >
			                <tr>
			                  <th>Cod.</th>
			                  <th>NF</th>
			                  <th>Fornecedor</th>
			                  <th>Data</th>
			                  <th>Status</th>
			                  <th>Resp.</th>
			                  <th>NF</th>
						  	</tr>";
					        while($fetch = mysqli_fetch_row($result)){

					        	$cod_ent = $fetch[0];
					        	$status = $fetch[4];	

					            echo "<tr class='tbl_row' id='".$fetch[0]."'><td>" .$fetch[0] . "</td>".
								     	 "<td>" .$fetch[1] . "</td>".
								     	 "<td>" .$fetch[2] . "</td>".
								         "<td>" . date('d/m/Y', strtotime($fetch[3])) . "</td>".
								     	 "<td>" .$fetch[4] . "</td>".
										 "<td>" .$fetch[5] . "</td>";
										 if($fetch[6] == null){ // Se não existe NF em PDF
											echo "<td></td>";
										 }else{
											echo "<td>@</td>";
										 }

								echo	 "<td style='display: none;'>" .$fetch[6] . "</td>".
										 "<td style='display: none;'>" .$fetch[7] . "</td>".
									 "</tr>";
					        }



						    echo"
						</table> 

				  </div>
				  ";
				$conexao->close();

		    }	    

	  ?>
  	  
  </div>

<div class="overlay">	
  <div class="popup">
    <h2 id="popTitle"></h2>
    <div class="close" >&times</div>
    <div class="content"></div>
  </div>
</div>

</body>
</html>