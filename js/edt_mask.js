// FUNÇÕES DE MÁSCARA PARA INPUTS EM HTML

function int_number(campo){
    var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
    var text = campo.value;
    var out_text = '';
    for(var i = 0; i<text.length; i++){

        if(ok_chr.includes(text.charAt(i))){
            out_text = out_text + text.charAt(i); 
        }

    }
    if(out_text == ''){
        out_text = 0;
    }

    campo.value = parseFloat(out_text);
}

function float_number(campo,casas=2){
    var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
    var text = campo.value;
    var after_dot = 0;
    var out_text = '';
    for(var i = 0; i<text.length; i++){

        if(after_dot > 0){ // conta quantas casas depois da virgula
            after_dot = after_dot + 1;
        }

        if (after_dot <= (casas + 1) ){ // se não passou das casas depois da virgula ( conta o ponto + n digitos)

            if(ok_chr.includes(text.charAt(i))){
                if (after_dot == 0){ // elimina o 0 a equerda
                    out_text = parseFloat(out_text + text.charAt(i));                    
                }else{
                    out_text = out_text + text.charAt(i);
                }
            }
            if((text.charAt(i) == ',' || text.charAt(i) == '.') && after_dot == 0){
                out_text = out_text + '.';
                after_dot = after_dot + 1;
            }
        }

    }
    if(out_text == ''){
        out_text = 0;
    }

    campo.value = out_text;
}


function format_num(campo,casas=2){
    var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
    var text = campo;
    var after_dot = '';
    var before_dot = '';
    var dot = false;
    var out_text = '';
    for(var i=text.length-1; i>=0; i--){

//        alert("->"+text.charAt(i))

        if((text.charAt(i) == ',' || text.charAt(i) == '.')){
            dot =  true;
        }else{

            if(ok_chr.includes(text.charAt(i))){
                if (dot){ // antes da virgula
                    before_dot = text.charAt(i) + before_dot ;                    
                }else{
                    after_dot = text.charAt(i) + after_dot ;                    
                }
            }
            
        }

    }
//    alert('out: '+before_dot+'.'+after_dot);


    if(before_dot == '' && after_dot == ''){
        out_text = 0;
    }else{
        out_text = parseFloat(before_dot+'.'+after_dot).toFixed(casas)
    }

    return out_text;
}

function format_fone(param){ // formata a string no padrão TELEFONE
    var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
    var num = param.value;
    var out = '';
    var count = 0;

    for(i=0;i<num.length;i++){
        if(ok_chr.includes(num.charAt(i))){

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
                out = out.substr(0,5) +"."+out.substr(5,3)+out.substr(9,1)+"-"+out.substr(10,3);
            }		
            out = out + chr;			
        }
    }

    param.value = out;
}

function format_ie(param){ // formata a string no padrão Incrição Estadual
    var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
    var num = param.value;
    var out = '';
    var count = 0;

    for(i=0;i<num.length;i++){
        if(ok_chr.includes(num.charAt(i))){

            chr = num.substring(i,i+1);
            count++;

            if((count-1)%3 == 0 && i !=0){
                out += '.' ;
            }
	
            out = out + chr;			
        }
    }

    param.value = out;
}

function format_cnpj(param){ // formata a string no padrão CNPJ
    var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
    var num = param.value;
    var out = '';
    var count = 0;

    for(i=0;i<num.length;i++){
        if(ok_chr.includes(num.charAt(i))){

            chr = num.substring(i,i+1);
            count++;

            if(count == 3 || count == 6){
                out += '.' ;
            }
            if(count == 9){
                out += '/';
            }
            if(count == 13){
                out += '-';
            }
            out = out + chr;			
        }
    }

    param.value = out;
}

function format_cep(param){ // formata a string no padrão CEP
    var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
    var num = param.value;
    var out = '';
    var count = 0;

    for(i=0;i<num.length;i++){
        if(ok_chr.includes(num.charAt(i))){

            chr = num.substring(i,i+1);
            count++;

            if(count == 3 ){
                out += '.' ;
            }
            if(count == 6){
                out += '-';
            }
            out = out + chr;			
        }
    }

    param.value = out;
}


//************** VALIDAÇÕES DE DADOS **************//


function obrigatorio(param){ // verifica se os campos obrigatorios estao preenchidos (recebe um array com os IDs como param)
	for(i=0;i<param.length;i++){
        obj = document.getElementById(param[i]);
		var val = obj.value;
		if(val == ''){
            event.preventDefault(); // cancela o submit do form;
			alert("Todos os campos com * são obrigatórios.");
			obj.focus();
			return false;
		}
	}
	return true;
}

function verif_senha(param){ // verifica se as senhas coincidem (recebe um array com os IDs como param)
        obj1 = document.getElementById(param[0]);
        obj2 = document.getElementById(param[1]);
		var val1 = obj1.value;
		var val2 = obj2.value;
		if(val1 != val2){
            event.preventDefault(); // cancela o submit do form;
			alert("As senhas não coincidem");
			obj2.focus();
			return false;
		}
	return true;
}

//************** MANIPULAÇÃO DE DADOS **************//


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

function getCookies(name){

    var ca = document.cookie.split(';');
    
	for(i=0; i<ca.length;i++){

        val = ca[i].split('=')    

		if(name.trim() == val[0].trim()){
			return val[1];
		}
	}

}
