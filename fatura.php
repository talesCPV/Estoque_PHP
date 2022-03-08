<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Datas para Fatura</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/funcoes.js"></script>
</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >


  <header>
    <?php
      include "menu.inc";

      $unid = 'config/fatura.txt';


      if (IsSet($_POST ["edtUnd"]) and $classe >=4 ){
         $texto = $_POST ["edtUnd"];
         $fp = fopen($unid, "w");
         fwrite($fp, $texto);
         fclose($fp);
      }


     if(file_exists ($arquivo)){    
       
      


     }

    ?>
  </header>

<div class="page_container">  
  <div class="page_form" <?php echo" style='background: {$_SESSION["cor_container"]}; color: {$_SESSION["cor_fonte_cont"]} ;' "; ?>>
    <p class="logo"> Texto de Fatura</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="#" onsubmit="return validaCampo(); return false;">
      <label> </label>
      <?php  
          echo "<textarea class='edtTextArea' name='edtUnd' cols='112' rows='5'>";
          if(file_exists ($unid)){
            $fp = fopen($unid, "a+");
            while (!feof ($fp)) {
              $valor = fgets($fp,4096);
              echo $valor;
            }
            fclose($fp);
          }
      echo"</textarea>";

      


      ?>


      <button type="submit">Salvar</button> <br>

    </form>      

  </div>
</div>


</body>
</html>