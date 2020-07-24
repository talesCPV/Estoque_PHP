<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Agenda</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="js/funcoes.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
      // RECEBENDO OS DADOS PREENCHIDOS DO FORMULÁRIO !
      if (IsSet($_POST ["cod_agenda"])){

        $cod_agenda = $_POST ["cod_agenda"];

        include "conecta_mysql.inc";
        if (!$conexao)
          die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());


        $query = "SELECT * FROM tb_agenda WHERE id = ".$cod_agenda."; ";
          
        $result = mysqli_query($conexao, $query);

        while($fetch = mysqli_fetch_row($result)){

                    $id_emp = $fetch[1];
                    $nome = $fetch[2];
                    $email = $fetch[3];
                    $dep = $fetch[4];
                    $cel1 = $fetch[5];
                    $cel2 = $fetch[6];
        }

      }

      $conexao->close();

    ?>
  </header>

<div class="page_container">  
  <div class="page_form">
    <p class="logo"> Agenda de Contatos</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="save_agenda.php" onsubmit="return validaCampo(new Array(cadastro.nome)); return false;">

      <label> Empresa </label>
      <?php  

      include "conecta_mysql.inc";
      if (!$conexao)
        die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

        $query = "SELECT * from tb_empresa";
        $result = mysqli_query($conexao, $query);

          echo "<td><select name=\"forn\" id=\"forn\">";


        while($fetch = mysqli_fetch_row($result)){
            if ($id_emp == $fetch[0]){
              echo "<option selected=\"selected\" value=\"". $fetch[0] ."\">". $fetch[1] ."</option>";
            }else{
              echo "<option value=\"". $fetch[0] ."\">". $fetch[1] ."</option>";
            }
        }

            echo "</select> </td>";
          $conexao->close();
      ?>
      <label> Nome * </label>
      <input type="text" name="nome" maxlength="40" value="<?php echo "$nome" ?>" />
      <label> Departamento </label>
      <input type="text" name="dep" id="Lower_Case" maxlength="14" value="<?php echo "$dep" ?>"/>
      <label> Email </label>
      <input type="text" name="email" id="Lower_Case" maxlength="70" value="<?php echo "$email" ?>"/>
      <label> Celular </label>
      <input type="text" name="fone1" maxlength="14" value="<?php echo "$cel1" ?>"/>
      <label> Telefone Fixo </label>
      <input type="text" name="fone2" maxlength="14" value="<?php echo "$cel2" ?>"/>
      <input type="hidden" name="cod_agenda" value="<?php echo "$cod_agenda" ?>">      
      <button type="submit">Salvar</button>

    </form>
  </div>
</div>

</body>
</html>