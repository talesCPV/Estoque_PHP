<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Recibos e Vales</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
    <!--<script src="js/funcoes.js"></script> -->
</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>





  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Vales</p> <br>
	  </div>
	  <?php

        include "conecta_mysql.inc";
        if (!$conexao)
            die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

            $query =  "SELECT *	FROM tb_funcionario ORDER BY nome;";

        $result = mysqli_query($conexao, $query);
				  

        echo"  <div class=\"page_form\" id=\"no_margin\">
                <table class=\"search-table\" id=\"tabChoise\" >   
                    <tr>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Obs.</th>
                    </tr>";
                    while($fetch = mysqli_fetch_row($result)){
                        echo "<tr class='tbl_row' >".
                                    "<td style='display: none;'>" .$fetch[0] . "</td>".
                                    "<td>" . $fetch[1] . "</td>".
                                    "<td>" .money_format('%=*(#0.2n', $fetch[15]). "</td>".
                                    "<td>" . $fetch[16] . "</td>";
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

	<script src="js/vales.js"></script>


</body>
</html>