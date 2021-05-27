
const title = document.querySelector('#popTitle');
const content = document.querySelector('.content');
const overlay = document.querySelector('.overlay');

function money(text,casas=2){
    var ok_chr = new Array('1','2','3','4','5','6','7','8','9','0');
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

    return out_text;
}

function float_number(obj){
    obj.value = money(obj.value);
}


async function queryDB(query){
    const data = new URLSearchParams();
    data.append('query',query);
    const myRequest = new Request('ajax/ajax.php',{
        method: 'POST',
        body: data
    });
    return await fetch(myRequest);
}

function centertext(obj,txt,y) {

    var fontSize = obj.internal.getFontSize();
    var pageWidth = obj.internal.pageSize.getWidth();
    txtWidth = obj.getStringUnitWidth(txt)*fontSize/obj.internal.scaleFactor;
    x = Math.floor(( pageWidth - txtWidth ) / 2);

    if (txtWidth > pageWidth){
        const arr_txt = txt.split(/\n| /);
        let new_txt = '';
        let new_y = y;

        for(let i=0; i<arr_txt.length; i++){
            lineWidth = obj.getStringUnitWidth(new_txt +arr_txt[i] )*fontSize/obj.internal.scaleFactor;
            if(lineWidth > pageWidth - 20 ){
//                alert(new_txt)
                obj.text(10,new_y,new_txt);
                new_txt = '';
                new_y += 5;
            }
            new_txt += " "+arr_txt[i];
        }

        obj.text(10,new_y,new_txt);

    }else{
        obj.text(x,y,txt);
    }

};


document.getElementById('btnGerar').addEventListener('click',(event)=>{
    event.preventDefault();
    const valor = document.getElementById('edtValor');
    const emp = document.getElementById('emp');

    const query = queryDB(`SELECT * from tb_empresa where id=${emp.value};`);

    const data = write_money(valor.value);

//    alert(data)

    query.then(function(response){
        response.text().then((text)=>{
            const obj = JSON.parse(text)[0];
            const today = new Date;
            const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril','Maio', 'Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro']

            console.log(today)
            console.log(obj);

            title.innerHTML = ` <input type="text" id="edtRecibo" value="RECIBO"/> - ${obj.nome}`;
            content.innerHTML = `<textarea rows="10" cols="60" id="edtTexto"> Eu ${obj.nome.toUpperCase()}, inscrito no CPF/CNPJ ${obj.cnpj}, recebi de Flexibus Sanfonados LTDA inscrito no CNPJ 00.519.547/0001-06 a importância de ${data[0]} (${data[1].toUpperCase().trim()}), referente a serviços prestados na data __/__/__
            </textarea><br>
            <input type="text" id="edtData" style="width:100%;" value="Caçapava, ${today.getDate()} de ${meses[today.getMonth()]} de ${today.getFullYear()} "><br>
            <button id="btnImprimir">Imprimir</button>
            `;

            document.getElementById('btnImprimir').addEventListener('click',()=>{

                var doc = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'A4'
                  });
                  
                  doc.setFontSize(20);
                  centertext(doc,document.getElementById('edtRecibo').value.toUpperCase(),60);
                  
                  doc.setFontSize(10);
                  centertext(doc,document.getElementById('edtTexto').value,90);
        
                  centertext(doc,document.getElementById('edtData').value+", __________________________________________",170);


                  doc.setFontSize(10);
                  doc.line(10,15,200,15);
                  centertext(doc,"Flexibus Sanfonados LTDA. CNPJ: 00.519.547/0001-06",20);
                  centertext(doc,"Av. Dr. Rosalvo de Almeida Teles, 2070, bairro Nova Caçapava",25);
                  centertext(doc,"CEP: 12.683-010 Caçapava-SP TEL: (12)3653-2230",30);

                  doc.line(10,35,200,35);

                  doc.line(10,285,200,285);
                  doc.save('a4.pdf')                  

            })

            overlay.style.visibility = "visible";
            overlay.style.opacity = 1;    
        })

    });

//    console.log(query);

})

function write_money(money){

    let mont = '';
    let cents = '';
    let mont_ext = '';
    let cents_ext = '';
    let set = 0;
    let ext = '';

    for(let i=0; i<money.length; i++){

        if(money[i] == "."){
            set = 1;
        }else{
            if(set == 0){
                mont += money[i];
            }else{
                cents += money[i];
            }
        }

    }

    while(cents.length < 2){
        cents += "0";
    }

    mont_ext = extenso(mont);
    cents_ext = extenso(cents);

    ext += mont_ext + " reais";

    if (cents_ext.length > 0){
        ext += " e "+cents_ext + " centavos"

    }


    console.log(ext.toUpperCase());
    
//    alert(ext)


    return ([`R$${mont},${cents}`, ext])


}


function extenso(number){

    const und = ["um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove"];
    const dezena = ["dez","vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa"];
    const dez = ["onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove"];
    const centena = ["cento","duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"];

    let split_num = [];
    let cont = 0;
    let aux = "";
    let ext = "";

    for(let i=number.length-1; i>=0; i--){
        aux = number[i] + aux;
        cont ++;
        if (cont == 3){
            split_num.push(aux);
            aux = "";
            cont = 0;
        }
    }
    if(aux.length > 0){
        split_num.push(aux);
    }


    for(let i=split_num.length-1; i>=0; i--){
        let numero = "";
        let num_cent = split_num[i];
        while(num_cent.length < 3){
            num_cent = "0" + num_cent;
        }


        for(let j=num_cent.length;j>=0;j--){
            let num = parseInt(num_cent[j]);
            let compl = " ";
            if(numero.length > 0){
                compl = " e "
            }
            if(num > 0){
                switch (j) {
                    case 2:
                        numero = und[num-1] + compl + numero;
                        console.log([und[num-1],num_cent[j]])
                        break;
                    case 1:

                        numero = dezena[num-1] + compl + numero;
                        break;
                    case 0:      
                        numero = centena[num-1] + compl + numero;
                        break;
                  }
            }

        }

        ext += numero

        if(i == 1){
            ext += "mil ";

        }

//        alert(numero);

    }

    if(ext == "cento "){
        ext = "cem "
    }

    return ext.trim();
}


    const btnClose = document.querySelector(".close");
    btnClose.addEventListener("click", ()=>{
        overlay.style.visibility = "hidden";
        overlay.style.opacity = 0;
    });    

    