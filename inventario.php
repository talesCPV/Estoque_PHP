<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Inventario</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script> 
    <script src="js/cad_etq.js"></script> 
</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >
  <header>
    <?php
      include "menu.inc";
      include "funcoes.inc";  

    ?>
  </header>

<div class="page_container">  
  <div class="page_form">
    <p class="logo">Inventário de Estoque</p> <br>
        <form class="login-form" method="POST" action="#">
            <table class="search-table"  border="0"><tr>
                    <td> <label> Cod. do Produto</label> </td>
                    <td> <input type="text" name="edtCodProd" id="edtCodProd"> </td>  
                    <td> <label> Quantidade</label> </td>
                    <td> <input type="text" name="edt_Qtd" id="edtQtd"> </td>                           
                    <td> <button name="btnAdd" class="botao_inline" type="submit">Adicionar</button> </td>
                    <input type="hidden" name="check" value="1" >
                    </tr> 
            </table>
        </form>
  </div>

  <div id="div_frm"></div>
    <?php

        $path = "config/inventario.cfg";
        switch (get_post_action('btnAdd','btnDel','btnLimpar','btnOK')) {            
            case 'btnAdd':
                if (IsSet($_POST ["edtCodProd"]) && IsSet($_POST ["edt_Qtd"]) ){
                    $line = $_POST ["edtCodProd"] ."->".$_POST ["edt_Qtd"]."\r\n";
                    if (file_exists($path)){
                        $fp = fopen($path, "r");
                        $invent = fread($fp, filesize($path));
                        fclose($fp);
                    }else{
                        $invent = "";
                    }
                    $fp = fopen($path, "w");
                    fwrite($fp, $invent.$line);
                    fclose($fp);
                }

            break;
            case 'btnLimpar':
                if (file_exists($path)){
                   unlink($path);
                }
            break;
            case 'btnDel':    
                if (IsSet($_POST ["sel_prod"])){
                  if (file_exists($path)){
                      $fp = fopen($path, "r");
                      $count = 0;
                      $txt="";
                      while (!feof ($fp)) {  // varre as linhas do arquivo                
                        $linha = fgets($fp,4096);
                        if ($count != $_POST ["sel_prod"]){
                          $txt = $txt . $linha;
                        }
                        $count++;
                      }
                  }
                  fclose($fp);
                  $fp = fopen($path, "w");
                  fwrite($fp, $txt);
                  fclose($fp);
                }
            break; 
            case 'btnOK':            
                if (file_exists($path)){
                    $fp = fopen($path, "r");
                    include "conecta_mysql.inc";
                    if (!$conexao)
                        die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());                      
                    while (!feof ($fp)) {  // varre as linhas do arquivo                
                        $linha = trim(fgets($fp,4096));
                        if(strlen($linha) > 0){
                            $field = explode("->", $linha);
                            $cod = trim($field[0]);
                            $qtd = trim($field[1]);
                            $opt = $_POST ["sel_opt"];
                            if($opt == '1'){
                                $query = " UPDATE tb_produto SET estoque = estoque - {$qtd} WHERE cod = '{$cod}';";
                            }else{
                                $query = " UPDATE tb_produto SET estoque = {$qtd} WHERE cod = '{$cod}';";
                            }
                            echo $query;
                            $result = mysqli_query($conexao, $query);
                        }
                    }
                    $conexao->close();
                    unlink($path);
                }
                fclose($fp);                
            break; 
            default:
           //no action sent
        }          

              echo"
                <div class=\"page_form\" id= \"no_margin\">                
                <form class=\"login-form\" name=\"cadastro\" method=\"POST\" action=\"#\" >

                    <select name=\"sel_prod\" size=\"10\">
                    <optgroup label=\"Lista de Produtos\">";
                $count = 0;

                if (file_exists($path)){
                    $fp = fopen($path, "r");
                    while (!feof ($fp)) {  // varre as linhas do arquivo                    
                        $linha = fgets($fp,4096);
                        echo"<option value='{$count}'>{$linha}</option>";
                        $count++;
                    }
                    fclose($fp);
                }

                echo"
                    </optgroup>
                    </select>


                </tr>    
                <table><tr>            
                <td><button name=\"btnDel\" id=\"botao_inline\" type=\"submit\">Deletar Linha</button></td>
                <td><button name=\"btnLimpar\" id=\"botao_inline\" type=\"submit\">Limpar Tudo</button></td>
                </form>  
                <form class=\"login-form\" name=\"cadastro\" method=\"POST\" action='#' >
                    <td><select name=\"sel_opt\" >
                        <option value='1'>Retirar do Estoque</option>
                        <option value='2'>Fazer o Inventario da Lista</option>
                    </td></select>
                  <td><button name=\"btnOK\" id=\"botao_inline\" type=\"submit\">Confirmar</button></td>
                </form>  
                </tr></table>
                </div>";

    ?>




  </div>





  
</div>


</body>
</html>