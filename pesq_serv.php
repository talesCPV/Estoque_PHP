<?php
	setcookie("cod_serv");
  	include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Serviços Executados</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
    <script src="js/pesq_serv.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Pesquisa de Serviços Executados</p> <br>
  	  	<form class="login-form" method="POST" action="#">
  	  		<table class="search-table"  border="0"><tr><td>
  		  <label> Busca por: </label> </td><td>
	      <select name="campo" id="selPesqPed">		
	        <option value="todos">Todos</option>
	        <option value="cli">Cliente</option>
	        <option value="nf">NF</option>
	        <option value="ped">Pedido</option>
	        <option value="num">Numero do Carro</option>
	    </select></td><td>
      <input type="text" name="valor" maxlength="12"/></td><td>
	  <button id="botao_inline" type="submit">OK</button></td></tr>  </table>
				
		<table class="search-table"  border="0">
          <tr>
            <td> <input type="checkbox" id="ckbDatas" name="ckbDatas" ></td>
			<td> <input type="date" name="data_exec" id="selData_Exec" value="<?php echo date('Y-m-d'); ?>"> </td>
			<td> <input type="date" name="data_fin" id="selData_Fin" value="<?php echo date('Y-m-d'); ?>"> </td>
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
				$data_exec = $_POST ["data_exec"];
				$data_fin = $_POST ["data_fin"];

                $query_opt = "";


                if ($campo == "cli"){
                    $query_opt = $query_opt . " AND e.nome LIKE '%".$valor."%'";
                }else if($campo == "nf"){
                    $query_opt = $query_opt . " AND s.nf LIKE '%".$valor."%'";
                }else if($campo == "ped"){
                    $query_opt = $query_opt . " AND s.pedido LIKE '%".$valor."%'";
                }else if($campo == "num"){
                    $query_opt = $query_opt . " AND s.num_carro LIKE '%".$valor."%'";
                }

                if (IsSet($_POST ["ckbDatas"])){
                    $query_opt = $query_opt . " AND s.data_exec >= '". $data_exec ."' AND s.data_exec <= '". $data_fin ."'";
                }
            
                  $query_opt = $query_opt . " order by data_exec";

                  $query =  "SELECT s.id, e.nome, s.num_carro, s.data_exec,  s.func, s.obs, s.nf, s.pedido
                             FROM tb_serv_exec as s
                             INNER JOIN tb_empresa as e
                             ON s.id_emp = e.id " . $query_opt;                                  

			  	
//			  	echo $query;

			  	$result = mysqli_query($conexao, $query);

				$qtd_lin = $result->num_rows;
				  

				echo"  <div class=\"page_form\" id=\"no_margin\">
						<table class=\"search-table\" id=\"tabItens\" >   
			                <tr>
			                  <th>Cod.</th>
			                  <th>Cliente</th>
			                  <th>Carro</th>
			                  <th>Execução</th>
			                  <th>NF</th>
						  	</tr>";
					        while($fetch = mysqli_fetch_row($result)){

					        	$cliente = $fetch[1];

                                echo "<tr class='tbl_row'>".
                                         "<td>" .$fetch[0] . "</td>".
								     	 "<td>" .$fetch[1] . "</td>".
								     	 "<td>" .$fetch[2] . "</td>".
								     	 "<td>" .date('d/m/Y', strtotime($fetch[3]))  . "</td>".
								         "<td style='display: none;'>" . $fetch[4] . "</td>".
								         "<td style='display: none;'>" . $fetch[5] . "</td>".
								         "<td>" . $fetch[6] . "</td>".
                                         "<td style='display: none;'>" . $fetch[7] . "</td>";
										  
					        }

                            echo"
                        </table> 

				  </div>
				  ";
				$conexao->close();

		    }

			if ($qtd_lin > 0){
		    	echo"
			  	  <div class=\"page_form\" id= \"no_margin\">
			  	  		<table class=\"search-table\"  border=\"0\">
			  	  			<tr>
			  	  				<td><form class=\"login-form\" method=\"POST\" action=\"pdf_serv.php\">
			  	  					<input type=\"hidden\" name=\"query\" value=\"". $query ."\">
			  	  					<input type=\"hidden\" name=\"cliente\" value=\"". $cliente ."\">
			  	  					<input type=\"hidden\" name=\"ini\" value=\"". $data_exec ."\">
			  	  					<input type=\"hidden\" name=\"fin\" value=\"". $data_fin ."\">
			  	  					<button id=\"botao_inline\" type=\"submit\">Relatório</button>
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