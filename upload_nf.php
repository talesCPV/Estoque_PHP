<?php

  function metadata($path, $cod, $dest){
//    GRAVA no Banco
    include "conecta_mysql.inc";
    if (!$conexao)
        die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

      if($dest == "compra"){
          $query = "UPDATE tb_entrada SET path= '{$path}' WHERE id={$cod};";
      }else{
          $query = "UPDATE tb_pedido SET path= '{$path}', status='PAGO', data_ped = now() WHERE id={$cod};";
      }

      
//echo $query;      

      mysqli_query($conexao, $query);
    
      $conexao->close();
  }

//echo "fora<br>";


  if (IsSet($_FILES["up_pdf"])){

//echo "entrou<br>" ;

    $cod = $_POST["cod"];
    $e_id = $_POST["eid"];
    $destino = $_POST["destino"];
    $arquivo = $_FILES["up_pdf"]["name"];
    $file_name = "nf/{$destino}/".$e_id."_".$cod.".pdf";
          
    copy($_FILES["up_pdf"]["tmp_name"],$file_name);


//echo $file_name;

    metadata($file_name, $cod, $destino);

    if($destino == 'compra'){
      header('Location: pesq_ent.php'); 
    }else{
      header('Location: pesq_ped.php'); 
    }
 

  }
?>