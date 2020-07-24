<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Menu Principal</title>
     <!-- Aqui chamamos o nosso arquivo css externo -->
    <link rel="stylesheet" type="text/css"  href="estilo.css" />
    <!--[if lte IE 8]>
 <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
 <![endif]-->   
</head>
<body>
<?php
  include "valida.inc";
  if (IsSet($_COOKIE["classe"])){
    $classe = $_COOKIE["classe"];
  }
?>


<nav>
  <ul class="menu">
        <?php
          if ($classe == "10" or $classe == "4")
          {
            echo"<li><a href=\"#\">Cadastro</a> 
                <ul>
                    <li><a href=\"#\">Empresas</a></li>
                    <li><a href=\"#\">Produtos</a></li>";
                    if ($classe == "10")
                    {
                      echo"<li><a href=\"cad_user.php\">Usuários</a></li>"; 
                    }                   
           echo"</ul>
        </li>";
          }

        ?>
        <li><a href="#">Pesquisa</a></li>
            <li><a href="#">Compras & Baixas</a>
                <ul>
                      <li><a href="#">Baixar Produto</a></li>
                      <li><a href="#">Adicionar em Estoque</a></li>
                      <li><a href="#">Inventário</a></li>                    
                </ul>
            </li>
        <li><a href="logout.php">logout</a></li>                
</ul>
</nav>
</body>
</html>