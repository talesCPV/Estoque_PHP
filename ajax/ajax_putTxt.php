<?php

  if (IsSet($_POST["path"])){
      $path = $_POST["path"];
      $row = $_POST["row"];

      print($path . ' - '. $row);

      $fp = fopen($path, "a");
      fwrite($fp, $row);
      fclose($fp);
      return true;

    }else{

      return false;

    }

?>