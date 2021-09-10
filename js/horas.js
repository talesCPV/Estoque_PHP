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

    document.getElementById('btnImprimir').addEventListener('click',(event)=>{
        event.preventDefault();
        const tabela = document.getElementById('tabHoras');
        const rows = tabela.rows;        
//        var imgData = 'data:image/png;base64,'+ btoa('img/logo.png');

        const nome = rows[0].cells[1].innerText;
        const ini = rows[3].cells[0].innerText;
        let fin = '';
        const alt_tab = 60; 

//        console.log(imgData)
        var doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'A4'
          })

        doc.setFontSize(10);
        let alt = 0;
        doc.text(12, alt_tab - 5, 'DATA');
        doc.text(30, alt_tab - 5, '');
        doc.text(40, alt_tab - 5, 'Ent');
        doc.text(52, alt_tab - 5, 'Sai');

        for(let i=3; i<rows.length; i++){
            const linha = alt_tab+(i-3)*5;
            alt = linha+1;
            doc.rect(9,linha - 4,53,5);
            const col = rows[i].cells;
            fin = col[0].innerText;
            doc.text(10, linha, col[0].innerText);
            doc.text(30, linha, col[1].innerText);
            doc.text(40, linha, col[2].innerText);
            doc.text(52, linha, col[3].innerText);
        }

        doc.line(29,alt_tab - 4, 29, alt);
        doc.line(39,alt_tab - 4, 39, alt);
        doc.line(50,alt_tab - 4, 50, alt);

        doc.setFontSize(20);
        doc.text(70, 10, "RELÓGIO DE PONTO");
        doc.line(10,15,200,15);
        doc.setFontSize(10);
        doc.text(90, 20, "Flexibus Sanfonados LTDA.");
        doc.text(80, 25, "Av. Dr. Rosalvo de Almeida Teles, 2070");
        doc.text(70, 30, "CEP: 12.683-010 Caçapava-SP TEL: (12)3653-2230");
//        doc.addImage('img/'+Base64.encode('LOGO.jpg'), 'JPEG', 10, 30, 150, 76);

        doc.line(10,35,200,35);
        doc.text(10, 40, "Nome: "+nome);
        doc.text(10, 45, "Relatório de: "+ini+" a "+fin);
        doc.line(10,50,200,50);

        doc.line(10,285,200,285);

        doc.save('a4.pdf')



    });


    // CLIQUE NA TABELA tabHoras    
    var tbl = document.getElementById("tabHoras");
    $('#tabHoras').on( 'click', 'td', function () {

        var row = $(this).closest("tr").index();
        var col = $(this).closest("td").index();
        var func = tbl.rows[0].cells[Math.ceil(col- (col%2))].innerHTML;
        var data = tbl.rows[row].cells[0].innerHTML;
        var dia  = data.substr(-14,2);
        var mes  = data.substr(-11,2);
        var ano  = data.substr(-8,4);

        let id = tbl.rows[0].cells[Math.ceil(col - (col%2))].id;
//        alert(id)
//        console.log(tbl.rows[0].cells)

        var query = "query=SELECT * FROM tb_hora_extra WHERE id_func = "+id+" AND entrada LIKE '"+ano+'-'+mes+'-'+dia+"%';";
        let myPromise = new Promise(function(resolve, reject) {            
            resolve(queryDB(query));
        });

        myPromise.then((resolve)=>{
//            console.log(resolve)
            if(resolve.length > 0){
                let sai_dia = resolve[0].saida.substr(0,10);
                document.getElementById('data_sai').value = sai_dia;
//                console.log(sai_dia)    
            }                       
        })

        resp = queryDB(query);


        if(col%2 == 0){
            var ent = tbl.rows[row].cells[col].innerHTML;
            var sai = tbl.rows[row].cells[col+1].innerHTML;
        }else{
            var ent = tbl.rows[row].cells[col-1].innerHTML;
            var sai = tbl.rows[row].cells[col].innerHTML;
        }

        ent = ent.trim();
        sai = sai.trim();

        var table = `<table>
            <tr>
                <td>ENTRADA</td><td> <input type='text' maxlength='5' id='edtEntrada' value='${ent}' onkeyup='return hora(this)'/></td>
                <td><input type="date" class="selData" id="data_ent" value="${ano}-${mes}-${dia}" readonly> </td>
            </tr>
            <tr>
                <td>SAIDA</td><td> <input type='text' id='edtSaida' maxlength='5'  value='${sai}' onkeyup='return hora(this)' /></td>
                <td><input type="date" class="selData" id="data_sai" value="${ano}-${mes}-${dia}"> </td>
            </tr>`;
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

            atualiza(tbl,row,col,"0000-00-00 07:00:00","0000-00-00 17:00:00");

            $(".overlay").css("visibility", "hidden").css("opacity", "0");

//            $('#frmRefresh').submit();
            
        });

        $(document).off('click', '#btn_Save').on('click', '#btn_Save', function() {
            
            if($('#edtEntrada').val().length == 5 && $('#edtSaida').val().length == 5){
                let dent = document.getElementById('data_ent').value;
                let dsai = document.getElementById('data_sai').value;

                var ent = document.getElementById('data_ent').value +' '+ $('#edtEntrada').val() +':00' ;
                var sai = document.getElementById('data_sai').value +' '+ $('#edtSaida').val() +':00' ;   

//                var ent = ano+'-'+mes+'-'+dia +' '+ $('#edtEntrada').val() +':00' ;
//                var sai = ano+'-'+mes+'-'+dia +' '+ $('#edtSaida').val() +':00' ;   

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
                

//                console.log(sai)

                var query = "query=SELECT * FROM tb_hora_extra WHERE id_func = "+id+" AND entrada LIKE '"+ano+'-'+mes+'-'+dia+"%';";

                resp = queryDB(query);
                if(resp.length == 0){
                    var query = "query=INSERT INTO tb_hora_extra VALUES (DEFAULT, "+id+",'"+ent+"','"+sai+"');";
                }else{
                    var query = "query=UPDATE tb_hora_extra  SET entrada = '"+ent+"', saida = '"+sai+"'  WHERE id_func = "+id+" AND entrada LIKE '"+ano+'-'+mes+'-'+dia+"%'";
                }
                
//                console.log(query);
                
                queryDB(query);

                atualiza(tbl,row,col,ent,sai)

                $(".overlay").css("visibility", "hidden").css("opacity", "0");
//                $('#frmRefresh').submit();


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


    function atualiza(tbl,row,col,ent,sai){

        const data_ini = document.getElementById(ent);
        const data_fin = document.getElementById(sai);

        if(col%2 == 0){
            tbl.rows[row].cells[col].innerHTML = ent.substr(11,5);
            tbl.rows[row].cells[col+1].innerHTML = sai.substr(11,5);

            tbl.rows[row].cells[col].style.background = "#F2F13B";
            tbl.rows[row].cells[col+1].style.background = "#F2F13B";
        }else{
            tbl.rows[row].cells[col-1].innerHTML = ent.substr(11,5);
            tbl.rows[row].cells[col].innerHTML = sai.substr(11,5);

            tbl.rows[row].cells[col-1].style.background = "#F2F13B";
            tbl.rows[row].cells[col].style.background = "#F2F13B";
        }


    }

});


