const btn_Abrir = document.querySelector("#btn_Abrir");
const frmUpload = document.querySelector("#frmUpload");
const cmbFiles = document.querySelector("#cmbFiles");


btn_Abrir.addEventListener('click',()=>{
    window.open('extratos/'+cmbFiles.value, '_blank').focus();
})

/*
btn_Upload.addEventListener('click',()=>{
    window.open('extratos/'+cmbFiles.value, '_blank').focus();
})

*/