<?php

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';


	$pdf = new FPDF('L','mm',array(210,297));  // P = Portrait, L = Landscape  em milimetros,  A4 (210x297)

    $pdf->AddPage();
    
    function print_line($pdf,$text,$space){
        $text = strtoupper("* ".$text);
        $width = 42;
        while(strlen($text) > $width){
            $line =  substr($text,0,$width);
            $text =  substr($text,$width);
            $pdf->Cell($space,5,"",0,0,"L");
            $pdf->Cell(30,5,$line,0,0,"L");
            $pdf->Ln(4);
        }
        $pdf->Cell($space,5,"",0,0,"L");
        $pdf->Cell(30,5,$text,0,0,"L");
        $pdf->Ln(4);

    }



    function addTexto($pdf,$conexao,$look_day,  $Y){

        $pdf->SetFont('Arial','',7);

        $query =  "SELECT * from tb_pcp WHERE data_serv = '{$look_day}'";

        $result = mysqli_query($conexao, $query);
        $fetch = mysqli_fetch_row($result);

        $frente = $fetch[2];
        $suporte = $fetch[3];
        $costura = $fetch[4];
        $montagem = $fetch[5];  

//      FRENTE        
        $a_frente=explode("\n", utf8_decode($frente));
        $pdf->SetY($Y);
        for($i=0; $i < sizeof($a_frente); $i++){
            print_line($pdf,$a_frente[$i],13);
        }

//      SUPORTE        
        $a_suporte=explode("\n", utf8_decode($suporte));
        $pdf->SetY($Y);
        for($i=0; $i < sizeof($a_suporte); $i++){
            print_line($pdf,$a_suporte[$i],80);
        }
//      COSTURA        
        $a_costura=explode("\n", utf8_decode($costura));
        $pdf->SetY($Y);
        for($i=0; $i < sizeof($a_costura); $i++){
            print_line($pdf,$a_costura[$i],150);
        }
//      MONTAGEM        
        $a_montagem=explode("\n", utf8_decode($montagem));
        $pdf->SetY($Y);
        for($i=0; $i < sizeof($a_montagem); $i++){
            print_line($pdf,$a_montagem[$i],216);
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
        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(50,10, "de {$start} ate {$end}",0,0,"C");
        $pdf->SetFont('Arial','B',10);
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
        $pdf->Line(10, 45, 10, 180);  
        $pdf->Line(22, 45, 22, 180);  
        $pdf->Line(90, 45, 90, 180);  
        $pdf->Line(158, 45, 158, 180);  
        $pdf->Line(226, 45, 226, 180);  

        $pdf->SetY(57);
        $pdf->Cell(12,5,"SEG",0,0,"C");
        $pdf->SetY(83);
        $pdf->Cell(12,5,"TER",0,0,"C");
        $pdf->SetY(111);
        $pdf->Cell(12,5,"QUA",0,0,"C");
        $pdf->SetY(137);
        $pdf->Cell(12,5,"QUI",0,0,"C");
        $pdf->SetY(163);
        $pdf->Cell(12,5,"SEX",0,0,"C");
        
        
        $look_day = $dto->format('Y-m-d');                                
        addTexto($pdf,$conexao,$look_day,45);
        $dto->modify('+1 days');
        $look_day = $dto->format('Y-m-d');                                
        addTexto($pdf,$conexao,$look_day,73);
        $dto->modify('+1 days');
        $look_day = $dto->format('Y-m-d');                                
        addTexto($pdf,$conexao,$look_day,100);
        $dto->modify('+1 days');
        $look_day = $dto->format('Y-m-d');                                
        addTexto($pdf,$conexao,$look_day,127);
        $dto->modify('+1 days');
        $look_day = $dto->format('Y-m-d');                                
        addTexto($pdf,$conexao,$look_day,154);




        $dto->modify('+1 days');


    }

	$pdf->Output();
    $conexao->close();

?>