

let btnServ = document.querySelector('.btnServ');
let pedido = "";
let empresa = 0;

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

//			let query = "SELECT descricao, (CONCAT('R$',FORMAT(preco_comp *(1 + (margem/100)),2))) as preco, unidade, cod  FROM tb_serv_exec WHERE "+field+" LIKE '%"+value+"%' ;";

		}else{
			query = "SELECT s.data_exec, s.num_carro, s.pedido, s.obs, s.valor, e.nome, s.id_emp FROM tb_serv_exec AS s INNER JOIN tb_empresa AS e ON s.pedido= '"+value+"' AND e.id=s.id_emp ;";

		}

//		alert(query);

	    const data = new URLSearchParams();
	    data.append('query',query);

	    const myRequest = new Request('ajax/ajax.php',{
	        method: 'POST',
	        body: data
	    });

        fetch(myRequest)
        .then(function (response){

            if (response.status === 200) { 
                response.text().then((text)=>{

                if(text.length > 0){

//					alert(text)


					let resp = JSON.parse(text);


					resp.forEach((item)=>{

						let aux = document.getElementById('edtBuscaServ').innerHTML;
						let linha = " Carro:"+item.num_carro+"\n"+item.obs;
						pedido = item.pedido;
						empresa = item.id_emp;

						document.getElementById('edtBuscaServ').innerHTML = aux + linha + '\n\n';

//                		console.log(item);
						total += parseFloat(item.valor);

					})
//					document.getElementById('edtBuscaServ').innerHTML = document.getElementById('edtBuscaServ').innerHTML+"\n Total"+ total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
					document.getElementById('nfs_val').value = total;

						document.getElementById("btnAddServ").addEventListener("click",()=>{


							let edtDescServ = document.getElementById('edtDescServ');
							edtDescServ.innerHTML = document.getElementById('edtBuscaServ').innerHTML;

							let edtInfoServ = document.getElementById('txt_info');
							edtInfoServ.innerHTML = 'Detalhes do serviço enviados e aprovados por email - Cotação ' + pedido;

							document.getElementById("id_emp").value = empresa;
//							document.getElementById('lblValor').innerHTML = total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});


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

    const btnClose = document.querySelector(".close");

    btnClose.addEventListener("click", ()=>{
        document.querySelector('.overlay').style.visibility = "hidden";
        document.querySelector('.overlay').style.opacity = 0;
	});
	