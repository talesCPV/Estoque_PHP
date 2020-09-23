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
  		$pdf->Ln(10);



		$pdf->Line(10, 50, 200, 50);        

		$pdf->SetFont('Arial','B',10);
	  	$pdf->Cell(15,5,"Carro.",0,0,"L");
	  	$pdf->Cell(25,5,utf8_decode("Execução"),0,0,"L");
	  	$pdf->Cell(20,5,"NF.",0,0,"L");
	  	$pdf->Cell(85,5,utf8_decode("Serviço"),0,0,"L");
  		$pdf->Ln(5);

		$pdf->SetFont('Arial','',10);


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

  		  	$pdf->Cell(15,5,utf8_decode(strtoupper($fetch[2])),0,0,"L");
            $pdf->Cell(25,5,utf8_decode(date('d/m/Y', strtotime($fetch[3]))),0,0,"L");
            $pdf->Cell(20,5,utf8_decode(strtoupper($fetch[6])),0,0,"L");
            $pdf->Cell(85,5,utf8_decode(substr(strtoupper($fetch[5]), 0, 38)),0,0,"L");
	  		$pdf->Ln(5);

		 }

    }

	$pdf->Output();
    $conexao->close();

?>