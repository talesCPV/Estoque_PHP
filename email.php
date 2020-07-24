<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
    <title>Email</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" charset="UTF-8"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/funcoes.js " charset="UTF-8"></script>
</head>
<body>
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Email Corporativo</p> <br>
  	  	<form class="login-form" method="POST" action="#">
  	  	<table class="search-table"  border="0">
			<tr>
				<td><select name="campo" id="selPesqEmail">
				<optgroup label="Caixa de Entrada"> 
	        		<option value="todos">Todos</option>
					<option value="remetente">Remetente</option>
					<option value="assunto">Assunto</option>
					<option value="conteudo">Conteúdo</option>
				</optgroup>
				<optgroup label="Emails Enviados"> 
	        		<option value="env_tod">Todos</option>
					<option value="env_rem">Remetente</option>
					<option value="env_ass">Assunto</option>
					<option value="env_cont">Conteúdo</option>
				</optgroup>
				</select></td>
				<td><input type="text" name="valor" maxlength="12"/></td>

			<td><button class="btnReceber" id="botao_inline" type="submit">Buscar</button></td>
			<td><button type="button" class="btnNovoEmail" id="botao_inline" type="submit">Novo</button></td>
		</tr>
      </table>


				<input type="checkbox" id="ckbDatas" name="ckbDatas" checked>
				<label for="ckbDatas">Início / Final</label>			
		<table class="search-table"  border="0"><tr>
			<td> <input type="date" name="data_ini" id="selData_ini" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date('m') , 1 , date('Y'))); ?>"> </td>
			<td> <input type="date" name="data_fin" id="selData_fin" value="<?php echo date('Y-m-d',mktime(23, 59, 59, date('m'), date("t"), date('Y'))); ?>"> </td></tr>
		</table>

    	</form>
	  </div>
	</div>

	<?php

		if (IsSet($_POST ["campo"]) && IsSet($_COOKIE["mail_pass"])){

			$host = "br610.hostgator.com.br"; 
			$usuario = $_COOKIE["email"];
			$senha = $_COOKIE["mail_pass"];
			$campo = $_POST ["campo"];
			$valor = $_POST ["valor"];
			$dat_ini = $_POST ["data_ini"];
			$dat_fin = $_POST ["data_fin"];		
			$box = 	"INBOX";	

//echo($usuario."<br>");
//echo($senha."<br>");

			$filter = '';
			if ($campo == "todos"){
				$filter = $filter .' ALL ';
			}elseif($campo == "remetente"){
				$filter = $filter . ' FROM '. $valor;
			}elseif($campo == "assunto"){
				$filter = $filter . '  SUBJECT '. $valor;
			}elseif($campo == "conteudo"){
				$filter = $filter . '   BODY '. $valor;
			}elseif ($campo == "env_tod"){
				$filter = $filter .' ALL ';
				$box = "INBOX.Sent";
			}elseif($campo == "env_rem"){
				$filter = $filter . ' FROM '. $valor;
				$box = "INBOX.Sent";
			}elseif($campo == "env_ass"){
				$filter = $filter . '  SUBJECT '. $valor;
				$box = "INBOX.Sent";
			}elseif($campo == "env_cont"){
				$filter = $filter . '   BODY '. $valor;
				$box = "INBOX.Sent";
			}

			if (IsSet($_POST ["ckbDatas"])){
				$filter = $filter . '  SINCE '. $dat_ini . ' BEFORE '.$dat_fin;
			}
		

			$inbox = imap_open("{".$host.":993/imap/ssl/novalidate-cert}".$box,trim($usuario),trim($senha))
			or die("<br>Usuário ou senha incorretos: <br><br>" . imap_last_error());


			$emails = imap_search($inbox,$filter);

			echo"  
			<div class='page_container'>  
				<div class='page_form' id='no_margin'>
							<table class=\"search-table\" id=\"tabItens\" >   
								<tr>
								<th>Data</th>";
			if($box=='INBOX'){
				echo           "<th>Remetente</th>";
			}else{
				echo           "<th>Destinatário</th>";
			}
			echo     			"<th>Assunto</th>
								<th>&</th>
								</tr> ";
				
			if($emails){
				rsort($emails);
				foreach ($emails as $message) {
					$overview = imap_fetch_overview($inbox,"{$message}:{$message}",0)[0];
					$structure = imap_fetchstructure($inbox,$message);
					if(substr(imap_qprint( strtolower($overview->subject) ),0,8) == '=?utf-8?'){
						$subject = substr(imap_qprint( $overview->subject ),10,strlen($overview->subject));
					}else{
						$subject = $overview->subject;
					}
					if(isset($structure->parts) && count($structure->parts) > 1) {
						$attachments = '✱';
					}else{
						$attachments = '' ;
					} 					
					echo "<tr class='tbl_row' id='".$overview->msgno."'>
						<td>".date('d/m/Y', strtotime($overview->date))."</td>";
					if($box=='INBOX'){
						echo"<td>".substr(imap_utf8(imap_qprint($overview->from)), 0, 40)."</td>";
					}else{
						echo"<td>".substr(imap_utf8(imap_qprint($overview->to)), 0, 40)."</td>";
					}
					echo "<td>".substr($subject, 0, 40)."</td>
						<td>".imap_utf8($attachments)."</td>
					</tr>";
				}
			}else{
				echo "<tr><td> NENHUM EMAIL ENCONTRADO </td><td></td><td></td></tr>";
			}
			echo"</table> </div> </div>";

		}    

		
	?>


<div class="overlay">	
  <div class="popup">
    <h2 id="popTitle"></h2>
    <div class="close" >&times</div>
    <div class="content"></div>
  </div>
</div>

<div class="modal"></div>

</body>
</html>