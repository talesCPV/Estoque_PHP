
$(document).ready(function(){

    $('#tabItens').on('dblclick','.tbl_row', function(){
        var id = $.trim($(this).children('td').slice(0, 1).text());
        var desc = $.trim($(this).children('td').slice(1, 2).text());
        var cod = $.trim($(this).children('td').slice(2, 3).text());


        var HTML = "<form id='frmSeleciona' method='POST' action='#'>";
        HTML    += "<button name='campos' id='btnSeleciona' type='submit'></button>";
        HTML    += "<input type='hidden' name='cod_prod' value='"+id+"'\">";
        HTML    += "<input type='hidden' name='desc' value='"+desc+"'\">";
        HTML    += "<input type='hidden' name='pass_desc' value='"+cod+" - "+desc+"'\">";
        HTML    += "</form>"


        $('#div_frm').html(HTML);
        $('#edtDesc').val(desc);
        $('#btnSeleciona').click();

    });

});