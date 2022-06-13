<?php
	setcookie("cod_serv");
  	include "valida.inc";
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
    <title>Meus Arquivos e Controles</title>
    <link rel="stylesheet" type="text/css"  href="css/estilo.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- <script src="js/funcoes.js"></script> -->
</head>
<body <?php

    echo" style='background: {$_SESSION["cor_fundo"]};' " ;
    
    if(IsSet($_POST["delete"])){
      unlink("arquivos/".$_POST["file"]);
    }

    
    ?> >
  <header>
    <?php
      include "menu.inc";

      if (IsSet($_FILES["up_pdf"])){
        $file_name = "arquivos/".$_FILES["up_pdf"]["name"];
        copy($_FILES["up_pdf"]["tmp_name"],$file_name);      
      }

    ?>
  </header>
  <div class="page_container">  
  	  <div class="page_form">
  	  	<p class="logo"> Meus Arquivos e Controles</p> <br>

        <table style="width:100%;">
            <form id="frmUpload" method="POST" action="#" enctype='multipart/form-data'>
                <tr>
                    <td><label class="logo"></label></td>
                    <td><input type="file" name="up_pdf" id="up_pdf" /></td>
                    <td><button type='submit' id="btn_Upload">Upload</button></td>
                </tr>
            </form>
        </table>

    </div>

    <div class="page_form" id="no_margin"> 

      <table class="search-table" id="tabItens">
        <tr> <th>Arquivo</th> </tr>

        <?php      

            $dir    = 'arquivos/';
            $files = scandir($dir, 1);
            $i = 0;      

            while($i < count($files) - 2){
                echo ("<tr class='tbl_row' id='{$files[$i]}'> <td>{$files[$i]}</td> </tr>");
                $i += 1;
            }

        ?>                

      </table>

    </div>
  	  
  </div>


<div class="overlay">	
  <div class="popup">
    <h2 id="popTitle"></h2>
    <div class="close" >&times</div>
    <div id="pop-content"></div>
  </div>
</div>

</body>

<script>

    const rows = document.querySelectorAll('.tbl_row')

    document.querySelector('.close').addEventListener('click',()=>{
        document.querySelector('.overlay').style = 'visibility: hidden; opacity: 0;'
    })

    
    function showPop(title, content, script = ''){
        document.querySelector('#popTitle').innerHTML = title
        document.querySelector('#pop-content').innerHTML = content
        document.querySelector('.overlay').style = 'visibility: visible; opacity: 1;'
        script.trim() != '' ? eval(script) : 0
    }

    for(let i=0; i< rows.length; i++){

        rows[i].addEventListener('click',(event)=>{

            const file = event.target.innerHTML
            const row = rows[i]

            let url = window.location.href
            while(url.substr(url.length-1,1) != '/'){
              url = url.substr(0,url.length-1)
            }
            url += 'arquivos/'+file

            const body = `
              <button id='btnShow' >Visualizar</button>
              <button id='btnDel' >Deletar</button>
            `

            const scpt = `
              document.querySelector('#btnShow').addEventListener('click',()=>{                
                window.open('${url}', '_blank');
                document.querySelector('.overlay').style = 'visibility: hidden; opacity: 0;'
              })

              document.querySelector('#btnDel').addEventListener('click',(event)=>{
                if (confirm('Deseja realmente deletar este arquivo?') == true) {

                  const data = new URLSearchParams();        
                    data.append("delete", 'YES');
                    data.append("file", '${file}');

                  const myRequest = new Request("#",{
                      method : "POST",
                      body : data
                  });

                  fetch(myRequest).then(()=>{

//                    window.location.reload();
                    document.querySelector('.overlay').style = 'visibility: hidden; opacity: 0;'
                    document.getElementById('${file}').remove()

//                    console.log('${file}')
//                    console.log(document.getElementById('${file}'))
                  
                  
                  })


                }
              })
              

            `




            showPop('Arquivo '+file,body,scpt)


//            console.log()

        })



    }






</script>


</html>