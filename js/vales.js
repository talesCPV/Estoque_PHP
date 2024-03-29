
function money(text,casas=2){
    var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
    var after_dot = 0;
    var out_text = '';
    for(var i = 0; i<text.length; i++){

        if(after_dot > 0){ // conta quantas casas depois da virgula
            after_dot = after_dot + 1;
        }

        if (after_dot <= (casas + 1) ){ // se não passou das casas depois da virgula ( conta o ponto + n digitos)

            if(ok_chr.includes(text.charAt(i))){
                if (after_dot == 0){ // elimina o 0 a equerda
                    out_text = parseFloat(out_text + text.charAt(i));                    
                }else{
                    out_text = out_text + text.charAt(i);
                }
            }
            if((text.charAt(i) == ',' || text.charAt(i) == '.') && after_dot == 0){
                out_text = out_text + '.';
                after_dot = after_dot + 1;
            }
        }

    }
    if(out_text == ''){
        out_text = 0;
    }

    return out_text;
}

async function queryDB(query){
    const data = new URLSearchParams();
    data.append('query',query);
    const myRequest = new Request('ajax/ajax.php',{
        method: 'POST',
        body: data
    });
    return await fetch(myRequest);
}
 
    const tbl = document.querySelector(".search-table");
    const content = document.querySelector('.content');
    const title = document.querySelector('#popTitle');
    const overlay = document.querySelector('.overlay');
    const btnRecibo = document.getElementById('btnRecibo');

    btnRecibo.addEventListener("click",()=>{

        const query = "SELECT * FROM tb_funcionario ORDER BY nome;";
        let option = '';
        const resp = queryDB(query);
        
        resp.then(function (response){
            if (response.status === 200) { 
                response.text().then((text)=>{
                    var obj = JSON.parse(text); 
                    for(let i=0; i<obj.length; i++){
                        option += `<option value="${obj[i].id}">${obj[i].nome} </option>`;
                    }
                    cmbFunc.innerHTML = option;
                });
            }
        });

        for(i=0;i<resp.length;i++){
            option += "<option value='"+resp[i].id+"'>"+resp[i].cargo+"</option>";
        }

        const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        const dia =  new Date();
        const html = `
            <table>
                <tr>
                    <td> <label> Funcionário </label> </td>
                    <td> <select id="cmbFunc"></select> </td>
                </tr>
                <tr>                    
                    <td> <label> Valor</label> </td>
                    <td> <input type="text" id="edtValor"  onkeyup="return float_number(this)" value="0" style="width:100%; padding:5px"/>
                </tr>
                <tr>                    
                    <td> <label> Referência</label> </td>                     
                    <td> <input type="text"  id="edtRef" value="serviços prestados como autônomo" style="width:100%; padding:5px"/> </td>
                </tr>
                <tr>                    
                    <td> <label> Obs: </label> </td>                     
                    <td> <input type="text"  id="edtPeriodo" value="no período   de   /  /    a    /  /  " style="width:100%; padding:5px"/> </td>
                </tr>
                <tr>                    
                    <td> <label> Data: </label> </td>                     
                    <td> <input type="text"  id="edtData" value="Caçapava ${dia.getDate()} de ${meses[dia.getMonth()]} de ${dia.getFullYear()}" style="width:100%; padding:5px"/> </td>
                </tr>                
                <tr>
                <td></td><td><button id='btn_Rec'>Imprimir</button></td>
                </tr>
            </table>
            <form id='frmRefresh' method='POST' action='#'></form>
        `;

        content.innerHTML = html;
        title.innerHTML = `Recibos de Pagamento `;

        const id = document.getElementById('cmbFunc');
        const edtValor = document.getElementById('edtValor');
        const edtData = document.getElementById('edtData');
        const edtRef = document.getElementById('edtRef');
        const edtPeriodo = document.getElementById('edtPeriodo');
        const btn_Rec = document.getElementById('btn_Rec');

        btn_Rec.addEventListener('click',()=>{  


            window.open (`pdf_vale.php?vale=nao&func=${id.value}&valor=${edtValor.value}&ref=${edtRef.value}&obs=${edtPeriodo.value}&edtData=${edtData.value}`, '_blank');

        });


        overlay.style.visibility = "visible";
        overlay.style.opacity = 1;
    });


    tbl.addEventListener("dblclick",(event)=>{

        let target = event.target;
        while (target.nodeName != 'TR') {
            target = target.parentElement;
        }
        let row = target.cells; 

        let id = (row[0].firstChild == null) ? "" : row[0].firstChild.data ;
        let nome = (row[1].firstChild == null) ? "" : row[1].firstChild.data ;
        let valor = (row[2].firstChild == null) ? "" : money(row[2].firstChild.data) ;
        let obs = (row[3].firstChild == null) ? "" : row[3].firstChild.data ;


        const html = `
            <table>
                <tr>
                    <td> <label> Valor </label> </td>
                    <td> <input type="text"  id="edtValor" onkeyup="return float_number(this)" value="${valor}"/> </td>
                </tr><tr>                    
                    <td> <label> Baixar</label> </td>
                    <td> <input type="text" id="edtBaixa"  onkeyup="return float_number(this)" value="0"/>
                </tr><tr>                    
                    <td> <label> Observação</label> </td>
                    <td> <textarea id="edtObs" rows="6" cols="45" style="resize: none; text-transform: uppercase;">${obs}</textarea>  </td>
                </tr><tr>
                <td></td><td><button id='btn_Save'>Salvar</button> <button id='btn_Vale'>Imprimir</button></td>
                </tr>
            </table>
            <form id='frmRefresh' method='POST' action='#'></form>
        `;
        content.innerHTML = html;

        const btnSave = document.getElementById('btn_Save');
        const btn_Vale = document.getElementById('btn_Vale');
        const edtBaixa = document.getElementById('edtBaixa');
        const edtValor = document.getElementById('edtValor');
        const edtObs = document.getElementById('edtObs');        

        btn_Vale.addEventListener('click',()=>{
            window.open (`pdf_vale.php?vale=sim&func=${id}&valor=${edtValor.value}&baixa=${edtBaixa.value}&obs=${edtObs.value}`, '_blank');
        });


        btnSave.addEventListener('click',()=>{  

            let query = `UPDATE tb_funcionario SET vale = ${parseFloat( edtValor.value)},  obs = '${edtObs.value}' WHERE id=${id};`;

            if(parseFloat( edtBaixa.value) > 0){
                if(confirm(`Confirma a baixa de R$${edtBaixa.value} para ${nome}`)){                    
                    query = `UPDATE tb_funcionario SET vale = ${parseFloat( edtValor.value) - parseFloat( edtBaixa.value)},  obs = '${edtObs.value}' WHERE id=${id};`;
                }
            }

            const resp = queryDB(query);
            
            resp.then(function (response){
                if (response.status === 200) { 
                    response.text().then((text)=>{
                        const frm = document.getElementById('frmRefresh');
                        frm.submit();
//                        window.location = `vale.php`;

                    });
                }
            });


        });

        title.innerHTML = `Registro de Vales - ${nome}`;
        overlay.style.visibility = "visible";
        overlay.style.opacity = 1;

    });

    const btnClose = document.querySelector(".close");
    btnClose.addEventListener("click", ()=>{
        overlay.style.visibility = "hidden";
        overlay.style.opacity = 0;
    });    