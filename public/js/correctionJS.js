var opos =
    [
        {
            "value": "jueces_fiscales",
            "puntuacion": [10, -0.33, 0]
        },

        {
            "value": "justicia",
            "puntuacion": [20, -0.33, 0]
        },

        {
            "value": "interior",
            "puntuacion": [30, -0.33, 0]
        },

        {
            "value": "hacienda",
            "puntuacion": [40, -0.33, 0]
        },

        {
            "value": "administracion_general",
            "puntuacion": [50, -0.33, 0]
        },

        {
            "value": "fuerzas_y_cuerpos_seguridad",
            "puntuacion": [60, -0.33, 0]
        },

        {
            "value": "sanidad",
            "puntuacion": [70, -0.33, 0]
        },

        {
            "value": "legislacion",
            "puntuacion": [80, -0.33, 0]
        },

        {
            "value": "abogacia",
            "puntuacion": [90, -0.33, 0]
        }
    ];
var valor = "";
$(document).ready(function(){
    clear();
    startEvents();
    changeOpos();
    }
);

function clear() {
    $("[data-js='changeOpoSelect']").val("personalizado");
    $("[data-js='numberCorrectQuestions']").val("0");
    $("[data-js='numberIncorrectQuestions']").val("0");
    $("[data-js='numberBlankQuestions']").val("0");
    $("[data-js='total']").val("0");
    $("[data-js='nota']").val("0");
}

function changeOpos() {
    var selected = $("[data-js='changeOpoSelect']").val();
    for(var i=0; i < opos.length; i++){
        if (selected === opos[i]["value"]){
            $("[data-js='scoreCorrectQuestions']").prop('disabled', true);
            $("[data-js='scoreIncorrectQuestions']").prop('disabled', true);
            $("[data-js='scoreBlankQuestions']").prop('disabled', true);
            $("[data-js='scoreCorrectQuestions']").val(opos[i]["puntuacion"][0]);
            $("[data-js='scoreIncorrectQuestions']").val(opos[i]["puntuacion"][1]);
            $("[data-js='scoreBlankQuestions']").val(opos[i]["puntuacion"][2]);
        } else if(selected === "personalizado") {
            $("[data-js='scoreCorrectQuestions']").prop('disabled', false);
            $("[data-js='scoreIncorrectQuestions']").prop('disabled', false);
            $("[data-js='scoreBlankQuestions']").prop('disabled', false);
            $("[data-js='scoreCorrectQuestions']").val("0");
            $("[data-js='scoreIncorrectQuestions']").val("0");
            $("[data-js='scoreBlankQuestions']").val("0");
        }
    }
}
/*$("[data-js='phoneNumber']")*/

function corregir(){
    scoreCorrectQuestions = $("[data-js='scoreCorrectQuestions']").val();
    scoreIncorrectQuestions = $("[data-js='scoreIncorrectQuestions']").val();
    scoreBlankQuestions = $("[data-js='scoreBlankQuestions']").val();

    ncorrectas = $("[data-js='numberCorrectQuestions']").val();
    nincorrectas = $("[data-js='numberIncorrectQuestions']").val();
    nblanco = $("[data-js='numberBlankQuestions']").val();

    total = $("[data-js='total']").val();

    if ((scoreCorrectQuestions === "0") || (scoreIncorrectQuestions === "0")) {
        alert("Establezca un sistema de evaluación válido.");
    } else if (total === "0") {
        alert("Introduzca una cantidad superior a 0 de preguntas");
    } else {
        totalCorrectas = parseFloat(scoreCorrectQuestions) * parseInt(ncorrectas);
        totalIncorrectas = parseFloat(scoreIncorrectQuestions) * parseInt(nincorrectas);
        totalBlanco = parseFloat(scoreBlankQuestions) * parseInt(nblanco);

        nota = totalBlanco + totalCorrectas + totalIncorrectas;

        $("[data-js='toPlaceMark']").text("Su nota es de " + nota + " sobre " + total * scoreCorrectQuestions);
        $("[data-js='markCol']").addClass("colored");
    }
}

function justNumbers(e)
{
    keynum = window.event ? window.event.keyCode : e.which;
    //teclas delete y supr
    if ((keynum === 8) || (keynum === 46))
        return true;

    // Patron de entrada, en este caso solo acepta numeros
    patron = /[0-9-]/;
    tecla_final = String.fromCharCode(keynum);
    valor = String.fromCharCode(keynum);
    return patron.test(tecla_final);
}

function sumaEvent() {
    total = $("[data-js='total']");
    corr = $("[data-js='numberCorrectQuestions']").val();
    incorr = $("[data-js='numberIncorrectQuestions']").val();
    blanco = $("[data-js='numberBlankQuestions']").val();

    if (corr === ""){
        corr = 0;
        $("[data-js='numberCorrectQuestions']").val("0");
    }
    if (incorr === ""){
        incorr = 0;
        $("[data-js='numberIncorrectQuestions']").val("0");
    }
    if  (blanco === ""){
        blanco = 0;
        $("[data-js='numberBlankQuestions']").val("0");
    }

    suma = parseInt(corr) + parseInt(incorr) + parseInt(blanco);
    total.val("" + suma);
}

function puntuacionEvent() {
    scoreCorrectQuestions = $("[data-js='scoreCorrectQuestions']").val();
    scoreIncorrectQuestions = $("[data-js='scoreIncorrectQuestions']").val();
    scoreBlankQuestions = $("[data-js='scoreBlankQuestions']").val();

    if (scoreCorrectQuestions === ""){
        $("[data-js='scoreCorrectQuestions']").val("0");
    }
    if (scoreIncorrectQuestions === ""){
        $("[data-js='scoreIncorrectQuestions']").val("0");
    }
    if  (scoreBlankQuestions === "") {
        $("[data-js='scoreBlankQuestions']").val("0");
    }
}

function startEvents() {
    $("[data-js='scoreCorrectQuestions']").on( "focusout", function( event ) {
        puntuacionEvent();
    });
    $("[data-js='scoreIncorrectQuestions']").on( "focusout", function( event ) {
        puntuacionEvent();
    });
    $("[data-js='scoreBlankQuestions']").on( "focusout", function( event ) {
        puntuacionEvent();
    });
    $("[data-js='numberCorrectQuestions']").on( "focusout", function( event ) {
        sumaEvent();
    });
    $("[data-js='numberCorrectQuestions']").on( "keypress", function( event ) {
        return justNumbers(event);
    });
    $("[data-js='numberIncorrectQuestions']").on( "focusout", function( event ) {
        sumaEvent();
    });
    $("[data-js='numberIncorrectQuestions']").on( "keypress", function( event ) {
        return justNumbers(event);
    });
    $("[data-js='numberBlankQuestions']").on( "focusout", function( event ) {
        sumaEvent();
    });
    $("[data-js='numberBlankQuestions']").on( "keypress", function( event ) {
        return justNumbers(event);
    });
    $("[data-js='corregir']").on( "click", function( event ) {
        corregir();
    });
    $("[data-js='changeOpoSelect']").on( "click", function( event ) {
        changeOpos();
    });
}

