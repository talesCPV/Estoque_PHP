<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pesquisa por Produto</title>
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
  	  	<p class="logo"> Funcionários</p> <br>
  	  	<form class="login-form" method="POST" action="#">
  	  		<table class="search-table"  border="0"><tr>
				<td><label> Nome: </label> </td><td>
				<td><input type="text" name="valor" maxlength="12"/></td>
				<td><button class="botao_inline" id="btn_Busca" type="submit">Busca</button></td>
				<td><button class="botao_inline" id="btn_NovoFunc">Novo</button></td>
			</tr></table>
    	</form>
	  </div>

	  <?php

		if (IsSet($_POST ["valor"])){
			$valor = $_POST ["valor"];
			include "conecta_mysql.inc";
			if (!$conexao)
				die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

			$query =  "SELECT f.*, c.cargo, c.salario, c.tipo FROM tb_funcionario AS f INNER JOIN tb_cargos AS c ON f.nome LIKE '%".$valor."%' AND f.id_cargo = c.id ORDER BY f.status";

			$result = mysqli_query($conexao, $query);

				echo"  <div class=\"page_form\" id=\"no_margin\">
						<table class=\"search-table\" id=\"tabItens\">
						  	<tr>
						    	<th>Cod.</th>
						    	<th>Nome</th>
						    	<th>Admissão</th>
						    	<th>Cargo</th>
						    	<th>Status</th>
						  	</tr>";
					        while($fetch = mysqli_fetch_row($result)){
								echo "<tr class='tbl_row'>".
										 "<td>" .$fetch[0] . "</td>".
										 "<td>" .$fetch[1] . "</td>".
										 "<td>" . date('d/m/Y', strtotime($fetch[9])) . "</td>".
								     	 "<td>" .$fetch[15] . "</td>".
								     	 "<td>" .$fetch[14] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[2] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[3] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[4] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[5] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[6] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[7] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[8] . "</td>".
								     	 "<td style='display: none;'>" .date('d/m/Y', strtotime($fetch[10])) . "</td>".
								     	 "<td style='display: none;'>" .$fetch[11] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[12] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[13] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[16] . "</td>".
								     	 "<td style='display: none;'>" .$fetch[17] . "</td>".
									  "</tr>";								     	 
					        }
						    echo"
						</table> 
				  </div>";

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