<?php

  header("Content-Type: text/html; charset: UTF-8");

  if (IsSet($_COOKIE["email"])){

    $host = "br610.hostgator.com.br"; 
    $usuario = $_COOKIE["email"];
    $senha = $_COOKIE["mail_pass"];

    $from = '';
    $to = '';
    $subject = '';
    $date = '';
    $body = '';


    if (IsSet($_POST["num"])){
      $num = $_POST["num"];

      $inbox = imap_open("{".$host.":143/novalidate-cert}INBOX", $usuario, $senha)
      or die("<br>Usuário ou senha incorretos: <br><br>" . imap_last_error());
      $structure = imap_fetchstructure($inbox,$num);
      $overview = imap_fetch_overview($inbox,"{$num}:{$num}",0)[0];
//      $body = imap_utf8(imap_qprint(imap_fetchbody($inbox, $num,1)) );
      imap_close($inbox);

      if(substr(imap_qprint( strtolower($overview->subject) ),0,8) == '=?utf-8?'){
          $subject = "'".substr(imap_qprint( $overview->subject ),10,strlen($overview->subject))."'";
      }else{
          $subject = "'".$overview->subject."'";
      }
      $from = "'".$overview->from."'";
      $to = "'".$overview->to."'";
      $date = "'".$overview->date."'";

    }
    $jsonfile = "{ 'from':".$from.", 'to':".$to.", 'subject':".$subject.", 'date':".$date.", 'body':".$body." }";


//    echo "\n\n";
    print_r($jsonfile) ;
//    print_r($overview) ;
//    echo "\n\n";
    

  }

?>