//JQUERY
$(document).ready(function(){

	var classe = $(this).getCookies('classe');

     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

	$('#btn_NovaSanf').click(function(){ 

        let table = "<form id='frmSanf' method='POST' action='#'><table>"+
        "<input type='hidden' name='hdnTipo' id='hdnTipo' value=''> <input type='hidden' name='edt_id' value=''>"+        
        "<tr><td>Mod.: *</td><td><input type='text' name='edtModelo' value=''></td></tr>"+
        "<tr><td>Fab: *</td><td><input type='text' name='edtFab' value=''></td></tr>"+
        "<tr><td>Ano: *</td><td><input type='text' name='edtAno' value=''></td></tr>"+
        "<tr><td>Barras.: </td><td><input type='text' name='edtBarras' value='0'></td></tr></tr>"+
        "<tr><td>Dob. Teto: </td><td><input type='text' name='edtDobTeto' value='0'></td></tr>"+
        "<tr><td>Dob. Chão: </td><td><input type='text' name='edtDobChao' value='0'></td></tr>"+

        "<tr><td>Tipo de Sanfoninha: </td><td><select name='cmbSanf' >"+
        "   <option value='SANF. INTERNA' selected> Proteção Sanfonada </option>"+
        "   <option value='BALC. MADEIRA'> Balcão de Madeira </option>"+
        "   <option value='SANF. BAIXA'> Proteção Sanfonada Baixa</option>"+
        "   <option value='NENHUM'> Nenhuma </option>"+
        "</select></td></tr>"+

        "<tr><td>Largura do Chão: </td><td><input type='text' name='edtLchao' value='0'></td></tr>"+
        "<tr><td>Comprimento do Chão (Velcro): </td><td><input type='text' name='edtCchao' value='0'></td></tr>"+
        "<tr><td>bainhas: </td><td><input type='text' name='edtBainha' value='0'></td></tr>"+
        "<tr><td>Altura da Sanfoninha: </td><td><input type='text' name='edtAltSanf' value='0'></td></tr>"+
        "<tr><td>Alt. Proteção de Teto: </td><td><input type='text' name='edtAltTeto' value='0'></td></tr>"+
        "</table></form>"; 

        var Btn = "<br>Acesso apenas p/ consulta<br>";

        if ($(this).perm(classe,'edit')){

            Btn = "<table><tr><td><button id='btnSalvar'>Salvar</button><button id='btnDeletar'>Deletar</button></td></tr></table>";

            $(document).off('click', '#btnSalvar').on('click', '#btnSalvar', function() {
                $('#hdnTipo').val('NOVO');
                $('#frmSanf').submit();
            });

            $(document).off('click', '#btnDeletar').on('click', '#btnDeletar', function() {
                if (confirm('Deseja remover o ítem definitivamente do sistema?')) {
                    $('#hdnTipo').val('DELETAR');
                    $('#frmSanf').submit();
                }
            });
        }

        $(".content").html(table+Btn);
        $('#popTitle').html("Modelos de Sanfonas");        
        $(".overlay").css("visibility", "visible").css("opacity", "1"); 

    }); 

    // DUPLO CLIQUE NA TEBELA tabItens
    $('#tabItens').on('dblclick','.tbl_row', function(){ // SELECIONANDO UM ÍTEM DA TABELA (DUPLO CLIQUE)

        let id = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
        let modelo = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
        let fabricante = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
        let ano = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
        let barras = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
        let dob_teto = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
        let dob_chao = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
        let sanf = $.trim($(this).children('td').slice(7, 8).text().toUpperCase());
        let l_chao = $.trim($(this).children('td').slice(8, 9).text().toUpperCase());
        let c_chao = $.trim($(this).children('td').slice(9, 10).text().toUpperCase());
        let bainhas = $.trim($(this).children('td').slice(10, 11).text().toUpperCase());
        let alt_sanf = $.trim($(this).children('td').slice(11, 12).text().toUpperCase());        
        let alt_teto = $.trim($(this).children('td').slice(12, 13).text().toUpperCase());        
        
        let sel_op = ["","","",""];

        if(sanf == "BALC. MADEIRA"){
            sel_op[1] = "selected";
        }else if(sanf == "SANF. BAIXA"){
            sel_op[2] = "selected";
        }else if(sanf == "NENHUM"){
            sel_op[3] = "selected";
        }else{
            sel_op[0] = "selected";
        }

        let table = "<form id='frmSanf' method='POST' action='#'><table>"+
        "<input type='hidden' name='hdnTipo' id='hdnTipo' value=''> <input type='hidden' name='edt_id' value='"+id+"'>"+        
        "<tr><td>Mod.:</td><td><input type='text' name='edtModelo' value='"+modelo+"'></td></tr>"+
        "<tr><td>Fab: </td><td><input type='text' name='edtFab' value='"+fabricante+"'></td></tr>"+
        "<tr><td>ano: </td><td><input type='text' name='edtAno' value='"+ano+"'></td></tr>"+
        "<tr><td>Barras.: </td><td><input type='text' name='edtBarras' value='"+barras+"'></td></tr></tr>"+
        "<tr><td>Dob. Teto: </td><td><input type='text' name='edtDobTeto' value='"+dob_teto+"'></td></tr>"+
        "<tr><td>Dob. Chão: </td><td><input type='text' name='edtDobChao' value='"+dob_chao+"'></td></tr>"+

        "<tr><td>Tipo de Sanfoninha: </td><td><select name='cmbSanf' >"+
        "   <option value='SANF. INTERNA'"+ sel_op[0] +"> Proteção Sanfonada </option>"+
        "   <option value='BALC. MADEIRA'"+ sel_op[1] +"> Balcão de Madeira </option>"+
        "   <option value='SANF. BAIXA'"+ sel_op[2] +"> Proteção Sanfonada Baixa</option>"+
        "   <option value='NENHUM'"+ sel_op[3] +"> Nenhuma </option>"+
        "</select></td></tr>"+


        "<tr><td>Largura do Chão: </td><td><input type='text' name='edtLchao' value='"+l_chao+"'></td></tr>"+
        "<tr><td>Comprimento do Chão (Velcro): </td><td><input type='text' name='edtCchao' value='"+c_chao+"'></td></tr>"+
        "<tr><td>bainhas: </td><td><input type='text' name='edtBainha' value='"+bainhas+"'></td></tr>"+
        "<tr><td>Altura da Sanfoninha: </td><td><input type='text' name='edtAltSanf' value='"+alt_sanf+"'></td></tr>"+
        "<tr><td>Alt. Proteção de Teto: </td><td><input type='text' name='edtAltTeto' value='"+alt_teto+"'></td></tr>"+
        "</table></form>";   
                
        var Btn = "<br>Acesso apenas p/ consulta<br>";

        if ($(this).perm(classe,'edit')){

            Btn = "<table><tr><td><button id='btnSalvar'>Salvar</button><button id='btnDeletar'>Deletar</button></td></tr></table>";

            $(document).off('click', '#btnSalvar').on('click', '#btnSalvar', function() {
                $('#hdnTipo').val('EDITAR');
                $('#frmSanf').submit();
            });

            $(document).off('click', '#btnDeletar').on('click', '#btnDeletar', function() {
                if (confirm('Deseja remover o ítem definitivamente do sistema?')) {
                    $('#hdnTipo').val('DELETAR');
                    $('#frmSanf').submit();
                }
            });
        }

        $(".content").html(table+Btn);
        $('#popTitle').html("Modelos de Sanfonas");        
        $(".overlay").css("visibility", "visible").css("opacity", "1"); 
    
    });

});