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
      justify-content: center;
      width : 100%;
      height : 100%;
      /*background : #FFFF00;*/
    }

    .daysquare{
      height : 10vh;
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
<!--
        <input type="date" class="hide-replaced" value="<?php echo date('Y-m-d',mktime(0, 0, 0, date('m') , 1 , date('Y'))); ?>"> 
        <input type="date" name="data_fin" class="selData" value="<?php echo date('Y-m-d',mktime(23, 59, 59, date('m'), date("t"), date('Y'))); ?>"> 
-->      
      </div>

    <div class="page_form calendar"id="no_margin"></div>
    <script>
    //alert(localStorage.getItem("user"));


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

      if(day.getDay() <= 2 ){
        day.setDate(day.getDate() - (7 + day.getDay()));
      }else{
        day.setDate(day.getDate() - day.getDay());
      }

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
          div.innerHTML = day.getDate()+'/'+(day.getMonth()+1)+'<div class="dayText">texto</div>';
          if(day.getMonth() == mes.value){
            div.style = 'background : #F7F7F7;'
          }else{
            div.style = 'background : #F0F0F0;'
          }
          screen.appendChild(div);
          day.setDate(day.getDate() + 1);

        }
      }      
    }
    
    </script>      

  </div>



</body>
</html>