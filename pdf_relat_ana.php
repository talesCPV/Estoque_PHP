<?php

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';


	$pdf = new FPDF('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";

    if (IsSet($_POST ["query"])){
        $query = $_POST ["query"];
		$obs = $_POST ["edtObs"];
		$cabecalho = false;
//        $inicio = $_POST ["ini"];
//		$final = $_POST ["fin"];

		if (!$conexao)
		  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

		$result = mysqli_query($conexao, $query);
		$total_exec = 0;
		$total_nao_exec = 0;
		$total = 0;
		$cont_exec = 0;
		$cont_nao_exec = 0;
		$cont = 0;

		while($fetch = mysqli_fetch_row($result)){

			if(!$cabecalho){
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(15,5,"Cliente.",0,0,"L");
	  		  	$pdf->Cell(25,5,utf8_decode(strtoupper($fetch[1])),0,0,"L");
				$pdf->Ln(10);
	
				$pdf->SetFont('Arial','B',15);
				$pdf->Cell(15,5,utf8_decode("                                     Relatório de Análise de Frota"),0,0,"L");
				$pdf->SetFont('Arial','',10);
				$pdf->Line(10, 44, 200, 44);        
				$pdf->Ln(15);
		
		
				$pdf->SetFont('Arial','B',10);
					$pdf->Cell(15,5,"Carro.",0,0,"L");
					$pdf->Cell(25,5,utf8_decode("Analisado"),0,0,"L");
					$pdf->Cell(25,5,utf8_decode("Executado"),0,0,"L");
					$pdf->Cell(45,5,utf8_decode("Valor"),0,0,"L");
					$pdf->Ln(5);
		
				$pdf->SetFont('Arial','',10);
				$cabecalho = true;				
			}			


//			$pdf->Cell(15,5,var_dump($nObs),0,0,"L");

			if ($pdf->Gety() > 265){
			    include "pdf_cabrod.inc";
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(15,5,"Carro.",0,0,"L");
				$pdf->Cell(25,5,utf8_decode("Analisado"),0,0,"L");
				$pdf->Cell(25,5,utf8_decode("Executado"),0,0,"L");
				$pdf->Cell(45,5,utf8_decode("Valor"),0,0,"L");
			$pdf->Ln(5);

				$pdf->SetFont('Arial','',10);

			}

			$total += $fetch[8];
			$cont++;
  		  	$pdf->Cell(15,5,utf8_decode(strtoupper($fetch[2])),0,0,"L");
			$pdf->Cell(25,5,utf8_decode(date('d/m/Y', strtotime($fetch[3]))),0,0,"L");
			if($fetch[5]){
				$pdf->Cell(25,5,"SIM",0,0,"L");
				$total_exec += $fetch[8];
				$cont_exec ++;
			}else{
				$pdf->Cell(25,5,utf8_decode("NÃO"),0,0,"L");
				$total_nao_exec += $fetch[8];
				$cont_nao_exec ++;
			}
            $pdf->Cell(45,5,utf8_decode(money_format('%=*(#0.2n',$fetch[8])) ,0,0,"L");
	  		$pdf->Ln(5);

		 }
		 $pdf->Ln(5);
		 $pdf->SetFont('Arial','B',10);
//		 $pdf->Cell(40,5,utf8_decode("Total de {$cont} carros,") ,0,0,"L");
//		 $pdf->Cell(20,5,utf8_decode("Valor:") ,0,0,"L");

		 if($cont_exec > 0){
			$pdf->Cell(30,5,utf8_decode("Executados") ,0,0,"L");
			$pdf->Cell(20,5,utf8_decode($cont_exec." carros ") ,0,0,"L");
			$pdf->Cell(20,5,utf8_decode(money_format('%=*(#0.2n',$total_exec)) ,0,0,"L");
			$pdf->Ln(6);   
		 }
		 if($cont_nao_exec > 0){
			$pdf->Cell(30,5,utf8_decode("Não executados") ,0,0,"L");
			$pdf->Cell(20,5,utf8_decode($cont_nao_exec." carros ") ,0,0,"L");
			$pdf->Cell(20,5,utf8_decode(money_format('%=*(#0.2n',$total_nao_exec)) ,0,0,"L");
			$pdf->Ln(6);   
		 }
		 $pdf->Cell(30,5,utf8_decode("_________________________________________") ,0,0,"L");
		 $pdf->Ln(6);
		 $pdf->Cell(30,5,utf8_decode("Total") ,0,0,"L");
		 $pdf->Cell(20,5,utf8_decode($cont." carros ") ,0,0,"L");
		 $pdf->Cell(20,5,utf8_decode(money_format('%=*(#0.2n',$total)) ,0,0,"L");
		 $pdf->Ln(6);

		 $nObs =  explode("\n", $obs);
		 
		 for($i = 0; $i< count($nObs);$i++){
			 $pdf->Cell(15,5,utf8_decode(strtoupper($nObs[$i])),0,0,"L");
			 $pdf->Ln(5);
		 }		 

    }

	$pdf->Output();
    $conexao->close();

?>