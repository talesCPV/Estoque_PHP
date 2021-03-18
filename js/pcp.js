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

    var classe = getCookies('classe');

    if (!$(this).perm(classe,'open')){
        $(window.document.location).attr('href',$(this).urlPath(window.location.href) + 'main.php');
    }


    // DUPLO CLIQUE NA TEBELA tabItens
    $('#tabItens').on('dblclick','.tbl_row', function(){ // SELECIONANDO UM ÍTEM DA TABELA (DUPLO CLIQUE)
        var dia_sem = $.trim($(this).children('th').slice(0, 1).text().toUpperCase());
        var id = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
        var data = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
        var frente = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
        var suporte = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
        var costura = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
        var montagem = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
        var line = parseInt($.trim($(this).children('td').slice(6, 7).text().toUpperCase())) + 1;

        var form = "<div id='frmPCP' method='POST' ><input type='hidden' name='id' value='"+id+"'><input type='hidden' name='hidDel' id='hidDel' value='0'>";
        var table = "<table><tr><td>Equipe de Frente</td><td> <textarea id=\"txtFrente\" name=\"txtFrente\" rows=\"6\" cols=\"45\" style=\"resize: none; text-transform: uppercase;\">"+ frente +"</textarea>  </td></tr>";
        table = table + "<tr><td>Equipe de Suporte</td><td> <textarea id=\"txtSuporte\" name=\"txtSuporte\" rows=\"6\" cols=\"45\" style=\"resize: none; text-transform: uppercase;\">"+ suporte +"</textarea>  </td></tr>";
        table = table + "<tr><td>Costura</td><td> <textarea id=\"txtCostura\" name=\"txtCostura\" rows=\"6\" cols=\"45\" style=\"resize: none; text-transform: uppercase;\">"+ costura +"</textarea>  </td></tr>";
        table = table + "<tr><td>Montagem</td><td> <textarea id=\"txtMontagem\" name=\"txtMontagem\" rows=\"6\" cols=\"45\" style=\"resize: none; text-transform: uppercase;\">"+ montagem +"</textarea>  </td></tr>";
        var Btn = "<br>Acesso apenas p/ consulta<br><br>";

        //da uma formatada no campo 'valor' deixando só numeros e '.'
       
        form = form + table + '<input type="hidden" name="hdn_data" id="hdn_data" value="'+data+'" ></input><input type="hidden" name="hdn_save" id="hdn_save" value="0" ></input> </div>' ;    
        
        if ($(this).perm(classe,'edit')){

            Btn = "<table><tr><td><button id='btnSalvar'>Salvar</button><button id='btnDeletar'>Deletar</button></td></tr></table>";

            $(document).off('click', '#btnSalvar').on('click', '#btnSalvar', function() {

                let frente = document.getElementById('txtFrente').value;
                let suporte = document.getElementById('txtSuporte').value;
                let costura = document.getElementById('txtCostura').value;
                let montagem = document.getElementById('txtMontagem').value;
                let query =     `query= INSERT INTO tb_pcp VALUES (DEFAULT, '${data}','${frente}','${suporte}','${costura}','${montagem}')
                                ON DUPLICATE KEY UPDATE frente = '${frente}', suporte = '${suporte}', costura = '${costura}', montagem = '${montagem}' ;`;
                queryDB(query);


                let tbl = document.getElementById('tabItens');

                tbl.rows[line].cells[3].innerHTML = frente;
                tbl.rows[line].cells[4].innerHTML = suporte;
                tbl.rows[line].cells[5].innerHTML = costura;
                tbl.rows[line].cells[6].innerHTML = montagem;

                $(".overlay").css("visibility", "hidden").css("opacity", "0");

            });

            $(document).off('click', '#btnDeletar').on('click', '#btnDeletar', function() {
                if (confirm('Deseja remover este registro definitivamente do sistema?')) {
                    let query =     `query= DELETE FROM tb_pcp WHERE data_serv = '${data}' ;`;
                    queryDB(query);

                    let tbl = document.getElementById('tabItens');

                    tbl.rows[line].cells[3].innerHTML = "";
                    tbl.rows[line].cells[4].innerHTML = "";
                    tbl.rows[line].cells[5].innerHTML = "";
                    tbl.rows[line].cells[6].innerHTML = "";

                    $(".overlay").css("visibility", "hidden").css("opacity", "0");

                }
            });
        }

        $(".content").html(form + Btn);
        $('#popTitle').html(dia_sem+' - '+data.substring(8,10) +"/"+data.substring(5,7) +"/"+data.substring(4,0));

        $(".overlay").css("visibility", "visible").css("opacity", "1");  

    });


     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

});