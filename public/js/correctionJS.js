opos =
    [
        {
            "value": "2",
            "puntuacion": [10, -0.33, 0]
        },

        {
            "value": "4",
            "puntuacion": [20, -0.33, 0]
        },

        {
            "value": "5",
            "puntuacion": [30, -0.33, 0]
        },

        {
            "value": "6",
            "puntuacion": [40, -0.33, 0]
        },

        {
            "value": "7",
            "puntuacion": [50, -0.33, 0]
        },

        {
            "value": "8",
            "puntuacion": [60, -0.33, 0]
        },

        {
            "value": "10",
            "puntuacion": [70, -0.33, 0]
        },

        {
            "value": "11",
            "puntuacion": [80, -0.33, 0]
        },

        {
            "value": "0",
            "puntuacion": [0, 0, 0]
        }
    ];
scoreCorrectQuestions = $("[data-js='scoreCorrectQuestions']");
scoreIncorrectQuestions = $("[data-js='scoreIncorrectQuestions']");
scoreBlankQuestions = $("[data-js='scoreBlankQuestions']");

ncorrectas = $("[data-js='numberCorrectQuestions']");
nincorrectas = $("[data-js='numberIncorrectQuestions']");
nblanco = $("[data-js='numberBlankQuestions']");
ntotal = $("[data-js='totalQuestions']");

selection = $("[data-js='changeOppostionSelect']");

nota = $("[data-js='toPlaceMark']");
notaCol = $("[data-js='markCol']");

btnCorrect = $("[data-js='corregir']");


$(document).ready(function(){
    clear();
    startEvents();
    changeOpos();
    }
);

function clear() {
    selection.val("0");
    ncorrectas.val("0");
    nincorrectas.val("0");
    nblanco.val("0");
    scoreCorrectQuestions.val("0");
    scoreIncorrectQuestions.val("0");
    scoreBlankQuestions.val("0");
    ntotal.val("0");
    nota.val("");
}

function changeOpos() {
    for( i=0; i < opos.length; i++){
        if (selection.val() === opos[i]["value"]){
            scoreCorrectQuestions.val(opos[i]["puntuacion"][0]);
            scoreIncorrectQuestions.val(opos[i]["puntuacion"][1]);
            scoreBlankQuestions.val(opos[i]["puntuacion"][2]);
        }
    }
}

function corregir(){
    if ((parseInt(ncorrectas.val()) >= 0) && (parseInt(nincorrectas.val()) >= 0) && (parseInt(nblanco.val()) >= 0)) {
        total =  parseInt(ncorrectas.val()) + parseInt(nincorrectas.val()) + parseInt(nblanco.val());
            totalCorrectas = parseFloat(scoreCorrectQuestions.val()) * parseInt(ncorrectas.val());
            totalIncorrectas = parseFloat(scoreIncorrectQuestions.val()) * parseInt(nincorrectas.val());
            totalBlanco = parseFloat(scoreBlankQuestions.val()) * parseInt(nblanco.val());

            calculoNota = totalBlanco + totalCorrectas + totalIncorrectas;

            nota.text("Su nota es de " + calculoNota + " sobre " + total * scoreCorrectQuestions.val());
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
    // focus events
    scoreCorrectQuestions.on( "focus", function( event ) {
        clearInput(scoreCorrectQuestions);
        setPersonalizado();
    });
    scoreIncorrectQuestions.on( "focus", function( event ) {
        clearInput(scoreIncorrectQuestions);
        setPersonalizado();
    });
    scoreBlankQuestions.on( "focus", function( event ) {
        clearInput(scoreBlankQuestions);
        setPersonalizado();
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
    scoreCorrectQuestions.on( "keypress", function( event ) {
        return justNumbers(event);
    });
    scoreIncorrectQuestions.on( "keypress", function( event ) {
        return justNumbers(event);
    });
    scoreBlankQuestions.on( "keypress", function( event ) {
        return justNumbers(event);
    });
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

}

function setPersonalizado() {
    scoreCorrectVal = getInputVal(scoreCorrectQuestions);
    scoreIncorrectVal = getInputVal(scoreIncorrectQuestions);
    scoreBlankVal = getInputVal(scoreBlankQuestions);
    selection.val("0");
    scoreCorrectQuestions.val(scoreCorrectVal);
    scoreIncorrectQuestions.val(scoreIncorrectVal);
    scoreBlankQuestions.val(scoreBlankVal);
}

function getInputVal(input) {
    return input.val();
}

