<?php

// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value
function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}


function IBGE_UF($UF){
    $result = "";
    $response = CallAPI("GET","https://servicodados.ibge.gov.br/api/v1/localidades/estados");
    $json = json_decode($response);

    foreach ($json as $aux){
      $count = 0;
      foreach ($aux as $field)
      {        
          
        if ($count == 0){
           $cUF = $field;
        }else{
            if($field == trim($UF)){
                return $cUF;
            }
        }

        $count++;
        if ($count == 2){
        break;
        }

      }
    }
    return $result;
}


function IBGE_Mun($UF,$Mun){
    $result = "";

    $cUF = IBGE_UF($UF);

    $response = CallAPI("GET","https://servicodados.ibge.gov.br/api/v1/localidades/estados/".$cUF."/municipios");
    $json = json_decode($response);

    foreach ($json as $aux){
      $count = 0;
      foreach ($aux as $field)
      {        
          
        if ($count == 0){
           $cMun = $field;
        }else{
            if($field == trim($Mun)){
                return $cMun;
            }
        }

        $count++;
        if ($count == 2){
        break;
        }

      }
    }
    return $result;
}


?>