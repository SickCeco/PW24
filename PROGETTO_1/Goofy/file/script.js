function resettaContent() {
    document.getElementById("content").innerHTML = "";
}

function resetFormC()
{
    document.getElementById("filtro_cssn").value = "";
    document.getElementById("filtro_nome").value = "";
    document.getElementById("filtro_cognome").value = "";
    document.getElementById("filtro_dataNascita").value = "";
    document.getElementById("filtro_luogoNascita").value = "";
    document.getElementById("filtro_indirizzo").value = "";
}

function resetFormO()
{
    document.getElementById("filtro_codice").value = "";
    document.getElementById("filtro_nome").value = "";
    document.getElementById("filtro_citta").value = "";
    document.getElementById("filtro_indirizzo").value = "";
    document.getElementById("filtro_direttoreSanitario").value = "";
    document.getElementById("filtro_nome_new").value = "";
    document.getElementById("filtro_citta_new").value = "";
    document.getElementById("filtro_indirizzo_new").value = "";
    document.getElementById("filtro_direttoreSanitario_new").value = "";
}

function resetFormP()
{
    document.getElementById("filtro_codice").value = "";
    document.getElementById("filtro_nome").value = "";
    document.getElementById("filtro_criticita").value = "";
}

function resetFormR()
{
    document.getElementById("filtro_codOspedale").value = "";
    document.getElementById("filtro_codRicovero").value = "";
    document.getElementById("filtro_paziente").value = "";
    document.getElementById("filtro_dataRicovero").value = "";
    document.getElementById("filtro_durata").value = "";
    document.getElementById("filtro_motivo").value = "";
    document.getElementById("filtro_costo").value = "";
}

function validateForm() {
  var input = document.getElementById('filtro_direttoreSanitario_new');
 
  if (!input.value) {
    alert('Per favore, imposta il valore del campo richiesto cliccando il bottone.');
    return false; // Previene l'invio del form
  }
  
  document.getElementById("filtro_direttoreSanitario_new").setAttribute("style", "display:none");
  
  return true; // Consente l'invio del form
}

function validateFormUpdate() {
    var ds = document.getElementById("filtro_direttoreSanitario_new_update").value;
    var nome = document.getElementById("filtro_nome_new_up").value;
    var citta = document.getElementById("filtro_citta_new_up").value;
    var indirizzo = document.getElementById("filtro_indirizzo_new_up").value;

    console.log("Direttore Sanitario:", ds);
    console.log("Nome:", nome);
    console.log("Città:", citta);
    console.log("Indirizzo:", indirizzo);

    if (ds.trim() === "" && nome.trim() === "" && citta.trim() === "" && indirizzo.trim() === "") {
        alert('Attenzione, almeno un campo deve essere inserito!');
        return false; 
    }

    return true;
}

function selectOperation(operation) 
{
    var filtersDiv = document.getElementById("filters");
    
    // Nascondi tutti i filtri
    var allFilters = filtersDiv.getElementsByClassName("filter");
    for (var i = 0; i < allFilters.length; i++) {
        allFilters[i].style.display = "none";
    }

    // Mostra il filtro necessario per l'operazione selezionata
    var selectedFilter = filtersDiv.getElementsByClassName(operation)[0];
    if (selectedFilter) {
        selectedFilter.style.display = "block";
    }

    // Mostra il contenitore dei filtri
    filtersDiv.style.display = "block";

    // Imposta il valore dell'operazione nel campo nascosto
    document.getElementById("form_ospedale").querySelector('input[name="operation"]').value = operation;
    
 }
 
 
//DIV POP-UP

function confirmDelete(codice) 
{
    if (confirm("Sei sicuro di voler eliminare questo elemento?")) 
    {
        deleteFromOspedale(codice);
        document.getElementById("label_risulato").innerText = "eliminazione eseguita con successo";
        selectFromOspedale("","","","","");
    }
}

function showConfirmation(codice) 
{
      var contentTable = document.getElementById("divTable");
      var confirmationModalDiv = document.getElementById("confirmationModal");
      contentTable.style.opacity = 10; // Metti la tabella in secondo piano
      confirmationModalDiv.style.display = "block"; // Mostra la seconda div
      confirmationModalDiv.style.zIndex = 100;
      document.getElementById("filtro_codice_delete").value = codice;
}

function hideConfirmation() 
{
	  var contentTable = document.getElementById("divTable");
      var confirmationModalDiv = document.getElementById("confirmationModal");
      contentTable.style.opacity = 100; // Metti la tabella in primo piano
      confirmationModalDiv.style.display = "none"; // Mostra la seconda div
}

function showUpdate(codice) 
{
	  var contentTable = document.getElementById("divTable");
      var UpdateDiv = document.getElementById("updateDiv");
      document.getElementById("filtro_codice_update").value = codice;
      UpdateDiv.style.opacity = 10; // Metti la tabella in secondo piano
      UpdateDiv.style.display = "block"; // Mostra la seconda div
      UpdateDiv.style.zIndex = 100;
      
}


function hideUpdate() 
{
      var contentTable = document.getElementById("divTable");
      var UpdateDiv = document.getElementById("updateDiv");
      contentTable.style.opacity = 100; // Metti la tabella in primo piano
      UpdateDiv.style.display = "none"; // Mostra la seconda div
}

function showChoiceDirettore(utilizzatore)
{
	  //var contentTable = document.getElementById("divTable");
      var direttoreDiv = document.getElementById("direttoreDiv");
      //contentTable.style.opacity = 10; // Metti la tabella in secondo piano
      direttoreDiv.style.display = "block"; // Mostra la seconda div
      direttoreDiv.style.zIndex = 100;  
      document.getElementById("utilizzatore").value = utilizzatore;    
}


function showAllChoiceDirettore(utilizzatore)
{  
      var direttoreDiv = document.getElementById("All_direttoreDiv");
      direttoreDiv.style.display = "block"; // Mostra la seconda div
      direttoreDiv.style.zIndex = 100;  
      document.getElementById("utilizzatore").value = utilizzatore;    
}
function hideChoiceDirettore(codice)
{	
      var direttoreDiv = document.getElementById("direttoreDiv");
      var utilizzatore = document.getElementById("utilizzatore").value;

      if(utilizzatore === 'insert') 
      {
      	
      	  document.getElementById("filtro_direttoreSanitario_new").setAttribute("style", "display:inline-block");
          var inputElement = document.getElementById("filtro_direttoreSanitario_new");   
          document.getElementById("union_button_input_direttore_insert").style.backgroundColor = '#5C584D';
  
      } else if(utilizzatore === 'update') 
      {
          document.getElementById("filtro_direttoreSanitario_new_update").setAttribute("style", "display:inline-block");
          var inputElement = document.getElementById("filtro_direttoreSanitario_new_update");  
          document.getElementById("union_button_input_direttore_update").style.backgroundColor = '#5C584D';
      }else if(utilizzatore == 'select')
      {
          document.getElementById("filtro_direttoreSanitario").setAttribute("style", "display:inline-block");
          var inputElement = document.getElementById("filtro_direttoreSanitario");
          direttoreDiv = document.getElementById("All_direttoreDiv");
          document.getElementById("union_button_input_direttore_select").style.backgroundColor = '#5C584D';
      }
      
      
		
      direttoreDiv.style.display = "none"; // Mostra la seconda div
      direttoreDiv.style.zIndex = 100;
      inputElement.value = codice;
}

function showPopup(codicePatologia, dettagliRicoveri) 
{
    var popup = document.getElementById("popupDettagli");
    var tableBody = document.getElementById("dettagliRicoveriBody");
    var tableHead =  document.getElementById("dettagliRicoveriHead");
	var content = document.getElementById("ricoveri_null");
    
    console.log('dettagliRicoveri:', dettagliRicoveri);
    console.log('dettagliRicoveri[codicePatologia]:', dettagliRicoveri[codicePatologia]);
    
    tableBody.innerHTML = ''; // Pulizia del corpo della tabella
    tableHead.innerHTML = ''; 
	content.innerText = '';
    
    var ricoveri = dettagliRicoveri[codicePatologia]; // Ottieni l'array dei ricoveri per la patologia selezionata
    if (ricoveri && ricoveri.length > 0) 
    {
    	var head = document.createElement("tr");
        head.innerHTML=
            "<th>Data</th>" + 
            "<th>Codice Ricovero</th>" +
            "<th>Ospedale</th>" +
            "<th>Paziente</th>" +
            "<th>Motivo</th>" +
            "<th>Costo</th>";
         tableHead.appendChild(head);
         
    for (var i = 0; i < ricoveri.length; i++) 
    {
        var ricovero = ricoveri[i];
        
        var row = document.createElement("tr");
        row.innerHTML = 
            "<td>" + ricovero.Data + "</td>" +
            "<td>" + ricovero.CodRicovero + "</td>" +
            "<td>" + ricovero.CodOspedale + "</td>" +
            "<td>" + ricovero.Paziente + "</td>" +
            "<td>" + ricovero.Motivo + "</td>" +
            "<td>" + ricovero.Costo + "</td>";
        tableBody.appendChild(row);
    }
    } else {
      content.innerText = '';
      content.innerText = "Non ci sono ricoveri per questa patologia";
    }

    popup.style.display = 'block'; // Mostra il popup
    popup.style.zIndex = 100;
}


function hidePopup() {
    var popup = document.getElementById('popupDettagli');
    popup.style.display = 'none';
}




//ORDINAMENTO TABELLE 
let sortDirection = {};

function sortTable(columnIndex) 
{
    const table = document.querySelector("table");
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.rows);
    const type = getColumnType(rows, columnIndex);

    const direction = sortDirection[columnIndex] = !sortDirection[columnIndex];

    rows.sort((a, b) => {
      let aValue = extractNumber(a.cells[columnIndex].innerText);
      let bValue = extractNumber(b.cells[columnIndex].innerText);

      if (type === 'number') {
        aValue = parseFloat(aValue);
        bValue = parseFloat(bValue);
      }

      // Confronto personalizzato per ordinare correttamente i numeri come stringhe
      if (aValue === bValue || (!isNaN(aValue) && !isNaN(bValue) && aValue === bValue)) {
        return 0;
      } else if (!isNaN(aValue) && !isNaN(bValue)) {
        return direction ? aValue - bValue : bValue - aValue;
      } else {
        return direction ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
      }
    });

    while (tbody.firstChild) {
      tbody.removeChild(tbody.firstChild);
    }

    tbody.append(...rows);
}

function getColumnType(rows, columnIndex) 
{
    for (let row of rows) {
      const value = row.cells[columnIndex].innerText;
      if (!isNaN(value)) {
        return 'number';
      }
    }
    return 'string';
}

function extractNumber(text) 
{
  const match = text.match(/\d+/);
  return match ? parseInt(match[0]) : text;
}

