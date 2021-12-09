<?php 
if (IsSet($_POST["cod_ent"])){
	$cod_ent = $_POST ["cod_ent"];
//	echo($cod_ent);

	setcookie("cod_ent", $cod_ent , time()+3600);
	header('Location: cad_item_ent.php');
}else{
	echo('teste');
//	header('Location: pesq_ent.php');
}
?>