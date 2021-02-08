

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

    function getCookies(name){

        var ca = document.cookie.split(';');
        
        for(i=0; i<ca.length;i++){
    
            val = ca[i].split('=')    
    
            if(name.trim() == val[0].trim()){
                return val[1];
            }
        }
    
    }

    var classe = getCookies('classe');

    if (!$(this).perm(classe,'open')){
        $(window.document.location).attr('href',$(this).urlPath(window.location.href) + 'main.php');
    }


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
        let id = tbl.rows[0].cells[Math.ceil((col-1)/2)].id;

//        alert(id)

        if(col%2 == 0){
            var ent = tbl.rows[row].cells[col].innerHTML;
            var sai = tbl.rows[row].cells[col+1].innerHTML;
        }else{
            var ent = tbl.rows[row].cells[col-1].innerHTML;
            var sai = tbl.rows[row].cells[col].innerHTML;
        }

        ent = ent.trim();
        sai = sai.trim();

        var table = "<table><tr><td>ENTRADA</td><td> <input type='text' maxlength='5' id='edtEntrada' value='"+ent+"' onkeyup='return hora(this)'/></td></tr>";
        table +=   "<tr><td>SAIDA</td><td> <input type='text' id='edtSaida' maxlength='5'  value='"+sai+"' onkeyup='return hora(this)' /></td></tr>";
        if ($(this).perm(classe,'edit')){
            table +=   "<tr><td></td><td><button id='btn_Save'>Salvar</button><button id='btn_Limpar'>Limpar</button></td></tr></table>";
        }else{
            table +=   "<tr><td></td><td>Acesso apenas p/ consulta</td></tr></table>";
        }
        table +=   "<form id='frmRefresh' method='POST' action='#'></form>";

        $(document).off('click', '#btn_Limpar').on('click', '#btn_Limpar', function() {
//            var query = "query=DELETE FROM tb_hora_extra  WHERE id_func = (SELECT id from tb_funcionario WHERE nome LIKE '"+ func +"%' ) AND entrada LIKE '"+ano+'-'+mes+'-'+dia+"%'";
            let query = "query=DELETE FROM tb_hora_extra  WHERE id_func = "+ id +" AND entrada LIKE '"+ano+'-'+mes+'-'+dia+"%'";
            queryDB(query);
            $('#frmRefresh').submit();
            
        });

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
                
                var query = "query=SELECT * FROM tb_hora_extra WHERE id_func = "+id+" AND entrada LIKE '"+ano+'-'+mes+'-'+dia+"%';";

                resp = queryDB(query);
                if(resp.length == 0){
                    var query = "query=INSERT INTO tb_hora_extra VALUES (DEFAULT, "+id+",'"+ent+"','"+sai+"');";
                }else{
                    var query = "query=UPDATE tb_hora_extra  SET entrada = '"+ent+"', saida = '"+sai+"'  WHERE id_func = "+id+" AND entrada LIKE '"+ano+'-'+mes+'-'+dia+"%'";
                }
//                alert(query);
                
                queryDB(query);  
                $('#frmRefresh').submit();


            }else{
                alert('Horarios incompletos')
            }
        });

        $(".content").html(table);
        $('#popTitle').html(func+" - "+data);        
        $(".overlay").css("visibility", "visible").css("opacity", "1");  
    });


     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

});