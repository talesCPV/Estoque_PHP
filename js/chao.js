let screen = [1200,800];

let x = 600;
let y = 350;
let d = 600; // diameter
let r = d/2;  // radius
let margin = 20;


let w = 600; // width
let h = (y+Math.cos(deg_rad(135)) * r)-(y+Math.cos(deg_rad(45)) * r); // height
let c = 350; // center

//let ang = [45,135,225,315 ];

function preload() {
    font = loadFont('config/isocpeur.ttf');
}


function setup() {
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

//    line(x,0,x,900);


    let p1 = [x - r*seno(135)-w/2 + seno(135) * r, y + cosseno(135) * r];
    let p2 = [x - r*seno(45)-w/2 + seno(45)  * r, y + cosseno(45)  * r];
    let p3 = [x - r*seno(315)+w/2 + seno(315) * r, y + cosseno(315) * r];
    let p4 = [x - r*seno(225)+w/2 + seno(225) * r, y + cosseno(225) * r];


//    arc(x+r*seno(135)-w/2, y, d, d, deg_rad(135), deg_rad(225)); 
//    line(p1[0],p1[1],p2[0],p2[1]);

    arc(x-r*seno(45)+w/2, y,  d, d, deg_rad(315), deg_rad(45));
    line(p3[0],p3[1],p4[0],p4[1]);

    
    line(x,y-c/2,p1[0],p1[1]);    
    line(x,y+c/2,p2[0],p2[1]);
    line(x,y+c/2,p3[0],p3[1]);
    line(x,y-c/2,p4[0],p4[1]);

    line(x,y-c/2,x,y+c/2);

    stroke(255,0,0);

    let t = 3000;

    arc(t/2+x+r*seno(180)-w/2, y, t, t, deg_rad(135), deg_rad(225)); 




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
    text("DES. N. 000-01", x1-w_leg+120, y1-h_leg +70, 300, 150);
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