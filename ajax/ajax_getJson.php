<?php

  $resp = "";

	if (IsSet($_POST["path"])){
    $path = $_POST["path"];
    
//    echo ($path."<br>");
	 
      if (file_exists($path)) {

        $fp = fopen($path, "r");
          while (!feof ($fp)) {
              $resp = $resp . fgets($fp,4096);
          }
          fclose($fp);
        }
    }

    echo($resp); 

//    $json_str = json_decode($resp, true);

//		foreach ( $json_str['seguranca'] as $e ) {
//        echo($e['url']);

//		} 



//    $rows = explode("\r\n", $resp);

//	print json_encode($rows);

?>