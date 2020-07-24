<?php
	setlocale(LC_MONETARY,"pt_BR", "ptb");

	$pdf->SetFont('Arial','B',10);
	$pdf->Image("img/logo.png",10,15,-200);
	$pdf->Cell(190,10, "Av. Dr. Rosalvo de Almeida Telles, 2070 - Nova Cacapava",0,0,"C");
	$pdf->Ln(5);
	$pdf->Cell(190,10, "Cacapava-SP - CEP 12.283-020",0,0,"C");
	$pdf->Ln(5);
	$pdf->Cell(190,10, "CNPJ 00.519.547/0001-06",0,0,"C");
	$pdf->Ln(5);
	$pdf->Cell(190,10, "comercial@flexibus.com.br | (12) 3653-2230",0,0,"C");
	$pdf->Line(10, 35, 200, 35);
	$pdf->Ln(12);
	$pdf->Line(10, 270, 200, 270);

	$pdf->AliasNbPages('{totalPages}');
	$pdf->SetY(271);
	$pdf->Cell(210, 5, $pdf->PageNo()."/{totalPages}", 0, 0, 'R');
	$pdf->SetY(37);

?>