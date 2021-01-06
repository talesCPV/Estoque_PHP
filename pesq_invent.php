<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Historico de Inventário</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Histórico de Inventário</p> <br>
  	  	<form class="login-form" method="POST" action="#">
  	  		<table class="search-table"  border="0"><tr><td>
			<label> Busca por: </label> </td><td>
			<select name="campo" id="selPesq">
                <optgroup label="Geral">
				<option value="todos">Todos</option>
				<option value="ret">Retirada p/ Cod.</option>
				<option value="cod">Codigo</option>
				<option value="usr">Usuário</option>

			</select></td><td>
			<input type="text" name="valor" maxlength="12"/></td><td>
			<button class="botao_inline" type="submit">OK</button></td></tr>  </table>

			<input type="checkbox" id="ckbDatas" name="ckbDatas" checked>
			<label for="ckbDatas">Início / Final</label>
			
			<table class="search-table"  border="0"><tr>
				<td> <input type="date" name="data_ini" class="selData" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date('m') , 1 , date('Y'))); ?>"> </td>
				<td> <input type="date" name="data_fin" class="selData" value="<?php echo date('Y-m-d',mktime(23, 59, 59, date('m'), date("t"), date('Y'))); ?>"> </td></tr> </table>
			

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

			  	if ($campo == "todos"){
					$query =  "SELECT * from tb_inventario ";
					if($on){
						$query = $query . "where dia >= '$data_ini' and dia <= '$data_fin 23:59:59'" . $pgto;
					}
			  	}
			  	if ($campo == "cod"){
					$query =  "SELECT * from tb_inventario where cod_prod = '{$valor}' ";
					if($on){
						$query = $query . "and dia >= '$data_ini' and dia <= '$data_fin 23:59:59'". $pgto;
					}
			  	}
			  	else
			  	if ($campo == "usr"){
					$query =  "SELECT * from tb_inventario where user LIKE '%{$valor}%' ";
					if($on){
						$query = $query . "and dia >= '$data_ini' and dia <= '$data_fin 23:59:59'". $pgto;
					}
			  	}
			  	else
			  	if ($campo == "ret"){
					$query =  "SELECT * from tb_inventario where cod_prod LIKE '%{$valor}%' AND oper = '1' ";
					if($on){
						$query = $query . "and dia >= '$data_ini' and dia <= '$data_fin 23:59:59'". $pgto;
					}
			  	}

                $query = $query . ' ORDER BY dia DESC';
                
			  	$result = mysqli_query($conexao, $query);

                echo"  <div class=\"page_form\" id=\"no_margin\">
                        <p class=\"logo\" id=\"lblPesq\"> Pesquisa </p> <br>
						<table class=\"search-table\" id=\"tabItens\" >   
			                <tr>
			                  <th>ID</th>
			                  <th>Cod. Produto</th>
			                  <th>Tipo</th>
			                  <th>Qtd.</th>
			                  <th>Etq. Ant.</th>
			                  <th>Data</th>
			                  <th>Usuario</th>
                              </tr>";
                            $tot = 0;
					        while($fetch = mysqli_fetch_row($result)){

					        	$id = $fetch[0];
                                $cod = $fetch[1];
                                if($fetch[2] == '1'){
                                    $ope = 'RETIRADA';
                                }else{
                                    $ope = 'INVENTARIO';
                                }
					        	$qtd = $fetch[3];
					        	$ant = $fetch[4];
					        	$usr = $fetch[5];
                                $dia = $fetch[6];
                                
                                if ($campo == "ret"){
                                    $tot = $tot + $qtd;
                                }

                                echo "<tr class='tbl_row'>".
                                    "<td>{$id}</td>".						   
                                    "<td>{$cod}</td>".						   
                                    "<td>{$ope}</td>".						   
                                    "<td>{$qtd}</td>".						   
                                    "<td>{$ant}</td>".						   
                                    "<td>" . date('d/m/Y', strtotime($dia)) . "</td>".
                                    "<td>" . strtoupper(utf8_decode($usr)) . "</td></tr>";
                            }
                            if ($campo == "ret"){
                                echo "<tr><td></td><td></td><td></td><td></td><td></td><th>Qtd Total</th><th>{$tot}</th></tr>";
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