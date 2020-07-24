<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Menu Principal</title>
     <!-- Aqui chamamos o nosso arquivo css externo -->
    <link rel="stylesheet" type="text/css"  href="estilo.css" />
    <!--[if lte IE 8]>
 <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
 <![endif]-->   
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<form class="login-form">
  		  <label> Pesquisar por: </label>
	      <select name="tipo">
	        <option selected="selected" value="prod">Produto</option>
	        <option value="emp">Empresa</option>

			<?php
			  include "valida.inc";
			  if (IsSet($_COOKIE["classe"])){
			    $classe = $_COOKIE["classe"];
			    $user = $_COOKIE["usuario"];
			  }
				if ($classe == "10")
			    {

	        		echo"<option value=\"user\">Usuario</option>";
	        	}
	        ?>
	    </select>
  		    <button type="submit">OK</button>

    	</form>
	  </div>

  </div>



</body>
</html>