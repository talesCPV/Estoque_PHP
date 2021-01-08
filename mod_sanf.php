<?php
	setcookie("cod_serv");
  	include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Modelos de Sanfonas</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/mod_sanf.js"></script>
</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Modelos de Sanfonas</p> <br>
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
                $fabricante = $_POST ["edtFab"];
                $modelo = $_POST ["edtModelo"];
                $ano = $_POST ["edtAno"];
                $barras = $_POST ["edtBarras"];
                $dob_teto = $_POST ["edtDobTeto"];
                $dob_chao = $_POST ["edtDobChao"];
                $tipo_sanf = $_POST ["cmbSanf"];
                $l_chao = $_POST ["edtLchao"];
                $c_chao = $_POST ["edtCchao"];
                $bainhas = $_POST ["edtBainha"];
                $alt_sanf = $_POST ["edtAltSanf"];
                $alt_teto = $_POST ["edtAltTeto"];

                include "conecta_mysql.inc";
                if (!$conexao)
                    die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

                if($hdn == "EDITAR"){
                    $query = "UPDATE tb_sanfonas SET fabricante='{$fabricante}', modelo='{$modelo}', ano='{$ano}', barras={$barras}, dob_teto={$dob_teto}, dob_chao={$dob_chao},
                    tipo_sanf='{$tipo_sanf}', chao_larg={$l_chao}, chao_comp={$c_chao}, bainhas={$bainhas}, alt_sanf={$alt_sanf}, alt_prot_teto={$alt_teto}
                    WHERE id={$id} ;";
                }else{
                    $query = "INSERT INTO tb_sanfonas ( fabricante, modelo, ano, barras, dob_teto, dob_chao, tipo_sanf, chao_larg, chao_comp, bainhas, alt_sanf, alt_prot_teto) 
                    VALUES ('{$fabricante}', '{$modelo}', '{$ano}', {$barras}, {$dob_teto}, {$dob_chao}, '{$tipo_sanf}', {$l_chao}, {$c_chao}, {$bainhas}, {$alt_sanf}, {$alt_teto} )";
                        }             
//                echo($query);

                mysqli_query($conexao, $query);
                
            }else if($hdn == "DELETAR"){
                $query = "DELETE FROM tb_sanfonas WHERE id={$id} ;";
                mysqli_query($conexao, $query);
            }

        }        
      

        $query =  "SELECT *	FROM tb_sanfonas ORDER BY modelo;";

        $result = mysqli_query($conexao, $query);
				  

        echo"  <div class=\"page_form\" id=\"no_margin\">
                <table class=\"search-table\" id=\"tabItens\" >   
                    <tr>
                        <th>ID.</th>
                        <th>Modelo</th>
                        <th>Fabricante</th>
                        <th>Ano</th>
                        <th>Barras</th>
                        <th>Dob. Teto</th>
                        <th>Dob. Chão</th>
                        <th>Sanfoninha</th>
                        <th>Chão Larg</th>
                        <th>Chão Velcro</th>
                        <th>Bainhas</th>
                        <th>Alt. Sanfoninha</th>
                        <th>Alt. Prot. Teto</th>
                        <th>Chão</th>
                    </tr>";
                    while($fetch = mysqli_fetch_row($result)){
                        echo "<tr class='tbl_row'>".
                                    "<td>" .$fetch[0] . "</td>".
                                    "<td>" .$fetch[2] . "</td>".
                                    "<td>" .$fetch[1] . "</td>".
                                    "<td>" .$fetch[3] . "</td>".
                                    "<td>" .$fetch[4] . "</td>".
                                    "<td>" .$fetch[5] . "</td>".
                                    "<td>" .$fetch[6] . "</td>".
                                    "<td>" .$fetch[7] . "</td>".
                                    "<td>" .$fetch[8] . "</td>".
                                    "<td>" .$fetch[9] . "</td>".
                                    "<td>" .$fetch[10] . "</td>".
                                    "<td>" .$fetch[11] . "</td>".
                                    "<td>" .$fetch[12] . "</td>".
                                    "<td> <a href='des_chao.html'>desenho</a> </td></tr>";                                    
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