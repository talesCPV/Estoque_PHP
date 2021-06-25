<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="js/funcoes.js"></script>
</head>
<body>
<div class="login-page">
  <div class="form">
    <p class="logo"> <a href="https://www.flexibus.com.br"><img src="img/logo.png"></a></p> <br>
    <form class="login-form" method="POST" action="connect.php">
      <input type="text" placeholder="username" name="user" id="edtUser" />
      <input type="password" placeholder="password" name="pass" id="edtPass"/>
      <button class="btn-login" id="btn_centro">login</button>
    </form>
  </div>
</div>
<script src="js/login.js"></script>

</body>
</html>