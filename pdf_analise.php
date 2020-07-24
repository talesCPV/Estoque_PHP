<?php


function percent($valores){
	$tot = ($valores[0] + $valores[1] + $valores[2]);
	$perc = array(0,0,0);
	for($i = 0; $i < 3; $i++){
		if($valores[$i] > 0){
			$perc[$i] = $valores[$i]/$tot*100; 
		}
	}  
	return $perc;     
}

function pizza($pdf,$yc,$r,$valores){

	$tot = ($valores[0] + $valores[1] + $valores[2]);
	$pos = array(0,0,0);

	$perc = percent($valores);
	if($tot>0){
		$pos[0] = round(360*$perc[0]/100);
		$pos[1] = round(360*($perc[0]/100 + $perc[1]/100 ) );
		$pos[2] = 360;
	}


	$xc = 90;

	$pdf->SetFillColor(120,120,255);
	$pdf->Sector($xc,$yc+5,$r,0,$pos[0]);
	$pdf->SetFillColor(120,255,120);
	$pdf->Sector($xc,$yc+5,$r,$pos[0],$pos[1]);
	$pdf->SetFillColor(255,120,120);
	$pdf->Sector($xc,$yc+5,$r,$pos[1],$pos[2]);
	
	// LEGENDA
	$pdf->SetFillColor(120,120,255);
	$pdf->Rect($xc+$r+15, $yc - 2, 4, 4, 'DF');
	$pdf->SetXY($xc+$r+20, $yc - 2);
	$pdf->Cell(20, 5, 'Custos' , 1, 0);
	$pdf->Cell(40, 5, money_format('%=*(#0.2n',$valores[0]) , 1, 0);
	$pdf->Cell(18, 5, number_format($perc[0] , 2, ',', '  ')  . '%' , 1, 0);
	$pdf->SetFillColor(120,255,120);
	$pdf->Rect($xc+$r+15, $yc +3, 4, 4, 'DF');
	$pdf->SetXY($xc+$r+20, $yc+3);
	$pdf->Cell(20, 5, 'Impostos' , 1, 0);
	$pdf->Cell(40, 5, money_format('%=*(#0.2n',$valores[1]) , 1, 0);
	$pdf->Cell(18, 5, number_format($perc[1], 2, ',', '  ')  . '%' , 1, 0);
	$pdf->SetFillColor(255,120,120);
	$pdf->Rect($xc+$r+15, $yc+8, 4, 4, 'DF');
	$pdf->SetXY($xc+$r+20, $yc+8);
	$pdf->Cell(20, 5, 'Margem' , 1, 0);
	$pdf->Cell(40, 5, money_format('%=*(#0.2n',$valores[2]) , 1, 0);
	$pdf->Cell(18, 5, number_format($perc[2], 2, ',', '  ')  . '%' , 1, 0);
	$pdf->Line(10, $yc+$r+10, 200, $yc+$r+10);
	$pdf->SetY($yc+$r+6);



}



	header('Content-Type: text/html; charset=utf-8');

	include './fpdf/fpdf.php';
    include './fpdf/pdf-sector.php';

	include 'conecta_mysql.inc';

	$cod_ped = $_POST ["cod_ped"];

    $pdf = new PDF_SECTOR('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";
	include "funcoes.inc";  


	if (!$conexao)
	  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

	$cod_ped = $_POST ["cod_ped"];

	$query =  "SELECT p.num_ped, e.nome, p.comp, p.data_ped, p.data_ent, p.resp, e.endereco, e.cnpj, e.ie, e.cidade, e.estado, e.cep, e.tel, p.status, p.desconto, p.cond_pgto, p.obs
	         FROM tb_pedido AS p INNER JOIN tb_empresa AS e 
	         ON p.id = '". $cod_ped ."' AND p.id_emp = e.id ;";

	$result = mysqli_query($conexao, $query);

	$pdf->SetFont('Arial','',10);

	while($fetch = mysqli_fetch_row($result)){
	  	$num_ped = $fetch[0];
	  	$desconto = $fetch[14];

	  	if($fetch[13] == 'ABERTO'){
	  		$pdf->Cell(80,5,"Cotacao: ".strtoupper($fetch[0]),0,0,"L");
	  	}else{
	  		$pdf->Cell(80,5,"Pedido: ".strtoupper($fetch[0]),0,0,"L");
	  	}
	  	$pdf->Cell(50,5,"Data: ".date('d/m/Y', strtotime($fetch[3])),0,0,"L");
	  	if ($fetch[4] != '0000-00-00'){
	  		$pdf->Cell(50,5,"Previsao de Entrega: ".date('d/m/Y', strtotime($fetch[4])),0,0,"L");
	  	}else{
	  		$pdf->Cell(50,5,"Previsao de Entrega: A Combinar",0,0,"L");
	  	}
  		$pdf->Ln(5);

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
  		$pdf->Ln(5);

		$pdf->Line(10, 65, 200, 65);

		$pdf->Ln(7);
		

	}


	$pdf->SetFont('Arial','',15);
 	$pdf->Cell(190,5,"Analise do Pedido",0,0,"C");
	$pdf->SetY(110);

	$pdf->SetFont('Arial','B',10);
  	$pdf->Cell(12,5,"Cod.",0,0,"L");
  	$pdf->Cell(80,5,"Descricao",0,0,"L");
  	$pdf->Cell(18,5,"Und.",0,0,"L");
  	$pdf->Cell(10,5,"Qtd",0,0,"L");
  	$pdf->Cell(25,5,"Custo",0,0,"L");
  	$pdf->Cell(25,5,"Venda",0,0,"L");
  	$pdf->Cell(25,5,"Margem",0,0,"L");
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',10);


	if (!$conexao)
	  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

$query =  "SELECT p.cod, p.descricao, i.und, i.qtd, i.preco, p.preco_comp
	        FROM tb_item_ped as i 
	        INNER JOIN tb_produto AS p
	        ON i.id_ped = '". $cod_ped ."' AND i.id_prod = p.id;";

	$result = mysqli_query($conexao, $query);

	$tot_custo = 0;
	$tot_venda = 0;
	$tot_margem = 0;

	while($fetch = mysqli_fetch_row($result)){
		$custo = $fetch[5];
		if(trim($fetch[2]) == "450ml"){
			$custo = $custo / 2;
	    }
		if(trim($fetch[2]) == "1.8L"){
			$custo = $custo * 2;
	    }
		if(trim($fetch[2]) == "2.7L"){
			$custo = $custo * 3;
	    }
		if(trim($fetch[2]) == "3.6L"){
			$custo = $custo * 4;
		}
		
		if($fetch[0] >= 7000){
			$custo = ($custo /1.18);
		}

		$tot_custo = $tot_custo + ($fetch[3] * $custo);
		$tot_venda = $tot_venda + ($fetch[3] * $fetch[4]);
		$tot_margem = $tot_margem + ($fetch[3] * ($fetch[4] - $custo));

	  	$pdf->Cell(12,5,strtoupper($fetch[0]),0,0,"L");
	  	$pdf->Cell(80,5,utf8_decode(substr(strtoupper($fetch[1]), 0, 36)),0,0,"L");
	  	$pdf->Cell(18,5,strtoupper($fetch[2]),0,0,"L");
	  	$pdf->Cell(10,5,utf8_decode(strtoupper($fetch[3])),0,0,"L");
	  	$pdf->Cell(25,5,money_format('%=*(#0.2n',$fetch[3] * $custo),0,0,"L");
	  	$pdf->Cell(25,5,money_format('%=*(#0.2n',$fetch[3] * $fetch[4]),0,0,"L");
	  	$pdf->Cell(25,5,money_format('%=*(#0.2n',$fetch[3] * ($fetch[4] - $custo)),0,0,"L");
		$pdf->Ln(5);

	}


	$pdf->SetFont('Arial','B',10);
  	$pdf->Cell(120,5,'Subtotal',0,0,"R");
  	$pdf->Cell(25,5,money_format('%=*(#0.2n',$tot_custo),0,0,"L");
  	$pdf->Cell(25,5,money_format('%=*(#0.2n',$tot_venda),0,0,"L");
	$pdf->Cell(25,5,money_format('%=*(#0.2n',$tot_margem),0,0,"L");
	$pdf->SetFont('Arial','',10);
	
	

	$pdf->Line(10, 78, 200, 78);
	$pdf->SetY(80);
	$pdf->Cell(60,5,"Valor total do Pedido: ". money_format('%=*(#0.2n',$tot_venda)   ,0,1,"L");
	$pdf->Cell(70,5,"Valor de custo dos produtos: ". money_format('%=*(#0.2n',$tot_custo),0,1,"L");
	$pdf->Cell(60,5,"ICMS sobre a NF: ". money_format('%=*(#0.2n',$tot_venda * 0.18)   ,0,1,"L");
	$pdf->Cell(60,5,"Valor da Margem: ". money_format('%=*(#0.2n',$tot_margem)   ,0,1,"L");
	$pdf->Cell(60,5,"Liquido: ". money_format('%=*(#0.2n', $tot_margem - ($tot_venda * 0.18)),0,1,"L");

    pizza($pdf ,87,10,[$tot_custo,$tot_venda * 0.18,$tot_margem - ($tot_venda * 0.18)]);


	$pdf->Line(10, 107, 200, 107);
  	

	$pdf->Output();

?>