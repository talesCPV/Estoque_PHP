<?php

//	if (IsSet($_POST["num"])){
//    $num = $_POST["num"];

    $host = "br610.hostgator.com.br"; 
    $usuario = "tania.morgado@flexibus.com.br";
    $senha = "Flex0169#";

    $mbox = imap_open("{".$host.":143/novalidate-cert}INBOX", $usuario, $senha)
    or die("<br>Usuário ou senha incorretos: <br><br>" . imap_last_error());

    $MC = imap_check($mbox);
    $result = array_reverse(imap_fetch_overview($mbox,"1:{$MC->Nmsgs}",0));
    imap_close($mbox);
       
    echo ($result);
    
//    }

    }
////    $json_str = json_decode($resp, true);

//		foreach ( $json_str['seguranca'] as $e ) {
//        echo($e['url']);

//		} 



//    $rows = explode("\r\n", $resp);

//	print json_encode($rows);

?>