<?php

  $resp = "";

	if (IsSet($_POST["path"])){
    $path = $_POST["path"];
    
    echo ($path."<br>");
	 
      if (file_exists($path)) {
          $fp = fopen($path, "r");
          while (!feof ($fp)) {
              $resp = $resp . fgets($fp,4096);
          }
          fclose($fp);
        }
    }

    echo($resp); 

    $rows = explode("\r\n", $resp);

	print json_encode($rows);

?>