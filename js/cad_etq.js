
$(document).ready(function(){

//var id = $(this).attr('id');

    $('#tabItens').on('dblclick','.tbl_row', function(){
        var id = $.trim($(this).children('td').slice(0, 1).text());

        $('#selBusca').val('cod');
        $('#edtBusca').val(id);
        $('#botao_inline').trigger('click');
    });

});