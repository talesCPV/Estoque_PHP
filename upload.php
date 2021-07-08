<?php

  function metadata($path, $cod, $dest){
//    GRAVA no Banco
    include "conecta_mysql.inc";
    if (!$conexao)
        die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

        if($dest == "compra"){
            $query = "UPDATE tb_entrada SET path= '{$path}' WHERE id={$cod};";
        }else if($dest == "img"){
            $query = "UPDATE tb_produto SET img_path= '{$path}' WHERE cod={$cod};";
        }else{
            $query = "UPDATE tb_pedido SET path= '{$path}', status='PAGO', data_ped = now() WHERE id={$cod};";
        }

      
//echo $query;      

      mysqli_query($conexao, $query);
    
      $conexao->close();
  }

//echo "fora<br>";


  if (IsSet($_FILES["up_file"])){    

//echo "entrou<br>" ;

    $cod = $_POST["cod"];
    $e_id = $_POST["eid"];
    $destino = $_POST["destino"];
    $tipo = $_POST["tipo"];
    $folder = $_POST["folder"];
    $arquivo = $_FILES["up_file"]["name"];
    $file_name = $folder.$e_id."_".$cod.$tipo;

//    echo $file_name;
    
    copy($_FILES["up_file"]["tmp_name"],$file_name);




    metadata($file_name, $cod, $destino);

    if($destino == 'compra'){
        header('Location: pesq_ent.php'); 
    }else if($destino == 'img'){
        header('Location: pesq_prod.php'); 
    }else{
        header('Location: pesq_ped.php'); 
    }


  }
?>