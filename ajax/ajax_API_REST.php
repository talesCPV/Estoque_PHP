<?php

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

function tira_espacos($string){
    $resp = "";
    for($i=0;$i < strlen($string);$i++){
        if(substr($string,$i,1) != " "){
            $resp = $resp . substr($string,$i,1);
        }
    }
    return $resp;
}

function distancia($origem,$destino,$ida_volta=false){
    $result = 0;
    $origem = utf8_decode(tira_espacos($origem));
    $destino = utf8_decode(tira_espacos($destino));
    $key = "AIzaSyD06swca9-Qs7pxu_295dHm6NI-UmStL7M";
    $URL = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".$origem."&destinations=".$destino."&key=".$key;

    $response = CallAPI("GET",$URL); 
//    $json = json_decode($response, true);

    print $response;
/*
    if ($ida_volta){
        return $json['rows'][0]['elements'][0]['distance']['value'] * 2;
    }else{
        return $json['rows'][0]['elements'][0]['distance']['value'];
    }
*/
}


if (IsSet($_POST["origins"])){
    distancia($_POST["origins"],$_POST["destinations"]);
}

?>