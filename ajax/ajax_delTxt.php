<?php

  if (IsSet($_POST["path"])){
    $path = $_POST["path"];
   
      if (file_exists($path)) {
          unlink($path);          
        }
    }
    
?>