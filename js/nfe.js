
class Teste{
    constructor(A,B,C){
        this.a = A,
        this.b = B,
        this.c = C, 
        this.d = new Object
            this.d.nome = "teste",
            this.d.idade = 21
        this.e = []

    }

    imprime(){
        console.log([this.a,this.b,this.c])
        console.log(this.d)
        console.log(this.e)
    }

    setD(nome,idade,sexo){
        this.d.nome = nome,
        this.d.idade = idade,
        this.d.sexo = sexo
    }

    addE(obj){
        this.e.push(obj)
    }

}


class NFe{
    constructor(){
        this.A = new Object,
        this.B = new Object,
        this.C = new Object,
        this.E = new Object,
        this.I = []
    }

    setAB(CNPJ,nNF,xMun="Caçapava",UF="SP",natOp="VENDA",indSinc="",codUf="35",ser="001",cUF,cNF="",mod="55",serie="1",tpNF="1",idDest="1",cMunFG,tpImp="1",tpEmis="1",cDV="",tpAmb="1",finNFe="1",indFinal="0",indPres="0",procEmi="3",versao="4.01",verProc="4.01_sebrae_b034",dhCont="",xJust=""){
        this.A.CNPJ = CNPJ,
        this.A.nNF = nNF.padStart(9, '0'),        
        this.A.versao = versao,        
        this.A.indSinc = indSinc,
        this.A.uf = codUf,
//        this.A.tpEmis = tpEmis,
//        this.A.mod = mod,
        this.A.ser = ser,
        this.A.cod = Math.floor(Math.random() * (99999999 - 10000000) + 10000000),
        this.A.dhEmi = this.getDataHora(),

        this.B.cUF = cUF,
        this.B.cNF = cNF,
        this.B.natOp = natOp,
        this.B.mod = mod,
        this.B.serie = serie,
        this.B.nNF = parseInt(nNF).toString(),
        this.B.dhEmi = this.A.dhEmi,
        this.B.dhSaiEnt,
        this.B.tpNF = tpNF,
        this.B.idDest = idDest,
        this.B.cMunFG = cMunFG,
        this.B.tpImp = tpImp,
        this.B.tpEmis = tpEmis,
        this.B.cDV = cDV,
        this.B.tpAmb = tpAmb,
        this.B.finNFe = finNFe,
        this.B.indFinal = indFinal,
        this.B.indPres = indPres,
        this.B.procEmi = procEmi,
        this.B.verProc = verProc,
        this.B.dhCont = dhCont,
        this.B.xJust = xJust,
        this.B.xMun = xMun,
        this.B.UF = UF,

        this.A.id = this.geraChave(this.A.dhEmi)

        this.getCodUf(this.B),
        this.getSaida()        
    }

/*
    setA(CNPJ,nNF,versao="4.01",indSinc="",uf="35",tpEmis="1",mod="55",ser="001"){
        this.A.CNPJ = CNPJ,
        this.A.nNF = nNF.padStart(9, '0'),        
        this.A.versao = versao,        
        this.A.indSinc = indSinc,
        this.A.uf = uf,
        this.A.tpEmis = tpEmis,
        this.A.mod = mod,
        this.A.ser = ser,
        this.A.cod = Math.floor(Math.random() * (99999999 - 10000000) + 10000000),
        this.A.dhEmi = this.getDataHora(),
        this.A.id = this.geraChave(this.A.dhEmi)
    }

    setB(nNF=this.A.nNF,dhEmi=this.A.dhEmi,xMun="Caçapava",UF="SP",cUF,cNF="",natOp="VENDA",mod="55",serie="1",tpNF="1",idDest="1",cMunFG,tpImp="1",tpEmis="1",cDV="",tpAmb="1",finNFe="1",indFinal="0",indPres="0",procEmi="3",verProc="4.01_sebrae_b034",dhCont="",xJust=""){
        this.B.cUF = cUF,
        this.B.cNF = cNF,
        this.B.natOp = natOp,
        this.B.mod = mod,
        this.B.serie = serie,
        this.B.nNF = parseInt(nNF).toString(),
        this.B.dhEmi = dhEmi,
        this.B.dhSaiEnt,
        this.B.tpNF = tpNF,
        this.B.idDest = idDest,
        this.B.cMunFG = cMunFG,
        this.B.tpImp = tpImp,
        this.B.tpEmis = tpEmis,
        this.B.cDV = cDV,
        this.B.tpAmb = tpAmb,
        this.B.finNFe = finNFe,
        this.B.indFinal = indFinal,
        this.B.indPres = indPres,
        this.B.procEmi = procEmi,
        this.B.verProc = verProc,
        this.B.dhCont = dhCont,
        this.B.xJust = xJust,
        this.B.xMun = xMun,
        this.B.UF = UF,
        this.getCodUf(this.B),
        this.getSaida()        
    }
*/
    setC(xNome="Flexibus Sanfonados LTDA",CNPJ="00519547000106",IE="234033845113",IM="111222",xLgr="Av. Dr. Rosalvo de Almeida Telles",nro="2070",cpl="",bairro="Nova Caçapava",xMun="Caçapava",UF="SP",CEP="122863020",cPais="",xPais="BRASIL",fone="1236532230",xFant="",IEST="",CNAE="",CRT=""){       
        this.C.xNome = xNome,
        this.C.xFant = xFant,
        this.C.IE = IE,
        this.C.IEST = IEST,
        this.C.IM = IM,
        this.C.CNAE = CNAE,
        this.C.CRT = CRT,
        this.C.CNPJ = CNPJ
        this.C.xLgr = xLgr,
        this.C.nro = nro,
        this.C.cpl = cpl,
        this.C.bairro = bairro,
        this.C.cMun = "",
        this.C.xMun = xMun,
        this.C.UF = UF,
        this.C.CEP = CEP,
        this.C.cPais = cPais,
        this.C.xPais = xPais,
        this.C.fone = fone,
        this.getCodUf(this.C);
        this.codPais(this.C);
    }

    setE(xNome="",CNPJ="",IE="",IM="",xLgr="",nro="",cpl="",bairro="",xMun="",UF="",CEP="",cPais="",xPais="",fone="",IEST="",CNAE="",CRT="",xFant=""){       
        this.E.xNome = xNome,
        this.E.xFant = xFant,
        this.E.IE = IE,
        this.E.IEST = IEST,
        this.E.IM = IM,
        this.E.CNAE = CNAE,
        this.E.CRT = CRT,
        this.E.CNPJ = CNPJ
        this.E.xLgr = xLgr,
        this.E.nro = nro,
        this.E.cpl = cpl,
        this.E.bairro = bairro,
        this.E.cMun = "",
        this.E.xMun = xMun,
        this.E.UF = UF,
        this.E.CEP = CEP,
        this.E.cPais = cPais,
        this.E.xPais = xPais,
        this.E.fone = fone,
        this.getCodUf(this.E);
        this.codPais(this.E);
    }


    addItem(nItem,cProd,xProd,NCM,uCom,qCom,vUnCom,CFOP="5102",infAdProd="",cEAN="",cBenef="",EXTIPI="",cEANTrib="",uTrib="",
    qTrib="",vUnTrib="",vFrete="",vSeg="",vDesc="",vOutro="",indTot="1",xPed="",nItemPed="",nFCI="",vTotTrib=""){

        const I = new Object;
            I.nItem =nItem,
            I.infAdProd = infAdProd.trim(),
            I.cProd = cProd.toString().trim(),
            I.cEAN = cEAN.toString().trim(),
            I.xProd = xProd.toString().trim(),
            I.NCM = NCM.toString().trim(),
            I. cBenef = cBenef.toString().trim(),
            I.EXTIPI = EXTIPI.toString().trim(),
            I.CFOP = CFOP.toString().trim(),
            I.uCom = uCom.toString().trim(),
            I.qCom = parseFloat(qCom).toFixed(4),
            I.vUnCom = parseFloat(vUnCom).toFixed(10),
            I.vProd =  (I.qCom * I.vUnCom).toFixed(2)
            I.cEANTrib = cEANTrib,
            I.uTrib = I.uCom,
            I.qTrib = I.qCom,
            I.vUnTrib = vUnTrib,
            I.vFrete = vFrete,
            I.vSeg = vSeg,
            I.vDesc = vDesc,
            I.vOutro = vOutro,
            I.indTot = indTot,
            I.xPed = xPed, 
            I.nItemPed = nItemPed,
            I.nFCI = nFCI,
            I.vTotTrib = vTotTrib
        this.I.push(I)
    }

    getDataHora(){
        const today = new Date(); 
        const dia = today.getDate().toString().padStart(2, '0');
        const mes = (today.getMonth()+1).toString().padStart(2, '0');
        const ano = today.getFullYear().toString().substr(0,4);
        const hora = today.getHours().toString().padStart(2, '0');
        const min = today.getMinutes().toString().padStart(2, '0');
        const seg = today.getSeconds().toString().padStart(2, '0');

        return(ano+"-"+mes+"-"+dia+"T"+hora+":"+min+":"+seg+"-03:00")

    }

    geraChave(date){
        const aamm = date.substr(2,2)+date.substr(5,2);
        const chave = this.A.uf + aamm + this.A.CNPJ + this.B.mod + this.A.ser +  this.A.nNF + this.B.tpEmis + this.A.cod ;

        let dv = 0;
        let mult = 2;
        for(let i = 1; i < chave.length+1; i++){
            dv = dv + (chave[chave.length - i] * mult);
            mult++;
            if (mult > 9){
                mult = 2;
            }
        }

        let resto = dv % 11;
        dv = 11 - resto;
        if(dv > 9){
            dv = 0;
        }

        return 'NFe'+chave+dv;    
    }

    getSaida(){
        const today = new Date(this.A.dhEmi.substr(0,19));
        today.setHours( today.getHours() + 2 )
        const dia = today.getDate().toString().padStart(2, '0');
        const mes = (today.getMonth()+1).toString().padStart(2, '0');
        const ano = today.getFullYear().toString().substr(0,4);
        const hora = today.getHours().toString().padStart(2, '0');
        const min = today.getMinutes().toString().padStart(2, '0');
        const seg = today.getSeconds().toString().padStart(2, '0');        

        this.B.dhSaiEnt = ano+"-"+mes+"-"+dia+"T"+hora+":"+min+":"+seg+"-03:00";

    }


    getCodUf(ORIGEM){
        const UF_cod = new Promise((resolve,reject) =>{

            fetch("https://servicodados.ibge.gov.br/api/v1/localidades/estados")
            .then(function (response){

                if (response.status === 200) { 
                    resolve(response.text());
                } else { 
                    reject(new Error("Houve algum erro na comunicação com o servidor"));
                } 
            })                    
        }); 
        UF_cod.then((resolve)=>{
            const json = JSON.parse(resolve);
            for(let i=0; i<json.length; i++){
                if(json[i].sigla == ORIGEM.UF){
                    ORIGEM.cUF = json[i].id;
                    this.getCodMun(ORIGEM);
                }
            }


        })

    }

    getCodMun(ORIGEM){
        const Mun_cod = new Promise((resolve,reject) =>{

            fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${ORIGEM.cUF}/municipios`)
            .then(function (response){

                if (response.status === 200) { 
                    resolve(response.text());
                } else { 
                    reject(new Error("Houve algum erro na comunicação com o servidor"));
                } 
            })                    
        }); 

        Mun_cod.then((resolve)=>{
            const json = JSON.parse(resolve);
            for(let j=0; j<json.length; j++){
                if(json[j].nome.trim().toLowerCase() == ORIGEM.xMun.trim().toLowerCase()){
                    ORIGEM.cMunFG = json[j].id;
                    break;
                }
            }

        })
                

    }

    codPais(ORIGEM){
        if(ORIGEM.xPais.toUpperCase() == "BRASIL"){
            ORIGEM.cPais = "1058"
        }

        if(ORIGEM.xFant == ""){
            ORIGEM.xFant = ORIGEM.xNome;
        }
    }

}


class Empresa{
    constructor(xNome="",CNPJ="",IE="",IM="",xLgr="",nro="",cpl="",bairro="",xMun="",UF="",CEP="",fone="",xPais="Brasil",cPais="",IEST="",CNAE="",CRT="",xFant=""){
        this.letra = "E",
        this.xNome = xNome,
        this.xFant = xFant,
        this.IE = IE,
        this.IEST = IEST,
        this.IM = IM,
        this.CNAE = CNAE,
        this.CRT = CRT,
        this.CNPJ = CNPJ
        this.xLgr = xLgr,
        this.nro = nro,
        this.cpl = cpl,
        this.bairro = bairro,
        this.cMun = "",
        this.xMun = xMun,
        this.UF = UF,
        this.CEP = CEP,
        this.cPais = cPais,
        this.xPais = xPais,
        this.fone = fone,

        this.getCodUf();
        this.codPais();


    }

    codPais(){
        if(this.xPais.toUpperCase() == "BRASIL"){
            this.cPais = "1058"
        }

        if(this.xFant == ""){
            this.xFant = this.xNome;
        }
    }

    exportTxt(){
        return this.letra+"|"+this.xNome+"|"+this.xFant+"|"+this.IE+"|"+this.IEST+"|"+this.IM+"|"+this.CNAE+"|"+this.CRT+"|\n"+
               this.letra+"02|"+this.CNPJ+"|\n"+
               this.letra+"05|"+this.xLgr+"|"+this.nro+"|"+this.cpl+"|"+this.bairro+"|"+this.cMun+"|"+this.xMun+"|"+this.UF+"|"+this.CEP+"|"+this.cPais+"|"+this.xPais+"|"+this.fone+"|\n"
    }

    getCodUf(){
        const UF_cod = new Promise((resolve,reject) =>{

            fetch("https://servicodados.ibge.gov.br/api/v1/localidades/estados")
            .then(function (response){

                if (response.status === 200) { 
                    resolve(response.text());
                } else { 
                    reject(new Error("Houve algum erro na comunicação com o servidor"));
                } 
            })                    
        }); 
        UF_cod.then((resolve)=>{
            const json = JSON.parse(resolve);
            for(let i=0; i<json.length; i++){
                if(json[i].sigla == this.UF){
                    this.cUF = json[i].id;
                    this.getCodMun();
                }
            }


        })

    }

    getCodMun(){
        const Mun_cod = new Promise((resolve,reject) =>{

            fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${this.cUF}/municipios`)
            .then(function (response){

                if (response.status === 200) { 
                    resolve(response.text());
                } else { 
                    reject(new Error("Houve algum erro na comunicação com o servidor"));
                } 
            })                    
        }); 

        Mun_cod.then((resolve)=>{
            const json = JSON.parse(resolve);
            for(let j=0; j<json.length; j++){
                if(json[j].nome.trim().toLowerCase() == this.xMun.trim().toLowerCase()){
                    this.cMun = json[j].id;
                    break;
                }
            }

        })
                

    }    

}

class Itens{
    constructor(nItem,cProd,xProd,NCM,uCom,qCom,vProd,CFOP="5102",infAdProd="",cEAN="",cBenef="",EXTIPI="",vUnCom="",cEANTrib="",uTrib="",
    qTrib="",vUnTrib="",vFrete="",vSeg="",vDesc="",vOutro="",indTot="",xPed="",nItemPed="",nFCI="",vTotTrib=""){

        this.nItem =nItem,
        this.infAdProd = infAdProd.cod.trim(),
        this.cProd = cProd.cod.trim(),
        this.cEAN = cEAN.cod.trim(),
        this.xProd = xProd.cod.trim(),
        this.NCM = NCM.cod.trim(),
        this. cBenef = cBenef.cod.trim(),
        this.EXTIPI = EXTIPI.cod.trim(),
        this.CFOP = CFOP.cod.trim(),
        this.uCom = uCom.cod.trim(),
        this.qCom = parseFloat(qCom).toFixed(4),
        this.vUnCom = parseFloat(vUnCom).toFixed(10),
        this.vProd = vProd, // parseFloat(data[i].preco * data[i].qtd).toFixed(2)
        this.cEANTrib = cEANTrib,
        this.uTrib = uTrib,
        this.qTrib = qTrib,
        this.vUnTrib = vUnTrib,
        this.vFrete = vFrete,
        this.vSeg = vSeg,
        this.vDesc = vDesc,
        this.vOutro = vOutro,
        this.indTot = indTot,
        this.xPed = xPed, 
        this.nItemPed = nItemPed,
        this.nFCI = nFCI,
        this.vTotTrib = vTotTrib

    }

    exportTxt(){
        return "H|"+this.nItem+"|"+this.infAdProd+"|\n"+
               "I|"+this.cProd+"|"+this.cEAN+"|"+this.xProd+"|"+this.NCM+"|"+this.cBenef+"|"+this.EXTIPI+"|"+this.CFOP+"|"+this.uCom+"|"+this.qCom+"|"+this.vUnCom+
               "|"+this.vProd+"|"+this.cEANTrib+"|"+this.uTrib+"|"+this.qTrib+"|"+this.vUnTrib+"|"+this.vFrete+"|"+this.vSeg+"|"+this.vDesc+"|"+this.vOutro+
               "|"+this.indTot+"|"+this.xPed+"|"+this.nItemPed+"|"+this.nFCI+"|\n"+
               "M|"+vTotTrib+"|\n"+
               "N|\nN10d|0|102|\n"+
               "O||||999|\nO07|99|0.00|\nO10|0.00|0.0000|\n"+
               "Q|\nQ05|99|0.00|\nQ07|0.00|0.0000|\n"+
               "S|\nS05|99|0.00|\nS07|0.00|0.0000|\n"
    }    
}

class A{
    constructor(CNPJ,nNF,versao="4.01",indSinc="",uf="35",tpEmis="1",mod="55",ser="001"){
        this.CNPJ = CNPJ,
        this.nNF = nNF.padStart(9, '0'),        
        this.versao = versao,        
        this.indSinc = indSinc,
        this.uf = uf,
        this.tpEmis = tpEmis,
        this.mod = mod,
        this.ser = ser,
        this.cod = Math.floor(Math.random() * (99999999 - 10000000) + 10000000),
        this.dhEmi = this.getDataHora(),
        this.id = this.geraChave(this.dhEmi)

    }

    exportTxt(){
        return "A|"+this.versao+"|"+this.id+"|"+this.indSinc+"|\n";
    }

    getDataHora(){
        const today = new Date(); 
        const dia = today.getDate().toString().padStart(2, '0');
        const mes = (today.getMonth()+1).toString().padStart(2, '0');
        const ano = today.getFullYear().toString().substr(0,4);
        const hora = today.getHours().toString().padStart(2, '0');
        const min = today.getMinutes().toString().padStart(2, '0');
        const seg = today.getSeconds().toString().padStart(2, '0');

        return(ano+"-"+mes+"-"+dia+"T"+hora+":"+min+":"+seg+"-03:00")

    }

    geraChave(date){
        const aamm = date.substr(2,2)+date.substr(5,2);
        const chave = this.uf + aamm + this.CNPJ + this.mod + this.ser +  this.nNF + this.tpEmis + this.cod ;

        let dv = 0;
        let mult = 2;
        for(let i = 1; i < chave.length+1; i++){
            dv = dv + (chave[chave.length - i] * mult);
            mult++;
            if (mult > 9){
                mult = 2;
            }
        }

        let resto = dv % 11;
        dv = 11 - resto;
        if(dv > 9){
            dv = 0;
        }

        return 'NFe'+chave+dv;    
    }


}

class B{
    constructor(nNF,dhEmi,xMun="Caçapava",UF="SP",cUF,cNF="",natOp="VENDA",mod="55",serie="1",dhSaiEnt,tpNF="1",idDest="1",cMunFG,tpImp="1",tpEmis="1",cDV="",tpAmb="1",finNFe="1",indFinal="0",indPres="0",procEmi="3",verProc="4.01_sebrae_b034",dhCont="",xJust=""){
        this.cUF = cUF,
        this.cNF = cNF,
        this.natOp = natOp,
        this.mod = mod,
        this.serie = serie,
        this.nNF = parseInt(nNF).toString(),
        this.dhEmi = dhEmi,
        this.dhSaiEnt = dhSaiEnt,
        this.tpNF = tpNF,
        this.idDest = idDest,
        this.cMunFG = cMunFG,
        this.tpImp = tpImp,
        this.tpEmis = tpEmis,
        this.cDV = cDV,
        this.tpAmb = tpAmb,
        this.finNFe = finNFe,
        this.indFinal = indFinal,
        this.indPres = indPres,
        this.procEmi = procEmi,
        this.verProc = verProc,
        this.dhCont = dhCont,
        this.xJust = xJust,
        this.xMun = xMun,
        this.UF = UF,
        this.getCodUf(),
        this.getSaida()
    }

    exportTxt(){
        return "B|"+this.cUF+"|"+this.cNF+"|"+this.natOp+"|"+this.mod+"|"+this.serie+"|"+this.nNF+"|"+this.dhEmi+"|"+this.dhSaiEnt+"|"+this.tpNF+"|"+this.idDest+"|"+
        this.cMunFG+"|"+this.tpImp+"|"+this.tpEmis+"|"+this.cDV+"|"+this.tpAmb+"|"+this.finNFe+"|"+this.indFinal+"|"+this.indPres+"|"+this.procEmi+"|"+this.verProc+
        "|"+this.dhCont+"|"+this.xJust+"|\n"
    }

    getSaida(){
        const today = new Date(this.dhEmi.substr(0,19));
        today.setHours( today.getHours() + 2 )
        const dia = today.getDate().toString().padStart(2, '0');
        const mes = (today.getMonth()+1).toString().padStart(2, '0');
        const ano = today.getFullYear().toString().substr(0,4);
        const hora = today.getHours().toString().padStart(2, '0');
        const min = today.getMinutes().toString().padStart(2, '0');
        const seg = today.getSeconds().toString().padStart(2, '0');        

        this.dhSaiEnt = ano+"-"+mes+"-"+dia+"T"+hora+":"+min+":"+seg+"-03:00";

    }


    getCodUf(){
        const UF_cod = new Promise((resolve,reject) =>{

            fetch("https://servicodados.ibge.gov.br/api/v1/localidades/estados")
            .then(function (response){

                if (response.status === 200) { 
                    resolve(response.text());
                } else { 
                    reject(new Error("Houve algum erro na comunicação com o servidor"));
                } 
            })                    
        }); 
        UF_cod.then((resolve)=>{
            const json = JSON.parse(resolve);
            for(let i=0; i<json.length; i++){
                if(json[i].sigla == this.UF){
                    this.cUF = json[i].id;
                    this.getCodMun();
                }
            }


        })

    }

    getCodMun(){
        const Mun_cod = new Promise((resolve,reject) =>{

            fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${this.cUF}/municipios`)
            .then(function (response){

                if (response.status === 200) { 
                    resolve(response.text());
                } else { 
                    reject(new Error("Houve algum erro na comunicação com o servidor"));
                } 
            })                    
        }); 

        Mun_cod.then((resolve)=>{
            const json = JSON.parse(resolve);
            for(let j=0; j<json.length; j++){
                if(json[j].nome.trim().toLowerCase() == this.xMun.trim().toLowerCase()){
                    this.cMunFG = json[j].id;
                    break;
                }
            }

        })
                

    }






}

class C extends Empresa{
    constructor(xNome="Flexibus Sanfonados LTDA",CNPJ="00519547000106",IE="234033845113",IM="111222",xLgr="Av. Dr. Rosalvo de Almeida Telles",nro="2070",cpl="",bairro="Nova Caçapava",xMun="Caçapava",UF="SP",CEP="122863020",cPais="",xPais="BRASIL",fone="1236532230",IEST="",CNAE="",CRT="",xFant=""){
        super(xNome,CNPJ,IE,IM,xLgr,nro,cpl,bairro,xMun,UF,CEP,cPais,xPais,fone,IEST,CNAE,CRT,xFant)
        this.letra = "C"
        this.xNome = xNome,
        this.xFant = xFant,
        this.IE = IE,
        this.IEST = IEST,
        this.IM = IM,
        this.CNAE = CNAE,
        this.CRT = CRT,
        this.CNPJ = CNPJ
        this.xLgr = xLgr,
        this.nro = nro,
        this.cpl = cpl,
        this.bairro = bairro,
        this.cMun = "",
        this.xMun = xMun,
        this.UF = UF,
        this.CEP = CEP,
        this.cPais = cPais,
        this.xPais = xPais,
        this.fone = fone,
        super.getCodUf();
        super.codPais();

        if(this.xFant == ""){
            this.xFant = this.xNome;
        }

    }

}

/*
const myClassA = new A("00519547000106","2021");
console.log(myClassA)

const myClassB = new B(myClassA.nNF,myClassA.dhEmi);
console.log(myClassB)

const myClassC = new C();
console.log(myClassC)

const myClassE = new Empresa("Empresa Teste","00584623654209","25321452321","","rua 2","1450","","jd vilagge","Timbó","SC","15485065","1578653642","Brasil");
console.log(myClassE)

const obj = new Teste(10,20,30)
*/

const nfe = new NFe();
/*
    nfe.setA("00519547000106","2021");
    nfe.setB();
*/
    nfe.setAB("00519547000106","2021");

    nfe.setC();
    nfe.setE("Empresa Teste","00584623654209","25321452321","","rua 2","1450","","jd vilagge","Timbó","SC","15485065","1578653642","Brasil");
    nfe.addItem(3,125,"Teste de Produto",123456789,"PEÇA",15,125.32,5236)

console.log(nfe)