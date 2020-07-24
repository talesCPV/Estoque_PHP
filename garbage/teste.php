<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8">
    <title>Menu Principal</title>
     <!-- Aqui chamamos o nosso arquivo css externo -->
    <link rel="stylesheet" type="text/css"  href="estilo.css" />

</head>

<body>
  
<?php
  include "funcoes.inc";

//echo IBGE_UF("SP");

//  echo IBGE_Mun("SP ",  "Taubaté ");

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
      $uf = "35";
      $data = substr(date('Y'), 2, 2).date('m');
      $cod = rand ( 100000000 , 999999999 );
      $mod = "55";
      $ser = "001";
      $Nnf = str_pad(trim($Nnf), 9, '0', STR_PAD_LEFT);

      $chave = $uf.$data.trim($cnpj).$mod.$ser.trim($Nnf).$cod ;

      $dv = 0;
      $mult = 2;
      echo $chave ."<br>" ;
//      echo strlen($chave)."<br>";
      for($i = 1;$i < strlen($chave)+1; $i++){
        echo $chave[strlen($chave)-$i] ;
        echo " x ".$mult."<br>" ;
        $dv = $dv + ($chave[strlen($chave)-$i] * $mult);
        $mult++;

        if ($mult > 9){
          $mult = 2;
        }


      }

      echo "CONTA: ". $dv ."<br>" ;

      $resto = $dv%11;

      echo "RESTO: ".$resto ."<br>" ;

      $dv = 11 - $resto;

      if($dv > 9){
        $dv = 0;
      }

      echo "DV: ". (11 - $resto) ."<br>" ;

      return ("NFe" . $chave . $dv);

      }


//echo limpa_num("15.25/3,32")."<br>";


//echo gera_chave();

//$data = substr(date('Y'), 2, 2).date('m');
//$cod = rand ( 10000000 , 99999999 );

//echo gera_chave("00519547000106","1698")  ."<br>";

$txt = "120";

$dias_parc = explode("/", $txt); 
$qtd_parc = count($dias_parc);

echo "Texto: ".$txt. "<br>";
echo "Qtd: ".$qtd_parc. "<br>";
//echo "Texto: ".$txt. "<br>";

for($i=0;$i<$qtd_parc;$i++){
  echo "parcelas: ".$dias_parc[$i]. "<br>";
  echo date('d/m/Y', strtotime("+".$dias_parc[$i]."days",strtotime(date('Y-m-d')))). "<br>";
}


//$Nnf = "    1054";

//$Nnf = str_pad(trim($Nnf), 9, '0', STR_PAD_LEFT);

//echo $Nnf . "<br/>";

?>

</body>
</html>
