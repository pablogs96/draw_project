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
	    	$("#nav").removeClass("navbar-light");
	        $('#nav').addClass('navbar-dark');
	        $('#nav').css("background-color", "black");
	        $("#nav").animate({height: "60px"});
	        $("#logoTop").css("filter", "invert(100%)");
	        key = false;
    	}
    } else if (!key){
    	if (stop < encuesta) {
	    	$("#nav").removeClass("navbar-dark");
	        $('#nav').css("background-color", "#EDDBF3");
	        $('#nav').addClass('navbar-light');
	        $("#nav").animate({height: "85px"});
            $("#logoTop").css("filter", "none");
	        key = true;
	    }
    }  

    if (key == null){
    	if (stop >= encuesta) {
	    	$("#nav").removeClass("navbar-light");
	        $('#nav').addClass('navbar-dark');
	        $('#nav').css("background-color", "black");
	        $("#nav").css("height", "60px");
            $("#logoTop").css("filter", "invert(100%)");
	        key = false;
    	} else if (stop < encuesta) {
	    	$("#nav").removeClass("navbar-dark");
	        $('#nav').css("background-color", "#EDDBF3");
	        $('#nav').addClass('navbar-light');
	        $("#nav").css("height", "85px");
            $("#logoTop").css("filter", "none");
	        key = true;
	    }
    }  	
}