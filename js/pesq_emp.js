
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

        $(".overlay").css("visibility", "visible").css("opacity", "1");  

    });


     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

});