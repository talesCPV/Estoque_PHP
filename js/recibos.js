
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
        const arr_txt = txt.split(" ");
        let new_txt = '';
        let new_y = y;

        for(let i=0; i<arr_txt.length; i++){

            lineWidth = obj.getStringUnitWidth(new_txt +arr_txt[i] )*fontSize/obj.internal.scaleFactor;
            if(lineWidth > pageWidth){
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

    query.then(function(response){
        response.text().then((text)=>{
            const obj = JSON.parse(text)[0];
            const today = new Date;
            const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril','Maio', 'Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro']

            console.log(today)
            console.log(obj);

            title.innerHTML = ` <input type="text" id="edtRecibo" value="RECIBO"/> - ${obj.nome}`;
            content.innerHTML = `<textarea rows="10" cols="60" id="edtTexto"> Eu ${obj.nome}, inscrito no CPF/CNPJ ${obj.cnpj}, recebi de Flexibus Sanfonados LTDA inscrito no CNPJ 00.519.547/0001-06 a importância de R$${valor.value}, referenta a serviços prestados na data __/__/__
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


    console.log(query);





})


    const btnClose = document.querySelector(".close");
    btnClose.addEventListener("click", ()=>{
        overlay.style.visibility = "hidden";
        overlay.style.opacity = 0;
    });    

    