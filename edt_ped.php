<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Pedido de Compra</title>
    <link rel="stylesheet" type="text/css"  href="estilo.css" />
    <script src="js/funcoes.js"></script>

</head>
<body>
  <header>

    <?php
      include "menu.inc";
      if (IsSet($_POST ["cod_ped"])){

        $cod_ped = $_POST ["cod_ped"];

        include "conecta_mysql.inc";
        if (!$conexao)
          die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());


        $query = "SELECT * FROM tb_pedido WHERE id = ".$cod_ped."; ";
          
        $result = mysqli_query($conexao, $query);

        while($fetch = mysqli_fetch_row($result)){
          $id = $fetch[0];
          $id_emp = $fetch[1];
          $data_ped = $fetch[2];
          $data_ent = $fetch[3];
          $resp = $fetch[4];
          $comp = $fetch[5];
          $num_ped = $fetch[6];
          $status = $fetch[7];
          $desconto = $fetch[8];
          $cond_pgto = $fetch[9];
          $obs = $fetch[10];
          $origem = $fetch[11];

        }


      }

      $conexao->close();

    ?>
  </header>

<div class="page_container">  
  <div class="page_form">
    <p class="logo"> Editar Pedido de Compra</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="save_ped.php" onsubmit="return validaCampo(); return false;">

      <label> Num. Pedido * </label>
      <input type="text" name="num_ped" maxlength="60" value="<?php echo $num_ped; ?>" />
      <label> Cliente </label>

      <select name="cliente" id="emp">";

      <?php  

        include "conecta_mysql.inc";
        if (!$conexao)
          die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());


        $query = "SELECT * from tb_empresa where tipo = \"CLI\";";
        $result = mysqli_query($conexao, $query);


        while($fetch = mysqli_fetch_row($result)){
            echo $fetch[1] . "<br>";
            if ($fetch[0] == $id_emp){
              echo "<option selected value=\"". $fetch[0] ."\">". $fetch[1] ."</option>";
            }else{
              echo "<option value=\"". $fetch[0] ."\">". $fetch[1] ."</option>";
            }
        }

          $conexao->close();
      ?>

      </select> </td>

      <label> Origem </label>
      <select name="selOrigem">
        <option value="FUN" <?php if($origem == 'FUN'){echo('selected');} ?>  > Funilaria e Pintura </option>
        <option value="SAN" <?php if($origem == 'SAN'){echo('selected');} ?> > Sanfonados </option>
        <option value="OUT" <?php if($origem == 'OUT'){echo('selected');} ?> > Outros </option>
      </select>
      <label> Data </label>
      <input type="date" name="data_ped" value="<?php echo $data_ped; ?>">
      <label> Data de Entrega </label>
      <input type="date" name="data_ent" value="<?php echo $data_ent; ?>">
      <label> Comprador</label>
      <input type="text" name="comprador" maxlength="30" value="<?php echo $comp; ?>"/>
      <label> Desconto (R$)</label>
      <input type="text" name="desconto" maxlength="30" value="<?php echo $desconto; ?>" onkeyup="return money(this)" />
      <label> Condicao de pgto</label>
      <input type="text" name="pgto" maxlength="30" value="<?php echo $cond_pgto; ?>"/>
      <label> Obs:</label>
      <input type="text" name="obs" maxlength="30" value="<?php echo $obs; ?>"/>
      <label> Emitido por</label>
      <input type="text" name="responsavel" value="<?php echo $resp; ?>" readonly/>
      <input type="hidden" name="novo" value="0">
      <input type="hidden" name="id_ped" value="<?php echo $id; ?>">

      <button type="submit">Salvar</button>

    </form>
  </div>
</div>

</body>
</html>