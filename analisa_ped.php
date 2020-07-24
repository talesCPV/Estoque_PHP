<?php

	header('Content-Type: text/html; charset=utf-8');

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';

	$cod_ped = $_POST ["cod_ped"];

	$pdf = new FPDF('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";

	$pdf->SetFont('Arial','',20);
	$pdf->Ln(5);
 	$pdf->Cell(180,5,"Analise Financeira do Pedido",0,0,"C");
	$pdf->Ln(15);

	$pdf->SetFont('Arial','B',10);
  	$pdf->Cell(15,5,"Cod.",0,0,"L");
  	$pdf->Cell(90,5,"Descricao",0,0,"L");
  	$pdf->Cell(20,5,"Und.",0,0,"L");
  	$pdf->Cell(15,5,"Qtd",0,0,"L");
  	$pdf->Cell(15,5,"Custo",0,0,"L");
  	$pdf->Cell(15,5,"Venda",0,0,"L");
  	$pdf->Cell(15,5,"Margem",0,0,"L");
	$pdf->Ln(5);

	if (!$conexao)
	  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

$query =  "SELECT p.cod, p.descricao, p.unidade, i.qtd, i.preco
	        FROM tb_item_ped as i 
	        INNER JOIN tb_produto AS p
	        ON i.id_ped = '". $cod_ped ."' AND i.id_prod = p.id;";

	$result = mysqli_query($conexao, $query);


	while($fetch = mysqli_fetch_row($result)){
	

	  	$pdf->Cell(15,5,strtoupper($fetch[0]),0,0,"L");
	  	$pdf->Cell(90,5,utf8_decode(substr(strtoupper($fetch[1]), 0, 36)),0,0,"L");
	  	$pdf->Cell(20,5,strtoupper($fetch[2]),0,0,"L");
	  	$pdf->Cell(15,5,utf8_decode(strtoupper($fetch[3])),0,0,"L");
	  	$pdf->Cell(15,5,strtoupper($fetch[4]),0,0,"C");
		$pdf->Ln(5);

	}





	$pdf->Output();

?>