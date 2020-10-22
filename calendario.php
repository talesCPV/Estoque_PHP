<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Calendário</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
    <script src="js/calendario.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Agenda Pessoal</p> <br>
  	  	<form class="login-form" method="POST" action="#">
			
			<table class="search-table"  border="0"><tr>
				<td> <label> Selecione o Mês/Ano</label> </td>
				<td> <input type="date" name="data_pcp" class="selData" value="<?php echo date('Y-m-d'); ?>"> </td>           
        <td> <button class="botao_inline" type="submit">OK</button> </td>
            <input type="hidden" name="check" value="1" >
            </tr> 
      </table>
		
    	</form>
	  </div>

	  <?php
        if (IsSet($_COOKIE["cod_user"])){
            $cod_user = $_COOKIE["cod_user"];
            $user = $_COOKIE["usuario"];
        }

		    if (IsSet($_POST ["check"])){

				include "conecta_mysql.inc";
				if (!$conexao)
					die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());


                $data_pcp =   new DateTime($_POST ["data_pcp"]);
                $start_day = new DateTime($data_pcp->format("Y-m-1"));
                  
                $month = $data_pcp->format('m');
                $next_month = $start_day->format('m');
                $year = $data_pcp->format("Y");

                echo"  <div class=\"page_form\" id=\"no_margin\">

                        <p class=\"logo\" id=\"lblPesq\"> ".strtoupper($user)." - {$month}/{$year} </p> <br>
						<table class=\"search-table\" id=\"tabItens\" >   
			                <tr>
			                  <th style='width: 5%;'>Dia</th>
			                  <th>Agenda</th>
							  </tr>";

                            while($next_month == $month){

                            $look_day = $start_day->format('Y-m-d');
                            $show_day = $start_day->format('d/m/Y');
                            
                            $start_day->modify('+1 days');
                            $next_month = $start_day->format('m');


                            $query =  "SELECT * from tb_calendario WHERE id_user = '{$cod_user}' AND data_agd = '{$look_day}'";

                            $result = mysqli_query($conexao, $query);
                            $fetch = mysqli_fetch_row($result);

                            $id = $fetch[0];
                            $dia = $fetch[1];
                            $obs = $fetch[2];
                            $hint = $fetch[3];
                            echo "<tr class='tbl_row'><th>{$show_day}</th><td style='display: none;'>{$id}</td><td style='display: none;'>{$dia}</td><td>{$obs}</td><td style='display: none;'>{$hint}</td></tr>";
//                            echo "<tr class='tbl_row'  style='white-space: pre-line;'><td style='display: none;'> {$id}</td><td style='display: none;'> {$look_day}</td><th>{$days_week[$i]}</th><td>{$frente}</td><td>{$suporte}</td></tr>";
                          }            

						    echo"
                </table> 

              </div>
              ";
            $conexao->close();
            

            echo"  <div class=\"page_form\" id=\"no_margin\">

                    <form class='login-form' method='POST' action='pdf_pcp.php'>                                          
                        <button class='botao_inline' type='submit'>Imprimir</button>
                        <input type='hidden' name='hdnStart' value='' >
                    </form>

                  </div>";

        }
        if (IsSet($_POST ["hdn_save"])){
          $save_opt = $_POST ["hdn_save"];
          
          if($save_opt == 1){
            $frente = $_POST ["txtFrente"];
            $suporte = $_POST ["txtSuporte"];
            $costura = $_POST ["txtCostura"];
            $montagem = $_POST ["txtMontagem"];
            $data = $_POST ["hdn_data"];

            include "conecta_mysql.inc";
            if (!$conexao)
            die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());


            $query =  "INSERT INTO tb_pcp VALUES (DEFAULT, '{$data}','{$frente}','{$suporte}','{$costura}','{$montagem}') ON DUPLICATE KEY UPDATE
            frente = '{$frente}', suporte = '{$suporte}', costura = '{$costura}', montagem = '{$montagem}' ";

            $result = mysqli_query($conexao, $query);

            $conexao->close();


          }


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