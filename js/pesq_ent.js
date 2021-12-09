
function getCookies(name){

	var ca = document.cookie.split(';');

	for(i=0; i<ca.length;i++){

		val = ca[i].split('=')

		if($.trim(name) == $.trim(val[0])){
			return val[1];
		}
	}

}


$(document).ready(function(){

    $.fn.perm = function(classe, area){ 
        var stay = true;
        var pathname = window.location.pathname.split('/')[2] ; 
        var fileJson = '';    
        dados = 'path=../config/menu.json';
    
        $.ajax({
            url: 'ajax/ajax_getJson.php',
            type: 'POST',
            dataType: 'html',
            data: dados,
            async: false,
            success: function(data){
                fileJson = data;
    
            }
    
        });	
        
        var par = $.parseJSON(fileJson);
        $.each(par['access'], function(index, value) {
            if(pathname == value['url']){
    
                if( $.inArray( parseInt(classe) , value[area] ) !== -1 ){
                    stay = true;
                }else{
                    stay = false;
                }
            }
        });
        return (stay);
    }

    var classe = getCookies('classe');

    if (!$(this).perm(classe,'open')){
        $(window.document.location).attr('href',$(this).urlPath(window.location.href) + 'main.php');
    }

    // DUPLO CLIQUE NA TEBELA tabItens
    $('#tabItens').on('dblclick','.tbl_row', function(){ // SELECIONANDO UM ÍTEM DA TABELA (DUPLO CLIQUE)
        var cod = $.trim($(this).children('td').slice(0, 1).text());
        var nf = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
        var obs = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
        var forn = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
        var data = $.trim($(this).children('td').slice(4, 5).text());
        var status = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
        var resp = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
        var have_nf = $.trim($(this).children('td').slice(7, 8).text());
        var path = $.trim($(this).children('td').slice(8, 9).text());
        var e_id = $.trim($(this).children('td').slice(9, 10).text());
        var id = $.trim($(this).children('td').slice(10, 11).text());

        console.log(cod)

        var form = "<form id='frmPesqEnt' method='POST' action='edita_ent.php'> <input type='hidden' name='cod_ent' value='"+cod+"'>";
        form    += "<input type='hidden' name='status' value='"+status+"'>";
        var table = "<table><tr><td>Nota Fiscal:</td><td>"+nf+"</td></tr><tr><td>Fornecedor:</td><td>"+forn+"</td></tr><tr><td>Data:</td><td>"+data+"</td></tr><tr><td>Status:</td><td>"+status+"</td></tr><tr><td>Obs:</td><td><textarea id='txtObs'>"+obs+"</textarea></td></tr><tr><td colspan='2'><button id='btnObs'>Salvar Obs:</button></td></tr>";
        var Btn =  "<table><tr><td><button id='btnVisualizar'>Visualizar</button>";

        if(have_nf == "@"){
            Btn += "<button id='btnVerPDF'>Abrir PDF</button>";
                
            $(document).off('click', '#btnVerPDF').on('click', '#btnVerPDF', function() {
                var out = '';
                var arr = window.location.href.split("/");
                for(i=0; i<arr.length-2; i++){
                    out += arr[i]+'/';
                }
                out += path;
                window.open(out, '_blank');
            });

        }            

        $(document).off('click', '#btnObs').on('click', '#btnObs', function() {
            const obs_txt = document.getElementById('txtObs')

            const data = new URLSearchParams();        
                data.append('query', `UPDATE tb_entrada SET OBS='${obs_txt.value}' WHERE id='${cod}'` );
    
            const myRequest = new Request('ajax/ajax.php',{
                method: 'POST',
                body: data
            });
    
            const myPromisse = new Promise((resolve,reject) =>{    
                fetch(myRequest)
                .then(function (response){
                    if (response.status === 200) { 
                        alert('Obs. Salva com sucesso!')
                        document.location.reload(true);
                    } else { 
                        reject(new Error("Houve algum erro na comunicação com o servidor"));
                    } 
                });
        
            });            



        });            

        $(document).off('click', '#btnVisualizar').on('click', '#btnVisualizar', function() {
            $('#frmPesqEnt').attr('action', 'edita_ent.php');
            $('#frmPesqEnt').submit();
        });

        if ($(this).perm(classe,'edit')){

            Btn += "<button id='btnDeletar'>Deletar</button>";
            
            $(document).off('click', '#btnDeletar').on('click', '#btnDeletar', function() {
                if (confirm('Deseja remover o ítem definitivamente do sistema?')) {
                    $('#frmPesqEnt').attr('action', 'del_ent.php');
                    $('#frmPesqEnt').submit();
                }
            });

            if(status == 'FECHADO'){
                table += "<tr><td colspan='2'><form id='frmUpload' action='upload_nf.php' method='post' enctype='multipart/form-data'>";
                table += "<input type='file' name='up_pdf' accept='.pdf'>";
                table += "<input type='hidden' name='cod' value='"+cod+"'>";
                table += "<input type='hidden' name='eid' value='"+e_id+"'>";
                table += "<input type='hidden' name='destino' value='compra'>";
                table += "<button type='submit' id='btnUpload'>Upload</button></td></form></tr>";
                
                $(document).off('click', '#btnUpload').on('click', '#btnUpload', function() {
                    $('#frmUpload').submit();
                });
    
            }

        }else{
            Btn += "<br>Acesso apenas p/ consulta<br>";
        }

        table += "</table>";
        form  += "</form>";
        Btn   += "</td></tr></table>";

        $(".content").html(table+form+Btn);
        $('#popTitle').html(forn+' NF:'+nf);

        $(".overlay").css("visibility", "visible").css("opacity", "1");  

    });


     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

});