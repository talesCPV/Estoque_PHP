
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

        var id_of = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
        var num_of = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
        var resp = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
        var func = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
        var tipo = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
        var data = $.trim($(this).children('td').slice(5, 6).text());
        var status = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());

        var table = "<table><tr><td>Cod.:</td><td>"+id_of+"</td></tr><tr><td>Tipo:</td><td>"+ tipo +"</td></tr><tr><td>Emit.:</td><td>"+resp+"</td></tr>";
        table += " <tr><td>Func.:</td><td>"+func+"</td></tr><tr><td>Data.:</td><td>"+data+"</td></tr><tr><td>Status.:</td><td>"+status+"</td></tr></table>";
        var form = "<form id='frmDetalhar' method='POST' action='cad_item_of.php'><input type='hidden' name='cod_serv' value='"+id_of+"'></form>";
        form +=    "<form id='frmImprimir' method='POST' action='pdf_of.php'><input type='hidden' name='cod_serv' value='"+id_of+"'></form>";
        form +=    "<form id='frmRefresh' method='POST' action='#'></form>";
        var Btn =  "<table><tr><td><button name='adicionar' id='btnDet'>Detalhar</button></td><td><button name='imprimir' id='btnImp'>Imprimir</button></td>";

        if(status == "ABERTO"){
            Btn += "<td><button name='deletar' id='btnDel'>Deletar</button></td>";
        }
            Btn += "</tr></table>";
        $(document).off('click', '#btnDet').on('click', '#btnDet', function() {
            $('#popTitle').val()
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

        $(".overlay").css("visibility", "visible").css("opacity", "1");  

    });


     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

});