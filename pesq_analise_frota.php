<?php
	setcookie("cod_serv");
  	include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Análise de Frota</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
    <script src="js/pesq_analise_frota.js"></script>
</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Análise de Frota</p> <br>
  	  	<form class="login-form" method="POST" action="#">
  	  		<table class="search-table"  border="0"><tr><td>
  		  <label> Busca por: </label> </td><td>
	      <select name="campo" id="selPesqPed">		
	        <option value="todos">Todos</option>
	        <option value="cli">Cliente</option>
	        <option value="codcli">Código do Cliente</option>
	        <option value="num">Numero do Carro</option>
	        <option value="cod">Cod.</option>
	    </select></td>
	    <td><input type="text" name="valor" maxlength="12"/></td>
		<td><select name="selexec" id="selExec">
			<option value="1">Todos</option>
			<option value="2">Executados</option>
			<option value="3">Não Executados</option>
		</select> </td>
      <td><button id="botao_inline" type="submit">OK</button></td>
	</tr>  </table>
				
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
			  $query;
		    if (IsSet($_POST ["campo"])){

				include "conecta_mysql.inc";
				if (!$conexao)
					die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

			  	$campo = $_POST ["campo"];
				$valor = $_POST ["valor"];
				$exec = $_POST ["selexec"];
				$data_exec = $_POST ["data_exec"];
				$data_fin = $_POST ["data_fin"];
				$check = false;
				$cliente = "";

                $query_opt = "";


                if ($campo == "cli"){
                    $query_opt = $query_opt . " AND e.nome LIKE '%".$valor."%'";
                }else if($campo == "codcli"){
                    $query_opt = $query_opt . " AND e.id LIKE '%".$valor."%'";
                }else if($campo == "cod"){
                    $query_opt = $query_opt . " AND a.id = '".$valor."'";
                }else if($campo == "num"){
					$arr = explode(',',$valor);
					if(count($arr) > 0){
						$query_opt = $query_opt . " AND (a.num_carro LIKE '%{$arr[0]}%'";

						for($i = 1; $i < count($arr); $i++){
							$query_opt = $query_opt . " OR a.num_carro LIKE '%{$arr[$i]}%'";
						}
						$query_opt = $query_opt . ')';
					}

				}
				
				if ($exec == "2"){				
					$query_opt = $query_opt . " AND  a.exec ";	
				} else if ($exec == "3"){
					$query_opt = $query_opt . " AND  NOT a.exec  ";	

				}

                if (IsSet($_POST ["ckbDatas"])){
					$query_opt = $query_opt . " AND a.data_analise >= '". $data_exec ."' AND a.data_analise <= '". $data_fin ."'";
					$check = true;
                }
            
                  $query_opt = $query_opt . " order by a.data_analise desc";

                  $query =  "SELECT a.id, e.fantasia, a.num_carro, a.data_analise, a.func, a.exec, a.obs, e.id, a.valor 
                            FROM tb_analise_frota as a 
                            INNER JOIN tb_empresa as e 
                            ON a.id_emp = e.id " . $query_opt;                                 

                			  	
				$_SESSION["query"] = $query;
//				echo $query;

			  	$result = mysqli_query($conexao, $query);

				$qtd_lin = $result->num_rows;
				$cont = 0;

				echo"  <div class=\"page_form\" id=\"no_margin\">
						<table class=\"search-table\" id=\"tabItens\" >   
			                <tr>
			                  <th>Cod.</th>
			                  <th>Cliente</th>
							  <th>Carro</th>
							  <th>Visita</th>
			                  <th>Exec.</th>
			                  <th>Valor</th>
						  	</tr>";
					        while($fetch = mysqli_fetch_row($result)){
								$cont++;
					        	$cliente = $fetch[1];
								$cod_cli = $fetch[7];
								$num_carro = $fetch[2];
								$valor = $fetch[8];
								$func = $fetch[4];
								$status = 'NÃO';
								if($fetch[5]){
									$status = "SIM";
								}

                                echo "<tr class='tbl_row'>".
                                         "<td> " .$fetch[0] . "</td>".
								     	 "<td>" .$cod_cli." - ". $cliente . "</td>".
								     	 "<td>" .$fetch[2] . "</td>".
								     	 "<td>" .date('d/m/Y', strtotime($fetch[3]))  . "</td>".
     									 "<td style='display: none;'>" . $fetch[6] . "</td>".
								     	 "<td>" . $status . "</td>".
								         "<td>" . money_format('%=*(#0.2n',$valor) . "</td>".
								         "<td style='display: none;'>" . $cod_cli . "</td>".
                                         "<td style='display: none;'>" . $func . "</td>";										  
					        }

							echo"
							<tr><td colspan='6'>*************************************</td></tr>
							<tr><th <td colspan='6'></td>${cont} REGISTROS ENCONTRADOS</th></tr>
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
									<form class=\"login-form\" method=\"POST\" action=\"pdf_orc_serv.php\">
										<input type='text' name='titulo' placeholder='Titulo do Relatório' value='Análise de Frota'/>
										OBS:
										<textarea id=\"edtObs\" name=\"edtObs\"> </textarea>
										<input type=\"hidden\" name=\"origem\" value=\"ANALISE\">
										<input type=\"hidden\" name=\"campo\" value=\"". $campo ."\">
										<input type=\"hidden\" name=\"query\" value=\"". $query ."\">
										<input type=\"hidden\" name=\"valor\" value=\"". $_POST ["valor"] ."\">
										<input type=\"hidden\" name=\"func\" value=\"". $func ."\">
										<input type=\"hidden\" name=\"exec\" value=\"". $exec ."\">
				  	  					<input type=\"hidden\" name=\"num_carro\" value=\"". $num_carro ."\">
										<input type=\"hidden\" name=\"pedido\" value=\"". $status ."\">
										<input type=\"hidden\" name=\"check\" value=\"". $check ."\">
				  	  					<input type=\"hidden\" name=\"cod_cli\" value=\"". $cod_cli ."\">
				  	  					<input type=\"hidden\" name=\"ini\" value=\"". $data_exec ."\">
										<input type=\"hidden\" name=\"fin\" value=\"". $data_fin ."\">
      									<button id=\"botao_inline\" type=\"submit\" style=\" margin: 0 10px;\">Orçamento</button>
									  </form>
									  <form class=\"login-form\" id='frmRelat' method=\"POST\" action=\"pdf_relat_ana.php\">
									  	<input type=\"hidden\" name=\"query\" value=\"". $query ."\">				
										<button id='btnRelat' class='btnRelat' type='submit' style=' margin: 0 10px;'>Relatorio</button>										
									</form>
									<script>
									const btnRelat = document.querySelector('.btnRelat');								
									btnRelat.addEventListener('click',(event)=>{										
										event.preventDefault();
										const txt = document.getElementById('edtObs')
										const frmRelat = document.getElementById('frmRelat')
										frmRelat.appendChild(txt);
										frmRelat.submit()
									})
									</script>
								</td>
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