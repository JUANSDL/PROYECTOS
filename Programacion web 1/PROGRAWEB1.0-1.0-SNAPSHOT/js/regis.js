
function FuncionJavascript(e){
  
  var nombre = document.getElementById('fnombre');
  var fecha = document.getElementById('ffecha');
  var email = document.getElementById('femail');
  var usuario = document.getElementById('fusuario');
  var contra = document.getElementById('fcontra');

  if(nombre.value===''){
    alert("El campo Nombre es obligatorio");
    e.preventDefault();
  }
 
  if(fecha.value=== ''){
    alert("El campo Fecha es obligatorio");
    e.preventDefault();
  }

  if(email.value=== ''){
    alert("El campo Email es obligatorio");
    e.preventDefault();
  }

  if(usuario.value=== ''){
    alert("El campo Usuario es obligatorio");
    e.preventDefault();
  }

  if(contra.value=== ''){
    alert("El campo contraseña es obligatorio");
    e.preventDefault();
  }
  
}

var numero = 50;
var decimal = 0.5;
var texto = "Palabra";
var bool = true; //o false;
var elemento = document.querySelector('#idElemento');
var arreglo = ["Uno","Dos","Tres"];

var objeto = {
atributo1:"hola",
atributo2:5,
atributo3:false
};


var formulario =  document.querySelector('#formLogin');

//Eventos
formulario.addEventListener('submit',FuncionJavascript);

