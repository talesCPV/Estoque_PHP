<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Cadastro de Empresas</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="js/funcoes.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
      // RECEBENDO OS DADOS PREENCHIDOS DO FORMULÁRIO !
      if (IsSet($_POST ["cod_emp"])){

        $cod_emp = $_POST ["cod_emp"];

        include "conecta_mysql.inc";
        if (!$conexao)
          die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());


        $query = "SELECT * FROM tb_empresa WHERE id = ".$cod_emp."; ";
          
        $result = mysqli_query($conexao, $query);

        while($fetch = mysqli_fetch_row($result)){

                    $nome = $fetch[1];
                    $endereco = $fetch[4];
                    $cidade = $fetch[5];
                    $estado = $fetch[6];
                    $cep = $fetch[9];
                    $cnpj = $fetch[2];
                    $ie = $fetch[3];
                    $tipo = $fetch[7];
                    $telefone = $fetch[8];
                    $bairro = $fetch[10];
                    $num = $fetch[11];

        }


      }

      $conexao->close();

    ?>
  </header>

<div class="page_container">  
  <div class="page_form">
    <p class="logo"> Cadastro de Empresas</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="save_emp.php" onsubmit="return validaCampo(new Array(cadastro.nome)); return false;">
      <label> Nome *</label>
      <input type="text" name="nome" maxlength="50" value="<?php echo "$nome" ?>" />
      <label> Endereço </label>
      <input type="text" name="endereco" maxlength="60" value="<?php echo "$endereco" ?>" />
      <label> Cidade </label>
      <input type="text" name="cidade" maxlength="30" value="<?php echo "$cidade" ?>" />
      <label> Bairro </label>
      <input type="text" name="bairro" maxlength="60" value="<?php echo "$bairro" ?>" />
      <label> Numero </label>
      <input type="text" name="num" maxlength="5" value="<?php echo "$num" ?>" />
      <label> Estado </label>
      <select name="estado" id="estado">
        <option <?php if($estado == 'AC'){echo"selected";} ?> value="AC">Acre</option>
        <option <?php if($estado == 'AL'){echo"selected";} ?> value="AL">Alagoas</option>
        <option <?php if($estado == 'AP'){echo"selected";} ?> value="AP">Amapa</option>
        <option <?php if($estado == 'AM'){echo"selected";} ?> value="AM">Amazonas</option>
        <option <?php if($estado == 'BA'){echo"selected";} ?> value="BA">Bahia</option>
        <option <?php if($estado == 'CE'){echo"selected";} ?> value="CE">Ceara</option>
        <option <?php if($estado == 'ES'){echo"selected";} ?> value="ES">Espirito Santo</option>
        <option <?php if($estado == 'DF'){echo"selected";} ?> value="DF">Distrito Federal</option>
        <option <?php if($estado == 'MA'){echo"selected";} ?> value="MA">Maranhao</option>
        <option <?php if($estado == 'MT'){echo"selected";} ?> value="MT">Mato Grosso</option>
        <option <?php if($estado == 'MS'){echo"selected";} ?> value="MS">Mato Grosso do Sul</option>
        <option <?php if($estado == 'MG'){echo"selected";} ?> value="MG">Minas Gerais</option>
        <option <?php if($estado == 'PA'){echo"selected";} ?> value="PA">Para</option>
        <option <?php if($estado == 'PB'){echo"selected";} ?> value="PB">Paraiba</option>
        <option <?php if($estado == 'PR'){echo"selected";} ?> value="PR">Parana</option>
        <option <?php if($estado == 'PE'){echo"selected";} ?> value="PE">Pernambuco</option>
        <option <?php if($estado == 'PI'){echo"selected";} ?> value="PI">Piaui</option>
        <option <?php if($estado == 'RJ'){echo"selected";} ?> value="RJ">Rio de Janeiro</option>
        <option <?php if($estado == 'RN'){echo"selected";} ?> value="RN">Rio Grande do Norte</option>
        <option <?php if($estado == 'RS'){echo"selected";} ?> value="RS">Rio Grande do Sul</option>
        <option <?php if($estado == 'RO'){echo"selected";} ?> value="RO">Rondonia</option>
        <option <?php if($estado == 'RR'){echo"selected";} ?> value="RR">Roraima</option>
        <option <?php if($estado == 'SC'){echo"selected";} ?> value="SC">Santa Catarina</option>
        <option <?php if($estado == 'SP'){echo"selected";} ?> value="SP">Sao Paulo</option>
        <option <?php if($estado == 'SE'){echo"selected";} ?> value="SE">Sergipe</option>
        <option <?php if($estado == 'TO'){echo"selected";} ?> value="TO">Tocantins</option>
      </select>
      <label> CEP </label>
      <input type="text" name="cep" maxlength="10" value="<?php echo "$cep" ?>" />
      <label> CNPJ </label>
      <input type="text" name="cnpj" maxlength="14" value="<?php echo "$cnpj" ?>" />
      <label> Inscrição Estadual </label>
      <input type="text" name="ie" maxlength="14" value="<?php echo "$ie" ?>" />
      <label> Tipo </label>
      <select name="tipo">
        
        <option <?php if($tipo == 'FOR'){echo"selected";} ?> value="FOR">Fornecedor</option>
        <option <?php if($tipo == 'CLI'){echo"selected";} ?> value="CLI">Cliente</option>

      </select>
      <label> Telefone </label>
      <input type="text" name="fone" maxlength="14" value="<?php echo "$telefone" ?>" />
      <input type="hidden" name="cod_emp" value="<?php echo "$cod_emp" ?>">
      <button type="submit">Salvar</button>

    </form>
  </div>
</div>

</body>
</html>