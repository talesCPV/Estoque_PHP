// CALCULADORA
document.getElementById('btnCalc').addEventListener('click',()=>{

	const HTML = `
	<div class="calculator">
		<input type="text" class="visor" id="edtVisor" value='0'/>
		<button class="um btn-calc">1</button>
		<button class="dois btn-calc">2</button>
		<button class="tres btn-calc">3</button>
		<button class="quatro btn-calc">4</button>
		<button class="cinco btn-calc">5</button>
		<button class="seis btn-calc">6</button>
		<button class="sete btn-calc">7</button>
		<button class="oito btn-calc">8</button>
		<button class="nove btn-calc">9</button>
		<button class="zero btn-calc">0</button>
		<button class="ce btn-calc">CE</button>
		<button class="soma btn-calc">+</button>
		<button class="sub btn-calc">-</button>
		<button class="mult btn-calc">x</button>
		<button class="div btn-calc">/</button>
		<button class="igual btn-calc">=</button>	
		<button class="ponto btn-calc">,</button>	
	</div>

	`;

	document.querySelector(".content").innerHTML = HTML;
	document.querySelector("#popTitle").innerHTML = 'Calculadora';
	document.querySelector(".overlay").style.visibility = "visible";
	document.querySelector(".overlay").style.opacity = 1;

})