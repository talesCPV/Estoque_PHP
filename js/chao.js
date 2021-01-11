let screen = [1200,800];

let x = 600;
let y = 350;
let d = 600; // diameter
let r = d/2;  // radius
let margin = 20;
let t = 2750; // raio grande do velcro

let params;

// usuario
let w = 600; // width
let h = 500; // height
let c = 350 ; // center
let bainhas = 0;

let escala = 3;


// calculo
let ang = Math.asin((h/2)/t)/(Math.PI / 180);
//alert(Math.asin(ang)/(Math.PI / 180));


function preload() {
    font = loadFont('config/isocpeur.ttf');
}


function setup() {
    params = parseURLParams(window.location.href);
    bainhas = params[0];
    h = params[2]/escala;
    w = params[1]/escala;
    c = (params[2] * 0.8)/escala;
    createCanvas(screen[0], screen[1]);
    frameRate(30)
    textSize(15);
    textAlign(10, 10);
    textFont(font);    
    stroke(255);
    fill(0);

}

function draw() {
    background(0, 0, 0);
    stroke(255);

    draw_sheet();

    let p1 = [x - w/2, y - h/2 ];
    let p2 = [x - w/2, y + h/2 ];
    let p3 = [x + w/2, y + h/2 ];
    let p4 = [x + w/2, y - h/2 ];

    cota(p1,p4,"up",params[1],40);
    cota(p4,p3,"right",params[2],40);
    cota([x,y-c/2],[x,y+c/2],"right",(params[2]*0.8),40);
    
    line(x,y-c/2,p1[0],p1[1]);    
    line(x,y+c/2,p2[0],p2[1]);
    line(x,y+c/2,p3[0],p3[1]);
    line(x,y-c/2,p4[0],p4[1]);

    line(x,y-c/2,x,y+c/2); // linha de centro

    let w1 = (t/2) - Math.sqrt(Math.pow((t/2),2) - Math.pow((h/2),2));

    stroke(255,255,0);

    // bainhas
    let xb = x-w/2-w1+(w/6);
    let ang_b = Math.asin( (0.1*h)/(w/2))/(Math.PI / 180)
    let off_20 = seno(ang_b) * (w/3);
    let lin_20 = h + (off_20 * 2);
//    alert(ang_b)

    for(let i=0; i< bainhas+1;i++){
//        line(xb-10,);

    }

    line(xb, y - h/2 + off_20 ,xb, y + h/2 - off_20);
    cota(p2,[x-w/2-w1+(w/6), y + h/2],"down",(w/6).toFixed(2),40);
    

    // arcos


    arc(t/2+x-w/2-w1, y, t, t, deg_rad(180-ang_b), deg_rad(180 + ang_b)); 

    arc(-t/2+x+w/2+w1, y, t, t, deg_rad(-ang_b), deg_rad(ang_b)); 


}


function draw_sheet(){

    let x0 = margin;
    let x1 = width-margin;
    let y0 = margin;
    let y1 = height-margin;

    let w_leg = 400;
    let h_leg = 150;
    let lin = 30;


    //margem
    line(x0,y0,x1,y0);
    line(x0,y1,x1,y1);
    line(x0,y0,x0,y1);
    line(x1,y0,x1,y1);

    //legenda
    line(x1-w_leg,y1-h_leg,x1,y1-h_leg);
    line(x1-w_leg,y1-h_leg,x1-w_leg,y1);

    //linhas
    line(x1-w_leg,y1-h_leg+lin,x1,y1-h_leg+lin);
    line(x1-w_leg,y1-h_leg+lin*3,x1,y1-h_leg+lin*3);

    line(x1-w_leg+lin*3,y1-h_leg+lin*3,x1-w_leg+lin*3,y1);

    fill(255);
    textSize(15);
    text("FLEXIBUS SANFONADOS LTDA. ", x1-w_leg+120, y1-h_leg +20, 300, 150);
    textSize(30);
    text("CHAO "+params[4]+" "+params[3], x1-w_leg+20, y1-h_leg +70, 300, 150);
    textSize(15);
    text("Escala:", x1-w_leg+15, y1-h_leg+lin*3 +15, 300, 150);
    textSize(25);
    text("TALES C. DANTAS", x1-w_leg+150, y1-h_leg+lin*3 +35, 300, 150);
    noFill();


}

function deg_rad(ang){
    return ang * (Math.PI / 180);
}

function seno(ang){
    return Math.sin(deg_rad(ang));
}

function cosseno(ang){
    return Math.cos(deg_rad(ang));
}

function cota(pi,pf,side,texto,os){
    textSize(25);
    fill(255);

    if(side == "up"){
        line(pi[0],pi[1]-(os*0.2),pi[0],pi[1]-os);
        line(pf[0],pf[1]-(os*0.2),pf[0],pf[1]-os);
        line(pi[0],pi[1]-(os*0.8),pf[0],pf[1]-(os*0.8));
  
        text(texto, (pf[0]-pi[0]) / 2 + pi[0] - textWidth(texto)/2 , pf[1]-(os*1.2), 300, 150);    

    }else if(side == "down"){
        line(pi[0],pi[1]+(os*0.2),pi[0],pi[1]+os);
        line(pf[0],pf[1]+(os*0.2),pf[0],pf[1]+os);
        line(pi[0],pi[1]+(os*0.8),pf[0],pf[1]+(os*0.8));
        
        text(texto, (pf[0]-pi[0]) / 2 + pi[0] - textWidth(texto)/2 , pf[1]+(os*0.6), 300, 150);    

    }else if(side == "left"){
        line(pi[0]-(os*0.2),pi[1],pi[0]-os,pi[1]);
        line(pf[0]-(os*0.2),pf[1],pf[0]-os,pf[1]);
        line(pi[0]-(os*0.8),pi[1],pf[0]-(os*0.8),pf[1]);

        push();
        translate(pi[0] - os, (pf[1]-pi[1]) / 2 + pi[1] - textWidth(texto)/2  );
        rotate( radians(270) );
        text(texto, 0, 0, 300, 150);    
        pop();


    }else if(side == "right"){
        line(pi[0]+(os*0.2),pi[1],pi[0]+os,pi[1]);
        line(pf[0]+(os*0.2),pf[1],pf[0]+os,pf[1]);
        line(pi[0]+(os*0.8),pi[1],pf[0]+(os*0.8),pf[1]);
        
        push();
        translate(pi[0] + os, (pf[1]-pi[1]) / 2 + pi[1] - textWidth(texto)/2 );
        rotate( radians(90) );
        text(texto, 0, 0, 300, 150);    
        pop();
    }

    noFill();

}

function parseURLParams(url) {

    var res = url.split("?");
    res = res[1].split("&");

    let b = res[0].split("=");
    b = b[1];

    let l = res[1].split("=");
    l = l[1];

    let c = res[2].split("=");
    c = c[1];

    let m = res[3].split("=");
    m = m[1];

    let f = res[4].split("=");
    f = f[1];

    return [b,l,c,m,f]; 
}