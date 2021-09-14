<?php

  $resp = "";
//  echo getcwd();
	if (IsSet($_POST["path"])){
    $path = $_POST["path"];
    $files = scandir($path);

    $resp = json_encode($files);

    }



    print($resp); 

//    $rows = explode("\r\n", $resp);

//	print json_encode($rows);

?>