<?php
	header('Content-Type: text/html; charset=utf-8');

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';
	include "funcoes.inc";  

    $dados = "config/dados.cfg";

	$vtx = get_id("VTX");
	$aux = explode("/", $vtx);

	$pdf = new FPDF('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";


	if (!$conexao)
	  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

	$query =  "SELECT p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.resp, e.endereco, e.cnpj, e.ie, e.cidade, e.estado, e.cep, e.tel, p.status, p.desconto, p.cond_pgto, p.obs
         FROM tb_pedido AS p INNER JOIN tb_empresa AS e 
         ON p.id = '". $aux[0] ."' AND p.id_emp = e.id ;";
	
	$result = mysqli_query($conexao, $query);

	$pdf->SetFont('Arial','',10);

    while($fetch = mysqli_fetch_row($result)){
	  	$pdf->Cell(80,5,"Cliente: ".utf8_decode(substr(strtoupper($fetch[1]), 0, 30)).".",0,0,"L");
	  	$pdf->Cell(50,5,"CNPJ: ".strtoupper($fetch[7]),0,0,"L");
	  	$pdf->Cell(50,5,"IE:".strtoupper($fetch[8]),0,0,"L");
  		$pdf->Ln(5);

	  	$pdf->Cell(130,5,"End.: ".utf8_decode(substr(strtoupper($fetch[6]), 0, 70)),0,0,"L");
	  	$pdf->Cell(50,5,"Cidade: ". utf8_decode(strtoupper($fetch[9])) .'-'.strtoupper($fetch[10]),0,0,"L");
  		$pdf->Ln(5); // $str = iconv('UTF-8', 'windows-1252', $str);

	  	$pdf->Cell(40,5,"CEP:".strtoupper($fetch[11]),0,0,"L");
	  	$pdf->Cell(40,5,"Tel.: ".strtoupper($fetch[12]),0,0,"L");
	  	$pdf->Cell(50,5,"Comprador: ".utf8_decode(strtoupper($fetch[2])),0,0,"L");
	  	$pdf->Cell(50,5,"Vendedor:".utf8_decode(strtoupper($fetch[5])),0,0,"L");
  		$pdf->Ln(5);

	  	$pdf->Cell(40,5,"Cond. Pgto.:".strtoupper($fetch[15]),0,0,"L");
	  	$pdf->Cell(100,5,"Obs.: ".strtoupper($fetch[16]),0,0,"L");
  		$pdf->Ln(6);
    }


	$pdf->Line(10, 57, 200, 57);


	$pdf->SetFont('Arial','B',10);

		$pdf->Cell(18,5,'Ped.' ,0,0,"L");
		$pdf->Cell(20,5,'Data',0,0,"L");
		$pdf->Cell(95,5,'Descricao',0,0,"L");
		$pdf->Cell(12,5,'Qtd.' ,0,0,"C");
		$pdf->Cell(22,5,'P. Unit.',0,0,"L");
		$pdf->Cell(22,5,'SubTotal.',0,0,"L");
		$pdf->Ln(5);

	$pdf->SetFont('Arial','',10);
    $tot = 0;

	for($i=0;$i<count($aux);$i++){

	   $query =  "SELECT p.id, p.num_ped, p.data_ped, pr.descricao, i.preco, i.qtd
					FROM tb_pedido AS p 
					INNER JOIN tb_produto AS pr 
					INNER JOIN tb_item_ped AS i 
					ON p.id = i.id_ped
					AND p.id = '". $aux[$i] ."'
					AND i.id_prod = pr.id
					AND p.status = 'FECHADO';";

      $result = mysqli_query($conexao, $query);

      while($fetch = mysqli_fetch_row($result)){


		if ($pdf->Gety() >= 255 && $pdf->Gety() <= 265){
	  		$pdf->Ln(20);
		}


		if ($pdf->Gety() > 265){
		    include "pdf_cabrod.inc";
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(18,5,'Ped.' ,0,0,"L");
			$pdf->Cell(20,5,'Data',0,0,"L");
			$pdf->Cell(95,5,'Descricao',0,0,"L");
			$pdf->Cell(12,5,'Qtd.' ,0,0,"C");
			$pdf->Cell(22,5,'P. Unit.',0,0,"L");
			$pdf->Cell(22,5,'SubTotal.',0,0,"L");
	  		$pdf->Ln(5);
			$pdf->SetFont('Arial','',10);
		}


		$pdf->Cell(18,5,$fetch[1] ,0,0,"L");
		$pdf->Cell(20,5,date('d/m/Y', strtotime($fetch[2])),0,0,"L");
		$pdf->Cell(95,5,strtoupper(substr($fetch[3] ,0,40)),0,0,"L");
		$pdf->Cell(12,5,$fetch[5] ,0,0,"C");
		$pdf->Cell(22,5,money_format('%=*(#0.2n',$fetch[4]),0,0,"L");
		$pdf->Cell(22,5,money_format('%=*(#0.2n',($fetch[4] * $fetch[5])),0,0,"L");
		$tot = $tot + ($fetch[4] * $fetch[5]);
		$pdf->Ln(5);
      }

	}
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(145,5,'' ,0,0,"L");
	$pdf->Cell(22,5,'Total' ,0,0,"L");
	$pdf->Cell(22,5,money_format('%=*(#0.2n',$tot),0,0,"L");

	$pdf->Output();

?>