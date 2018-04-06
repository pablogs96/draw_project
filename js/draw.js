function validateForm() {
    var x = document.getElementById("nombre");
    var y = document.getElementById("tdMail");
    var name = x.value;
    var mail = y.value;
    if (name == ""){
        alert("El campo 'Nombre' debe de estar cubierto");
        return false;
    }
    if (mail == ""){
        alert("El campo 'Correo' debe de estar cubierto");
        return false;
    }
} 

function addToLocal() {
    var x = document.getElementById("nombre");
    var y = document.getElementById("tdMail");
    var name = x.value;
    var mail = y.value;
	if (typeof(Storage) !== "undefined") {
        // Store
        localStorage.setItem("name", name);
        localStorage.setItem("mail", mail);
        refreshTable();
        // Retrieve
        //document.getElementById("result").innerHTML = localStorage.getItem("lastname");
    } else {
        document.getElementById("result").innerHTML = "Sorry, your browser does not support Web Storage...";
    }
}

function refreshTable(){
    /*var table = document.getElementById("tablaParticipantes");
    var row = table.insertRow(1);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    cell1.innerHTML = name;
    cell2.innerHTML = mail;
    cell3.innerHTML = '<img src="../img/delete.png" alt="delete" title="delete" height="50" width="50" onclick="deleteFromTable()">';*/

    var tabla=document.getElementById("tablaParticipantes");
    var tr =document.createElement("tr");
    var td =document.createElement("td");
    var td2 =document.createElement("td");
    var td3 =document.createElement("td");

    var del = document.createElement("BUTTON");
    var ima = document.createElement("img");
    ima.setAttribute("src", "../img/delete.png");
    ima.setAttribute("height", "50");
    ima.setAttribute("width", "50");
    ima.setAttribute("alt", "Delete");
    ima.setAttribute("onclick", "deleteFromTable()");
    del.appendChild(ima);

    td3.appendChild(del);
    tr.appendChild(td);
    tr.appendChild(td2);
    tr.appendChild(td3);
    tabla.appendChild(tr); 
}

function deleteRow(r) {
    var i = r.parentNode.rowIndex;
    document.getElementById("tablaParticipantes").deleteRow(i);
}

function showMessage(){
    var text = " ->OpositaTest: Iniciando sorteo de la pizarra...\n->OpositaTest: ";
    var area=document.getElementById("out").value = text;
}

function transformToDele(){
    var tick = document.getElementById("tick");
    var pos = document.getElementById("imgtd");

    pos.removeChild(tick);

    var dele = document.createElement("img");
    dele.setAttribute("src", "../img/delete.png");
    dele.setAttribute("height", "50");
    dele.setAttribute("width", "50");
    dele.setAttribute("alt", "Delete");
    dele.setAttribute("id", "dele");
    pos.appendChild(dele);
}

function transformToTick(){
    var pos = document.getElementById("imgtd");
    var elim = document.getElementById("dele");

    pos.removeChild(elim);

    var tick = document.createElement("img");
    tick.setAttribute("src", "../img/tick.png");
    tick.setAttribute("height", "50");
    tick.setAttribute("width", "50");
    tick.setAttribute("alt", "Delete");
    tick.setAttribute("id", "tick");
    pos.appendChild(tick);


}