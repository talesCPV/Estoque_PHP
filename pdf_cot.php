<?php
	
	header('Content-Type: text/html; charset=utf-8');

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';
	include 'funcoes.inc';

	$pdf = new FPDF('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";

	if (!$conexao)
	  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

	$cod_ped = $_POST ["cod_ped"];
	$cond_pgto;
	$obs;

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
		if (!IsSet($_POST["planilha"])){
			$pdf->Cell(35,5,"Data: ".date('d/m/Y', strtotime($fetch[3])),0,0,"L");
		}else{
			$pdf->Cell(35,5,"Data: ".date('d/m/Y'),0,0,"L");
			$pdf->Ln(5);

		}
		$pdf->Ln(5);

		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(300,5,"Cliente: ".utf8_decode(substr(strtoupper(trim($fetch[1])), 0, 50)).".",0,0,"L");
		$pdf->SetFont('Arial','',10);
		$pdf->Ln(5);

		$pdf->Cell(140,5,"End.: ".utf8_decode(substr(strtoupper(trim($fetch[6])), 0, 70)),0,0,"L");
		$pdf->Cell(50,5,"Cidade: ". utf8_decode(strtoupper(trim($fetch[9]))) .'-'.strtoupper($fetch[10]),0,0,"L");
		$pdf->Ln(5); // $str = iconv('UTF-8', 'windows-1252', $str);

		$pdf->Cell(50,5,"CEP:".strtoupper(trim($fetch[11])),0,0,"L");
	  	$pdf->Cell(40,5,"Tel.: ".strtoupper(trim($fetch[12])),0,0,"L");
	    $pdf->Cell(50,5,"CNPJ: ". CNPJ(trim($fetch[7])),0,0,"L");
	  	$pdf->Cell(50,5,"IE:".strtoupper(trim($fetch[8])),0,0,"L");
		$pdf->Ln(5);

		if ($fetch[4] != '0000-00-00'){
			$pdf->Cell(70,5,"Previsao de Entrega: ".date('d/m/Y', strtotime($fetch[4])),0,0,"L");
		}else{
			$pdf->Cell(70,5,"Previsao de Entrega: A Combinar",0,0,"L");
		}
		$cond_pgto = $fetch[15];

		if (!IsSet($_POST["planilha"])){
			$pdf->Cell(70,5,"Comprador: ".utf8_decode(strtoupper($fetch[2])),0,0,"L");
			$pdf->Cell(70,5,"Vendedor:".utf8_decode(strtoupper($fetch[5])),0,0,"L");
			$pdf->Ln(5);

		}

		$aux =  explode("\n", $fetch[16]);
		$pdf->Cell(60,5,"Obs.: ",0,1,"L");  
	
		$pdf->SetFont('Arial','B',10);
		for($i = 0; $i< count($aux);$i++){
			$pdf->Cell(60,5,utf8_decode(strtoupper($aux[$i])),0,1,"L");  
		}
		$pdf->SetFont('Arial','',10);

		$obs = $fetch[16];
		$Y = $pdf->GetY();
		$pdf->Line(10, $Y, 200, $Y);
		$pdf->Ln(5);
	}

	switch (get_post_action('imprimir', 'recibo')) {
	    case 'recibo':	
			$pdf->SetFont('Arial','',15);
		  	$pdf->Cell(190,5,"Recibo de Material",0,0,"C");
			$pdf->Ln(10);
		break;
		default:
			$pdf->SetFont('Arial','',15);
			if (IsSet($_POST["planilha"])){
				$pdf->Cell(190,5,utf8_decode("Planilha de Preços"),0,0,"C");
			}else{
				$pdf->Cell(190,5,utf8_decode("Cotação: ".$num_cot),0,0,"C");
			}
		  	$pdf->Ln(10);
  
	}


	$query =  "SELECT p.cod, p.descricao, i.und, i.qtd, i.preco, p.ncm
	        FROM tb_item_ped as i 
	        INNER JOIN tb_produto AS p
	        ON i.id_ped = '". $cod_ped ."' AND i.id_prod = p.id;";


	$result = mysqli_query($conexao, $query);

	$total = 0;

		$pdf->SetFont('Arial','B',10);
	  	$pdf->Cell(15,5,"Cod.",0,0,"L");
	  	$pdf->Cell(103,5,"Descricao",0,0,"L");
//	  	$pdf->Cell(25,5,"Cod. Prod.",0,0,"L");
	  	$pdf->Cell(10,5,"Und.",0,0,"L");
	  	$pdf->Cell(10,5,"Qtd.",0,0,"C");
		if(isset($_POST['ver_preco'])){	  	
		  	$pdf->Cell(25,5,"Valor Unit.",0,0,"L");
		  	$pdf->Cell(20,5,"SubTotal",0,0,"L");
	  }
  		$pdf->Ln(5);
		$pdf->SetFont('Arial','',10);



	while($fetch = mysqli_fetch_row($result)){
		$venda = $fetch[4];
		$subtotal = $fetch[3] * $venda;
		$total = $total + $subtotal;


		if ($pdf->Gety() > 236){
	  		$pdf->Ln(30);
		    include "pdf_cabrod.inc";
			$pdf->SetFont('Arial','B',10);
		  	$pdf->Cell(15,5,"Cod.",0,0,"L");
		  	$pdf->Cell(103,5,utf8_decode("Descrição"),0,0,"L");
//		  	$pdf->Cell(25,5,"Cod.Prod.",0,0,"L");
		  	$pdf->Cell(10,5,"Und.",0,0,"L");
		  	$pdf->Cell(10,5,"Qtd.",0,0,"C");
			if(isset($_POST['ver_preco'])){	  	
			  	$pdf->Cell(25,5,"Valor Unit.",0,0,"L");
			  	$pdf->Cell(20,5,"SubTotal",0,0,"L");
		  	}
	  		$pdf->Ln(5);

			$pdf->SetFont('Arial','',10);

		}


	  	$pdf->Cell(15,5,strtoupper($fetch[0]),0,0,"L");
	  	$pdf->Cell(103,5,utf8_decode(substr(strtoupper($fetch[1]), 0, 36)),0,0,"L");
//	  	$pdf->Cell(25,5,strtoupper($fetch[2]),0,0,"L");
	  	$pdf->Cell(10,5,utf8_decode(strtoupper($fetch[2])),0,0,"L");
	  	$pdf->Cell(10,5,strtoupper($fetch[3]),0,0,"C");
		if(isset($_POST['ver_preco'])){	  	
		  	$pdf->Cell(25,5,money_format('%=*(#0.2n', $fetch[4]),0,0,"L");
		  	$pdf->Cell(20,5,money_format('%=*(#0.2n', $subtotal),0,0,"L");
  		}
  		$pdf->Ln(5);

	}


	if(isset($_POST['ver_preco'])){	  	
		if($desconto > 0){
			$pdf->Ln(5);
			$pdf->SetX(150);
			$pdf->Cell(20,5,'SUBTOTAL ',0,0,"L");
			$pdf->Cell(26,5,money_format('%=*(#0.2n', $total),0,1,"L");

			$pdf->SetX(150);

			$pdf->Cell(20,5,'Desconto ',0,0,"L");
			$pdf->Cell(28,5,money_format('%=*(#0.2n', $desconto),0,0,"L");
			$total = $total - $desconto;
			
			$pdf->Ln(2);
		}
		$pdf->SetFont('Arial','B',12);
		$pdf->Ln(4);
		$pdf->SetX(155);

		$pdf->Cell(15,5,'TOTAL ',0,0,"L");
		$pdf->Cell(33,5,money_format('%=*(#0.2n', $total),0,0,"L");
	}



	$back_Y = $pdf->GetY();

	$pdf->Line(10, 240, 200, 240);
	$pdf->SetY(240);


	$aux =  explode("\n", $cond_pgto);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(20,5,utf8_decode("Condição de Pagamento:"),0,1,"L");  
	$pdf->SetFont('Arial','',8);

	for($i = 0; $i< count($aux);$i++){
		$pdf->Cell(60,5,utf8_decode(strtoupper($aux[$i])),0,0,"L");  
		$pdf->Ln(3);

	}
	$pdf->SetFont('Arial','',10);		

	switch (get_post_action('imprimir', 'recibo')) {
	    case 'recibo':
			$pdf->Ln(25);
	
			$pdf->SetY(230); 
			$pdf->Line(60,$pdf->getY(), 150, $pdf->getY());
			$pdf->SetFont('Arial','',9);
		  	$pdf->Cell(190,5,"Fico ciente da cobranca que sera feita posteriormente",0,0,"C");

	    break;

	}


	$pdf->Output();
    $conexao->close();



?>