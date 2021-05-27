<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recibos</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js" integrity="sha384-THVO/sM0mFD9h7dfSndI6TS0PgAGavwKvB5hAxRRvc0o9cPLohB0wb/PTA7LdUHs" crossorigin="anonymous"></script>


</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >
  <header>
    <?php
      include "menu.inc";
      include "conecta_mysql.inc";
      if (!$conexao)
          die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());
    ?>
  </header>
  <div class="page_container">  
  	    <div class="page_form">
            <p class="logo"> Recido de Pagamento</p> <br>
            <form class="login-form" method="POST" action="#">
                <table class="search-table"  ><tr>
                    <td><label> Empresa: </label> </td><td>

                    <?php
                        $query = "SELECT * from tb_empresa order by fantasia";
                        $result = mysqli_query($conexao, $query);

                        echo "<td><select name=\"cliente\" id=\"emp\">";

                        while($fetch = mysqli_fetch_row($result)){
                            echo $fetch[1] . "<br>";
                            echo "<option value=\"". $fetch[0] ."\">". str_pad($fetch[0],3,'0', STR_PAD_LEFT)." - ". strtoupper($fetch[12]) ."</option>";
                        }

                        echo "</select> </td>";
                        $conexao->close();            
                        
                    ?>
                    </td>
                    <td><label> Valor: R$</label></td>
                    <td><input type="text" id="edtValor" onkeyup="return float_number(this)" value="0"></td>
                    <td><button class="botao_inline" id="btnGerar" type="submit">Gerar</button></td>
                    </tr>                        
                </table>
            </form>
	    </div>


  </div>

<div class="overlay"> 
  <div class="popup">
    <h2 id="popTitle"></h2>
    <div class="close" >&times</div>
    <div class="content"></div>
  </div>
</div>

<script src="js/recibos.js"></script>

</body>
</html>