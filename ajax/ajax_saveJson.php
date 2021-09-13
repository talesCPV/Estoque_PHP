<?php

  if (IsSet($_POST["path"])){
      $path = $_POST["path"];
      $json = $_POST["json"];

      $fp = fopen($path, "w");
      fwrite($fp, $json);
      fclose($fp);
      return true;

    }else{

      return false;

    }

?>