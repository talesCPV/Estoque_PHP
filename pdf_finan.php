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

    function pizza($pdf,$yc,$r,$titulo,$valores){

        $tot = ($valores[0] + $valores[1] + $valores[2]);
        $pos = array(0,0,0);

        $perc = percent($valores);
        if($tot>0){
            $pos[0] = round(360*$perc[0]/100);
            $pos[1] = round(360*($perc[0]/100 + $perc[1]/100 ) );
            $pos[2] = 360;
        }


        $xc = 50;

        $pdf->SetFillColor(120,120,255);
        $pdf->Sector($xc,$yc+5,$r,0,$pos[0]);
        $pdf->SetFillColor(120,255,120);
        $pdf->Sector($xc,$yc+5,$r,$pos[0],$pos[1]);
        $pdf->SetFillColor(255,120,120);
        $pdf->Sector($xc,$yc+5,$r,$pos[1],$pos[2]);

        //  TÍTULO
        $pdf->SetXY(10,$yc-2 - $r);
        $pdf->SetFont('courier', '', 12);
        $pdf->Cell(0, 5, $titulo , 0, 1);
        $pdf->Ln(8);
    
        // LEGENDA
        $pdf->SetFillColor(120,120,255);
        $pdf->Rect($xc+$r+15, $yc - 5, 4, 4, 'DF');
        $pdf->SetXY($xc+$r+20, $yc - 5);
        $pdf->Cell(50, 5, 'Funilaria e Pintura' , 1, 0);
        $pdf->Cell(40, 5, money_format('%=*(#0.2n',$valores[0]) , 1, 0);
        $pdf->Cell(20, 5, number_format($perc[0] , 2, ',', '  ')  . '%' , 1, 0);
        $pdf->SetFillColor(120,255,120);
        $pdf->Rect($xc+$r+15, $yc, 4, 4, 'DF');
        $pdf->SetXY($xc+$r+20, $yc);
        $pdf->Cell(50, 5, 'Sanfonados' , 1, 0);
        $pdf->Cell(40, 5, money_format('%=*(#0.2n',$valores[1]) , 1, 0);
        $pdf->Cell(20, 5, number_format($perc[1], 2, ',', '  ')  . '%' , 1, 0);
        $pdf->SetFillColor(255,120,120);
        $pdf->Rect($xc+$r+15, $yc+5, 4, 4, 'DF');
        $pdf->SetXY($xc+$r+20, $yc+5);
        $pdf->Cell(50, 5, 'Outros' , 1, 0);
        $pdf->Cell(40, 5, money_format('%=*(#0.2n',$valores[2]) , 1, 0);
        $pdf->Cell(20, 5, number_format($perc[2], 2, ',', '  ')  . '%' , 1, 0);
        $pdf->Line(10, $yc+$r+10, 200, $yc+$r+10);
        $pdf->SetY($yc+$r+6);

    

    }

	header('Content-Type: text/html; charset=utf-8');

	include './fpdf/fpdf.php';
    include './fpdf/pdf-sector.php';

    $label = "Analise Financeira";

    if (IsSet($_POST ["pdf_campo"])){

        include "conecta_mysql.inc";
        if (!$conexao)
            die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

        $campo = $_POST ["pdf_campo"];
        $valor = $_POST ["pdf_valor"];
        $data_ini = $_POST ["pdf_dataini"];
        $data_fin = $_POST ["pdf_datafin"];
        $on = $_POST ["pdf_check"];

        if ($on){
            $label = $label . " de ". date('d/m/Y', strtotime($data_ini)) . " a " . date('d/m/Y', strtotime($data_fin));
        }

        if ($campo == "todos"){
            $query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem from tb_financeiro ";
            if($on){
                $query = $query . "where data_pg >= '$data_ini' and data_pg <= '$data_fin'";
            }
        }
        if ($campo == "fun_todos"){
            $label = $label . " - Fun. e Pint.";
            $query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem from tb_financeiro where origem = 'FUN' ";
            if($on){
                $query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'";
            }
        }
        else
        if ($campo == "san_todos"){
            $label = $label . " - Sanfonados";
            $query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem from tb_financeiro where origem = 'SAN' ";
            if($on){
                $query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'";
            }
        }
        else
        if ($campo == "entrada"){
            $query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem from tb_financeiro where tipo = 'ENTRADA' ";
            if($on){
                $query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'";
            }
        }
        else
        if ($campo == "fun_entrada"){
            $label = $label . " - Entrada Fun. e Pint.";
            $query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem from tb_financeiro where tipo = 'ENTRADA' and origem = 'FUN' ";
            if($on){
                $query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'";
            }
        }
        else
        if ($campo == "san_entrada"){
            $label = $label . " - Entrada Sanfonados";
            $query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem from tb_financeiro where tipo = 'ENTRADA' and origem = 'SAN' ";
            if($on){
                $query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'";
            }
        }
        else
        if ($campo == "saida"){
            $query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem from tb_financeiro where tipo = 'SAIDA' ";
            if($on){
                $query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'";
            }
        }
        else
        if ($campo == "fun_saida"){
            $label = $label . " - Saidas Fun. e Pint.";
            $query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem from tb_financeiro where tipo = 'SAIDA' and origem = 'FUN' ";
            if($on){
                $query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'";
            }
        }
        else
        if ($campo == "san_saida"){
            $label = $label . " - Saidas Sanfonados";
            $query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem from tb_financeiro where tipo = 'SAIDA' and origem = 'SAN' ";
            if($on){
                $query = $query . "and data_pg >= '$data_ini' and data_pg <= '$data_fin'";
            }
        }
        else
        if ($campo == "cli"){
            $query =  "SELECT id, ref, emp, data_pg, preco, tipo, origem from tb_financeiro where emp LIKE '%".$valor."%' and data_pg >= '$data_ini' and data_pg <= '$data_fin';";
        }
        $query = $query . 'ORDER BY data_pg';
        
        $result = mysqli_query($conexao, $query);

        $qtd_lin = $result->num_rows;

        $entradas = array(0, 0, 0);
        $saidas = array(0, 0, 0);

        while($fetch = mysqli_fetch_row($result)){
            $cod_lanc = $fetch[0];
            $orig = $fetch[6];
            $tipo = $fetch[5];
            if($tipo == 'ENTRADA'){
                if($orig == 'FUN'){
                    $entradas[0] = $entradas[0] + $fetch[4];
                }
                if($orig == 'SAN'){
                    $entradas[1] = $entradas[1] + $fetch[4];
                }
                if($orig == 'OUT'){
                    $entradas[2] = $entradas[2] + $fetch[4];
                }
            }else{
                if($orig == 'FUN'){
                    $saidas[0] = $saidas[0] + $fetch[4];
                }
                if($orig == 'SAN'){
                    $saidas[1] = $saidas[1] + $fetch[4];
                }
                if($orig == 'OUT'){
                    $saidas[2] = $saidas[2] + $fetch[4];
                }
            }
            
        }

        $liquido = array($entradas[0] - $saidas[0], $entradas[1] - $saidas[1], $entradas[2] - $saidas[2]);

//        $conexao->close();

    }


    $pdf = new PDF_SECTOR('P','mm',array(210,297));  // P = Portrait, em milimetros, e A4 (210x297)

	$pdf->AddPage();

    include "pdf_cabrod.inc";

	$pdf->SetFont('Arial','',15);
	$pdf->Ln(5);
 	$pdf->Cell(180,5,$label,0,0,"C");
	$pdf->Ln(15);


    pizza($pdf ,70,15,'1 - Entradas',$entradas);
    pizza($pdf ,120,15,'2 - Saidas',$saidas);
//    pizza($pdf ,170,15,'3 - Liquido',$liquido);

    $pdf->SetY(165);
    $pdf->Cell(50, 5, 'DESCRICAO' , 0, 0);
    $pdf->Cell(40, 5, 'ENTRADA' , 0, 0);
    $pdf->Cell(40, 5, 'SAIDA' , 0, 0);
    $pdf->Cell(40, 5, 'LIQUIDO' , 0, 0);
    $pdf->SetY(170);
    $pdf->Cell(50, 5, 'Funilaria e Pintura' , 1, 0);
    $pdf->Cell(40, 5, money_format('%=*(#0.2n',$entradas[0]) , 1, 0);
    $pdf->Cell(40, 5, money_format('%=*(#0.2n',$saidas[0]) , 1, 0);
    $pdf->Cell(40, 5, money_format('%=*(#0.2n',$liquido[0]) , 1, 0);
    $pdf->SetY(175);
    $pdf->Cell(50, 5, 'Sanfonados' , 1, 0);
    $pdf->Cell(40, 5, money_format('%=*(#0.2n',$entradas[1]) , 1, 0);
    $pdf->Cell(40, 5, money_format('%=*(#0.2n',$saidas[1]) , 1, 0);
    $pdf->Cell(40, 5, money_format('%=*(#0.2n',$liquido[1]) , 1, 0);
    $pdf->SetY(180);
    $pdf->Cell(50, 5, 'Outros' , 1, 0);
    $pdf->Cell(40, 5, money_format('%=*(#0.2n',$entradas[2]) , 1, 0);
    $pdf->Cell(40, 5, money_format('%=*(#0.2n',$saidas[2]) , 1, 0);
    $pdf->Cell(40, 5, money_format('%=*(#0.2n',$liquido[2]) , 1, 0);
    $pdf->SetY(185);
    $pdf->Cell(90, 5, '' , 0, 0);
    $pdf->Cell(40, 5,'Saldo', 1, 0);
    $pdf->Cell(40, 5, money_format('%=*(#0.2n',$liquido[0]+$liquido[1]+$liquido[2]) , 1, 0);

    $pdf->SetY(275);
    include "pdf_cabrod.inc";

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,5,"Orig.",0,0,"L");
    $pdf->Cell(20,5,"Tipo",0,0,"L");
    $pdf->Cell(55,5,"Referencia / NF",0,0,"L");
    $pdf->Cell(55,5,"Cedente / Sacado",0,0,"L");
    $pdf->Cell(20,5,"Venc.",0,0,"L");
    $pdf->Cell(40,5,"Valor..",0,0,"L");
    $pdf->Ln(5);

    $pdf->SetFont('Arial','',10);

    $result = mysqli_query($conexao, $query);

    while($fetch = mysqli_fetch_row($result)){

        if ($pdf->Gety() > 265){
            include "pdf_cabrod.inc";
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(10,5,"Orig.",0,0,"L");
            $pdf->Cell(20,5,"Tipo",0,0,"L");
            $pdf->Cell(55,5,"Referencia / NF",0,0,"L");
            $pdf->Cell(55,5,"Cedente / Sacado",0,0,"L");
            $pdf->Cell(20,5,"Venc.",0,0,"L");
            $pdf->Cell(40,5,"Valor..",0,0,"L");
            $pdf->Ln(5);
            $pdf->SetFont('Arial','',10);
        }        


        $pdf->Cell(10,5,$fetch[6],0,0,"L");
        $pdf->Cell(20,5,$fetch[5],0,0,"L");
        $pdf->Cell(55,5,strtoupper(substr(utf8_decode($fetch[1]),0,24)),0,0,"L");
        $pdf->Cell(55,5,strtoupper(substr(utf8_decode($fetch[2]),0,24)),0,0,"L"); // 
        $pdf->Cell(20,5,date('d/m/Y', strtotime($fetch[3])),0,0,"L");
        $pdf->Cell(40,5,money_format('%=*(#0.2n', $fetch[4]),0,1,"L");

    
    }
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(140,5,'',0,0,"L");
    $pdf->Cell(20,5,'SALDO',0,0,"L");
    $pdf->Cell(40, 5, money_format('%=*(#0.2n',$liquido[0]+$liquido[1]+$liquido[2]) , 0, 0);

    
    $conexao->close();

	$pdf->Output();

?>