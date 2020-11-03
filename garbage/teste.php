<?php

    function clear_num($num){
        $out = "";
        $perm = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0"); 
        for($i=0;$i<strlen($num);$i++){
            if (in_array($num[$i], $perm)) { 
                $out = $out . $num[$i];
            }        
        }
        return $out;
    }


echo "Página de Teste<br>";

$texto = "00.519.547/0001-06";

echo $texto."<br>";

echo clear_num($texto)."<br>";


?>