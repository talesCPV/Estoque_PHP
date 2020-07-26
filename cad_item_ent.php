<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Entrada de Material</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="js/funcoes.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
      if (IsSet($_COOKIE["cod_ent"])){
        $cod_ent = $_COOKIE["cod_ent"];
      }
    ?>
  </header>
  <div class="page_container">  

      <div class="page_form">
        <p class="logo">Entrada de NF</p> <br>
          <table class="search-table" >
                <tr>
                  <th>Cod.</th>
                  <th>NF</th>
                  <th>Empresa</th>
                  <th>Data</th>
                </tr>
        <?php                
              include "conecta_mysql.inc";
              if (!$conexao)
                  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

              $query =  "SELECT etd.id, etd.nf, e.nome, etd.data_ent, etd.status
                         FROM tb_entrada AS etd INNER JOIN tb_empresa AS e 
                         ON etd.id = '". $cod_ent ."' AND etd.id_emp = e.id ;";

              $result = mysqli_query($conexao, $query);

              while($fetch = mysqli_fetch_row($result)){
                $status = $fetch[4];
                  echo "<tr><td>" .$fetch[0] . "</td>".
                   "<td>" .$fetch[1] . "</td>".
                   "<td>" .$fetch[2] . "</td>".
                   "<td>" .date('d/m/Y', strtotime($fetch[3])) . "</td></tr>";
              }

              echo "
                    </table>
              </div>  

                <div class=\"page_form\" id=\"no_margin\">
                  <p class=\"logo\"> Itens</p> <br>
                    <table class=\"search-table\" id=\"tabChoise\">
                          <tr>
                            <th>Codigo</th>
                            <th>Descricao</th>
                            <th>Unidade</th>
                            <th>Qtd</th>
                            <th>Preço</th>
                          </tr>";


              $query =  "SELECT p.cod, p.descricao, p.unidade, i.qtd, i.preco
                        FROM tb_item_compra as i 
                        INNER JOIN tb_produto AS p
                        ON i.id_ent = '". $cod_ent ."' AND i.id_prod = p.id ORDER BY i.id;";

              $result = mysqli_query($conexao, $query);


              $qtd_itens = $result->num_rows;
              $tot = 0;


              while($fetch = mysqli_fetch_row($result)){
                  echo "<tr class='tbl_row'><td>" .$fetch[0] . "</td>".
                       "<td>" .$fetch[1] . "</td>".
                       "<td>" .$fetch[2] . "</td>".
                       "<td>" .$fetch[3] . "</td>".
                       "<td>" .money_format('%=*(#0.2n', $fetch[4]) . "</td></tr>";
                       $tot = $tot + $fetch[3] * $fetch[4];
              }
              echo"<tr><td></td><td></td><td></td><td>Total</td><td>".money_format('%=*(#0.2n', $tot)."</td>";


              echo"            
                    </table>
              </div>"; 

            if($qtd_itens > 0 and $status == 'ABERTO'){
              echo"<div class=\"page_form\" id=\"no_margin\">
                    <form class=\"login-form\" method=\"POST\" action=\"add_item_ent.php\">
                    <table class=\"search-table\"  border=\"0\">
                      <tr>
                        <td><label> Cod.: </label> </td>
                        <td><input type=\"text\" name=\"cod_prod\" maxlength=\"14\" readonly/></td>
                        <td><label> Qtd.: </label> </td>
                        <td><input type=\"text\" id=\"edtEdtQtd\" name=\"qtd\" maxlength=\"14\"/></td>
                        <td><label>R$: </label> </td>
                        <td><input type=\"text\" id=\"edtEdtPreco\" name=\"preco\" maxlength=\"14\" \"/></td>
                        <td><button name=\"alterar\" id=\"botao_inline\" type=\"submit\" onclick=\"return confirma('Confirma alteração?')\">Alterar</button></td>
                        <td><button name=\"remover\" id=\"botao_inline\" type=\"submit\" onclick=\"return confirma('Deseja deletar este item?')\">Remover</button></td>
                      </tr>
                    </table>
                    <input type=\"hidden\" name=\"cod_ped\" value=\"". $cod_ent ."\">

                    </form>
                  </div> ";   
            }
            $conexao->close();

        
            if($status == 'ABERTO'){

              echo"  <div class=\"page_form\" id=\"no_margin\">
                      <form class=\"login-form\" method=\"POST\" action=\"#\">
                        <p class=\"logo\">Adicionar Itens</p> <br>
                        <table class=\"search-table\"  border=\"0\"><tr><td>
                        <label> Busca por: </label> </td><td>
                        <select name=\"campo\" id=\"selBusca\">
                          <option value=\"desc\">Descricao</option>
                          <option value=\"cod\">Codigo</option>
                          <option value=\"forn\">Fornecedor</option>
                          <option value=\"cod_bar\">Codigo do Produto</option>
                        </select></td><td>
                        <input type=\"text\" name=\"valor\" maxlength=\"12\" id=\"edtBusca\" /></td><td>
                        <button id=\"botao_ok\" type=\"submit\">OK</button></td></tr>  </table>
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
            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.descricao LIKE '%".$valor."%' AND p.id_emp = e.id ;";
          }
          else
          if ($campo == "cod"){
            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.cod = '".$valor."' AND p.id_emp = e.id ;";
          }
          else
          if ($campo == "cod_bar"){
            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome FROM tb_produto AS p INNER JOIN tb_empresa AS e ON p.cod_bar LIKE '%".$valor."%' AND p.id_emp = e.id ;";
          }
          else
          if ($campo == "forn"){

            $query =  "SELECT p.id, p.cod, p.descricao, p.unidade, p.estoque, p.cod_bar, e.nome FROM tb_produto AS p INNER JOIN tb_empresa AS e ON e.nome LIKE '%".$valor."%' AND p.id_emp = e.id ;";

          }

          $result = mysqli_query($conexao, $query);

          $qtd_lin = $result->num_rows;



        echo"  <div class=\"page_form\" id=\"no_margin\">
            <table class=\"search-table\" id=\"tabItens\">
                <tr>
                  <th>Codigo</th>
                  <th>Descricao</th>
                  <th>Unid.</th>
                  <th>Estoque</th>
                  <th>Cod. Produto</th>
                  <th>Fornecedor</th>
                </tr>";
                  while($fetch = mysqli_fetch_row($result)){

                    $cod_prod = $fetch[0];
                    $qtd = $fetch[4];
                    $cod = $fetch[1];

                      echo "<tr class='tbl_row' id='".$fetch[1]."'><td>" .$fetch[1] . "</td>".
                       "<td>" .$fetch[2] . "</td>".
                         "<td>" .$fetch[3] . "</td>".
                         "<td>" .$fetch[4] . "</td>".
                       "<td>" .$fetch[5] . "</td>".
                       "<td>" .$fetch[6] . "</td></tr>";
                  }
                echo"
            </table> 

          </div>
          ";
        $conexao->close();

        }

      if ($qtd_lin == 1){
          echo"
            <div class=\"page_form\" id= \"no_margin\">
                <table class=\"search-table\"  border=\"0\">
                  <tr>
                    <form name=\"add_item\" class=\"login-form\" method=\"POST\" action=\"add_item_ent.php\" >
                      <td><label> QTD. </label> </td>
                      <td> <input type=\"text\" name=\"qtd\" onkeyup=\"return money(this)\"/> </td>
                      <td><label> Preço_R$</label> </td>
                      <td> <input type=\"text\" name=\"preco\" onkeyup=\"return money(this)\"/> </td>                      
                      <td><label> ICMS%</label> </td>
                      <td> <input type=\"text\" name=\"icms\" id=\"edtIcms\" value=\"18\" onkeyup=\"return money(this)\"/> </td>                      
                      <td><label> IVA%</label> </td>
                      <td> <input type=\"text\" name=\"mva\" id=\"edtMva\" value=\"58\" onkeyup=\"return money(this)\"/> </td>                      
                      <td><label> IPI_%</label> </td>
                      <td> <input type=\"text\" name=\"ipi\" id=\"edtIpi\" value=\"5\" onkeyup=\"return money(this)\"/> </td>                      
                      <td>
                        <button name=\"adicionar\" id=\"botao_inline\" type=\"submit\">Adicionar</button>
                        <input type=\"hidden\" name=\"cod_prod\" value=\"". $cod_prod ."\">
                        <input type=\"hidden\" name=\"cod_ent\" value=\"". $cod_ent ."\">
                      </td>
                    </form>
                  </tr>
                </table>

            </form>


          </div>";
      }       
            if($qtd_itens > 0 and $status == 'ABERTO'){
              echo"<div class=\"page_form\" id=\"no_margin\">
                    <form class=\"login-form\" method=\"POST\" action=\"add_item_ent.php\">
                    <table class=\"search-table\"  border=\"0\">
                      <tr>
                        <td><button name=\"encerrar\" id=\"botao_inline\" type=\"submit\" onclick=\"return confirma('Confirmar a entrada desta NF?')\">Fechar NF</button></td>
                        <input type=\"hidden\" name=\"cod_ped\" value=\"". $cod_ent ."\">
                    </form>
                      </tr>
                    </table>

                  </div> ";   
            }


    ?>      
  </div>

</body>
</html>