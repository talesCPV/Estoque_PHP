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
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >
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
            $valor = $_POST['valor'];

            $query = "INSERT INTO tb_serv_exec ( id_emp, data_exec, num_carro, func, obs, nf, pedido, valor) 
            VALUES ('{$cliente}', '{$data_exec}', '{$num_car}','{$funcionarios}', '{$servico}', '{$nf}', '{$pedido}', {$valor})";
            	mysqli_query($conexao, $query);
    
        }
		
    }
    

    ?>
  </header>

<div class="page_container">  
  <div class="page_form">
    <p class="logo"> Cadastro - Análise de Frota</p> <br>
    <form class="login-form" name="cadastro" method="POST" action="#" id="frmSaveServ">
    <label> Cliente </label>
      <?php  

        $query = "SELECT * from tb_empresa where tipo = \"CLI\" order by fantasia";
        $result = mysqli_query($conexao, $query);

          echo "<td><select name=\"cli\" id=\"cli\">";

        while($fetch = mysqli_fetch_row($result)){
            echo $fetch[1] . "<br>";
            echo "<option value=\"". $fetch[0] ."\">". str_pad($fetch[0],3,'0', STR_PAD_LEFT)." - ". strtoupper($fetch[12]) ."</option>";
        }

            echo "</select> </td>";
          $conexao->close();
      ?>
      <label id="lblDataExec"> Data</label>
      <input type="date" name="data_exec" id="edtData" value="<?php echo date('Y-m-d'); ?>">
      <label> Número do Carro *</label>
      <input type="text" name="num" maxlength="15" id="edtNum"/>
      <label> Local da Execução do Serviço </label>
      <select name="cmbLocal" id="cmbLocal">
        <option value="No Cliente">No Cliente</option>
        <option value="Na Flexibus">Na Flexibus</option>
      </select>
      <label> Serviços Executados: *</label>
      <textarea class='edtTextArea' name="txt_obs" id="txt_obs" cols="212" rows="6" id="txt_obs" > </textarea>
      <label> Técnico Responsável * </label>
      <input type="text" name="equipe" maxlength="60" id="edtEquipe" value="Bruno Mathias"/>
      <label> Valor R$ </label>
      <input type="text" name="valor" onkeyup="return float_number(this)" maxlength="60" id="edtValor" value="0"/>
      <button type="submit" id="btnCad" >Cadastrar</button>
    </form>
  </div>
</div>

<div class="overlay"> 
  <div class="popup">
    <h2 id="popTitle"></h2>
    <div class="close" >&times</div>
    <div class="content"></div>
  </div>
</div>

    <script src="js/cad_analise.js"></script>

</body>
</html>