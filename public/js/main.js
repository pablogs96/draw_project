var puntuacion = 0;
var Id_encuesta = 1;
var PPos = 0;
var key = null;
var pos = 0;
var printed = 0;




// FUNCIÓN ONLOAD QUE INICIALIZA VARIAS FUNCIONES
$(document).ready($(window).on('scroll' ,function(){
    navbar();
    }
));

//FUNCION PARA MODIFICAR EL NAVBAR
function navbar(){
	var encuesta = $('#edge').offset().top;
	var stop = Math.round($(window).scrollTop());
	if (key) {
    	if (stop >= encuesta) {
	    	$("#nav").removeClass("colored-nav");
	        $('#nav').addClass('colored-nav2');
	        $("#nav").animate({height: "85px"});
	        key = false;
    	}
    } else if (!key){
    	if (stop < encuesta) {
	    	$("#nav").removeClass("colored-nav2");
	        $('#nav').addClass('colored-nav');
	        $("#nav").animate({height: "150px"});
	        key = true;
	    }
    }  

    if (key == null){
    	if (stop >= encuesta) {
	    	$("#nav").removeClass("colored-nav");
	        $('#nav').addClass('colored-nav2');
            $("#nav").css("height", "85px");
	        key = false;
    	} else if (stop < encuesta) {
	    	$("#nav").removeClass("colored-nav2");
	        $('#nav').addClass('colored-nav');
	        $("#nav").css("height", "150px");
	        key = true;
	    }
    }  	
}