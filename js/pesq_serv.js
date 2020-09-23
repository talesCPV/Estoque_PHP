
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
        var cliente = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
        var carro = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
        var data = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
        var func = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
        var obs = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
        var nf = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
        var pedido = $.trim($(this).children('td').slice(7, 8).text().toUpperCase());

        var table = "<table><tr><td>Cod.:</td><td>"+id+"</td></tr><tr><td>Cliente:</td><td>"+ cliente +"</td></tr><tr><td>Data:</td><td>"+data+"</td></tr>";
        table += " <tr><td>Técnicos:</td><td><input type='text' name='func' maxlength='10' id='edtFunc' value='"+func.toUpperCase()+"'/></td></tr><tr><td>Carro:</td><td><input type='text' name='dep' maxlength='15' id='edtCarro' value='"+carro.toUpperCase()+"'/></td></tr>";
        table += " <tr><td>NF:</td><td><input type='text' name='dep' maxlength='10' id='edtNF' value='"+nf.toUpperCase()+"'/></td></tr><tr><td>Pedido:</td><td><input type='text' name='dep' maxlength='15' id='edtPedido' value='"+pedido.toUpperCase()+"'/></td></tr>";
        table += " <tr><td>Obs:</td><td><textarea class='edtTextArea' name='txt_obs' cols='112' rows='2' id='txt_obs' >"+ obs +"</textarea></td></tr></table>";
        var form = "<form id='frmDetalhar' method='POST' action='cad_item_of.php'><input type='hidden' name='cod_serv' value='"+id+"'></form>";
        form +=    "<form id='frmImprimir' method='POST' action='pdf_of.php'><input type='hidden' name='cod_serv' value='"+id+"'></form>";
        form +=    "<form id='frmRefresh' method='POST' action='#'></form>";
        var Btn =  "<table><tr><td><button name='deletar' id='btnDel'>Deletar</button></td><td><button name='salvar' id='btnSal'>Salvar</button></td></tr></table>";

        $(document).off('click', '#btnDel').on('click', '#btnDel', function() {
            if (confirm('Confirma a exclusão deste serviço?')) {
                var query = "query=DELETE FROM tb_serv_exec WHERE id = "+ id +";";
                queryDB(query);
                $('#frmRefresh').submit();    

            }
        }); 

        $(document).off('click', '#btnSal').on('click', '#btnSal', function() {
                var query = "query=UPDATE tb_serv_exec SET num_carro='"+$('#edtCarro').val()+"', nf='"+$('#edtNF').val()+"', pedido='"+$('#edtPedido').val()+"', obs='"+$('#txt_obs').val()+"', func='"+$('#edtFunc').val()+"'  WHERE id = "+ id +";";
                queryDB(query);
                $('#frmRefresh').submit();    
        }); 
        
        $(".content").html(table+form+Btn);
        $('#popTitle').html(cliente + ' - ' + data);
        $(".overlay").css("visibility", "visible").css("opacity", "1");  

    });


     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

});