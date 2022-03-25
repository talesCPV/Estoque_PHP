<?php
	$user = $_POST['user'];
	$pass = $_POST['pass'];

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	include "../../conecta_mysql.inc";

        if ($conexao->connect_error) {
           echo "Connected fail";
           die("Connection failed: " . $conn->connect_error);
        }
//        echo "Connected successfully <br>";

//		echo 'SELECT * FROM tb_usuario WHERE user="'.$user.'" AND pass="'.$pass.'" ;';

	$res = $conexao->query('SELECT * FROM tb_usuario WHERE user="'.$user.'" AND pass="'.$pass.'" ;');
	$qtd_lin = $res->num_rows;

//		echo $qtd_lin;

 	if($qtd_lin > 0)
    {

    	$fetch = mysqli_fetch_row($res);
		
		echo("{$fetch[3]}|{$user}|{$fetch[0]}|{$fetch[5]}|{$fetch[4]}|".createMenu($fetch[3]));
/*			
		setcookie("logado", "true", 8 * time()+3600);
		setcookie("classe", $classe, 8 * time()+3600);
		setcookie("usuario", $user, 8 * time()+3600);
		setcookie("cod_user", $cod_user, 8 * time()+3600);
		setcookie("email", $fetch[5], 8 * time()+3600);
		setcookie("mail_pass", $fetch[7], 8 * time()+3600);
*/

//        header("Location: main.php");

	}else{
		session_start(); 
		session_destroy();
		unset($_SESSION);
		setcookie("logado");
		setcookie("classe");
		setcookie("usuario");
		setcookie("cod_user");
		setcookie("email");
		setcookie("mail_pass");
//		echo "Usuario ou senha invalido.";
	}
  	
	  $conexao->close();
	  

	  function createMenu($class){

		$arquivo = "../../config/menu.json";
		$fp = fopen($arquivo, "r");
		$menu_json = fread($fp, filesize($arquivo));
		fclose($fp);

		$menu = json_decode($menu_json);

		$out_menu=[];

		foreach ($menu->menu as $value) {

			if (in_array($class, $value->perm)) {
				$menu_item = (object) array(
					"modulo" => $value->modulo,
					"link" => $value->link,
					"icone" => $value->icone,
					"id" => $value->id,
					"itens" => array()
				) ;
				
				foreach ($value->itens as $item ) {
					if (in_array($class, $item->perm)) {

						$sub_menu_item = (object) array(
							"nome" => $item->nome,
							"link" => $item->link,
							"open" => $item->open,
							"icone" => $item->icone,
							"id" => $item->id,
							"edit" => $item->edit
						) ;
						array_push($menu_item->itens, $sub_menu_item);						
					}				
					
				}

				array_push($out_menu,$menu_item);

			}

		}

		return json_encode($out_menu);

	  }


?>