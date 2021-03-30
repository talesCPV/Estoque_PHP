<?php
	
	header('Content-Type: text/html; charset=utf-8');

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';
	include 'funcoes.inc';

	setlocale(LC_MONETARY,"pt_BR", "ptb");

	$pdf = new FPDF('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";

    if (IsSet($_GET ["func"])){

		$id = $_GET ["func"];
		$vale = $_GET ["vale"];

		$nomemes = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
		$dia = date('d');
		$mes = date('m');
		$ano = date('Y');

		if (!$conexao)
		die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

		$query =  "SELECT * FROM tb_funcionario WHERE  id = {$id}";
		$result = mysqli_query($conexao, $query);		

		if($vale == "sim"){

			$baixa = $_GET ["baixa"];
			$anterior = $_GET ["valor"];
			$obs = $_GET ["obs"];
			$saldo = $anterior - $baixa;
	
	
			while($fetch = mysqli_fetch_row($result)){
				
				$pdf->Ln(1);
	
				$pdf->Cell(100,5,"Func: ".utf8_decode($fetch[1]),0,0,"L");
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(50,5,"RG: ".RG($fetch[2]),0,0,"L");
				$pdf->Cell(50,5,"CPF: ".CPF($fetch[3]),0,0,"L");
				$pdf->Ln(5);
				$pdf->Cell(150,5,utf8_decode("Obs: ".$obs),0,0,"L");
	
				$pdf->Line(10, 51, 200, 51);
	
				$pdf->SetFont('Arial','B',15);
				$pdf->SetY(60);
				$pdf->Cell(190,5,"VALE",0,0,"C");
				$pdf->Ln(15);
				$pdf->SetFont('Arial','B',10);
	
				$pdf->Cell(150,5,utf8_decode("Empréstimo: "),0,0,"L");
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(50,5,money_format('%=*(#0.2n', $anterior),0,0,"L");
				$pdf->SetFont('Arial','',10);
				$pdf->Ln(5);
				$pdf->Cell(150,5,utf8_decode("Valor debitado : "),0,0,"L");
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(50,5,money_format('%=*(#0.2n', $baixa),0,0,"L");
				$pdf->SetFont('Arial','',10);
				$pdf->Ln(5);
				$pdf->Cell(150,5,utf8_decode("Saldo pendente: "));
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(50,5,money_format('%=*(#0.2n',$saldo),0,0,"L");
				$pdf->SetFont('Arial','',10);
				$pdf->Ln(15);

				$pdf->SetY(120);
				$pdf->SetX(120); 
	
				$pdf->Cell(100,5,utf8_decode($fetch[1]),0,0,"L");
				$pdf->Line(110, 120, 180, 120);
				$pdf->Cell(100,5,utf8_decode($fetch[1]),0,0,"L");
				$pdf->Ln(7);
				$pdf->SetX(121); 
				$pdf->Cell(100,5,utf8_decode("Caçapava, {$dia} de {$nomemes[intval($mes-1)]} de {$ano} "),0,0,"L");
				$pdf->Ln(15);
	
			}
	
		}else{

			$valor = money_format('%=*(#0.2n', $_GET ["valor"]);
			$ref = utf8_decode($_GET ["ref"]);
			$obs = utf8_decode($_GET ["obs"]);



//			$pdf->Cell(190,5,"dia:{$dia} mes:{$nomemes[intval($mes-1)]} ano:{$ano} ",0,0,"C");
//			$pdf->Ln(5);


			$pdf->SetFont('Arial','B',15);
			$pdf->SetY(60);
			$pdf->Cell(190,5,"RECIBO",0,0,"C");
			$pdf->Ln(15);
			$pdf->SetFont('Arial','',10);			


			while($fetch = mysqli_fetch_row($result)){
				$nome = utf8_decode($fetch[1]);
				$cpf = CPF($fetch[3]);

				$pdf->Cell(190,5,"Declaro que eu {$nome},",0,0,"C");
				$pdf->Ln(5);
				$pdf->Cell(190,5,"inscrito no CPF {$cpf}",0,0,"C");
				$pdf->Ln(5);
				$pdf->Cell(190,5,"recebi {$valor} referente a {$ref}",0,0,"C");
				$pdf->Ln(5);
				$pdf->Cell(190,5,$obs,0,0,"C");
				$pdf->Ln(5);
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(190,5,"de Flexibus Sanfonados LTDA CNPJ 00.519.547/0001-06",0,0,"C");
				$pdf->SetFont('Arial','B',10);
				$pdf->Ln(25);

				$pdf->SetX(30); 
				$pdf->Cell(100,5,utf8_decode("Caçapava, {$dia} de {$nomemes[intval($mes-1)]} de {$ano} "),0,0,"L");


				$pdf->SetY(150);
				$pdf->SetX(120); 
				$pdf->Line(110, 150, 180, 150);
				$pdf->Cell(100,5,utf8_decode($fetch[1]),0,0,"L");
				$pdf->Ln(7);
				$pdf->SetX(121); 

				
			}




		}


	}

	$pdf->Output();
    $conexao->close();

?>