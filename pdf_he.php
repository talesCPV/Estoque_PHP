<?php

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';


	$pdf = new FPDF('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";
    $i = 0;
    $achei = true;

    $inicio = $_POST ["inicio"];
    $final = $_POST ["final"];

    $pdf->SetFont('Arial','B',15);
    $pdf->Cell(200,5,date("d/m/Y",$inicio)." a ".date("d/m/Y",$final),0,0,"C");
    $pdf->Ln(10);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,5,'Cod.',0,0,"L");
    $pdf->Cell(50,5,"Nome",0,0,"L");
    $pdf->Cell(45,5,"Cargo.",0,0,"L");
    $pdf->Cell(15,5,"Sal.",0,0,"C");
    $pdf->Cell(15,5,"Evento",0,0,"C");
    $pdf->Cell(25,5,"Desc.",0,0,"L");
    $pdf->Cell(10,5,"Ref.Val.",0,0,"C");
    $pdf->Cell(20,5,"Tipo",0,0,"C");
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

            $pdf->Cell(10,5,$id_func,0,0,"C");
            $pdf->Cell(50,5,utf8_decode(substr($nome_comp,0,30)),0,0,"L");
            $pdf->Cell(45,5,utf8_decode(substr($cargo,0,25)),0,0,"L");
            $pdf->Cell(15,5,money_format('%=*(#0.2n', $salario),0,0,"C");
            $pdf->Cell(15,5,"0082",0,0,"C");
            $pdf->Cell(25,5,"HE 100%",0,0,"L");
            $pdf->Cell(10,5,number_format($he100, 2, '.', ''),0,0,"C");
            $pdf->Cell(20,5,$tipo,0,0,"C");
            $pdf->Ln(5);
            $pdf->Cell(10,5,$id_func,0,0,"C");
            $pdf->Cell(50,5,utf8_decode(substr($nome_comp,0,30)),0,0,"L");
            $pdf->Cell(45,5,utf8_decode(substr($cargo,0,25)),0,0,"L");
            $pdf->Cell(15,5,money_format('%=*(#0.2n', $salario),0,0,"C");
            $pdf->Cell(15,5,"0106",0,0,"C");
            $pdf->Cell(25,5,"HE 100%+AD 20%",0,0,"L");
            $pdf->Cell(10,5,number_format($headn, 2, '.', ''),0,0,"C");
            $pdf->Cell(20,5,$tipo,0,0,"C");
            $pdf->Ln(5);
            $pdf->Cell(10,5,$id_func,0,0,"C");
            $pdf->Cell(50,5,utf8_decode(substr($nome_comp,0,30)),0,0,"L");
            $pdf->Cell(45,5,utf8_decode(substr($cargo,0,25)),0,0,"L");
            $pdf->Cell(15,5,money_format('%=*(#0.2n', $salario),0,0,"C");
            $pdf->Cell(15,5,"1182",0,0,"C");
            $pdf->Cell(25,5,"AD 20%",0,0,"L");
            $pdf->Cell(10,5,number_format($adn, 2, '.', ''),0,0,"C");
            $pdf->Cell(20,5,$tipo,0,0,"C");
            $pdf->Ln(5);
            $pdf->Cell(10,5,$id_func,0,0,"C");
            $pdf->Cell(50,5,utf8_decode(substr($nome_comp,0,30)),0,0,"L");
            $pdf->Cell(45,5,utf8_decode(substr($cargo,0,25)),0,0,"L");
            $pdf->Cell(15,5,money_format('%=*(#0.2n', $salario),0,0,"C");
            $pdf->Cell(15,5,"0106",0,0,"C");
            $pdf->Cell(25,5,"Faltas",0,0,"L");
            $pdf->Cell(10,5,number_format($faltas, 2, '.', ''),0,0,"C");
            $pdf->Cell(20,5,$tipo,0,0,"C");
            $pdf->Ln(5);

            $i = $i+1;
        }else{
            $achei = false;
        }
    }
	$pdf->Output();
    $conexao->close();

?>