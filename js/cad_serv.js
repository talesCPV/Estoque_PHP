

const btnAdd = document.getElementById('btnAdd');
const overlay = document.querySelector('.overlay');

btnAdd.addEventListener('click',(event)=>{
	event.preventDefault();

	document.querySelector("#popTitle").innerHTML = 'Adicione um Serviço';	

	let content = `<table>
		<tr>
			<td>Busca por:</td>
			<td>
				<select id="cmbField">
					<option value="descricao">Descricao</option>
					<option value="cod">Codigo</option>
					<option value="cod_bar">Codigo do Produto</option>
					<option value="cod_cli">Codigo de Barras</option>
				</select>
			</td>
			<td><input type="text" id="edtValue" maxlength="12"/></td>
			<td><button id="btnOk">OK</button></td>
		</tr>
	</table>
	<table id="tabItens" class="search-table"></table>	
	`;


    
	document.querySelector(".content").innerHTML = content;

	const btnOk = document.getElementById('btnOk');

	btnOk.addEventListener('click',()=>{
		const field = document.getElementById('cmbField').value;
		const value = document.getElementById('edtValue').value;

		let query = "SELECT descricao, (CONCAT('R$',FORMAT(preco_comp *(1 + (margem/100)),2))) as preco, unidade, cod  FROM tb_produto WHERE "+field+" LIKE '%"+value+"%' ;";

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

					let resp = JSON.parse(text);

					resp.forEach((item)=>{

	                	const tr = document.createElement('tr');
	                	tr.class = 'tbl_row';

	                	const td_cod = document.createElement('td'); 
	                	td_cod.innerHTML = item.cod;
	                	tr.appendChild(td_cod);

	                	const td_desc = document.createElement('td'); 
	                	td_desc.innerHTML = item.descricao;
	                	tr.appendChild(td_desc);

	                	const td_preco = document.createElement('td'); 
	                	td_preco.innerHTML = item.preco;
	                	tr.appendChild(td_preco);

	                	const td_unidade = document.createElement('td'); 
	                	td_unidade.innerHTML = item.unidade;
	                	tr.appendChild(td_unidade);

						document.getElementById('tabItens').appendChild(tr);

					})

					document.getElementById('tabItens').addEventListener('dblclick',(event)=>{
			            let target = event.target;
			            while (target.nodeName != 'TR') {
			                target = target.parentElement;
			            }
			            let row = target.cells; 

			            let desc = row[1].firstChild ? row[1].firstChild.data.trim() : "" ;
			            let preco = row[2].firstChild ? row[2].firstChild.data.trim() : "" ;
			            let unidade = row[3].firstChild ? row[3].firstChild.data.trim() : "" ;
			            let cod = row[0].firstChild ? row[0].firstChild.data.trim() : "" ;

			            let linha = "1 - "+ desc.toUpperCase().trim() +" - "+ preco + " "+ unidade +" cod-"+ cod;
			            linha = document.getElementById('txt_obs').innerHTML +"\n"+ linha.trim();

			            document.getElementById('txt_obs').innerHTML = linha.trim(); 

				        overlay.style.visibility = "hidden";
				        overlay.style.opacity = 0;

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

	document.getElementById('edtValue').addEventListener('keyup',(key)=>{
		if(key.keyCode == 13){ // ENTER
			btnOk.click();
		}

	});


    overlay.style.visibility = "visible";
    overlay.style.opacity = 1;	

});

    const btnClose = document.querySelector(".close");

    btnClose.addEventListener("click", ()=>{
        overlay.style.visibility = "hidden";
        overlay.style.opacity = 0;
    });