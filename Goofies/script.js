function resettaContent() {
    document.getElementById("content").innerHTML = "";
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
	  console.log(codice);
      var d = codice;
	  var contentTable = document.getElementById("divTable");
      var confirmationModalDiv = document.getElementById("updateDiv");
      contentTable.style.opacity = 10; // Metti la tabella in secondo piano
      confirmationModalDiv.style.display = "block"; // Mostra la seconda div
      confirmationModalDiv.style.zIndex = 100;
      document.getElementById("filtro_codice_update").value = codice;
     
}

function hideUpdate() 
{
      var contentTable = document.getElementById("divTable");
      var confirmationModalDiv = document.getElementById("updateDiv");
      contentTable.style.opacity = 100; // Metti la tabella in primo piano
      confirmationModalDiv.style.display = "none"; // Mostra la seconda div
}

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

function sortTablePatologie(tableId, columnIndex) 
{
    const table = document.getElementById(tableId);
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.rows);
    const type = getColumnType(rows, columnIndex);

    const direction = sortDirection[tableId] || 1;
    sortDirection[tableId] = direction * -1;

    rows.sort((a, b) => {
      let aValue = extractValue(a.cells[columnIndex].innerText);
      let bValue = extractValue(b.cells[columnIndex].innerText);

      if (type === 'number') {
        aValue = parseFloat(aValue);
        bValue = parseFloat(bValue);
      }

      // Confronto personalizzato per ordinare correttamente i numeri come stringhe
      if (aValue === bValue || (!isNaN(aValue) && !isNaN(bValue) && aValue === bValue)) {
        return 0;
      } else if (!isNaN(aValue) && !isNaN(bValue)) {
        return (aValue - bValue) * direction;
      } else {
        return aValue.localeCompare(bValue) * direction;
      }
	});

    while (tbody.firstChild) {
      tbody.removeChild(tbody.firstChild);
    }

    tbody.append(...rows);
}

function extractValue(text) 
{
  const match = text.match(/\d+/);
  return match ? parseInt(match[0]) : text;
}
