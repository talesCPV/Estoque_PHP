$(document).ready(function(){

//	MENU

	$(this).getColor();

	$("#menuCadTinta").click(function(){
		$(".content").load("../html/cadTinta.php");
	});

	$("#menuCadEtq").click(function(){
		$(".content").load("../html/cadEtq.php");
	});	

	$("#menuCadProd").click(function(){
		$(".content").load("../html/cadProd.php");
	});	

	$("#menuCadEmp").click(function(){
		$(".content").load("../html/cadEmp.php");
	});	

	$("#menuCadAgenda").click(function(){
		$(".content").load("../html/cadAgd.php");
	});	

	$("#menuCadUser").click(function(){
		$(".content").load("../html/cadUsr.php");
	});	

	$("#menuPesquisa").click(function(){
		$(".content").load("../html/menuPesq.php");
	});	

// BOTÔES
	$(window).click(function() {
		$('.main_menu ').css({display: 'none'});
	});

	$("#btnIniciar").click(function(){	
		$('.main_menu ').css({display: 'block'});
		event.stopPropagation();

	});

	$("#edtBusca").click(function(){	
		event.stopPropagation();
	});

    $("#edtBusca").keypress(function(e){
        if(e.which == 13){
            var inputVal = $(this).val();
//            alert("You've entered: " + inputVal);
            $('.main_menu ').css({display: 'none'});
        }
    });	

});

//ALTERAÇÕES

$.fn.getCookies = function(name){

	var ca = document.cookie.split(';');

	for(i=0; i<ca.length;i++){

		val = ca[i].split('=')

		if($.trim(name) == $.trim(val[0])){
			return val[1];
		}
	}

}

$.fn.getText = function(path_file){

	dados = 'path=' + path_file;
	resp = '';

	$.ajax({
		url: 'ajax/ajax_getTxt.php',
		type: 'POST',
		dataType: 'html',
		data: dados,
		async: false,
		success: function(data){
			resp = data;
		}
	});
	return resp;
}

$.fn.getColor = function(){

	//"config/".$user."_colors.txt"
	dados = 'path=../config/' + $(this).getCookies('usuario') + '_colors.txt';

	$.ajax({
		url: 'ajax/ajax_getTxt.php',
		type: 'POST',
		dataType: 'html',
		data: dados,
		async: false,
		success: function(data){
			var obj = $.parseJSON(data);
			$('body').css({background: obj[1]});
			$('.top_bar').css({background: obj[0]});
			$('.top_bar_rigth').css({background: obj[0], color: obj[4]});
			$('.top_bar_left').css({background: obj[0]});
			$('.form button, .page_form button').css({background: obj[2]});
			$('.menu li').css({background: obj[3], color: obj[4]});
			$('.menu li a').css({color: obj[4]});
			$('.page_form').css({background: obj[5], color: obj[6]}); //  .search-table tr:nth-child(odd)
			$('.search-table tr:nth-child(odd)').css({background: obj[5], color: obj[6]});
			$('.menu li a').hover( function(){  $(this).css({color: '#ffffff'}) } );
			$('.menu li a').mouseleave( function(){  $(this).css({color: obj[4]}) } );
			$('#tabItens tr, #tabChoise tr').hover( function(){  $(this).css({background: '#606060', color: '#FFFFFF'}) } );			
			$('.search-table tr:nth-child(odd)').mouseleave( function(){ $(this).css({background: obj[5], color: obj[6]} )} );
			$('.search-table tr:nth-child(even)').mouseleave( function(){ $(this).css({background: '#EEE', color: obj[6]} )} );
		}

	});

}



// VALIDAÇÕES

$.fn.numeros = function(param){ // RECEBE UMA STRING E LIMPA TD QUE NÃO FOR NUMERO DELA
	var pos = ['1','2','3','4','5','6','7','8','9','0'];
	var out = '';
	for(i=0;i<param.length;i++){
		chr = param.substring(i,i+1);
		if(jQuery.inArray(chr,pos) != -1){
			out = out + chr;
		}
	}
	return out;
}

$.fn.moeda = function(param){ // RECEBE UMA STRING E LIMPA TD QUE NÃO FOR NUMERO DELA, só deixa 2 casa depois da virgula
	var pos = ['1','2','3','4','5','6','7','8','9','0'];
	var out = '';
	var aft = 0;

	for(i=0;i<param.length;i++){
		chr = param.substring(i,i+1);

		if((chr == ',' || chr == '.') && aft == 0){ // coloca o ponto e começa a contar as casas
			out = out + '.';
			aft++;

		}

		if(jQuery.inArray(chr,pos) != -1 && aft < 3){
			out = out + chr;
			if(aft > 0){
				aft++;
			}
		}		
	}
	return out;
}

$.fn.obrigatorio = function(param){ // verifica se os campos obrigatorios estao preenchidos (recebe um array com o nome dos IDs como param)

	for(i=0;i<param.length;i++){
		var id = "#" + param[i];
		var val = $.trim($(id).val());
		if(val == ''){
			alert("Todos os campos com * são obrigatórios.");
			$(id).focus();
			return false;
		}
	}
	return true;
}

$.fn.cep = function(param){ // formata a string no padrão CEP
	var num = $(this).numeros(param)
	var out = '';
	var count = 0;
	for(i=0;i<num.length;i++){
		chr = num.substring(i,i+1);
		count++;
		if(count == 3){
			out = out + '.';
		}
		if(count == 6){
			out = out + '-';
		}	
		if(count == 9){
			return out;
		}
		out = out + chr;			
		
	}
	return out;
}

$.fn.cnpj = function(param){ // formata a string no padrão CNPJ
	var num = $(this).numeros(param)
	var out = '';
	var count = 0;
	for(i=0;i<num.length;i++){
		chr = num.substring(i,i+1);
		count++;
		if(count == 3 || count == 6){
			out = out + '.';
		}
		if(count == 9){
			out = out + '/';
		}		
		if(count == 13){
			out = out + '-';
		}		
		if(count == 15){
			return out;
		}
		
		out = out + chr;			
	}
	return out;
}

$.fn.telefone = function(param){ // formata a string no padrão TELEFONE
	var num = $(this).numeros(param)
	var out = '';
	var count = 0;

	for(i=0;i<num.length;i++){
		chr = num.substring(i,i+1);
		count++;

		if(count == 1){
			out = '(' + out ;
		}
		if(count == 3){
			out = out + ')';
		}
		if(count == 7){
			out = out + '-';
		}
		if(count == 11){
			out = $(this).celular(num);
			return out;
		}		
		out = out + chr;			
	}

	return out;
}

$.fn.celular = function(param){ // formata a string no padrão Celular
	var num = $(this).numeros(param)
	var out = '';
	var count = 0;

	for(i=0;i<num.length;i++){
		chr = num.substring(i,i+1);
		count++;

		if(count == 1){
			out = '(' + out ;
		}

		if(count == 3){
			out = out + ')';
		}
		if(count == 4){
			out = out + '.';
		}
		if(count == 8){
			out = out + '-';
		}		
		if(count == 12){
			return out;
		}		
		out = out + chr;			
	}

	return out;
}

$.fn.senha = function(param){ // RECEBE UMA STRING E LIMPA TD QUE NÃO FOR NUMERO DELA
		var pass1 = $.trim($("#" + param[0]).val());
		var pass2 = $.trim($("#" + param[1]).val());
		if(pass1 == pass2){
			return true;
		}else{
			alert('Senhas digitadas não conferem, favor, redigitar a mesma senha.');
			$("#" + param[1]).val('');
			$("#" + param[1]).focus();
			return false;
		}
}

$.fn.email = function(param){ // verifica se um email é válido
	var forbiden = [' ','#','$','%','&','*','!','?','/','|'];
	var email = $.trim($("#" + param).val());
	var arroba = false;
	var dot = false;

	for(i=0;i<email.length;i++){
		chr = email.substring(i,i+1);
		if(chr == '@'){
		 	if(i == 0 || arroba){
		 		return false;
		 	}else{
				arroba = true;
		 	}
		}
		if(chr == '.'){
			if(email.substring(i,i+2) == ".@" || email.substring(i-1,i+1) == "@." || email.substring(i,i+2) == ".." || i == 0 || i == email.length-1){
				return false;
		  	}else{
		  		if(arroba){ // esta depois do '@'
					dot = true;	
				}
			}	
		}
		// caracteres proibidos
		if(jQuery.inArray(chr,forbiden) != -1){
			return false;
		}

	}

	return dot;
}

$.fn.checkFinan = function(ref){ // 
    var dados = "query=SELECT * from tb_financeiro WHERE ref = '"+ref+"';";
	var saida = 0;
	$.ajax({
		url: 'ajax/ajax.php',
		type: 'POST',
		dataType: 'html',
		data: dados,
		async: false,		
		success: function(data){
			if(data.length > 0){
				saida = 1;
			}
		}
	});    
	return (saida);
}

$.fn.urlPath = function(ref){ // 

	for(i=ref.length;i>0;i--){
		if(ref[i-1] == '/'){
			return(ref.substring(0,i));
		}
	}

	return;
}

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

function queryDB(query) {        
	var resp = '';
	$.ajax({
		url: 'ajax/ajax.php',
		type: 'POST',
		dataType: 'html',
		data: query,
		async: false,
		success: function(data){
			resp = jQuery.parseJSON( data );
		}
	});   
	return resp;     
}   