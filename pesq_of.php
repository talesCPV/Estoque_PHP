<?php
	setcookie("cod_serv");
  	include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Ordem de Fabricação</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
    <script src="js/pesq_of.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Pesquisa de OFs</p> <br>
  	  	<form class="login-form" method="POST" action="#">
  	  		<table class="search-table"  border="0"><tr><td>
  		  <label> Busca por: </label> </td><td>
	      <select name="campo" id="selPesqPed">		
	        <option value="todos">Todos</option>
	        <option value="num">Número</option>
	        <option value="item">Cód.</option>
	        <option value="aberto">Aberto</option>
	        <option value="fechado">Fechado</option>
	        <option value="of">Ordem de Fabricação</option>
	        <option value="os">Ordem de Serviço</option>
	        <option value="resp">Gerado por...</option>
	        <option value="fun">Funcionário Resp.</option>
	    </select></td><td>
      <input type="text" name="valor" maxlength="12"/></td><td>
	  <button id="botao_inline" type="submit">OK</button></td></tr>  </table>
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
				$finQuery = " ORDER BY id";

				if (IsSet($_POST ["ckbDatas"])){
					$finQuery = " WHERE data_serv >= '". $dat_ini ."' AND data_serv <= '". $dat_fin ."' ORDER BY id";
				}
  
				if ($campo == "todos"){
	            	$query =  "SELECT *	FROM tb_servico". $finQuery;
			  	}
			  	else if ($campo == "num"){
					$query =  "SELECT *	FROM tb_servico WHERE num_serv LIKE '%".$valor."%'";
					if (IsSet($_POST ["ckbDatas"])){
						$query =  $query." AND ". substr($finQuery,6,strlen($finQuery)) ;
					}

			  	}
			  	else if ($campo == "item"){
					$query =  "SELECT s.*	FROM tb_servico AS s INNER JOIN tb_item_serv AS i INNER JOIN tb_produto AS p ON s.id = i.id_serv AND p.id = i.id_item AND  p.cod = '".$valor."'". $finQuery;
				}
				else if ($campo == "aberto"){
					$query =  "SELECT *	FROM tb_servico WHERE status = 'ABERTO'";
					if (IsSet($_POST ["ckbDatas"])){
						$query =  $query." AND ". substr($finQuery,6,strlen($finQuery)) ;
					}					
				}
				else if ($campo == "fechado"){
					$query =  "SELECT *	FROM tb_servico WHERE status = 'FECHADO'";
					if (IsSet($_POST ["ckbDatas"])){
						$query =  $query." AND ". substr($finQuery,6,strlen($finQuery)) ;
					}					
				}
				else if ($campo == "of"){
					$query =  "SELECT *	FROM tb_servico WHERE tipo = 'OF'";
					if (IsSet($_POST ["ckbDatas"])){
						$query =  $query." AND ". substr($finQuery,6,strlen($finQuery)) ;
					}					
				}		
				else if ($campo == "os"){
					$query =  "SELECT *	FROM tb_servico WHERE tipo = 'OS'";
					if (IsSet($_POST ["ckbDatas"])){
						$query =  $query." AND ". substr($finQuery,6,strlen($finQuery)) ;
					}					
				}	
				else if ($campo == "resp"){
					$query =  "SELECT *	FROM tb_servico WHERE resp LIKE '%".$valor."%'";
					if (IsSet($_POST ["ckbDatas"])){
						$query =  $query." AND ". substr($finQuery,6,strlen($finQuery)) ;
					}

				  }	
				  else if ($campo == "fun"){
					$query =  "SELECT *	FROM tb_servico WHERE func LIKE '%".$valor."%'";
					if (IsSet($_POST ["ckbDatas"])){
						$query =  $query." AND ". substr($finQuery,6,strlen($finQuery)) ;
					}

			  	}					  									
//			  	echo $query;

			  	$result = mysqli_query($conexao, $query);

				$qtd_lin = $result->num_rows;
				  

				echo"  <div class=\"page_form\" id=\"no_margin\">
						<table class=\"search-table\" id=\"tabItens\" >   
			                <tr>
			                  <th>Cod.</th>
			                  <th>Numero</th>
			                  <th>Emitido por</th>
			                  <th>Func. Resp</th>
			                  <th>Tipo</th>
			                  <th>Data</th>
			                  <th>Status</th>
						  	</tr>";
					        while($fetch = mysqli_fetch_row($result)){

					        	$cod_ped = $fetch[0];
					        	$status = $fetch[6];

                                echo "<tr class='tbl_row'>".
                                         "<td>" .$fetch[0] . "</td>".
								     	 "<td>" .$fetch[1] . "</td>".
								     	 "<td>" .$fetch[2] . "</td>".
								     	 "<td>" .$fetch[3] . "</td>".
								     	 "<td>" .$fetch[4] . "</td>".
								         "<td>" . date('d/m/Y', strtotime($fetch[5])) . "</td>".
                                         "<td>" .$fetch[6] . "</td>";
										  
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