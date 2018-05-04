var puntuacion = 0;
var Id_encuesta = 1;
var PPos = 0;
var key = null;
var pos = 0;
var printed = 0;




// FUNCIÓN ONLOAD QUE INICIALIZA VARIAS FUNCIONES
function cargar(){
	$(window).on('scroll',function(){
	    navbar();
	});
	imprimirEncuesta(encuesta.id);
	imprimirSavedComments();
	actualizarComments();
}

//FUNCION PARA MODIFICAR EL NAVBAR
function navbar(){
	var encuesta = $('#encuesta').offset().top;
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

// FUNCION QUE SACA LAS PREGUNTAS Y RESPUESTAS
function imprimirEncuesta(id_encuesta){
		//datos encuesta
	var titulo = encuesta.title;
	// create titulo de la encuesta
	var h1 = document.createElement("h1");
	h1.setAttribute("id", "titulo_encuesta_h1");
	h1.setAttribute("style", "color: green");
	h1.innerHTML = titulo;
	$(h1).hide().appendTo("#encuesta").fadeIn(1000);
	imprimirPreguntas(id_encuesta, this.pos, getPPos());
}

function imprimirPreguntas(id_encuesta, pos, pos_pregunta){
		//datos pregunta
	var id_pregunta = encuesta.preguntas[pos_pregunta].id;
	var enunciado_pregunta = encuesta.preguntas[pos_pregunta].text;
	var ruta_img = encuesta.preguntas[pos_pregunta].image;

	// create imagen de la pregunta
	var img = document.createElement("img");
	img.setAttribute("src", ruta_img);
	img.setAttribute("class", "img-fluid rounded");
	$(img).hide().appendTo("#img_encuesta").fadeIn(1000);
	//$("#img_encuesta").append(img);

	// create enunciado de la pregunta
	var h4 = document.createElement("h4");
	h4.setAttribute("id", "enunciado_pregunta_h4");
	h4.innerHTML = enunciado_pregunta;
	$(h4).hide().appendTo("#pregunta").fadeIn(1000);
	//$("#pregunta").append(h4);

	// create respuestas de la pregunta
	for (var j = 0; j < encuesta.preguntas[pos_pregunta].respuestas.length; j++){
		//datos respuesta
		var id_respuesta = encuesta.preguntas[pos_pregunta].respuestas[j].id;
		var enunciado_respuesta = encuesta.preguntas[pos_pregunta].respuestas[j].text;
		

		var div = document.createElement("div");
	    div.setAttribute("class", "col-12 justify-content-center text-center");
	    div.setAttribute("style", "padding-bottom:20px");

		var respuesta = document.createElement("input");
	    respuesta.setAttribute("type", "button");
	    respuesta.setAttribute("id", id_respuesta);
	    respuesta.setAttribute("class", "btn btn-dark btn-lg");
	    respuesta.setAttribute("value", enunciado_respuesta);
	    respuesta.setAttribute("data-idResponse", id_respuesta);
	    respuesta.setAttribute("data-idQuestion", id_pregunta);
	    respuesta.setAttribute("data-idEncuesta", id_encuesta);
	    respuesta.setAttribute("onclick", "accion(this)");

	    $(respuesta).hide().appendTo(div).fadeIn(1000);
    	//div.append(respuesta);
	   
		//$("#div_respuestas").append(div);
		$(div).hide().appendTo("#div_respuestas").fadeIn(1000);
		setPPos(pos_pregunta + 1);
		//progress bar
		var num_preg = encuesta.preguntas.length + 1;
		var newWidth = (pos_pregunta + 1) / num_preg * 100;
		progress(newWidth);
	}
	this.printed ++;
}

// funcion que imprime el resultado

function imprimirSolucion(pos){
	for (var i = 0; i < encuesta.resultados.length; i++){
		if ( (encuesta.resultados[i].minVal)  <= (this.puntuacion) && (this.puntuacion) <= (encuesta.resultados[i].maxVal) ){
			var solucion = encuesta.resultados[i].text;
			var ruta = encuesta.resultados[i].image;
			var explicacion = encuesta.resultados[i].explanation;

			var h1 = document.createElement("h1");
			h1.innerHTML = solucion;
			$(h1).hide().appendTo("#titulo_encuesta").fadeIn(1000);

			var img = document.createElement("img");
			img.setAttribute("src", ruta);
			img.setAttribute("height", "400px");
            img.setAttribute("width", "400px");
			$(img).hide().appendTo("#img_encuesta").fadeIn(1000);

			var result = document.createElement("p");
			result.innerHTML = explicacion;
			$(result).hide().appendTo("#pregunta").fadeIn(1000);

			var redo = document.createElement("a");
			redo.setAttribute("type", "button");
			redo.setAttribute("class", "btn btn-primary");
			redo.setAttribute("onclick", "playAgain()");
			redo.setAttribute("style", "padding: 15px;");
			redo.setAttribute("href", "/encuesta/" + encuesta.id);
			redo.innerHTML = "Volver a jugar";

            var btnSorteo = document.createElement("button");
            btnSorteo.setAttribute("type", "button");
            btnSorteo.setAttribute("class", "btn btn-warning btn-lg");
            btnSorteo.setAttribute("onclick", "suscrption()");
            btnSorteo.setAttribute("style", "padding: 12px;");
            btnSorteo.setAttribute("data-toggle", "modal");
            btnSorteo.setAttribute("data-target", "#myModal");
            btnSorteo.innerHTML = "Suscríbete al sorteo";

			var col = document.createElement("div");
            col.setAttribute("class", "col-6 justify-content-center text-right");
            col.setAttribute("id", "colRedo");
            $(col).appendTo("#div_respuestas");

            var col1 = document.createElement("div");
            col1.setAttribute("class", "col-6 justify-content-center text-left");
            col1.setAttribute("id", "colSorteo");
            $(col1).appendTo("#div_respuestas");

            $(redo).hide().appendTo("#colRedo").fadeIn(1000);
            $(btnSorteo).hide().appendTo("#colSorteo").fadeIn(1000);

            progress(100);
		}
	}
}

// funcion que realizan los botones de las respuestas
function accion(boton){
	var selfPregunta;
	var selfRespuesta;
	var selfValor;

    for (var j = 0; j < encuesta.preguntas.length; j++) {
        if ($(boton).attr("data-idQuestion") == encuesta.preguntas[j].id) {
            selfPregunta =  encuesta.preguntas[j];
            for (var i = 0; i < selfPregunta.respuestas.length; i++) {
                if ($(boton).attr("data-idResponse") == selfPregunta.respuestas[i].id) {
                    selfRespuesta = selfPregunta.respuestas[i];
                }
            }
        }
    }
    selfValor = selfRespuesta.value;
    setPuntuacion(selfValor);
	limpiarSalida();
	// si el numero de preguntas impresas es igual al total de preguntas de esa encuesta, sacamos el resultado
	if (this.printed == encuesta.preguntas.length){
		imprimirSolucion(this.pos);
	} else {
		imprimirPreguntas(getEPos(), this.pos, getPPos());	
	}
}

// funcion que resetea la pregunta y los botones
function limpiarSalida(){
	$("#titulo_encuesta").empty();
	$("#img_encuesta").empty();
	$("#pregunta").empty();
	$("#div_respuestas").empty();
}

// funcion que saca por pantalla los comentarios ya guardados
function imprimirSavedComments(){
    for($i = 0; $i < encuesta.comentarios.length; $i++){
        var div = document.createElement("div");
        div.setAttribute("class", "row");
        div.setAttribute("style", "margin-bottom: 25px");

        // creo div col 1
        var div1 = document.createElement("div");
        div1.setAttribute("class", "col-sm-2 text-center");

        // creo img de div1
        var img = document.createElement("img");
        img.setAttribute("src", "https://png.icons8.com/metro/1600/gender-neutral-user.png");
        img.setAttribute("class", "img-circle");
        img.setAttribute("height", "65");
        img.setAttribute("width", "65");
        img.setAttribute("alt", "avatar");

        // meto img en div1
        $(img).hide().appendTo(div1).fadeIn(1000);
        //div1.append(img);

        // creo div col 2
        var div2 = document.createElement("div");
        div2.setAttribute("class", "col-sm-10");
        div2.setAttribute("style", "margin-bottom: 10px");

        // creo p de div2
        var p = document.createElement("p");
        p.innerHTML = encuesta.comentarios[$i].text;

        // meto p en div 2
        $(p).hide().appendTo(div2).fadeIn(1000);

        // meto div1 y div2 en div row
        $(div1).hide().appendTo(div).fadeIn(1000);
        $(div2).hide().appendTo(div).fadeIn(1000);

        //meto div row en div row rowComment
        $(div).hide().appendTo("#rowComment").fadeIn(1000);

	}
}
// funcion que imprime los comentarios nuevos y los guarda en bbdd
function imprimirComentarios(){
	var texto = $("#coment").val();
	if (texto != "") {
        //llamada ajax para persistirlo
	    saveComment();

	    var comment = [];
		comment.push({"texto": texto, "encuesta": encuesta});

        // creo div row
        var div = document.createElement("div");
        div.setAttribute("class", "row");
        div.setAttribute("style", "margin-bottom: 25px");

        // creo div col 1
        var div1 = document.createElement("div");
        div1.setAttribute("class", "col-sm-2 text-center");

        // creo img de div1
        var img = document.createElement("img");
        img.setAttribute("src", "https://png.icons8.com/metro/1600/gender-neutral-user.png");
        img.setAttribute("class", "img-circle");
        img.setAttribute("height", "65");
        img.setAttribute("width", "65");
        img.setAttribute("alt", "avatar");

        // meto img en div1
        $(img).hide().appendTo(div1).fadeIn(1000);

        // creo div col 2
        var div2 = document.createElement("div");
        div2.setAttribute("class", "col-sm-10");
        div2.setAttribute("style", "margin-bottom: 10px");

        // creo p de div2
        var p = document.createElement("p");
        p.innerHTML = comment[0].texto;

        // meto p en div 2
        $(p).hide().appendTo(div2).fadeIn(1000);

        // meto div1 y div2 en div row
        $(div1).hide().appendTo(div).fadeIn(1000);
        $(div2).hide().appendTo(div).fadeIn(1000);

        //meto div row en div row rowComment
        $(div).hide().appendTo("#rowComment").fadeIn(1000);
        $("#coment").val("");

        //actualizo el numero que aparece de comentarios escritos
	} else {

	}	
}
//llamada ajax
function saveComment(){
    var val = $("#coment").val();
    var data = {"texto": val, "encuesta":encuesta};
    $.ajax({
        data:  data, //datos que se envian a traves de ajax
        url:   '/encuesta/comment/save', //archivo que recibe la peticion
        type:  'post', //método de envio
        beforeSend: function () {
            console.log("Procesando, espere por favor...");
        },
        success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
            $new =  parseInt($("#number").text()) + 1;
            $("#number").text($new);
        }
    });
}

//funcion que actualiza el numero que se muestra de comentarios totales
function actualizarComments(){
    $("#number").text(encuesta.comentarios.length);
}

//funcion que elimina la salida de los comentarios
function limpiarComentarios(){
	$("#rowComment").empty();
}

// funcion que actualiza la barra de progreso
function progress(newWidth){
	$("#PBar").css({width: newWidth + "%"});
	$("#PBar").val(newWidth + "%");
}

//funcion que vuelve al principio de la encuesta actual
function playAgain() {

}

//funcion que muestra el formulario de suscripcion al sorteo
function suscrption() {

}

// GETTERS Y SETTERS
function setEPos(EPos){
	this.Id_encuesta = Id_encuesta;
}

function getEPos(){
	return this.Id_encuesta;
}

function setPPos(PPos){
	this.PPos = PPos;
}

function getPPos(){
	return this.PPos;
}

function setPuntuacion(nueva_puntuacion){
	this.puntuacion += nueva_puntuacion;
}

function getPuntuacion(){
	return this.puntuacion;
}