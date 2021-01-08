<?php
	setcookie("cod_serv");
  	include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Manutenção e Fabricação de Sanfonas</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>    
    <script src="js/manut_sanf.js"></script>

</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Manutenção e Fabricação de Sanfonas</p> <br>
      <button id="btn_NovaSanf">Novo</button>
	  </div>
	  <?php


        include "conecta_mysql.inc";
        if (!$conexao)
            die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());
      
        if (IsSet($_POST ["hdnTipo"])){
            
            $hdn = $_POST ["hdnTipo"];

            $id = $_POST["edt_id"];
            if($hdn == "NOVO" || $hdn == "EDITAR"){
                $entrada = $_POST ["edtEntrada"];
                $cliente = $_POST ["edtCli"];
                $modelo = $_POST ["edtMod"];
                $num = $_POST ["edtNum"];
                $tipo = $_POST ["cmbTipo"];
                $status = $_POST ["cmbStatus"];
                $saida = $_POST ["edtSaida"];
                $obs = $_POST ["edtObs"];

                include "conecta_mysql.inc";
                if (!$conexao)
                    die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

                if($hdn == "EDITAR"){
                    $query = "UPDATE tb_ref_sanf SET entrada='{$entrada}', id_cliente='{$cliente}', id_modelo='{$modelo}', numero='{$num}', tipo='{$tipo}',
                    status='{$status}', saida='{$saida}', obs='{$obs}' WHERE id={$id} ;";
                }else{
                    $query = "INSERT INTO tb_ref_sanf ( entrada, id_cliente, id_modelo, numero, tipo, status, saida, obs) 
                    VALUES ('{$entrada}', '{$cliente}', '{$modelo}', '{$num}', '{$tipo}', '{$status}', '{$saida}', '{$obs}' ) ;";
                }
//                echo($query);

                mysqli_query($conexao, $query);
                
            }else if($hdn == "DELETAR"){
                $query = "DELETE FROM tb_ref_sanf WHERE id={$id} ;";
                mysqli_query($conexao, $query);
            }

        }        
      

        $query =  "SELECT r.id, r.entrada, e.nome, s.fabricante, s.modelo, s.ano, r.numero, r.tipo, r.status, r.saida, r.obs, e.id, s.id
                  FROM tb_ref_sanf AS r INNER JOIN tb_empresa AS e INNER JOIN tb_sanfonas as s 
                  ON r.id_cliente = e.id
                  AND r.id_modelo = s.id 
                  ORDER BY r.entrada DESC;";

        $result = mysqli_query($conexao, $query);
				  

        echo"  <div class=\"page_form\" id=\"no_margin\">
                <table class=\"search-table\" id=\"tabItens\" >   
                    <tr>
                        <th>ID.</th>
                        <th>ENTRADA</th>
                        <th>CLIENTE</th>
                        <th>MODELO</th>
                        <th>NUMERO</th>
                        <th>TIPO</th>
                        <th>STATUS</th>
                        <th>PREVISÃO</th>
                        <th>OBS:</th>
                    </tr>";
                    while($fetch = mysqli_fetch_row($result)){
                        echo "<tr class='tbl_row'>".
                                    "<td>" .$fetch[0] . "</td>".
                                    "<td>" .date('d/m/Y', strtotime($fetch[1])). "</td>".
                                    "<td>" .$fetch[2] . "</td>".
                                    "<td>" .$fetch[3]." ".$fetch[4]." ".$fetch[5] . "</td>".
                                    "<td>" .$fetch[6] . "</td>".
                                    "<td>" .$fetch[7] . "</td>".
                                    "<td>" .$fetch[8] . "</td>".
                                    "<td>" .date('d/m/Y', strtotime($fetch[9])). "</td>".
                                    "<td>" .$fetch[10]. "</td>".
                                    "<td style='display: none;'>" .$fetch[11]. "</td>".
                                    "<td style='display: none;'>" .$fetch[12]. "</td>".
                                    "<td style='display: none;'>" .$fetch[1]. "</td>".
                                    "<td style='display: none;'>" .$fetch[9]. "</td>".
                                    "</tr>";                                    
                    }
        echo"    </table> 
                </div>";
				$conexao->close();
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