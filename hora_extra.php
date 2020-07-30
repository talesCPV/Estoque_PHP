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


                $query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%{$valor}%' AND f.status='ATIVO'  AND f.id_cargo = c.id;";
				$result = mysqli_query($conexao, $query);
				$qtd_func = $result->num_rows;

                $th_func="<th></th>";
				$th_entsai="<th class='center_text' style='width:1%;'>Data</th>";
				$nomes = [];
                while($fetch = mysqli_fetch_row($result)){
					$prim_nome = explode(" ", $fetch[1])[0];
					$nomes[] = $prim_nome;	
                    $th_func = $th_func . "<th class='center_text' colspan='2' id={$fetch[0]}>{$prim_nome}</th>";
                    $th_entsai = $th_entsai . "<th class='center_text'>Ent.</th><th class='center_text'>Sai.</th>";
                }

				echo ("<tr>".$th_func."</tr><tr>".$th_entsai."</tr>");

				$d = $inicio;
				while($d <= $final){
					$dw = date('w', $d);
					if($dw == 0 || $dw == 6){
						echo "<tr style='background-color:#F5C5C6;' class='tbl_row'>";
					}else if($dw == 2 || $dw == 4){
						echo "<tr style='background-color:#EEE;' class='tbl_row'>";
					}else{
						echo "<tr style='background-color:#FFF;' class='tbl_row'>";
					}
					echo "<th>".(date("d/m/Y", $d))."</th>";
					for($i=0; $i<$qtd_func;$i++){echo "<td class='center_text'>{$dw}</td><td class='center_text'>00:00</td>"; }
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