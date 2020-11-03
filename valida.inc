<?php
	session_start();

	if (!IsSet($_SESSION["usuario"])){
		header('Location: login.php');
	}else{

		$arquivo = "config/colors.json";
		$fp = fopen($arquivo, "r");
		$color_json = fread($fp, filesize($arquivo));
		fclose($fp);
	
		$json_str = json_decode($color_json, true);
		$user = $_SESSION["usuario"];

		$_SESSION["cor_menu"] = $json_str["{$user}"]["menu"];
		$_SESSION["cor_barra"] = $json_str["{$user}"]["barra"];
		$_SESSION["cor_fundo"] = $json_str["{$user}"]["fundo"];
		$_SESSION["cor_container"] = $json_str["{$user}"]["container"];
		$_SESSION["cor_botao"] = $json_str["{$user}"]["botao"];
		$_SESSION["cor_fonte_menu"] = $json_str["{$user}"]["fonte_menu"];
		$_SESSION["cor_fonte_cont"] = $json_str["{$user}"]["fonte_container"];

	}
?>