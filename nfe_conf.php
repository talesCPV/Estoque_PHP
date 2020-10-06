<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
    <title>Configuracoes</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/funcoes.js"></script>
</head>
<body>


  <header>
    <?php
      include "menu.inc";
      include "funcoes.inc";  


      $file = "config/nfe.cfg \r\n";
      $file_itens = "config/itens.cfg \r\n";
      $dados = "config/dados.cfg";
      $NF = "config/NF.txt";


      function Add_Item($Cod_Pedido){

        $value = explode("/", $Cod_Pedido);

        $itens_txt = "";

        include "conecta_mysql.inc";

        if (!$conexao)
          die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());


        if(count($value) > 1){ // adicionar varios ítens neste select
          $ped =  " (";

          foreach ($value as $aux) {
            $ped = $ped . "i.id_ped = '".$aux."' or ";
            echo "Pedido ".$aux." adicionado.<br>";
          }
           echo "<BR><b> VERIFIQUE O CLIENTE E CLIQUE EM SALVAR. </b><BR>";

          $ped = substr($ped, 0, strlen($ped)-3 ) .") "; // Apaga o ultimo "or" e fecha os ")"

          $query =  "SELECT p.cod, p.descricao, p.ncm, p.unidade, i.qtd, i.preco, p.tipo, i.und
                    FROM tb_item_ped as i 
                    INNER JOIN tb_produto AS p
                    ON ". $ped ." AND i.id_prod = p.id;";

        }else{
          $query =  "SELECT p.cod, p.descricao, p.ncm, p.unidade, i.qtd, i.preco, p.tipo, i.und
                    FROM tb_item_ped as i 
                    INNER JOIN tb_produto AS p
                    ON i.id_ped = '". $Cod_Pedido ."' AND i.id_prod = p.id;";

        }        


        $result = mysqli_query($conexao, $query);

        $qtd_itens = $result->num_rows;

        $total = 0;
        $count = 1;

        while($fetch = mysqli_fetch_row($result)){
          $itens_txt = $itens_txt .  "H|".$count."||\r\n";
          $count++;

          $linha_txt = "I|"; 
          $linha_txt = $linha_txt . trim($fetch[0])."|"; //cProd
          $linha_txt = $linha_txt ."|"; //cEAN
          if(trim($fetch[6]) == 'TINTA'){
            $linha_txt = $linha_txt . strtoupper(trim($fetch[1]))." - ".trim($fetch[7])."|"; //xProd
          }else{
            $linha_txt = $linha_txt . strtoupper(trim($fetch[1]))."|"; //xProd
          }
          $linha_txt = $linha_txt . trim($fetch[2])."|"; //NCM
          $linha_txt = $linha_txt ."|"; //EXTIPI

          if(get_id("C15") == get_id("E14")){
            $prefixo = "5";
          }else{
            $prefixo = "6";
          }
          if(strlen(trim($fetch[0])) == 3){
             $sufixo = "101";
          }else{
             $sufixo = "102";
          }

          $linha_txt = $linha_txt . $prefixo . $sufixo . "|"; // uNCM

          $linha_txt = $linha_txt . trim($fetch[3])."|"; //uCom
          $linha_txt = $linha_txt . number_format($fetch[4],4, '.','')."|"; //qCom
          $linha_txt = $linha_txt . number_format($fetch[5],10, '.','')."|"; //vUnCom // <- Aqui............number_format($number, 2, '.', '') 
          $linha_txt = $linha_txt . number_format($fetch[4] * $fetch[5], 2, '.', '')."|"; //vProd
          $linha_txt = $linha_txt ."|"; //cEANTrib
          $linha_txt = $linha_txt . trim($fetch[3])."|"; //uTrib
          $linha_txt = $linha_txt . number_format($fetch[4],4, '.','')."|"; //qTrib
          $linha_txt = $linha_txt . number_format($fetch[5],10, '.','')."|";
          $linha_txt = $linha_txt ."|"; //vFrete
          $linha_txt = $linha_txt ."|"; //vSeg
          $linha_txt = $linha_txt ."|"; //vDesc
          $linha_txt = $linha_txt ."|"; //vOutro
          $linha_txt = $linha_txt ."1|"; //indTot
          $linha_txt = $linha_txt ."|"; //xPed
          $linha_txt = $linha_txt ."|"; //nItemPed
          $linha_txt = $linha_txt ."|"; //nFCI
          $linha_txt = $linha_txt ."|"; //
          $linha_txt = $linha_txt ."|"; //
          $linha_txt = $linha_txt ."|\r\n"; //


        $M = "M||\r\n";
        if(strlen(trim(get_id("E03"))) == "" ){
          $N = "N|\r\nN10d|0|102|\r\n";
        }else{
          $N = "N|\r\nN10c|0|101|2.82|" . number_format($fetch[4] * $fetch[5] * 0.0282,2,'.','')."|\r\n";
        }
        $O = "O||||999|\r\nO07|99|0.00|\r\nO10|0.00|0.0000|\r\nQ|\r\nQ05|99|0.00|\r\nQ07|0.00|0.0000|\r\nS|\r\nS05|99|0.00|\r\nS07|0.00|0.0000|\r\n";

        $MNO = $M . $N .$O; //"M||\r\nN|\r\nN10d|0|102|\r\nO||||999|\r\nO07|99|0.00|\r\nO10|0.00|0.0000|\r\nQ|\r\nQ05|99|0.00|\r\nQ07|0.00|0.0000|\r\nS|\r\nS05|99|0.00|\r\nS07|0.00|0.0000|\r\n";




          $itens_txt = $itens_txt . $linha_txt . $MNO;

          $total = $total + $fetch[5] * $fetch[4];
// arredondamento esta por aqui



        }

        post_id("TOT",$total);

        $W = "W|\r\nW02|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|".number_format(get_id("TOT"), 2, '.', '')."|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|0.00|".number_format(get_id("TOT"), 2, '.', '')."|0.00|\r\nW04c|0.00|\r\nW04e|0.00|\r\nW04g|0.00|\r\nX|0|\r\n";

        $itens_txt = $itens_txt . $W;

//        echo $itens_txt; 


        $conexao->close();
        global $file_itens;
        $fp = fopen($file_itens, "w");
        fwrite($fp, $itens_txt);
        fclose($fp);



      }


  if (file_exists($file)) {
    $fp = fopen($file, "r");
    while (!feof ($fp)) {
      $str_line = fgets($fp,4096);
      $linha = make_array($str_line); // Transforma a linha num array
      if (sizeof($linha) > 0){
        switch ($linha[0]) {
          case 'B':
            $B = $str_line;
            break;
          case 'C':
            $C = $str_line;
            break;
          case 'C02':
            $C02 = $str_line;
            break;
          case 'C05':
            $C05 = $str_line;
            break;
          default:
              //no action sent
        }
      }
    } 

    fclose($fp);
  }

    ?>
  </header>

<div class="page_container">  
  <div class="page_form">
    <p class="logo"> Configuracao de NFe</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="#" >
      <table>
        <tr>
          <td><button name="emitente" type="submit">Emitente</button></td>
          <td><button name="fiscal" type="submit">Fiscal</button></td>
          <td><button name="pedido" type="submit">Pedido</button></td>
          <td><button name="itens" type="submit">Ítens</button></td>
          <td><button name="fatura" type="submit">Fatura</button></td>
        </tr>
      </table>
      
    </form>    
  </div>


  <div class="page_form" id="no_margin">

    <?php
      global $file, $topo, $A, $B, $C, $C02, $C05;
      switch (get_post_action('emitente', 'fiscal', 'save_emit', 'save_fiscal', 'pedido', 'save_ped', 'itens', 'fatura', 'save_itens', 'save_fatura','add_bol')) {
          case 'emitente':

            echo" <p class=\"logo\"> Dados do Emitente</p> <br>";
            echo"
              <form class=\"login-form\" name=\"cadastro\" method=\"POST\" action=\"#\" onsubmit=\"return validaCampo(new Array(cadastro.nome)); return false;\">
                <label> Razão Social *</label>
                <input type=\"text\" name=\"xNome\" maxlength=\"60\" value=\"". get_id("C01") ."\" />
                <label> Nome Fantasia </label>
                <input type=\"text\" name=\"xFant\" maxlength=\"60\" value=\"". get_id("C02")."\"/>
                <label> Insc. Estadual </label>
                <input type=\"text\" name=\"IE\" maxlength=\"14\" value=\"". get_id("C03")."\" />
                <input type=\"hidden\" name=\"IEST\" value=\"". get_id("C04")."\"  >                
                <label> Insc. Municipal </label>
                <input type=\"text\" name=\"IM\" maxlength=\"15\" value=\"". get_id("C05") ."\" />
                <label> CNAE </label>
                <input type=\"text\" name=\"CNAE\" maxlength=\"7\"  value=\"". get_id("C06") ."\"/>
                <label> Regime Tributário </label>
                <select name=\"CRT\" >
                  <option "; if(get_id("C07") == '1'){echo" selected ";} echo "value=\"1\">Simples Nacional</option>
                  <option "; if(get_id("C07") == '2'){echo" selected ";} echo "value=\"2\">Simples Nacional - excesso de sublimite de receita bruta</option>
                  <option "; if(get_id("C07") == '3'){echo" selected ";} echo "value=\"3\">Regime Normal.</option>
                </select>
                <label> CNPJ </label>
                <input type=\"text\" name=\"CNPJ\" maxlength=\"14\" value=\"". get_id("C08") ."\"/>
                <label> Endereço </label>
                <input type=\"text\" name=\"xLgr\" maxlength=\"60\" value=\"". get_id("C09") ."\" />
                <label> Número: </label>
                <input type=\"text\" name=\"nro\" maxlength=\"5\" value=\"". get_id("C10") ."\" />
                <label> Complemento </label>
                <input type=\"text\" name=\"cpl\" maxlength=\"60\"  value=\"". get_id("C11") ."\"/>
                <label> Bairro </label>
                <input type=\"text\" name=\"bairro\" maxlength=\"60\" value=\"". get_id("C12") ."\" />
                <label> Código do Municipio </label>
                <input type=\"text\" name=\"cMun\" maxlength=\"60\" value=\"". get_id("C13") ."\"/>
                <label>  Nome do Municipio</label>
                <input type=\"text\" name=\"xMun\" maxlength=\"60\" value=\"". get_id("C14") ."\"/>
                <label> Sigla da UF </label>
                <input type=\"text\" name=\"UF\" maxlength=\"60\" value=\"". get_id("C15") ."\" />
                <label> CEP </label>
                <input type=\"text\" name=\"CEP\" maxlength=\"8\" value=\"". get_id("C16") ."\" />
                <label> Código do País  </label>
                <input type=\"text\" name=\"cPais\" maxlength=\"4\" value=\"". get_id("C17") ."\" />
                <label> Nome do País </label>
                <input type=\"text\" name=\"xPais\" maxlength=\"60\" value=\"". get_id("C18") ."\"/>
                <label> Telefone </label>
                <input type=\"text\" name=\"fone\" maxlength=\"14\" value=\"". get_id("C19") ."\" />
                <td><button name=\"save_emit\" type=\"submit\">Salvar</button></td>
              </form>
            ";
            break;

          case 'fiscal':

            echo" <p class=\"logo\"> Dados Fiscais</p> <br>";

            echo"
              <form class=\"login-form\" name=\"cadastro\" method=\"POST\" action=\"#\" onsubmit=\"return validaCampo(new Array(cadastro.nome)); return false;\">
                <input type=\"hidden\" name=\"cUF\" value=\"35\" >      

                <input type=\"hidden\" name=\"cNF\" value=\"". rand (10000000,99999999)."\" >                

                <label> Natureza da Operação *</label>
                <input type=\"text\" name=\"natOp\" maxlength=\"60\" value=\"". get_id("B03") ."\" />

                <label> Código do Modelo do Documento Fiscal </label>
                <input type=\"text\" name=\"mod\" maxlength=\"2\" value=\"". get_id("B04") ."\"  />
            
                <input type=\"hidden\" name=\"serie\" value=\"1\" >                

                <label> Numero da NF</label>
                <input type=\"text\" name=\"nNF\" maxlength=\"9\" value=\"".(get_id("B06")+1) ."\" />

                <label> Data de emissão do Documento Fiscal </label>
                <input type=\"date\" name=\"dhEmi\" value=\"". date('Y-m-d') . "\" >

                <label> Data e hora de Saída ou da Entrada da Mercadoria/Produto </label>
                <input type=\"date\" name=\"dhSaiEnt\" value=\"". date('Y-m-d') . "\" >

                <label> Tipo de Operação</label>
                <select name=\"tpNF\" >
                  <option "; if(get_id("B09") == '0'){echo" selected ";} echo "value=\"0\">Entrada</option>
                  <option "; if(get_id("B09") == '1'){echo" selected ";} echo "value=\"1\">Saída</option>
                </select>

                <label> Identificador de Local de destino da operação</label>
                <select name=\"idDest\" >
                  <option "; if(get_id("B10") == '1'){echo"selected ";} echo "value=\"1\">Operação Interna</option>
                  <option "; if(get_id("B10") == '2'){echo"selected ";} echo "value=\"2\">Operação Interestadual</option>
                  <option "; if(get_id("B10") == '3'){echo"selected ";} echo "value=\"3\">Operação com o Exterior</option>
                </select>

                <input type=\"hidden\" name=\"cMunFG\" value=\"". get_id("B11") ."\" >                

                <label> Formato de Impressão do DANFE</label>
                <select name=\"tpImp\" >
                  <option "; if(get_id("B12") == '1'){echo"selected ";} echo "value=\"1\">Retrato</option>
                  <option "; if(get_id("B12") == '2'){echo"selected ";} echo "value=\"2\">Paisagem</option>
                </select>

                <label>Tipo de Emissão da NF-e</label>
                <select name=\"tpEmis\" >
                  <option "; if(get_id("B13") == '1'){echo"selected ";} echo "value=\"1\">Normal</option>
                  <option "; if(get_id("B13") == '2'){echo"selected ";} echo "value=\"2\">Contingência FS - emissão em contingência com impressão do DANFE em Formulário de Segurança</option>
                  <option "; if(get_id("B13") == '4'){echo"selected ";} echo " value=\"4\">Contingência EPEC - emissão em contingência com envio da Evento Prévio de Emissão em Contingência - EPEC</option>
                  <option "; if(get_id("B13") == '5'){echo"selected ";} echo " value=\"5\">Contingência FS-DA - emissão em contingência com impressão do DANFE em Formulário de Segurança para Impressão de Documento Auxiliar de Documento Fiscal Eletrônico (FS-DA)</option>
                  <option "; if(get_id("B13") == '6'){echo"selected ";} echo " value=\"6\">Contingência SVC-AN</option>
                  <option "; if(get_id("B13") == '7'){echo"selected ";} echo " value=\"7\">Contingência SVC-RS</option>
                  <option "; if(get_id("B13") == '9'){echo"selected ";} echo " value=\"9\">Contingência off-line NFC-e</option>
                </select>

                <input type=\"hidden\" name=\"cDV\" value=\"". get_id("B14") ."\" >                

                <label> Identificação do Ambiente</label>
                <select name=\"tpAmb\" >
                  <option "; if(get_id("B15") == '1'){echo"selected ";} echo "value=\"1\">Produção</option>
                  <option "; if(get_id("B15") == '2'){echo"selected ";} echo "value=\"2\">Homologação</option>
                </select>

                <label> Finalidade de emissão da NF-e</label>
                <select name=\"finNFe\" >
                  <option "; if(get_id("B16") == '1'){echo"selected ";} echo "value=\"1\">NF-e normal</option>
                  <option "; if(get_id("B16") == '2'){echo"selected ";} echo "value=\"2\">NF-e complementar</option>
                  <option "; if(get_id("B16") == '3'){echo"selected ";} echo "value=\"3\">NF-e de ajuste</option>
                </select>

                <label>Indica operação com o consumidor final</label>
                <select name=\"indFinal\" >
                  <option "; if(get_id("B17") == '0'){echo"selected ";} echo "value=\"0\">Normal</option>    
                  <option "; if(get_id("B17") == '1'){echo"selected ";} echo "value=\"1\">Consumidor Final</option>
                </select>

                <label>Indicador de presença do comprador no estabelecimento comercial no momento da operação</label>
                <select name=\"indPres\" >
                  <option "; if(get_id("B18") == '0'){echo"selected ";} echo "value=\"0\">Não se aplica (por exemplo Nota Fiscal Complementar ou de ajuste)</option>
                  <option "; if(get_id("B18") == '1'){echo"selected ";} echo "value=\"1\">Operação presencial</option>
                  <option "; if(get_id("B18") == '2'){echo"selected ";} echo "value=\"2\">Operação não presencial, pela Internet</option>
                  <option "; if(get_id("B18") == '3'){echo"selected ";} echo "value=\"3\">Operação não presencial, pelo tele atendimento</option>
                  <option "; if(get_id("B18") == '4'){echo"selected ";} echo "value=\"4\">NFC-e em operação com entrega em domicílio</option>
                  <option "; if(get_id("B18") == '5'){echo"selected ";} echo "value=\"5\">Operação não presencial, fora do estabelecimento</option>
                  <option "; if(get_id("B18") == '9'){echo"selected ";} echo "value=\"9\">Operação não presencial, outros</option>
                </select>

                <label>Processo de emissão da NF-e</label>
                <select name=\"procEmi\" >
                  <option "; if(get_id("B19") == '0'){echo"selected ";} echo "value=\"0\">emissão de NF-e com aplicativo do contribuinte</option>
                  <option "; if(get_id("B19") == '1'){echo"selected ";} echo "value=\"1\">emissão de NF-e avulsa pelo Fisco</option>
                  <option "; if(get_id("B19") == '2'){echo"selected ";} echo "value=\"2\">emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco</option>
                  <option "; if(get_id("B19") == '3'){echo"selected ";} echo "value=\"3\">emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco</option>
                </select>


                <input type=\"hidden\" name=\"verProc\" value=\"". get_id("B20") ."\" >                

                <input type=\"hidden\" name=\"dhCont\" value=\"". get_id("B21") ."\" >                

                <input type=\"hidden\" name=\"xJust\" value=\"". get_id("B22") ."\" >                

                <td><button name=\"save_fiscal\" type=\"submit\">Salvar</button></td>

              </form>
            ";
            break;

          case 'pedido': 
            echo" <p class=\"logo\"> Selecione o Pedido </p> <br>";
            echo"
                    <form class=\"login-form\" method=\"POST\" action=\"#\">
                    <table class=\"search-table\"  border=\"0\"><tr><td>
                      <label> Busca por: </label> </td><td>
                      <select id=\"selBusca\" name=\"campo_busca\">
                        <option value=\"todos\">Todos</option>
                        <option value=\"cod\">Codigo</option>
                        <option value=\"num_ped\">Numero</option>
                        <option value=\"cliente\">Cliente</option>
                        <option value=\"varios\">Vários Códigos</option>
                      </select></td><td>
                      <input type=\"text\" id=\"edtBusca\" name=\"valor_busca\" /></td><td>
                      <button name=\"pedido\" id=\"botao_inline\" type=\"submit\">OK</button></td></tr>
                    </table>
                    </form>";

            $qtd_lin = 0;
            if (IsSet($_POST ["campo_busca"])){
              global $conexao;
              post_id("VAR",0);

              include "conecta_mysql.inc";

              if (!$conexao)
                die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

                $campo = $_POST ["campo_busca"];
                $valor = $_POST ["valor_busca"];
                if ($campo == "todos"){

                      $query =  "SELECT p.id, p.num_ped, e.nome, e.ie, e.cnpj, e.endereco, p.status, e.num, e.bairro, e.cidade, e.estado, e.cep, e.tel, p.data_ped
                                 FROM tb_pedido AS p INNER JOIN tb_empresa AS e 
                                 ON p.id_emp = e.id AND p.status = 'FECHADO' ORDER BY p.data_ped DESC ;";

                }
                else
                if ($campo == "num_ped"){
                      $query =  "SELECT p.id, p.num_ped, e.nome, e.ie, e.cnpj, e.endereco, p.status, e.num, e.bairro, e.cidade, e.estado, e.cep, e.tel, p.data_ped
                                 FROM tb_pedido AS p INNER JOIN tb_empresa AS e 
                                 ON p.id_emp = e.id AND p.num_ped = '". $valor ."' AND p.status = 'FECHADO';";
                }
                else
                if ($campo == "cliente"){
                      $query =  "SELECT p.id, p.num_ped, e.nome, e.ie, e.cnpj, e.endereco, p.status, e.num, e.bairro, e.cidade, e.estado, e.cep, e.tel, p.data_ped
                                 FROM tb_pedido AS p INNER JOIN tb_empresa AS e 
                                 ON p.id_emp = e.id AND e.nome LIKE '%".$valor."%' AND p.status = 'FECHADO' ORDER BY p.data_ped DESC ;";

                }
                else
                if ($campo == "cod"){
                      $query =  "SELECT p.id, p.num_ped, e.nome, e.ie, e.cnpj, e.endereco, p.status, e.num, e.bairro, e.cidade, e.estado, e.cep, e.tel, p.data_ped
                                 FROM tb_pedido AS p INNER JOIN tb_empresa AS e 
                                 ON p.id_emp = e.id AND p.id = '". $valor ."' AND p.status = 'FECHADO';";
                }
                else
                if ($campo == "varios"){
                  if (IsSet($_POST ["valor_busca"])){

                    Add_Item($_POST ["valor_busca"]);
                    $aux = explode("/", $_POST ["valor_busca"]);
                    $valor = $aux[0];
                    post_id("VAR",1);
                    post_id("VTX",$_POST ["valor_busca"]);
                  }
                      $query =  "SELECT p.id, p.num_ped, e.nome, e.ie, e.cnpj, e.endereco, p.status, e.num, e.bairro, e.cidade, e.estado, e.cep, e.tel, p.data_ped
                                 FROM tb_pedido AS p INNER JOIN tb_empresa AS e 
                                 ON p.id_emp = e.id AND p.id = '". $valor ."' AND p.status = 'FECHADO';";

                }


              $result = mysqli_query($conexao, $query);

              $qtd_lin = $result->num_rows;

  

              echo"  
                  <table class=\"search-table\" id=\"tabItens\">
                            <tr>
                              <th>Cod.</th>
                              <th>Numero</th>
                              <th>Cliente</th>
                              <th>CNPJ</th>
                              <th>Data</th>
                      </tr>";
                        while($fetch = mysqli_fetch_row($result)){
                          $cod_ped = $fetch[0];
                          $num_ped = $fetch[1];
                          $status = $fetch[6];
                          $xNome = $fetch[2];
                          $IE = $fetch[3]; 
                          $CNPJ = $fetch[4]; 
                          $xLgr = $fetch[5]; 
                          $nro = $fetch[7]; 
                          $xBairro = $fetch[8];
                          $xMun = $fetch[9];
                          $UF = $fetch[10];
                          $CEP = $fetch[11];
                          $fone = $fetch[12];

                            echo "<tr class='tbl_row' id='".$fetch[0]."'><td>" .$fetch[0] . "</td>".
                             "<td>" .$fetch[1] . "</td>".
                             "<td>" .$fetch[2] . "</td>".
                               "<td>" . $fetch[4] . "</td>".
                               "<td>" . date('d/m/Y', strtotime($fetch[13])) . "</td></tr>";

                        }


                      echo"
                  </table> 
                ";

              if ($qtd_lin == 1){


          echo " <br><br>

                <form class=\"login-form\" method=\"POST\" action=\"#\">
                  <input type=\"hidden\" name=\"cod_ped\" value=\"".trim($cod_ped) ."\" >                
                  <input type=\"hidden\" name=\"num_ped\" value=\"".trim($num_ped) ."\" >                
                  <input type=\"hidden\" name=\"xNome\" value=\"".trim($xNome) ."\" >                

                  <label class=\"logo\"> Finalidade de emissão da NF-e</label>
                  <select name=\"indIEDest\" >
                    <option "; if(trim($IE) != ''){echo"selected ";} echo "value=\"1\">Contribuinte ICMS (informar a IE do destinatário no cadastro da empresa)</option>
                    <option "; if(trim($IE) == ''){echo"selected ";} echo "value=\"2\">Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS</option>
                    <option value=\"9\">Não Contribuinte, que pode ou não possuir Inscrição Estadual no Cadastro de Contribuintes do ICMS</option>
                  </select>
                  <input type=\"hidden\" name=\"IE\" value=\"". trim($IE) ."\" >                
                  <label class=\"logo\"> Inscrição na SUFRAMA</label>
                  <input type=\"text\" name=\"ISUF\" maxlength=\"9\" value=\"\"  />
                  <input type=\"hidden\" name=\"IM\" value=\"\" >                
                  <label class=\"logo\"> Email</label>
                  <input type=\"text\" name=\"email\" maxlength=\"60\" value=\"\"  />
                  <input type=\"hidden\" name=\"CNPJ\" value=\"". trim($CNPJ) ."\" >                
                  <input type=\"hidden\" name=\"xLgr\" value=\"". trim($xLgr) ."\" >                
                  <input type=\"hidden\" name=\"nro\" value=\"". trim($nro) ."\" >                
                  <input type=\"hidden\" name=\"XCpl\" value=\"\" >                
                  <input type=\"hidden\" name=\"xBairro\" value=\"".trim($xBairro)."\" >                
                  <input type=\"hidden\" name=\"cMun\" value=\"\" >                
                  <input type=\"hidden\" name=\"xMun\" value=\"".trim($xMun)."\" >                
                  <input type=\"hidden\" name=\"UF\" value=\"".trim($UF)."\" >                
                  <input type=\"hidden\" name=\"CEP\" value=\"". limpa_num($CEP)."\" >                
                  <input type=\"hidden\" name=\"cPais\" value=\"1058\" >                
                  <input type=\"hidden\" name=\"xPais\" value=\"BRASIL\" >                
                  <input type=\"hidden\" name=\"fone\" value=\"".limpa_num($fone)."\" >                
                  <br><br><table>
                  <td><button name=\"save_ped\" type=\"submit\">Salvar</button></td></form>";
                  if(get_id("VAR")){
         echo"        <form class=\"login-form\" method=\"POST\" action=\"pdf_var.php\">
                      <td><button name=\"pdf_var\" type=\"submit\">Relatório</button></td></form>"; // este botão só aparece no caso de vários itens e manda pra pag. pdf_var.php
                  }


          echo"   </table>";
              }

            $conexao->close();

            }
            break;

          case 'itens': 
            echo" <p class=\"logo\"> CFOPs</p> <br>";
            echo" <form class=\"login-form\" name=\"cadastro\" method=\"POST\" action=\"#\" />";

            if (file_exists($file_itens)){
              $fp = fopen($file_itens, "r");
              $count = 1;
              while (!feof ($fp)) {
                  $linha = fgets($fp,4096);
                  if(substr($linha, 0, 1) == "I"){
                      $resp = make_array($linha);
                      echo " <label>". $resp[3] ."</label>
                             <input type=\"text\" name=\"".$count."CFOP\" maxlength=\"9\" value=\"". $resp[6] ."\" />";
                      $count++;
                  }
              }

              fclose($fp);

            }else{
                      echo " <label> Não existe ítens disponíveis, favor gerar pedido</label>";
            }

            echo" <br><br> <td><button name=\"save_itens\" type=\"submit\">Salvar</button></td>

                  </form>";

            break;

          case 'fatura': 
            echo" <p class=\"logo\"> Dados de Fatura</p> <br>";
            echo" <form class=\"login-form\" name=\"cadastro\" method=\"POST\" action=\"#\" />";

            if (file_exists($file_itens)){
              echo" 
                  <label class=\"logo\"> Dias entre as parcelas</label>
                  <input type=\"text\" name=\"dias_parc\" maxlength=\"15\" value=\"30/45/60\" onkeyup=\"return dias_pgto(this)\" />
                  <label> Total da NF: ". money_format('%=*(#0.2n',get_id("TOT"))."</label><br><br>

                  <label> Texto complementar </label>
                  <textarea class='edtTextArea' name=\"txt_comp\" cols=\"112\" rows=\"5\" >DOC. EMITIDO POR ME OU EPP OPTANTE S.NACIONAL";

                  if(strlen(trim(get_id("E03"))) != "" ){

                    echo  ", NÃO GERA DIREITO A CREDITO FISCAL DE ISSQN E IPI, PERMITE O APROVEITAMENTO DE CREDITO DO ICMS NO VALOR DE ". money_format('%=*(#0.2n',get_id("TOT")*0.0282)." CORRESPONDENTE A ALIQUOTA DE 2,82% NOS TERMOS DO ARTIGO 23 DA LC 123";
                  }

                  echo ", APROVADO ATRAVES DO PEDIDO " . get_id("ENP") ;

              echo"</textarea>


            <br><br> <table><tr>
            <td><button name=\"add_bol\" type=\"submit\">Add Boletos</button></td>
            <td><select name='origem' id='selOrig'>
              <option value='FUN'> Funilaria e Pintura </option>
              <option value='SAN'> Sanfonados </option>
              <option value='OUT'> Outro </option>
            </select></td></tr>
            <tr><td><button name=\"save_fatura\" type=\"submit\">Gerar NFe</button></td></tr></table>";

            }else{
              echo " <label> Não existe ítens disponíveis, favor gerar pedido</label>";
            }


            echo"      </form>";

            break;

          case 'save_fiscal': 

            post_id("B01",$_POST ["cUF"]);
            post_id("B02",$_POST ["cNF"]);
            post_id("B03",$_POST ["natOp"]);
            post_id("B04",$_POST ["mod"]);
            post_id("B05",$_POST ["serie"]);
            post_id("B06",$_POST ["nNF"]);
            post_id("B07",$_POST ["dhEmi"]."T".date('H:i:s')."-03:00");
            post_id("B08",$_POST ["dhSaiEnt"]."T".date('H:i:s', strtotime('+1 hour'))."-03:00");
            post_id("B09",$_POST ["tpNF"]);
            post_id("B10",$_POST ["idDest"]);
            post_id("B11",$_POST ["cMunFG"]);
            post_id("B12",$_POST ["tpImp"]);
            post_id("B13",$_POST ["tpEmis"]);
            post_id("B14",$_POST ["cDV"]);
            post_id("B15",$_POST ["tpAmb"]);
            post_id("B16",$_POST ["finNFe"]);
            post_id("B17",$_POST ["indFinal"]);
            post_id("B18",$_POST ["indPres"]);
            post_id("B19",$_POST ["procEmi"]);
            post_id("B20",$_POST ["verProc"]);
            post_id("B21",$_POST ["dhCont"]);
            post_id("B22",$_POST ["xJust"]);
            monta();

            break;

          case 'save_emit':   

            post_id("B11",$_POST ["cMun"]);

            post_id("C01",$_POST ["xNome"]);
            post_id("C02",$_POST ["xFant"]);
            post_id("C03",$_POST ["IE"]);
            post_id("C04",$_POST ["IEST"]);
            post_id("C05",$_POST ["IM"]);
            post_id("C06",$_POST ["CNAE"]);
            post_id("C07",$_POST ["CRT"]);
            post_id("C08",$_POST ["CNPJ"]);
            post_id("C09",$_POST ["xLgr"]);
            post_id("C10",$_POST ["nro"]);
            post_id("C11",$_POST ["cpl"]);
            post_id("C12",$_POST ["bairro"]);
            post_id("C13",$_POST ["cMun"]);
            post_id("C14",$_POST ["xMun"]);
            post_id("C15",$_POST ["UF"]);
            post_id("C16",$_POST ["CEP"]);
            post_id("C17",$_POST ["cPais"]);
            post_id("C18",$_POST ["xPais"]);
            post_id("C19",$_POST ["fone"]); 

            monta();
            break;

          case 'save_ped':  
            global $dados;

            $cMun = IBGE_Mun($_POST ["UF"],$_POST ["xMun"]);

            post_id("TE1",$cMun);
            post_id("TE2",$_POST ["UF"]);
            post_id("TE3",$_POST ["xMun"]);

            post_id("E00",$_POST ["cod_ped"]);
            post_id("ENP",$_POST ["num_ped"]);
            post_id("E01",$_POST ["xNome"]);
            post_id("E02",$_POST ["indIEDest"]);
            post_id("E03",$_POST ["IE"]);
            post_id("E04",$_POST ["ISUF"]);
            post_id("E05",$_POST ["IM"]);
            post_id("E06",$_POST ["email"]);
            post_id("E07",$_POST ["CNPJ"]);
            post_id("E08",$_POST ["xLgr"]);
            post_id("E09",$_POST ["nro"]);
            post_id("E10",$_POST ["XCpl"]);
            post_id("E11",$_POST ["xBairro"]);
            post_id("E12", camel(tirarAcentos($_POST ["xMun"])));
            post_id("E13",$cMun);
            post_id("E14",$_POST ["UF"]);
            post_id("E15",$_POST ["CEP"]);
            post_id("E16",$_POST ["cPais"]);
            post_id("E17",$_POST ["xPais"]);
            post_id("E18",$_POST ["fone"]);

            monta();
            if(get_id("VAR")){
              echo "<BR><b> PEDIDOS ADICIONADOS COM SUCESSO, CLIQUE NA ABA ITENS PARA CONFERIR. </b><BR>";
              post_id("VAR",0);
            }else{
              Add_Item($_POST ["cod_ped"]);
            }


            break;
          case 'save_itens':  

            if (file_exists($file_itens)){
              $fp = fopen($file_itens, "r");
              $count = 1;
              $texto = "";
              while (!feof ($fp)) {
                  $linha = fgets($fp,4096);
                  if(substr($linha, 0, 1) == "I"){
                    $resp = make_array($linha);
                    $resp[6] = $_POST[$count."CFOP"];
                    $nova_linha = "";
                    foreach($resp as $value){
                      $nova_linha = $nova_linha . $value . "|";
                    }
                      $linha = $nova_linha."\r\n";
                      $count++;
                  }
                  $texto = $texto . $linha;
              }
              fclose($fp);

              $fp = fopen($file_itens, "w");
              fwrite($fp, $texto);
              fclose($fp);


            }
            break;

          case 'save_fatura': 
            $qtd_parc = 0;
            $dias_parc = 0;
            $txt_comp = "";

            if (IsSet($_POST ["dias_parc"])){
              $dias_parc = explode("/", $_POST["dias_parc"]); 
              $qtd_parc = count($dias_parc);
              $txt_comp = $_POST ["txt_comp"] ; 
            }

            $val_parc = get_id("TOT") / $qtd_parc;

            global $file, $file_itens;

            $texto = "";

            monta();
            $fp = fopen($file, "r");
            $texto =  fread($fp, filesize($file));
            fclose($fp);

            $fp = fopen($file_itens, "r");
            $texto =  $texto . fread($fp, filesize($file_itens));
            fclose($fp);

            $texto = $texto . "Y|\r\nY02|".str_pad($qtd_parc,3,'0', STR_PAD_LEFT)."|".number_format(get_id("TOT"), 2, '.', '')."|0.00|".number_format(get_id("TOT"), 2, '.', '')."|\r\n";

            for($i=1; $i< $qtd_parc+1; $i++){
              $pagto = date('Y-m-d', strtotime("+".$dias_parc[$i-1]."days",strtotime(date('Y-m-d'))));

              $texto = $texto . "Y07|".str_pad(trim($i),3,'0', STR_PAD_LEFT)."|".$pagto."|".number_format($val_parc, 2, '.', '')."|\r\n";
  
            }

            $texto = $texto . "YA\r\nYA01|1|15|".number_format(get_id("TOT"), 2, '.', '')."|\r\nZ||".$txt_comp."|\r\n";

//            $string_encoded = iconv( mb_detect_encoding( $texto ), 'UTF-8', $texto );

            $original =      array("Ã", "ã", "Á", "á", "Â", "â", "É", "é", "Ê", "ẽ", "Í", "í", "Ó", "ó", "Õ", "õ", "Ú", "ú", "Ç", "ç");
            $substituido   = array("A", "a", "A", "a", "A", "a", "E", "e", "E", "e", "I", "i", "O", "o", "O", "o", "U", "u", "C", "c");
            $string_encoded = str_replace($original, $substituido, $texto);

            $fp = fopen($NF, "w");
            fwrite($fp, $string_encoded);
//            fwrite($fp, $texto);
            fclose($fp);

          break;
          case 'add_bol':
            $qtd_parc = 0;
            $dias_parc = 0;

            if (IsSet($_POST ["dias_parc"])){
              $dias_parc = explode("/", $_POST["dias_parc"]); 
              $qtd_parc = count($dias_parc);
              $val_parc = number_format((get_id("TOT") / $qtd_parc), 2, '.', '') ;
              $dest = get_id("E01");
              $lanc = date('Y-m-d');
              $orig = $_POST["origem"];

              include "conecta_mysql.inc";        
              if (!$conexao)
                die ("Erro de conex�o com localhost, o seguinte erro ocorreu -> ".mysql_error());
              
              for($i=1; $i< $qtd_parc+1; $i++){
                $venc = date('Y-m-d', strtotime("+".$dias_parc[$i-1]."days",strtotime(date('Y-m-d'))));
                $ref = "NF-" .get_id("B06") ." " . $i . "/" . $qtd_parc ;
  
                $query = "INSERT INTO tb_financeiro ( tipo, ref, emp, preco, data_pg, data_ini, resp, origem, pgto) VALUES ('ENTRADA', '$ref', '$dest', '$val_parc','$venc', '$lanc' ,'SISTEMA','$orig', 'BOL');";   
  
                mysqli_query($conexao, $query);
    
              }   
              $conexao->close();  
           
            }

          break;
          default:
              //no action sent
        }

    ?>
  </div>

</div>


</body>
</html>