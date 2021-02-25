<?php

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';


	$pdf = new FPDF('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";

    if (IsSet($_POST ["query"])){
//        $query = $_POST ["query"];
		if (!$conexao)
		  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

        $cliente = $_POST ["cod_cli"];
        $inicio = $_POST ["ini"];
        $final = $_POST ["fin"];


        $query = "SELECT nome FROM tb_empresa WHERE id={$cliente};";

        
		$result = mysqli_query($conexao, $query);

		while($fetch = mysqli_fetch_row($result)){
			$nome = $fetch[0];
		}

		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','B',10);
	  	$pdf->Cell(15,5,"Cliente.",0,0,"L");
	  	$pdf->Cell(25,5,$nome,0,0,"L");
  		$pdf->Ln(5);
	  	$pdf->Cell(45,5,utf8_decode("Serviços executados dia "),0,0,"L");
	  	$pdf->Cell(18,5,utf8_decode(date('d/m/Y', strtotime($inicio))),0,0,"L");
//	  	$pdf->Cell(5,5,utf8_decode(" a "),0,0,"L");
//	  	$pdf->Cell(18,5,utf8_decode(date('d/m/Y', strtotime($final))),0,0,"L");
  		$pdf->Ln(10);


        $query = "SELECT num_carro, obs, valor FROM tb_serv_exec WHERE id_emp={$cliente} AND data_exec BETWEEN '{$inicio}' AND '{$final}';";

		$result = mysqli_query($conexao, $query);


		$pdf->Line(10, 50, 200, 50);        


		$pdf->SetFont('Arial','',10);

		$total = 0;

		while($fetch = mysqli_fetch_row($result)){
			$carro = $fetch[0];
			$itens = explode("\n", $fetch[1]);
			$valor = money_format('%=*(#0.2n', $fetch[2]);
			$total = $total + $fetch[2];

			if ($pdf->Gety() > 265){
			    include "pdf_cabrod.inc";
			}

			$pdf->SetFont('Arial','B',10);
		  	$pdf->Cell(20,5,"Carro: ",0,0,"L");
			$pdf->SetTextColor(200,0,0);
  		  	$pdf->Cell(15,5,utf8_decode(strtoupper($carro)),0,0,"L");
			$pdf->SetTextColor(0);
  		  	$pdf->Ln(5);
			$pdf->SetFont('Arial','',10);

//            $pdf->Cell(25,5,utf8_decode(date('d/m/Y', strtotime($fetch[3]))),0,0,"L");

			for($i=0; $i<count($itens);$i++){
			  	$pdf->Cell(20,5," ",0,0,"L");
	            $pdf->Cell(20,5,utf8_decode(strtoupper($itens[$i])),0,0,"L");
		  		$pdf->Ln(5);
				
			}
			$pdf->SetFont('Arial','B',10);
		  	$pdf->Cell(20,5,"Valor: ",0,0,"L");
  		  	$pdf->Cell(15,5,utf8_decode(strtoupper($valor)),0,0,"L");
  		  	$pdf->Ln(10);

//            $pdf->Cell(85,5,utf8_decode(strtoupper($valor)),0,0,"L");

		 }

		  	$pdf->Cell(20,5,"Total: ",0,0,"L");
			$pdf->Cell(15,5,utf8_decode(money_format('%=*(#0.2n', $total)),0,0,"L");
			$pdf->SetTextColor(200,0,0);
			$pdf->Ln(10);
			$pdf->Cell(15,5,utf8_decode("Aguardo aprovação e/ou num. do pedido para emissão de nota fiscal"),0,0,"L");
			$pdf->SetTextColor(0);



//Aguardo pedido  para emissão de nota fiscal

    }

	$pdf->Output();
    $conexao->close();

?>