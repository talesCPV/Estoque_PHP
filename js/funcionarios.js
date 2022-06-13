
    alert(1)

    function queryDB(query) {        
        var resp = '';
        $.ajax({
            url: 'ajax/ajax.php',
            type: 'POST',
            dataType: 'html',
            data: query,
            async: false,
            success: function(data){
                resp = jQuery.parseJSON( data );
            }
        });   
        return resp;     
    }  

    $('#tabItens').on('dblclick','.tbl_row', function(){ // SELECIONANDO UM ÍTEM DA TABELA (DUPLO CLIQUE)

        var arr = window.location.href.split("/");
        var id = $(this).attr('id');

        alert(2)

        var id_func = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
        var nome = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
        var adm = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
        adm = adm.substr(6,4)+"-"+adm.substr(3,2)+"-"+adm.substr(0,2);
        var cargo = $.trim($(this).children('td').slice(3, 4).text());
        var status = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
        var rg = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
        var cpf = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
        var pis = $.trim($(this).children('td').slice(7, 8).text().toUpperCase());
        var end = $.trim($(this).children('td').slice(8, 9).text());
        var cid = $.trim($(this).children('td').slice(9, 10).text().toUpperCase());
        var est = $.trim($(this).children('td').slice(10, 11).text().toUpperCase());
        var cep = $.trim($(this).children('td').slice(11, 12).text().toUpperCase());
        var dem = $.trim($(this).children('td').slice(12, 13).text().toUpperCase());
        var id_cargo = $.trim($(this).children('td').slice(13, 14).text().toUpperCase());
        var tel = $.trim($(this).children('td').slice(14, 15).text());
        var cel = $.trim($(this).children('td').slice(15, 16).text().toUpperCase());
        var sal = $.trim($(this).children('td').slice(16, 17).text().toUpperCase());
        var tipo = $.trim($(this).children('td').slice(17, 18).text().toUpperCase());            

        var query = "query=SELECT id, cargo FROM tb_cargos;";
        var resp = queryDB(query);
        var opt = '';
        for(i=0;i<resp.length;i++){
            if(resp[i].id == id_cargo){
                opt += "<option selected value='"+resp[i].id+"'>"+resp[i].cargo+"</option>";
            }else{
                opt += "<option value='"+resp[i].id+"'>"+resp[i].cargo+"</option>";
            }
        }
        var opt2 = '';
        if(status == 'ATIVO'){
            opt2 += "<option selected value='ATIVO'>ATIVO</option><option value='DEMIT'>DEMITIDO</option>";
        }else{
            opt2 += "<option value='ATIVO'>ATIVO</option><option selected value='DEMIT'>DEMITIDO</option>";
        }


        var table = "<table class='tblPopUp'><tr><td>Nome *</td><td> <input type='text' name='edtNome' maxlength='30' id='edtNome' value='"+nome+"'/></td></tr>";
        table  +=   "<tr><td>RG </td><td> <input type='text' name='edtRG' id='edtRG' onkeyup='return money(this)' value='"+rg+"'/></td></tr>";
        table  +=   "<tr><td>CPF </td><td> <input type='text' name='edtCPF' id='edtCPF' onkeyup='return money(this)' value='"+cpf+"'/></td></tr>";
        table  +=   "<tr><td>PIS </td><td> <input type='text' name='edtPIS' id='edtPIS' onkeyup='return money(this)' value='"+pis+"'/></td></tr>";
        table  +=   "<tr><td>End. </td><td> <input type='text' name='edtEnd' id='edtEnd' maxlength='60' value='"+end+"'/></td></tr>";
        table  +=   "<tr><td>Cidade </td><td> <input type='text' name='edtCid' id='edtCid' maxlength='30' value='"+cid+"'/></td></tr>";
        table  +=   "<tr><td>Estado </td><td> <input type='text' name='edtEst' id='edtEst' maxlength='2' value='"+est+"'/></td></tr>";
        table  +=   "<tr><td>CEP </td><td> <input type='text' name='edtCEP' id='edtCEP' maxlength='10' value='"+cep+"'/></td></tr>";
        table  +=   "<tr><td>Telefone </td><td> <input type='text' name='edtTel' id='edtTel' onkeyup='return telefone(this)' maxlength='15' value='"+tel+"'/></td></tr>";
        table  +=   "<tr><td>Celular </td><td> <input type='text' name='edtCel' id='edtCel' onkeyup='return telefone(this)' maxlength='15' value='"+cel+"'/></td></tr>";
        table  +=   "<tr><td>Cargo</td><td><select name='selCargo' id= 'selCargo'>"+ opt +"</select></td></tr>";
        table  +=   "<tr><td>Admissão </td><td> <input type='date' name='cmbAdm' id= 'cmbAdm' class='selData' value='"+adm+"'></td></tr>";
        table  +=   "<tr><td>Status</td><td><select name='selStatus' id= 'selStatus'>"+ opt2 +"</select></td></tr>";
        table  +=   "<tr><td></td><td><button id='btn_Save'>Salvar</button><button id='btn_Del'>Deletar</button></td></tr></table>";
        table  +=   "<form id='frmRefresh' method='POST' action='#'></form>";

        $(document).off('click', '#btn_Save').on('click', '#btn_Save', function() {
            if(nome != ""){                              
                var nome = $('#edtNome').val().trim().toUpperCase();
                var adm = $('#cmbAdm').val();
                var status = $('#selStatus').val();
                var rg = $('#edtRG').val().trim().toUpperCase();
                var cpf = $('#edtCPF').val().trim().toUpperCase();
                var pis = $('#edtPIS').val().trim().toUpperCase();
                var end = $('#edtEnd').val().trim().toUpperCase();
                var cid = $('#edtCid').val().trim().toUpperCase();
                var est = $('#edtEst').val().trim().toUpperCase();
                var cep = $('#edtCEP').val().trim().toUpperCase();
        //                            var dem = $('#edtRG').val().trim().toUpperCase();
                var id_cargo = $('#selCargo').val();
                var tel = $('#edtTel').val().trim().toUpperCase();
                var cel = $('#edtCel').val().trim().toUpperCase();
        //                            var sal = $('#edtRG').val().trim().toUpperCase();
        //                            var tipo = $('#edtRG').val().trim().toUpperCase();   

                var query = "query=UPDATE tb_funcionario SET  nome='"+ nome +"', rg='"+ rg +"', cpf='"+ cpf+"', pis='"+ pis+"', endereco='"+ end+"', cidade='"+ cid+"', estado='"+ est+"', cep='"+ cep+"', data_adm='"+ adm+"', id_cargo='"+ id_cargo+"', tel='"+ tel+"', cel='"+ cel+"', status='"+ status+"' WHERE id="+id_func+";";
                queryDB(query);   
                $('#frmRefresh').submit();                               
            }else{
                alert('Todos os campos com * são obrigatórios');

            }
        });
        $(document).off('click', '#btn_Del').on('click', '#btn_Del', function() {
            if (confirm('Deseja deletar o funcionário '+nome+' do sistema?')) {                    
                var query = "query=DELETE FROM tb_funcionario WHERE id = '"+ id_func +"';";
                queryDB(query); 
                $('#frmRefresh').submit();  
            }
        });

        $(".content").html(table);
        $('#popTitle').html('Dados do Funcionário - '+id_func);   

})