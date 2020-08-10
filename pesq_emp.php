<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
    <title>Pesquisa por Empresas</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>	
    <script src="js/edt_mask.js"></script>
    <script src="js/pesq_emp.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Pesquisa por Empresas</p> <br>
  	  	<form class="login-form" method="POST" action="#">
  	  		<table class="search-table"  border="0"><tr><td>
  		  <label> Busca por: </label> </td><td>
	      <select name="campo">
	        <option selected="selected" value="nome">Nome</option>
	        <option value="cli">Clientes</option>
	        <option value="forn">Fornecedores</option>
	        <option value="cnpj">CNPJ</option>
	        <option value="ie">Insc. Estadual</option>
	        <option value="cod">Codigo</option>
	    </select></td><td>

      <input type="text" name="valor" maxlength="12"/></td><td>
	  <button class="botao_inline" type="submit">OK</button></td></tr>  </table>

    	</form>
	  </div>

	  <?php

		function CNPJ($cnpj){
			$resp = substr($cnpj,0,2).".";
			$resp = $resp . substr($cnpj,2,3).".";
			$resp = $resp . substr($cnpj,5,3)."/";
			$resp = $resp . substr($cnpj,8,4)."-";
			$resp = $resp . substr($cnpj,12,2);

			return $resp;
		}

		function IE($ie){
			$resp = substr($ie,0,3).".";
			$resp = $resp . substr($ie,3,3).".";
			$resp = $resp . substr($ie,6,3).".";
			$resp = $resp . substr($ie,9,3);

			return $resp;
		}


	  	$qtd_lin = 0;

		    if (IsSet($_POST ["campo"])){

				include "conecta_mysql.inc";
				if (!$conexao)
					die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

			  	$campo = $_POST ["campo"];
			  	$valor = $_POST ["valor"];
			  	if ($campo == "nome"){
			  		$query = "SELECT * FROM tb_empresa WHERE nome LIKE '%".$valor."%'; ";
			  	}
			  	else
			  	if ($campo == "cli"){
			  		$query = "SELECT * FROM tb_empresa WHERE tipo = \"cli\"; ";
			  	}
			  	else
			  	if ($campo == "forn"){
			  		$query = "SELECT * FROM tb_empresa WHERE tipo = \"for\"; ";
			  	}
			  	else
			  	if ($campo == "cnpj"){
			  		$query = "SELECT * FROM tb_empresa WHERE cnpj LIKE '%".$valor."%'; ";
			  	}
			  	else
			  	if ($campo == "ie"){
			  		$query = "SELECT * FROM tb_empresa WHERE ie LIKE '%".$valor."%'; ";
			  	}
			  	else
			  	if ($campo == "cod"){
			  		$query = "SELECT * FROM tb_empresa WHERE id = '" . $valor . "';";
			  	}

			  	$result = mysqli_query($conexao, $query);
				
				$qtd_lin = $result->num_rows;

				echo"  <div class=\"page_form\" id=\"no_margin\">
						<table class=\"search-table\" id=\"tabItens\">
						  	<tr>
						    	<th>Cod.</th>
						    	<th>Nome</th>
						    	<th>CNPJ</th>
						    	<th>Insc. Est.</th>
						    	<th>Telefone</th>
						    	<th>Tipo</th>
						  	</tr>";
					        while($fetch = mysqli_fetch_row($result)){

					        	$cod_emp = $fetch[0];

								echo "<tr class='tbl_row'>".
										 "<td>" .$fetch[0] . "</td>".
								         "<td>" .$fetch[1] . "</td>".
								         "<td>" . CNPJ($fetch[2]) . "</td>".
								         "<td>" . IE($fetch[3]) . "</td>".
								     	 "<td style='display: none;'>" .$fetch[4] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[5] . "</td>".
								         "<td style='display: none;'>" .$fetch[6] . "</td>".
								         "<td>" .$fetch[8] . "</td>".
								         "<td>" .$fetch[7] . "</td>".
								         "<td style='display: none;'>" .$fetch[9] . "</td>".
								         "<td style='display: none;'>" .$fetch[10] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[11] . "</td></tr>";
					        }
						    echo"
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
			  	  				<td><form class=\"login-form\" method=\"POST\" action=\"edt_emp.php\">
			  	  					<button id=\"botao_inline\" type=\"submit\">Editar</button>
			  	  					<input type=\"hidden\" name=\"cod_emp\" value=\"". $cod_emp ."\">
			  	  				</form></td>
			  	  				<td><form class=\"login-form\" method=\"POST\" action=\"del_emp.php\" onsubmit=\"return confirma('Deseja deletar esta empresa?')\"  >
			  	  					<button id=\"botao_inline\" type=\"submit\">Deletar</button>
			  	  					<input type=\"hidden\" name=\"cod_emp\" value=\"". $cod_emp ."\">
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