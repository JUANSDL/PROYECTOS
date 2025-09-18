document.getElementById("btn_iniciarsesion").addEventListener("click", iniciarSesion);
document.getElementById("btn_registrarse").addEventListener("click", register);

document.getElementById('regresarse').onclick = function(){
    window.location.href = 'index.jsp';
};
    


var container_login_reggister = document.querySelector(".contenedor_loginregister");
var formulario_login = document.querySelector(".formulario_login");
var form_register = document.querySelector(".form_register");
var caja_trasera_login = document.querySelector(".caja_trasera_login");
var caja_trasera_register = document.querySelector(".caja_trasera_reggister");



function iniciarSesion(){
    form_register.style.display = "none";
    container_login_reggister.style.left = "10px"; 
    formulario_login.style.display = "block";
    caja_trasera_register.style.opacity = "1";
    caja_trasera_login.style.opacity = "0";
}

function register(){
    form_register.style.display = "block";
    container_login_reggister.style.left = "410px"; 
    formulario_login.style.display = "none";
    caja_trasera_register.style.opacity = "0";
    caja_trasera_login.style.opacity = "1";
}

