
$(document).ready(function(){

    $.fn.perm = function(classe, area){ 
        var stay = true;
        var pathname = window.location.pathname.split('/')[2] ; 
        var fileJson = '';    
        dados = 'path=../config/menu.json';
    
        $.ajax({
            url: 'ajax/ajax_getJson.php',
            type: 'POST',
            dataType: 'html',
            data: dados,
            async: false,
            success: function(data){
                fileJson = data;
    
            }
    
        });	
        
        var par = $.parseJSON(fileJson);
        $.each(par['access'], function(index, value) {
            if(pathname == value['url']){
    
                if( $.inArray( parseInt(classe) , value[area] ) !== -1 ){
                    stay = true;
                }else{
                    stay = false;
                }
            }
        });
        return (stay);
    }

    var classe = getCookies('classe');

    if (!$(this).perm(classe,'open')){
        $(window.document.location).attr('href',$(this).urlPath(window.location.href) + 'main.php');
    }


    // DUPLO CLIQUE NA TEBELA tabItens
    $('#tabItens').on('dblclick','.tbl_row', function(){ // SELECIONANDO UM ÍTEM DA TABELA (DUPLO CLIQUE)

        var id = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
        var tipo = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
        var origem = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
        var ref = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
        var emp = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
        var venc = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
        var pgto = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
        var valor = $.trim($(this).children('td').slice(7, 8).text().toUpperCase());

        const obj =  new Object
            obj.id = id
            obj.tipo = tipo
            obj.origem = origem
            obj.ref = ref
            obj.emp = emp
            obj.venc = venc
            obj.pgto = pgto
            obj.valor = valor

        data = obj

        openHTML('anafin.html',tipo+' - '+origem)

/*        

        function addSel(ARR){
            function addOP(V,N){
                const out = document.createElement('option')
                out.value = V
                out.innerHTML = N
                return out
            }
            const sel =  document.createElement('select')
            for(let i=0; i<ARR[0].length; i++){
                sel.appendChild(addOP(ARR[0][i],ARR[1][i]))
            }
            return sel
        }


        function addInput(T,N,V,I){
            const out = document.createElement('input')
            console.log(I)
            if(V != undefined){
                out.value = V
            }
            if(T != undefined){                
                out.type = T
            }
            if(N != undefined){
                out.name = N
            }
            if(I != undefined){
                out.id = I
            }
                return out
        }

        function addTR(C1,C2){
            const tr = document.createElement('tr')

            function addTD(H){
                const td = document.createElement('td')
                    if(typeof H === 'object'){
                        td.appendChild(H)
                    }else{
                        td.innerHTML = H
                    }
                return td
            }

            tr.appendChild(addTD(C1))
            tr.appendChild(addTD(C2))
            return tr
        }


        const frm = document.createElement('form')
            frm.id = 'frmPesqProd'
            frm.method = 'POST'

        frm.appendChild(addInput('hidden','id',id))
        frm.appendChild(addInput('hidden','hidDel',0,'hidDel'))
        
        const tb = document.createElement('table')
        tb.appendChild(addTR('Referência',addInput('text','ref',ref,'edtRef')))
        tb.appendChild(addTR('Tipo',addSel([['ENTRADA','SAIDA'],['a Receber','a Pagar']])))
//        tb.appendChild(addTR('Entrada',addSel([['SAN','FUN'],['Sanfonados','Funilaria e Pintura']])))
        tb.appendChild(addTR('Origem',addSel([['SAN','FUN'],['Sanfonados','Funilaria e Pintura']])))

        tb.appendChild(addTR('Valor',addInput('text','valor',valor,'edtValor')))


        
        frm.appendChild(tb)

        var form = "<form id='frmPesqProd' method='POST' ><input type='hidden' name='id' value='"+id+"'><input type='hidden' name='hidDel' id='hidDel' value='0'>";
        var table = "<table><tr><td>Referência / NF</td><td> <input type='text' name='ref' maxlength='30' id='edtRef' value='"+ref+"' /></td></tr>";
        var Btn = "<br>Acesso apenas p/ consulta<br><br>";

        var opt = [['SAN','FUN'],['IMP','CMP','FIX','PGT']]
        var opt_name = [['Sanfonados','Funilaria e Pintura'],['Impostos','Compras','Custo Fixo','Pagto a Funcionarios']]
        var opt_index = 0

        if(tipo == 'ENTRADA'){            
            table = table +  "<tr><td>Entrada/Saida</td><td> <select name='tipo' id='selTipo'><option value='ENTRADA' selected> A Receber </option><option value='SAIDA'> A Pagar </option> </select></tr>";
            opt_index = 1
        }else{
            table = table +  "<tr><td>Entrada/Saida</td><td> <select name='tipo' id='selTipo'><option value='ENTRADA'> A Receber </option><option value='SAIDA' selected> A Pagar </option> </select></tr>";
        }

        var sel = "<select name='origem' id='selOrig'>"
        for(let i=0; i<opt[opt_index].length; i++){
            sel += `<option value='${opt[i]}' selected> Funilaria e Pintura </option><option value='SAN'> Sanfonados</option> option value='FUN' selected> A Receber </option><option value='OUT'> Outros </option> </select></tr>`;
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
        valor = format_num(valor);
        
        table = table + "<tr><td>Valor R$</td><td> <input type='text' name='valor' maxlength='15' id='edtValor' value='"+ valor +"'  onkeyup='return float_number(this)' /></td></tr>";
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

//        document.querySelector('.content').appendChild(frm)

        $('#popTitle').html(id+'-'+ref+' - '+emp);

        $(".overlay").css("visibility", "visible").css("opacity", "1");  
*/

    });


     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

});