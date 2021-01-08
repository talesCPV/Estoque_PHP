//JQUERY
$(document).ready(function(){

	var classe = $(this).getCookies('classe');

     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

	$('#btn_NovaSanf').click(function(){ 

        let today = new Date();
        let dd = String(today.getDate()).padStart(2, '0');
        let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        let yyyy = today.getFullYear();        
        today =  yyyy + '-' + mm + '-' + dd;        

        let query = "query=SELECT id, nome FROM tb_empresa WHERE tipo = 'CLI' ORDER BY nome;";
        let resp = queryDB(query);
        let option_emp = '';
        for(i=0;i<resp.length;i++){
            sel = '';
/*            
            if(emp.trim() == resp[i].nome.trim()){
                sel = 'selected';
            }
*/
            option_emp += "<option value='"+resp[i].id+"'"+sel+">"+resp[i].nome.trim()+"</option>";
        }


        query = "query=SELECT id, fabricante, modelo, ano FROM tb_sanfonas ;";
        resp = queryDB(query);
        let option_sanf = '';
        for(i=0;i<resp.length;i++){

            option_sanf += "<option value='"+resp[i].id+"'>"+resp[i].fabricante.trim()+" "+resp[i].modelo.trim()+" "+resp[i].ano.trim()+"</option>";
        }

        let table = "<form id='frmSanf' method='POST' action='#'><table>"+
        "<input type='hidden' name='hdnTipo' id='hdnTipo' value=''> <input type='hidden' name='edt_id' value='0'>"+        
        "<tr><td>ENTRADA.: *</td><td> <input type='date' name='edtEntrada' id='edtEntrada' value='"+today+"'> </td></tr>"+
        "<tr><td>CLIENTE: *</td><td><select name='edtCli' id= 'edtCli'>"+ option_emp +"</select></td></tr>"+
        "<tr><td>MODELO: *</td><td><select name='edtMod' id= 'edtMod'>"+ option_sanf +"</select></td></tr>"+
        "<tr><td>NUM: </td><td><input type='text' name='edtNum' value='0'></td></tr></tr>"+

        "<tr><td>TIPO: </td><td><select name='cmbTipo' >"+
        "   <option value='REFORMA' selected> REFORMA </option>"+
        "   <option value='NOVA'> NOVA </option>"+
        "</select></td></tr>"+

        "<tr><td>STATUS: </td><td><select name='cmbStatus' >"+
        "   <option value='RECEBIMENTO' selected> RECEBIMENTO </option>"+
        "   <option value='DESMONTAGEM'> DESMONTAGEM </option>"+
        "   <option value='LIMPEZA'> LIMPEZA </option>"+
        "   <option value='CORTE/COSTURA'> CORTE/COSTURA </option>"+
        "   <option value='MONTAGEM'> MONTAGEM </option>"+
        "   <option value='CALAFETAÇÃO'> CALAFETAÇÃO </option>"+
        "   <option value='ESTOQUE'> ESTOQUE </option>"+
        "   <option value='EXPEDIÇÃO'> EXPEDIÇÃO </option>"+
        "</select></td></tr>"+

        "<tr><td>PREVISÃO: </td><td><input type='date' name='edtSaida' id='edtSaida' value='"+today+"'></td></tr>"+
        "<tr><td>OBSERVAÇÃO: </td><td><textarea id='edtObs' name='edtObs' rows='4' cols='50'></textarea></td></tr>"+
        "</table></form>"; 

        var Btn = "<br>Acesso apenas p/ consulta<br>";

        if ($(this).perm(classe,'edit')){

            Btn = "<table><tr><td><button id='btnSalvar'>Salvar</button></td></tr></table>";

            $(document).off('click', '#btnSalvar').on('click', '#btnSalvar', function() {
                $('#hdnTipo').val('NOVO');
                $('#frmSanf').submit();
            });

        }

        $(".content").html(table+Btn);
        $('#popTitle').html("Manufatura de Sanfona");        
        $(".overlay").css("visibility", "visible").css("opacity", "1"); 

    }); 

    // DUPLO CLIQUE NA TEBELA tabItens
    $('#tabItens').on('dblclick','.tbl_row', function(){ // SELECIONANDO UM ÍTEM DA TABELA (DUPLO CLIQUE)

        let id = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
        let entrada = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
        let cliente = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
        let modelo = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
        let numero = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
        let tipo = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
        let status = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
        let saida = $.trim($(this).children('td').slice(7, 8).text().toUpperCase());
        let obs = $.trim($(this).children('td').slice(8, 9).text().toUpperCase());
        let e_id = $.trim($(this).children('td').slice(9, 10).text().toUpperCase());
        let m_id = $.trim($(this).children('td').slice(10, 11).text().toUpperCase());
        let dat_1 = $.trim($(this).children('td').slice(11, 12).text().toUpperCase());
        dat_1 = dat_1.substring(0,10);
        let dat_2 = $.trim($(this).children('td').slice(12, 13).text().toUpperCase());
        dat_2 = dat_2.substring(0,10);

        let opt_tipo = ["",""];
        let opt_status = ["","","","","","","",""];

        switch (tipo){
            case 'REFORMA':
                opt_tipo[0] = "selected"
            break;
            case 'NOVA':
                opt_tipo[1] = "selected"
            break;
        }

        switch (status){
            case 'RECEBIMENTO':
                opt_status[0] = "selected"
            break;
            case 'DESMONTAGEM':
                opt_status[1] = "selected"
            break;
            case 'LIMPEZA':
                opt_status[2] = "selected"
            break;
            case 'CORTE/COSTURA':
                opt_status[3] = "selected"
            break;
            case 'MONTAGEM':
                opt_status[4] = "selected"
            break;
            case 'CALAFETACAO':
                opt_status[5] = "selected"
            break;
            case 'ESTOQUE':
                opt_status[6] = "selected"
            break;
            case 'EXPEDICAO':
                opt_status[7] = "selected"
            break;
        }

        
        let query = "query=SELECT id, nome FROM tb_empresa WHERE tipo = 'CLI' ORDER BY nome;";
        let resp = queryDB(query);
        let option_emp = '';
        for(i=0;i<resp.length;i++){
            sel = '';
            
            if(e_id == resp[i].id.trim()){
                sel = 'selected';
            }

            option_emp += "<option value='"+resp[i].id+"'"+sel+">"+resp[i].nome.trim()+"</option>";
        }

        query = "query=SELECT id, fabricante, modelo, ano FROM tb_sanfonas ;";
        resp = queryDB(query);
        let option_sanf = '';
        for(i=0;i<resp.length;i++){

            sel = '';
            
            if(m_id == resp[i].id.trim()){
                sel = 'selected';
            }

            option_sanf += "<option value='"+resp[i].id+"'"+sel+">"+resp[i].fabricante.trim()+" "+resp[i].modelo.trim()+" "+resp[i].ano.trim()+"</option>";
        }


        let table = "<form id='frmSanf' method='POST' action='#'><table>"+
        "<input type='hidden' name='hdnTipo' id='hdnTipo' value=''> <input type='hidden' name='edt_id' value='"+id+"'>"+        
        "<tr><td>ENTRADA.: *</td><td> <input type='date' name='edtEntrada' id='edtEntrada' value='"+dat_1+"'> </td></tr>"+
        "<tr><td>CLIENTE: *</td><td><select name='edtCli' id= 'edtCli'>"+ option_emp +"</select></td></tr>"+
        "<tr><td>MODELO: *</td><td><select name='edtMod' id= 'edtMod'>"+ option_sanf +"</select></td></tr>"+
        "<tr><td>NUM: </td><td><input type='text' name='edtNum' value='"+numero+"'></td></tr></tr>"+

        "<tr><td>TIPO: </td><td><select name='cmbTipo' >"+
        "   <option value='REFORMA' "+opt_tipo[0]+"> REFORMA </option>"+
        "   <option value='NOVA' "+opt_tipo[1]+"> NOVA </option>"+
        "</select></td></tr>"+

        "<tr><td>STATUS: </td><td><select name='cmbStatus' >"+
        "   <option value='RECEBIMENTO' "+opt_status[0]+"> RECEBIMENTO </option>"+
        "   <option value='DESMONTAGEM' "+opt_status[1]+"> DESMONTAGEM </option>"+
        "   <option value='LIMPEZA' "+opt_status[2]+"> LIMPEZA </option>"+
        "   <option value='CORTE/COSTURA' "+opt_status[3]+"> CORTE/COSTURA </option>"+
        "   <option value='MONTAGEM' "+opt_status[4]+"> MONTAGEM </option>"+
        "   <option value='CALAFETACAO' "+opt_status[5]+"> CALAFETAÇÃO </option>"+
        "   <option value='ESTOQUE' "+opt_status[6]+"> ESTOQUE </option>"+
        "   <option value='EXPEDICAO' "+opt_status[7]+"> EXPEDIÇÃO </option>"+
        "</select></td></tr>"+

        "<tr><td>PREVISÃO: </td><td><input type='date' name='edtSaida' id='edtSaida' value='"+dat_2+"'></td></tr>"+
        "<tr><td>OBSERVAÇÃO: </td><td><textarea id='edtObs' name='edtObs' rows='4' cols='50'>"+obs+"</textarea></td></tr>"+
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