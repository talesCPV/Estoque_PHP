<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Agenda de Contatos</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
    <script src="js/pesq_agenda.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Agenda de Contatos</p> <br>
  	  	<form class="login-form" id="frmBusca" method="POST" action="#">
  	  		<table class="search-table"  border="0"><tr>
				<td><label> Busca por: </label> </td>
				<td> <select name="campo">
					<option selected="selected" value="nome">Nome</option>
					<option value="emp">Empresa</option>
					<option value="cod">Codigo</option>
				</select> </td>
				<td> <input type="text" name="valor" maxlength="12"/></td>
				<td> <button class="botao_inline" type="submit">OK</button></td>
				<td> <button class="botao_inline" id="btnNovo">Novo</button></td>
				</tr>  	  
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


			  	if ($campo == "nome"){
			  		$query = "SELECT a.id, a.nome, a.depart, a.email, a.cel1, a.cel2, e.nome FROM tb_agenda a, tb_empresa e WHERE a.nome LIKE '%".$valor."%' AND a.id_emp = e.id ; ";
			  	}
			  	else
			  	if ($campo == "emp"){
			  		$query =  "SELECT a.id, a.nome, a.depart, a.email, a.cel1, a.cel2, e.nome FROM tb_agenda AS a INNER JOIN tb_empresa AS e ON e.nome LIKE '%".$valor."%' AND a.id_emp = e.id ;";
			  	}

			  	else
			  	if ($campo == "cod"){
			  		$query =  "SELECT a.id, a.nome, a.depart, a.email, a.cel1, a.cel2, e.nome FROM tb_agenda AS a INNER JOIN tb_empresa AS e ON a.id = ".$valor." AND a.id_emp = e.id ;";

			  	}

			  	$result = mysqli_query($conexao, $query);

			  	$qtd_lin = $result->num_rows;



				echo"  <div class=\"page_form\" id=\"no_margin\">
						<table class=\"search-table\" id=\"tabItens\" >
						  	<tr>
						    	<th>Nome</th>
						    	<th>Telefone</th>
						    	<th>Empresa</th>
						  	</tr>";
					        while($fetch = mysqli_fetch_row($result)){

					        	$cod_agenda = $fetch[0];

								echo "<tr class='tbl_row'>".
										 "<td style='display: none;'>" .strtoupper($fetch[0]) . "</td>".
								         "<td>" .strtoupper($fetch[1]) . "</td>".
								         "<td style='display: none;'>" .strtoupper($fetch[2]) . "</td>".
								         "<td style='display: none;'>" .strtolower($fetch[3]) . "</td>".
								     	 "<td style='display: none;'>" .strtoupper($fetch[4]) . "</td>".
								     	 "<td>" .strtoupper($fetch[5]) . "</td>".
								     	 "<td>" .strtoupper($fetch[6]) . "</td></tr>";
					        }
						    echo"
						</table> 

				  </div>
				  ";
				$conexao->close();

		    }

		    $classe = $_COOKIE["classe"];

			if ($qtd_lin == 1 && ($classe == "10" or $classe == "4")){

		    	echo"
			  	  <div class=\"page_form\" id= \"no_margin\">
			  	  		<table class=\"search-table\"  border=\"0\">
			  	  			<tr>
			  	  				<td><form class=\"login-form\" method=\"POST\" action=\"edt_agenda.php\">
			  	  					<button id=\"botao_inline\" type=\"submit\">Editar</button>
			  	  					<input type=\"hidden\" name=\"cod_agenda\" value=\"". $cod_agenda ."\">
			  	  				</form></td>
			  	  				<td><form class=\"login-form\" method=\"POST\" action=\"del_agenda.php\" onsubmit=\"return confirma('Deseja deletar este contato?')\"  >
			  	  					<button id=\"botao_inline\" type=\"submit\">Deletar</button>
			  	  					<input type=\"hidden\" name=\"cod_agenda\" value=\"". $cod_agenda ."\">
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