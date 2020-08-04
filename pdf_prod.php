<?php

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';


	$pdf = new FPDF('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";

    if (IsSet($_POST ["query"])){
        $query = $_POST ["query"];

		if (!$conexao)
		  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

		$result = mysqli_query($conexao, $query);


		$pdf->SetFont('Arial','B',10);
	  	$pdf->Cell(10,5,"Cod.",0,0,"L");
	  	$pdf->Cell(85,5,"Descricao",0,0,"L");
	  	$pdf->Cell(25,5,"NCM.",0,0,"L");
	  	$pdf->Cell(15,5,"Und.",0,0,"L");
	  	$pdf->Cell(20,5,"Qtd..",0,0,"C");
		if(isset($_POST['ver_preco'])){	  	
		  	$pdf->Cell(15,5,"Preco.",0,0,"C");
		}else{
		  	$pdf->Cell(15,5,"Fornecedor.",0,0,"C");
		}
  		$pdf->Ln(5);

		$pdf->SetFont('Arial','',10);


		while($fetch = mysqli_fetch_row($result)){
			$venda = $fetch[7] * (1 + $fetch[8]/100);

			if ($pdf->Gety() > 265){
			    include "pdf_cabrod.inc";
				$pdf->SetFont('Arial','B',10);
			  	$pdf->Cell(10,5,"Cod.",0,0,"L");
			  	$pdf->Cell(85,5,utf8_decode("Descrição"),0,0,"L");
			  	$pdf->Cell(25,5,"NCM.",0,0,"L");
			  	$pdf->Cell(15,5,"Und.",0,0,"L");
			  	$pdf->Cell(20,5,"Qtd..",0,0,"C");
				if(isset($_POST['ver_preco'])){	  	
				  	$pdf->Cell(15,5,"Preco.",0,0,"C");
				}else{
				  	$pdf->Cell(15,5,"Fornecedor.",0,0,"C");
				}
		  		$pdf->Ln(5);

				$pdf->SetFont('Arial','',10);

			}

  		  	$pdf->Cell(10,5,utf8_decode(strtoupper($fetch[1])),0,0,"L");
		  	$pdf->Cell(85,5,utf8_decode(substr(strtoupper($fetch[2]), 0, 35)),0,0,"L");
		  	$pdf->Cell(25,5,utf8_decode(strtoupper($fetch[9])),0,0,"L");
		  	$pdf->Cell(15,5,utf8_decode(strtoupper($fetch[3])),0,0,"L");
		  	$pdf->Cell(20,5,utf8_decode(strtoupper($fetch[4])),0,0,"C");
  			if(isset($_POST['ver_preco'])){	  	
				$pdf->Cell(15,5,money_format('%=*(#0.2n', $venda),0,0,"L");
			}else{
			  	$pdf->Cell(15,5,substr(strtoupper($fetch[6]), 0, 10),0,0,"C");
			}
	  		$pdf->Ln(5);

		 }

    }

	$pdf->Output();
    $conexao->close();

?>