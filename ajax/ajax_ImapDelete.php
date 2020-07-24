<?php

  header("Content-Type: text/html; charset: UTF-8");

  if (IsSet($_COOKIE["email"])){

    $host = "br610.hostgator.com.br"; 
    $usuario = $_COOKIE["email"];
    $senha = $_COOKIE["mail_pass"];

    if (IsSet($_POST["num"])){
      $num = $_POST["num"];

      $inbox = imap_open("{".$host.":143/novalidate-cert}INBOX", $usuario, $senha)
      or die("<br>Usuário ou senha incorretos: <br><br>" . imap_last_error());

      imap_delete($inbox, $num);

      echo "\n\n";
      if(imap_expunge($inbox)){
        print_r('Email deletado com sucesso!');
      }else{
        print_r('Erro ao deletar emails no servidor \n Favor tentar mais tarde');
      }
      echo "\n\n";

      imap_close($inbox);

    }

  }

?>