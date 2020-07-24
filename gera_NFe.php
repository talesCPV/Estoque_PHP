<?php

    include "funcoes.inc";  

    $file = "config/NF.txt";

  function download($arquivo){
      header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream;");
        header("Content-Length:".filesize($arquivo));
        header("Content-disposition: attachment; filename=".$arquivo);
        header("Pragma: no-cache");
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Expires: 0");
        readfile($arquivo);
        flush();
  }

  if (file_exists($file)) {
    download($file);
  }else{
    echo"Arquivo não gerado";
  }




?>