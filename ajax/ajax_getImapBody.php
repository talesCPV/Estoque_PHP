<?php

  header("Content-Type: text/html; charset: UTF-8");

  if (IsSet($_COOKIE["email"])){

    $host = "br610.hostgator.com.br"; 
    $usuario = $_COOKIE["email"];
    $senha = $_COOKIE["mail_pass"];

    if (IsSet($_POST["num"])){
      $num = $_POST["num"];
      $caixa = $_POST["caixa"];

      $mbox = imap_open("{".$host.":143/novalidate-cert}".$caixa, $usuario, $senha)
      or die("<br>Usuário ou senha incorretos: <br><br>" . imap_last_error());

      $overview = imap_fetch_overview($mbox,"{$num}:{$num}",0)[0];


      echo "\n\n";
      print_r(imap_utf8(imap_qprint(imap_fetchbody($mbox, $num,"1")) ));
//      print_r(imap_fetchstructure($mbox,$num) ) ;
//      print_r(( $overview )) ;

      echo "\n\n";

      imap_close($mbox);

    }

  }

?>