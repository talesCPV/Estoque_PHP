
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

        var cod_int = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
        var nome = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
        var und = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
        var etq = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
        var codprod = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
//        var forn = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
        var preco = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
        var custo = $.trim($(this).children('td').slice(7, 8).text().toUpperCase());
        var margem = $.trim($(this).children('td').slice(8, 9).text().toUpperCase());
        var tipo = $.trim($(this).children('td').slice(9, 10).text().toUpperCase());
        var id = $.trim($(this).children('td').slice(10, 11).text().toUpperCase());

        if(cod_int < 7000 || tipo == 'TINTA_E'){
            var table = "<table><tr><td>Unidade</td><td>"+und+"</td></tr><tr><td>Estoque</td><td>"+etq+"</td></tr><tr><td>Cód. Fab</td><td>"+codprod+"</td></tr><tr><td>Custo</td><td>"+custo+"</td></tr><tr><td>Margem</td><td>"+margem+"%</td></tr><tr><td>Preço</td><td>"+preco+"</td></tr></table>";
        }else{
            var val = parseFloat(preco.substring(2,preco.length-3)+'.'+preco.substring(preco.length-2,preco.length)) ;
            var table = "<table><tr><td>Unidade</td><td>"+und+"</td></tr><tr><td>Estoque</td><td>"+etq+"</td></tr><tr><td>Cód. Fab</td><td>"+codprod+"</td></tr><tr><td>Custo</td><td>"+custo+"</td></tr><tr><td>Margem</td><td>"+margem+"%</td></tr><tr><td>900ml</td><td>R$"+val.toFixed(2)+"</td></tr><tr><td>1.8L</td><td>R$"+(val*2).toFixed(2)+"</td></tr><tr><td>2.7L</td><td>R$"+(val*3).toFixed(2)+"</td></tr><tr><td>Galão</td><td>R$"+(val*4).toFixed(2)+"</td></tr></table>";
        }
        var form = "<form id='frmPesqProd' method='POST' action='edt_prod.php'><input type='hidden' name='cod_prod' value='"+id+"'></form>";
        var Btn = "<br>Acesso apenas p/ consulta<br>";


        if ($(this).perm(classe,'edit')){
            Btn = "<table><tr><td><button id='btnEditar'>Editar</button><button id='btnDeletar'>Deletar</button>";
            
            if(tipo == 'CONJ' || tipo == 'SERVICO' || tipo == 'TINTA_E'){
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

        $(".overlay").css("visibility", "visible").css("opacity", "1");  

    });


     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

});