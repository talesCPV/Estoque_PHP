<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Pesquisa por Produto</title>
     <!-- Aqui chamamos o nosso arquivo css externo -->
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/funcoes.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  

      <div class="page_form">

        <?php                
            if (IsSet($_POST["cod_serv"]) || IsSet($_COOKIE["cod_serv"])){
              
                if(IsSet($_POST["cod_serv"])){
                  $cod_serv = $_POST["cod_serv"];
                }else{
                  $cod_serv = $_COOKIE["cod_serv"];
                }
                    
                include "conecta_mysql.inc";
                if (!$conexao)
                    die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

                $query =  "SELECT * FROM tb_servico WHERE id =". $cod_serv .";";

//                echo $query."<br>";


                $result = mysqli_query($conexao, $query);

                while($fetch = mysqli_fetch_row($result)){   
                    $num_serv = $fetch[1];                 
                    $resp = $fetch[2];
                    $func = $fetch[3];
                    $tipo = $fetch[4];
                    $data = $fetch[5];
                    $status = $fetch[6];
                }

            }
        ?>


        <p class="logo"> OF <?php echo(" - ".$num_serv); ?> </p> <br>
          <table class="search-table">
                <tr>
                  <th>Cod.</th>
                  <th>Func. Resp.</th>
                  <th>Emitido.</th>
                  <th>Data</th>
                  <th>Status</th>
                </tr>
        <?php               

              echo "<tr><td style='display: none;'>" .$cod_serv . "</td>".
                  "<td>" .$cod_serv . "</td>".
                  "<td>" .$func . "</td>".
                  "<td>" .$resp . "</td>".
                  "<td>" . date('d/m/Y', strtotime($data))  . "</td>".                  
                  "<td>" .$status . "</td>".
                  "</tr>";
                          
              echo"</table>
                <form class=\"login-form\" method=\"POST\" action=\"pdf_of.php\">
                  <input type='hidden' name='cod_serv' value='". $cod_serv ."'>
                  <button name=\"remover\" id=\"btn_imp\" type=\"submit\" >Imprimir</button>
                </form>
              </div>  

                <div class=\"page_form\" id=\"no_margin\">
                  <p class=\"logo\"> Itens</p> <br>
                    <table class=\"search-table\" id=\"tabChoise\">
                          <tr>
                            <th>Cod.</th>
                            <th>Descricao</th>
                            <th>Unidade</th>
                            <th>Qtd</th>
                          </tr>";

             
              $query =  "SELECT p.cod, p.descricao, p.unidade, s.qtd, p.id, s.id FROM tb_produto AS p INNER JOIN tb_item_serv AS s ON p.id = s.id_item  AND s.id_serv = ". $cod_serv ." ;";

              $result = mysqli_query($conexao, $query);

              $qtd_itens = $result->num_rows;

              while($fetch = mysqli_fetch_row($result)){

                  echo "<tr class='tbl_row'>".
                       "<td>" .$fetch[0] . "</td>".
                       "<td>" .$fetch[1] . "</td>".
                       "<td>" .$fetch[2] . "</td>".
                       "<td>" .$fetch[3] . "</td>".
                       "<td style='display: none;'>" .$fetch[4] . "</td>".
                       "<td style='display: none;'>" .$fetch[5] . "</td>".
                       "</tr>";
              }

            

              echo"            
                    </table>
              </div>"; 

            if($qtd_itens > 0 && $status == 'ABERTO'){
              echo"<div class=\"page_form\" id=\"no_margin\">
                    <table class=\"search-table\"  border=\"0\">
                      <tr>
                        <form class=\"login-form\" method=\"POST\" action=\"#\">
                          <input type=\"hidden\" name=\"cod_serv\" value=\"". $cod_serv ."\">
                          <td><label> Cod.: </label> </td>
                          <td><input type=\"text\" name=\"edtCod_item\" maxlength=\"6\" readonly/></td>
                          <td><label> Qtd.: </label> </td>
                          <td><input type=\"text\" id=\"edtEdt_Qtd\" name=\"edtEdt_Qtd\" maxlength=\"8\" onkeyup=\"return money(this)\"/></td>
                          <td><button name=\"alterar\" id=\"btn_alt\" type=\"submit\" onclick=\"return confirma('Deseja realmente alterar este item?')\">Alterar</button></td>
                          <td><button name=\"remover\" id=\"btn_del\" type=\"submit\" onclick=\"return confirma('Deseja realmente remover este item?')\">Remover</button></td>
                        </form>
                      </tr>
                    </table>
                  </div> ";   

            }
            if($status == 'ABERTO'){


            echo"  <div class=\"page_form\" id=\"no_margin\">
                    <form class=\"login-form\" method=\"POST\" action=\"#\">
                      <p class=\"logo\">Adicionar Itens</p> <br>
                      <table class=\"search-table\"  border=\"0\"><tr><td>
                      <label> Busca por: </label> </td><td>
                      <select name=\"campo\">
                        <option value=\"desc\">Descricao</option>
                        <option value=\"cod\">Codigo</option>
                        <option value=\"conj\">Conjuntos</option>
                        <option value=\"servico\">Serviços</option>
                      </select></td><td>
                      <input type=\"text\" name=\"valor\" maxlength=\"12\"/></td><td>
                      <button id=\"botao_inline\" type=\"submit\">OK</button></td></tr>  </table>
                      <input type='hidden' name='cod_serv' value='". $cod_serv ."'>
                    </form>
                  </div>";

            }
            $conexao->close();                  

        if (IsSet($_POST ["campo"])){

        include "conecta_mysql.inc";
        if (!$conexao)
          die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

          $campo = $_POST ["campo"];
          $valor = $_POST ["valor"];
          if ($campo == "desc"){
            $query =  "SELECT id, cod, descricao, unidade, tipo FROM tb_produto WHERE descricao LIKE '%".$valor."%' AND (tipo = 'CONJ' OR tipo = 'SERVICO' OR tipo = 'TINTA_E') ORDER BY cod;";
          }
          else
          if ($campo == "cod"){
            $query =  "SELECT id, cod, descricao, unidade, tipo FROM tb_produto WHERE cod LIKE '".$valor."%' AND (tipo = 'CONJ' OR tipo = 'SERVICO' OR tipo = 'TINTA_E') ORDER BY cod;";
          }
          else
          if ($campo == "conj"){
            $query =  "SELECT id, cod, descricao, unidade, tipo FROM tb_produto WHERE tipo ='CONJ' ORDER BY cod;";
          }
          else
          if ($campo == "servico"){
            $query =  "SELECT id, cod, descricao, unidade, tipo FROM tb_produto WHERE tipo ='SERVICO' ORDER BY cod;";
          }
          

          $result = mysqli_query($conexao, $query);

        echo"  <div class=\"page_form\" id=\"no_margin\">
                <table class=\"search-table\"  id=\"tabItens\" >
                    <tr>
                        <th>Codigo</th>
                        <th>Descricao</th>
                        <th>Unid.</th>
                        <th>Tipo</th>
                    </tr>";
                    while($fetch = mysqli_fetch_row($result)){

                        $id_prod = $fetch[0];
                        $cod_prod = $fetch[1];
                        $desc = $fetch[2];
                        $und = $fetch[3];
                        $tipo = $fetch[4];

                        echo "<tr class='tbl_row'>".
                                "<td style='display: none;'>" .$id_prod . "</td>".
                                "<td>" .$cod_prod . "</td>".
                                "<td>" .$desc . "</td>".
                                "<td>" .$und . "</td>".
                                "<td>" .$tipo . "</td>".
                                "<td style='display: none;'>" .$cod_serv . "</td>".
                            "</tr>";
                  }
        echo"
            </table> 

          </div>
          ";

        $conexao->close();

        }
     
        if($qtd_itens > 0 && $status == 'ABERTO'){
            echo"<div class=\"page_form\" id=\"no_margin\">
                  <table class=\"search-table\"  border=\"0\">
                    <tr>";      
            echo"   <form class=\"login-form\" method=\"POST\" action=\"save_of.php\">
                      <input type=\"hidden\" name=\"novo\" value='0'\">
                      <input type=\"hidden\" name=\"cod_serv\" value=\"". $cod_serv ."\">
                      <td><button name=\"remover\" id=\"btn_FechaOF\" type=\"submit\" \">Encerrar</button></td>
                    </form>";

            echo"


                    </tr>
                  </table>
                </div> ";   
          }

    ?>      
  </div>

<div class="overlay"> 
  <div class="popup">
    <h2 id="popTitle"></h2>
    <div class="close" >&times</div>
    <div class="content"></div>
  </div>
</div>

</body>
</html>