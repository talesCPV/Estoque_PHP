<?php
  include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Menu Principal</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/funcoes.js"></script> 
  <style>

    .calendar{
      display : grid;
      grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
      grid-template-rows: 1fr repeat(6, 4fr);
      gap: 20px;
    }

    .dayLabel{

      padding-top : 5px;
      background : #606060;
      color : #FFFFFF;
      text-align: center;
   
    }

    .dayText{
      display: flex;
      align-items: center;
      justify-content: left;
      width : 100%;
      height : 100%;
      /*background : #FFFF00;*/
    }

    .daysquare{
      height : 15vh;
      width : 10vw;
      /*background : #F6F6F6;*/
      cursor : pointer;
      text-align: right;
    }    

  </style>


</head>
<body <?php echo" style='background: {$_SESSION["cor_fundo"]};' " ?> >
  <header>
    <?php
      include "menu.inc";
    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form" id="head">
        <select name="cmbMes" id="cmbMes">
          <option value='0' <?php if(date('m') == 1){ echo 'SELECTED';} ?> >JANEIRO</option>
          <option value='1' <?php if(date('m') == 2){ echo 'SELECTED';} ?> >FEVEREIRO</option>
          <option value='2' <?php if(date('m') == 3){ echo 'SELECTED';} ?> >MARÇO</option>
          <option value='3' <?php if(date('m') == 4){ echo 'SELECTED';} ?> >ABRIL</option>
          <option value='4' <?php if(date('m') == 5){ echo 'SELECTED';} ?> >MAIO</option>
          <option value='5' <?php if(date('m') == 6){ echo 'SELECTED';} ?> >JUNHO</option>
          <option value='6' <?php if(date('m') == 7){ echo 'SELECTED';} ?> >JULHO</option>
          <option value='7' <?php if(date('m') == 8){ echo 'SELECTED';} ?> >AGOSTO</option>
          <option value='8' <?php if(date('m') == 9){ echo 'SELECTED';} ?> >SETEMBRO</option>
          <option value='9' <?php if(date('m') == 10){ echo 'SELECTED';} ?> >OUTUBRO</option>
          <option value='10' <?php if(date('m') == 11){ echo 'SELECTED';} ?> >NOVEMBRO</option>
          <option value='11' <?php if(date('m') == 12){ echo 'SELECTED';} ?> >DEZEMBRO</option>
        </select>
        <input type="number" id="edtAno" name="edtAno" min="2000" max="2100" value="<?php echo date('Y'); ?>">
    
      </div>

    <div class="page_form calendar"id="no_margin"></div>

  </div>

  <div class="overlay">	
  <div class="popup">
    <h2 id="popTitle"></h2>
    <div class="close" >&times</div>
    <div class="content"></div>
  </div>
</div>

</body>

<script>
    const cmbMes = document.querySelector('#cmbMes'); 
    const cmbAno = document.querySelector('#edtAno'); 
    const user_id = localStorage.getItem("user_id");
//    alert(user_id)

//  const usr = dataQuery('ajax/ajax.php',{'query':'select id, user from tb_usuario;'});

    cmbMes.addEventListener('change',()=>{
      openCalendar();
    })

    cmbAno.addEventListener('change',()=>{
      openCalendar();
    })

    openCalendar();

    function openCalendar(){
      const screen = document.querySelector('.calendar');
      const mes = document.querySelector('#cmbMes');
      const ano = document.querySelector('#edtAno');
      const day = new Date;
      
      let row = 0;

      day.setMonth(mes.value);
      day.setYear(ano.value);
      day.setDate(1);
//      console.log(day.getFullYear())
      if(day.getDay() <= 2 ){
        day.setDate(day.getDate() - (7 + day.getDay()));
      }else{
        day.setDate(day.getDate() - day.getDay());
      }

      const endDay = new Date(day);
      endDay.setDate(endDay.getDate() + 41);

      const schedule = busca(day,endDay);

      schedule.then((response)=>{

        let resp = [];
        if(response.trim() != ''){
          resp = JSON.parse(response)
        }

        resp.forEach((sched)=>{
          let dia = sched['data_agd'].split(' ')[0].split('-');
          let element = document.getElementById(dia[2]+'/'+dia[1]);

          if(typeof(element) != 'undefined' && element != null){
            const txt = element.querySelector('.dayText');
            txt.innerHTML = sched['obs'].trim().substr(0,50);
            element.title = sched['obs'].trim();
            element.style.background = '#FFF176';
          }  

        })

     })

      screen.innerHTML=`
        <div class='dayLabel'>Domingo</div>
        <div class='dayLabel'>Segunda</div>
        <div class='dayLabel'>Terça</div>
        <div class='dayLabel'>Quarta</div>
        <div class='dayLabel'>Quinta</div>
        <div class='dayLabel'>Sexta</div>
        <div class='dayLabel'>Sábado</div>`;

      for(let x=0; x<7; x++){
        for(let y=0; y<6; y++){
          const div = document.createElement('div');
          div.classList.add("daysquare");
          div.id = String(day.getDate()).padStart(2, '0')+'/'+String((day.getMonth()+1)).padStart(2, '0');
          div.innerHTML = div.id +`<div class="dayText"></div><div class='data' style='display:none;'>${day.getFullYear()+'-'+String((day.getMonth()+1)).padStart(2, '0')+'-'+String(day.getDate()).padStart(2, '0')+' 00:00:00' }</div>`;
//          div.innerHTML;
          if(day.getMonth() == mes.value){
            div.style = 'background : #F0F0F0;'
          }else{
            div.style = 'background : #E0E0E0;'
          }

          const today = new Date;

          if(day.getDate() == today.getDate() && day.getFullYear() == today.getFullYear() && day.getMonth() == today.getMonth()){
            console.log(day)
            div.style = 'border : solid red 4px;'

          }


          div.addEventListener('click',()=>{
            clickDate(div)
          });

          screen.appendChild(div);
          day.setDate(day.getDate() + 1);

        }
      }   

      function clickDate(square){
        const content = document.querySelector('.content');
        const popTitle = document.querySelector('#popTitle');
        const overlay = document.querySelector('.overlay');

        let query =  'SELECT id, nome FROM tb_usuario; ';

        
        popTitle.innerHTML = square.firstChild.data;
        content.innerHTML = `


        <div class=' page_form' id= "no_margin">
          <select id='cmbUser'></select>
          <textarea rows = '20' id='txtDia' >${square.title} </textarea>
          <button id="btnSalvar" >Salvar</button>
        </div>        
        `;
          

        const usr = dataQuery('ajax/ajax.php',{'query':'select id, user from tb_usuario;'});
        usr.then((response)=>{

          if(response != ''){
            const cmbUser = content.querySelector('#cmbUser');
            const resp = JSON.parse(response)
            for(let i=0; i< resp.length; i++){
              const opt = document.createElement('option');
              opt.innerHTML = resp[i]['user'].toUpperCase();
              opt.value = resp[i]['id'];
              if(user_id == opt.value){
                opt.selected = true;
//                console.log(opt.innerHTML)
              }
              cmbUser.appendChild(opt);
//              console.log(resp[i]);


            }



          }

        })

        overlay.style = ' visibility : visible; opacity : 1;';

        content.querySelector('#btnSalvar').addEventListener('click',()=>{

//          console.log(square.querySelector('.data').innerHTML)
          Save(square.querySelector('.data').innerHTML)

        })

      }
 
    }
    

    function busca(start,end){

      function correctDate(D){
        return String(D.getFullYear()).padStart(4, '0') + '-' + String(D.getMonth() + 1).padStart(2, '0') + '-' + String(D.getDate()).padStart(2, '0')
      }

      const query = `SELECT * FROM tb_calendario where id_user='${user_id}' AND data_agd between '${correctDate(start)}' and '${correctDate(end)}';`;

//        console.log(query)

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
                } else { 
                    reject(new Error("Houve algum erro na comunicação com o servidor"));
                } 
            });
    
        });
    
        return myPromisse;

    }

    function Save(D){
        
      const cmbUser = document.querySelector('#cmbUser');
      let sameUser = true;
      if(user_id != cmbUser.value){
        sameUser = false;      
      }

      function correctDate(D){
        return String(D.getFullYear()).padStart(4, '0') + '-' + String(D.getMonth() + 1).padStart(2, '0') + '-' + String(D.getDate()).padStart(2, '0') +' 00:00:00'
      }
      const txtDia = document.querySelector('#txtDia');
      const usuario = localStorage.getItem("usuario").toUpperCase();

      let query = '';
      if(sameUser){
        if(txtDia.value.trim() == ''){
          query = `DELETE FROM tb_calendario WHERE id_user=${user_id} AND data_agd='${D}' ; `;
        }else{
          query = `INSERT INTO tb_calendario (id_user, data_agd, obs) VALUES(${user_id}, '${D}', '${txtDia.value}')
          ON DUPLICATE KEY UPDATE   obs='${txtDia.value}'; `;
        }

      }else{
        if(txtDia.value.trim() != ''){
          query = `INSERT INTO tb_calendario (id_user, data_agd, obs) VALUES(${cmbUser.value}, '${D}', '***${usuario}***\r\n${txtDia.value}\r\n***************')
          ON DUPLICATE KEY UPDATE   obs= CONCAT(obs, '\r\n***** ${usuario} *****\r\n${txtDia.value}\r\n***************'); `;        
        }
      }
        
//      console.log(query);

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
//                    resolve(response.text());
                  document.querySelector('.overlay').style = ' visibility : hidden; opacity : 0;';
                  location.reload();

                } else { 
                    reject(new Error("Houve algum erro na comunicação com o servidor"));
                } 
            });

        });


//        fetch(myRequest)
     
    }

    </script>    

</html>