
const cliente = document.getElementById('cli');
const dt =  document.getElementById('edtData');
const num_carro = document.getElementById('edtNum');
const func = document.getElementById('edtEquipe');
const local = document.getElementById('cmbLocal');
const serv = document.getElementById('txt_obs');
const valor = document.getElementById('edtValor');
const btnCad = document.getElementById('btnCad');
const overlay = document.querySelector('.overlay');


btnCad.addEventListener('click',(event)=>{
	event.preventDefault();
    if(obrigatorio(['edtNum', 'edtEquipe', 'txt_obs'])){

        const query = `INSERT INTO tb_analise_frota  (id_emp, data_analise, num_carro, func, local, valor, obs)
                       VALUES (${cliente.value}, '${dt.value}', '${num_carro.value}', '${func.value}', '${local.value}', ${valor.value}, '${serv.value}');`;

        console.log(query)

        const data = new URLSearchParams();        
        data.append("query", query);

        const myRequest = new Request('ajax/ajax.php',{
            method: 'POST',
            body: data
        });
    
        const myPromisse = new Promise((resolve,reject) =>{
    
            fetch(myRequest)
            .then(function (response){
    
                if (response.status === 200) { 
                    resolve(response.text());
                    num_carro.value = "";
                    serv.value = "";
                    valor.value = "0.00";
                    num_carro.focus();

                } else { 
                    reject(new Error("Houve algum erro na comunicação com o servidor"));
                } 
    
            });
    
        });
/*    
        myPromisse.then((response)=>{
            console.log('RESPONSE=>'+response);
        })
*/


    }
});

    const btnClose = document.querySelector(".close");

    btnClose.addEventListener("click", ()=>{
        overlay.style.visibility = "hidden";
        overlay.style.opacity = 0;
    });