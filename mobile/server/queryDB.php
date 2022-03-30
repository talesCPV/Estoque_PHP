<?php

    if (IsSet($_POST ["query"])){

        $query = $_POST ["query"];

    	include "../../conecta_mysql.inc";

        $result = mysqli_query($conexao, $query);
        
        $qtd_lin = $result->num_rows;

        if($qtd_lin > 0){
                        
            $lines = array();

            while($fetch = mysqli_fetch_assoc($result)) {
               array_push($lines, $fetch);
            }
            print( json_encode($lines) );

        }

    }

?>