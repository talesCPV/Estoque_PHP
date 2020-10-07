<?php

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';


	$pdf = new FPDF('L','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

    $pdf->AddPage();
    
    function addTexto($pdf,$conexao,$look_day){

        $pdf->SetFont('Arial','',8);

        $query =  "SELECT * from tb_pcp WHERE data_serv = '{$look_day}'";

        $result = mysqli_query($conexao, $query);
        $fetch = mysqli_fetch_row($result);

        $frente = $fetch[2];
        $suporte = $fetch[3];
        $costura = $fetch[4];
        $montagem = $fetch[5];  

//      FRENTE        
        $a_frente=explode("\n", utf8_decode($frente));
        $pdf->SetY(45);
        for($i=0; $i < sizeof($a_frente); $i++){
            $pdf->Cell(13,5,"",0,0,"L");
            $pdf->Cell(30,5,$a_frente[$i],0,0,"L");
            $pdf->Ln(4);
        }
//      SUPORTE        
        $a_suporte=explode("\n", utf8_decode($suporte));
        $pdf->SetY(45);
        for($i=0; $i < sizeof($a_suporte); $i++){
            $pdf->Cell(80,5,"",0,0,"L");
            $pdf->Cell(30,5,$a_suporte[$i],0,0,"L");
            $pdf->Ln(4);
        }
//      COSTURA        
        $a_costura=explode("\n", utf8_decode($costura));
        $pdf->SetY(45);
        for($i=0; $i < sizeof($a_costura); $i++){
            $pdf->Cell(150,5,"",0,0,"L");
            $pdf->Cell(30,5,$a_costura[$i],0,0,"L");
            $pdf->Ln(4);
        }
//      MONTAGEM        
        $a_montagem=explode("\n", utf8_decode($montagem));
        $pdf->SetY(45);
        for($i=0; $i < sizeof($a_montagem); $i++){
            $pdf->Cell(215,5,"",0,0,"L");
            $pdf->Cell(30,5,$a_montagem[$i],0,0,"L");
            $pdf->Ln(4);
        }

        $pdf->SetFont('Arial','B',10);

    }

    if (IsSet($_POST ["hdnStart"])){

        $dto = new DateTime($_POST ["hdnStart"]);
        $end = $dto->format('d/m/Y');
        $dto->modify('-6 days');
        $start = $dto->format('d/m/Y');

        setlocale(LC_MONETARY,"pt_BR", "ptb");

        $pdf->SetFont('Arial','B',10);
        $pdf->Image("img/logo.png",10,15,-200);
        $pdf->Cell(190,10, "Av. Dr. Rosalvo de Almeida Telles, 2070 - Nova Cacapava",0,0,"C");
        $pdf->Ln(5);
        $pdf->Cell(190,10, "Cacapava-SP - CEP 12.283-020",0,0,"C");
        $pdf->SetFont('Arial','B',50);
        $pdf->Cell(45,10, "PCP",0,0,"R");
        $pdf->SetFont('Arial','B',10);
        $pdf->Ln(5);
        $pdf->Cell(190,10, "CNPJ 00.519.547/0001-06",0,0,"C");
        $pdf->Ln(5);
        $pdf->Cell(190,10, "comercial@flexibus.com.br | (12) 3653-2230",0,0,"C");
        $pdf->Cell(50,10, "de {$start} ate {$end}",0,0,"C");
        $pdf->Line(10, 35, 285, 35);
        $pdf->Ln(12);
        $pdf->Line(10, 180, 285, 180);

        $pdf->AliasNbPages('{totalPages}');
        $pdf->SetY(182);
        $pdf->Cell(290, 5, $pdf->PageNo()."/{totalPages}", 0, 0, 'R');
        $pdf->SetY(37);


		$pdf->SetFont('Arial','B',10);
	  	$pdf->Cell(10,5,"",0,0,"C");
	  	$pdf->Cell(70,5,"EQUIPE DE FRENTE",0,0,"C");
	  	$pdf->Cell(65,5,"EQUIPE SUPORTE",0,0,"C");
	  	$pdf->Cell(70,5,"COSTURA",0,0,"C");
        $pdf->Cell(70,5,"MONTAGEM",0,0,"C");
        $pdf->Ln(5);
//      HORIZONTAL        
        $pdf->Line(10, 45, 285, 45);  
        $pdf->Line(10, 72, 285, 72);  
        $pdf->Line(10, 99, 285, 99);  
        $pdf->Line(10, 126, 285, 126);  
        $pdf->Line(10, 153, 285, 153);  
//      VERTICAL        
        $pdf->Line(22, 45, 22, 180);  
        $pdf->Line(90, 45, 90, 180);  
        $pdf->Line(158, 45, 158, 180);  
        $pdf->Line(226, 45, 226, 180);  

        $pdf->SetY(57);
        $pdf->Cell(10,5,"SEG",0,0,"C");
        $pdf->SetY(83);
        $pdf->Cell(10,5,"TER",0,0,"C");
        $pdf->SetY(109);
        $pdf->Cell(10,5,"QUA",0,0,"C");
        $pdf->SetY(135);
        $pdf->Cell(10,5,"QUI",0,0,"C");
        $pdf->SetY(161);
        $pdf->Cell(10,5,"SEX",0,0,"C");
        
        
        $look_day = $dto->format('Y-m-d');
                                
        addTexto($pdf,$conexao,$look_day);




        $dto->modify('+1 days');


    }

	$pdf->Output();
    $conexao->close();

?>