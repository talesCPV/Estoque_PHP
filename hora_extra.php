<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Hora Extra</title>
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
  	  	<p class="logo"> Relógio de Ponto</p> <br>
  	  	<form class="login-form" method="POST" action="#">
  	  		<table class="search-table"  border="0"><tr>
                <td><label> Funcionário: </label></td>
				<td>
					<select name="selStatus" id="selStatus">	
						<optgroup label="Ativos">			
						<option value="ATV" selected> Horista </option>
						<option value="MAT"> Mensalistas </option>
						<option value="HMA"> Todos </option>
						<optgroup label="Inativos">			
						<option value="DEM"> Horista </option>				
						<option value="MDE"> Mensalistas </option>				
						<option value="HMD"> Todos  </option>				
						<optgroup label="Todos">			
						<option value="TDS"> Horista </option>
						<option value="MTD"> Mensalistas </option>
						<option value="HMT"> Todos  </option>
					</select>
				</td>
                <td><input type="text" name="valor" maxlength="30"/></td>
                <td><button class="botao_inline" type="submit">OK</button></td>

            </tr></table>

			<label for="ckbDatas">Início / Final</label>			
			<table class="search-table"  border="0"><tr>
				<?php $m = date('m'); $y = date('Y');?>
				<td> <input type="date" name="data_ini" class="selData" value="<?php echo date('Y-m-d',mktime(0, 0, 0, $m -1 , 26 , $y)); ?>"> </td>
				<td> <input type="date" name="data_fin" class="selData" value="<?php echo date('Y-m-d',mktime(23, 59, 59, $m, 25, $y)); ?>"> </td></tr>
			</table>
    	</form>

	  </div>

	  <?php
	  		function weekday($num){
// 				ARRAY { DIA DA SEMANA, COR DA LINHA, HINT DA TABELA, ENTRADA, SAIDA, QTD HORAS NORMAIS }				  
				  $resp = ["DOM","style='background-color:#F5C5C6;'","title='Domingo'","00:00","00:00",0];
				  switch ($num) {
					case 1:
						$resp = ["SEG","style='background-color:#FFF;'","","07:00","17:00",9];
					break;
					case 2:
						$resp = ["TER","style='background-color:#EEE;;5C6;'","","07:00","17:00",9];
					break;
					case 3:
						$resp = ["QUA","style='background-color:#FFF;'","","07:00","17:00",9];
					break;
					case 4:
						$resp = ["QUI","style='background-color:#EEE;;'","","07:00","17:00",9];
					break;
					case 5:
						$resp = ["SEX","style='background-color:#FFF;'","","07:00","16:00",8];
					break;
					case 6:
						$resp = ["SAB","style='background-color:#F5C5C6;'","title='Sábado'","00:00","00:00",0];
					break;
				  }
				  return $resp;				  
			  }

		    if (IsSet($_POST ["valor"])){
                $valor = $_POST ["valor"];
                $status = $_POST ["selStatus"];
                $inicio = strtotime($_POST ["data_ini"]);
				$final = strtotime($_POST ["data_fin"]);

				include "conecta_mysql.inc";
				if (!$conexao)
					die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

                echo"  <div class=\"page_form\" id=\"no_margin\">
                            <p class=\"logo\" id=\"lblPesq\"> Lançamento de Horas </p> <br>
                            <table class=\"search-tabl\" id=\"tabHoras\" >";


				if ($status == "TDS"){
					$query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%{$valor}%' AND f.id_cargo = c.id AND c.tipo = 'HORA';";
				}else if ($status == "ATV"){
					$query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%{$valor}%' AND f.status='ATIVO'  AND f.id_cargo = c.id AND c.tipo = 'HORA';";
				}else if ($status == "DEM"){
					$query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%{$valor}%' AND f.status='DEMIT'  AND f.id_cargo = c.id AND c.tipo = 'HORA';";
				}else if ($status == "MTD"){
					$query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%{$valor}%' AND f.id_cargo = c.id AND c.tipo = 'MENSAL';";
				}else if ($status == "MAT"){
					$query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%{$valor}%' AND f.status='ATIVO'  AND f.id_cargo = c.id AND c.tipo = 'MENSAL';";
				}else if ($status == "MDE"){
					$query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%{$valor}%' AND f.status='DEMIT'  AND f.id_cargo = c.id AND c.tipo = 'MENSAL';";
				}else if ($status == "HMT"){
					$query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%{$valor}%' AND f.id_cargo = c.id ;";
				}else if ($status == "HMA"){
					$query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%{$valor}%' AND f.status='ATIVO'  AND f.id_cargo = c.id ;";
				}else if ($status == "HMD"){
					$query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%{$valor}%' AND f.status='DEMIT'  AND f.id_cargo = c.id ;";
				}



				$result = mysqli_query($conexao, $query);
				$qtd_func = $result->num_rows;

                $th_func="<th colspan='2'></th>";
				$th_entsai="<th class='center_text' style='width:1%;' colspan='2'>Data</th>";
				$func = [];
				$nome_func = [];
				$h = [];

                while($fetch = mysqli_fetch_row($result)){
					$nome = explode(" ", $fetch[1])[0];
                    $th_func = $th_func . "<th class='center_text' colspan='2' id={$fetch[0]}>{$nome}</th>";
					$th_entsai = $th_entsai . "<th class='center_text'>Ent.</th><th class='center_text'>Sai.</th>";
					$func[] = $fetch[0];
					$nome_func[] = $nome;
					$h[] = 0;
				}
				// junto com $h são os arrays para guardarem as horas extra, adicional noturno, horam normais de cada funcionario
				$an = $h;
				$he_50 = $h;
				$he_100 = $h;
				$he_an = $h;
				$falta = $h;


				echo ("<tr>{$th_func}</tr><tr>{$th_entsai}</tr><th>");

				$d = $inicio;

				while($d <= $final){
					$dw =  weekday(date('w', $d));

					$query =  "SELECT * FROM tb_feriados  WHERE dia=".date("d", $d)." AND mes=".date("m", $d)." ";
					$result = mysqli_query($conexao, $query);
					$feriado = $result->num_rows;

					if($feriado > 0){
						$fetch = mysqli_fetch_row($result);
						$dw[1] = "style='background-color:#F5C5C6;'";
						$dw[2] = "title='{$fetch[4]}'";
						$dw[3] = "00:00";
						$dw[4] = "00:00";
						$dw[5] = 0;
					}


					echo "<tr {$dw[1]} class='tbl_row'><th><a {$dw[2]}>".(date("d/m/Y", $d))."</a></th><th>{$dw[0]}</th>";

					$query =  "SELECT * FROM tb_hora_extra  WHERE entrada like '". date('Y-m-d',$d) ."%'";
					$result = mysqli_query($conexao, $query);

					$he = [];
					while($fetch = mysqli_fetch_row($result)){
						$he[] = $fetch;
					}

					$find_func = false;
					for($i=0; $i<$qtd_func;$i++){
						for($j=0; $j < count($he); $j++){
							if($func[$i] == $he[$j][1]){
								$find_func = true;
								$ent = strtotime($he[$j][2])/3600 ;
								$sai = strtotime($he[$j][3])/3600 ;
								$almoço = 0;
								if ( ($ent <= strtotime(date("Y-m-d 12:00:00", $ent*3600 ))/3600) && ($sai > strtotime(date("Y-m-d 13:00:00", $ent*3600 ))/3600)){
									$almoço = 1;
								}
								$ht =  $sai - $ent - $almoço; // horas trabalhadas no dia
	
								$extra = $ht - $dw[5];
								$noturno = 0;
								$extra_noturno = 0;
								$falta_dia = 0;
								$_22hrs = strtotime(date("Y-m-d 22:00:00", $ent*3600 ))/3600;

								if($ht < $dw[5]){
									$falta_dia = $dw[5] - $ht;
									$extra = 0;

//echo $nome_func[$i]." Falta:".$falta_dia."->". $dw[5] ." - ". $ht  ."<br>";

								}

								if(($ent + $ht + $almoço ) > $_22hrs){
									$noturno = $sai - $_22hrs;

									if($extra > 0){
										if($extra <= $noturno){
											$extra_noturno = $extra;
											$noturno = $noturno - $extra;
											$extra = 0;
										}else{
											$extra_noturno = $noturno;
											$extra = $extra - $noturno;
											$noturno = 0;
										}
											
									}						

								}

								$h[$i] = $h[$i] + $ht - $extra - $extra_noturno;	
								$he_100[$i] = $he_100[$i] + $extra;
								$an[$i] = $an[$i] + $noturno;
								$he_an[$i] = $he_an[$i] + $extra_noturno;
								$falta[$i] = $falta[$i] + $falta_dia;


//								echo $nome_func[$i]." ".date("d/m/Y",$ent*3600)." HT:". $ht." HE:".$extra." NOT:".$noturno." HE+NOT:".$extra_noturno." Falta:".$falta_dia.   "<br>";
								
								echo "<td class='center_text'>".date("H:i",$ent*3600)."</td><td class='center_text'>".date("H:i",$sai*3600)."</td>"; 
								break;
							}

						}
						if(!$find_func){
							if($d < strtotime(date('Y-m-d'))){
								$h[$i] = $h[$i] + $dw[5];
								echo "<td class='center_text'>{$dw[3]}</td><td class='center_text'>{$dw[4]}</td>"; 
							}else{
								echo "<td class='center_text'>00:00</td><td class='center_text'>00:00</td>"; 
							}
						}
						
						$find_func = false;
					}

					echo"</tr>";
					$d = date(strtotime('+1 day', $d));
				}
				
	echo"			</table> 
				  </div>";

				  if($inicio <= $final){
					echo"
						<div class='page_form' id='no_margin'>
						<form class='login-form' method='POST' action='pdf_he.php'>
						<p class='logo' id='lblPesq'> Resumo </p> <br>
						<table class='search-table'> 
							<tr class='center_text'>
								<th>Nome</th>
								<th>Horas</th>
								<th>Faltas</th>
								<th>Ad.Not</th>
								<th>HE 50%</th>
								<th>HE 100%</th>
								<th>HE+Ad.Not.</th>
							</tr>";
					for($i=0; $i<count($nome_func);$i++){
						echo "<tr class='center_text'><th>{$nome_func[$i]}</th><td>".number_format($h[$i], 2, '.', '')."</td><td>".number_format($falta[$i], 2, '.', '')."</td><td>".number_format($an[$i], 2, '.', '')."</td><td>".number_format($he_50[$i], 2, '.', '')."</td><td>".number_format($he_100[$i], 2, '.', '')."</td><td>".number_format($he_an[$i], 2, '.', '')."</td></tr>";
						echo "<input type='hidden' name='nome_{$i}' value='". $nome_func[$i]."'>
							  <input type='hidden' name='horas_{$i}' value='". $h[$i]."'>
							  <input type='hidden' name='faltas_{$i}' value='". $falta[$i]."'>
							  <input type='hidden' name='adn_{$i}' value='". $an[$i]."'>
							  <input type='hidden' name='he50_{$i}' value='". $he_50[$i]."'>
							  <input type='hidden' name='he100_{$i}' value='". $he_100[$i]."'>
							  <input type='hidden' name='headn_{$i}' value='". $he_an[$i]."'>
						";					
					}

						echo"</table>";
						
						echo "<br>
								<button class='botao_inline' type='submit'>Relatório</button>	
								<input type='hidden' name='inicio' value='{$inicio}'>
								<input type='hidden' name='final' value='{$final}'>																								  
							</form>";

						echo"</div>
					";
				  }


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