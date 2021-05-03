<?php
	setcookie("cod_serv");
  	include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Extrato Consolidado</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- <script src="js/funcoes.js"></script> -->
</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >
  <header>
    <?php
      include "menu.inc";

      if (IsSet($_FILES["up_pdf"])){
        $ano = $_POST["edtAno"];              
        $mes = $_POST["cmbMes"];
        $arquivo = $ano."_".$mes.".pdf";
        $file_name = "extratos/".$arquivo;

        copy($_FILES["up_pdf"]["tmp_name"],$file_name);      
      }

    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Extrato Consolidado</p> <br>

        <table style="width:100%;">
            <tr >
                <td><label class="logo">Ano_Mês</label></td>
                <td><select name="cmbFiles"  id="cmbFiles">
                    <?php
                        $dir    = 'extratos/';
                        $files = scandir($dir, 1);
                        $i = 0;      
                    
                        while($i < count($files) - 2){
                            echo ("<option value='{$files[$i]}'>{$files[$i]}</option>");
                            $i += 1;
                        }
                    ?>                
                </select></td>
                <td><button id="btn_Abrir">Abrir</button></td>
            </tr>
            <?php 
              $classe = $_SESSION["classe"];
              if($classe != "10"){
                echo("<!--");
              }            
            ?>
            <form id="frmUpload" method="POST" action="#" enctype='multipart/form-data'>
                <tr>
                  <td><label class="logo"></label></td>
                  <td><input type="file" name="up_pdf" id="up_pdf" accept='.pdf'/></td>
                  <td><button type='submit' id="btn_Upload">Upload</button></td>
                </tr>
                <tr>
                  <td><label class="logo"></label></td>
                  <td><input type="text" name="edtAno" value="2021"></td>
                  <td><select name="cmbMes">
                        <option value="01">Janeiro</option>
                        <option value="02">Fevereiro</option>
                        <option value="03">Março</option>
                        <option value="04">Abril</option>
                        <option value="05">Maio</option>
                        <option value="06">Junho</option>
                        <option value="07">Julho</option>
                        <option value="08">Agosto</option>
                        <option value="09">Setembro</option>
                        <option value="10">Outubro</option>
                        <option value="11">Novembro</option>
                        <option value="12">Dezembro</option>
                      </select>
                  </td>
                <tr>
              </form>
              <?php 
              $classe = $_SESSION["classe"];
              if($classe != "10"){
                echo("-->");
              }
            
            ?>

        </table>

	  </div>

  	  
  </div>

<div class="overlay">	
  <div class="popup">
    <h2 id="popTitle"></h2>
    <div class="close" >&times</div>
    <div class="content"></div>
  </div>
</div>
<script src="js/extrato.js"></script>

</body>
</html>