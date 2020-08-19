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
    <script src="js/pesq_prod.js"></script>
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
  	  	<p class="logo"> Pesquisa por Produto</p> <br>
  	  	<form class="login-form" method="POST" action="#">
  	  		<table class="search-table"  border="0"><tr><td>
  		  <label> Busca por: </label> </td><td>
	      <select name="campo">
	        <option value="desc">Descricao</option>
	        <option value="cod">Codigo</option>
	        <option value="forn">Fornecedor</option>
	        <option value="servico">Servicos</option>
	        <option value="conj">Conjuntos</option>
	        <option value="tinta">Tintas</option>
	        <option value="pigmto">Pigmentos</option>
	        <option value="cod_bar">Cod. Produto</option>
	        <option value="cod_cli">Cod. de Barras</option>
	        <option value="etq_min">Estoque Baixo</option>
	    </select></td><td>
      <input type="text" name="valor" maxlength="12"/></td><td>
	  <button class="botao_inline" type="submit">OK</button></td></tr>  </table>

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
			  	if ($campo == "desc"){
			  		$query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.descricao LIKE '%".$valor."%' AND p.id_emp = e.id ORDER BY cast(p.cod as unsigned integer);";
			  	}
			  	else
			  	if ($campo == "cod"){
			  		$query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.cod = '".$valor."' AND p.id_emp = e.id ;";
			  	}
			  	else
			  	if ($campo == "cod_bar"){
			  		$query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.cod_bar LIKE '%".$valor."%' AND p.id_emp = e.id ;";
			  	}
			  	else
			  	if ($campo == "etq_min"){
			  		$query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.estoque <= p.etq_min AND p.id_emp = e.id AND (p.tipo ='VENDA' or p.tipo ='PIGMTO') ORDER BY cast(p.cod as unsigned integer);";
			  	}
			  	else
			  	if ($campo == "forn"){

			  		$query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON e.nome LIKE '%".$valor."%' AND p.id_emp = e.id AND p.tipo ='VENDA' ORDER BY e.nome;";

			  	}
			  	if ($campo == "servico"){

			  		$query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON  p.tipo ='SERVICO' AND p.id_emp = e.id ORDER BY e.nome;";

			  	}
			  	if ($campo == "conj"){

					$query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON  p.tipo ='CONJ' AND p.id_emp = e.id ORDER BY e.nome;";

				}
				if ($campo == "tinta"){

			  		$query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON  (p.tipo ='TINTA' OR p.tipo ='TINTA_E')  AND p.id_emp = e.id ORDER BY p.cod desc;";

			  	}
			  	if ($campo == "pigmto"){

			  		$query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON  p.tipo ='PIGMTO' AND p.id_emp = e.id ORDER BY e.nome;";

			  	}
			  	if ($campo == "cod_cli"){

					$query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.cod_cli LIKE '%".$valor."%' AND p.id_emp = e.id ;";

				}

			  	$result = mysqli_query($conexao, $query);

			  	$qtd_lin = $result->num_rows;



				echo"  <div class=\"page_form\" id=\"no_margin\">
						<table class=\"search-table\" id=\"tabItens\">
						  	<tr>
						    	<th class=\"center_text\">Cod.</th>
						    	<th class=\"center_text\">Descricao</th>
						    	<th class=\"center_text\">Und.</th>
						    	<th class=\"center_text\">Estq.</th>
						    	<th class=\"center_text\">Cod. Prod.</th>
						    	<th class=\"center_text\">Fornecedor</th>
						    	<th class=\"center_text\">Preço</th>
						  	</tr>";
					        while($fetch = mysqli_fetch_row($result)){

					        	$cod_prod = $fetch[0];
					        	$preco = $fetch[7] * (1 + $fetch[8]/100);

								echo "<tr class='tbl_row'>".
										 "<td class=\"center_text\" >" .$fetch[1] . "</td>".
								     	 "<td>" .$fetch[2] . "</td>".
								         "<td class=\"center_text\">" .$fetch[3] . "</td>".
								         "<td class=\"center_text\">" .$fetch[4] . "</td>".
								     	 "<td class=\"center_text\">" .$fetch[5] . "</td>".
								     	 "<td class=\"center_text\">" . substr($fetch[6], 0, 10) . ".</td>".
										 "<td>" .money_format('%=*(#0.2n', $preco). "</td>
										  <td style='display: none;'>".money_format('%=*(#0.2n', $fetch[7])."</td>
										  <td style='display: none;'>".$fetch[8]."</td>
										  <td style='display: none;'>".$fetch[10]."</td>
										  <td style='display: none;'>".$fetch[0]."</td>
									  </tr>";
								     	 
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
			  	  				<td><form class=\"login-form\" method=\"POST\" action=\"edt_prod.php\">
			  	  					<button id=\"botao_inline\" type=\"submit\">Editar</button>
			  	  					<input type=\"hidden\" name=\"cod_prod\" value=\"". $cod_prod ."\">
			  	  				</form></td>
			  	  				<td><form class=\"login-form\" method=\"POST\" action=\"del_prod.php\" onsubmit=\"return confirma('Deseja mesmo deletar este produto?')\"  >
			  	  					<button id=\"botao_inline\" type=\"submit\">Deletar</button>
			  	  					<input type=\"hidden\" name=\"cod_prod\" value=\"". $cod_prod ."\">
			  	  				</form></td>
			  	  			</tr>
			  	  		</table>

			    	</form>


				  </div>";
			}		    

			if ($qtd_lin > 0){
  	  
  	  			echo"

  	  			<div class=\"page_form\" id=\"no_margin\">



                    <table class=\"search-table\"  border=\"0\">

                       <td>
                      <form class=\"login-form\" method=\"POST\" action=\"pdf_prod.php\">
                        <td><button name=\"imprimir\" id=\"botao_inline\" type=\"submit\" >Imprimir</button></td>
                        <td>  <input type=\"checkbox\" checked name=\"ver_preco\" value=\"1\" /> </td>
                        <input type=\"hidden\" name=\"query\" value=\"". $query ."\">
                      </form></td>
                      </tr>
                    </table>





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