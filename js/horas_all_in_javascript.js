

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
/*
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
*/

    const selStatus = document.getElementById('selStatus');
    const edtValor = document.getElementById('edtValor');


    async function queryDB(query){
        const data = new URLSearchParams();
        data.append('query',query);
        const myRequest = new Request('ajax/ajax.php',{
            method: 'POST',
            body: data
        });
        return await fetch(myRequest);
    }


    const btnBusca = document.getElementById('btnSearch');   

    btnBusca.addEventListener('click',(event)=>{
        event.preventDefault();
        
            if (selStatus.value == "TDS"){
                query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%"+ edtValor.value +"%' AND f.id_cargo = c.id AND c.tipo = 'HORA';";
            }else if (selStatus.value == "ATV"){
                query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%"+ edtValor.value +"%' AND f.status='ATIVO'  AND f.id_cargo = c.id AND c.tipo = 'HORA';";
            }else if (selStatus.value == "DEM"){
                query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%"+ edtValor.value +"%' AND f.status='DEMIT'  AND f.id_cargo = c.id AND c.tipo = 'HORA';";
            }else if (selStatus.value == "MTD"){
                query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%"+ edtValor.value +"%' AND f.id_cargo = c.id AND c.tipo = 'MENSAL';";
            }else if (selStatus.value == "MAT"){
                query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%"+ edtValor.value +"%' AND f.status='ATIVO'  AND f.id_cargo = c.id AND c.tipo = 'MENSAL';";
            }else if (selStatus.value == "MDE"){
                query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%"+ edtValor.value +"%' AND f.status='DEMIT'  AND f.id_cargo = c.id AND c.tipo = 'MENSAL';";
            }else if (selStatus.value == "HMT"){
                query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%"+ edtValor.value +"%' AND f.id_cargo = c.id ;";
            }else if (selStatus.value == "HMA"){
                query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%"+ edtValor.value +"%' AND f.status='ATIVO'  AND f.id_cargo = c.id ;";
            }else if (selStatus.value == "HMD"){
                query =  "SELECT f.id, f.nome FROM tb_funcionario AS f INNER JOIN tb_cargos AS c WHERE f.nome LIKE '%"+ edtValor.value +"%' AND f.status='DEMIT'  AND f.id_cargo = c.id ;";
            }

            let horas;
            let resp = queryDB(query);
            resp.then(function (response){

                if (response.status === 200) { 
                    response.text().then((text)=>{

                    console.log(text);

                    if(text.length > 0){

                        const id_col = [];
    
                        let resp = JSON.parse(text);
                        const tabHoras = document.getElementById('tabHoras');
                        tabHoras.innerHTML = "";
                        const tblRow_names = document.createElement('tr');
                        const tblRow_titles = document.createElement('tr');

                        const tblBlank = document.createElement('th');
                        tblBlank.style.width = "1%";
                        const tblData = document.createElement('th');
                        tblData.style.width = "1%";
                        tblData.innerHTML = "Data";

                        tblRow_names.appendChild(tblBlank);
                        tblRow_titles.appendChild(tblData);

                        resp.forEach((item)=>{
                            id_col.push(item.id);

                            console.log(item)       

                            const tblItem = document.createElement('th');
                            tblItem.style.width = "1%";
                            tblItem.colSpan = "2";

                            const nome = item.nome.split(" ");
                            tblItem.innerHTML = nome[0];
                            tblRow_names.appendChild(tblItem);

                            const tblEnt = document.createElement('th');
                            tblEnt.style.width = "1%";
//                            tblEnt.style.colspan = 1;
                            tblEnt.innerHTML = "Ent.";
                            tblRow_titles.appendChild(tblEnt);

                            const tblSai = document.createElement('th');
                            tblSai.style.width = "1%";
//                            tblSai.style.colspan = 1;
                            tblSai.innerHTML = "Sai.";
                            tblRow_titles.appendChild(tblSai);

                        })
                        
                        tabHoras.appendChild(tblRow_names);
                        tabHoras.appendChild(tblRow_titles);

                        query = `SELECT func.id, he.entrada, he.saida 
                        FROM tb_hora_extra as he 
                        INNER JOIN tb_funcionario as func 
                        ON func.id = he.id_func 
                        AND id_func IN (${id_col}) 
                        AND he.entrada BETWEEN '2021-02-26' AND '2021-03-25' `;


                        alert(query)

                        let resp1 = queryDB(query);
                        resp1.then(function (response){
                            response.text().then((text)=>{
                                horas = JSON.parse(text);

                                const data_ini = document.getElementById('data_ini');
                                const data_fin = document.getElementById('data_fin');

                                let inicio = new Date(data_ini.value);
                                inicio.setDate(inicio.getDate() + 1);
                                let final = new Date(data_fin.value);
                                final.setDate(final.getDate() + 1);

                                for(let i= inicio; i <= final; i.setDate(i.getDate() + 1)){                            
                                    let day = i.getDate().toString().padStart(2, '0');
                                    let month = (i.getMonth() + 1).toString().padStart(2, '0');
                                    let year = i.getFullYear(); 

                                    const tblRow = document.createElement('tr');
                                    if(i.getDay() == 0 || i.getDay() == 6){
                                        tblRow.style.background = "#F5C5C6";
                                    }
                                    const tblItem = document.createElement('th');                            
                                    tblItem.style.width = "1%";
                                    tblItem.innerHTML = day+"/"+month+"/"+year;
                                    tblRow.appendChild(tblItem);


                                    for(let j=0; j<id_col.length; j++){

                                        let e_in = "07:00";
                                        let e_out = "17:00";
                                        const td_ent = document.createElement('td');                            
                                        const td_sai = document.createElement('td');                            

                                        for(let d=0; d<horas.length; d++){

                                            let ent = new Date(horas[d].entrada);
                                            let e_day = ent.getDate().toString().padStart(2, '0');
                                            let e_month = (ent.getMonth() + 1).toString().padStart(2, '0');
                                            let e_year = ent.getFullYear();
                                            let e_hour = ent.getHours().toString().padStart(2, '0');
                                            let e_min = ent.getMinutes().toString().padStart(2, '0');

                                            let sai = new Date(horas[d].saida);
                                            let s_hour = sai.getHours().toString().padStart(2, '0');
                                            let s_min = sai.getMinutes().toString().padStart(2, '0');

//                                            console.log(e_hour+":"+e_min);

                                            if((id_col[j] == horas[d].id) && (day == e_day) && (month == e_month) && (year == e_year) ){
                                                e_in = e_hour+":"+e_min;
                                                e_out = s_hour+":"+s_min;

                                                td_ent.style.background = "#BCD0FE";
                                                td_sai.style.background = "#BCD0FE";
                                            }

                                        }
                                        td_ent.innerHTML = e_in;
                                        td_sai.innerHTML = e_out;
                                        tblRow.appendChild(td_ent);
                                        tblRow.appendChild(td_sai);

//                                        alert([e_in,e_out])
                                    }



                                    tabHoras.appendChild(tblRow);

                                }




                            });
                            
                        });




/*
                        query =  "SELECT * FROM tb_hora_extra  WHERE entrada BETWEEN '"+inicio+"' AND '"+final+"' ";

                        let resp_2 = queryDB(query);
                        resp_2.then(function (response){

                            alert(response)
                        }
*/
                            
                    }else{
                        alert("Nenhum elemento encontrado.")
                    }
    
                });
                } else { 
                    alert(new Error("Houve algum erro na comunicação com o servidor"));
                } 
    
            });




    })


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


    // CLIQUE NA TABELA tabHoras    
    var tbl = document.getElementById("tabHoras");
    $('#tabHoras').on( 'click', 'td', function () {

        var row = $(this).closest("tr").index();
        var col = $(this).closest("td").index();
        var func = tbl.rows[0].cells[Math.ceil((col-1)/2)].innerHTML;
        var data = tbl.rows[row].cells[0].innerHTML;
        var dia  = data.substr(-14,2);
        var mes  = data.substr(-11,2);
        var ano  = data.substr(-8,4);
        let id = tbl.rows[0].cells[Math.ceil((col-1)/2)].id;

//        alert(id)

        if(col%2 == 1){
            var ent = tbl.rows[row].cells[col].innerHTML;
            var sai = tbl.rows[row].cells[col+1].innerHTML;
        }else{
            var ent = tbl.rows[row].cells[col-1].innerHTML;
            var sai = tbl.rows[row].cells[col].innerHTML;
        }

        ent = ent.trim();
        sai = sai.trim();

        var table = "<table><tr><td>ENTRADA</td><td> <input type='text' maxlength='5' id='edtEntrada' value='"+ent+"' onkeyup='return hora(this)'/></td></tr>";
        table +=   "<tr><td>SAIDA</td><td> <input type='text' id='edtSaida' maxlength='5'  value='"+sai+"' onkeyup='return hora(this)' /></td></tr>";
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
            $('#frmRefresh').submit();
            
        });

        $(document).off('click', '#btn_Save').on('click', '#btn_Save', function() {
            
            if($('#edtEntrada').val().length == 5 && $('#edtSaida').val().length == 5){
                var ent = ano+'-'+mes+'-'+dia +' '+ $('#edtEntrada').val() +':00' ;
                var sai = ano+'-'+mes+'-'+dia +' '+ $('#edtSaida').val() +':00' ;   

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
                
                var query = "query=SELECT * FROM tb_hora_extra WHERE id_func = "+id+" AND entrada LIKE '"+ano+'-'+mes+'-'+dia+"%';";

                resp = queryDB(query);
                if(resp.length == 0){
                    var query = "query=INSERT INTO tb_hora_extra VALUES (DEFAULT, "+id+",'"+ent+"','"+sai+"');";
                }else{
                    var query = "query=UPDATE tb_hora_extra  SET entrada = '"+ent+"', saida = '"+sai+"'  WHERE id_func = "+id+" AND entrada LIKE '"+ano+'-'+mes+'-'+dia+"%'";
                }
//                alert(query);
                
                queryDB(query);  
                $('#frmRefresh').submit();


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

});