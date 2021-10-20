
//showDiv(1,[{img:"img04.jpg",label:"TREK"},{img:"img03.jpg",label:"PISTÃO"}])

var slideIndex = 1;
showSlides(slideIndex);


function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}

function showDiv(id,obj){

  const todos = document.getElementsByClassName('fotoslide')
  for(let i=0; i<todos.length; i++){
    todos[i].innerHTML = ''
  }

  const div = document.getElementById(id).querySelector('.fotoslide');

  const slide = document.createElement('div')
  slide.className = 'slideshow-container'
  const dots = document.createElement('div')
  dots.style = 'text-align:center; margin-top:20px;'

  for(let i=0; i<obj.length; i++){
    const foto = document.createElement('div')
    foto.className = 'mySlides fade'

    const index = document.createElement('div')
    index.className = 'numbertext'
    index.innerHTML = `${i+1}/${obj.length}`
    foto.appendChild(index)

    const image = document.createElement('img')
    image.src = 'assets/images/'+obj[i].img    
    image.style = 'width:100%'
    foto.appendChild(image)

    const label = document.createElement('div')
    label.className = 'text'
    label.innerHTML = `${obj[i].label}`
    foto.appendChild(label)

    slide.appendChild(foto)
    dots.innerHTML += `<span class="dot" onclick="currentSlide(${i+1})"></span>`
  }
  slide.innerHTML += `
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
  `
  div.appendChild(slide)
  div.appendChild(dots)
  slideIndex = 1;
  showSlides(slideIndex);




}