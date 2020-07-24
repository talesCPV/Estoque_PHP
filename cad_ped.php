<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Pedido de Compra</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
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
    <p class="logo"> Cadastrar Pedido de Compra</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="save_ped.php">
      <?php  

      include "conecta_mysql.inc";
      if (!$conexao)
        die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

      $gera_cod = substr(strtoupper(date('Y')), 2, 2).date('md');

      $query = "select * from tb_pedido where num_ped like '".$gera_cod."%';";
      $result = mysqli_query($conexao, $query);
      $qtd_lin  = $result->num_rows + 1;


echo"
      <label> Num. Pedido * </label>
      <input type=\"text\" name=\"num_ped\" maxlength=\"60\" value=\"".$gera_cod."-".$qtd_lin."\" />
      <label> Cliente </label>
";

        $query = "SELECT * from tb_empresa where tipo = \"CLI\"";
        $result = mysqli_query($conexao, $query);

          echo "<td><select name=\"cliente\" id=\"emp\">";

        while($fetch = mysqli_fetch_row($result)){
            echo $fetch[1] . "<br>";
            echo "<option value=\"". $fetch[0] ."\">". $fetch[1] ."</option>";
        }

            echo "</select> </td>";
          $conexao->close();
      ?>
      <label> Origem </label>
      <select name="selOrigem">
        <option value="FUN" > Funilaria e Pintura </option>
        <option value="SAN" > Sanfonados </option>
        <option value="OUT" > Outros </option>
      </select>
      <label> Data </label>
      <input type="date" name="data_ped" value="<?php echo date('Y-m-d'); ?>">
      <label> Data de Entrega </label>
      <input type="date" name="data_ent" >
      <label> Comprador</label>
      <input type="text" name="comprador" maxlength="30" />
      <label> Emitido por</label>
      <input type="text" name="responsavel" value="<?php if (IsSet($_COOKIE["usuario"])){ echo $_COOKIE["usuario"]; } ?>" readonly/>
      <button type="submit">Salvar</button>
      <input type="hidden" name="novo" value="1">

    </form>
  </div>
</div>

</body>
</html>