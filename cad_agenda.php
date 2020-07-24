<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Agenda</title>
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
    <p class="logo"> Agenda </p> <br>
    <form class="login-form" name="cadastro" method="POST" action="save_agenda.php" id="frmSaveAgenda" >
      <label> Empresa </label>
      <?php  

      include "conecta_mysql.inc";
      if (!$conexao)
        die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

        $query = "SELECT * from tb_empresa order by nome";
        $result = mysqli_query($conexao, $query);

          echo "<td><select name=\"forn\" id=\"forn\">";


        while($fetch = mysqli_fetch_row($result)){
            echo $fetch[1] . "<br>";
            echo "<option value=\"". $fetch[0] ."\">". $fetch[1] ."</option>";
        }

            echo "</select> </td>";
          $conexao->close();
      ?>
      <label> Nome * </label>
      <input type="text" name="nome" maxlength="40" id="edtNome"/>
      <label> Departamento </label>
      <input type="text" name="dep" maxlength="15"/>
      <label> Email </label>
      <input type="text" name="email" id="Lower_Case" maxlength="70"/>
      <label> Celular </label>
      <input type="text" name="fone1" class="checkFone" maxlength="15"/>
      <label> Telefone Fixo </label>
      <input type="text" name="fone2" class="checkFone" maxlength="15"/>
    </form>
    <button type="submit" id="btnSaveAgenda" >Cadastrar</button>
  </div>
</div>

</body>
</html>