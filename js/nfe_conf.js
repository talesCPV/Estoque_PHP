

let btnServ = document.querySelector('.btnServ');
let pedido = "";
let empresa = 0;


const btnSave = document.getElementById("save_nfs");

if(btnSave != null){
	const frmCadNFS = document.getElementById("frmCadNFS");
	btnSave.addEventListener("click",(event)=>{
		event.preventDefault();

		document.querySelector("#popTitle").innerHTML = 'Deseja adicionar um pedido ao sistema?';

		const content = `<table style="width:100%;">
			<tr>
				<td>Pedido Num:</td>
				<td><input type="text" id="edtPed" maxlength="12"/></td>
			</tr>	
		</table>
		
		<button id="btnSim">Sim</button>
		<button id="btnNao">Não</button>	

		
		`;

		document.querySelector(".content").innerHTML = content;

		const btnSim = document.getElementById('btnSim');	
		btnSim.addEventListener('click',()=>{			

			let today = new Date();
			let dd = String(today.getDate()).padStart(2, '0');
			let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
			let yyyy = today.getFullYear();

			today = yyyy + '-' + mm + '-' + dd;

			const ped = document.getElementById('edtPed').value;
			const valor = document.getElementById('nfs_val').value;
			const id_emp = document.getElementById("id_emp").value;
//			const data_exec = document.getElementById("data_exec").value;
			const cond_pagto = document.getElementById("dias_parc").value +" d.d.d.";

			let query = "INSERT INTO tb_pedido ( id_emp, data_ped, data_ent, resp, num_ped, origem, cond_pgto, status) VALUES ('"+id_emp+"', '"+today+"', '"+today+"','SISTEMA', '"+ped+"', 'SAN','"+cond_pagto+"', 'FECHADO')";  

			alert(query)
				
			resp = queryDB(query);

			resp.then(function (response){
				console.log(response);
				if (response.status === 200) { 	
					response.text().then((text)=>{
						query =  "INSERT INTO tb_item_ped (id_prod, id_ped, qtd, preco, und, serv) VALUES (539, (SELECT MAX(id) FROM tb_pedido), 1 ,  '"+valor+"', 'SERV', 'SERV') ";
						resp = queryDB(query);
						resp.then(function (){
							submit();
						});
					});
					
				} else { 
					alert(new Error("Houve algum erro na comunicação com o servidor"));
				} 
	
			});

		});

		const btnNao = document.getElementById('btnNao');
		btnNao.addEventListener('click',()=>{			
			submit();
		});

		document.querySelector('.overlay').style.visibility = "visible";
		document.querySelector('.overlay').style.opacity = 1;	

	
		function submit(){
			const hiddenInput = document.createElement('input');
			hiddenInput.type = 'hidden';
			hiddenInput.name = "save_nfs";
			hiddenInput.value = "save_nfs";
			frmCadNFS.appendChild(hiddenInput);
			frmCadNFS.submit();			
		}


	});
	
}

if(btnServ != null){
	btnServ.addEventListener('click',(event)=>{
		event.preventDefault();
	
		document.querySelector("#popTitle").innerHTML = 'Busca por Serviço';
	
		let content = `<table style="width:100%;">
			<tr>
				<td>Busca por:</td>
				<td>
					<select id="cmbField">
						<option value="cli">Cliente</option>
						<option value="id_emp">Codigo do Cliente</option>
						<option value="pedido">Pedido</option>
					</select>
				</td>
				<td><input type="text" id="edtValue" maxlength="12"/></td>
			</tr>
			<tr>
				<td>Data: </td>
				<td> <input type="date" name="edtData" id="edtData" value=""> </td>
				<td><button id="btnOk">OK</button></td>
	
			</tr>
			<tr>
				<td colspan="3"><textarea id='edtBuscaServ' cols='60' rows='5' ></textarea></td>
			</tr>
			<tr>
				<td><button id="btnAddServ" >Selecionar</button></td>
			</tr>
	
		</table>
	
		
		`;
	
		document.querySelector(".content").innerHTML = content;
	
		const btnOk = document.getElementById('btnOk');
	
		btnOk.addEventListener('click',()=>{
	
	
			const field = document.getElementById('cmbField').value;
			const value = document.getElementById('edtValue').value;
			const data_exec = document.getElementById('edtData').value;
	
			let total = 0;
			let query;
	
			if(field == 'cli'){
	
				query = "SELECT s.data_exec, s.num_carro, s.pedido, s.obs, s.valor, e.nome, s.id_emp FROM tb_serv_exec AS s INNER JOIN tb_empresa AS e ON e.nome LIKE '%"+value+"%' AND e.id=s.id_emp AND data_exec BETWEEN '"+data_exec+"' AND '"+data_exec+"';";
	
			}else if(field == 'id_emp'){
	
				query = "SELECT s.data_exec, s.num_carro, s.pedido, s.obs, s.valor, e.nome, s.id_emp FROM tb_serv_exec AS s INNER JOIN tb_empresa AS e ON s.id_emp= '"+value+"' AND e.id=s.id_emp AND data_exec BETWEEN '"+data_exec+"' AND '"+data_exec+"';";
	
			}else{
				query = "SELECT s.data_exec, s.num_carro, s.pedido, s.obs, s.valor, e.nome, s.id_emp FROM tb_serv_exec AS s INNER JOIN tb_empresa AS e ON s.pedido= '"+value+"' AND e.id=s.id_emp ;";
	
			}

			let resp = queryDB(query);
			resp.then(function (response){
	
				if (response.status === 200) { 
					response.text().then((text)=>{
					if(text.length > 0){
	
						let resp = JSON.parse(text);
	
						resp.forEach((item)=>{
	
							let aux = document.getElementById('edtBuscaServ').innerHTML;
							let linha = " Carro:"+item.num_carro+"\n"+item.obs;
							pedido = item.pedido;
							empresa = item.id_emp;
	
							document.getElementById('edtBuscaServ').innerHTML = aux + linha + '\n\n';
							total += parseFloat(item.valor);
	
						})
						document.getElementById('nfs_val').value = total;
	
							document.getElementById("btnAddServ").addEventListener("click",()=>{
	
								let edtDescServ = document.getElementById('edtDescServ');
								edtDescServ.innerHTML = document.getElementById('edtBuscaServ').innerHTML;
	
								let edtInfoServ = document.getElementById('txt_info');
								edtInfoServ.innerHTML = 'Detalhes do serviço enviados e aprovados por email - Cotação ' + pedido;
	
								document.getElementById("id_emp").value = empresa;
								document.getElementById("data_exec").value = data_exec;	
								document.querySelector('.overlay').style.visibility = "hidden";
								document.querySelector('.overlay').style.opacity = 0;
	
							})
	
							
					}else{
						alert("Nenhum elemento encontrado.")
					}
	
				});
				} else { 
					alert(new Error("Houve algum erro na comunicação com o servidor"));
				} 
	
			});
	
		});
	
	
	
	
		document.querySelector('.overlay').style.visibility = "visible";
		document.querySelector('.overlay').style.opacity = 1;	
	
	});
}


    const btnClose = document.querySelector(".close");

    btnClose.addEventListener("click", ()=>{
        document.querySelector('.overlay').style.visibility = "hidden";
        document.querySelector('.overlay').style.opacity = 0;
	});
	

	async function queryDB(Q){
		const data = new URLSearchParams();
		data.append('query',Q);
		const myRequest = new Request('ajax/ajax.php',{
			method: 'POST',
			body: data
		});
		return await fetch(myRequest);
	}