<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="js/funcoes.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";

      // RECEBENDO OS DADOS PREENCHIDOS DO FORMULÁRIO !
      if (IsSet($_POST ["cod_prod"])){

        $cod_prod = $_POST ["cod_prod"];

        include "conecta_mysql.inc";
        if (!$conexao)
          die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());


        $query = "SELECT * FROM tb_produto WHERE id = ".$cod_prod."; ";
          
        $result = mysqli_query($conexao, $query);

        while($fetch = mysqli_fetch_row($result)){
          $id_emp = $fetch[1];
          $descricao = $fetch[2];
          $estoque = $fetch[3];
          $etq_min = $fetch[4];
          $unidade = $fetch[5];
          $ncm = $fetch[6];
          $cod = $fetch[7];
          $cod_bar = $fetch[8];
          $cod_cli = $fetch[13];
          $compra = $fetch[10];
          $margem = $fetch[11];
          $tipo = $fetch[12];
        }
        
      }

      $conexao->close();
    ?>
  </header>

<div class="page_container">  
  <div class="page_form">
    <p class="logo"> Cadastro de Produtos</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="save_prod.php">
      <label> Descrição * </label>
      <input type="text" name="nome" maxlength="80" value="<?php echo "$descricao" ?>" />
      <label> Fornecedor </label>
      <?php  

      include "conecta_mysql.inc";
      if (!$conexao)
        die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

        $query = "SELECT * from tb_empresa where tipo = \"FOR\"";
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
      <label> Unidade </label> 
      <?php  // busca unidades no
          $arquivo = "config/unidades.txt";
          $fp = fopen($arquivo, "r");
          echo "<td><select name=\"und\" >";
          while (!feof ($fp)) {
            $valor = fgets($fp, 4096);
            if( trim($unidade) == trim($valor)){
              echo "<option selected=\"selected\" value=\"". $valor ."\">". $valor ."</option>";
            }else{
              echo "<option value=\"". $valor ."\">". $valor ."</option>";
            }
          }

          echo "</select> </td>";

          fclose($fp);
      ?>
      <label> Tipo </label>
      <select name="tipo" >";
        <option <?php if(trim($tipo) == 'VENDA'){echo"selected";} ?> value="VENDA"> PRODUTO </option>"
        <option <?php if(trim($tipo) == 'SERVICO'){echo"selected";} ?> value="SERVICO"> SERVICO </option>"
        <option <?php if(trim($tipo) == 'TINTA'){echo"selected";} ?> value="TINTA"> TINTA </option>"
        <option <?php if(trim($tipo) == 'TINTA_E'){echo"selected";} ?> value="TINTA_E"> TINTA PRONTA MODIFICADA</option>"
        <option <?php if(trim($tipo) == 'PIGMTO'){echo"selected";} ?> value="PIGMTO"> PIGMENTO </option>"
        <option <?php if(trim($tipo) == 'CONJ'){echo"selected";} ?> value="CONJ"> CONJUNTO </option>"     
      </select>
      <label> Estoque </label>
      <input type="text" name="estoque" maxlength="6" value="<?php echo "$estoque" ?>"/>
      <label> Estoque Minimo </label>
      <input type="text" name="est_min" maxlength="4" value="<?php echo "$etq_min" ?>"/>
      <label> Codigo *</label>
      <input type="text" name="cod" maxlength="15" value="<?php echo "$cod" ?>" READONLY/>
      <label> Codigo do Produto </label>
      <input type="text" name="cod_bar" maxlength="15" value="<?php echo "$cod_bar" ?>"/>
      <label> Codigo de Barras </label>
      <input type="text" name="cod_cli" maxlength="20" value="<?php echo "$cod_cli" ?>"/>
      <label> NCM </label>
      <input type="text" name="ncm" maxlength="10" value="<?php echo "$ncm" ?>"/>
      <label> Preço de Compra R$</label>
      <input type="text" name="compra" maxlength="15" value="<?php echo "$compra" ?>" onkeyup="return money(this)"/>
      <label> Margem %</label>
      <input type="text" name="margem" maxlength="15" value="<?php echo "$margem" ?>" onkeyup="return money(this)"/>
      <input type="hidden" name="cod_prod" value="<?php echo "$cod_prod" ?>">
      <button type="submit">Salvar</button>

    </form>
  </div>
</div>

</body>
</html>