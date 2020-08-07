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

function phone(param){ // formata a string no padrão TELEFONE
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