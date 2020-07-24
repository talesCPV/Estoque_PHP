<?php

 $resp = "";

  if (IsSet($_POST["path"])){
    $path = $_POST["path"];
    $row = $_POST["row"];
    $i = 0;
   
      if (file_exists($path)) {
          $fp = fopen($path, "r");
          while (!feof ($fp)) {
              if ($i != $row){
                  $resp = $resp . fgets($fp,4096);
              }else{
                fgets($fp,4096);
              }
              $i = $i + 1;
          }
          fclose($fp);

          $fp = fopen($path, "w");
          fwrite($fp, $resp);
          fclose($fp);
        }
    }

    $rows = explode("\r\n", $resp);

  print json_encode($rows);

?>