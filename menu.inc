    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>  -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="js/jqFunc.js"></script>                                                      

<?php

//  include "valida.inc";
//    if (!IsSet($_COOKIE["logado"])){ // Valida se o usuario esta logado
//        header('Location: login.php');
//    }

  setlocale(LC_MONETARY,"pt_BR", "ptb");

    $cor_menu =  '#f0f0f0';
		$cor_barra =  '#f0f0f0';
		$cor_fundo =  '#2e2e2e';
		$cor_container =  '#ffffff';
		$cor_botao =  '#2e2e2e';
//		$cor_fonte_menu =  '#2e2e2e';
		$cor_fonte_cont =  '#2e2e2e';

  if (IsSet($_SESSION["classe"])){
    $classe = $_SESSION["classe"];
    $user = $_SESSION["usuario"];
    $cor_menu =  $_SESSION["cor_menu"] ;
		$cor_barra =  $_SESSION["cor_barra"] ;
		$cor_fundo =  $_SESSION["cor_fundo"] ;
		$cor_container =  $_SESSION["cor_container"] ;
		$cor_botao =  $_SESSION["cor_botao"] ;
		$cor_fonte_menu =  $_SESSION["cor_fonte_menu"] ;
		$cor_fonte_cont =  $_SESSION["cor_fonte_cont"] ;

  }
	echo"
		 <div style='background: {$cor_barra}; color:{$cor_fonte_menu};' class=\"top_bar\">
		    <div style='background: {$cor_barra}; color:{$cor_fonte_menu};' class=\"top_bar_left\">
		     <nav>
		      <ul class=\"menu\" >";


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
      $main_ico = $e['icone'];

// <i class='{$main_ico}' aria-hidden='true'></i>      

      if(in_array($classe, $main_perm)){
        echo"<li style=' background: {$cor_menu}; color:{$cor_fonte_menu};'><a style='color:{$cor_fonte_menu};' href='". $main_link ."'> <i class='{$main_ico}' aria-hidden='false'> - </i>". $main_name ."</a><ul>";

        foreach ($e['itens'] as $a ) {
          if(in_array($classe, $a['perm'])){
            echo"<li style='background: {$cor_menu}; color:{$cor_fonte_menu};'><a style='color:{$cor_fonte_menu};' href='". $a['link']."'>  <i class='". $a['icone'] ."' aria-hidden='false'> </i> ". $a['nome'] ."</a></li>";
          }
        }
        echo("</ul></li>");
      }

		} 

    echo"
		    </nav>
		    </div>
        
		    <div style='background: {$cor_barra}; color:{$cor_fonte_menu};' class='top_bar_rigth'> <form method='POST' action='pesq_prod.php'> <input type='text' name='valor' placeholder='Pesq. Produto' size='10' /><input type='hidden' name='campo' value='desc'> <i class='fa fa-user-circle-o' aria-hidden='true'> - </i>".strtoupper($user);
        

//        if (IsSet($_COOKIE["mail_pass"])){
//            echo " <a href=\"email.php\"><img src=\"img/small_mail.png\"></a>";
//        }

     echo" <a href=\"profile.php\"><img src=\"img/small_gear.png\"></a> <a href=\"logout.php\"><img src=\"img/small_logout.png\"></a></div>
        </form>
        </div>"; // fim do echo principal


?>