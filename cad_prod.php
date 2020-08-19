<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>

<div class="page_container">  
  <div class="page_form">
    <p class="logo"> Cadastro de Produtos</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="save_prod.php" id="frmSaveProd">
      <label> Descrição * </label>
      <input type="text" name="nome" maxlength="80" id="edtDesc"/>
      <label> Fornecedor </label>
      <?php  

      include "conecta_mysql.inc";
      if (!$conexao)
        die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

        $query = "SELECT * from tb_empresa where tipo = \"FOR\"";
        $result = mysqli_query($conexao, $query);

          echo "<td><select name=\"forn\" id=\"forn\">";


        while($fetch = mysqli_fetch_row($result)){
            echo $fetch[1] . "<br>";
            echo "<option value=\"". $fetch[0] ."\">". $fetch[1] ."</option>";
        }

            echo "</select> </td>";
          $conexao->close();
      ?>
      <label> Unidade </label>
      <?php  // busca unidades no arquivo 
          $arquivo = "config/unidades.txt";
          $fp = fopen($arquivo, "r");

          echo "<td><select name=\"und\" >";
          while (!feof ($fp)) {
            $valor = fgets($fp, 4096);
            echo "<option value=\"". $valor ."\">". $valor ."</option>";
          }

          echo "</select> </td>";

          fclose($fp);
      ?>
      <label> Estoque </label>
      <input type="text" name="estoque" maxlength="10" onkeyup="return float_number(this)" value="0"/>
      <label> Tipo </label>
      <select name="tipo" >";
        <option value="VENDA"> PRODUTO </option>"
        <option value="CONJ"> CONJUNTO </option>"
        <option value="SERVICO"> SERVICO </option>"
        <option value="PIGMTO"> PIGMENTO </option>"
      </select>
      <label> Estoque Minimo </label>
      <input type="text" name="est_min" maxlength="14" onkeyup="return float_number(this)" value="0"/>
      <label> Codigo do Produto</label>
      <input type="text" name="cod_bar" maxlength="15"/>
      <label> Codigo de Barras</label>
      <input type="text" name="cod_cli" maxlength="20"/>
      <label> NCM </label>
      <input type="text" name="ncm" maxlength="8"/>
      <label> Preço de Compra R$</label>
      <input type="text" name="compra" maxlength="15" onkeyup="return float_number(this)" value="0"/>
      <label> Margem de Lucro %</label>
      <input type="text" name="margem" maxlength="15" onkeyup="return float_number(this)" value="100"/>
      <button type="submit" id="btnCadastrar" onclick="return obrigatorio(['edtDesc'])" >Cadastrar</button>
    </form>
  </div>
</div>

</body>
</html>