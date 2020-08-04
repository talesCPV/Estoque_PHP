<?php
	
	header('Content-Type: text/html; charset=utf-8');

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';
	include 'funcoes.inc';
/*
	function get_post_action($name)
	{
	    $params = func_get_args();

	    foreach ($params as $name) {
	        if (isset($_POST[$name])) {
	            return $name;
	        }
	    }
	}
*/

	$pdf = new FPDF('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";

	if (!$conexao)
	  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

	$cod_ped = $_POST ["cod_ped"];

	$query =  "SELECT p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.resp, e.endereco, e.cnpj, e.ie, e.cidade, e.estado, e.cep, e.tel, p.status, p.desconto, p.cond_pgto, p.obs, p.id
	         FROM tb_pedido AS p INNER JOIN tb_empresa AS e 
	         ON p.id = '". $cod_ped ."' AND p.id_emp = e.id ;";

	$result = mysqli_query($conexao, $query);

	$pdf->SetFont('Arial','',10);

	while($fetch = mysqli_fetch_row($result)){
		$p_id = $fetch[17];
	  	$num_ped = $fetch[0];
	  	$desconto = $fetch[14];
		$num_cot = strtoupper($fetch[0]);
	  	if($fetch[13] == 'ABERTO'){
	  		$pdf->Cell(150,5,$p_id.utf8_decode(" - Cotação: ".$num_cot),0,0,"L");
	  	}else{
	  		$pdf->Cell(150,5,$p_id." - Pedido: ".strtoupper($fetch[0]),0,0,"L");
		  }
		$pdf->Cell(35,5,"Data: ".date('d/m/Y', strtotime($fetch[3])),0,0,"L");
		$pdf->Ln(5);

		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(200,5,"Cliente: ".utf8_decode(substr(strtoupper($fetch[1]), 0, 30)).".",0,0,"L");
		$pdf->SetFont('Arial','',10);
		$pdf->Ln(5);

		$pdf->Cell(150,5,"End.: ".utf8_decode(substr(strtoupper($fetch[6]), 0, 70)),0,0,"L");
		$pdf->Cell(50,5,"Cidade: ". utf8_decode(strtoupper($fetch[9])) .'-'.strtoupper($fetch[10]),0,0,"L");
		$pdf->Ln(5); // $str = iconv('UTF-8', 'windows-1252', $str);

	    $pdf->Cell(50,5,"CNPJ: ". CNPJ($fetch[7]),0,0,"L");
	  	$pdf->Cell(50,5,"IE:".strtoupper($fetch[8]),0,0,"L");
		$pdf->Cell(50,5,"CEP:".strtoupper($fetch[11]),0,0,"L");
	  	$pdf->Cell(40,5,"Tel.: ".strtoupper($fetch[12]),0,0,"L");
		$pdf->Ln(5);

	  	$pdf->Cell(100,5,"Comprador: ".utf8_decode(strtoupper($fetch[2])),0,0,"L");
	  	$pdf->Cell(100,5,"Vendedor:".utf8_decode(strtoupper($fetch[5])),0,0,"L");
  		$pdf->Ln(5);

	  	if ($fetch[4] != '0000-00-00'){
			$pdf->Cell(100,5,"Previsao de Entrega: ".date('d/m/Y', strtotime($fetch[4])),0,0,"L");
		}else{
			$pdf->Cell(100,5,"Previsao de Entrega: A Combinar",0,0,"L");
		}
	  	$pdf->Cell(60,5,"Cond. Pgto.:".strtoupper($fetch[15]),0,0,"L");
    	$pdf->Ln(5);


	  	$pdf->Cell(200,5,"Obs.: ".strtoupper($fetch[16]),0,0,"L");
  		$pdf->Ln(5);

		$pdf->Line(10, 75, 200, 75);

		$pdf->Ln(10);
	}

	switch (get_post_action('imprimir', 'recibo')) {
	    case 'recibo':	
			$pdf->SetFont('Arial','',15);
		  	$pdf->Cell(190,5,"Recibo de Material",0,0,"C");
			$pdf->Ln(10);
		break;
		default:
			$pdf->SetFont('Arial','',15);
	   		$pdf->Cell(190,5,utf8_decode("Cotação: ".$num_cot),0,0,"C");
		  	$pdf->Ln(10);
  
	}


	$query =  "SELECT p.cod, p.descricao, p.cod_bar, i.und, i.qtd, i.preco, p.ncm
	        FROM tb_item_ped as i 
	        INNER JOIN tb_produto AS p
	        ON i.id_ped = '". $cod_ped ."' AND i.id_prod = p.id;";


	$result = mysqli_query($conexao, $query);

	$total = 0;

		$pdf->SetFont('Arial','B',10);
	  	$pdf->Cell(15,5,"Cod.",0,0,"L");
	  	$pdf->Cell(85,5,"Descricao",0,0,"L");
//	  	$pdf->Cell(18,5,"NCM.",0,0,"L");
	  	$pdf->Cell(25,5,"Cod. Prod.",0,0,"L");
	  	$pdf->Cell(10,5,"Und.",0,0,"L");
	  	$pdf->Cell(10,5,"Qtd.",0,0,"C");
		if(isset($_POST['ver_preco'])){	  	
		  	$pdf->Cell(22,5,"Valor Unit.",0,0,"L");
		  	$pdf->Cell(20,5,"SubTotal",0,0,"L");
	  }
  		$pdf->Ln(5);
		$pdf->SetFont('Arial','',10);



	while($fetch = mysqli_fetch_row($result)){
		$venda = $fetch[5];
		$subtotal = $fetch[4] * $venda;
		$total = $total + $subtotal;


		if ($pdf->Gety() > 236){
	  		$pdf->Ln(30);
		    include "pdf_cabrod.inc";
			$pdf->SetFont('Arial','B',10);
		  	$pdf->Cell(15,5,"Cod.",0,0,"L");
		  	$pdf->Cell(85,5,utf8_decode("Descrição"),0,0,"L");
//		  	$pdf->Cell(18,5,"NCM.",0,0,"L");
		  	$pdf->Cell(25,5,"Cod.Prod.",0,0,"L");
		  	$pdf->Cell(10,5,"Und.",0,0,"L");
		  	$pdf->Cell(10,5,"Qtd.",0,0,"C");
			if(isset($_POST['ver_preco'])){	  	
			  	$pdf->Cell(22,5,"Valor Unit.",0,0,"L");
			  	$pdf->Cell(20,5,"SubTotal",0,0,"L");
		  	}
	  		$pdf->Ln(5);

			$pdf->SetFont('Arial','',10);

		}


	  	$pdf->Cell(15,5,strtoupper($fetch[0]),0,0,"L");
	  	$pdf->Cell(85,5,utf8_decode(substr(strtoupper($fetch[1]), 0, 36)),0,0,"L");
//	  	$pdf->Cell(18,5,strtoupper($fetch[6]),0,0,"L");
	  	$pdf->Cell(25,5,strtoupper($fetch[2]),0,0,"L");
	  	$pdf->Cell(10,5,utf8_decode(strtoupper($fetch[3])),0,0,"L");
	  	$pdf->Cell(10,5,strtoupper($fetch[4]),0,0,"C");
		if(isset($_POST['ver_preco'])){	  	
		  	$pdf->Cell(22,5,money_format('%=*(#0.2n', $fetch[5]),0,0,"L");
		  	$pdf->Cell(20,5,money_format('%=*(#0.2n', $subtotal),0,0,"L");
  		}
  		$pdf->Ln(5);

	}

	
	if(isset($_POST['ver_preco'])){	  	
	  	$pdf->Cell(191,5,'_____________________',0,0,"R");
		$pdf->Ln(5);
		if($desconto > 0){
		  	$pdf->Cell(148,5,' ',0,0,"L");
		  	$pdf->Cell(20,5,'SUBTOTAL ',0,0,"L");
		  	$pdf->Cell(26,5,money_format('%=*(#0.2n', $total),0,0,"L");
			$pdf->Ln(5);
		  	$pdf->Cell(148,5,' ',0,0,"L");
		  	$pdf->Cell(20,5,'Desconto ',0,0,"L");
		  	$pdf->Cell(28,5,money_format('%=*(#0.2n', $desconto),0,0,"L");
		  	$total = $total - $desconto;
			$pdf->Ln(2);
		}
		$pdf->SetFont('Arial','B',12);
		$pdf->Ln(4);
	  	$pdf->Cell(148,5,' ',0,0,"L");
	  	$pdf->Cell(20,5,'TOTAL ',0,0,"L");
	  	$pdf->Cell(33,5,money_format('%=*(#0.2n', $total),0,0,"L");
  	}

	switch (get_post_action('imprimir', 'recibo')) {
	    case 'recibo':
			$pdf->Ln(25);
	
			$pdf->Line(60,$pdf->getY(), 150, $pdf->getY());
			$pdf->SetFont('Arial','',9);
		  	$pdf->Cell(190,5,"Fico ciente da cobranca que sera feita posteriormente",0,0,"C");

	    break;

	}


	$pdf->Output();
    $conexao->close();



?>