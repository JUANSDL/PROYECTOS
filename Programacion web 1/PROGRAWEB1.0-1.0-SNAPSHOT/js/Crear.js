
function FuncionJavascript(e){
  
  var texto = document.getElementById('Textopub');

  if(texto.value==''){
    alert("El campo es obligatorio");
  }
 
}
var numero = 50;
var decimal = 0.5;
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

