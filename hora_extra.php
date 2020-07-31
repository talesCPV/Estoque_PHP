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
                <td><input type="text" name="valor" maxlength="30"/></td>
                <td><button class="botao_inline" type="submit">OK</button></td>
                <td><button class="botao_inline" id="btn_NovaHora">Novo</button></td>
            </tr></table>

			<label for="ckbDatas">Início / Final</label>
			
			<table class="search-table"  border="0"><tr>
				<?php $m = date('m'); $y = date('Y');?>
				<td> <input type="date" name="data_ini" class="selData" value="<?php echo date('Y-m-d',mktime(0, 0, 0, $m -1 , 26 , $y)); ?>"> </td>
				<td> <input type="date" name="data_fin" class="selData" value="<?php echo date('Y-m-d',mktime(23, 59, 59, $m, 25, $y)); ?>"> </td></tr> </table>
    	</form>
	  </div>

	  <?php
	  		function weekday($num){
				  $resp = ["DOM","style='background-color:#F5C5C6;'"];
				  switch ($num) {
					case 1:
						$resp = ["SEG","style='background-color:#FFF;'"];
					break;
					case 2:
						$resp = ["TER","style='background-color:#EEE;;5C6;'"];
					break;
					case 3:
						$resp = ["QUA","style='background-color:#FFF;'"];
					break;
					case 4:
						$resp = ["QUI","style='background-color:#EEE;;'"];
					break;
					case 5:
						$resp = ["SEX","style='background-color:#FFF;'"];
					break;
					case 6:
						$resp = ["SAB","style='background-color:#F5C5C6;'"];
					break;
				  }
				  return $resp;				  
			  }

		    if (IsSet($_POST ["valor"])){
                $valor = $_POST ["valor"];
                $inicio = strtotime($_POST ["data_ini"]);
				$final = strtotime($_POST ["data_fin"]);

				include "conecta_mysql.inc";
				if (!$conexao)
					die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

                echo"  <div class=\"page_form\" id=\"no_margin\">
                            <p class=\"logo\" id=\"lblPesq\"> Lançamento de Horas </p> <br>
                            <table class=\"search-tabl\" id=\"tabHoras\" >";


                $query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%{$valor}%' AND f.status='ATIVO'  AND f.id_cargo = c.id AND c.tipo = 'HORA';";
				$result = mysqli_query($conexao, $query);
				$qtd_func = $result->num_rows;

                $th_func="<th colspan='2'></th>";
				$th_entsai="<th class='center_text' style='width:1%;' colspan='2'>Data</th>";
				$func = [];
                while($fetch = mysqli_fetch_row($result)){
					$nome = explode(" ", $fetch[1])[0];
                    $th_func = $th_func . "<th class='center_text' colspan='2' id={$fetch[0]}>{$nome}</th>";
					$th_entsai = $th_entsai . "<th class='center_text'>Ent.</th><th class='center_text'>Sai.</th>";
					$func[] = $fetch[0];
                }
/*
				for($i=0; $i<$qtd_func;$i++){
					echo $func[$i]."|";
				}
*/				
//echo "<br>";
				echo ("<tr>{$th_func}</tr><tr>{$th_entsai}</tr><th>");

				$d = $inicio;
//				echo date('Y-m-d',$d);
				while($d <= $final){
					$dw =  weekday(date('w', $d));
					echo "<tr {$dw[1]} class='tbl_row'><th>".(date("d/m/Y", $d))."</th><th>{$dw[0]}</th>";

					$query =  "SELECT * FROM tb_hora_extra  WHERE entrada like '". date('Y-m-d',$d) ."%'";
					$result = mysqli_query($conexao, $query);

					$he = [];
					while($fetch = mysqli_fetch_row($result)){
						$he[] = $fetch;
					}

//					echo $result->num_rows;
					$find_func = false;
					for($i=0; $i<$qtd_func;$i++){
//						echo '->'.$func[$i]."<-";	

//						while($fetch = mysqli_fetch_row($result)){
						for($j=0; $j < count($he); $j++){
//							echo $func[$i].'=='.$he[$j][1]." ";
							if($func[$i] == $he[$j][1]){
								$find_func = true;
								echo "<td class='center_text'>".substr($he[$j][2], -8, 5)."</td><td class='center_text'>".substr($he[$j][3], -8, 5)."</td>"; 
								break;
							}

						}
						if(!$find_func){
							echo "<td class='center_text'>00:00</td><td class='center_text'>00:00</td>"; 
						}
						$find_func = false;
					}
//					echo "<br>";
					echo"</tr>";
					$d = date(strtotime('+1 day', $d));
				}
				

                $query =  "SELECT f.nome, h.entrada. h.saida FROM tb_funcionario AS f INNER JOIN tb_hora_extra AS h ON h.id_func = f.id AND f.nome like '%{$valor}%'";
			  	$result = mysqli_query($conexao, $query);
/*                            
					        while($fetch = mysqli_fetch_row($result)){


								echo "<tr class='tbl_row' id='".$fetch[0]."'><td>" .$fetch[0] . "</td>";
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
*/
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