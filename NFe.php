<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Gerador de Nota Fiscal Eletrônica</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
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

              $query =  "SELECT p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.status
                         FROM tb_pedido AS p INNER JOIN tb_empresa AS e 
                         ON p.id = '". $cod_ped ."' AND p.id_emp = e.id ;";

              $result = mysqli_query($conexao, $query);

              while($fetch = mysqli_fetch_row($result)){
                  $num_ped = $fetch[0];
                  $emp = $fetch[1];
                  $comp = $fetch[2];
                  $data_ped = $fetch[3];
                  $data_ent = $fetch[4];
                  if($fetch[5] == 'ABERTO'){
                    $status = 'Cotacao';
                  }else{
                    $status = 'Pedido';
                  }  
              }
        ?>


        <p class="logo"><?php echo$status; ?></p> <br>
          <table class="search-table" >
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
                   "<td>" .$data_ped . "</td>".
                   "<td>" .$data_ent . "</td></tr>";

              echo "
                    </table>
              </div>  

                <div class=\"page_form\" id=\"no_margin\">
                  <p class=\"logo\"> Itens</p> <br>
                    <table class=\"search-table\" >
                          <tr>
                            <th>Codigo</th>
                            <th>Descricao</th>
                            <th>Unidade</th>
                            <th>Qtd</th>
                            <th>Preço</th>
                            <th>Sub Total</th>
                          </tr>";


              $query =  "SELECT p.cod, p.descricao, p.unidade, i.qtd, i.preco
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

                  echo "<tr><td>" .$fetch[0] . "</td>".
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
                      <tr>
                        <form class=\"login-form\" method=\"POST\" action=\"gera_NFe.php\">
                          <td><button name=\"imprimir\" id=\"botao_inline\" type=\"submit\" >Visualizar NFe</button></td>
                          <td><button name=\"recibo\" id=\"botao_inline\" type=\"submit\" >Exportar TXT</button></td>
                          <input type=\"hidden\" name=\"cod_ped\" value=\"". $cod_ped ."\">
                          <input type=\"hidden\" name=\"num_nf\" value=\"1765\">
                        </form>
                      </tr>
                    </table>
                  </div> ";   
            }
            $conexao->close();

    ?>      
  </div>

</body>
</html>