<?php

	function smtpmailer($user, $pass, $para, $de, $de_nome, $assunto, $corpo) { 
		$mail = new PHPMailer();
		$mail->IsSMTP();		// Ativar SMTP
		$mail->SMTPDebug = 0;		// Debugar: 1 = erros e mensagens, 2 = mensagens apenas
		$mail->SMTPAuth = true;		// Autenticação ativada
		$mail->SMTPSecure = 'ssl';	// SSL REQUERIDO pelo GMail
		$mail->Host = 'flexibus.com.br';	// SMTP utilizado
		$mail->Port = 465;  		// A porta 587 deverá estar aberta em seu servidor
		$mail->Username = $user;
		$mail->Password = $pass;
		$mail->SetFrom($de, $de_nome);
		$mail->Subject = $assunto;
		$mail->Body = $corpo;
		$mail->AddAddress($para);
		if(!$mail->Send()) {
			return 'Houve um erro no envio, favor tentar mais tarde: '.$mail->ErrorInfo; 
		} else {
			return true;
		}
	}

	if (IsSet($_COOKIE["email"]) && IsSet($_POST["num"])){

		require_once("../config/class.phpmailer.php");
	
		$host = "br610.hostgator.com.br"; 
		$usuario = $_COOKIE["email"];
		$senha = $_COOKIE["mail_pass"];
		$num = $_POST["num"];
		$mensagem = $_POST["body"];

		if($num < 0){
			$subject = $_POST["sub"];
			$email = $usuario;
			$fromaddr = $_POST["dest"];
			$name = strtoupper($_COOKIE["usuario"]);

		}else{

			$inbox = imap_open("{".$host.":143/novalidate-cert}INBOX", $usuario, $senha)
			or die("<br>Usuário ou senha incorretos: <br><br>" . imap_last_error());
	
			$header = imap_headerinfo($inbox, $num);
			$fromaddr = $header->from[0]->mailbox . "@" . $header->from[0]->host;
//			$name = $header->from[0]->personal;
			$name = strtoupper($_COOKIE["usuario"]);
			$subject =  'Re:' . $header->Subject;
			$email =  $header->toaddress;	

		}

//		echo('usuario:'.$usuario.' senha:'.$senha.' para:'.$fromaddr.' de:'.$email. ' nome:'.$name.' assunto:'.$subject.' mss:'.$mensagem);
		smtpmailer($usuario, $senha, $fromaddr, $email, $name, $subject, $mensagem);

	}

?>