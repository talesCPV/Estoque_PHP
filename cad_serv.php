<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
    <title>Serviços Executados</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";

      include "conecta_mysql.inc";
      if (!$conexao)
        die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());


      if (IsSet($_POST["num"]) && IsSet($_POST["txt_obs"]) && IsSet($_POST["equipe"])){
        if(trim($_POST["num"]) != "" && trim($_POST["txt_obs"]) != "" && trim($_POST["equipe"]) != "" ){
            $cliente = $_POST['cli'];
            $data_exec = $_POST['data_exec'];
            $num_car = $_POST['num'];
            $funcionarios = $_POST['equipe'];
            $nf = $_POST['nf'];
            $pedido = $_POST['pedido'];
            $servico = $_POST['txt_obs'];

            $query = "INSERT INTO tb_serv_exec ( id_emp, data_exec, num_carro, func, obs, nf, pedido) 
            VALUES ('{$cliente}', '{$data_exec}', '{$num_car}','{$funcionarios}', '{$servico}', '{$nf}', '{$pedido}')";
            	mysqli_query($conexao, $query);
    

        }

		
    }
    

    ?>
  </header>

<div class="page_container">  
  <div class="page_form">
    <p class="logo"> Cadastro de Serviços Executados</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="#" id="frmSaveEmp">
    <label> Cliente </label>
      <?php  


        $query = "SELECT * from tb_empresa where tipo = \"CLI\" order by nome";
        $result = mysqli_query($conexao, $query);

          echo "<td><select name=\"cli\" id=\"cli\">";


        while($fetch = mysqli_fetch_row($result)){
            echo $fetch[1] . "<br>";
            echo "<option value=\"". $fetch[0] ."\">". $fetch[1] ."</option>";
        }

            echo "</select> </td>";
          $conexao->close();
      ?>
      <label id="lblDataExec"> Data</label>
      <input type="date" name="data_exec" id="edtDataVenc" value="<?php echo date('Y-m-d'); ?>">

      <label> Número do Carro *</label>
      <input type="text" name="num" maxlength="15" id="edtNum"/>
      <label> Equipe (funcionários) * </label>
      <input type="text" name="equipe" maxlength="60" id="edtEquipe"/>
      <label> Nota Fiscal </label>
      <input type="text" name="nf" maxlength="60" id="edtNF"/>
      <label> Pedido </label>
      <input type="text" name="pedido" maxlength="60" id="edtPedido"/>
      <label> Serviços Executados: *</label>
      <textarea class='edtTextArea' name="txt_obs" cols="112" rows="2" id="txt_obs" ></textarea>
      
      <button type="submit" onclick="return obrigatorio(['edtNum', 'edtEquipe', 'txt_obs'])">Cadastrar</button>
    </form>
  </div>
</div>

</body>
</html>