<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Pedido de Compra</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/pesq_ped.js"></script>
	<script src="js/edt_mask.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Pedidos e Cotações</p> <br>
  	  	<form class="login-form" method="POST" action="#">
  	  		<table class="search-table"  border="0"><tr><td>
  		  <label> Busca por: </label> </td><td>
	      <select name="campo" id="selPesqPed">
	        <option value="todos">Todos</option>
	        <option value="sanf">Sanfonados</option>
	        <option value="funi">Fun. e Pintura</option>
	        <option value="aberto">Cotacoes</option>
	        <option value="fechado">Pedidos</option>
	        <option value="faturado">Faturados</option>
	        <option value="interno">Uso Interno</option>
	        <option value="cod">Codigo</option>
	        <option value="num_ped">Numero</option>
	        <option value="cod_prod">Cod. Produto</option>
	        <option value="cliente">Cliente</option>
	        <option value="vendtotal">Vendedor Total</option>
	        <option value="vendfechado">Vendedor Fechados</option>
	    </select></td><td>
      <input type="text" name="valor" maxlength="12"/></td><td>
	  <button class="botao_inline" type="submit">OK</button></td></tr>  </table>

		<input type="checkbox" id="ckbDatas" name="ckbDatas" checked>
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

				include "conecta_mysql.inc";
				if (!$conexao)
					die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

			  	$campo = $_POST ["campo"];
				$valor = $_POST ["valor"];
				$dat_ini = $_POST ["data_ini"];
				$dat_fin = $_POST ["data_fin"];				
				$on = 0;

				if (IsSet($_POST ["ckbDatas"])){
					$on = 1;
				}
  
				if ($campo == "todos"){

	            	$query =  "SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path
					FROM tb_pedido AS p 
					INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i
					ON p.id = i.id_ped
					INNER JOIN tb_empresa AS e 
					ON p.id_emp = e.id";
					if($on){
						$query = $query . " AND p.data_ped >= '". $dat_ini ."'
						AND p.data_ped <= '". $dat_fin ."'";
					}				  
				$query = $query . " ORDER BY p.data_ped DESC;";

			  	}
			  	else
				  if ($campo == "sanf"){

	            	$query =  "SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path
					FROM tb_pedido AS p 
					INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i
					ON p.id = i.id_ped
					INNER JOIN tb_empresa AS e 
					ON p.id_emp = e.id AND p.origem = 'SAN'";
					if($on){
						$query = $query . " AND p.data_ped >= '". $dat_ini ."'
						AND p.data_ped <= '". $dat_fin ."'";
					}				  
				$query = $query . " ORDER BY p.data_ped DESC;";

			  	}
			  	else
				  if ($campo == "funi"){

	            	$query =  "SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path
					FROM tb_pedido AS p 
					INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i
					ON p.id = i.id_ped
					INNER JOIN tb_empresa AS e 
					ON p.id_emp = e.id AND p.origem = 'FUN'";
					if($on){
						$query = $query . " AND p.data_ped >= '". $dat_ini ."'
						AND p.data_ped <= '". $dat_fin ."'";
					}				  
				$query = $query . " ORDER BY p.data_ped DESC;";

			  	}
			  	else
			  	if ($campo == "num_ped"){
	              	$query =  "SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path
				  			FROM tb_pedido AS p 
				  			INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i
							ON p.id = i.id_ped
				   			INNER JOIN tb_empresa AS e 
							ON p.id_emp = e.id
							AND p.num_ped = '". $valor ."'";
				 
			  	}
			  	else
			  	if ($campo == "cliente"){
	              	$query =  "SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path
						FROM tb_pedido AS p 
						INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i
						ON p.id = i.id_ped
						INNER JOIN tb_empresa AS e 
						ON p.id_emp = e.id
						AND e.nome LIKE '%".$valor."%'";
					if($on){
						$query = $query . " AND p.data_ped >= '". $dat_ini ."'
						AND p.data_ped <= '". $dat_fin ."'";
					}				  
					$query = $query . " ORDER BY p.data_ped DESC;";

			  	}
			  	else
			  	if ($campo == "vendfechado"){
	              	$query =  "SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path
						FROM tb_pedido AS p 
						INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i
						ON p.id = i.id_ped
						INNER JOIN tb_empresa AS e 
						ON p.id_emp = e.id
						AND p.resp LIKE '%".$valor."%'
						AND p.status = 'FECHADO'";
					if($on){
						$query = $query . " AND p.data_ped >= '". $dat_ini ."'
						AND p.data_ped <= '". $dat_fin ."'";
					}				  
					$query = $query . " ORDER BY p.data_ped DESC;";

			  	}
			  	else
			  	if ($campo == "vendtotal"){
	              	$query =  "SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path
						FROM tb_pedido AS p 
						INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i
						ON p.id = i.id_ped
						INNER JOIN tb_empresa AS e 
						ON p.id_emp = e.id
						AND p.resp LIKE '%".$valor."%'";
					if($on){
						$query = $query . " AND p.data_ped >= '". $dat_ini ."'
						AND p.data_ped <= '". $dat_fin ."'";
					}				  
					$query = $query . " ORDER BY p.data_ped DESC;";

			  	}
				else
			  	if ($campo == "aberto"){
					$query =  "SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path
						FROM tb_pedido AS p 
						INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i
						ON p.id = i.id_ped
						INNER JOIN tb_empresa AS e 
						ON p.id_emp = e.id
						AND p.status = 'ABERTO'";
					if($on){
						$query = $query . " AND p.data_ped >= '". $dat_ini ."'
						AND p.data_ped <= '". $dat_fin ."'";
					}				  
					$query = $query . " ORDER BY p.data_ped DESC;";
								  
				}
			  	else
			  	if ($campo == "fechado"){
	              $query =  "SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path
						FROM tb_pedido AS p 
						INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i
						ON p.id = i.id_ped
						INNER JOIN tb_empresa AS e 
						ON p.id_emp = e.id
						AND p.status = 'FECHADO'";
					if($on){
						$query = $query . " AND p.data_ped >= '". $dat_ini ."'
						AND p.data_ped <= '". $dat_fin ."'";
					}				  
					$query = $query . " ORDER BY p.data_ped DESC;";
				 
				}
			  	if ($campo == "faturado"){
					$query =  "SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path
						  FROM tb_pedido AS p 
						  INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i
						  ON p.id = i.id_ped
						  INNER JOIN tb_empresa AS e 
						  ON p.id_emp = e.id
						  AND p.status = 'PAGO'";
					  if($on){
						  $query = $query . " AND p.data_ped >= '". $dat_ini ."'
						  AND p.data_ped <= '". $dat_fin ."'";
					  }				  
					  $query = $query . " ORDER BY p.data_ped DESC;";
				   
				}					
			  	else
			  	if ($campo == "interno"){
					$query =  "SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path
						  FROM tb_pedido AS p 
						  INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i
						  ON p.id = i.id_ped
						  INNER JOIN tb_empresa AS e 
						  ON p.id_emp = e.id
						  AND p.status = 'INTERNO' ORDER BY p.data_ped DESC;";				   
				}					
			  	else
			  	if ($campo == "cod"){
	              $query =  "SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path
						FROM tb_pedido AS p 
						INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i
						ON p.id = i.id_ped
						INNER JOIN tb_empresa AS e 
						ON p.id_emp = e.id
						AND p.id = '". $valor ."'";
					if($on){
						$query = $query . " AND p.data_ped >= '". $dat_ini ."'
						AND p.data_ped <= '". $dat_fin ."'";
					}				  
					$query = $query . " ORDER BY p.data_ped DESC;";
							 
				  }
			  	if ($campo == "cod_prod"){
					$query =  " SELECT p.id, p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, i.venda, p.path 
					FROM tb_pedido AS p 
					INNER JOIN (SELECT id_ped, ROUND(SUM(qtd * preco),2) AS venda FROM tb_item_ped GROUP BY id_ped) AS i 
					ON p.id = i.id_ped INNER JOIN tb_empresa AS e ON p.id_emp = e.id 
					INNER JOIN tb_item_ped as ip INNER JOIN tb_produto as prod 
					ON p.id = ip.id_ped  AND ip.id_prod = prod.id AND prod.cod = '". $valor ."' ORDER BY p.data_ped DESC;";
																   
					}				  

//			  	echo $query;

			  	$result = mysqli_query($conexao, $query);

				$qtd_lin = $result->num_rows;
				  
				$total = 0;

				echo"  <div class=\"page_form\" id=\"no_margin\">
						<table class=\"search-table\" id=\"tabItens\" >   
			                <tr>
			                  <th>Cod.</th>
			                  <th>Numero</th>
			                  <th>Cliente</th>
			                  <th>Data</th>
			                  <th>Status</th>
			                  <th>Valor</th>
			                  <th>NF</th>
						  	</tr>";
					        while($fetch = mysqli_fetch_row($result)){

					        	$cod_ped = $fetch[0];
					        	$status = $fetch[6];

					            echo "<tr class='tbl_row'><td>" .$fetch[0] . "</td>".
								     	 "<td>" .$fetch[1] . "</td>".
								     	 "<td>" .$fetch[2] . "</td>".
								         "<td>" . date('d/m/Y', strtotime($fetch[4])) . "</td>";
								         if ($fetch[6] == 'ABERTO'){
								     	 	echo "<td> COT</td>";
								     	 }else if ($fetch[6] == 'FECHADO'){
												echo "<td> PED</td>";
											}else if($fetch[6] == 'INTERNO'){
												echo "<td> <b>INT</b> </td>";
												$fetch[7] = 0;

											}else{
												echo "<td> <b>FAT</b> </td>";
											}
										  
										  echo "<td>" . money_format('%=*(#0.2n',$fetch[7]) . "</td>";
										  
										  if($fetch[8] == null){ // Se não existe NF em PDF
											echo "<td></td>";
										 }else{
											echo "<td>@</td>";
										 }										  

										  echo "<td style='display: none;'>" .$fetch[8] . "</td></tr>";
										  $total = $total + $fetch[7];
										  
					        }



						    echo"<tr><td></td><td></td><td></td><td></td><th>Total</th><th>".money_format('%=*(#0.2n',$total)."</th></tr>
						</table> 

				  </div>
				  ";
				$conexao->close();

		    }

			if ($qtd_lin == 1){
		    	echo"
			  	  <div class=\"page_form\" id= \"no_margin\">
			  	  		<table class=\"search-table\"  border=\"0\">
			  	  			<tr>
			  	  				<td><form class=\"login-form\" method=\"POST\" action=\"pdf_analise.php\">
			  	  					<input type=\"hidden\" name=\"cod_ped\" value=\"". $cod_ped ."\">
			  	  					<button id=\"botao_inline\" type=\"submit\">Analisar</button>
			  	  				</form></td>
			  	  				<td><form class=\"login-form\" method=\"POST\" action=\"edita_ped.php\">
			  	  					<input type=\"hidden\" name=\"cod_ped\" value=\"". $cod_ped ."\">
			  	  					<button id=\"botao_inline\" type=\"submit\">Visualizar</button>
			  	  				</form></td>
			  	  				<td><form class=\"login-form\" method=\"POST\" action=\"del_ped.php\" onsubmit=\"return confirma('Deseja realmente deletar esta cotação?')\"  >
			  	  					<input type=\"hidden\" name=\"cod_ped\" value=\"". $cod_ped ."\">
			  	  					<input type=\"hidden\" name=\"status\" value=\"". $status ."\">
			  	  					<button id=\"botao_inline\" type=\"submit\">Deletar</button>
			  	  				</form></td>
			  	  			</tr>
			  	  		</table>
			    	</form>


				  </div>";
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