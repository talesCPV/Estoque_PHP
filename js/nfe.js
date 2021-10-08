

class NFe{
    constructor(){
        this.A = new Object,
        this.B = new Object,
        this.C = new Object,
        this.E = new Object,
        this.Fat = new Object,
        this.I = []

    }

    exportTxt(){
        function add(str){
            let out='';
            for(let i=0; i< str.length; i++){

                let txt = ''
                if(str[i] != undefined){
                    txt = str[i].toString().trim()
                    if(i > 0){
                        txt = txt.toUpperCase()
                    }

//                    txt = str[i].toString().trim().toUpperCase()
                }
                out += `${txt}|`
            }
            out += '\r\n'
            return out;

        }
        const lineA = add(['A',this.A.versao,this.A.id])
        const lineB = add(['B',this.B.cUF,this.B.cNF,this.B.natOp,this.B.mod,this.B.serie,this.B.nNF,this.B.dhEmi,this.B.dhSaiEnt,this.B.tpNF,this.B.idDest,this.B.cMunFG,this.B.tpImp,this.B.tpEmis,this.B.cDV,this.B.tpAmb,this.B.finNFe,this.B.indFinal,this.B.indPres,this.B.procEmi,this.B.verProc,this.B.dhCont,this.B.xJust])
        const lineC = add(['C',this.C.xNome,this.C.xFant,this.C.IE,this.C.IEST,this.C.IM,this.C.CNAE,this.C.CRT])
        const lineC02 = add(['C02',this.C.CNPJ])
        const lineC05 = add(['C05',this.C.xLgr,this.C.nro,this.C.cpl,this.C.bairro,this.B.cMunFG,this.C.xMun,this.C.UF,this.C.CEP,this.C.cPais,this.C.xPais,this.C.fone])
        const lineE =  add(['E',this.E.xNome,this.E.indIEDest,this.E.IE,this.E.IEST,this.E.IM,this.E.CNAE,this.E.CRT])
        const lineE02 = add(['E02',this.E.CNPJ])
        const lineE05 = add(['E05',this.E.xLgr,this.E.nro,this.E.cpl,this.E.bairro,this.E.cMunFG,this.E.xMun,this.E.UF,this.E.CEP,this.E.cPais,this.E.xPais,this.E.fone])

        let tot = 0;
        let lineI=''
        for(let i=0; i<this.I.length; i++){
            const item = this.I[i]
            tot += (parseFloat(item.vUnCom) * parseFloat(item.qCom)) ;
            lineI += add(['H',i+1])
            lineI += add(['I',item.cProd,item.cEAN,item.cBarra,item.xProd,item.NCM,item.cBenef,item.CFOP,item.uCom,item.qCom,item.vUnCom,item.vProd,"",item.cEANTrib,item.uTrib,item.qTrib,item.vUnTrib,item.vFrete,item.vSeg,item.vDesc,item.vOutro,item.indTot,item.xPed,item.nFCI])
            lineI += add(['M',''])
            lineI += add(['N'])
            lineI += add(['N10d',0,102])
            lineI += add(['O',"","","",999])
            lineI += add(['O07',99,(0).toFixed(2)])
            lineI += add(['O10',(0).toFixed(2),(0).toFixed(4)])
            lineI += add(['Q'])
            lineI += add(['Q05',99,(0).toFixed(2)])
            lineI += add(['Q07',(0).toFixed(2),(0).toFixed(4)])
            lineI += add(['S'])
            lineI += add(['S05',99,(0).toFixed(2)])
            lineI += add(['S07',(0).toFixed(2),(0).toFixed(4)])
        }
/*
        W02|vBC|vICMS|vICMSDeson|vFCPUFDest|vICMSUFDest|vICMSUFRemet|vFCP|vBCST|vST|vFCPST|
        vFCPSTRet|vProd|vFrete|vSeg|vDesc|vII|vIPI|vIPIDevol|vPIS|vCOFINS|
        vOutro|vNF|vTotTrib|
*/
        let lineFat = add(['W'])
        lineFat += add(['W02',(0).toFixed(2),(0).toFixed(2),(0).toFixed(2),(0).toFixed(2),(0).toFixed(2),(0).toFixed(2),(0).toFixed(2),(0).toFixed(2),tot.toFixed(2),(0).toFixed(2),
        (0).toFixed(2),(0).toFixed(2),(0).toFixed(2),(0).toFixed(2),(0).toFixed(2),(0).toFixed(2),(0).toFixed(2),(0).toFixed(2),tot.toFixed(2),(0).toFixed(2)])
        lineFat += add(['W04c',(0).toFixed(2)])
        lineFat += add(['W04e',(0).toFixed(2)])
        lineFat += add(['W04g',(0).toFixed(2)])
        lineFat += add(['X',0])
        lineFat += add(['Y'])

        const parcelas = this.Fat.parc.split('/')        
        const totFin = (tot - this.Fat.desc).toFixed(2)
        lineFat += add(['Y02',parcelas.length,tot.toFixed(2),this.Fat.desc,totFin])
        let valorParc =  (totFin/parcelas.length).toFixed(2);
        let ultimaparc = totFin
        console.log(parcelas)
        for(let i=0; i< parcelas.length; i++){
            if(i == parcelas.length-1){
                valorParc = parseFloat(ultimaparc).toFixed(2);
            }else{
                ultimaparc -= valorParc;
            }
            const dia = new Date(this.A.dhEmi);
            dia.setDate(dia.getDate() + parseInt(parcelas[i]) + 1);
            const datasVenc = dia.getFullYear()+'-'+(dia.getMonth()+1).toString().padStart(2, '0')+'-'+dia.getDate().toString().padStart(2, '0') ;
            lineFat += add(['Y07',(i+1).toString().padStart(3, '0'),datasVenc,valorParc])
        }

        lineFat += add(['YA'])
        lineFat += add(['YA01',this.Fat.prazo,this.Fat.TpPgto,"",totFin])
        lineFat += add(['Z',"",this.Fat.txtCpl])

        return 'NOTAFISCAL|1\r\n'+lineA+lineB+lineC+lineC02+lineC05+lineE+lineE02+lineE05+lineI+lineFat


    }

    setAB(CNPJ,nNF,xMun="Caçapava",UF="SP",natOp="VENDA",indSinc="",codUf="35",ser="001",cUF,cNF="",mod="55",serie="1",tpNF="1",idDest="1",cMunFG,tpImp="1",tpEmis="1",cDV="",tpAmb="1",finNFe="1",indFinal="0",indPres="0",procEmi="",versao="4.00",verProc="",dhCont="",xJust=""){
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

    setFat(valor,parc,txtCpl,desc,prazo,TpPgto){
        this.Fat.valor = parseFloat(valor).toFixed(2),
        this.Fat.parc = parc,
        this.Fat.txtCpl = txtCpl,
        this.Fat.prazo = prazo,
        this.Fat.TpPgto = TpPgto,
        this.Fat.desc = parseFloat(desc).toFixed(2)
    }

    setC(xNome="Flexibus Sanfonados LTDA",CNPJ="00519547000106",IE="234033845113",IM="111222",xLgr="Av. Dr. Rosalvo de Almeida Telles",nro="2070",cpl="",bairro="Nova Caçapava",xMun="Caçapava",UF="SP",CEP="122863020",cPais="",xPais="BRASIL",fone="1236532230",xFant="",IEST="",CNAE="",CRT=""){       
        this.C.xNome = xNome,
        this.C.xFant = xFant,
        this.C.IE = this.onlyNum(IE),
        this.C.IEST = IEST,
        this.C.IM = this.onlyNum(IM),
        this.C.CNAE = CNAE,
        this.C.CRT = CRT,
        this.C.CNPJ = this.onlyNum(CNPJ)
        this.C.xLgr = xLgr,
        this.C.nro = nro,
        this.C.cpl = cpl,
        this.C.bairro = bairro,
        this.C.cMun = "",
        this.C.xMun = xMun,
        this.C.UF = UF,
        this.C.CEP = this.onlyNum(CEP),
        this.C.cPais = cPais,
        this.C.xPais = xPais,
        this.C.fone = this.onlyNum(fone),
        this.getCodUf(this.C);
        this.codPais(this.C);
    }

    setE(xNome="",CNPJ="",IE="",IM="",xLgr="",nro="",cpl="",bairro="",xMun="",UF="",CEP="",cPais="",xPais="",indIEDest="",fone="",IEST="",CNAE="",CRT="",xFant=""){   
        console.log(indIEDest)    
        this.E.xNome = xNome,
        this.E.xFant = xFant,
        this.E.IE = this.onlyNum(IE),
        this.E.indIEDest = indIEDest,
        this.E.IEST = IEST,
        this.E.IM = this.onlyNum(IM),
        this.E.CNAE = CNAE,
        this.E.CRT = CRT,
        this.E.CNPJ = this.onlyNum(CNPJ),
        this.E.xLgr = xLgr,
        this.E.nro = nro,
        this.E.cpl = cpl,
        this.E.bairro = bairro,
        this.E.cMun = "",
        this.E.xMun = xMun,
        this.E.UF = UF,
        this.E.CEP = this.onlyNum(CEP),
        this.E.cPais = cPais,
        this.E.xPais = xPais,
        this.E.fone = this.onlyNum(fone),
        this.getCodUf(this.E);
        this.codPais(this.E);
    }

    clearItens(){
        this.I = []
    }

    onlyNum(N){
        const allow = ['0','1','2','3','4','5','6','7','8','9']
        let num = ''
        for(let i=0; i<N.length; i++){
            if(allow.includes(N[i])){
                num += N[i]
            }
        }
        return num
    }

    addItem(nItem,cProd,xProd,NCM,uCom,qCom,vUnCom,CFOP="5102",infAdProd="",cEAN="",cBarra="",cBenef="",EXTIPI="",cEANTrib="",
    vFrete="",vSeg="",vDesc="",vOutro="",indTot="1",xPed="",nItemPed="",nFCI="",vTotTrib=""){

        const I = new Object;
            I.nItem =nItem,
            I.infAdProd = infAdProd.trim(),
            I.cProd = cProd.toString().trim(),
            I.cEAN = cEAN.toString().trim(),
            I.cBarra = cBarra,
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
            I.vUnTrib = I.vUnCom,
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