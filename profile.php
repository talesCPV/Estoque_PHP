<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/funcoes.js " charset="UTF-8"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";

      if (IsSet($_COOKIE["cod_user"]))
      {
        $cod_user = $_COOKIE["cod_user"];

        include "conecta_mysql.inc";
        if (!$conexao)
          die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

        $query = "SELECT * from tb_usuario where id = \"". $cod_user ."\"";
        $result = mysqli_query($conexao, $query);

        while($fetch = mysqli_fetch_row($result)){
          $user = $fetch[1] ;
          $pass = $fetch[2] ;
          $class = $fetch[3] ;
          $nome = $fetch[4] ;
          $email = $fetch[5] ;
          $tel = $fetch[6] ;
        }
        $conexao->close();
      }
      ?>


  </header>

<div class="page_container">  
  <div class="page_form">
    <p class="logo"> Dados de Perfil</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="save_user.php" id="frmProfile">
      <label> Usuário </label>
      <input type="text" name="user" id="edtUser" value= "<?php echo htmlspecialchars($user); ?>" maxlength="12" readonly />
      <label> Nome </label>
      <input type="text" name="nome" id="edtNome" value= "<?php echo htmlspecialchars($nome); ?>" maxlength="40" />
      <label> Email </label>
      <input type="text" name="email" id="edtEmail" value= "<?php echo htmlspecialchars($email); ?>" maxlength="70" />
      <label> Senha do Email </label>
      <input type="password" name="emailpass" id="emailpass" maxlength="12" />
      <label> Telefone </label>
      <input type="text" name="tel" id="edtFone" value= "<?php echo htmlspecialchars($tel); ?>" maxlength="15" />
      <label> Senha </label>
      <input type="password" name="pass" id="edtPass1" maxlength="12"  />
      <label> Repita a senha </label>
      <input type="password" name="repass" id="edtPass2" maxlength="12" />
      <label> Nivel de Acesso </label>
      <input type="text" name="tipo" id="edtAceso" value= "<?php echo htmlspecialchars($class); ?>" maxlength="2" readonly />
      <button type="button" id="btnSaveProfile" >Salvar</button>
      <input type="hidden" name="cod_user" value= "<?php echo htmlspecialchars($cod_user); ?>">
    </form>
  </div>
</div>

</body>
</html>