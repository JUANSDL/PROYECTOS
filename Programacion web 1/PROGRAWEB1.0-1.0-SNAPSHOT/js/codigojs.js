
function FuncionJavascript(e){
  
  var usuario = document.getElementById('fusuario');
  var contra = document.getElementById('fcontra');


  if(usuario.value==''){
    alert("El campo usuario es obligatorio");
    e.preventDefault();
  }
 
  if(contra.value== ''){
    alert("El campo contraseña es obligatoria");
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

