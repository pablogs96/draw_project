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
    $("[data-js='ncorrectas']").val("0");
    $("[data-js='nincorrectas']").val("0");
    $("[data-js='nblanco']").val("0");
    $("[data-js='total']").val("0");
    $("[data-js='nota']").val("0");
}

function changeOpos() {
    var selected = $("[data-js='changeOpoSelect']").val();
    for(var i=0; i < opos.length; i++){
        if (selected === opos[i]["value"]){
            $("[data-js='pcorrectas']").prop('disabled', true);
            $("[data-js='pincorrectas']").prop('disabled', true);
            $("[data-js='pblanco']").prop('disabled', true);
            $("[data-js='pcorrectas']").val(opos[i]["puntuacion"][0]);
            $("[data-js='pincorrectas']").val(opos[i]["puntuacion"][1]);
            $("[data-js='pblanco']").val(opos[i]["puntuacion"][2]);
        } else if(selected === "personalizado") {
            $("[data-js='pcorrectas']").prop('disabled', false);
            $("[data-js='pincorrectas']").prop('disabled', false);
            $("[data-js='pblanco']").prop('disabled', false);
            $("[data-js='pcorrectas']").val("0");
            $("[data-js='pincorrectas']").val("0");
            $("[data-js='pblanco']").val("0");
        }
    }
}
/*$("[data-js='phoneNumber']")*/

function corregir(){
    pcorrectas = $("[data-js='pcorrectas']").val();
    pincorrectas = $("[data-js='pincorrectas']").val();
    pblanco = $("[data-js='pblanco']").val();

    ncorrectas = $("[data-js='ncorrectas']").val();
    nincorrectas = $("[data-js='nincorrectas']").val();
    nblanco = $("[data-js='nblanco']").val();

    total = $("[data-js='total']").val();

    if ((pcorrectas === "0") || (pincorrectas === "0")) {
        alert("Establezca un sistema de evaluación válido.");
    } else if (total === "0") {
        alert("Introduzca una cantidad superior a 0 de preguntas");
    } else {
        totalCorrectas = parseFloat(pcorrectas) * parseInt(ncorrectas);
        totalIncorrectas = parseFloat(pincorrectas) * parseInt(nincorrectas);
        totalBlanco = parseFloat(pblanco) * parseInt(nblanco);

        nota = totalBlanco + totalCorrectas + totalIncorrectas;

        $("[data-js='toPlaceMark']").text("Su nota es de " + nota + " sobre " + total * pcorrectas);
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
    corr = $("[data-js='ncorrectas']").val();
    incorr = $("[data-js='nincorrectas']").val();
    blanco = $("[data-js='nblanco']").val();

    if (corr === ""){
        corr = 0;
        $("[data-js='ncorrectas']").val("0");
    }
    if (incorr === ""){
        incorr = 0;
        $("[data-js='nincorrectas']").val("0");
    }
    if  (blanco === ""){
        blanco = 0;
        $("[data-js='nblanco']").val("0");
    }

    suma = parseInt(corr) + parseInt(incorr) + parseInt(blanco);
    total.val("" + suma);
}

function puntuacionEvent() {
    pcorrectas = $("[data-js='pcorrectas']").val();
    pincorrectas = $("[data-js='pincorrectas']").val();
    pblanco = $("[data-js='pblanco']").val();

    if (pcorrectas === ""){
        $("[data-js='pcorrectas']").val("0");
    }
    if (pincorrectas === ""){
        $("[data-js='pincorrectas']").val("0");
    }
    if  (pblanco === "") {
        $("[data-js='pblanco']").val("0");
    }
}

function startEvents() {
    $("[data-js='pcorrectas']").on( "focusout", function( event ) {
        puntuacionEvent();
    });
    $("[data-js='pincorrectas']").on( "focusout", function( event ) {
        puntuacionEvent();
    });
    $("[data-js='pblanco']").on( "focusout", function( event ) {
        puntuacionEvent();
    });
    $("[data-js='ncorrectas']").on( "focusout", function( event ) {
        sumaEvent();
    });
    $("[data-js='ncorrectas']").on( "keypress", function( event ) {
        return justNumbers(event);
    });
    $("[data-js='nincorrectas']").on( "focusout", function( event ) {
        sumaEvent();
    });
    $("[data-js='nincorrectas']").on( "keypress", function( event ) {
        return justNumbers(event);
    });
    $("[data-js='nblanco']").on( "focusout", function( event ) {
        sumaEvent();
    });
    $("[data-js='nblanco']").on( "keypress", function( event ) {
        return justNumbers(event);
    });
    $("[data-js='corregir']").on( "click", function( event ) {
        corregir();
    });
    $("[data-js='changeOpoSelect']").on( "click", function( event ) {
        changeOpos();
    });
}

