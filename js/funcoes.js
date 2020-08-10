

/* SCRIPTS DE VALIDAÇÃO DE DADOS - SISTEMA FLEXIBUS*/      


    function readCookie(value) {
        var resp = "";
        var cookies = document.cookie.split(';');

        cookies.forEach(element => {
                item = element.split('=');
                if($.trim(item[0]) == value){
                    resp = item[1];
                }
            });
            
        return resp;
    }

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

    function validaCampo(obj)
    {
        for(var i = 0; i<obj.length; i++){
    
            if(obj[i].value=="")
              {
                  alert("Os campos com * são obrigatórios!");
                  return false;
              }
        }
        return false;
    }

     function estq_baixo(etq_max) {
        if (add_item.qtd.value > etq_max) {
            alert("Estoque Insuficiente !!");
            return false;
        }else{
             return true;
        }
    }


    function valida_senha(obj)
    {
        if(obj[1].value != obj[2].value)
          {
              alert("O campo \"Repita a senha\" não confere!");
              return false;
          }

        if (validaCampo(obj)){
            return true;
        }else{
            return false;
        }
    }

    function ClearMoney(campo){
        var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
        var text = campo;
        var out_text = '';

        for(var i = 0; i<text.length; i++){

            if(ok_chr.includes(text.charAt(i))){
                out_text = out_text + text.charAt(i)
            }
        }
        return out_text.substr(0,out_text.length-2)+"."+out_text.substr(out_text.length-2,out_text.length) ;
    }
 
    function money(campo){
        var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
        var text = campo.value;
        var after_dot = 0;
        var out_text = '';
        for(var i = 0; i<text.length; i++){

            if(after_dot > 0){ // conta quantas casas depois da virgula
                after_dot = after_dot + 1;
            }

            if (after_dot < 4 ){ // se não passou de 2 casas depois da virgula ( conta o ponto + 2 digitos)

                if(ok_chr.includes(text.charAt(i))){
                    out_text = out_text + text.charAt(i)

                }
                if((text.charAt(i) == ',' || text.charAt(i) == '.') && after_dot == 0){
                    out_text = out_text + '.';
                    after_dot = after_dot + 1;
                }
            }


        }
        campo.value = out_text;
    }

    function hora(campo){
        var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
        var text = campo.value;
        var count = 0;
        var out_text = '';
        var last_num = 0;
        for(var i = 0; i<text.length; i++){
            if(ok_chr.includes(text.charAt(i))){

                if(count == 0 && text.charAt(i) < 3 ){ out_text += text.charAt(i); }
                if(count == 1 && ((last_num == 2 && text.charAt(i) < 4)|| last_num < 2 )){out_text += text.charAt(i);}
                if(count == 2 && text.charAt(i) < 6 ){ out_text += ':'+text.charAt(i);}
                if(count == 3 ){ out_text += text.charAt(i);}
                               
                count += 1;
                last_num = text.charAt(i);
            }

        }
        campo.value = out_text;
    }

    function number(campo){
        var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
        var text = campo.value;
        var after_dot = 0;
        var out_text = '';
        for(var i = 0; i<text.length; i++){

            if(after_dot > 0){ // conta quantas casas depois da virgula
                after_dot = after_dot + 1;
            }

            if (after_dot < 4 ){ // se não passou de 2 casas depois da virgula ( conta o ponto + 2 digitos)

                if(ok_chr.includes(text.charAt(i))){
                    out_text = out_text + text.charAt(i)

                }
                if((text.charAt(i) == ',' || text.charAt(i) == '.') && after_dot == 0){
                    out_text = out_text + '.';
                    after_dot = after_dot + 1;
                }
            }


        }
        campo.value = out_text;
    }    

    function telefone(param){ // formata a string no padrão TELEFONE
        number(param);
        var num = param.value;
        var out = '';
        var count = 0;
    
        for(i=0;i<num.length;i++){
            chr = num.substring(i,i+1);
            count++;
    
            if(count == 1){
                out = '(' + out ;
            }
            if(count == 3){
                out = out + ')';
            }
            if(count == 7){
                out = out + '-';
            }
            if(count == 11){
                out = out.substr(0,5) +" "+out.substr(5,3)+out.substr(9,1)+"-"+out.substr(10,3);
            }		
            out = out + chr;			
        }
    
        param.value = out;
    }
      

//JQUERY
$(document).ready(function(){

    $body = $("body");

    $(document).on({
         ajaxStart: function() { $body.addClass("loading");   },
         ajaxStop: function() { $body.removeClass("loading"); }    
    }); 

	var classe = $(this).getCookies('classe');
    var usuario = $(this).getCookies('usuario');

//  VERIFICA SE O USUARIO TEM PERMISSÃO PARA ACESSAR A PÁGINA DE ACORDO COM O ARQUIVO /config/menu.json na sessão "seguranca"
    if (!$(this).perm(classe,'open')){
//        $.cookie('message','Você nao tem permissao para acessar esta pagina');
        $(window.document.location).attr('href',$(this).urlPath(window.location.href) + 'main.php');
    }


	$('#selTipo').change(function(){ // COMBOBOX DO CAMPO DE PESQUISA
        if($(this).val() == 'ENTRADA'){
            $('#lblDataVenc').html("Data Recebimento");
            $('#lblDest').html("Sacado");
        }else{
            $('#lblDataVenc').html("Data Vencimento");
            $('#lblDest').html("Cedente");
        }        
    }); 

    $('.checkFone').keyup(function(){
        var val= $(this).val();
        $(this).val( $(this).telefone(val));
    });

	$('.selData').change(function(){ // SELECIONA A DATA
        $('#ckbDatas').prop( "checked", true );
    });      

    $('#btnSalvar').click(function(){ 
        if ($(this).obrigatorio(['edtRef','edtDest','edtValor'])){
            if($(this).checkFinan($('#edtRef').val())){
                alert('Já existe um registro com esta referência');
            }else{
                $("#frmSaveFinan").submit();
            }
        }
    });     

    $('#btnSaveAgenda').click(function(){ 
        if ($(this).obrigatorio(['edtNome'])){
            $("#frmSaveAgenda").submit();
        }
    });     

    $("#frmSaveTinta").submit(function(event){
        if ($(this).obrigatorio(['edtDesc','edtPreco'])){
            $("#frmSaveTinta").submit();
        }else{
            event.preventDefault();
        }
     });

    $("#frmSaveProd").submit(function(event){
        if ($(this).obrigatorio(['edtDesc'])){
            $("#frmSaveProd").submit();
        }else{
            event.preventDefault();
        }
    });

    $("#frmSaveEmp").submit(function(event){
        if ($(this).obrigatorio(['edtNome'])){
            $("#frmSaveEmp").submit();
        }else{
            event.preventDefault();
        }
    });

    $("#btnSaveProfile").click(function(event){
        if ($(this).senha(['edtPass1','edtPass2'])){
            $("#frmProfile").submit();
        }
    });

    $("#btn_NovoCargo").click(function(event){

        var table = "<table><tr><td>Cargo *</td><td> <input type='text' name='cargo' maxlength='40' id='edtCargo'/></td></tr>";
        table +=   "<tr><td>Salário * R$</td><td> <input type='text' name='edtSal' id='edtSal' onkeyup='return money(this)' /></td></tr>";
        table +=   "<tr><td>Tipo</td><td><select name='tipo' id= 'selTipo'> <option value='HORA'>HORISTA</option><option value='MENSAL'>MENSALISTA</option></select></td></tr>";
        table +=   "<tr><td></td><td><button id='btn_Save'>Salvar</button></td></tr></table>";
        table +=   "<form id='frmRefresh' method='POST' action='#'></form>";

        $(document).off('click', '#btn_Save').on('click', '#btn_Save', function() {
            var cargo = $('#edtCargo').val().trim().toUpperCase();
            if(cargo != "" && $('#edtSal').val().trim() != ""){
                var sal = parseFloat($('#edtSal').val());
                var tipo = $('#selTipo').val();
                var query = "query=INSERT INTO tb_cargos VALUES (DEFAULT, '"+ cargo +"', "+ sal +", '"+ tipo+"');";
                queryDB(query);   
                $('#frmRefresh').submit();                               
            }else{
                alert('Todos os campos com * são obrigatórios');

            }
        });

        $(".content").html(table);
        $('#popTitle').html('Cadastro de Cargos e Funções');        
        $(".overlay").css("visibility", "visible").css("opacity", "1");  
    });

    $("#btn_NovoFeriado").click(function(event){
        var today = new Date();
        var d = String(today.getDate()).padStart(2, '0');
        var m = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var a = today.getFullYear();
        today = a+'-'+m+'-'+d;

        var table = "<table><tr><td>Descrição *</td><td> <input type='text' maxlength='40' id='edtDesc'/></td></tr>";
        table +=   "<tr><td>Data </td><td> <input type='date' id='cmbData' class='selData' value='"+ today +"'></td></tr>";
        table +=   "<tr><td> </td><td> <input type='checkbox' id='ckbAnual' name='ckbAnual' value='1' checked><label for='ckbAnual'> Recorrência Anual</label></td></tr>";
        table +=   "<tr><td></td><td><button id='btn_Save'>Salvar</button></td></tr></table>";
        table +=   "<form id='frmRefresh' method='POST' action='#'></form>";

        $(document).off('click', '#btn_Save').on('click', '#btn_Save', function() {
            var desc = $('#edtDesc').val().trim().toUpperCase();
            if(desc != "" ){
                var data = $('#cmbData').val();
                d = data.substr(8,2);
                m = data.substr(5,2);
                if ($('#ckbAnual').is(":checked")){
                    a='DEFAULT';
                }else{
                    a = data.substr(0,4);
                }           

                var query = "query=INSERT INTO tb_feriados VALUES (DEFAULT, "+ d +", "+ m +", "+ a+", '"+ desc+"');";
                queryDB(query);   
                $('#frmRefresh').submit();                               
            }else{
                alert('Todos os campos com * são obrigatórios');

            }
        });

        $(".content").html(table);
        $('#popTitle').html('Cadastro de Feriados');        
        $(".overlay").css("visibility", "visible").css("opacity", "1");  
    });

    $("#btn_NovoFunc").click(function(event){
        event.preventDefault(); // cancela o submit do form;
        var today = new Date();
        var d = String(today.getDate()).padStart(2, '0');
        var m = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var a = today.getFullYear();
        today = a+'-'+m+'-'+d;

        var query = "query=SELECT id, cargo FROM tb_cargos;";
        var resp = queryDB(query);
        var option = '';
        for(i=0;i<resp.length;i++){
            option += "<option value='"+resp[i].id+"'>"+resp[i].cargo+"</option>";
        }

        var table = "<table class='tblPopUp'><tr><td>Nome *</td><td> <input type='text' name='edtNome' maxlength='30' id='edtNome'/></td></tr>";
        table +=   "<tr><td>RG </td><td> <input type='text' name='edtRG' id='edtRG' onkeyup='return money(this)' /></td></tr>";
        table +=   "<tr><td>CPF </td><td> <input type='text' name='edtCPF' id='edtCPF' onkeyup='return money(this)' /></td></tr>";
        table +=   "<tr><td>PIS </td><td> <input type='text' name='edtPIS' id='edtPIS' onkeyup='return money(this)' /></td></tr>";
        table +=   "<tr><td>End. </td><td> <input type='text' name='edtEnd' id='edtEnd' maxlength='60'/></td></tr>";
        table +=   "<tr><td>Cidade </td><td> <input type='text' name='edtCid' id='edtCid' maxlength='30'/></td></tr>";
        table +=   "<tr><td>Estado </td><td> <input type='text' name='edtEst' id='edtEst' maxlength='2'/></td></tr>";
        table +=   "<tr><td>CEP </td><td> <input type='text' name='edtCEP' id='edtCEP' maxlength='10'/></td></tr>";
        table +=   "<tr><td>Telefone </td><td> <input type='text' name='edtTel' id='edtTel' onkeyup='return telefone(this)' maxlength='15' /></td></tr>";
        table +=   "<tr><td>Celular </td><td> <input type='text' name='edtCel' id='edtCel' onkeyup='return telefone(this)' maxlength='15' /></td></tr>";
        table +=   "<tr><td>Cargo</td><td><select name='selCargo' id= 'selCargo'>"+ option +"</select></td></tr>";
        table +=   "<tr><td>Admissão </td><td> <input type='date' name='cmbAdm' id= 'cmbAdm' class='selData' value='"+ today +"'></td></tr>";
        table +=   "<tr><td></td><td><button id='btn_Save'>Salvar</button></td></tr></table>";
        table +=   "<form id='frmRefresh' method='POST' action='#'></form>";

        $(document).off('click', '#btn_Save').on('click', '#btn_Save', function() {
            var nome = $('#edtNome').val().trim().toUpperCase();
            if(nome != ""){
                var rg = $('#edtRG').val().trim().toUpperCase();
                var cpf = $('#edtCPF').val().trim().toUpperCase();
                var pis = $('#edtPIS').val().trim().toUpperCase();
                var end = $('#edtEnd').val().trim().toUpperCase();
                var cid = $('#edtCid').val().trim().toUpperCase();
                var est = $('#edtEst').val().trim().toUpperCase();
                var cep = $('#edtCEP').val().trim().toUpperCase();
                var tel = $('#edtEnd').val().trim().toUpperCase();
                var cel = $('#edtCid').val().trim().toUpperCase();
                var id_cargo = parseInt($('#selCargo').val());
                var adm = $('#cmbAdm').val();

                var query = "query=INSERT INTO tb_funcionario VALUES (DEFAULT, '"+ nome +"', '"+ rg +"', '"+ cpf+"', '"+ pis+"', '"+ end+"', '"+ cid+"', '"+ est+"', '"+ cep+"', '"+ adm+"', 'DEFAULT', '"+ id_cargo+"', '"+ tel+"', '"+ cel+"', 'ATIVO');";
//                alert(query);
                queryDB(query);   
                $('#frmRefresh').submit();                               
            }else{
                alert('Todos os campos com * são obrigatórios');

            }
        });

        $(".content").html(table);
        $('#popTitle').html('Cadastro de Funcionário');     
        $(".overlay").css("visibility", "visible").css("opacity", "1");  

    });

    $('.btnNovoEmail').click(function(){ 

        var par = $.parseJSON($(this).getText('../config/'+usuario+'_ass.txt'));
        var ass = '\n';
        $.each(par, function(index) {
            ass = ass +'\n'+par[index];
        });

        var form = "<textarea class='edtTextArea' id='edtNewMail' type='text' name='edtNewMail' rows='30' cols='139'>"+ass+"</textarea>";
        var Btn = "<table><tr><td><button id='btnEnviar'>Enviar</button></td></tr></table>";
        var head = "<table><tr><td>destinatátio:</td><td><select id='selEmail'>";

        var dados = "query=SELECT * from tb_agenda order by nome;";
        $.ajax({
            url: 'ajax/ajax.php',
            type: 'POST',
            dataType: 'html',
            data: dados,
            async: false,		
            success: function(data){
                var obj = jQuery.parseJSON( data );
                $.each(obj, function( index, value ) {
                    if(value.email){
                        head = head + '<option value="'+value.email+'">'+value.nome + ' - ' + value.email+'</option>';
                    }
                });
            }
        });

        head = head + '</select></td>';
        head = head + "</tr><tr><td>assunto:</td><td><input type='text' id='edtSubject' size='100'></td><td>"+Btn+"</td></tr></table>";

        $(".content").html(form);
        $('#popTitle').html(head);

        $(document).off('click', '#btnEnviar').on('click', '#btnEnviar', function() {
            dados = 'num=-1&body='+$('#edtNewMail').val()+'&sub='+$('#edtSubject').val()+'&dest='+$('#selEmail').val();

            $.ajax({
                url: 'ajax/ajax_sendMail.php',
                type: 'POST',
                dataType: 'html',
                data: dados,
                async: false,
                success: function(data){
                    alert('Emil enviado com sucesso');
                    $('.close').click();
                }
            });	

        });

        $(".overlay").css("visibility", "visible").css("opacity", "1");  

    });         

     // POPUP CLOSE
    $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

    // CLIQUE NA TABELA tabHoras    
    var tbl = document.getElementById("tabHoras");
    $('#tabHoras').on( 'click', 'td', function () {
        var row = $(this).closest("tr").index();
        var col = $(this).closest("td").index();
        var func = tbl.rows[0].cells[Math.ceil((col-1)/2)].innerHTML;
        var data = tbl.rows[row].cells[0].innerHTML;
        var dia  = data.substr(-14,2);
        var mes  = data.substr(-11,2);
        var ano  = data.substr(-8,4);


        if(col%2 == 0){
            var ent = tbl.rows[row].cells[col].innerHTML;
            var sai = tbl.rows[row].cells[col+1].innerHTML;
        }else{
            var ent = tbl.rows[row].cells[col-1].innerHTML;
            var sai = tbl.rows[row].cells[col].innerHTML;
        }

        var table = "<table><tr><td>ENTRADA</td><td> <input type='text' maxlength='5' id='edtEntrada' value='"+ent+"' onkeyup='return hora(this)'/></td></tr>";
        table +=   "<tr><td>SAIDA</td><td> <input type='text' id='edtSaida' maxlength='5'  value='"+sai+"'onkeyup='return hora(this)' /></td></tr>";
        if ($(this).perm(classe,'edit')){
            table +=   "<tr><td></td><td><button id='btn_Save'>Salvar</button></td></tr></table>";
        }else{
            table +=   "<tr><td></td><td>Acesso apenas p/ consulta</td></tr></table>";
        }
        table +=   "<form id='frmRefresh' method='POST' action='#'></form>";


        $(document).off('click', '#btn_Save').on('click', '#btn_Save', function() {
            
            if($('#edtEntrada').val().length == 5 && $('#edtSaida').val().length == 5){
                var ent = ano+'-'+mes+'-'+dia +' '+ $('#edtEntrada').val() +':00' ;
                var sai = ano+'-'+mes+'-'+dia +' '+ $('#edtSaida').val() +':00' ;   

                if(new Date(sai) < new Date(ent) ){ // entrou em um dia e saiu em outro

                    var nova =  new Date(sai);
                    nova.setDate(nova.getDate() + 1);
                    nova = nova.toString();
                    d = nova.substr(8,2);
                    m = mes;
                    a = ano;
                    if(d == '01'){
                        m = parseInt(m)+1;
                        if(m > 12){
                            m = '01';
                            a = parseInt(ano)+1;
                        }else if(m < 10){
                            m = '0'+m;
                        }                        
                    }
                    var sai = a+'-'+m+'-'+d +' '+ $('#edtSaida').val() +':00' ;   
                }
                
                var query = "query=SELECT * FROM tb_hora_extra WHERE id_func = (SELECT id from tb_funcionario WHERE nome LIKE '"+ func +"%' ) AND entrada LIKE '"+ano+'-'+mes+'-'+dia+"%';";
                resp = queryDB(query);
                if(resp.length == 0){
                    var query = "query=INSERT INTO tb_hora_extra VALUES (DEFAULT, (SELECT id from tb_funcionario WHERE nome LIKE '"+ func +"%' ),'"+ent+"','"+sai+"');";
                }else{
                    var query = "query=UPDATE tb_hora_extra  SET entrada = '"+ent+"', saida = '"+sai+"'  WHERE id_func = (SELECT id from tb_funcionario WHERE nome LIKE '"+ func +"%' ) AND entrada LIKE '"+ano+'-'+mes+'-'+dia+"%'";
                }
                
                queryDB(query);  
                $('#frmRefresh').submit();

//                    alert(query);

            }else{
                alert('Horarios incompletos')
            }
        });

        $(".content").html(table);
        $('#popTitle').html(func+" - "+data);        
        $(".overlay").css("visibility", "visible").css("opacity", "1");  
    });


    // DUPLO CLIQUE NA TABELA tabItens
    $('#tabItens').on('dblclick','.tbl_row', function(){ // SELECIONANDO UM ÍTEM DA TABELA (DUPLO CLIQUE)

        var arr = window.location.href.split("/");
        var id = $(this).attr('id');

//        alert(arr[arr.length-1]);

        switch (arr[arr.length-1]){
        case 'pesq_ped.php#': // PÁGINA PESQ_PED (PESQUISA DE PEDIDOS)
            var cod = $.trim($(this).children('td').slice(0, 1).text());
            var num = $.trim($(this).children('td').slice(1, 2).text());
            var cli = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
            var data = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
            var status = $.trim($(this).children('td').slice(4, 5).text());
            var valor = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
            var have_nf = $.trim($(this).children('td').slice(6, 7).text());
            var path = $.trim($(this).children('td').slice(7, 8).text());

            var table = "<table><tr><td>Cliente:</td><td>"+cli+"</td></tr><tr><td>Data:</td><td>"+data+"</td></tr>"; 
            if(status == "COT"){
                table += "<tr><td>Status:</td><td>Cotação</td></tr><tr><td>Valor:</td><td>"+valor+"</td></tr>";
            }else{
                table += "<tr><td>Status:</td><td>Pedido</td></tr>"
                table += "<tr><td>Valor:</td><td>"+valor+"</td></tr>";
              
                table += "</td></tr>";
                $(document).off('click', '#btnUpload').on('click', '#btnUpload', function() {
                    $('#frmUpload').submit();
                });                
            }

            var Btn = "";
            if(have_nf == "@"){
                Btn += "<button id='btnVerPDF'>Abrir PDF</button>";
                    
                $(document).off('click', '#btnVerPDF').on('click', '#btnVerPDF', function() {
                    var out = '';
                    for(i=0; i<arr.length-1; i++){
                        out += arr[i]+'/';
                    }
                    out += path;
                    window.open(out, '_blank');
                });

            }               
            var form = "<form id='frmPesqPed' method='POST' action='pdf_analise.php'><input type='hidden' name='cod_ped' value='"+id+"'><input type='hidden' name='status' value='"+status+"'></form>";
            if ($(this).perm(classe,'edit')){
                table += "<tr><td colspan='2'><form id='frmUpload' action='upload_nf.php' method='post' enctype='multipart/form-data'>";
                table += "<input type='file' name='up_pdf' accept='.pdf'>";
                table += "<input type='hidden' name='cod' value='"+cod+"'>";
                table += "<input type='hidden' name='eid' value='FB'>";                
                table += "<input type='hidden' name='destino' value='venda'>";
                table += "<button type='submit' id='btnUpload'>Upload</button></td></form></tr>";                  
                Btn = "<table><tr><td><button id='btnAnalisar'>Analisar</button></td><td><button id='btnVisualizar'>Visualizar</button><button id='btnDeletar'>Deletar</button>";
                if(have_nf == "@"){
                    Btn += "<button id='btnVerPDF'>Abrir PDF</button>";
                        
                    $(document).off('click', '#btnVerPDF').on('click', '#btnVerPDF', function() {
                        var out = '';
                        for(i=0; i<arr.length-1; i++){
                            out += arr[i]+'/';
                        }
                        out += path;
                        window.open(out, '_blank');
                    });
    
                }                
                Btn +="</td></tr></table>";

                $(document).off('click', '#btnAnalisar').on('click', '#btnAnalisar', function() {
                    $('#frmPesqPed').attr('action', 'pdf_analise.php');
                    $('#frmPesqPed').submit();
                });

                $(document).off('click', '#btnVisualizar').on('click', '#btnVisualizar', function() {
                    $('#frmPesqPed').attr('action', 'edita_ped.php');
                    $('#frmPesqPed').submit();
                });

                $(document).off('click', '#btnDeletar').on('click', '#btnDeletar', function() {
                    if (confirm('Deseja remover o ítem definitivamente do sistema?')) {
                        $('#frmPesqPed').attr('action', 'del_ped.php');
                        $('#frmPesqPed').submit();
                    }
                });

                $(document).off('click', '#btnTransfVenda').on('click', '#btnTransfVenda', function() {
                    var NF = $.trim($('#edtNF').val());
                    if(NF == ''){
                        NF='FECHADO';
                    }                      
                    if (confirm('Deseja associar a NFe-'+NF+' de venda a este pedido?')) {
//                            alert('->'+num);
        
                        var dados = "query=UPDATE tb_pedido  set status ='"+NF+"' where num_ped = '"+num+"';";
                        //alert(dados);
                        $.ajax({
                            url: 'ajax/ajax.php',
                            type: 'POST',
                            dataType: 'html',
                            data: dados,
                            async: false,		
                            success: function(data){
//                                    alert('NF Adicionada com sucesso');
                            }
                        });
                    }
                });
            }else{
                Btn += "<br>Acesso apenas p/ consulta<br>";

            }

            table += "</table>";
            $(".content").html(table+form+Btn);
            $('#popTitle').html(num);

            break;

        case 'pesq_emp.php#':
//            alert('teste');
                var id = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
                var nome = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
                var cnpj = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
                var ie = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
                var end = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
                var cidade = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
                var estado = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
                var tel = $.trim($(this).children('td').slice(7, 8).text().toUpperCase());
                var tipo = $.trim($(this).children('td').slice(8, 9).text().toUpperCase());
                var cep = $.trim($(this).children('td').slice(9, 10).text().toUpperCase());
                var bairro = $.trim($(this).children('td').slice(10, 11).text().toUpperCase());
                var num = $.trim($(this).children('td').slice(11, 12).text().toUpperCase());

                var table = "<table><tr><td>Emp.:</td><td>"+nome+"</td></tr>"+
                                  "<tr><td>CNPJ: </td><td>"+cnpj+"</td></tr>"+
                                  "<tr><td>I.E: </td><td>"+ie+"</td></tr>"+
                                  "<tr><td>End.: </td><td>"+end+", "+num+"</td></tr></tr>"+
                                  "<tr><td>Cidade: </td><td>"+cidade+"-"+estado+"</td></tr>"+
                                  "<tr><td>Bairro: </td><td>"+bairro+"</td></tr>"+
                                  "<tr><td>CEP: </td><td>"+cep+"</td></tr>"+
                                  "<tr><td>Tel.: </td><td>"+tel+"</td></tr>"+
                            "</table>";

                            var form = "<form id='frmPesqEmp' method='POST' action='edt_emp.php'><input type='hidden' name='cod_emp' value='"+id+"'></form>";
                            var Btn = "<br>Acesso apenas p/ consulta<br>";
                
                if ($(this).perm(classe,'edit')){
                
                    Btn = "<table><tr><td><button id='btnEditar'>Editar</button><button id='btnDeletar'>Deletar</button></td></tr></table>";
    
                    $(document).off('click', '#btnEditar').on('click', '#btnEditar', function() {
                        $('#frmPesqEmp').attr('action', 'edt_emp.php');
                        $('#frmPesqEmp').submit();
                    });
    
                    $(document).off('click', '#btnDeletar').on('click', '#btnDeletar', function() {
                        if (confirm('Deseja remover o ítem definitivamente do sistema?')) {
                            $('#frmPesqEmp').attr('action', 'del_emp.php');
                            $('#frmPesqEmp').submit();
                        }
                    });
                }
                $(".content").html(table+form+Btn);
                $('#popTitle').html(nome);
            break;

        case 'pesq_prod.php#':
            var cod_int = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
            var nome = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
            var und = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
            var etq = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
            var codprod = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
            var forn = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
            var preco = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
            var custo = $.trim($(this).children('td').slice(7, 8).text().toUpperCase());
            var margem = $.trim($(this).children('td').slice(8, 9).text().toUpperCase());
            var tipo = $.trim($(this).children('td').slice(9, 10).text().toUpperCase());

            if(cod_int < 7000){
                var table = "<table><tr><td>Unidade</td><td>"+und+"</td></tr><tr><td>Estoque</td><td>"+etq+"</td></tr><tr><td>Cód. Fab</td><td>"+codprod+"</td></tr><tr><td>Custo</td><td>"+custo+"</td></tr><tr><td>Margem</td><td>"+margem+"%</td></tr><tr><td>Preço</td><td>"+preco+"</td></tr></table>";
            }else{
                var val = parseFloat(preco.substring(2,preco.length-3)+'.'+preco.substring(preco.length-2,preco.length)) ;
                var table = "<table><tr><td>Unidade</td><td>"+und+"</td></tr><tr><td>Estoque</td><td>"+etq+"</td></tr><tr><td>Cód. Fab</td><td>"+codprod+"</td></tr><tr><td>Custo</td><td>"+custo+"</td></tr><tr><td>Margem</td><td>"+margem+"%</td></tr><tr><td>900ml</td><td>R$"+val.toFixed(2)+"</td></tr><tr><td>1.8L</td><td>R$"+(val*2).toFixed(2)+"</td></tr><tr><td>2.7L</td><td>R$"+(val*3).toFixed(2)+"</td></tr><tr><td>Galão</td><td>R$"+(val*4).toFixed(2)+"</td></tr></table>";
            }
            var form = "<form id='frmPesqProd' method='POST' action='edt_prod.php'><input type='hidden' name='cod_prod' value='"+id+"'></form>";
            var Btn = "<br>Acesso apenas p/ consulta<br>";

            if ($(this).perm(classe,'edit')){
                Btn = "<table><tr><td><button id='btnEditar'>Editar</button><button id='btnDeletar'>Deletar</button>";
                
                if(tipo == 'CONJ' || tipo == 'SERVICO'){
                    Btn += "<button id='btnSubconj'>Itens</button> ";

                    $(document).off('click', '#btnSubconj').on('click', '#btnSubconj', function() {
                        $('#frmPesqProd').attr('action', 'cad_subconj.php');
                        $('#frmPesqProd').submit();
                    });                    
                }

                Btn += "</td></tr></table>";
                

                $(document).off('click', '#btnEditar').on('click', '#btnEditar', function() {
                    $('#frmPesqProd').attr('action', 'edt_prod.php');
                    $('#frmPesqProd').submit();
                });

                $(document).off('click', '#btnDeletar').on('click', '#btnDeletar', function() {
                    if (confirm('Deseja remover o ítem definitivamente do sistema?')) {
                        if(tipo == 'CONJ'){
                            var query = "query=DELETE FROM tb_subconj WHERE id_conj = "+ id +";";
                            queryDB(query);                            
                        }
                        $('#frmPesqProd').attr('action', 'del_prod.php');
                        $('#frmPesqProd').submit();
                    }
                });
            }

            $(".content").html(table+form+Btn);
            $('#popTitle').html(nome);

            break;

            case 'pesq_agenda.php#':  
                var id = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
                var nome = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
                var dep = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
                var email = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
                var cel = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
                var fone = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
                var emp = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
//                var table = "<table><tr><td>Nome:</td><td>"+nome+"</td></tr><tr><td>Empresa:</td><td>"+emp+"</td></tr><tr><td>Depart.:</td><td>"+dep+"</td></tr><tr><td>Email</td><td>"+email+"</td></tr><tr><td>Celular:</td><td>"+cel+"</td></tr><tr><td>Telefone:</td><td>"+fone+"</td></tr></table>";

                var form = "<form id='frmPesqAgenda' method='POST' ><input type='hidden' name='id' value='"+id+"'><input type='hidden' name='hidDel' id='hidDel' value='0'>";
                var table = "<table><tr><td>Nome</td><td> <input type='text' name='nome' maxlength='40' id='edtNome' value='"+nome.toUpperCase()+"' /></td></tr>";
                table = table  +   "<tr><td>Empresa</td><td> <input type='text' name='emp' maxlength='80' id='edtEmp' value='"+emp.toUpperCase()+"' readonly /></td></tr>";
                table = table  +   "<tr><td>Departamento</td><td> <input type='text' name='dep' maxlength='15' id='edtDep' value='"+dep.toUpperCase()+"'  /></td></tr>";
                table = table  +   "<tr><td>Email</td><td> <input type='text' name='email' maxlength='80' id='edtEmail' value='"+email.toLowerCase()+"'  /></td></tr>";
                table = table  +   "<tr><td>Cel</td><td> <input type='text' name='fone1' maxlength='15' id='edtCel' value='"+cel+"'  /></td></tr>";
                table = table  +   "<tr><td>Telefone</td><td> <input type='text' name='fone2' maxlength='15' id='edtFone' value='"+fone+"'  /></td></tr></table>";

                form = form + table + "</form>";
//                var form = "<form id='frmPesqAgenda' method='POST' action='edt_agenda.php'><input type='hidden' name='cod_prod' value='"+id+"'></form>";
                var Btn = "<br>Acesso apenas p/ consulta<br>";
    
                if ($(this).perm(classe,'edit')){
                
                    Btn = "<table><tr><td><button id='btnEditar'>Salvar</button><button id='btnDeletar'>Deletar</button></td></tr></table>";
    
                    $(document).off('click', '#btnEditar').on('click', '#btnEditar', function() {
                        $('#hidDel').val('0');
                        $('#frmPesqAgenda').attr('action', 'save_agenda.php');
                        $('#frmPesqAgenda').submit();
                    });
    
                    $(document).off('click', '#btnDeletar').on('click', '#btnDeletar', function() {
                        $('#hidDel').val('1');
                        if (confirm('Deseja remover o ítem definitivamente do sistema?')) {
                            $('#frmPesqAgenda').attr('action', 'save_agenda.php');
                            $('#frmPesqAgenda').submit();
                        }
                    });
                }
    
                $(document).off('keyup', '#edtCel').on('keyup', '#edtCel', function() {
                    var val= $(this).val();
                    $('#edtCel').val( $(this).celular(val));
                });

                $(document).off('keyup', '#edtFone').on('keyup', '#edtFone', function() {
                    var val= $(this).val();
                    $('#edtFone').val( $(this).telefone(val));
                });

                $(".content").html(form+Btn);
                $('#popTitle').html(nome);
    
                break;


            case 'email.php#':

                var id = $(this).attr('id');
                var title = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
                var par = $.parseJSON($(this).getText('../config/'+usuario+'_ass.txt'));
                var ass = '\n';
                $.each(par, function(index) {
                    ass = ass +'\n'+par[index];
                });

                // Caixa de Entrada ou de Saída?
                if($('#tabItens th').slice(1, 2).text() =='Destinatário'){
                    caixa = 'INBOX.Sent';
                }else{
                    caixa = 'INBOX';
                }

                dados = 'num='+id+'&caixa='+caixa;
                var saida = ""
                $.ajax({
                    url: 'ajax/ajax_getImapBody.php',
                    type: 'POST',
                    dataType: 'html',
                    data: dados,
                    async: false,
                    success: function(data){
                        saida = data;
            
                    }
            	});	
                
                var Btn = "<table><tr><td><button id='btnResponder'>Responder</button></td><td><button id='btnEncaminhar'>Encaminhar</button></td><td><button id='btnDeletar'>Deletar</button></td></tr></table>";
                var form = "<textarea class='edtTextArea' id='edtNewMail' type='text' name='edtNewMail' rows='30' cols='150'>"+saida +"</textarea>";

                $(document).off('click', '#btnDeletar').on('click', '#btnDeletar', function() {
                    $('#hidDel').val('1');
                    if (confirm('Deseja remover este email permanentemente do sistema?')) {
                        dados = 'num='+id;
                        $.ajax({
                            url: 'ajax/ajax_ImapDelete.php',
                            type: 'POST',
                            dataType: 'html',
                            data: dados,
                            async: false,
                            success: function(data){
                                alert(data);
                                $('.btnReceber').click();                
                            }
                        });	
    
                    }
                });

                $(document).off('click', '#btnEncaminhar').on('click', '#btnEncaminhar', function() {

                    var form = "<textarea class='edtTextArea' id='edtNewMail' type='text' name='edtNewMail' rows='30' cols='150'>"+ass + saida +"</textarea>";
                    var Btn = "<table><tr><td><button id='btnEnviar'>Enviar</button></td></tr></table>";
                    var head = "<table><tr><td>destinatátio:</td><td><select id='selEmail'>";
            
                    var dados = "query=SELECT * from tb_agenda order by nome;";
                    $.ajax({
                        url: 'ajax/ajax.php',
                        type: 'POST',
                        dataType: 'html',
                        data: dados,
                        async: false,		
                        success: function(data){
                            var obj = jQuery.parseJSON( data );
                            $.each(obj, function( index, value ) {
                                if(value.email){
                                    head = head + '<option value="'+value.email+'">'+value.nome + ' - ' + value.email+'</option>';
                                }
                            });
                        }
                    });
            
                    head = head + '</select></td>';
                    head = head + "</tr><tr><td>assunto:</td><td><input type='text' id='edtSubject' size='100' value=Fwd:'"+title+"'></td><td>"+Btn+"</td></tr></table>";
            
                    $(".content").html(form);
                    $('#popTitle').html(head);
                    id = -1

                });                

                $(document).off('click', '#btnResponder').on('click', '#btnResponder', function() {
                    var form = "<textarea class='edtTextArea' id='edtNewMail' type='text' name='edtNewMail' rows='30' cols='150'>"+ ass +  saida +"</textarea>";
                    Btn = "<table><tr><td><button id='btnEnviar'>Enviar</button></td></tr></table>";

                    $(".content").html(form);
                    $('#popTitle').html(title+"<br>"+Btn);

                });

                $(document).off('click', '#btnEnviar').on('click', '#btnEnviar', function() {
                    if(id < 0){
                        dados = 'num='+id + '&body='+$('#edtNewMail').val()+'&sub='+$('#edtSubject').val()+'&dest='+$('#selEmail').val();
//                        alert(dados);
                    }else{
                        dados = 'num='+id + '&body='+$('#edtNewMail').val();
                    }

                    $.ajax({
                        url: 'ajax/ajax_sendMail.php',
                        type: 'POST',
                        dataType: 'html',
                        data: dados,
                        async: false,
                        success: function(data){                           
                            alert('Email enviado com sucesso');
                            $('.close').click();
                        }
                    });	

                });

                $(".content").html(form);
                $('#popTitle').html(title+"<br>"+Btn);

                break;



            case 'pesq_anafin.php#':

                var tipo = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
                var origem = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
                var ref = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
                var emp = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
                var venc = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
                var pgto = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
                var valor = $.trim($(this).children('td').slice(7, 8).text().toUpperCase());

                var form = "<form id='frmPesqProd' method='POST' ><input type='hidden' name='id' value='"+id+"'><input type='hidden' name='hidDel' id='hidDel' value='0'>";
                var table = "<table><tr><td>Referência / NF</td><td> <input type='text' name='ref' maxlength='30' id='edtRef' value='"+ref+"' /></td></tr>";
                var Btn = "<br>Acesso apenas p/ consulta<br><br>";

                if(tipo == 'ENTRADA'){
                    table = table +  "<tr><td>Entrada/Saida</td><td> <select name='tipo' id='selTipo'><option value='ENTRADA' selected> A Receber </option><option value='SAIDA'> A Pagar </option> </select></tr>";
                }else{
                    table = table +  "<tr><td>Entrada/Saida</td><td> <select name='tipo' id='selTipo'><option value='ENTRADA'> A Receber </option><option value='SAIDA' selected> A Pagar </option> </select></tr>";
                }
                if(origem == 'FUN'){
                    table = table +  "<tr><td>Origem</td><td> <select name='origem' id='selOrig'><option value='FUN' selected> Funilaria e Pintura </option><option value='SAN'> Sanfonados</option> option value='FUN' selected> A Receber </option><option value='OUT'> Outros </option> </select></tr>";
                }
                if(origem == 'SAN'){
                    table = table +  "<tr><td>Origem</td><td> <select name='origem' id='selOrig'><option value='FUN'> Funilaria e Pintura </option><option value='SAN' selected> Sanfonados</option> option value='FUN' selected> A Receber </option><option value='OUT'> Outros </option> </select></tr>";
                }
                if(origem == 'OUT'){
                    table = table +  "<tr><td>Origem</td><td> <select name='origem' id='selOrig'><option value='FUN'> Funilaria e Pintura </option><option value='SAN'> Sanfonados</option> option value='FUN' selected> A Receber </option><option value='OUT' selected> Outros </option> </select></tr>";
                }

                table = table + "<tr><td>Sacado / Cedente</td><td> <input type='text' name='dest' maxlength='30' id='edtDest' value='"+emp+"' /></td></tr>";
                table = table + "<tr><td>Vencimento</td><td> <input type='date' name='data_venc' maxlength='30' id='edtDataVenc' value='"+venc.split("/")[2]+'-'+venc.split("/")[1]+'-'+venc.split("/")[0]+ "' /></td></tr>";

                if(pgto == 'BOL'){
                    table = table +  "<tr><td>Pagto.</td><td> <select name='selPgt' id='selPgt'><option value='BOL' selected>Boleto</option><option value='CRD'>Cartão de Crédito</option><option value='DEB'>Cartão de Débto</option><option value='CHQ'>Cheque</option><option value='DIN'>Dinheiro</option><option value='DEP'>Depósito Bancário</option><option value='AUT'>Débto Automático</option></select></tr>";
                }
                if(pgto == 'CRD'){
                    table = table +  "<tr><td>Pagto.</td><td> <select name='selPgt' id='selPgt'><option value='BOL'>Boleto</option><option value='CRD' selected>Cartão de Crédito</option><option value='DEB'>Cartão de Débto</option><option value='CHQ'>Cheque</option><option value='DIN'>Dinheiro</option><option value='DEP'>Depósito Bancário</option><option value='AUT'>Débto Automático</option> </select></tr>";
                }
                if(pgto == 'DEB'){
                    table = table +  "<tr><td>Pagto.</td><td> <select name='selPgt' id='selPgt'><option value='BOL'>Boleto</option><option value='CRD'>Cartão de Crédito</option><option value='DEB' selected>Cartão de Débto</option><option value='CHQ'>Cheque</option><option value='DIN'>Dinheiro</option><option value='DEP'>Depósito Bancário</option><option value='AUT'>Débto Automático</option> </select></tr>";
                }
                if(pgto == 'CHQ'){
                    table = table +  "<tr><td>Pagto.</td><td> <select name='selPgt' id='selPgt'><option value='BOL'>Boleto</option><option value='CRD'>Cartão de Crédito</option><option value='DEB'>Cartão de Débto</option><option value='CHQ' selected>Cheque</option><option value='DIN'>Dinheiro</option><option value='DEP'>Depósito Bancário</option><option value='AUT'>Débto Automático</option> </select></tr>";
                }
                if(pgto == 'DIN'){
                    table = table +  "<tr><td>Pagto.</td><td> <select name='selPgt' id='selPgt'><option value='BOL'>Boleto</option><option value='CRD'>Cartão de Crédito</option><option value='DEB'>Cartão de Débto</option><option value='CHQ'>Cheque</option><option value='DIN' selected>Dinheiro</option><option value='DEP'>Depósito Bancário</option><option value='AUT'>Débto Automático</option> </select></tr>";
                }
                if(pgto == 'DEP'){
                    table = table +  "<tr><td>Pagto.</td><td> <select name='selPgt' id='selPgt'><option value='BOL'>Boleto</option><option value='CRD'>Cartão de Crédito</option><option value='DEB'>Cartão de Débto</option><option value='CHQ'>Cheque</option><option value='DIN'>Dinheiro</option><option value='DEP' selected>Depósito Bancário</option><option value='AUT'>Débto Automático</option> </select></tr>";
                }
                if(pgto == 'AUT'){
                    table = table +  "<tr><td>Pagto.</td><td> <select name='selPgt' id='selPgt'><option value='BOL'>Boleto</option><option value='CRD'>Cartão de Crédito</option><option value='DEB'>Cartão de Débto</option><option value='CHQ'>Cheque</option><option value='DIN'>Dinheiro</option><option value='DEP'>Depósito Bancário</option><option value='AUT' selected>Débto Automático</option> </select></tr>";
                }

                //da uma formatada no campo 'valor' deixando só numeros e '.'
                valor = $(this).numeros(valor).substring(0,$(this).numeros(valor).length-2) + '.' + $(this).numeros(valor).substring($(this).numeros(valor).length -2,$(this).numeros(valor).length);
                
                table = table + "<tr><td>Valor R$</td><td> <input type='text' name='valor' maxlength='15' id='edtValor' value='"+ valor +"' /></td></tr>";
                form = form + table + '</form>' ;    
                
                if ($(this).perm(classe,'edit')){

                    Btn = "<table><tr><td><button id='btnSalvar'>Salvar</button><button id='btnDeletar'>Deletar</button></td></tr></table>";

                    $(document).off('click', '#btnSalvar').on('click', '#btnSalvar', function() {
                        $('#hidDel').val('0');
                        $('#frmPesqProd').attr('action', 'save_finan.php');
                        $('#frmPesqProd').submit();
                    });
        
                    $(document).off('click', '#btnDeletar').on('click', '#btnDeletar', function() {
                        $('#hidDel').val('1');
                        if (confirm('Deseja remover este registro definitivamente do sistema?')) {
                            $('#frmPesqProd').attr('action', 'save_finan.php');
                            $('#frmPesqProd').submit();
                        }
                    });
                }

                $(document).off('keyup', '#edtValor').on('keyup', '#edtValor', function() {
                    var val= $(this).val();
                    $('#edtValor').val( $(this).moeda(val));
                });

                $(".content").html(form + Btn);
                $('#popTitle').html(id+'-'+ref+' - '+emp);
        
            break;

        case 'pesq_ent.php#':
            var cod = $.trim($(this).children('td').slice(0, 1).text());
            var nf = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
            var forn = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
            var data = $.trim($(this).children('td').slice(3, 4).text());
            var status = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
            var resp = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
            var have_nf = $.trim($(this).children('td').slice(6, 7).text());
            var path = $.trim($(this).children('td').slice(7, 8).text());
            var e_id = $.trim($(this).children('td').slice(8, 9).text());
            var form = "<form id='frmPesqEnt' method='POST' action='edita_ent.php'><input type='hidden' name='cod_ent' value='"+id+"'>";
            form    += "<input type='hidden' name='status' value='"+status+"'>";
            var table = "<table><tr><td>Nota Fiscal:</td><td>"+nf+"</td></tr><tr><td>Fornecedor:</td><td>"+forn+"</td></tr><tr><td>Data:</td><td>"+data+"</td></tr><tr><td>Status:</td><td>"+status+"</td></tr>";
            var Btn =  "<table><tr><td><button id='btnVisualizar'>Visualizar</button>";

            if(have_nf == "@"){
                Btn += "<button id='btnVerPDF'>Abrir PDF</button>";
                    
                $(document).off('click', '#btnVerPDF').on('click', '#btnVerPDF', function() {
                    var out = '';
                    for(i=0; i<arr.length-1; i++){
                        out += arr[i]+'/';
                    }
                    out += path;
                    window.open(out, '_blank');
                });

            }            

            $(document).off('click', '#btnVisualizar').on('click', '#btnVisualizar', function() {
                $('#frmPesqEnt').attr('action', 'edita_ent.php');
                $('#frmPesqEnt').submit();
            });



            if ($(this).perm(classe,'edit')){

                Btn += "<button id='btnDeletar'>Deletar</button>";
                
                $(document).off('click', '#btnDeletar').on('click', '#btnDeletar', function() {
                    if (confirm('Deseja remover o ítem definitivamente do sistema?')) {
                        $('#frmPesqEnt').attr('action', 'del_ent.php');
                        $('#frmPesqEnt').submit();
                    }
                });

                if(status == 'FECHADO'){
                    table += "<tr><td colspan='2'><form id='frmUpload' action='upload_nf.php' method='post' enctype='multipart/form-data'>";
                    table += "<input type='file' name='up_pdf' accept='.pdf'>";
                    table += "<input type='hidden' name='cod' value='"+cod+"'>";
                    table += "<input type='hidden' name='eid' value='"+e_id+"'>";
                    table += "<input type='hidden' name='destino' value='compra'>";
                    table += "<button type='submit' id='btnUpload'>Upload</button></td></form></tr>";
                    
                    $(document).off('click', '#btnUpload').on('click', '#btnUpload', function() {
                        $('#frmUpload').submit();
                    });
        
                }

            }else{
                Btn += "<br>Acesso apenas p/ consulta<br>";
            }

            table += "</table>";
            form  += "</form>";
            Btn   += "</td></tr></table>";

            $(".content").html(table+form+Btn);
            $('#popTitle').html(forn+' NF:'+nf);

            break;


        case 'cad_item_ped.php#': // nfe_conf.php#
            var codProd = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
            var desc = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
            var und = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
            var etq = $.trim($(this).children('td').slice(3, 4).text());
            var preco = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
            var forn = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
            var codPed = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
            var tipo = $.trim($(this).children('td').slice(7, 8).text().toUpperCase());
            var table = "<table><td>Und.:</td><td>"+und+"</td></tr><td>Preço:</td><td>"+ preco +"</td></tr><td>Forn.:</td><td>"+forn+"</td></tr></table>";
            var form = "<form id='frmAddItem' method='POST' action='add_item.php'><input type='hidden' name='preco' value='"+ ClearMoney(preco) +"'><input type='hidden' name='op' value='add'><input type='hidden' name='cod_prod' value='"+id+"'><input type='hidden' name='und' value='"+und+"'><input type='hidden' name='cod_ped' value='"+codPed+"'><input type='hidden' name='tipo' value='"+tipo+"'>";
            var Btn = "<table><tr><td><label> Quantidade </label></td><td><input id='edtQtd' type='text' name='qtd'/></td>";
            if(tipo == 'TINTA'){
                Btn = Btn + " <td><select name='vol'><option value='0.5'>450ml</option><option selected='selected' value='1'>900ml</option><option value='2'>1.8L</option><option value='3'>2.7L</option><option value='4'>3.6L</option></select></td>";
            }else{
                Btn = Btn + "<input type='hidden' name='vol' value='1'>";
            }

            Btn = Btn + "<td><button name='adicionar' id='btnAdd'>Adicionar</button></td></tr></table></form>";

            $(document).off('click', '#btnAdd').on('click', '#btnAdd', function() {
                $('#frmAddItem').submit();
            });

            $(document).off('keyup', '#edtQtd').on('keyup', '#edtQtd', function() {
                txt = $('#edtQtd').val()
                $("#edtQtd").val($(this).numeros(txt));
            });         

            $(".content").html(table+form+Btn);
            $('#popTitle').html(desc);

            break;     
        case 'nfe_conf.php#':

            $('#selBusca').val('cod');
            $('#edtBusca').val(id);
            $('#botao_inline').trigger('click');

            break;            
        case 'cad_etq.php#':

            $('#selBusca').val('cod');
            $('#edtBusca').val(id);
            $('#botao_inline').trigger('click');

            break; 
        case 'cad_item_ent.php':
            var codProd = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());

//            alert(codProd);

            $('#selBusca').val('cod');
            $('#edtBusca').val(id);
            $('#botao_ok').trigger('click');


            break;             
        case 'cad_subconj.php#':
                var codProd = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
                var desc = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
                var und = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
                var custo = $.trim($(this).children('td').slice(3, 4).text());
                var forn = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
                var tipo = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
                var cod_conj = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());                

                var table = "<table><td>Und.:</td><td>"+und+"</td></tr><td>Custo:</td><td>"+ custo +"</td></tr><td>Forn.:</td><td>"+forn+"</td></tr></table>";
                var form = "<form id='frmAddItem' method='POST' action='#'><input type='hidden' name='preco' value='"+ ClearMoney(custo) +"'><input type='hidden' name='cod_prod' value='"+cod_conj+"'>";
                var Btn = "<table><tr><td><label> Quantidade </label></td><td><input id='edtQtd' type='text' name='qtd'/></td>";
                if(tipo == 'TINTA'){
                    Btn = Btn + " <td><select name='vol'><option value='0.5'>450ml</option><option selected='selected' value='1'>900ml</option><option value='2'>1.8L</option><option value='3'>2.7L</option><option value='4'>3.6L</option></select></td>";
                }else{
                    Btn = Btn + "<input type='hidden' name='vol' value='1'>";
                }
    
                Btn = Btn + "<td><button name='adicionar' id='btnAdd'>Adicionar</button></td></tr></table></form>";
  
                $(document).off('click', '#btnAdd').on('click', '#btnAdd', function() {
                    if(cod_conj != id){
                        var qtd =  parseFloat($('#edtQtd').val(),10);
                        var query = "query=INSERT INTO tb_subconj VALUES (DEFAULT, "+cod_conj+", "+id+", "+ qtd +");";
                        queryDB(query);
                        $('#frmAddItem').submit();    
                    }else{
                        alert("Não se pode adicionar um conjunto como ítem dele mesmo")
                    }
                });
    
                $(document).off('keyup', '#edtQtd').on('keyup', '#edtQtd', function() {
                    txt = $('#edtQtd').val()
                    $("#edtQtd").val($(this).moeda(txt));
                });         
    
                $(".content").html(table+form+Btn);
                $('#popTitle').html(desc);
    


            break;  
        case 'cad_item_of.php#':
                var id_prod = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
                var cod_prod = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
                var desc = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
                var und = $.trim($(this).children('td').slice(3, 4).text());
                var tipo = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
                var id_of = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());

                var table = "<table><td>Cod.:</td><td>"+cod_prod+"</td></tr><td>Tipo:</td><td>"+ tipo +"</td></tr><td>Und.:</td><td>"+und+"</td></tr></table>";
                var form = "<form id='frmAddItem' method='POST' action='#'><input type='hidden' name='id_prod' value='"+id_prod+"'>";
                var Btn = "<table><tr><td><label> Quantidade </label></td><td><input id='edtQtd' type='text' name='qtd'/></td>";    
                Btn = Btn + "<td><button name='adicionar' id='btnAdd'>Adicionar</button></td></tr></table></form>";

                $(document).off('click', '#btnAdd').on('click', '#btnAdd', function() {
                    var qtd =  parseFloat($('#edtQtd').val(),10);
                    var query = "query=INSERT INTO tb_item_serv VALUES (DEFAULT, "+id_of+", "+id_prod+", "+ qtd +");";
                    queryDB(query);
                    $('#frmAddItem').submit();    
                });

                $(".content").html(table+form+Btn);
                $('#popTitle').html(desc);

            break;  
              
            case 'pesq_of.php#':
                    var id_of = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
                    var num_of = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
                    var resp = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
                    var func = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
                    var tipo = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
                    var data = $.trim($(this).children('td').slice(5, 6).text());
                    var status = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
    
                    var table = "<table><tr><td>Cod.:</td><td>"+id_of+"</td></tr><tr><td>Tipo:</td><td>"+ tipo +"</td></tr><tr><td>Emit.:</td><td>"+resp+"</td></tr>";
                    table += " <tr><td>Func.:</td><td>"+func+"</td></tr><tr><td>Data.:</td><td>"+data+"</td></tr><tr><td>Status.:</td><td>"+status+"</td></tr></table>";
                    var form = "<form id='frmDetalhar' method='POST' action='cad_item_of.php'><input type='hidden' name='cod_serv' value='"+id_prod+"'></form>";
                    form +=    "<form id='frmImprimir' method='POST' action='pdf_of.php'><input type='hidden' name='cod_serv' value='"+id_of+"'></form>";
                    form +=    "<form id='frmRefresh' method='POST' action='#'></form>";
                    var Btn =  "<table><tr><td><button name='adicionar' id='btnDet'>Detalhar</button></td><td><button name='imprimir' id='btnImp'>Imprimir</button></td>";
                    if(status == "ABERTO"){
                        Btn += "<td><button name='deletar' id='btnDel'>Deletar</button></td>";
                    }
                        Btn += "</tr></table>";
                    $(document).off('click', '#btnDet').on('click', '#btnDet', function() {
                        var now = new Date();
                        now.setTime(now.getTime() + 1 * 3600 * 1000);
                        document.cookie = "cod_serv="+id_of+"; expires=" + now.toUTCString() + "; path=/";        
                        $('#frmDetalhar').submit();    
                    });
    
                    $(document).off('click', '#btnImp').on('click', '#btnImp', function() {
                        $('#frmImprimir').submit();    
                    });     

                    $(document).off('click', '#btnDel').on('click', '#btnDel', function() {
                        if (confirm('Confirma a exclusão desta OF?')) {
                            var query = "query=DELETE FROM tb_item_serv WHERE id_serv = "+ id_of +";";
                            queryDB(query);
                            var query = "query=DELETE FROM tb_servico WHERE id = "+ id_of +";";
                            queryDB(query);
                            $('#frmRefresh').submit();    

                        }
                    }); 

                    $(".content").html(table+form+Btn);
                    if(tipo == "OF"){
                        $('#popTitle').html('OF - '+num_of);
                    }else{
                        $('#popTitle').html('OS - '+num_of);
                    }
                break;  

            case 'funcionarios.php#':
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
                break;

                default:
                        if (arr[arr.length-1] == 'cargos.php#' || arr[arr.length-1] == 'cargos.php'){         
                        var id_cargo = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
                        var cargo = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
                        var tipo = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
                        var sal = ClearMoney($.trim($(this).children('td').slice(3, 4).text()));

                        var table = "<table><tr><td>Cargo *</td><td> <input type='text' name='cargo' maxlength='40' id='edtCargo' value='"+cargo+"'/></td></tr>";
                        table +=   "<tr><td>Salário * R$</td><td><input type='text' name='edtSal' id='edtSal' onkeyup='return money(this)' value='"+sal+"'/></td></tr>";
                        if(tipo == "HORISTA"){
                            table +=   "<tr><td>Tipo</td><td><select name='tipo' id= 'selTipo'> <option selected='selected' value='HORA'>HORISTA</option><option value='MENSAL'>MENSALISTA</option></select></td></tr>";
                        }else{
                            table +=   "<tr><td>Tipo</td><td><select name='tipo' id= 'selTipo'> <option value='HORA'>HORISTA</option><option selected='selected' value='MENSAL'>MENSALISTA</option></select></td></tr>";
                        }
                        table +=   "<tr><td><button id='btn_Save'>Salvar</button></td><td><button id='btn_Del'>Deletar</button></td></tr></table>";
                        table +=   "<form id='frmRefresh' method='POST' action='#'></form>";
                
                        $(document).off('click', '#btn_Save').on('click', '#btn_Save', function() {
                            var cargo = $('#edtCargo').val().trim().toUpperCase();
                            if(cargo != "" && $('#edtSal').val().trim() != ""){
                                var sal = parseFloat($('#edtSal').val());
                                var tipo = $('#selTipo').val();
                                var query = "query=UPDATE tb_cargos SET cargo = '"+ cargo +"', salario = "+ sal +", tipo = '"+ tipo+"' WHERE id = '"+ id_cargo +"';";
                                queryDB(query);  
                                $('#frmRefresh').submit();                               
                            }else{
                                alert('Todos os campos com * são obrigatórios');
                
                            }
                        });

                        $(document).off('click', '#btn_Del').on('click', '#btn_Del', function() {
                            if (confirm('Confirma a exclusão deste cargo?')) {                    
                                var query = "query=DELETE FROM tb_cargos WHERE id = '"+ id_cargo +"';";
                                queryDB(query); 
                                $('#frmRefresh').submit();  
                            }
                        });                
                
                        $(".content").html(table);
                        $('#popTitle').html('Cadastro de Cargos e Funções');    
                    } 
                break;                  
        }

        $(".overlay").css("visibility", "visible").css("opacity", "1");  

    });

    $('#tabChoise').on('dblclick','.tbl_row', function(){ // SELECIONANDO UM ÍTEM DA TABELA (DUPLO CLIQUE)
        var arr = window.location.href.split("/");
        if(arr[arr.length-1] == 'cad_item_ped.php#' || arr[arr.length-1] == 'cad_item_ped.php'){ // CAD ITEM PED - TAB 1
            $('[name="cod_prod"]').val($.trim($(this).children('td').slice(0, 1).text().toUpperCase()));
            $('[name="qtd"]').val($.trim($(this).children('td').slice(3, 4).text().toUpperCase()));
            $('[name="preco"]').val(ClearMoney($.trim($(this).children('td').slice(4, 5).text().toUpperCase())));
//            $('[name="preco"]').val($(this).moeda($.trim($(this).children('td').slice(4, 5).text().toUpperCase())));


            $(document).off('keyup', '#edtEdtQtd').on('keyup', '#edtEdtQtd', function() { // VALIDA OS FORMULARIOS 
                txt = $('#edtEdtQtd').val()
                $("#edtEdtQtd").val($(this).numeros(txt));
            }); 

             $(document).off('keyup', '#edtEdtPreco').on('keyup', '#edtEdtPreco', function() { // VALIDA OS FORMULÁRIOS
                txt = $('#edtEdtPreco').val()
//                $("#edtEdtPreco").val(ClearMoney(txt));
                $("#edtEdtPreco").val($(this).moeda(txt));
            }); 
        }

        if(arr[arr.length-1] == 'feriados.php#' || arr[arr.length-1] == 'feriados.php'){ // CAD ITEM PED - TAB 1

            var id_fer = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
            var dia = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
            var mes = dia.substr(3,2);
            dia = dia.substr(0,2);
            var ano = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
            var nome = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
            var check = '';
            if(ano == 0){
                ano = '2000';
                check = 'checked'
            }
            today = ano+'-'+mes+'-'+dia;

            var table = "<table><tr><td>Descrição *</td><td> <input type='text' maxlength='40' id='edtDesc' value='"+ nome +"'/></td></tr>";
            table +=   "<tr><td>Data </td><td> <input type='date' id='cmbData' class='selData' value='"+ today +"'></td></tr>";
            table +=   "<tr><td> </td><td> <input type='checkbox' id='ckbAnual' name='ckbAnual' value='1' "+check+"><label for='ckbAnual'> Recorrência Anual</label></td></tr>";
            table +=   "<tr><td></td><td><button id='btn_Save'>Salvar</button><button id='btn_Del'>Deletar</button></td></tr></table>";
            table +=   "<form id='frmRefresh' method='POST' action='#'></form>";
    

            $(document).off('click', '#btn_Save').on('click', '#btn_Save', function() {
                var desc = $('#edtDesc').val().trim().toUpperCase();
                if(desc != "" ){
                    var data = $('#cmbData').val();
                    d = data.substr(8,2);
                    m = data.substr(5,2);
                    if ($('#ckbAnual').is(":checked")){
                        a='DEFAULT';
                    }else{
                        a = data.substr(0,4);
                    }           
    
                    var query = "query=UPDATE tb_feriados SET dia="+ d +", mes="+ m +", ano="+ a+", nome='"+ desc+"' WHERE id="+id_fer+";";
                    queryDB(query);   
                    $('#frmRefresh').submit();                               
                }else{
                    alert('Todos os campos com * são obrigatórios');
    
                }
            });    
            
            $(document).off('click', '#btn_Del').on('click', '#btn_Del', function() {
                if (confirm('Deseja remover '+nome+' da lista de feriados?')) {                    
                    var query = "query=DELETE FROM tb_feriados WHERE id="+id_fer+";";
                    queryDB(query);                                              
                    $('#frmRefresh').submit();                               
                }
            });

            $(".content").html(table);
            $('#popTitle').html('Cadastro de Feriados');        
            $(".overlay").css("visibility", "visible").css("opacity", "1");  
        }    

        if(arr[arr.length-1] == 'cad_item_ent.php#' || arr[arr.length-1] == 'cad_item_ent.php'){ // CAD ITEM PED - TAB 1
            $('[name="cod_prod"]').val($.trim($(this).children('td').slice(0, 1).text().toUpperCase()));
            $('[name="qtd"]').val($.trim($(this).children('td').slice(3, 4).text().toUpperCase()));
            $('[name="preco"]').val($(this).moeda($.trim($(this).children('td').slice(4, 5).text().toUpperCase())));
           
            $(document).off('keyup', '#edtEdtQtd').on('keyup', '#edtEdtQtd', function() { // VALIDA OS FORMULARIOS 
                txt = $('#edtEdtQtd').val()
                $("#edtEdtQtd").val($(this).numeros(txt));
            }); 

             $(document).off('keyup', '#edtEdtPreco').on('keyup', '#edtEdtPreco', function() { // VALIDA OS FORMULÁRIOS
                txt = $('#edtEdtPreco').val()
                $("#edtEdtPreco").val($(this).moeda(txt));
            }); 
        }        

        if(arr[arr.length-1] == 'cad_subconj.php#' || arr[arr.length-1] == 'cad_subconj.php'){ // CAD SUB CONJ - TAB 1
            var item_id = $(this).children('td').slice(0, 1).text();
            var qtd = parseFloat($.trim($(this).children('td').slice(4, 5).text().toUpperCase()));
            var cod_item = parseInt($.trim($(this).children('td').slice(1, 2).text().toUpperCase()));
            var preco = parseFloat($(this).moeda($.trim($(this).children('td').slice(5, 6).text().toUpperCase())));
            $('[name="edtCod_item"]').val(cod_item);
            $('[name="edtEdt_Qtd"]').val(qtd);
            
            $(document).off('click', '#btn_alt').on('click', '#btn_alt', function() {
                qtd = parseFloat($('#edtEdt_Qtd').val());
                var query = "query=UPDATE tb_subconj SET qtd = "+ qtd +" WHERE id = "+ item_id +" ;";
                queryDB(query);

            });

            $(document).off('click', '#btn_del').on('click', '#btn_del', function() {
                var query = "query=DELETE FROM tb_subconj WHERE id = "+ item_id +";";
                queryDB(query);
              
            });

        }        

        if(arr[arr.length-1] == 'cad_item_of.php#' || arr[arr.length-1] == 'cad_item_of.php'){ // CAD ITEM OF
            var cod_item = $(this).children('td').slice(0, 1).text();
            var qtd = parseFloat($.trim($(this).children('td').slice(3, 4).text().toUpperCase()));
            var prod_id = parseFloat($.trim($(this).children('td').slice(4, 5).text().toUpperCase()));
            var item_id = parseFloat($.trim($(this).children('td').slice(5, 6).text().toUpperCase()));
            $('[name="edtCod_item"]').val(cod_item);
            $('[name="edtEdt_Qtd"]').val(qtd);
            
            $(document).off('click', '#btn_alt').on('click', '#btn_alt', function() {
                qtd = parseFloat($('#edtEdt_Qtd').val());
                var query = "query=UPDATE tb_item_serv SET qtd = "+ qtd +" WHERE id = "+ item_id +" ;";
                queryDB(query);

            });

            $(document).off('click', '#btn_del').on('click', '#btn_del', function() {
                var query = "query=DELETE FROM tb_item_serv WHERE id = "+ item_id +";";
                queryDB(query);
              
            });

        }     

    });


});
