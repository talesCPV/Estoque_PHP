<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Cadastro de Usuários</title>
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
    <p class="logo"> Cadastro de Usuários</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="save_user.php"  >
      <label> Usuário *</label>
      <input type="text" name="user" id="edtUser" maxlength="12"/>
      <label> Senha *</label>
      <input type="password" name="pass" id="edtSenha1" maxlength="12"  />
      <label> Repita a senha *</label>
      <input type="password" name="repass" id="edtSenha2" maxlength="12" />
      <label> Tipo de Acesso </label>
      <select name="classe" id="classe">
          <option value="1">Contador</option>
          <option value="2">Comercial</option>
          <option value="3">Colorista</option>
          <option value="4">Gerente</option>
          <option value="10">CEO</option>
      </select>
      <button type="submit" onclick="return obrigatorio(['edtUser','edtSenha1']) && verif_senha(['edtSenha1','edtSenha2'])">Cadastrar</button>

    </form>
  </div>
</div>

</body>
</html>