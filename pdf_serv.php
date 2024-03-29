<?php

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';


	$pdf = new FPDF('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";

    if (IsSet($_POST ["query"])){
        $query = $_POST ["query"];
        $cliente = $_POST ["cliente"];
        $inicio = $_POST ["ini"];
		$final = $_POST ["fin"];
		$obs =  $_POST ["edtObs"];

		if (!$conexao)
		  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

		$result = mysqli_query($conexao, $query);

		$pdf->SetFont('Arial','B',10);
	  	$pdf->Cell(15,5,"Cliente.",0,0,"L");
	  	$pdf->Cell(25,5,$cliente,0,0,"L");
  		$pdf->Ln(5);
	  	$pdf->Cell(45,5,utf8_decode("Serviços executados de "),0,0,"L");
	  	$pdf->Cell(25,5,utf8_decode(date('d/m/Y', strtotime($inicio))),0,0,"L");
	  	$pdf->Cell(15,5,utf8_decode(" até "),0,0,"L");
	  	$pdf->Cell(25,5,utf8_decode(date('d/m/Y', strtotime($final))),0,0,"L");
  		$pdf->Ln(8);

//		$pdf->Line(10, 50, 200, 50);    
		$pdf->SetFont('Arial','B',15);
		$pdf->Cell(15,5,utf8_decode("                         Boletim Informativo de Serviços em Execução"),0,0,"L");
		$pdf->SetFont('Arial','',10);
		$pdf->Line(10, 60, 200, 60);        
		$pdf->Ln(15);


		$pdf->SetFont('Arial','B',10);
	  	$pdf->Cell(15,5,"Carro.",0,0,"L");
	  	$pdf->Cell(25,5,utf8_decode("Execução"),0,0,"L");
	  	$pdf->Cell(20,5,"NF.",0,0,"L");
	  	$pdf->Cell(85,5,utf8_decode("Valor"),0,0,"L");
  		$pdf->Ln(5);

		$pdf->SetFont('Arial','',10);
		$total = 0;
		$cont = 0;

		while($fetch = mysqli_fetch_row($result)){
			$venda = $fetch[7] ;

			if ($pdf->Gety() > 265){
			    include "pdf_cabrod.inc";
				$pdf->SetFont('Arial','B',10);
                $pdf->Cell(15,5,"Carro.",0,0,"L");
                $pdf->Cell(25,5,utf8_decode("Execução"),0,0,"L");
                $pdf->Cell(20,5,"NF.",0,0,"L");
                $pdf->Cell(85,5,utf8_decode("Serviço"),0,0,"L");
                $pdf->Ln(5);

				$pdf->SetFont('Arial','',10);

			}

			$total += $fetch[9];
			$cont++;
  		  	$pdf->Cell(15,5,utf8_decode(strtoupper($fetch[2])),0,0,"L");
            $pdf->Cell(25,5,utf8_decode(date('d/m/Y', strtotime($fetch[3]))),0,0,"L");
            $pdf->Cell(20,5,utf8_decode(strtoupper($fetch[6])),0,0,"L");
            $pdf->Cell(45,5,utf8_decode(money_format('%=*(#0.2n',$fetch[9])) ,0,0,"L");
	  		$pdf->Ln(5);

		 }
		 $pdf->Ln(5);
		 $pdf->SetFont('Arial','B',10);
		 $pdf->Cell(40,5,utf8_decode("Total de {$cont} carros,") ,0,0,"L");
		 $pdf->Cell(20,5,utf8_decode("Valor:") ,0,0,"L");

		 $pdf->Cell(45,5,utf8_decode(money_format('%=*(#0.2n',$total)) ,0,0,"L");
		 $pdf->Ln(10);

		 $nObs =  explode("\n", $obs);
		 for($i = 0; $i< count($nObs);$i++){
			 $pdf->Cell(15,5,utf8_decode(strtoupper($nObs[$i])),0,0,"L");
			 $pdf->Ln(5);
		 }		 

    }

	$pdf->Output();
    $conexao->close();

?>