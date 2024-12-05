function resettaContent() {
  document.getElementById("content").innerHTML = ""; // Resetta il contenuto dell'elemento con id "content"
}

function resetFormC() {
  // Resetta i valori dei campi di input del form C
  document.getElementById("filtro_cssn").value = "";
  document.getElementById("filtro_nome").value = "";
  document.getElementById("filtro_cognome").value = "";
  document.getElementById("filtro_data_nascita").value = "";
  document.getElementById("filtro_luogo_nascita").value = "";
  document.getElementById("filtro_indirizzo").value = "";
}

function resetFormO() {
  // Resetta i valori dei campi di input del form O
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

function resetFormP() {
  // Resetta i valori dei campi di input del form P
  document.getElementById("filtro_codice").value = "";
  document.getElementById("filtro_nome").value = "";
  document.getElementById("filtro_criticita").value = "";
}

function resetFormR() {
  // Resetta i valori dei campi di input del form R
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
  console.log(input);
  if (!input.value) {
      // Mostra un avviso se il campo è vuoto
      alert('Per favore, imposta il valore del campo richiesto cliccando il bottone.');
      return false; // Previene l'invio del form
  }
  
  // Nasconde il campo input
  document.getElementById("filtro_direttoreSanitario_new").setAttribute("style", "display:none");
  
  return true; // Consente l'invio del form
}

function validateFormUpdate() {
  // Validazione dei campi di input per l'aggiornamento
  var ds = document.getElementById("filtro_direttoreSanitario_new_update").value;
  var nome = document.getElementById("filtro_nome_new_up").value;
  var citta = document.getElementById("filtro_citta_new_up").value;
  var indirizzo = document.getElementById("filtro_indirizzo_new_up").value;

  console.log("Direttore Sanitario:", ds);
  console.log("Nome:", nome);
  console.log("Città:", citta);
  console.log("Indirizzo:", indirizzo);

  // Controlla che almeno un campo sia riempito
  if (ds.trim() === "" && nome.trim() === "" && citta.trim() === "" && indirizzo.trim() === "") {
      alert('Attenzione, almeno un campo deve essere inserito!');
      return false; 
  }

  return true; // Consente l'invio del form
}

function selectOperation(operation) {
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

// Funzioni per la gestione dei popup di conferma e aggiornamento
function confirmDelete(codice) {
  if (confirm("Sei sicuro di voler eliminare questo elemento?")) {
      deleteFromOspedale(codice); // Funzione di eliminazione
      document.getElementById("label_risulato").innerText = "eliminazione eseguita con successo";
      selectFromOspedale("","","","",""); // Aggiorna la lista
  }
}

function showConfirmation(codice) {
  // Mostra il popup di conferma eliminazione
  var contentTable = document.getElementById("divTable");
  var confirmationModalDiv = document.getElementById("confirmationModal");
  contentTable.style.opacity = 10; // Riduce l'opacità della tabella di sfondo
  confirmationModalDiv.style.display = "block"; // Mostra il popup
  confirmationModalDiv.style.zIndex = 100;
  document.getElementById("filtro_codice_delete").value = codice;
}

function hideConfirmation() {
  // Nasconde il popup di conferma eliminazione
  var contentTable = document.getElementById("divTable");
  var confirmationModalDiv = document.getElementById("confirmationModal");
  contentTable.style.opacity = 100; // Ripristina l'opacità della tabella
  confirmationModalDiv.style.display = "none"; // Nasconde il popup
}

function showUpdate(codice) {
  // Mostra il popup per l'aggiornamento
  var contentTable = document.getElementById("divTable");
  var UpdateDiv = document.getElementById("updateDiv");
  document.getElementById("filtro_codice_update").value = codice;
  UpdateDiv.style.opacity = 10; // Riduce l'opacità della tabella di sfondo
  UpdateDiv.style.display = "block"; // Mostra il popup
  UpdateDiv.style.zIndex = 100;
}

function hideUpdate() {
  // Nasconde il popup per l'aggiornamento
  var contentTable = document.getElementById("divTable");
  var UpdateDiv = document.getElementById("updateDiv");
  contentTable.style.opacity = 100; // Ripristina l'opacità della tabella
  UpdateDiv.style.display = "none"; // Nasconde il popup
}

function showChoiceDirettore(utilizzatore) {
  // Mostra il popup per la scelta del direttore sanitario
  var direttoreDiv = document.getElementById("direttoreDiv");
  direttoreDiv.style.display = "block"; // Mostra il popup
  direttoreDiv.style.zIndex = 100;  
  document.getElementById("utilizzatore").value = utilizzatore;    
}

function showAllChoiceDirettore(utilizzatore) {
  // Mostra il popup per la scelta di tutti i direttori sanitari
  var direttoreDiv = document.getElementById("All_direttoreDiv");
  direttoreDiv.style.display = "block"; // Mostra il popup
  direttoreDiv.style.zIndex = 100;  
  document.getElementById("utilizzatore").value = utilizzatore;    
}

function hideChoiceDirettore(codice) {
  // Nasconde il popup per la scelta del direttore sanitario e aggiorna il campo di input corrispondente
  var direttoreDiv = document.getElementById("direttoreDiv");
  var utilizzatore = document.getElementById("utilizzatore").value;

  var inputElement;
  if(utilizzatore === 'insert') {
      document.getElementById("filtro_direttoreSanitario_new").setAttribute("style", "display:inline-block");
      inputElement = document.getElementById("filtro_direttoreSanitario_new");   
      document.getElementById("union_button_input_direttore_insert").style.backgroundColor = '#5C584D';
  } else if(utilizzatore === 'update') {
      document.getElementById("filtro_direttoreSanitario_new_update").setAttribute("style", "display:inline-block");
      inputElement = document.getElementById("filtro_direttoreSanitario_new_update");  
      document.getElementById("union_button_input_direttore_update").style.backgroundColor = '#5C584D';
  } else if(utilizzatore == 'select') {
      document.getElementById("filtro_direttoreSanitario").setAttribute("style", "display:inline-block");
      inputElement = document.getElementById("filtro_direttoreSanitario");
      direttoreDiv = document.getElementById("All_direttoreDiv");
      document.getElementById("union_button_input_direttore_select").style.backgroundColor = '#5C584D';
  }
    
  direttoreDiv.style.display = "none"; // Nasconde il popup
  direttoreDiv.style.zIndex = 100;
  inputElement.value = codice;
}

function showPopup(button, dettagliRicoveriString) {
  // Mostra il popup con i dettagli dei ricoveri
  var dettagliRicoveri = JSON.parse(dettagliRicoveriString);

  var popup = document.getElementById("popupDettagli");
  var tableBody = document.getElementById("dettagliRicoveriBody");
  var tableHead = document.getElementById("dettagliRicoveriHead");
  var content = document.getElementById("ricoveri_null");

  tableBody.innerHTML = ''; // Pulizia del corpo della tabella
  tableHead.innerHTML = ''; 
  content.innerText = '';

  if (dettagliRicoveri.length > 0) {
      // Crea l'intestazione della tabella
      var head = document.createElement("tr");
      head.innerHTML =
          "<th>Data</th>" + 
          "<th>Codice Ricovero</th>" +
          "<th>Ospedale</th>" +
          "<th>Paziente</th>" +
          "<th>Motivo</th>" +
          "<th>Costo</th>";
      tableHead.appendChild(head);

      // Aggiunge le righe con i dettagli dei ricoveri
      for (var i = 0; i < dettagliRicoveri.length; i++) {
          var ricovero = dettagliRicoveri[i];

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
      // Mostra un messaggio se non ci sono ricoveri
      content.innerText = "Non ci sono ricoveri per questa patologia";
  }

  popup.style.display = 'block'; // Mostra il popup
  popup.style.zIndex = 100;
}

function hidePopup() {
  // Nasconde il popup dei dettagli dei ricoveri
  var popup = document.getElementById('popupDettagli');
  popup.style.display = 'none';
}

// Funzioni per l'ordinamento delle tabelle
let sortDirection = {};

function sortTable(columnIndex) {
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

      // Confronto personalizzato per ordinare correttamente i numeri e le stringhe
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

function getColumnType(rows, columnIndex) {
  for (let row of rows) {
      const value = row.cells[columnIndex].innerText;
      if (!isNaN(value)) {
          return 'number'; // Riconosce la colonna come numerica
      }
  }
  return 'string'; // Riconosce la colonna come stringa
}

function extractNumber(text) {
  const match = text.match(/\d+/);
  return match ? parseInt(match[0]) : text; // Estrae il numero da una stringa
}
