var key = null;

// FUNCIÓN ONLOAD QUE INICIALIZA VARIAS FUNCIONES
$(document).ready($(window).on('scroll' ,function(){
    // navbar();
    }
));

//FUNCION PARA MODIFICAR EL NAVBAR
function navbar(){
	var encuesta = $('#edge').offset().top;
	var stop = Math.round($(window).scrollTop());
	if (key) {
    	if (stop >= encuesta) {
	        $("#nav").animate({height: "70px"});
	        key = false;
    	}
    } else if (!key){
    	if (stop < encuesta) {
	        $("#nav").animate({height: "90px"});
	        key = true;
	    }
    }

    if (key == null){
    	if (stop >= encuesta) {
            $("#nav").css("height", "70px");
            key = false;
    	} else if (stop < encuesta) {
	        $("#nav").css("height", "90px");
	        key = true;
	    }
    }
}