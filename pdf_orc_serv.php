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
		$campo = $_POST ["campo"];
		$valor = $_POST ["valor"];
		$num_carro = $_POST ["num_carro"];
		$pedido = $_POST ["pedido"];
		$compl = " AND s.data_exec >= '{$inicio}' AND s.data_exec <= '{$final}'";
		$check =  $_POST ["check"];
		$obs =  $_POST ["edtObs"];

        $query = "SELECT nome, endereco, num, cnpj FROM tb_empresa WHERE id={$cliente};";

        
		$result = mysqli_query($conexao, $query);

		while($fetch = mysqli_fetch_row($result)){
			$nome = $fetch[0];
			$endereco = $fetch[1];
			$num = $fetch[2];
			$cnpj = $fetch[3];
		}

		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','B',10);
	  	$pdf->Cell(15,5,"Cliente.",0,0,"L");
	  	$pdf->Cell(110,5,utf8_decode($nome),0,0,"L");
	  	$pdf->Cell(15,5,"CNPJ.",0,0,"L");
	  	$pdf->Cell(50,5,utf8_decode($cnpj),0,0,"L");
  		$pdf->Ln(5);
	  	$pdf->Cell(15,5,"End.",0,0,"L");
	  	$pdf->Cell(25,5,utf8_decode( trim($endereco).", ".$num),0,0,"L");
		$pdf->Ln(5);
		if($check && !IsSet($_POST ["origem"])){		  
			$pdf->Cell(55,5,utf8_decode("SERVIÇOS EXECUTADOS DIA "),0,0,"L");
			$pdf->Cell(18,5,utf8_decode(date('d/m/Y', strtotime($inicio))),0,0,"L");
			if($inicio != $final){
				$pdf->Cell(5,5,utf8_decode(" A "),0,0,"L");
				$pdf->Cell(18,5,utf8_decode(date('d/m/Y', strtotime($final))),0,0,"L");
			}
		}else{
//			$func = strtoupper($_POST["func"]);
//			$pdf->Cell(124,5,utf8_decode("Técnico resp. pela avaliação: {$func} "),0,0,"L");
			$pdf->Cell(15,5,utf8_decode("Data da Avaliação: " . date('d/m/Y', strtotime($inicio))),0,0,"L");

		}
  		$pdf->Ln(10);

		if($campo == "num"){
			$query = "SELECT num_carro, obs, valor, data_exec FROM tb_serv_exec WHERE num_carro='{$num_carro}'";
		}else if($campo == "ped"){
			$query = "SELECT num_carro, obs, valor, data_exec FROM tb_serv_exec WHERE pedido='{$pedido}' ";
		}else{
			$query = "SELECT num_carro, obs, valor, data_exec FROM tb_serv_exec WHERE id_emp={$cliente}";
		}

		if($check){
			$query = $query . " AND data_exec BETWEEN '{$inicio}' AND '{$final}' order by data_exec";
		}		

		if (IsSet($_POST ["origem"])){

			if($campo == "num"){
				$query_opt = '';
				$arr = explode(',',$valor);
				if(count($arr) > 0){
					$query_opt = " AND (a.num_carro LIKE '%{$arr[0]}%'";

					for($i = 1; $i < count($arr); $i++){
						$query_opt = $query_opt . " OR a.num_carro LIKE '%{$arr[$i]}%'";
					}
					$query_opt = $query_opt . ')';
				}
				session_start();
				$query = $_SESSION["query"];

				$query = "SELECT num_carro, obs, valor, data_analise, func, local, exec FROM tb_analise_frota as a INNER JOIN tb_empresa as e ON a.id_emp = e.id  ON a.id_emp = e.id " . $query_opt;

				$pdf->SetFont('Arial','',4);				
				$pdf->Cell(124,5,utf8_decode($query),0,1,"L");

// SELECT a.id, e.fantasia, a.num_carro, a.data_analise, a.func, a.exec, a.obs, e.id, a.valor
// FROM tb_analise_frota as a
// INNER JOIN tb_empresa as e
// ON a.id_emp = e.id  AND (a.num_carro LIKE '%0000%') order by a.data_analise desc

			}else{
				if($campo == "cod"){
					$query = "SELECT num_carro, obs, valor, data_analise, func, local, exec FROM tb_analise_frota WHERE id='{$valor}' ";
//					$pdf->Cell(124,5,utf8_decode($query),0,1,"L");

				}else{
					$query = "SELECT num_carro, obs, valor, data_analise, func, local, exec FROM tb_analise_frota WHERE id_emp='{$cliente}' ";
				}
				if (IsSet($_POST ["exec"])){
					if($_POST ["exec"] == "2"){
						$query = $query . " AND  exec ";
					}else if($_POST ["exec"] == "3"){
						$query = $query . " AND  NOT exec ";
					}
				
				}
			}

			if($check){
				$query = $query . " AND data_analise BETWEEN '{$inicio}' AND '{$final}' order by data_analise";
			}

			
		}




		$result = mysqli_query($conexao, $query);

		$pdf->Line(10, 53, 200, 53);

		$pdf->SetFont('Arial','',10);

		$total = 0;

		if (IsSet($_POST ["origem"])){
			$pdf->SetFont('Arial','B',15);
			$pdf->Cell(190,5,utf8_decode(strtoupper($_POST ["titulo"])),0,0,"C");
			$pdf->Ln(15);
		}

		$cont = 0;
/*
		$pdf->Ln(5);
		$pdf->SetFont('Arial','',5);
		$pdf->Cell(20,5,$query,0,0,"L");

// SELECT num_carro, obs, valor, data_analise, func, local FROM tb_analise_frota WHERE id_emp={$cliente} AND data_analise BETWEEN '2022-01-07' AND '2022-01-10' order by data_analise
// SELECT num_carro, obs, valor, data_analise, func, local, exec FROM tb_analise_frota as a INNER JOIN tb_empresa as e ON a.id_emp = e.id  ON a.id_emp = e.id  AND (a.num_carro LIKE '%0000%')
*/

		while($fetch = mysqli_fetch_row($result)){
			$cont++;
			$carro = $fetch[0];
			$itens = explode("\n", $fetch[1]);
			$valor = money_format('%=*(#0.2n', $fetch[2]);
			$total = $total + $fetch[2];
			$data = $fetch[3];

			if ($pdf->GetY() > 265 ){				;
//				$pdf->SetY(250);
				include "pdf_cabrod.inc";
			}

			if (IsSet($_POST ["origem"])){
				$func = utf8_decode(strtoupper($fetch[4]));
				$local = utf8_decode(strtoupper($fetch[5]));
				$exec = $fetch[6];
			}


			$pdf->SetFont('Arial','B',10);
		  	$pdf->Cell(20,5,"Carro: ",0,0,"L");
			$pdf->SetTextColor(200,0,0);
			$pdf->Cell(15,5,utf8_decode(strtoupper($carro)),0,0,"L");
			if(!IsSet($_POST ["origem"])){
				$pdf->Cell(25,5," - ".date('d/m/Y', strtotime($fetch[3])),0,0,"L");
			}else{
				if($exec){
					$pdf->SetTextColor(25,200,100);
					$pdf->Cell(30,5,utf8_decode("-   SERVIÇO EXECUTADO ". $local) ,0,0,"L");
				}else{
					$pdf->Cell(30,5,utf8_decode("-   SERVIÇO A SER EXECUTADO ". $local) ,0,0,"L");
				}
			}
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

		  	$pdf->Cell(30,5,"Total: {$cont} carros, ",0,0,"L");
			$pdf->Cell(15,5,utf8_decode(money_format('%=*(#0.2n', $total)),0,0,"L");
			$pdf->Ln(10);
			$nObs =  explode("\n", $obs);
			for($i = 0; $i< count($nObs);$i++){
				$pdf->Cell(15,5,utf8_decode(strtoupper($nObs[$i])),0,0,"L");
				$pdf->Ln(5);
			}

//			$pdf->Cell(15,5,utf8_decode(strtoupper($obs)),0,0,"L");
			$pdf->SetTextColor(200,0,0);
			$pdf->Ln(10);
			if(!IsSet($_POST ["origem"])){			
				$pdf->Cell(30,5,"",0,0,"L");
				$pdf->Cell(15,5,utf8_decode("Aguardo aprovação e/ou num. do pedido para emissão de nota fiscal"),0,0,"L");
				$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','B',8);
				$pdf->Ln(10);

				$unid = 'config/fatura.txt';

				if(file_exists ($unid)){
					$fp = fopen($unid, "a+");
					while (!feof ($fp)) {
					  $linha = fgets($fp,4096);
					  $pdf->Cell(10,5,"",0,0,"L");
					  $pdf->Cell(15,5,utf8_decode($linha),0,0,"L");
					  $pdf->Ln(5);
	  				}
					fclose($fp);
				}				
/*
				$pdf->Cell(60,5,"",0,0,"L");
				$pdf->Cell(15,5,utf8_decode("******** CONDIÇÃO P/ FATURAMENTO ********"),0,0,"L");
				$pdf->Ln(5);
				$pdf->Cell(60,5,"",0,0,"L");
				$pdf->Cell(15,5,utf8_decode("                  até R$2.000,00 - 30 dias          "),0,0,"L");
				$pdf->Ln(5);
				$pdf->Cell(60,5,"",0,0,"L");
				$pdf->Cell(15,5,utf8_decode("         de R$2.000,00 a R$4.000,00 - 30/45    "),0,0,"L");
				$pdf->Ln(5);
				$pdf->Cell(60,5,"",0,0,"L");
				$pdf->Cell(15,5,utf8_decode("            acima de R$4.000,00 - 30/45/60      "),0,0,"L");
*/
			}else{
				$pdf->Cell(15,5,utf8_decode("*Lembrando que até a data da execução poderá haver acrescimos de serviços"),0,0,"L");
				$pdf->Ln(5);
				$pdf->Cell(15,5,utf8_decode("*Recomenda-se corrigir os problemas o mais rápido possivel."),0,0,"L");
			}
			$pdf->SetTextColor(0);
			$pdf->SetFont('Arial','B',10);



//Aguardo pedido  para emissão de nota fiscal

    }

	$pdf->Output();
    $conexao->close();

?>