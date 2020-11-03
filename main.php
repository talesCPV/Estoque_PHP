<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Menu Principal</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="js/funcoes.js"></script> 
</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<?php
  	  		if (IsSet($_COOKIE["message"])){
  	  			echo $_COOKIE["message"];
          }

  	  	?>
	  </div>


  <?php 
                

  if (file_exists("lousa/".$user.".txt")) {
    echo"  <div class=\"page_form\" id=\"no_margin\">";

          $fp = fopen("lousa/".$user.".txt", "r");
          while (!feof ($fp)) {
            $valor = fgets($fp,4096);
            echo $valor."<br>" ;
          }
          fclose($fp);

    echo"  
          <form class=\"login-form\" name=\"limpa\" method=\"POST\" action=\"limpa_lousa.php\" onsubmit=\"return verifica(); return false;\">
 
            <button type=\"submit\" name=\"limpar\" value = \"1\">Limpar Lousa</button>
            <input type=\"hidden\" name=\"file\" value=\"lousa/".$user.".txt\">
          </form>    
          </div>";

  } 

    
  ?>

  </div>



</body>
</html>