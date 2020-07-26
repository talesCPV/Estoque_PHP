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
            if (IsSet($_POST["cod_prod"])){
                $cod_conj = $_POST["cod_prod"];
              
            
                include "conecta_mysql.inc";
                if (!$conexao)
                    die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

                $query =  "SELECT * FROM tb_produto WHERE id =". $cod_conj .";";

                $result = mysqli_query($conexao, $query);

                while($fetch = mysqli_fetch_row($result)){   
                    $cod = $fetch[7];                 
                    $desc = $fetch[2];
                    $und = $fetch[5];
                    $custo = $fetch[10];
                }

            }
        ?>


        <p class="logo"> Sub-Conjunto </p> <br>
          <table class="search-table">
                <tr>
                  <th>Cod.</th>
                  <th>Descricao</th>
                  <th>Und.</th>

                </tr>
        <?php               

              echo "<tr><td style='display: none;'>" .$cod_conj . "</td>".
                  "<td>" .$cod . "</td>".
                  "<td>" .$desc . "</td>".
                  "<td>" .$und . "</td>".
                  "</tr>";


                          
              echo"</table></div>  

                <div class=\"page_form\" id=\"no_margin\">
                  <p class=\"logo\"> Itens</p> <br>
                    <table class=\"search-table\" id=\"tabChoise\">
                          <tr>
                            <th>Cod.</th>
                            <th>Descricao</th>
                            <th>Unidade</th>
                            <th>Qtd</th>
                            <th>Custo</th>
                            <th>Tot. Custo</th>
                          </tr>";

             
              $query =  "SELECT s.id, p.descricao, p.unidade, s.qtd, p.preco_comp, p.cod FROM tb_produto AS p INNER JOIN tb_subconj AS s ON p.id = s.id_peca  AND s.id_conj = ". $cod_conj ." ;";

              $result = mysqli_query($conexao, $query);


              $qtd_itens = $result->num_rows;

              $total = 0;

              while($fetch = mysqli_fetch_row($result)){
                $venda = $fetch[4];
                $subtotal = $fetch[3] * $venda;
                $total = $total + $subtotal;

                  echo "<tr class='tbl_row'><td style='display: none;'>" .$fetch[0] . "</td>".
                       "<td>" .$fetch[5] . "</td>".
                       "<td>" .$fetch[1] . "</td>".
                       "<td>" .$fetch[2] . "</td>".
                       "<td>" .$fetch[3] . "</td>".
                       "<td>" .money_format('%=*(#0.2n', $venda) . "</td>".
                       "<td>" .money_format('%=*(#0.2n', $subtotal) . "</td>".
                       "</tr>";
              }

              echo "<tr><td></td><td></td><td></td><td></td><td> TOTAL </td><td>". money_format('%=*(#0.2n', $total) ."  </td>";
              
              $total = number_format($total, 2, '.', '');
              $query =  "UPDATE tb_produto SET preco_comp = {$total} WHERE id = {$cod_conj};";
              mysqli_query($conexao, $query);


              echo"            
                    </table>
              </div>"; 

            if($qtd_itens > 0){
              echo"<div class=\"page_form\" id=\"no_margin\">
                    <table class=\"search-table\"  border=\"0\">
                      <tr>";      
              echo"   <form class=\"login-form\" method=\"POST\" action=\"#\">
                        <input type=\"hidden\" name=\"cod_prod\" value=\"". $cod_conj ."\">
                        <td><label> Cod.: </label> </td>
                        <td><input type=\"text\" name=\"edtCod_item\" maxlength=\"6\" readonly/></td>
                        <td><label> Qtd.: </label> </td>
                        <td><input type=\"text\" id=\"edtEdt_Qtd\" name=\"edtEdt_Qtd\" maxlength=\"8\" onkeyup=\"return money(this)\"/></td>
                        <td><button name=\"alterar\" id=\"btn_alt\" type=\"submit\" onclick=\"return confirma('Deseja realmente alterar este item?')\">Alterar</button></td>
                        <td><button name=\"remover\" id=\"btn_del\" type=\"submit\" onclick=\"return confirma('Deseja realmente remover este item?')\">Remover</button></td>
                      </form>";

              echo"


                      </tr>
                    </table>
                  </div> ";   
            }
            $conexao->close();


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
                      <input type='hidden' name='cod_prod' value='". $cod_conj ."'>
                    </form>
                  </div>";


        $qtd_lin = 0;
        if (IsSet($_POST ["campo"])){

        include "conecta_mysql.inc";
        if (!$conexao)
          die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

          $campo = $_POST ["campo"];
          $valor = $_POST ["valor"];
          if ($campo == "desc"){
            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, e.nome, p.preco_comp, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.descricao LIKE '%".$valor."%' AND p.id_emp = e.id ORDER BY p.cod;";
          }
          else
          if ($campo == "cod"){
            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, e.nome, p.preco_comp, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.cod = '".$valor."' AND p.id_emp = e.id ORDER BY p.cod ;";
          }
          else
          if ($campo == "cod_bar"){ // código do fornecedor do produto 
            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, e.nome, p.preco_comp, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.cod_bar = '".$valor."' AND p.id_emp = e.id ORDER BY p.cod ;";
          }
          else
          if ($campo == "cod_cli"){ // código de barras
            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, e.nome, p.preco_comp, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.cod_cli LIKE '%".$valor."%' AND p.id_emp = e.id ORDER BY p.cod ;";
          }
          else
          if ($campo == "forn"){

            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, e.nome, p.preco_comp, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON e.nome LIKE '%".$valor."%' AND p.id_emp = e.id ORDER BY p.cod ;";

          }
          if ($campo == "servico"){

            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, e.nome, p.preco_comp, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON  p.tipo ='SERVICO' AND p.id_emp = e.id ORDER BY p.cod;";

          }
          if ($campo == "tinta"){

            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, e.nome, p.preco_comp, p.tipo FROM tb_produto AS p INNER JOIN tb_empresa AS e ON  p.tipo ='TINTA' AND p.id_emp = e.id ORDER BY p.cod desc ;";

          }

          $result = mysqli_query($conexao, $query);

          $qtd_lin = $result->num_rows;


        echo"  <div class=\"page_form\" id=\"no_margin\">
            <table class=\"search-table\"  id=\"tabItens\" >
                <tr>
                  <th>Codigo</th>
                  <th>Descricao</th>
                  <th>Unid.</th>
                  <th>Custo</th>
                  <th>Fornecedor</th>
                </tr>";
                  while($fetch = mysqli_fetch_row($result)){

                    $id_prod = $fetch[0];
                    $cod_prod = $fetch[1];
                    $preco = $fetch[5];
                    $tipo = $fetch[6];
                    $und = $fetch[3];

                      echo "<tr class='tbl_row' id='".$id_prod."'><td>" .$cod_prod . "</td>".
                       "<td>" .$fetch[2] . "</td>".
                         "<td>" .$und . "</td>".
                       "<td>" .money_format('%=*(#0.2n', $preco ). "</td>".
                       "<td>" .$fetch[4] . "</td>".
                       "<td style='visibility: collapse' >" .$tipo . "</td>".
                       "<td style='visibility: collapse' >" .$cod_conj . "</td>".
                       "</tr>";
                  }
                echo"
            </table> 

          </div>
          ";
        $conexao->close();

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