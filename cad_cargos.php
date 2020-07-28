<?php
	setcookie("cod_serv");
  	include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Pedido de Compra</title>
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
  	  	<p class="logo"> Cargos e Funções</p> <br>
      <button id="btn_NovoCargo">Novo</button>

	  </div>
	  <?php

        include "conecta_mysql.inc";
        if (!$conexao)
            die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

            $query =  "SELECT *	FROM tb_cargos;";

        $result = mysqli_query($conexao, $query);
				  

        echo"  <div class=\"page_form\" id=\"no_margin\">
                <table class=\"search-table\" id=\"tabItens\" >   
                    <tr>
                        <th>Cod.</th>
                        <th>Cargo</th>
                        <th>Tipo</th>
                        <th>Salario</th>
                    </tr>";
                    while($fetch = mysqli_fetch_row($result)){

                        $cod_ped = $fetch[0];
                        $status = $fetch[6];

                        echo "<tr class='tbl_row'>".
                                    "<td>" .$fetch[0] . "</td>".
                                    "<td>" .$fetch[1] . "</td>";
                        if($fetch[3] == 'HORA'){
                            echo   "<td>HORISTA</td>";
                        }else{
                            echo   "<td>MENSALISTA</td>";
                        }
                        echo        "<td>" .money_format('%=*(#0.2n', $fetch[2]). "</td></tr>";
                                    
                    }
        echo"    </table> 
                </div>";
				$conexao->close();
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