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
                } else if (checkEmail(email, aNames)) {
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
function checkEmail(email, aNames){
    for (i = 0; i < aNames.length; i++) {
        var saved = aNames[i].email;
        if (email === saved) {
            return false;
        } else {
            return true;
        }
    }
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

    // FUNCION PARA SACAR MENSAJES POR LA CONSOLA
function showMessage(){
    
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
    var name = row.getElementsByTagName("TD")[0].innerHTML;
    //Get the reference of the Table.
    var table = document.getElementById("tablaParticipantes");

    //Delete the Table row using it's Index.
    table.deleteRow(row.rowIndex);
}

    //  FUNCION PARA AÑADIR A LA TABLA LA FILA CON LOS DATOS INTRODUCIDOS

function AddRow(name, email) {
    //Get the reference of the Table's TBODY element.
    var tBody = document.getElementById("tablaParticipantes").getElementsByTagName("TBODY")[0];

    //Add Row.
    var row = tBody.insertRow(-1);

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
    //document.getElementsByTagName("img").src= "../img/tick.png";
    img.setAttribute("src", "../img/tick.png");
}

function transformToTick(img){
    //document.getElementsByTagName("img").src= "../img/delete.png";
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
    } else {
        aNames = JSON.parse(retrievedUser);
        if ((aNames.length == 0) || (aNames.length == 1)) {
            alert("Introduzca al menos dos participantes para poder realizar el sorteo.");
        } else {
            var min = 0;
            var size = aNames.length;
            var max = size -1;
            var random = aleatorio(min, max );
            var nombre = aNames[random].nombre;
            var email = aNames[random].email;
            var texto = "'" + nombre + "', con email: '" + email + "'. ¡Enhorabuena!:)";
            return texto;
        }
    }
}

    // FUNCION PARA LA REUNION
function sorteoReunion(){
    var aNames = [];    
    var retrievedUser = localStorage.getItem("aNames");
    if (retrievedUser == null){
        alert("No hay datos guardados. Introduzca datos en el formulario para poder realizar el sorteo.");
    } else {
        aNames = JSON.parse(retrievedUser);
        if ((aNames.length == 0) || (aNames.length == 1)) {
            alert("Introduzca al menos dos participantes para poder realizar el sorteo.");
        } else {
            var min = 0;
            var size = aNames.length;
            var max = size -1;
            var random = aleatorio(min, max );
            var nombre = aNames[random].nombre;
            var email = aNames[random].email;
            var texto = "'" + nombre + "', con email: '" + email + "'. ¡Enhorabuena!:)";
            return texto;
        }
    }
}

    // FUNCION PARA LIMPIAR LA CONSOLA
function limpiarConsola() {
    document.getElementById("out").value = "";
}

    // FUNCION PARA EL TEXTO DE LA CONSOLA
function setAreaText (){
    limpiarConsola();
    var a = sorteoPizarra();
    var b = sorteoReunion();
    if (b !== a){
        var text = "->OpositaTest: Iniciando sorteo de la pizarra...\n->OpositaTest: El ganador es ...\n->OpositaTest: " + a;
        text += "\n->OpositaTest: ...\n->OpositaTest: ...\n->OpositaTest: ...\n->OpositaTest: Iniciando sorteo de la reunión ...\n->OpositaTest: El ganador es ...\n->OpositaTest: " + b;
        escribir("out", text, 100);
    } else{
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