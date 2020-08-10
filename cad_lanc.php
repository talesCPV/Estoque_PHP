<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Financeiro</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
</head>
<body>
  <header>

    <?php
      include "menu.inc";
      include "funcoes.inc";  
    ?>
  </header>

<div class="page_container">  
  <div class="page_form">
    <p class="logo"> Lançamentos Financeiros</p> <br>
    <form class="login-form" name="cadastro" id="frmSaveFinan" method="POST" action="save_finan.php" >
      <label> Referência / NF *</label>
      <input type="text" name="ref" maxlength="30" id="edtRef"/>
      <label id="lblEntSai"> Entrada / Saída </label>
      <select name="tipo" id="selTipo">";
        <option value="ENTRADA"> A Receber </option>"
        <option value="SAIDA"> A Pagar </option>"
      </select>
      <label id="lblOrigem"> Origem </label>
      <select name="origem" id="selOrig">";
        <option value="FUN"> Funilaria e Pintura </option>"
        <option value="SAN"> Sanfonados </option>"
        <option value="OUT"> Outro </option>"
      </select>
      <label id="lblDest"> Sacado *</label>
      <input type="text" name="dest" maxlength="50" id="edtDest"/>
      <label id="lblDataVenc"> Data Recebimento / Vencimento</label>
      <input type="date" name="data_venc" id="edtDataVenc" value="<?php echo date('Y-m-d'); ?>">
      <label id="lblDataVenc"> Tipo de Pagto.</label>
      <select name="selPgt" id="selPgt">";
        <option value="BOL"> Boleto </option>"
        <option value="DEB"> Cartão de Débto </option>"
        <option value="CRD"> Cartão de Crédito </option>"
        <option value="CHQ"> Cheque </option>"
        <option value="DIN"> Dinheiro </option>"
        <option value="DEP"> Depósito </option>"
        <option value="AUT"> Débto Automático </option>"
      </select>
      <label> Valor do Título R$ *</label>
      <input type="text" name="valor" id="edtValor" maxlength="15" onkeyup="return float_number(this)" />
      <label> Cadastrado por</label>
      <input type="text" name="resp" id="edtResp" value="<?php if (IsSet($_COOKIE["usuario"])){ echo $_COOKIE["usuario"]; } ?>" readonly/>
      <label> Emitido em </label>
      <input type="text" name="data_ent" id="edtDataEnt" value="<?php echo date('d/m/Y'); ?>" readonly/>
      <button type="submit" onclick="return obrigatorio(['edtRef','edtDest','edtValor'])">Salvar</button>
    </form>
  </div>
</div>

</body>
</html>