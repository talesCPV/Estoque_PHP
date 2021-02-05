

    const btnNovoCargo = document.getElementById("btn_NovoCargo");
    const table = document.querySelector('#tabItens');;
//    const rowTable = table.rows;

    const btnClose = document.querySelector(".close");

    btnClose.addEventListener("click", ()=>{
        document.querySelector(".overlay").style.visibility = "hidden";
        document.querySelector(".overlay").style.opacity = 0;
    });

    function screen(cod=0,cargo="",sal="",tipo="HORISTA", cbo=""){

        let form = "<table><tr><td>Cargo *</td><td> <input type='text' name='cargo' maxlength='40' id='edtCargo' value='"+cargo+"'/></td></tr>";
        form +=   "<tr><td>Salário * R$</td><td> <input type='text' name='edtSal' id='edtSal' onkeyup='return money(this)' value='"+sal+"' /></td></tr>";
        form +=   "<tr><td>CBO</td><td> <input type='text' name='edtCBO' id='edtCBO' value='"+cbo+"' /></td></tr>";
        if(tipo == 'HORISTA'){
            form +=   "<tr><td>Tipo</td><td><select name='tipo' id= 'selTipo'> <option value='HORA' selected>HORISTA</option><option value='MENSAL'>MENSALISTA</option></select></td></tr>";
        }else{
            form +=   "<tr><td>Tipo</td><td><select name='tipo' id= 'selTipo'> <option value='HORA'>HORISTA</option><option value='MENSAL' selected>MENSALISTA</option></select></td></tr>";
        }
//        table +=   "<tr><td></td><td><button id='btn_Save'>Salvar</button></td></tr></table>";
        form +=   "<form id='frmRefresh' method='POST' action='#'></form>";


        let btnSave = document.createElement('button');
        btnSave.innerHTML = "Salvar";
        btnSave.addEventListener("click",(event)=>{
            let cargo = document.getElementById('edtCargo').value;
            let sal = document.getElementById('edtSal').value;
            let tipo = document.getElementById('selTipo').value;
            let cbo = document.getElementById('edtCBO').value;
            let query;
            if(cargo.trim() != "" && sal.trim() != ""){
                if (cod != 0){
                    query = "UPDATE tb_cargos SET  cargo='"+cargo+"', salario="+sal+", tipo='"+tipo+"', cbo='"+cbo+"' WHERE id="+cod+" ;"; 
                }else{
                    query = "INSERT INTO tb_cargos VALUES (DEFAULT, '"+ cargo +"', "+ sal +", '"+ tipo+"', '"+cbo+"');";
                }
                sendFetch(query);
            }else{
                alert('Todos os campos com * são obrigatórios');                
            }

        });

        let btnDel = document.createElement('button');
        btnDel.innerHTML = "Deletar";
        btnDel.addEventListener("click",(event)=>{
            if(confirm("Deseja realmente deletar o registro?")){
                query = "DELETE FROM tb_cargos WHERE id="+cod+" ;"; 
                sendFetch(query);
            }
        });


        document.querySelector(".content").innerHTML = form;
        document.querySelector(".content").appendChild(btnSave);
        if (cod != 0){
            document.querySelector(".content").appendChild(btnDel);
        }
        document.querySelector("#popTitle").innerHTML = 'Cadastro de Cargos e Funções';
        document.querySelector(".overlay").style.visibility = "visible";
        document.querySelector(".overlay").style.opacity = 1;

    }

    btnNovoCargo.addEventListener("click",()=>{
        screen();
    });

    table.addEventListener("dblclick",(event)=>{

        let target = event.target;
        while (target.nodeName != 'TR') {
            target = target.parentElement;
        }
        let row = target.cells; 

        let cod = row[0].firstChild ? row[0].firstChild.data : "" ;
        let cargo = row[1].firstChild ? row[1].firstChild.data : "" ;
        let cbo = row[2].firstChild ? row[2].firstChild.data : "" ;
        let tipo = row[3].firstChild ? row[3].firstChild.data : "" ;
        let salario = row[4].firstChild ? number(row[4].firstChild.data) : "" ;

        screen(cod,cargo,salario,tipo,cbo);

    });

    function sendFetch(query){

        const data = new URLSearchParams();
        data.append('query', query);
    
        const myRequest = new Request('ajax/ajax.php',
            {
                method: 'POST',
                body: data
            });
    
        fetch(myRequest)
          .then(function(response){
            response.text().then(function (text) {
                let frm = document.getElementById('frmRefresh');
                frm.submit();
            });
    
        });
    
    }



    function number (param){ // RECEBE UMA STRING E LIMPA TD QUE NÃO FOR NUMERO DELA
        var pos = ['1','2','3','4','5','6','7','8','9','0'];
        var out = '';
        for(i=0;i<param.length;i++){
            chr = param.substring(i,i+1);
            if(jQuery.inArray(chr,pos) != -1){
                out = out + chr;
            }
        }
        return out.substr(0,out.length-2)+"."+out.substr(out.length-2,out.length);
    }