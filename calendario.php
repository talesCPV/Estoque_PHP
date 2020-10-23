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

                        <p class=\"logo\" id=\"lblPesq\"> ".strtoupper($user)." - Mês {$month}/{$year} </p> <br>
						<table class=\"search-tabl\" id=\"tabHoras\" >   
			                <tr>
			                  <th style='width: 5%;'>Dia</th>
			                  <th>Agenda</th>
							  </tr>";

                            while($next_month == $month){

                            $look_day = $start_day->format('Y-m-d');
                            $show_day = $start_day->format('d/m/Y');
                            
                            $start_day->modify('+1 days');
                            $next_month = $start_day->format('m');
                            $day = $start_day->format('d');
                            $weekday = $start_day->format('w');


                            $query =  "SELECT * FROM tb_feriados  WHERE dia=".substr($look_day, 8, 2)." AND mes=".substr($look_day, 5, 2)." ";
                            $result = mysqli_query($conexao, $query);
                            $feriado = $result->num_rows;

                            $query =  "SELECT * from tb_calendario WHERE id_user = '{$cod_user}' AND data_agd = '{$look_day}'";

                            $result = mysqli_query($conexao, $query);
                            $fetch = mysqli_fetch_row($result);
                            
                            $id = $fetch[0];
                            $dia = $fetch[1];
                            $obs = $fetch[2];
                            $hint = $fetch[3];                  
                  
                            if($day % 2 == 0){
                              $backcolor = '#EEE';
                            }else{
                              $backcolor = '#FFF';
                            }

                            if($weekday == 0 || $weekday == 1 || $feriado > 0){
                              $backcolor = "#F5C5C6";
                            }

                            if($result->num_rows > 0){
                              $backcolor = "#BCD0FE";
                            }


                            echo "<tr class='tbl_row' style='white-space: pre-line; background-color:{$backcolor}'; ><th title='{$hint}'>{$show_day}</th><td style='display: none;'>{$id}</td><td style='display: none;'>{$look_day}</td><td>{$obs}</td><td style='display: none;'>{$hint}</td></tr>";
//                            echo "<tr class='tbl_row'  style='white-space: pre-line;'><td style='display: none;'> {$id}</td><td style='display: none;'> {$look_day}</td><th>{$days_week[$i]}</th><td>{$frente}</td><td>{$suporte}</td></tr>";
                          }            

						    echo"
                </table> 

              </div>
              ";
            $conexao->close();
            


        }
        if (IsSet($_POST ["hdn_save"])){
          $save_opt = $_POST ["hdn_save"];
          
          if($save_opt == 1){
            $obs = $_POST ["txtObs"];
            $hint = $_POST ["txtHint"];
            $data = $_POST ["hdn_data"];

            include "conecta_mysql.inc";
            if (!$conexao)
            die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

            $query =  "INSERT INTO tb_calendario VALUES ({$cod_user}, '{$data}','{$obs}','{$hint}') ON DUPLICATE KEY UPDATE
            obs = '{$obs}', hint = '{$hint}' ";

            $result = mysqli_query($conexao, $query);

            $conexao->close();

          }
          if($save_opt == 2){
            $data = $_POST ["hdn_data"];

            include "conecta_mysql.inc";
            if (!$conexao)
            die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

            $query =  "DELETE FROM tb_calendario WHERE id_user = '{$cod_user}' AND data_agd = '{$data}' ";
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