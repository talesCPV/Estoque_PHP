

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
    
    $("#btnImpTxt").click(function(event){
        event.preventDefault();
        let id = $('#selTxt').val();
        let query = "SELECT * FROM tb_texto_nf WHERE id = "+id+";";
        let resp = queryDB(query);
        resp.then((response)=>{
            response.text().then(function (text) {
                let texto = JSON.parse(text)[0].texto;
//                console.log(texto)

                let replace_text = "";
                for (i=0; i<texto.length; i++){            
                    if(texto[i] == '{'){
                        if(texto.substring(i+1,i+4).toUpperCase() == 'ICM'){
                            replace_text += $('#icms').val().trim();
                            i += 4;
                        }
                        if(texto.substring(i+1,i+4).toUpperCase() == 'PED'){
                            replace_text += $('#pedido').val().trim();
                            i += 4;
                        }
                        if(texto.substring(i+1,i+4).toUpperCase() == 'ALI'){
                            replace_text += $('#aliquota').val().trim();
                            i += 4;
                        }
                    }else{
                        replace_text += texto[i];
                    }
                }

                document.getElementById("txt_comp").value = replace_text;
            });
        })

    });
    $("#btnAddTxt").click(function(event){
        event.preventDefault();
        let query = "query=SELECT * FROM tb_texto_nf;";
        let resp = queryDB(query);
        let table = "<table class='search-table' id='tabItens'>";
        table +=   "<tr> <th>Cod</th> <th>Desc.</th> <th>Texto</th> </tr>";

        let index = 0;
        while(index < resp.length){
            table +=   "<tr class='tbl_row'><td>"+resp[index]['id']+"</td><td>"+resp[index]['nome']+"</td><td>"+resp[index]['texto']+"</td></tr>";
            index += 1;
          }
        table +=   "</table><table>";
        table +=   "<tr><td>Cod.</td><td><input type='text' size='5' id='id_txt'</td></tr>";
        table +=   "<tr><td>Nome</td><td><input type='text' size='100' id='nome_txt'></td></tr>"; 
        table +=   "<tr><td>Texto</td><td><textarea class='edtTextArea' id='texto_txt' cols='100' rows='5'></textarea></td></tr>";          
        table +=   "<tr><td></td><td><button id='btnSalvar'>Salvar</button><button id='btnDel'>Deletar</button> </td></tr>";
        table +=   "</table>";      
        table +=   "<form id='frmRefresh' method='POST' action='#'></form>";

        $(document).off('click', '#btnSalvar').on('click', '#btnSalvar', function() {
            let cod = $('#id_txt').val().trim();
            let nome = $('#nome_txt').val().trim();
            let texto = $('#texto_txt').val().trim();
            if(cod == ''){
                query = "query=INSERT INTO tb_texto_nf (nome, texto) VALUES ('"+nome+"','"+texto+"') ;";
            }else{
                query = "query=UPDATE tb_texto_nf SET nome = '"+nome+"', texto = '"+texto+"' WHERE id = "+cod+" ;";
            }
            queryDB(query);
            $('#frmRefresh').submit();

        });

        $(document).off('click', '#btnDel').on('click', '#btnDel', function() {
            let cod = $('#id_txt').val().trim();
            if(cod == ''){
                alert('Precisa selecionar um texto.')
            }else{
                if (confirm('Deseja remover definitivamente este texto de NF?')) {
                    query = "query=DELETE FROM tb_texto_nf WHERE id = "+cod+" ;";
                    queryDB(query);
                    $('#frmRefresh').submit();
                }
            }



        });

        $(document).off('dblclick', '.tbl_row').on('dblclick', '.tbl_row', function() {
            var cod = $.trim($(this).children('td').slice(0, 1).text());
            var nome = $.trim($(this).children('td').slice(1, 2).text());
            var texto = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
            $('#id_txt').val(cod);
            $('#nome_txt').val(nome);
            $('#texto_txt').val(texto);
            
        });

        $(".content").html(table);
        $('#popTitle').html('Textos da NF');
        $(".overlay").css("visibility", "visible").css("opacity", "1");  
    });

     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

});