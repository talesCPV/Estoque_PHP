document.querySelector('.btn-login').addEventListener('click',(event)=>{
    const user = document.getElementById('edtUser').value;
    const pass = document.getElementById('edtPass').value;
    localStorage.setItem("user",user);
    localStorage.setItem("pass",pass);

//    event.preventDefault();
//    alert(pass)

})