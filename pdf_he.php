<?php

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';


	$pdf = new FPDF('L','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    //    include "pdf_cabrod.inc";

    $i = 0;
    $achei = true;

    $inicio = $_POST ["inicio"];
    $final = $_POST ["final"];
    $dias = (($final - $inicio) / 3600 / 24) + 1;

    setlocale(LC_MONETARY,"pt_BR", "ptb");

    $pdf->SetFont('Arial','B',10);
    $pdf->Image("img/logo.png",10,15,-200);
    $pdf->Cell(190,10, "Av. Dr. Rosalvo de Almeida Telles, 2070 - Nova Cacapava",0,0,"C");
    $pdf->Ln(5);
    $pdf->Cell(190,10, "Cacapava-SP - CEP 12.283-020",0,0,"C");
    $pdf->SetFont('Arial','B',20);
    $pdf->Cell(50,10, utf8_decode("Relógio de Ponto") ,0,0,"C");
    $pdf->SetFont('Arial','B',10);
    $pdf->Ln(5);
    $pdf->Cell(190,10, "CNPJ 00.519.547/0001-06",0,0,"C");
    $pdf->Ln(5);
    $pdf->Cell(190,10, "comercial@flexibus.com.br | (12) 3653-2230",0,0,"C");
    $pdf->SetFont('Arial','B',15);
    $pdf->Cell(50,5,"de ".date("d/m/Y",$inicio)." a ".date("d/m/Y",$final),0,0,"C");
    $pdf->SetFont('Arial','B',10);
    $pdf->Line(10, 35, 285, 35);
    $pdf->Ln(12);
    $pdf->Line(10, 180, 285, 180);

    $pdf->AliasNbPages('{totalPages}');
    $pdf->SetY(182);
    $pdf->Cell(290, 5, $pdf->PageNo()."/{totalPages}", 0, 0, 'R');
    $pdf->SetY(37);


    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,5,"Cod.",0,0,"L");
    $pdf->Cell(50,5,"Nome",0,0,"L");
    $pdf->Cell(50,5,"Cargo.",0,0,"L");
    $pdf->Cell(25,5,"Sal.",0,0,"L");
    $pdf->Cell(20,5,"Horas",0,0,"L");
    $pdf->Cell(20,5,"Faltas",0,0,"L");
    $pdf->Cell(20,5,"Ad.Not.",0,0,"L");
    $pdf->Cell(20,5,"HE 50%",0,0,"L");
    $pdf->Cell(20,5,"HE 100%",0,0,"L");
    $pdf->Cell(20,5,"HE + Ad.",0,0,"L");
    $pdf->Cell(20,5,"Total",0,0,"L");
    $pdf->Ln(5);


    while($achei){
        $pdf->SetFont('Arial','',8);

        if (IsSet($_POST ["nome_{$i}"])){
            $nome = $_POST ["nome_{$i}"];
            $horas = $_POST ["horas_{$i}"];
            $faltas = $_POST ["faltas_{$i}"];
            $adn = $_POST ["adn_{$i}"];
            $he50 = $_POST ["he50_{$i}"];
            $he100 = $_POST ["he100_{$i}"];
            $headn = $_POST ["headn_{$i}"];

            $id_func =  0;
            $nome_comp =  '';
            $cargo =  '';
            $salario =  0.00;
            $tipo =  '';

            if (!$conexao)
                die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

            $query =  "SELECT f.id, f.nome, c.cargo, c.salario, c.tipo FROM tb_funcionario as f INNER JOIN tb_cargos as c  ON f.nome like '{$nome}%' AND f.id_cargo = c.id";
            $result = mysqli_query($conexao, $query);

            while($fetch = mysqli_fetch_row($result)){
                $id_func =  $fetch[0];
                $nome_comp =  $fetch[1];
                $cargo =  $fetch[2];
                $salario =  $fetch[3];
                $tipo =  $fetch[4];
            }


            if($tipo == "HORA"){
                $total =  ($horas *  $salario) + ($adn * $salario * 1.2) + ($he50 * $salario * 1.5) + ($he100 * $salario * 2) + ($headn * $salario * 2.2);
            }else{
                $total =    $salario * $dias / 30 ;
//                $total =    $salario * ($horas + $he50 * 1.5 + $he100 * 2 + $headn * 2.2 + $adn * 1.2) / 240 ;
            }



            $pdf->Cell(10,5,$id_func,0,0,"L");
            $pdf->Cell(50,5,utf8_decode(substr($nome_comp,0,30)),0,0,"L");
            $pdf->Cell(50,5,utf8_decode(substr($cargo,0,25)),0,0,"L");
            $pdf->Cell(25,5,money_format('%=*(#0.2n', $salario),0,0,"L");
            $pdf->Cell(20,5,number_format($horas, 2, '.', ''),0,0,"L");
            $pdf->Cell(20,5,number_format($faltas, 2, '.', ''),0,0,"L");
            $pdf->Cell(20,5,number_format($adn, 2, '.', ''),0,0,"L");
            $pdf->Cell(20,5,number_format($he50, 2, '.', ''),0,0,"L");
            $pdf->Cell(20,5,number_format($he100, 2, '.', ''),0,0,"L");
            $pdf->Cell(20,5,number_format($headn, 2, '.', ''),0,0,"L");
            $pdf->Cell(20,5,money_format('%=*(#0.2n', $total),0,0,"L");
            $pdf->Ln(5);

            $i = $i+1;
        }else{
            $achei = false;
        }
    }
	$pdf->Output();
    $conexao->close();

?>