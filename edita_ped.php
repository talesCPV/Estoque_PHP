<?php 
	$cod_ped = $_POST ["cod_ped"];
	setcookie("cod_ped", $cod_ped , time()+3600);
	header('Location: cad_item_ped.php');
?>