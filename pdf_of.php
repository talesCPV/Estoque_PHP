<?php
	
	header('Content-Type: text/html; charset=utf-8');

	include './fpdf/fpdf.php';
	include 'conecta_mysql.inc';
	include 'funcoes.inc';

	$pdf = new FPDF('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";

	if (!$conexao)
	  die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

    if (IsSet($_POST ["cod_serv"])){      
        $cod_serv = $_POST ["cod_serv"];

        $query =  "SELECT * FROM tb_servico WHERE id = {$cod_serv};";

        $result = mysqli_query($conexao, $query);

        while($fetch = mysqli_fetch_row($result)){
            $id = $fetch[0];
            $num_serv = strtoupper($fetch[1]);
            $resp = strtoupper($fetch[2]);
            $func = strtoupper($fetch[3]);
            if($fetch[4] == 'OF'){
               $tipo = utf8_decode("ORDEM DE FABRICAÇÂO");
            }else{
                $tipo = utf8_decode("ORDEM DE SERVIÇO");
            }
            $data = date('d/m/Y', strtotime($fetch[5]));
            $status = strtoupper($fetch[6]); 

        }

        $pdf->SetFont('Arial','B',15);
        $pdf->Ln(5);            
        $pdf->Cell(200,5,$tipo." - ". $num_serv,0,1,"C");
        $pdf->Ln(5);

        $pdf->SetFont('Arial','',10);
        $pdf->Cell(150,5,utf8_decode("Emitido por: ".$resp),0,0,"L");
        $pdf->Cell(35,5,"Data: ". $data ,0,0,"L");
        $pdf->Ln(5);
        $pdf->Cell(150,5,utf8_decode("Funcionario Responsável: ".$func),0,0,"L");
        $pdf->Cell(35,5,"Status: ". $status ,0,0,"L");
        $pdf->Ln(5);
        $pdf->Line(10, 65, 200, 65);

        $pdf->SetFont('Arial','B',12);
        $pdf->Ln(10);            
        $pdf->Cell(200,5,utf8_decode("FABRICAR CONFORME PROCESSO"),0,1,"C");
        $pdf->Ln(5);    

        $query =  "SELECT p.cod, p.descricao, s.qtd, p.unidade FROM tb_item_serv AS s INNER JOIN tb_produto AS p ON p.id = s.id_item AND s.id_serv = {$cod_serv};";

        $result = mysqli_query($conexao, $query);

        $pdf->Cell(20,5,utf8_decode("Cod."),0,0,"L");
        $pdf->Cell(100,5,utf8_decode('Descrição') ,0,0,"L");
        $pdf->Cell(15,5,utf8_decode('Qtd.'),0,0,"L");
        $pdf->Cell(30,5,utf8_decode('Und.') ,0,0,"L");
        $pdf->Ln(5);
        $pdf->SetFont('Arial','',12);
        while($fetch = mysqli_fetch_row($result)){
            $pdf->Cell(20,5,utf8_decode($fetch[0]),0,0,"L");
            $pdf->Cell(100,5,utf8_decode(strtoupper($fetch[1])) ,0,0,"L");
            $pdf->Cell(15,5,utf8_decode($fetch[2]),0,0,"L");
            $pdf->Cell(30,5,utf8_decode(strtoupper($fetch[3])) ,0,0,"L");
            $pdf->Ln(5);
        }

        $pdf->Ln(35);
        $pdf->Cell(200,5,utf8_decode("Ass. do Resposável: __________________________________"),0,1,"C");
        $pdf->Cell(200,5,utf8_decode("                     ".$func),0,0,"C");

    }

    $pdf->Output();
    $conexao->close();



?>