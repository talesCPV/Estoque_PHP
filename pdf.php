<?php

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';


	$pdf = new FPDF();


	$pdf->AddPage();
	$pdf->SetFont('Arial','B',10);
	$pdf->Image("img/logo.png",10,15,-200);
	$pdf->Cell(190,10, "Av. Dr. Rosalvo de Almeida Telles, 2070 - Nova Cacapava",0,0,"C");
	$pdf->Ln(5);
	$pdf->Cell(190,10, "Cacapava-SP - CEP 12.283-020",0,0,"C");
	$pdf->Ln(5);
	$pdf->Cell(190,10, "CNPJ 00.519.547/0001-06",0,0,"C");
	$pdf->Ln(5);
	$pdf->Cell(190,10, "comercial@flexibus.com.br | (12) 3653-2230",0,0,"C");
	$pdf->Line(10, 35, 190, 35);

	$pdf->Ln(12);

	if (!$conexao)
	  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

	$cod_ped = $_POST ["cod_ped"];

	$query =  "SELECT p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.resp
	         FROM tb_pedido AS p INNER JOIN tb_empresa AS e 
	         ON p.id = '". $cod_ped ."' AND p.id_emp = e.id ;";

	$result = mysqli_query($conexao, $query);

	$pdf->SetFont('Arial','',10);

	while($fetch = mysqli_fetch_row($result)){
	  	$num_ped = $fetch[0];

	  	$pdf->Cell(50,5,"Cotacao: ".strtoupper($fetch[0]),0,0,"L");
	  	$pdf->Cell(50,5,"Cliente: ".strtoupper($fetch[1]),0,0,"L");
	  	$pdf->Cell(50,5,"Comprador: ".strtoupper($fetch[2]),0,0,"L");
	  	$pdf->Cell(50,5,"Vendedor: ".strtoupper($fetch[5]),0,0,"L");
  		$pdf->Ln(5);

	  	$pdf->Cell(50,5,"Data: ".date('d/m/Y', strtotime($fetch[3])),0,0,"L");
	  	$pdf->Cell(50,5,"Entrega: ".date('d/m/Y', strtotime($fetch[4])),0,0,"L");
		$pdf->Line(10, 50, 190, 50);

		$pdf->Ln(15);
	}


	$query =  "SELECT p.cod, p.descricao, p.ncm, p.unidade, i.qtd, i.preco
	        FROM tb_item_ped as i 
	        INNER JOIN tb_produto AS p
	        ON i.id_ped = '". $cod_ped ."' AND i.id_prod = p.id;";


	$result = mysqli_query($conexao, $query);

	$total = 0;

		$pdf->SetFont('Arial','B',10);
	  	$pdf->Cell(15,5,"Cod.",0,0,"C");
	  	$pdf->Cell(80,5,"Descricao",0,0,"L");
	  	$pdf->Cell(20,5,"NCM",0,0,"C");
	  	$pdf->Cell(20,5,"Und.",0,0,"C");
	  	$pdf->Cell(15,5,"Qtd.",0,0,"C");
	  	$pdf->Cell(20,5,"Valor Unit.",0,0,"L");
	  	$pdf->Cell(20,5,"SubTotal",0,0,"L");
  		$pdf->Ln(5);
		$pdf->SetFont('Arial','',10);


	while($fetch = mysqli_fetch_row($result)){
		$venda = $fetch[5];
		$subtotal = $fetch[4] * $venda;
		$total = $total + $subtotal;

	  	$pdf->Cell(15,5,strtoupper($fetch[0]),0,0,"C");
	  	$pdf->Cell(80,5,strtoupper($fetch[1]),0,0,"L");
	  	$pdf->Cell(20,5,strtoupper($fetch[2]),0,0,"C");
	  	$pdf->Cell(20,5,strtoupper($fetch[3]),0,0,"C");
	  	$pdf->Cell(15,5,strtoupper($fetch[4]),0,0,"C");
	  	$pdf->Cell(20,5,money_format('%=*(#0.2n', $fetch[5]),0,0,"L");
	  	$pdf->Cell(20,5,money_format('%=*(#0.2n', $subtotal),0,0,"L");
  		$pdf->Ln(5);
/*

		echo "<tr><td>" .$fetch[0] . "</td>".
		   "<td>" .$fetch[1] . "</td>".
		   "<td>" .$fetch[2] . "</td>".
		   "<td>" .$fetch[3] . "</td>".
		   "<td>" .money_format('%=*(#0.2n', $venda) . "</td>".
		   "<td>" .money_format('%=*(#0.2n', $subtotal) . "</td></tr>";
*/
	}
	$pdf->SetFont('Arial','B',12);
	$pdf->Ln(5);
  	$pdf->Cell(190,5,'TOTAL '.money_format('%=*(#0.2n', $total),0,0,"R");


	$pdf->Output();
    $conexao->close();



?>