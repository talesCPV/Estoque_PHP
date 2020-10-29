<?php
	$user = $_POST['user'];
	$pass = $_POST['pass'];

	include "conecta_mysql.inc";

        if ($conexao->connect_error) {
           echo "Connected fail";
           die("Connection failed: " . $conn->connect_error);
        }
//        echo "Connected successfully <br>";

  

	$res = $conexao->query("SELECT * FROM tb_usuario WHERE user=\"$user\" AND pass=\"$pass\" ");
	$qtd_lin = $res->num_rows;

 	if($qtd_lin > 0)
    {

    	$fetch = mysqli_fetch_row($res);

    	$classe = $fetch[3];
    	$cod_user = $fetch[0];


		$arquivo = "config/colors.json";
		$fp = fopen($arquivo, "r");
		$color_json = fread($fp, filesize($arquivo));
		fclose($fp);
	
		$json_str = json_decode($color_json, true);

		session_start();
	
		$_SESSION["cor_menu"] = $json_str["{$user}"]["menu"];
		$_SESSION["cor_barra"] = $json_str["{$user}"]["barra"];
		$_SESSION["cor_fundo"] = $json_str["{$user}"]["fundo"];
		$_SESSION["cor_container"] = $json_str["{$user}"]["container"];
		$_SESSION["cor_botao"] = $json_str["{$user}"]["botao"];
		$_SESSION["cor_fonte_menu"] = $json_str["{$user}"]["fonte_menu"];
		$_SESSION["cor_fonte_cont"] = $json_str["{$user}"]["fonte_container"];



		$_SESSION["cod_user"]=$cod_user;
		$_SESSION["usuario"]=$user;
		$_SESSION["classe"]=$classe;


		setcookie("logado", "true", 8 * time()+3600);
		setcookie("classe", $classe, 8 * time()+3600);
		setcookie("usuario", $user, 8 * time()+3600);
		setcookie("cod_user", $cod_user, 8 * time()+3600);
		setcookie("email", $fetch[5], 8 * time()+3600);
		setcookie("mail_pass", $fetch[7], 8 * time()+3600);
		setcookie("message", $user .", Bem vindo", 8 * time()+3600);


        header("Location: main.php");

	}else{
		setcookie("logado");
		setcookie("classe");
		setcookie("usuario");
		setcookie("cod_user");
		setcookie("email");
		setcookie("mail_pass");
		echo "Usuario ou senha invalido.";
	}
  

  	$conexao->close();
?>