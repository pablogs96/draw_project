var name = document.forms["formularioRegistro"]["nombre"].value;
var mail = document.forms["formularioRegistro"]["correo"].value;

function validateForm() {
    if (name == ""){
        alert("El campo 'Nombre' debe de estar cubierto");
        return false;
    }
    if (mail == ""){
        alert("El campo 'Correo' debe de estar cubierto");
        return false;
    }
} 

function registro() {
	
}