<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Análise Financeira</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
    <script src="js/pesq_anafin.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Fluxo de Caixa</p> <br>
  	  	<form class="login-form" method="POST" action="#">
  	  		<table class="search-table"  border="0"><tr><td>
			<label> Busca por: </label> </td><td>
			<select name="campo" id="selPesq">
				<optgroup label="Geral">
				<option value="todos">Todos</option>
				<option value="entrada">Entradas</option>
				<option value="saida">Saídas</option>
				<option value="cli">Cliente / Fornecedor</option>
				<optgroup label="Funilaria e Pintura">
				<option value="fun_todos">Todos</option>
				<option value="fun_entrada">Entradas</option>
				<option value="fun_saida">Saídas</option>
				<optgroup label="Sanfonados">
				<option value="san_todos">Todos</option>
				<option value="san_entrada">Entradas</option>
				<option value="san_saida">Saídas</option>
			</select></td><td>
			<input type="text" name="valor" maxlength="12"/></td><td>
			<button class="botao_inline" type="submit">OK</button></td></tr>  </table>

			<input type="checkbox" id="ckbDatas" name="ckbDatas" checked>
			<label for="ckbDatas">Início / Final</label>
			
			<table class="search-table"  border="0"><tr>
				<td> <input type="date" name="data_ini" class="selData" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date('m') , 1 , date('Y'))); ?>"> </td>
				<td> <input type="date" name="data_fin" class="selData" value="<?php echo date('Y-m-d',mktime(23, 59, 59, date('m'), date("t"), date('Y'))); ?>"> </td></tr> </table>
			<label> Tipo de Pagto.</label>
			<select name="selPgt" id="selPgt">
				<option value="TDS" selected> Todos </option>
				<option value="BOL"> Boleto </option>
				<option value="DEB"> Cartão de Débto </option>
				<option value="CRD"> Cartão de Crédito </option>
				<option value="CHQ"> Cheque </option>
				<option value="DIN"> Dinheiro </option>
				<option value="DEP"> Depósito </option>
				<option value="AUT"> Débto Automático </option>
			</select>			

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
			  	$data_ini = $_POST ["data_ini"];
				$data_fin = $_POST ["data_fin"];
				$on = 0;
				$pgto = '';

				if (IsSet($_POST ["ckbDatas"])){
					$on = 1;
				}

				if ($_POST ["selPgt"] != "TDS"){
					$pgto =  " AND pgto='{$_POST ['selPgt']}'";
			  	}


			  	if ($campo == "todos"){
					$query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem, pgto from tb_financeiro ";
					if($on){
						$query = $query . "where data_pg >= '$data_ini' and data_pg <= '$data_fin'". $pgto;
					}
			  	}
			  	if ($campo == "fun_todos"){
					$query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem, pgto from tb_financeiro where origem = 'FUN' ";
					if($on){
						$query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'". $pgto;
					}
			  	}
			  	else
			  	if ($campo == "san_todos"){
					$query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem, pgto from tb_financeiro where origem = 'SAN' ";
					if($on){
						$query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'". $pgto;
					}
			  	}
			  	else
			  	if ($campo == "entrada"){
					$query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem, pgto from tb_financeiro where tipo = 'ENTRADA' ";
					if($on){
						$query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'". $pgto;
					}
				}
			  	else
			  	if ($campo == "fun_entrada"){
					$query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem, pgto from tb_financeiro where tipo = 'ENTRADA' and origem = 'FUN' ";
					if($on){
						$query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'". $pgto;
					}
				}
			  	else
			  	if ($campo == "san_entrada"){
					$query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem , pgtofrom tb_financeiro where tipo = 'ENTRADA' and origem = 'SAN' ";
					if($on){
						$query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'". $pgto;
					}
				}
			  	else
			  	if ($campo == "saida"){
					$query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem, pgto from tb_financeiro where tipo = 'SAIDA' ";
					if($on){
						$query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'". $pgto;
					}
			  	}
			  	else
			  	if ($campo == "fun_saida"){
					$query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem, pgto from tb_financeiro where tipo = 'SAIDA' and origem = 'FUN' ";
					if($on){
						$query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'". $pgto;
					}
			  	}
			  	else
			  	if ($campo == "san_saida"){
					$query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem, pgto from tb_financeiro where tipo = 'SAIDA' and origem = 'SAN' ";
					if($on){
						$query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'". $pgto;
					}
			  	}
			  	else
			  	if ($campo == "cli"){
					$query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem, pgto from tb_financeiro where emp LIKE '%".$valor."%'";
					if($on){
						$query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'". $pgto;
					}
			  	}

				$query = $query . ' ORDER BY data_pg';

			  	$result = mysqli_query($conexao, $query);

			  	$qtd_lin = $result->num_rows;

                echo"  <div class=\"page_form\" id=\"no_margin\">
                        <p class=\"logo\" id=\"lblPesq\"> Pesquisa </p> <br>
						<table class=\"search-table\" id=\"tabItens\" >   
			                <tr>
			                  <th>Cod.</th>
			                  <th>Tipo</th>
			                  <th>Orig.</th>
			                  <th>Referência / NF</th>
			                  <th>Empresa</th>
			                  <th>Vencimento</th>
			                  <th>Pgto</th>
			                  <th>Valor</th>
							  </tr>";
							$tot_ent = 0;
							$tot_sai = 0;
					        while($fetch = mysqli_fetch_row($result)){

					        	$cod_ped = $fetch[0];
					        	$status = $fetch[5];

								echo "<tr class='tbl_row'><td>" .$fetch[0] . "</td>";
								if ($fetch[5] == 'ENTRADA'){
									$tot_ent = $tot_ent + $fetch[4];
									 echo "<td> ENTRADA</td>";
								 }else{
								   $tot_sai = $tot_sai + $fetch[4];
								   echo "<td> SAÍDA</td>";
								 }
					   
								echo  	 "<td>" .$fetch[6] . "</td>".
								     	 "<td>" . strtoupper(substr(utf8_decode($fetch[1]),0,30)) . "</td>".
								     	 "<td>" . strtoupper(substr(utf8_decode($fetch[2]),0,30)) . "</td>".
								         "<td>" . date('d/m/Y', strtotime($fetch[3])) . "</td>".
										 "<td>" . strtoupper(utf8_decode($fetch[7])) . "</td>".
								         "<td>" . money_format('%=*(#0.2n', $fetch[4]) . "</td></tr>";
							}
							$saldo = $tot_ent - $tot_sai;
							echo "<tr><td></td><td></td><td></td><td></td><td></td><td>SALDO</td><th></th><th>". money_format('%=*(#0.2n', $saldo) ."</th></tr>";



						    echo"
						</table> 

				  </div>
				  ";
				$conexao->close();

		    }
				if($qtd_lin > 0){
		    	echo"
					<div class=\"page_form\" id= \"no_margin\">
			  	  		<table class=\"search-table\"  border=\"0\">
			  	  			<tr>
			  	  				<td><form class=\"login-form\" method=\"POST\" action=\"pdf_finan.php\">
			  	  					<input type=\"hidden\" name=\"pdf_campo\" value=\"".$campo."\">
			  	  					<input type=\"hidden\" name=\"pdf_valor\" value=\"".$valor."\">
			  	  					<input type=\"hidden\" name=\"pdf_dataini\" value=\"".$data_ini."\">
			  	  					<input type=\"hidden\" name=\"pdf_datafin\" value=\"".$data_fin."\">
			  	  					<input type=\"hidden\" name=\"pdf_check\" value=\"".$on."\">
			  	  					<button id=\"botao_inline\" type=\"submit\">Analisar</button>
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