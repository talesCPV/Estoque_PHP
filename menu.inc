    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>  -->
    <script src="js/jqFunc.js"></script>                                                      

<?php
  include "valida.inc";
//    if (!IsSet($_COOKIE["logado"])){ // Valida se o usuario esta logado
//        header('Location: login.php');
//    }

  setlocale(LC_MONETARY,"pt_BR", "ptb");

  if (IsSet($_COOKIE["classe"])){
    $classe = $_COOKIE["classe"];
    $user = $_COOKIE["usuario"];
  }
	echo"
		 <div class=\"top_bar\">
		    <div class=\"top_bar_left\">
		     <nav>
		      <ul class=\"menu\">
		      <li> <a href=\"main.php\"><img src=\"img/menu_logo.png\"></a> </li>";


    // LÊ O ARQUIVO MENU.JSON
    $arquivo = "config/menu.json";
    $fp = fopen($arquivo, "r");
    $menu_json = fread($fp, filesize($arquivo));
    fclose($fp);

    $json_str = json_decode($menu_json, true);

		foreach ( $json_str['menu'] as $e ) {
      $main_name = $e['modulo'];
      $main_perm = $e['perm'];
      $main_link = $e['link'];
      
      if(in_array($classe, $main_perm)){
        echo"<li><a href='". $main_link ."'>". $main_name ."</a><ul>";

        foreach ($e['itens'] as $a ) {
          if(in_array($classe, $a['perm'])){
            echo"<li><a href='". $a['link']."'>". $a['nome'] ."</a></li>";
          }
        }
        echo("</ul></li>");
      }

		} 

    echo"
		    </nav>
		    </div>
        
		    <div class='top_bar_rigth'> <form method='POST' action='pesq_prod.php'> <input type='text' name='valor' /><input type='hidden' name='campo' value='desc'>  ".strtoupper($user);
        

        if (IsSet($_COOKIE["mail_pass"])){
            echo " <a href=\"email.php\"><img src=\"img/small_mail.png\"></a>";
        }
     echo" <a href=\"profile.php\"><img src=\"img/small_gear.png\"></a> <a href=\"logout.php\"><img src=\"img/small_logout.png\"></a></div>
        </form>
        </div>"; // fim do echo principal


?>