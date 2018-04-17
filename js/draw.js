    // FUNCIÓN DEL BOTON MOSTRAR
function mostrar() {
    $("#boton").click(function(){
        $("#body td").fadeIn(2000);
    });
    addToLocal();
}
    // FUNCION PARA AÑADIR LOS DATOS INTRODUCIDOS EN EL FORM AL LOCAL STORAGE DEL BROWSER
function addToLocal() {
	if (typeof(Storage) !== "undefined") {
        // new arrays
        var aNames = [];
        // get input text
        var txtName = document.getElementById("txtName");
        var txtEmail = document.getElementById("txtEmail");
        var nombre = txtName.value;
        var email = txtEmail.value;

        // get array from local and its data or create array if not exist
        if ((nombre!=="") && (email!=="")) {
            var retrievedUser = localStorage.getItem("aNames");
            if (retrievedUser == null){ 
                // cretae first id
                var id = 1;
                // push first data
                aNames.push({"nombre": nombre, "email": email, "id": id});
                // create array in localStorage
                localStorage.setItem('aNames', JSON.stringify(aNames));
                // add first row to table
                AddRow(nombre, email);
                // clear form
                clearForm();
            } else {
                // Parse the serialized data back into an array of objects
                aNames = JSON.parse(retrievedUser);
                if (aNames.length == 0) {
                    // cretae first id
                    var id = 1;
                    // push first data
                    aNames.push({"nombre": nombre, "email": email, "id": id});
                    // create array in localStorage
                    localStorage.setItem('aNames', JSON.stringify(aNames));
                    // add first row to table
                    AddRow(nombre, email);
                    // clear form
                    clearForm();
                } else if (checkEmail(email, aNames, aNames.length)) {
                    // get array length
                    var size = aNames.length;
                    // get the last id
                    var prevId = aNames[size - 1].id;
                    // create new id, new id = last id + 1
                    var id = prevId + 1;
                    // Push the new data onto the array
                    aNames.push({"nombre": nombre, "email": email, "id": id});
                    // Re-serialize the array back into a string and store it in localStorage
                    localStorage.setItem('aNames', JSON.stringify(aNames));
                    // Add data to table
                    AddRow(nombre, email);
                    //clear form
                    clearForm();
                } else {
                    alert("El email introducido ya está registrado, pruebe con otro.");
                    clearForm();
                }
            }
        } else {
            alert('Completa los campos nombre e email.');
        }        
    } else {
        document.getElementById("result").innerHTML = "Sorry, your browser does not support Web Storage...";
    }

}

    // FUNCION PARA LIMPIAR EL ESPACIO DE TEXTO DEL FORMULARIO
function clearForm(){
    document.getElementById("txtName").value = "";
    document.getElementById("txtEmail").value = "";
}


    // FUNCION PARA CONTROLAR QUE NO SE REPITAN LOS EMAILS
function checkEmail(email, aNames, length){
    for (i = 0; i <= (aNames.length -1); i++) {
        var saved = aNames[i].email;
        if (email === saved) {    
            return false;
        }
        
    }
    return true;
}
    // FUNCION PARA CARGAR LOS DATOS DEL LOCALSTORAGE EN LA TABLA AL CARGAR LA PAGINA

function createTable(){
    limpiarConsola();
    clearForm();
    var aNames = [];
    // retrieve data from localstorage
    var retrievedUser = localStorage.getItem("aNames");
    //aNames = JSON.parse(retrievedUser);
    // if empty data 
    if (retrievedUser == null){
        console.log("No hay datos guardados, se mostrará un tabla vacía.");
    // if data retrieved
    } else {
        console.log("Cargando datos en la tabla....");
        aNames = JSON.parse(retrievedUser);
        for (var i = 0; i < aNames.length; i++) {
            AddRow(aNames[i].nombre, aNames[i].email);
        }
    }
}

        // FUNCION PARA ELIMINAR DATOS DEL LOCALSTORAGE CORRESPONDIENTES A LA FILA SELECCIONADA
function RemoveFromLocal(img){
    // get the position of the row in the table
    var row = img.parentNode.parentNode.rowIndex;
    // get the id of the row from localStorage
    var aNames = [];
    var retrievedUser = localStorage.getItem("aNames");
    aNames = JSON.parse(retrievedUser);
    // search id in array, its position is the row number minus 1
    var pos = row - 1;
    // delete position in the array
    aNames.splice(pos,1);
    // update localStorage
    localStorage.setItem('aNames', JSON.stringify(aNames));
    // we delete the row in the table
    Remove(img);


}
    // FUNCION PARA ELIMINAR FILA CUANDO CLICKEAS IMAGEN

function Remove(img) {
    //Determine the reference of the Row using the Button.
    var row = img.parentNode.parentNode;
    //Get the reference of the Table.
    var table = document.getElementById("tablaParticipantes");

    //Delete the Table row using it's Index.
    //$('#tablaParticipantes tr').eq(row.rowIndex).fadeOut(500);
    table.deleteRow(row.rowIndex);
}

    //  FUNCION PARA AÑADIR A LA TABLA LA FILA CON LOS DATOS INTRODUCIDOS

function AddRow(name, email) {
    //Get the reference of the Table's TBODY element.
    var tBody = document.getElementById("tablaParticipantes").getElementsByTagName("TBODY")[0];

    //Add Row.
    var row = tBody.insertRow(-1);
    $('#tablaParticipantes tr').eq(row.rowIndex).hide();
    $('#tablaParticipantes tr').eq(row.rowIndex).fadeIn(1000);


    //Add Name cell.
    var cell = row.insertCell(-1);
    cell.innerHTML = name;

    //Add email cell.
    cell = row.insertCell(-1);
    cell.innerHTML = email;

    //Add img cell.
    cell = row.insertCell(-1);

    var tick = document.createElement("img");
    tick.setAttribute("src", "../img/tick.png");
    tick.setAttribute("height", "50");
    tick.setAttribute("width", "50");
    tick.setAttribute("onclick", "RemoveFromLocal(this);");
    tick.setAttribute("alt", "dele.");
    tick.setAttribute("onmouseenter", "transformToTick(this)");
    tick.setAttribute("onmouseleave", "transformToDele(this)");
    tick.value = "Remove";
    tick.type = "img";
    cell.appendChild(tick);
}

    // FUNCIONES ARA TRANSFORMAR LA IMAGEN AL PASAR POR ENCIMA

function transformToDele(img){
    img.setAttribute("src", "../img/tick.png");
}

function transformToTick(img){
    img.setAttribute("src", "../img/delete.png");
}
    // FUNCION PARA GENERAR UN ALEATORIO
function aleatorio(a,b) {
    return Math.round(Math.random()*(b-a)+parseInt(a));
}

    // FUNCION PARA LA PIZARRA
function sorteoPizarra(){
    var aNames = [];    
    var retrievedUser = localStorage.getItem("aNames");
    if (retrievedUser == null){
        alert("No hay datos guardados. Introduzca datos en el formulario para poder realizar el sorteo.");
        return false;
    } else {
        aNames = JSON.parse(retrievedUser);
        if (aNames.length <= 1) {
            alert("Introduzca al menos dos participantes para poder realizar el sorteo."); 
            return false;
        } else {
            var size = aNames.length;
            var min = 0;
            var max = size -1;

            var random = aleatorio(min, max);

            var nombre = aNames[random].nombre;
            var email = aNames[random].email;

            if (addPizarra(aNames[random])){
                var texto = "'" + nombre + "', con email: '" + email + "'. ¡Enhorabuena!:)";
                var obj = {"texto": texto, "random": random, "email": email};
                return obj;
            } else {
                return sorteoPizarra();
            }
            
        }
    }
}

    // FUNCION PARA LA REUNION
function sorteoReunion(){
    var aNames = [];

    var retrievedUser = localStorage.getItem("aNames");
    aNames = JSON.parse(retrievedUser);

    var size = aNames.length;
    var min = 0;
    var max = size -1;
    var random = aleatorio(min, max);
    var nombre = aNames[random].nombre;
    var email = aNames[random].email;


    if (addReunion(aNames[random])){
        var texto = "'" + nombre + "', con email: '" + email + "'. ¡Enhorabuena!:)";
        var obj = {"texto": texto, "random": random, "email": email};
        return obj;
    } else {
        return sorteoReunion();
    }
}

    // FUNCION PARA LIMPIAR LA CONSOLA
function limpiarConsola() {
    //document.getElementById("out").value = "";
    $("#out").val("");
}

    // FUNCION PARA EL TEXTO DE LA CONSOLA
function setAreaText (){
    desmodificarFilas();
    limpiarConsola();
    var a = sorteoPizarra();
    if (a !== false) {
        var b = sorteoReunion();
    }
    if (a===false) {
        return;
    }
    if (b.texto !== a.texto){
        var text = "->OpositaTest: Iniciando sorteo de la pizarra...\n->OpositaTest: El ganador es ...\n->OpositaTest: " + a.texto;
        text += "\n->OpositaTest: ...\n->OpositaTest: ...\n->OpositaTest: Iniciando sorteo de la reunión ...\n->OpositaTest: El ganador es ...\n->OpositaTest: " + b.texto
         + "\n->Opositatest: Hasta la semana que viene!";
        //$("#out").val(text);
        escribir("out", text, 50);
        modificarFila(a.email);
        modificarFila(b.email);
    } else{
        var pizarra = [];
        localStorage.setItem('pizarra', JSON.stringify(pizarra));
        var reunion = [];
        localStorage.setItem('reunion', JSON.stringify(reunion));
        setAreaText();
    }
}

    //FUNCION PARA INTERVALO DE TIMEPO AL ESCRIBIR
function escribir(contenedor,writer,speed){

   longitud = writer.length;

   var area = document.getElementById(contenedor);
   var i=0;
   tiempo = setInterval(function(){
      area.value = area.value.substr(0,area.value.length-1) + writer.charAt(i)+ " ";
      if(i >= longitud){
         clearInterval(tiempo);
         area.value = area.value.substr(0,longitud);
         return true;
      } else {
         i++;
      }},speed);
};

function addPizarra(aNames){
    var pizarra = [];
    var retrievedPizarra = localStorage.getItem("pizarra");
    if (retrievedPizarra == null){
        pizarra.push({"nombre": aNames.nombre, "email": aNames.email, "id": aNames.id});
        // create array in localStorage
        localStorage.setItem('pizarra', JSON.stringify(pizarra));
        return true;
    } else {
        pizarra = JSON.parse(retrievedPizarra);
        if (pizarra.length === 0){
            pizarra.push({"nombre": aNames.nombre, "email": aNames.email, "id": aNames.id});
            // create array in localStorage
            localStorage.setItem('pizarra', JSON.stringify(pizarra));
            return true;
        } else if(pizarra.length === 1){
            if (aNames.email === pizarra[0].email){
                return false;
            } else {
                pizarra.splice(0, 1);
                pizarra.push({"nombre": aNames.nombre, "email": aNames.email, "id": aNames.id});
                localStorage.setItem('pizarra', JSON.stringify(pizarra));
                return true;
            }
        }
    }
}

function addReunion(aNames){
    var reunion = [];
    var retrievedreunion = localStorage.getItem("reunion");
    if (retrievedreunion == null){
        reunion.push({"nombre": aNames.nombre, "email": aNames.email, "id": aNames.id});
        // create array in localStorage
        localStorage.setItem('reunion', JSON.stringify(reunion));
        return true;
    } else {
        reunion = JSON.parse(retrievedreunion);
        if (reunion.length === 0){
            reunion.push({"nombre": aNames.nombre, "email": aNames.email, "id": aNames.id});
            // create array in localStorage
            localStorage.setItem('reunion', JSON.stringify(reunion));
            return true;
        } else if(reunion.length === 1){
            if (aNames.email === reunion[0].email){
                return false;
            } else {
                reunion.splice(0, 1);
                reunion.push({"nombre": aNames.nombre, "email": aNames.email, "id": aNames.id});
                localStorage.setItem('reunion', JSON.stringify(reunion));
                return true;
            }
        }
    }
}

$(document).ready(function(){
    $(".ocultar").click(function(){
        $("#body td").fadeToggle(2000);
    });
});

function toggletable(){
    $("#boton").click(function(){
        $("#body td").fadeToggle(2000);
    });
}

function modificarFila(email){
    var tableRow = $("td").filter(function() {
        return $(this).text() == email;
    }).closest("tr");
    tableRow.css({"font-size": "120%", "color": "red", "opacity": "0.7"});
}

function desmodificarFilas(){
    $("#body tr").css({"font-size": "14px", "color": "black", "opacity": "0.3"});
}