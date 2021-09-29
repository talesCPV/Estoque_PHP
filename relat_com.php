<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Comercial</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/edt_mask.js"></script>
</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >
  <style>
    .grid{
      display : grid;
            grid-template-columns: 1fr 4fr 14fr 2fr;
            grid-template-rows: 50px;
            gap: 5px;
    }


    #txtObs{
      margin-top : 15px;

    }

    tr:nth-child(even) {background: #CCC}
    tr:nth-child(odd) {background: #FFF}

    tr:hover{
        cursor: pointer;
        background-color: cornflowerblue;
        color: cornsilk;
    }    

    .big-table > * {
      font-size: 18px;
    }

    .big-table {
      width : 1200px;
      background : red;
    }

    #txtEdit {
      width : 100%;
      height : 200px;
      font-size: 18px;

    }

  </style>


  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Relatório Comercial</p> <br>
  	  	<form class="login-form" method="POST" action="#">
			
        <div class="grid">
          <label> Data</label>
          <input type="date" id="dteCom" class="selData" value="<?php echo date('Y-m-d'); ?>">          
          <input type="text" id="edtContato" readonly>
          <button id="btnBusca" >Busca</button>
        </div>

        <textarea id="txtObs" cols="30" rows="10"></textarea>
        <button id="btnSalvar">Salvar</button>

		
    	</form>
	  </div>
      
    <div class="page_form" id="no_margin">
    <table id="tblComercial"></table>
  </div>


  </div>

<div class="overlay">	
  <div class="popup">
    <h2 id="popTitle"></h2>
    <div class="close" >&times</div>
    <div class="content"></div>
  </div>
</div>

<script>    

    var myData = [];
    loadComercial();

    document.getElementById('btnBusca').addEventListener('click',(event)=>{
      event.preventDefault();
      openHTML('comercial.html','Contato Comercial');    
    })

    document.getElementById('btnSalvar').addEventListener('click',(event)=>{
      event.preventDefault();


      console.log(myData);

      if(myData.length > 0){
        const query = `INSERT INTO tb_comercial (id_agenda, dia, resumo) VALUES ('${myData[5]}', '${document.getElementById('dteCom').value}', '${document.getElementById('txtObs').value}' );`;

        console.log(query);

        const data = new URLSearchParams();
    	    data.append('query',query);

        const myRequest = new Request('ajax/ajax.php',{
	        method: 'POST',
	        body: data
	      });

        const myFetch = fetch(myRequest);


        myFetch.then(()=>{
          alert('Cadastro Efetuado')
          loadComercial();
        })


      }

    })


    function loadComercial(){
      let tblComercial = document.getElementById('tblComercial');

      query = `SELECT A.nome as Nome, E.nome as Empresa, C.dia as Data, A.depart as Departamento, A.email as Email, A.cel1 as Celular, A.cel2 as Telefone, C.resumo as Resumo, C.id as id 
              FROM tb_agenda as A 
              INNER JOIN tb_empresa as E 
              INNER JOIN tb_comercial as C
              ON C.id_agenda = A.id
              AND A.id_emp = E.id
              ORDER BY C.dia DESC `;

              const data = new URLSearchParams();
    	    data.append('query',query);

        const myRequest = new Request('ajax/ajax.php',{
	        method: 'POST',
	        body: data
	      });
        fetch(myRequest)
        .then((response)=>{

            if(response.status == 200){
              response.text().then((json)=>{
                const data = JSON.parse(json);
                console.log(data)
                tblComercial.innerHTML = "<tr><th>Nome</th><th>Empresa</th><th>Data</th><th>Telefone</th><th>Celular</th><th>Resumo</th></tr>";
                for(let i=0; i<data.length; i++){
                  const row = document.createElement('tr');
                    const dia = data[i].Data.substr(8,2) +"/"+data[i].Data.substr(5,2) +"/"+ data[i].Data.substr(0,4)
                  row.innerHTML = `<td>${data[i].Nome.toUpperCase()}</td><td>${data[i].Empresa.toUpperCase()}</td><td">${dia}</td><td>${data[i].Telefone}</td><td>${data[i].Celular}</td><td  style="display:none;">${data[i].Email.toLowerCase()}</td><td>${data[i].Resumo}</td><td style="display:none;>${data[i].Departamento.toUpperCase()}</td><td style="display:none;>${data[i].id}</td>`;
                  row.addEventListener('click',(event)=>{
                      let target = event.target;
                          while (target.nodeName != 'TR') {
                              target = target.parentElement;
                          }
                          const dataRow = target.cells; 

                          console.log(dataRow)

                          document.querySelector(".content").innerHTML = `
                              <table class="big-table">
                                <tr><td>Nome</td><td>${data[i].Nome.toUpperCase()}</td></tr>
                                <tr><td>Empresa</td><td>${data[i].Empresa.toUpperCase()}</td></tr>
                                <tr><td>Data</td><td>${dia}</td></tr>
                                <tr><td>Telefone</td><td>${data[i].Telefone}</td></tr>
                                <tr><td>Celular</td><td>${data[i].Celular}</td></tr>
                                <tr><td>Email</td><td>${data[i].Email.toLowerCase()}</td></tr>
                                <tr><td>Departamento</td><td>${data[i].Departamento.toUpperCase()}</td></tr>
                                <tr><td>Resumo</td><td><textarea id='txtEdit'>${data[i].Resumo}</textarea></td></tr>
                              </table>
                          `;

                          const btnEdit = document.createElement('button');
                            btnEdit.innerHTML = "SALVAR";
                            btnEdit.addEventListener('click',()=>{
                              if (confirm('Deseja editar este registro?')) {
                                query = `UPDATE tb_comercial SET  resumo= '${document.getElementById('txtEdit').value}'  WHERE id = ${dataRow[6].innerHTML}`;

                                const data = new URLSearchParams();
                                    data.append('query',query);

                                const myRequest = new Request('ajax/ajax.php',{
                                  method: 'POST',
                                  body: data
                                });
                                fetch(myRequest)
                                .then(()=>{
                                  document.querySelector('.overlay').style.visibility = "hidden";
                                  document.querySelector('.overlay').style.opacity = "0";
                                  loadComercial();
                                })

                              }
                            })
                            document.querySelector(".content").appendChild(btnEdit)



                            const btnDel = document.createElement('button');
                            btnDel.innerHTML = "Deletar";
                            btnDel.addEventListener('click',()=>{
                              if (confirm('Deseja deletar este registro?')) {
                                query = `DELETE FROM tb_comercial WHERE id = ${dataRow[6].innerHTML}`;

                                    const data = new URLSearchParams();
                                  data.append('query',query);

                                const myRequest = new Request('ajax/ajax.php',{
                                  method: 'POST',
                                  body: data
                                });
                                fetch(myRequest)
                                .then(()=>{
                                  document.querySelector('.overlay').style.visibility = "hidden";
                                  document.querySelector('.overlay').style.opacity = "0";
                                  loadComercial();
                                })

                              }
                            })
                            document.querySelector(".content").appendChild(btnDel)


                          document.querySelector("#popTitle").innerHTML = "Relatório Comercial";
                          document.querySelector(".overlay").style.visibility = "visible";
                          document.querySelector(".overlay").style.opacity = 1;

                    
                  })



                  tblComercial.appendChild(row);
                }

              })
            }
        })


    }

    document.querySelector('.close').addEventListener('click',()=>{
      document.querySelector('.overlay').style.visibility = "hidden";
      document.querySelector('.overlay').style.opacity = "0";
    })

  </script>


</body>
</html>