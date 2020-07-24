<?php

	header('Content-Type: text/html; charset=utf-8');
	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';

	$path = "config/etq_data.cfg";


	$pdf = new FPDF('L','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)
	$pdf->SetMargins( 84, 10, 0, 0 );

	$pdf->AddPage();
	$x = 10;
	$y = 84;

//    include "pdf_cabrod.inc";

	if (file_exists($path)){
	    $fp = fopen($path, "r");
	    $count = 0;
	    while (!feof ($fp)) {  // varre as linhas do arquivo
		
	//		$pdf->Image("img/menu_logo.png",10,55,-200);
			$linha = fgets($fp,4096);
			$field = explode("|", $linha);


			if(count($field) > 7){
				$count++;

//				$pdf->SetFont('Arial','',15);
//				$pdf->Cell(1,6,$count,0,0,"L");

				if ($count > 2){
//				    $pdf->Cell(30,105,'',0,0,"L");
				    $x = 10;
				    $count = 1;
					$pdf->Ln(74);
					$pdf->Cell(1,6,utf8_decode(''),0,0,"L");
				}

				$pdf->Line($y, $x, $y + 125 , $x);	    
				$pdf->Line($y + 125, $x,$y + 125, $x+35);	    
				$pdf->Line($y + 125, $x+35, $y , $x+35);	    
				$pdf->Line($y , $x+35, $y, $x );	    

				$pdf->SetFont('Arial','',15);
				$pdf->Cell(60,6,utf8_decode($field[0]),0,0,"L");
				$pdf->Ln(6);
				$pdf->SetFont('Arial','B',13);
				$pdf->Cell(60,6,strtoupper(substr(utf8_decode($field[2]),0,48)),0,0,"L");
				$pdf->SetFont('Arial','',13);
				$pdf->Ln(6);

				if($field[3] == "ES"){
					$pdf->Cell(30,6,utf8_decode('DILUIÇÃO DE 10% a 20%'),0,0,"L");
					$pdf->Ln(6);
				}
				if($field[3] == "AC"){
					$pdf->Cell(30,6,utf8_decode('CATALIZADOR 573.790     CATÁLISE 2X1'),0,0,"L");
					$pdf->Ln(6);
					$pdf->Cell(30,6,utf8_decode('DILUIÇÃO DE 30% A 40%   THINNER 8000'),0,0,"L");
				}
				if($field[3] == "AL"){
					$pdf->Cell(30,6,utf8_decode('CATALIZADOR 573.790     CATÁLISE 3X1'),0,0,"L");
					$pdf->Ln(6);
					$pdf->Cell(30,6,utf8_decode('DILUIÇÃO DE 20%         THINNER 8000'),0,0,"L");
				}
				if($field[3] == "LN"){
					$pdf->Cell(30,6,utf8_decode('Diluição de 100%        THINNER 7000'),0,0,"L");
					$pdf->Ln(6);
				}
				if($field[3] == "PL"){
					$pdf->Ln(3);
					$pdf->Cell(30,6,utf8_decode('NÃO DILUIR, PRODUTO PRONTO PARA APLICAÇÃO'),0,0,"L");
					$pdf->Ln(3);
				}
				if($field[3] == "EP"){
					$pdf->Ln(3);
					$pdf->Cell(30,6,utf8_decode('CATALIZADOR EPOXI 175.010 - 3X1'),0,0,"L");
					$pdf->Ln(3);
				}
				if($field[3] == "SB"){
					$pdf->Cell(30,6,utf8_decode('CATALIZADOR 573.790     CATÁLISE 5X1'),0,0,"L");
					$pdf->Ln(6);
					$pdf->Cell(30,6,utf8_decode('DILUIÇÃO DE 5%    THINNER 8000'),0,0,"L");
				}

				$pdf->Ln(6);
				$pdf->Cell(30,5,utf8_decode('Fabricação'),0,0,"L");
				$pdf->Cell(25,5,utf8_decode($field[4]),0,0,"L");
				$pdf->Cell(30,5,'Validade',0,0,"L");
				$pdf->Cell(25,5,utf8_decode($field[5]),0,0,"L");
				$pdf->Ln(5);
				$pdf->SetFont('Arial','B',13);				
				$pdf->Cell(30,6,'Volume',0,0,"L");
				$pdf->Cell(25,6,utf8_decode($field[6]),0,0,"L");
//				$pdf->Cell(15,6,'Cliente',0,0,"L");
				$pdf->Cell(50,6,utf8_decode($field[1]),0,0,"L");
				$pdf->Ln(21);

				$x = $x + 50;

			}

		}
	    fclose($fp);
	}

	$pdf->Output();
    $conexao->close();



?>