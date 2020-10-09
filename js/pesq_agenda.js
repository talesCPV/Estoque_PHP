
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

    $.fn.show_popup = function(id,nome,dep,email,cel,fone,emp,title,opt){ 


        var query = "query=SELECT id, nome FROM tb_empresa;";
        var resp = queryDB(query);
        var option = '';
        for(i=0;i<resp.length;i++){
            sel = '';
            
            if(emp.trim() == resp[i].nome.trim()){
                sel = 'selected';
            }

            option += "<option value='"+resp[i].id+"'"+sel+">"+resp[i].nome.trim()+"</option>";
        }


        var form = "<form id='frmPesqAgenda' method='POST' ><input type='hidden' name='id' value='"+id+"'><input type='hidden' name='hidDel' id='hidDel' value='0'>";
        var table = "<table><tr><td>Nome</td><td> <input type='text' name='nome' maxlength='40' id='edtNome' value='"+nome.toUpperCase()+"' /></td></tr>";

        table +=   "<tr><td>Empresa</td><td><select name='emp' id= 'edtEmp'>"+ option +"</select></td></tr>";

//        table = table  +   "<tr><td>Empresa</td><td> <input type='text' name='emp' maxlength='80' id='edtEmp' value='"+emp.toUpperCase()+"' readonly /></td></tr>";
        table = table  +   "<tr><td>Departamento</td><td> <input type='text' name='dep' maxlength='15' id='edtDep' value='"+dep.toUpperCase()+"'  /></td></tr>";
        table = table  +   "<tr><td>Email</td><td> <input type='text' name='email' maxlength='80' id='edtEmail' value='"+email.toLowerCase()+"'  /></td></tr>";
        table = table  +   "<tr><td>Cel</td><td> <input type='text' name='fone1' maxlength='15' id='edtCel' value='"+cel+"' onkeyup='return format_fone(this)' /></td></tr>";
        table = table  +   "<tr><td>Telefone</td><td> <input type='text' name='fone2' maxlength='15' id='edtFone' value='"+fone+"' onkeyup='return format_fone(this)' /></td></tr></table>";

        form = form + table + "</form>";
//                var form = "<form id='frmPesqAgenda' method='POST' action='edt_agenda.php'><input type='hidden' name='cod_prod' value='"+id+"'></form>";
        var Btn = "<br>Acesso apenas p/ consulta<br>";

        if ($(this).perm(classe,'edit')){
        
            Btn = "<table><tr><td><button id='btnEditar' onclick='return obrigatorio(['edtNome'])'>Salvar</button><button id='btnDeletar'>Deletar</button></td></tr></table>";

            $(document).off('click', '#btnEditar').on('click', '#btnEditar', function() {
                $('#hidDel').val(opt);
                $('#frmPesqAgenda').attr('action', 'save_agenda.php');
                $('#frmPesqAgenda').submit();
            });

            $(document).off('click', '#btnDeletar').on('click', '#btnDeletar', function() {
                $('#hidDel').val('1');
                if (confirm('Deseja remover o ítem definitivamente do sistema?')) {
                    $('#frmPesqAgenda').attr('action', 'save_agenda.php');
                    $('#frmPesqAgenda').submit();
                }
            });
        }

        $(document).off('keyup', '#edtCel').on('keyup', '#edtCel', function() {
            var val= $(this).val();
            $('#edtCel').val( $(this).celular(val));
        });

        $(document).off('keyup', '#edtFone').on('keyup', '#edtFone', function() {
            var val= $(this).val();
            $('#edtFone').val( $(this).telefone(val));
        });

        $(".content").html(form+Btn);
        $('#popTitle').html(title);

        $(".overlay").css("visibility", "visible").css("opacity", "1");          

    }


    var classe = getCookies('classe');

    if (!$(this).perm(classe,'open')){
        $(window.document.location).attr('href',$(this).urlPath(window.location.href) + 'main.php');
    }


     // BTN Novo
     $('#btnNovo').click(function(){ // BOTÃO NOVO        
        event.preventDefault();
       
        $(this).show_popup('DEFAULT','','','','','','','Novo Usuário','0');


    }); 


    // DUPLO CLIQUE NA TEBELA tabItens
    $('#tabItens').on('dblclick','.tbl_row', function(){ // SELECIONANDO UM ÍTEM DA TABELA (DUPLO CLIQUE)

        var id = $.trim($(this).children('td').slice(0, 1).text().toUpperCase());
        var nome = $.trim($(this).children('td').slice(1, 2).text().toUpperCase());
        var dep = $.trim($(this).children('td').slice(2, 3).text().toUpperCase());
        var email = $.trim($(this).children('td').slice(3, 4).text().toUpperCase());
        var cel = $.trim($(this).children('td').slice(4, 5).text().toUpperCase());
        var fone = $.trim($(this).children('td').slice(5, 6).text().toUpperCase());
        var emp = $.trim($(this).children('td').slice(6, 7).text().toUpperCase());
//                var table = "<table><tr><td>Nome:</td><td>"+nome+"</td></tr><tr><td>Empresa:</td><td>"+emp+"</td></tr><tr><td>Depart.:</td><td>"+dep+"</td></tr><tr><td>Email</td><td>"+email+"</td></tr><tr><td>Celular:</td><td>"+cel+"</td></tr><tr><td>Telefone:</td><td>"+fone+"</td></tr></table>";

        $(this).show_popup(id,nome,dep,email,cel,fone,emp,nome,'2');


    });


     // POPUP CLOSE
     $('.close').click(function(){ // BOTÃO FECHAR DO POPUP
        $(".overlay").css("visibility", "hidden").css("opacity", "0");

    }); 

});