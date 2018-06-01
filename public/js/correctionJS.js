opos =
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
scoreCorrectQuestions = $("[data-js='scoreCorrectQuestions']");
scoreIncorrectQuestions = $("[data-js='scoreIncorrectQuestions']");
scoreBlankQuestions = $("[data-js='scoreBlankQuestions']");

ncorrectas = $("[data-js='numberCorrectQuestions']");
nincorrectas = $("[data-js='numberIncorrectQuestions']");
nblanco = $("[data-js='numberBlankQuestions']");
ntotal = $("[data-js='totalQuestions']");

selection = $("[data-js='changeOpoSelect']");

nota = $("[data-js='toPlaceMark']");
notaCol = $("[data-js='markCol']");

btnCorrect = $("[data-js='corregir']");


$(document).ready(function(){
    clear();
    startEvents();
    // changeOpos();
    }
);

function clear() {
    // $("[data-js='changeOpoSelect']").val("Personalizado");
    ncorrectas.val("0");
    nincorrectas.val("0");
    nblanco.val("0");
    scoreCorrectQuestions.val("0");
    scoreIncorrectQuestions.val("0");
    scoreBlankQuestions.val("0");
    ntotal.val("0");
    nota.val("0");
}

function changeOpos() {
    for( i=0; i < opos.length; i++){
        if (selection.val() === opos[i]["value"]){

            scoreCorrectQuestions.prop('disabled', true);
            scoreIncorrectQuestions.prop('disabled', true);
            scoreBlankQuestions.prop('disabled', true);
            scoreCorrectQuestions.val(opos[i]["puntuacion"][0]);
            scoreIncorrectQuestions.val(opos[i]["puntuacion"][1]);
            scoreBlankQuestions.val(opos[i]["puntuacion"][2]);

        } else if(selection.val() === "personalizado") {

            scoreCorrectQuestions.prop('disabled', false);
            scoreIncorrectQuestions.prop('disabled', false);
            scoreBlankQuestions.prop('disabled', false);
            scoreCorrectQuestions.val("0");
            scoreIncorrectQuestions.val("0");
            scoreBlankQuestions.val("0");
        }
    }
}

function corregir(){
    if ((parseInt(ncorrectas.val()) > 0) && (parseInt(nincorrectas.val()) > 0) && (parseInt(nblanco.val()) > 0)) {
        total =  parseInt(ncorrectas.val()) + parseInt(nincorrectas.val()) + parseInt(nblanco.val());

        if ((scoreCorrectQuestions.val() === "0") || (scoreIncorrectQuestions.val() === "0")) {
            alert("Establezca un sistema de evaluación válido.");
        } else if (total === "0") {
            alert("Introduzca una cantidad superior a 0 de preguntas");
        } else {
            totalCorrectas = parseFloat(scoreCorrectQuestions.val()) * parseInt(ncorrectas.val());
            totalIncorrectas = parseFloat(scoreIncorrectQuestions.val()) * parseInt(nincorrectas.val());
            totalBlanco = parseFloat(scoreBlankQuestions.val()) * parseInt(nblanco.val());

            calculoNota = totalBlanco + totalCorrectas + totalIncorrectas;

            nota.text("Su nota es de " + calculoNota + " sobre " + total * scoreCorrectQuestions.val());
            notaCol.addClass("colored");
        }
    } else {
        alert("No puede haber un número negativo de preguntas.");
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
    return patron.test(tecla_final);
}

function sumaEvent() {
    if (ncorrectas.val() === ""){
        ncorrectas.val("0");
    }
    if (nincorrectas.val() === ""){
        nincorrectas.val("0");
    }
    if  (nblanco.val() === ""){
        nblanco.val("0");
    }

    suma = parseInt(ncorrectas.val()) + parseInt(nincorrectas.val()) + parseInt(nblanco.val());
    ntotal.val("" + suma);
}

function puntuacionEvent() {
    if (scoreCorrectQuestions.val() === ""){
        scoreCorrectQuestions.val("0");
    }
    if (scoreIncorrectQuestions.val() === ""){
        scoreIncorrectQuestions.val("0");
    }
    if  (scoreBlankQuestions.val() === ""){
        scoreBlankQuestions.val("0");
    }
}

function clearInput(focus) {
    focus.val("");
}

function startEvents() {
    // focusout events
    scoreCorrectQuestions.on( "focusout", function( event ) {
        puntuacionEvent();
    });
    scoreIncorrectQuestions.on( "focusout", function( event ) {
        puntuacionEvent();
    });
    scoreBlankQuestions.on( "focusout", function( event ) {
        puntuacionEvent();
    });

    ncorrectas.on( "focusout", function( event ) {
        sumaEvent();
    });
    nincorrectas.on( "focusout", function( event ) {
        sumaEvent();
    });
    nblanco.on( "focusout", function( event ) {
        sumaEvent();
    });

    // keypress events
    ncorrectas.on( "keypress", function( event ) {
        return justNumbers(event);
    });
    nincorrectas.on( "keypress", function( event ) {
        return justNumbers(event);
    });
    nblanco.on( "keypress", function( event ) {
        return justNumbers(event);
    });

    // click events
    btnCorrect.on( "click", function( event ) {
        corregir();
    });
    selection.on( "click", function( event ) {
        changeOpos();
    });


    // focus events
    scoreCorrectQuestions.on( "focus", function( event ) {
        clearInput(scoreCorrectQuestions);
    });
    scoreIncorrectQuestions.on( "focus", function( event ) {
        clearInput(scoreIncorrectQuestions);
    });
    scoreBlankQuestions.on( "focus", function( event ) {
        clearInput(scoreBlankQuestions);
    });
    ncorrectas.on( "focus", function( event ) {
        clearInput(ncorrectas);
    });
    nincorrectas.on( "focus", function( event ) {
        clearInput(nincorrectas);
    });
    nblanco.on( "focus", function( event ) {
        clearInput(nblanco);
    });
}

