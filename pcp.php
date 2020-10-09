<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>PCP</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
    <script src="js/pcp.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Planejamento e Controle de Produção</p> <br>
  	  	<form class="login-form" method="POST" action="#">
			
			<table class="search-table"  border="0"><tr>
				<td> <label> Selecione a Semana</label> </td>
				<td> <input type="date" name="data_pcp" class="selData" value="<?php echo date('Y-m-d'); ?>"> </td>           
        <td> <button class="botao_inline" type="submit">OK</button> </td>
            <input type="hidden" name="check" value="1" >
            </tr> 
      </table>
		
    	</form>
	  </div>

	  <?php


		    if (IsSet($_POST ["check"])){

				include "conecta_mysql.inc";
				if (!$conexao)
					die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());


                $data_pcp =   new DateTime($_POST ["data_pcp"]);
                  
                $week = $data_pcp->format("W");
                $year = $data_pcp->format("Y");

                $dto = new DateTime();
                $dto->setISODate($year, $week);

                $days_week = array("SEG","TER","QUA","QUI","SEX","SAB","DOM");
                $start_day = $dto->format('d/m/Y');
                $new_dto = new DateTime($dto->format('Y-m-d'));
                $new_dto->modify('+6 days');
                $end_day = $new_dto->format('d/m/Y');


                echo"  <div class=\"page_form\" id=\"no_margin\">

                        <p class=\"logo\" id=\"lblPesq\"> PCP de {$start_day} até {$end_day} </p> <br>
						<table class=\"search-table\" id=\"tabItens\" >   
			                <tr>
			                  <th>Dia</th>
			                  <th>Equipe de Frente</th>
			                  <th>Equipe Suporte</th>
			                  <th>Costura</th>
			                  <th>Montagem</th>
							  </tr>";

                          for($i=0; $i < 7; $i++){
                            $look_day = $dto->format('Y-m-d');
                            
                            $dto->modify('+1 days');

                            $query =  "SELECT * from tb_pcp WHERE data_serv = '{$look_day}'";

                            $result = mysqli_query($conexao, $query);
                            $fetch = mysqli_fetch_row($result);

                            $id = $fetch[0];
                            $dia = $fetch[1];
                            $frente = $fetch[2];
                            $suporte = $fetch[3];
                            $costura = $fetch[4];
                            $montagem = $fetch[5];                                
                                            

                            echo "<tr class='tbl_row'  style='white-space: pre-line;'><td style='display: none;'> {$id}</td><td style='display: none;'> {$look_day}</td><th>{$days_week[$i]}</th><td>{$frente}</td><td>{$suporte}</td><td>{$costura}</td><td>{$montagem}</td></tr>";
                          }            

						    echo"
                </table> 

              </div>
              ";
            $conexao->close();
            

            echo"  <div class=\"page_form\" id=\"no_margin\">

                    <form class='login-form' method='POST' action='pdf_pcp.php'>                                          
                        <button class='botao_inline' type='submit'>Imprimir</button>
                        <input type='hidden' name='hdnStart' value='{$new_dto->format('Y-m-d')}' >
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