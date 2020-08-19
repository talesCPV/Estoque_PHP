
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

        var cod = $.trim($(this).children('td').slice(0, 1).text());
        var num = $.trim($(this).children('td').slice(1, 2).text());
        var cli = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
        var data = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
        var status = $.trim($(this).children('td').slice(4, 5).text());
        var valor = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
        var have_nf = $.trim($(this).children('td').slice(6, 7).text());
        var path = $.trim($(this).children('td').slice(7, 8).text());

        var table = "<table><tr><td>Cliente:</td><td>"+cli+"</td></tr><tr><td>Data:</td><td>"+data+"</td></tr>"; 
        var Btn = "<table><tr><td>";

        if(status == "COT"){
            table += "<tr><td>Status:</td><td>Cotação</td></tr><tr><td>Valor:</td><td>"+valor+"</td></tr>";
        }else{
            table += "<tr><td>Status:</td><td>Pedido</td></tr>"
            table += "<tr><td>Valor:</td><td>"+valor+"</td></tr>";
          
            table += "</td></tr>";
            $(document).off('click', '#btnUpload').on('click', '#btnUpload', function() {
                $('#frmUpload').submit();
            }); 

            if(have_nf == "@"){
                Btn += "<button id='btnVerPDF'>Abrir PDF</button>";
                    
                $(document).off('click', '#btnVerPDF').on('click', '#btnVerPDF', function() {
                    var out = '';
                    var arr = window.location.href.split("/");
                    for(i=0; i<arr.length-1; i++){
                        out += arr[i]+'/';
                    }
                    out += path;
                    window.open(out, '_blank');

                });
    
            }else{
                Btn += "<button id='btnMarkPG'>Marcar PAGO</button>";
                    
                $(document).off('click', '#btnMarkPG').on('click', '#btnMarkPG', function() {
                    
                    var today = new Date();
                    var dd = String(today.getDate()).padStart(2, '0');
                    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = today.getFullYear();                    
                    today = yyyy + '-' + mm + '-' + dd;                    


                    if(num.trim().toUpperCase() == 'USO INTERNO'){
                        dados = "query=UPDATE tb_pedido  set status ='INTERNO' where num_ped = '"+num+"';";
                    }else{
                        dados = "query=UPDATE tb_pedido  set status ='PAGO', data_ped = '"+ today +"' where num_ped = '"+num+"';";
                    }

//                    alert(dados);
                    queryDB(dados);
                    $('#frmRefresh').submit();
                });

            }         
        }


        var form = "<form id='frmPesqPed' method='POST' action='pdf_analise.php'><input type='hidden' name='cod_ped' value='"+cod+"'><input type='hidden' name='status' value='"+status+"'></form>";
        if ($(this).perm(classe,'edit')){
            table += "<tr><td colspan='2'><form id='frmUpload' action='upload_nf.php' method='post' enctype='multipart/form-data'>";
            if(status != 'COT'){
                table += "<input type='file' name='up_pdf' accept='.pdf'>";
                table += "<input type='hidden' name='cod' value='"+cod+"'>";
                table += "<input type='hidden' name='eid' value='FB'>";                
                table += "<input type='hidden' name='destino' value='venda'>";
                table += "<button type='submit' id='btnUpload'>Upload</button></td></tr>";                                      
            }

            Btn += "<button id='btnAnalisar'>Analisar</button></td><td><button id='btnVisualizar'>Visualizar</button><button id='btnDeletar'>Deletar</button>";           

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
    
                    dados = "query=UPDATE tb_pedido  set status ='"+NF+"' where num_ped = '"+num+"';";
                    queryDB(dados);
                }
            });
        }else{
            Btn += "</tr>Acesso apenas p/ consulta<tr>";

        }

        Btn +="</td></tr></table>";
        table += "</form></table>";
        form += "<form id='frmRefresh' method='POST' action='#'></form>"
        $(".content").html(table+form+Btn);
        $('#popTitle').html(num);

        $(".overlay").css("visibility", "visible").css("opacity", "1");  

    });


     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

});