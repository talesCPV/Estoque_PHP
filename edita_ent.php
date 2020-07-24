<?php 
	$cod_ent = $_POST ["cod_ent"];
	setcookie("cod_ent", $cod_ent , time()+3600);
	header('Location: cad_item_ent.php');
?>