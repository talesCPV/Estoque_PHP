<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Configuracoes</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/funcoes.js"></script>
</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >


  <header>
    <?php
      include "menu.inc";

      $unid = 'config/unidades.txt';
      $ass = 'config/'.$user.'_ass.txt';
      $color = "config/".$user."_colors.txt";
      $txtNF = 'config/nftxt.txt';

      if (IsSet($_POST ["edtUnd"]) and $classe >=4 ){
         $texto = $_POST ["edtUnd"];
         $fp = fopen($unid, "w");
         fwrite($fp, $texto);
         fclose($fp);
      }
      if (IsSet($_POST ["edtMail"])){
        $texto = $_POST ["edtMail"];
        $fp = fopen($ass, "w");
        fwrite($fp, $texto);
        fclose($fp);
     }
     if (IsSet($_POST ["edtLousa"])){
        $destino = $_POST ["destino"];
        $texto = "\n \n".$user." escreveu em ".date('d/m/Y')."\n \n".$_POST ["edtLousa"];
        $fp = fopen("lousa/".$destino.".txt", "a");
        fwrite($fp, $texto);
        fclose($fp);
      }

      if (IsSet($_POST ["menu-color"])){

        $arquivo = "config/colors.json";
        $fp = fopen($arquivo, "r");
        $color_json = fread($fp, filesize($arquivo));
        fclose($fp);
      
        $json_str = json_decode($color_json, true);

        $json_str[$_SESSION["usuario"]] = array(
                "barra" => "{$_POST ["barra-color"]}",
                "menu" => "{$_POST ["menu-color"]}",
                "fundo" => "{$_POST ["background-color"]}",
                "container" => "{$_POST ["form-color"]}",
                "botao" => "{$_POST ["btn0-color"]}",
                "fonte_menu" => "{$_POST ["sel-color"]}",
                "fonte_container" => "{$_POST ["fonte-color"]}"
          );

          $_SESSION["cor_menu"] = $_POST ["menu-color"];
          $_SESSION["cor_barra"] = $_POST ["barra-color"];
          $_SESSION["cor_fundo"] = $_POST ["background-color"];
          $_SESSION["cor_container"] = $_POST ["form-color"];
          $_SESSION["cor_botao"] = $_POST ["btn0-color"];
          $_SESSION["cor_fonte_menu"] = $_POST ["sel-color"];
          $_SESSION["cor_fonte_cont"] = $_POST ["fonte-color"];


          $fp = fopen($arquivo, "w");
          fwrite($fp, json_encode($json_str));
          fclose($fp);
     
     }


     $arquivo = "config/colors.json";

     if(file_exists ($arquivo)){    
       
      
      $fp = fopen($arquivo, "r");
      $color_json = fread($fp, filesize($arquivo));
      fclose($fp);

      $json_str = json_decode($color_json, true);

       $barra = $json_str["{$user}"]["barra"];
       $back = $json_str["{$user}"]["fundo"];
       $btn0 = $json_str["{$user}"]["botao"];
       $menu = $json_str["{$user}"]["menu"];
       $menuSel = $json_str["{$user}"]["fonte_menu"];
       $form = $json_str["{$user}"]["container"];
       $fonte = $json_str["{$user}"]["fonte_container"];

     }else{
      $barra = '#f0f0f0';
      $back =  '#2e2e2e';
      $btn0 = '#2e2e2e';
      $menu = '#f0f0f0';
      $menuSel = '#2e2e2e';
      $form = '#ffffff';
      $fonte = '#2e2e2e';
     }

    ?>
  </header>

<div class="page_container">  
  <div class="page_form" <?php echo" style='background: {$_SESSION["cor_container"]}; color: {$_SESSION["cor_fonte_cont"]} ;' "; ?>>
    <p class="logo"> Configuracao</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="#" onsubmit="return validaCampo(); return false;">
      <label> Unidades de Medida </label>
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

      echo "<label> Assinatura de Email </label>
      <textarea class='edtTextArea' name=\"edtMail\" cols=\"112\" rows=\"5\" >";
      
          if(file_exists ($ass)){
            $fp = fopen($ass, "a+");
            while (!feof ($fp)) {
              $valor = fgets($fp,4096);
              echo $valor;
            }
            fclose($fp);
          }
      
      
      echo "</textarea>";
  

      

      include "conecta_mysql.inc";
      if (!$conexao)
        die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

        $query = "SELECT * from tb_usuario where user != \"".$user."\"";
        $result = mysqli_query($conexao, $query);


        echo"<br><br><label> Lousa de Recados </label>

           <td><select name=\"destino\" >
           <option selected value=\"0\"> Nenhum </option>";


        while($fetch = mysqli_fetch_row($result)){
            echo $fetch[1] . "<br>";
            echo "<option value=\"". $fetch[1] ."\">". $fetch[1] ."</option>";
        }

            echo "</select> </td>";
          $conexao->close();
  
        echo"<textarea class='edtTextArea' name=\"edtLousa\" cols=\"112\" rows=\"5\" >";

        echo"</textarea>";

      ?>

        <label> Cores <br></label>
        <table><tr>
          <td> Barra Superior.... </td>
          <td> Fundo </td>
          <td>  <input name="barra-color" type="color" <?php echo"value='". trim($barra) ."'" ?> /> </td>
          <td> Menu </td>     
          <td> <input name="menu-color" type="color" <?php echo"value='". trim($menu) ."'" ?> /> </td>
          <td> Fonte </td>     
          <td> <input name="sel-color" type="color" <?php echo"value='". trim($menuSel) ."'" ?> /> </td>
        </tr><tr> 
          <td> Tela.................... </td>
          <td> Fundo </td>     
          <td> <input name="background-color" type="color" <?php echo"value='". trim($back) ."'" ?> /> </td>
          <td> Form </td>     
          <td> <input name="form-color" type="color" <?php echo"value='". trim($form) ."'" ?> /> </td>
          <td> Botões </td>     
          <td> <input name="btn0-color" type="color" <?php echo"value='". trim($btn0) ."'" ?> /> </td>
          <td> fonte </td>     
          <td> <input name="fonte-color" type="color" <?php echo"value='". trim($fonte) ."'" ?> /> </td>
        </tr></table>

      <button type="submit">Salvar</button> <br>

    </form>      

  </div>
</div>


</body>
</html>