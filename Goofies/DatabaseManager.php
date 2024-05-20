<?php
	include 'conndb.php';
	
    function selectFromCittadino($filtro_cssn, $filtro_nome, $filtro_cognome, $filtro_dataNascita, $filtro_luogoNascita, $filtro_indirizzo) : string 
    {
        $sql = "SELECT * FROM Cittadino WHERE 1";
        
        if (!empty($filtro_cssn)) {
            $sql .= " AND CSSN LIKE '%$filtro_cssn%'";
        }
        if (!empty($filtro_nome)) {
            $sql .= " AND Nome LIKE '%$filtro_nome%'";
        }
        if (!empty($filtro_cognome)) {
            $sql .= " AND Cognome LIKE '%$filtro_cognome%'";
        }
        if (!empty($filtro_dataNascita)) {
            $sql .= " AND DataNascita = '$filtro_dataNascita'";
        }
        if (!empty($filtro_luogoNascita)) {
            $sql .= " AND LuogoNascita LIKE '%$filtro_luogoNascita%'";
        }
        if (!empty($filtro_indirizzo)) {
            $sql .= " AND indirizzo LIKE '%$filtro_indirizzo%'";
        }
        
        return $sql;
      }
      
     function selectFromRicovero($filtro_codOspedale, $filtro_codRicovero, $filtro_paziente, $filtro_dataRicovero, $filtro_durata, $filtro_motivo, $filtro_costo): string 
     {
        $sql = "SELECT r.*, pr.CodPatologia 
            FROM Ricovero r 
            INNER JOIN PatologiaRicovero pr 
            ON r.CodOspedale = pr.CodOspedale AND r.CodRicovero = pr.CodRicovero
            WHERE 1";

        if (!empty($filtro_codOspedale)) {
            $sql .= " AND r.CodOspedale LIKE '%$filtro_codOspedale%'";
        }
        if (!empty($filtro_codRicovero)) {
            $sql .= " AND r.Cod LIKE '%$filtro_codRicovero%'";
        }
        if (!empty($filtro_paziente)) {
            $sql .= " AND r.Paziente LIKE '%$filtro_paziente%'";
        }
        if (!empty($filtro_dataRicovero)) {
            $sql .= " AND r.Data = '$filtro_dataRicovero'";
        }
        if (!empty($filtro_durata)) {
            $sql .= " AND r.Durata LIKE '%$filtro_durata%'";
        }
        if (!empty($filtro_motivo)) {
            $sql .= " AND r.Motivo LIKE '%$filtro_motivo%'";
        }
        if (!empty($filtro_costo)) {
            $sql .= " AND r.Costo LIKE '%$filtro_costo%'";
        }

        return $sql;    
    }

    function selectFromPatologieCroniche($filtro_codice, $filtro_nome, $filtro_criticita) {
        $sql = "SELECT Codice, Nome, Criticità FROM Patologia WHERE Codice IN (SELECT CodPatologia FROM PatologiaCronica) ";

        if (!empty($filtro_codice)) {
            $sql .= " AND Codice LIKE '%$filtro_codice%'";
        }
        if (!empty($filtro_nome)) {
            $sql .= " AND Nome LIKE '%$filtro_nome%'";
        }
        if (!empty($filtro_criticita)) {
            $sql .= " AND Criticità LIKE '%$filtro_criticita%'";
        }

        return $sql;
    }

    function selectFromPatologieMortali($filtro_codice, $filtro_nome, $filtro_criticita) {
        $sql = "SELECT Codice, Nome, Criticità FROM Patologia WHERE Codice IN (SELECT CodPatologia FROM PatologiaMortale) ";

        if (!empty($filtro_codice)) {
            $sql .= " AND Codice LIKE '%$filtro_codice%'";
        }
        if (!empty($filtro_nome)) {
            $sql .= " AND Nome LIKE '%$filtro_nome%'";
        }
        if (!empty($filtro_criticita)) {
            $sql .= " AND Criticità LIKE '%$filtro_criticita%'";
        }

        return $sql;
    }

    function selectFromOspedale($filtro_codice, $filtro_nome, $filtro_citta, $filtro_indirizzo, $filtro_direttoreSanitario) : string 
    {
        $sql = "SELECT * FROM Ospedale WHERE 1";

        if (!empty($filtro_codice)) {
            $sql .= " AND Codice LIKE '%$filtro_codice%'";
        }
        if (!empty($filtro_nome)) {
            $sql .= " AND Nome LIKE '%$filtro_nome%'";
        }
        if (!empty($filtro_citta)) {
            $sql .= " AND Citta LIKE '%$filtro_citta%'";
        }
        if (!empty($filtro_indirizzo)) {
            $sql .= " AND Indirizzo LIKE '%$filtro_indirizzo%'";
        }
        if (!empty($filtro_direttoreSanitario)) {
            $sql .= " AND DirettoreSanitario = '$filtro_direttoreSanitario'";
        }
        return $sql;
    }
    
    function insertIntoOspedale($nome, $citta, $indirizzo, $direttoreSanitario) : string 
    {
    	global $conn;

     	// Verifica se il direttore sanitario è già direttore di un altro ospedale
        $sql = "SELECT * FROM Ospedale WHERE DirettoreSanitario = '$direttoreSanitario'" ;
        $presente = $conn->query($sql);

        if ($presente->num_rows > 0)  {
          echo "<label id='label_risultato' class='esito_negativo'> Errore: Il direttore sanitario è già direttore di un altro ospedale! </label>";
          return "";
        }

        // Esegue una query per trovare il massimo codice OSP presente nella tabella
        $result = $conn->query("SELECT MAX(Codice) AS max_codice FROM Ospedale");
        $row = $result->fetch_assoc();
        $max_codice = $row['max_codice'];

        $numero = intval(substr($max_codice, 3)) + 1;

        // Formatta il numero in modo che abbia almeno due cifre 
        $numero_formattato = sprintf('%02d', $numero);

        // Crea il nuovo codice concatenando "OSP" con il numero incrementato
        $codice = "OSP" . $numero_formattato;

        $sql_insert = "INSERT INTO Ospedale (Codice, Nome, Citta, Indirizzo, DirettoreSanitario) VALUES ('$codice', '$nome', '$citta', '$indirizzo', '$direttoreSanitario')";

        return $sql_insert;
  	}

    function updateOspedale($codiceOspedale, $nome, $citta, $indirizzo, $codiceDirettoreSanitario) : string 
	{
    	global $conn;

        // Verifica che l'ospedale sia presente nel database 
        $sql = "SELECT * FROM Ospedale WHERE Codice LIKE '%$codiceOspedale%'";
        $presente = $conn->query($sql);

        if ($presente->num_rows == 0) 
        {  
          echo "<label id='label_risultato' class='esito_negativo'> L'ospedale non è presente nel database! </label>";
          return "";
        }

        if (!empty($codiceDirettoreSanitario)) 
        {
        	// Verifica che il direttore sanitario sia presente nel database come cittadino
        	$sql = "SELECT * FROM Cittadino WHERE CSSN LIKE '%$codiceDirettoreSanitario%'";
        	$presente = $conn->query($sql);
            if ($presente->num_rows == 0) 
            {        
              echo "<label id='label_risultato' class='esito_negativo'> Il cittadino che hai selezionato non è presente nel database! </label>";
              return "";
            }

            // Verifica se il direttore sanitario non sia già direttore di un altro ospedale
            $sql = "SELECT * FROM Ospedale WHERE DirettoreSanitario LIKE '%$codiceDirettoreSanitario%'";
            $libero = $conn->query($sql);

            if ($libero->num_rows > 0)
            {
              echo "<label id='label_risultato' class='esito_negativo'> Il direttore che hai selezionato è già direttore di un altro ospedale! </label>";
              return "";
            }
        }
       
      	// Aggiorna il record nella tabella Ospedale
        $sql = "UPDATE Ospedale SET ";

        $setValues = []; // Array per memorizzare i valori da aggiungere al set

        if (!empty($nome)) {
            $setValues[] = "Nome='$nome'";
        }
        if (!empty($citta)) {
            $setValues[] = "Citta='$citta'";
        }
        if (!empty($indirizzo)) {
            $setValues[] = "Indirizzo='$indirizzo'";
        }
        if (!empty($codiceDirettoreSanitario)) {
            $setValues[] = "direttoreSanitario='$codiceDirettoreSanitario'";
        }

        $sql .= implode(', ', $setValues);
        
        $sql .= " WHERE Codice='$codiceOspedale'";
		
		return $sql;    
  	}

    function deleteFromOspedale($codice) 
    {
    	global $conn;
        $result = $conn->query("SELECT Codice FROM Ospedale WHERE Codice = '$codice'");

        // Controllo se esiste almeno un record con il codice specificato
        if ($result && $result->num_rows > 0) 
        {
            $sql = "DELETE FROM Ospedale WHERE Codice='$codice'";
        	$conn->query("DELETE FROM Ricovero WHERE CodOspedale ='$codice'");
            
        } else {
        	 echo "<label id='label_risultato' class='esito_negativo'> Il codice che hai selezionato non appartiene a nessun ospedale! </label>";
            $sql = "";
    	}
        
        
    	return $sql;
	}
    
?>