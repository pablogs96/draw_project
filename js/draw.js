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
        var retrievedUser = localStorage.getItem("aNames");
        if ((nombre!=="") && (email!=="")) {
            if (retrievedUser == null){
                aNames.push({"nombre": nombre, "email": email});
                localStorage.setItem('aNames', JSON.stringify(aNames));
                AddRow(nombre, email);
            }else {
                AddRow(nombre, email);
                // Parse the serialized data back into an array of objects
                aNames = JSON.parse(retrievedUser);
                // Push the new data (whether it be an object or anything else) onto the array
                aNames.push({"nombre": nombre, "email": email});
                // Re-serialize the array back into a string and store it in localStorage
                localStorage.setItem('aNames', JSON.stringify(aNames));
                // Add data to table
            }
        } else {
                alert('Completa los campos nombre e email.');
        }        
    } else {
        document.getElementById("result").innerHTML = "Sorry, your browser does not support Web Storage...";
    }
}

        // FUNCION PARA SACAR MENSAJES POR LA CONSOLA
function showMessage(){
    var text = " ->OpositaTest: Iniciando sorteo de la pizarra...\n->OpositaTest: ";
    var area=document.getElementById("out").value = text;
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
    tick.setAttribute("onclick", "Remove(this);");
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