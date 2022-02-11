<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Dados Cadastrais</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>

<div class="page_container">  
  <div class="page_form">
    <p class="logo"> Dados Cadastrais </p> <br>
    <form class="login-form" name="cadastro" method="POST" action="save_agenda.php" id="frmSaveAgenda" >
      <label> Empresa: Flexibus Sanfonados LTDA. </label>
      <label> CNPJ: 00.519.547/0001-06 </label>
      <label> I.E.: 234.033.845.113 </label>
      <label> I.M:  </label>
      <label> Endereço: Av. Dr. Rosalvo de Almeida Telles, 2070  </label>
      <label> Bairro: Nova Caçapava </label>
      <label> CEP: 12.283-020  </label>
      <label> Telefone: (12) 3653-2230  </label>

       
       <label >Dados Bancários:</label>

        <label>Banco Santander </label>
        <label>Agência: 0016 </label>
        <label>Conta Corrente: 13001189-8 </label>
        <label>Gerente . Letícia - (12) 3654-2500 </label>
            
      <button> <a href="#">Home</a></button>
    </form>
  </div>
</div>

</body>
</html>