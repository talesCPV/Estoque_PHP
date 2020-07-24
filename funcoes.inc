<?php

// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value

function minusculas($string)
    {
        $low=array("Ã" => "ã", "Á" => "á", "É" => "é", "Í" => "í", "Ó" => "ó", "Ú" => "ú", "Ü" => "ü", "não" => "não","Ç" => "ç");
        return strtolower(strtr($string,$low));
    }

  function camel($string){
    $aux = "";
    $resp = "";

    for($i = 0; $i < strlen($string); $i++){
      if(substr($string, $i, 1) == " "){
        if (strlen(trim($aux)) <= 3 && minusculas(substr(trim($aux), 0, 1)) == "d"){
          $resp = $resp . " " . minusculas($aux);
        }else{
          $resp = $resp . " " . ucfirst(minusculas($aux));
        }

        $aux = "";
      }else{
        $aux = $aux . substr($string, $i, 1);
      }
    }
    $resp = $resp . " " . ucfirst(minusculas($aux));

      return trim($resp);
  }

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
    $Mun = camel($Mun);
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



    function post_id($id,$value)
    {
      global $dados;
      $text = "";
      $achou = 0;

      if (file_exists($dados)) {
        $fp = fopen($dados, "r");
        while (!feof ($fp)) {  // varre as linhas do arquivo
          $linha = fgets($fp,4096);
          if($id == substr($linha, 0, 3)){ // achou o indice na linha
            $text = $text . $id . "|" . $value . "\r\n";  // $text recebe a nova linha no lugar
            $achou = 1;
          }else{ //  não achou o indice na linha
            $text = $text . $linha; // $text recebe a linha sem alterar o valor
          }
        }
        fclose($fp);
       }

      if($achou == 0){ // não achou o indice procurado
        $text = $text . $id . "|" . $value . "\r\n"; //  adiciona a nova linha embaixo do arquivo    
      }

      $fp = fopen($dados, "w"); // abrir para regravar
      fwrite($fp, $text);
      fclose($fp);

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
    $json = json_decode($response, true);

    if ($ida_volta){
        return $json['rows'][0]['elements'][0]['distance']['value'] * 2;
    }else{
        return $json['rows'][0]['elements'][0]['distance']['value'];
    }

}

      function limpa_num($num)
      {
        $resp = "";
        
        for($i=0; $i< strlen($num); $i++){
          $R = $num[$i];
          if ($R == '0' ||$R == '1' ||$R == '2' ||$R == '3' ||$R == '4' ||$R == '5' ||$R == '6' ||$R == '7' ||$R == '8' ||$R == '9')
          {
            $resp = $resp . $num[$i];
          }
        }
      return $resp;
      }

    function gera_chave($cnpj,$Nnf)      
    {
      $uf = "35";                                       // cUF - Código da UF do emitente do Documento Fiscal;
      $data = substr(date('Y'), 2, 2).date('m');        // AAMM - Ano e Mês de emissão da NF-e;
      $tpEmis = "1";                                    // tpEmis – forma de emissão da NF-e;
      $cod = get_id("B02");                             // cNF - Código Numérico que compõe a Chave de Acesso;
      $mod = "55";                                      // mod - Modelo do Documento Fiscal;
      $ser = "001";                                     // serie - Série do Documento Fiscal;
      $Nnf = str_pad(trim($Nnf), 9, '0', STR_PAD_LEFT); // nNF - Número do Documento Fiscal;

      $chave = $uf.$data.trim($cnpj).$mod.$ser.$Nnf.$tpEmis.$cod ;

      $dv = 0;
      $mult = 2;
      for($i = 1;$i < strlen($chave)+1; $i++){
        $dv = $dv + ($chave[strlen($chave)-$i] * $mult);
        $mult++;

        if ($mult > 9){
          $mult = 2;
        }
      }

      $resto = $dv%11;
      $dv = 11 - $resto;

      if($dv > 9){
        $dv = 0;
      }

      return ("A|4.00|NFe" . $chave . $dv ."| \r\n");
    }


    function get_id($id)
    {
        global $dados;
        if (file_exists($dados)) {
            $fp = fopen($dados, "r");
            while (!feof ($fp)) {
                $linha = fgets($fp,4096);
                if($id == substr($linha, 0, 3)){
                    fclose($fp);
                    $resp = trim(substr($linha, 4, strlen($linha)-4));
                    $resp = tirarAcentos($resp);
                    return $resp;
                }
            }
            fclose($fp);
        }
        return "";
    }



      function get_post_action($name)
      {
          $params = func_get_args();

          foreach ($params as $name) {
              if (isset($_POST[$name])) {
                  return $name;
              }
          }
      }

      function monta()
      {
          $topo = "NOTAFISCAL|1 \r\n";

        global $file, $dados, $A, $B, $C, $C02, $C05, $E, $E02, $E05;

        if (file_exists($dados)) {
            $A = gera_chave(get_id("C08"),get_id("B06"));
            $B = "B|".get_id("B01")."|".get_id("B02")."|".get_id("B03")."|".get_id("B04")."|".get_id("B05")."|".get_id("B06")."|".get_id("B07")."|".get_id("B08")."|".get_id("B09")."|".get_id("B10")."|".get_id("B11")."|".get_id("B12")."|".get_id("B13")."|".get_id("B14")."|".get_id("B15")."|".get_id("B16")."|".get_id("B17")."|".get_id("B18")."|".get_id("B19")."|".get_id("B20")."|".get_id("B21")."|".get_id("B22")."|\r\n";
            $C = "C|".get_id("C01")."|".get_id("C02")."|".get_id("C03")."|".get_id("C04")."|".get_id("C05")."|".get_id("C06")."|".get_id("C07")."|\r\n";
            $C02 = "C02|".get_id("C08")."|\r\n";
            $C05 = "C05|".get_id("C09")."|".get_id("C10")."|".get_id("C11")."|".get_id("C12")."|".get_id("C13")."|".get_id("C14")."|".get_id("C15")."|".get_id("C16")."|".get_id("C17")."|".get_id("C18")."|".get_id("C19")."|\r\n";
            $E = "E|".get_id("E01")."|".get_id("E02")."|".get_id("E03")."|".get_id("E04")."|".get_id("E05")."|".get_id("E06")."|\r\n";
            $E02 = "E02|".get_id("E07")."|\r\n";
            $E05 = "E05|".get_id("E08")."|".get_id("E09")."|".get_id("E10")."|".get_id("E11")."|".get_id("E13")."|".get_id("E12")."|".get_id("E14")."|".get_id("E15")."|".get_id("E16")."|".get_id("E17")."|".get_id("E18")."|\r\n";
        }else{
            $A = "A|4.00||\r\n";
            $B = "B|35||VENDA|55|1||||1|1|3508504|1|1||1|1|0|0|3||||\r\n";
            $C = "C|FLEXIBUS SANFONADOS LTDA|FLEXIBUS|234033845113||||1|\r\n";
            $C02 = "C02|00519547000106|\r\n";
            $C05 = "C05|AV. DR. ROSALVO DE ALMEIDA TELLES|2070||NOVA CAÇAPAVA|3508504|Caçapava|SP|12283020|1058|BRASIL|1236532230|\r\n";
        }

        $fp = fopen($file, "w");
        fwrite($fp, $topo.$A.$B.$C.$C02.$C05.$E.$E02.$E05);
        fclose($fp);
      }

      function export_nfe($cab,$it,$rod="")
      {
        $fp = fopen("config/NOTAFISCAL.txt", "w");
        fwrite($fp,$cab.$it.$rod);
        fclose($fp);
      }


      function make_array($line){ // Recebe uma string e separa os dados entre "|"
        $val = "";
        $count = 0;
        $resp = [];
        for($i = 0; $i < strlen($line); $i++){
          if(substr($line, $i, 1) == "|"){
            $resp[$count] = $val;
            $val = "";
            $count++;
          }else{
            $val = $val . substr($line, $i, 1);
          }

        }

        return $resp;

      }


function tirarAcentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
}


    function post_etq($id,$value,$path = "config/etiqueta.cfg \r\n")
    {
      $text = "";
      $achou = 0;

      if (file_exists($path)) {
        $fp = fopen($path, "r");
        while (!feof ($fp)) {  // varre as linhas do arquivo
          $linha = fgets($fp,4096);
          if($id == substr($linha, 0, 3)){ // achou o indice na linha
            $text = $text . $id . "|" . $value . "\r\n";  // $text recebe a nova linha no lugar
            $achou = 1;
          }else{ //  não achou o indice na linha
            $text = $text . $linha; // $text recebe a linha sem alterar o valor
          }
        }
        fclose($fp);
       }

      if($achou == 0){ // não achou o indice procurado
        $text = $text . $id . "|" . $value . "\r\n"; //  adiciona a nova linha embaixo do arquivo    
      }

      $fp = fopen($path, "w"); // abrir para regravar
      fwrite($fp, $text);
      fclose($fp);
    }

    function get_etq($id,$path = "config/etiqueta.cfg \r\n")
    {
        if (file_exists($path)) {
            $fp = fopen($path, "r");
            while (!feof ($fp)) {
                $linha = fgets($fp,4096);
                if($id == substr($linha, 0, 3)){
                    fclose($fp);
                    $resp = trim(substr($linha, 4, strlen($linha)-4));
                    $resp = tirarAcentos($resp);
                    return $resp;
                }
            }
            fclose($fp);
        }
        return "";
    }

  function CNPJ($str)
  {
    $resp = "";
    for($i = 0; $i < strlen($str); $i++){
      if($i == 2){ $resp = $resp . "."; }
      if($i == 5){ $resp = $resp . "."; }
      if($i == 8){ $resp = $resp . "/"; }
      if($i == 12){ $resp = $resp . "-"; }
      $resp = $resp . substr($str, $i, 1);
    }
    return ($resp);

  }

?>