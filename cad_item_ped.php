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
      if (IsSet($_COOKIE["cod_ped"])){
        $cod_ped = $_COOKIE["cod_ped"];
      }
    ?>
  </header>
  <div class="page_container">  

      <div class="page_form">

        <?php                
              include "conecta_mysql.inc";
              if (!$conexao)
                  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

              $query =  "SELECT p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status, p.id
                         FROM tb_pedido AS p INNER JOIN tb_empresa AS e 
                         ON p.id = '". $cod_ped ."' AND p.id_emp = e.id ;";

              $result = mysqli_query($conexao, $query);

              while($fetch = mysqli_fetch_row($result)){
                  $num_ped = $fetch[0];
                  $cod_ped = $fetch[6];
                  $emp = $fetch[1];
                  $comp = $fetch[2];
                  $data_ped = $fetch[3];
                  $data_ent = $fetch[4];
                  if($fetch[5] == 'ABERTO'){
                    $status = 'Cotacao';
                  }else{
                    if($fetch[5] == 'FECHADO'){
                      $status = 'Pedido';
                    }else{
                      $status = 'Venda';
                    }
                  }  
              }
        ?>


        <p class="logo"><?php echo$status; ?></p> <br>
          <table class="search-table">
                <tr>
                  <th>Numero</th>
                  <th>Empresa</th>
                  <th>Comprador</th>
                  <th>Data</th>
                  <th>Data de Entrega</th>
                </tr>
        <?php               

              echo "<tr><td>" .$num_ped . "</td>".
                   "<td>" .$emp . "</td>".
                   "<td>" .$comp . "</td>".
                   "<td>" . date('d/m/Y', strtotime($data_ped))  . "</td>".
                   "<td>" .date('d/m/Y', strtotime($data_ent)) . "</td></tr>";

              echo "
                    </table> <br><br>
                    <table> <tr><td></td> <td>
                      <form class=\"login-form\" method=\"POST\" action=\"edt_ped.php\">
                        <input type=\"hidden\" name=\"cod_ped\" value=\"". $cod_ped ."\">
                        <button id=\"botao_inline\" type=\"submit\">Editar</button>              
                      </form> </td></tr>";
                          
              echo"</table></div>  

                <div class=\"page_form\" id=\"no_margin\">
                  <p class=\"logo\"> Itens</p> <br>
                    <table class=\"search-table\" id=\"tabChoise\">
                          <tr>
                            <th>Codigo</th>
                            <th>Descricao</th>
                            <th>Unidade</th>
                            <th>Qtd</th>
                            <th>Preço</th>
                            <th>Sub Total</th>
                          </tr>";


              $query =  "SELECT p.cod, p.descricao, i.und, i.qtd, i.preco
                        FROM tb_item_ped as i 
                        INNER JOIN tb_produto AS p
                        ON i.id_ped = '". $cod_ped ."' AND i.id_prod = p.id;";


              $result = mysqli_query($conexao, $query);


              $qtd_itens = $result->num_rows;

              $total = 0;

              while($fetch = mysqli_fetch_row($result)){
                $venda = $fetch[4];
                $subtotal = $fetch[3] * $venda;
                $total = $total + $subtotal;

                  echo "<tr class='tbl_row'><td>" .$fetch[0] . "</td>".
                       "<td>" .$fetch[1] . "</td>".
                       "<td>" .$fetch[2] . "</td>".
                       "<td>" .$fetch[3] . "</td>".
                       "<td>" .money_format('%=*(#0.2n', $venda) . "</td>".
                       "<td>" .money_format('%=*(#0.2n', $subtotal) . "</td></tr>";
              }

              echo "<tr><td></td><td></td><td></td><td></td><td> TOTAL </td><td>". money_format('%=*(#0.2n', $total) ."  </td>";


              echo"            
                    </table>
              </div>"; 

            if($qtd_itens > 0){
              echo"<div class=\"page_form\" id=\"no_margin\">
                    <table class=\"search-table\"  border=\"0\">
                      <tr>";
              if($status=='Cotacao'){        
              echo"   <form class=\"login-form\" method=\"POST\" action=\"add_item.php\">
                        <input type=\"hidden\" name=\"cod_ped\" value=\"". $cod_ped ."\">
                        <td><label> Cod.: </label> </td>
                        <td><input type=\"text\" name=\"cod_prod\" maxlength=\"14\" readonly/></td>
                        <td><label> Qtd.: </label> </td>
                        <td><input type=\"text\" id=\"edtEdtQtd\" name=\"qtd\" maxlength=\"14\"/></td>
                        <td><label> R$: </label> </td>
                        <td><input type=\"text\" id=\"edtEdtPreco\" name=\"preco\" maxlength=\"14\" onkeyup=\"return money(this)\"/></td></tr></table><table id=\"btn_table\"> <tr>
                        <td><button name=\"alterar\" id=\"botao_inline\" type=\"submit\" onclick=\"return confirma('Deseja realmente alterar este item?')\">Alterar</button></td>
                        <td><button name=\"remover\" id=\"botao_inline\" type=\"submit\" onclick=\"return confirma('Deseja realmente remover este item?')\">Remover</button></td>
                      </form>";
              } 
              echo"

                    <td><form class=\"login-form\" method=\"POST\" action=\"pdf_analise.php\">
                      <input type=\"hidden\" name=\"cod_ped\" value=\"". $cod_ped ."\">
                      <button id=\"botao_inline\" type=\"submit\">Analisar</button>
                    </form></td>
  
                       <td>
                      <form class=\"login-form\" method=\"POST\" action=\"pdf_cot.php\">
                        <td><button name=\"imprimir\" id=\"botao_inline\" type=\"submit\" >Cotação</button></td>
                        <td><button name=\"recibo\" id=\"botao_inline\" type=\"submit\" >Recibo Mat.</button></td>
                        <td>  <input type=\"checkbox\" checked name=\"ver_preco\" value=\"1\" /> </td>
                        <input type=\"hidden\" name=\"cod_ped\" value=\"". $cod_ped ."\">
                      </form>

                      </tr>
                    </table>
                  </div> ";   
            }
            $conexao->close();

        
            if($status=='Cotacao'){

            echo"  <div class=\"page_form\" id=\"no_margin\">
                    <form class=\"login-form\" method=\"POST\" action=\"#\">
                      <p class=\"logo\">Adicionar Itens</p> <br>
                      <table class=\"search-table\"  border=\"0\"><tr><td>
                      <label> Busca por: </label> </td><td>
                      <select name=\"campo\">
                        <option value=\"desc\">Descricao</option>
                        <option value=\"cod\">Codigo</option>
                        <option value=\"tinta\">Tintas</option>
                        <option value=\"forn\">Fornecedor</option>
                        <option value=\"servico\">Serviço</option>
                        <option value=\"cod_bar\">Codigo do Produto</option>
                        <option value=\"cod_cli\">Codigo de Barras</option>
                      </select></td><td>
                      <input type=\"text\" name=\"valor\" maxlength=\"12\"/></td><td>
                      <button id=\"botao_inline\" type=\"submit\">OK</button></td></tr>  </table>
                    </form>
                  </div>";

            }

        $qtd_lin = 0;
        if (IsSet($_POST ["campo"])){

        include "conecta_mysql.inc";
        if (!$conexao)
          die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

          $campo = $_POST ["campo"];
          $valor = $_POST ["valor"];
          if ($campo == "desc"){
            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.descricao LIKE '%".$valor."%' AND p.id_emp = e.id ;";
          }
          else
          if ($campo == "cod"){
            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.cod = '".$valor."' AND p.id_emp = e.id ;";
          }
          else
          if ($campo == "cod_bar"){ // código do fornecedor do produto 
            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.cod_bar = '".$valor."' AND p.id_emp = e.id ;";
          }
          else
          if ($campo == "cod_cli"){ // código de barras
            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.cod_cli LIKE '%".$valor."%' AND p.id_emp = e.id ;";
          }
          else
          if ($campo == "forn"){

            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON e.nome LIKE '%".$valor."%' AND p.id_emp = e.id ;";

          }
          if ($campo == "servico"){

            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON  p.tipo ='SERVICO' AND p.id_emp = e.id ORDER BY e.nome;";

          }
          if ($campo == "tinta"){

            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome, p.preco_comp, p.margem, p.ncm, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON  p.tipo ='TINTA' AND p.id_emp = e.id ORDER BY p.cod desc;";

          }

          $result = mysqli_query($conexao, $query);

          $qtd_lin = $result->num_rows;



        echo"  <div class=\"page_form\" id=\"no_margin\">
            <table class=\"search-table\"  id=\"tabItens\" >
                <tr>
                  <th>Codigo</th>
                  <th>Descricao</th>
                  <th>Unid.</th>
                  <th>Estoque</th>
                  <th>Preço</th>
                  <th>Fornecedor</th>
                </tr>";
                  while($fetch = mysqli_fetch_row($result)){

                    $cod_prod = $fetch[0];
                    $qtd = $fetch[4];
                    $preco = $fetch[7];
                    $margem = $fetch[8];
                    $tipo = $fetch[10];
                    $und = $fetch[3];

                      echo "<tr class='tbl_row' id='".$fetch[0]."'><td>" .$fetch[1] . "</td>".
                       "<td>" .$fetch[2] . "</td>".
                         "<td>" .$fetch[3] . "</td>".
                         "<td>" .$fetch[4] . "</td>".
                       "<td>" .money_format('%=*(#0.2n', $preco * (1+ $margem/100)). "</td>".
                       "<td>" .$fetch[6] . "</td>".
                       "<td style='visibility: collapse' >" .$cod_ped . "</td>".
                       "<td style='visibility: collapse' >" .$tipo . "</td>".
                       "</tr>";
                  }
                echo"
            </table> 

          </div>
          ";
        $conexao->close();

        }
// ARREDONDAMENTO NO VALOR DO PREÇO PARA 2 CASAS DEPOIS DA VÍRGULA ***** AQUI ******
      if ($qtd_lin == 1){
          echo"
            <div class=\"page_form\" id= \"no_margin\">
                <table class=\"search-table\"  border=\"0\">
                  <tr>
                    <form name=\"add_item\" class=\"login-form\" method=\"POST\" action=\"add_item.php\" onsubmit=\"return estq_baixo(".$qtd."); return adiciona(); return false;\">

                     <td><label> Quantidade </label> </td> 
                        <input type=\"hidden\" name=\"preco\" value=\"". number_format( $preco * (1+ $margem/100) , 2, '.', '') ."\">
                        <input type=\"hidden\" name=\"tipo\" value=\"".trim($tipo)."\">
                        <input type=\"hidden\" name=\"und\" value=\"".$und."\">
                      <td> <input type=\"text\" name=\"qtd\" /> </td>
                      <td>";
        if(trim($tipo) == 'TINTA'){           
          echo"       
                      <td> <select name=\"vol\">
                              <option value=\"0.5\">450ml</option>
                              <option selected=\"selected\" value=\"1\">900ml</option>
                              <option value=\"2\">1.8L</option>
                              <option value=\"3\">2.7L</option>
                              <option value=\"4\">3.6L</option>
                           </select></td>
                      <td>";

        }else{
         echo"        <input type=\"hidden\" name=\"vol\" value=\"1\">";

        }

          echo"         <button name=\"adicionar\" id=\"botao_inline\" type=\"submit\">Adicionar</button>
                        <input type=\"hidden\" name=\"cod_prod\" value=\"". $cod_prod ."\">
                        <input type=\"hidden\" name=\"cod_ped\" value=\"". $cod_ped ."\">
                      </td>
                    </form>
                  </tr>
                </table>

            </form>


          </div>";
      }       
            if($qtd_itens > 0 and $status=='Cotacao' && $classe >= 4){

              echo"<div class=\"page_form\" id=\"no_margin\">
                    <form class=\"login-form\" method=\"POST\" action=\"add_item.php\">
                    <table class=\"search-table\"  border=\"0\">
                      <tr>
                        <td><label> Num. do Pedido de Compra: </label> </td>
                        <td><input type=\"text\" name=\"pedido\" maxlength=\"14\" value=\"". $num_ped ."\"></td>
                        <td><button name=\"encerrar\" id=\"botao_inline\" type=\"submit\" onclick=\"return encerra(); return false;\">Encerrar</button></td>
                      </tr>
                    </table>
                    <input type=\"hidden\" name=\"cod_ped\" value=\"". $cod_ped ."\">

                    </form>
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