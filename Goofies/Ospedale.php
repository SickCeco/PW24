<!DOCTYPE HTML>
<html>
    <head>
        <title>Ospedale</title>
        <link rel="stylesheet" href="style.css">
        <script type="text/javascript" src="script.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="shortcut icon" href="/img/GOOFY_HOSPITAL__1_.png" />
    </head>
	<?php $operation = $_POST['operation'];?>
    <body onload="selectOperation('<?php echo $operation; ?>'); " id="wholePage">
	<div id=wholePage>
      <?php
      include 'DatabaseManager.php';
      include 'conndb.php';
      
       $query = selectFromOspedale("","","","","");
       try 
       {
         $result = $conn->query($query);
         if (!$result) {
           $error = true;
         }
        }catch (PDOException $e) {
          echo "<label id='label_risultato' class='esito_negativo'>Errore nel database durante la query: " . $e->getMessage() . "</label>";
        }
      ?>

      <div id="header">
          <?php include 'header.html'; ?>
      </div>

      <div id="navigation">
          <?php include 'navigation.html'; ?>
      </div>
	
      <div id="search-filter">
          <!-- Bottoni per selezionare il tipo di operazione -->
          <div id="operation-selection">
              <button onclick="selectOperation('select'); resettaContent()"><span>Ricerca &#128269</span></button>
              <button onclick="selectOperation('insert'); resettaContent()"><span>Inserisci ➕</span></button>
          </div>
		  <br>
          <div id="filters">
              <!-- Filtro per la Select -->
              <div class="filter select" style="display: none;">
                  <form id="form_ospedale" action="Ospedale.php" method="post">
                  <div>
                      <input type="hidden" name="operation" value="select">
                      <input type="text" placeholder="Codice Ospedale" name="filtro_codice" pattern="OSP\d{2}" class="code_input" title="Il codice deve essere nel formato OSP seguito da due cifre.">
                      <input type="text" placeholder="Direttore Sanitario" name="filtro_direttoreSanitario" pattern="CSSN\d+" title="Il codice deve essere nel formato CSSN seguito da almeno un numero.">
                      <input type="text" placeholder="Nome" name="filtro_nome" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo lettere e spazi sono permessi">
    				  <input type="text" placeholder="Città" name="filtro_citta" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo lettere e spazi sono permessi">
                      <input type="text" placeholder="Indirizzo" name="filtro_indirizzo" pattern="^[A-Za-z\s]+,\s\d+$" title="L'indirizzo deve essere nel formato 'Nome della Via, Numero Civico'.">
                      <button type="submit"><span>Esegui</span></button>
                      </div>
                  </form>
              </div>

              <!-- Filtro per l'Insert -->
              <div class="filter insert" style="display: none;">
                  <form id="form_ospedale" action="Ospedale.php" method="post">
                      <input type="hidden" name="operation" value="insert">
                       <input type="text" placeholder="Nome" name="filtro_nome" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo lettere e spazi sono permessi">
    				  <input type="text" placeholder="Città" name="filtro_citta" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo lettere e spazi sono permessi">
                      <input type="text" placeholder="Indirizzo" name="filtro_indirizzo" pattern="^[A-Za-z\s]+,\s\d+$" title="L'indirizzo deve essere nel formato 'Nome della Via, Numero Civico'.">
                      <input type="text" placeholder="Direttore Sanitario" name="filtro_direttoreSanitario" pattern="CSSN\d+" class="code_input" title="Il codice deve essere nel formato CSSN seguito da almeno un numero." required>
                      <button type="submit"><span>Esegui</span></button>
                  </form>
              </div>     
          </div>   
      </div>
	  <div id="wholePage">
        	<div id="content">
      <?php
      $error = false;

      if ($_SERVER['REQUEST_METHOD'] === 'POST') 
      {
          $operation = $_POST['operation'];

          $filtro_codice = $_POST['filtro_codice'] ?? '';
          $filtro_nome = $_POST['filtro_nome'] ?? '';
          $filtro_citta = $_POST['filtro_citta'] ?? '';
          $filtro_indirizzo = $_POST['filtro_indirizzo'] ?? '';
          $filtro_direttoreSanitario = $_POST['filtro_direttoreSanitario'] ?? '';

          $filtro_nome_new = $_POST['filtro_nome_new'] ?? '';
          $filtro_citta_new = $_POST['filtro_citta_new'] ?? '';
          $filtro_indirizzo_new = $_POST['filtro_indirizzo_new'] ?? '';
          $direttoreSanitario_new = $_POST['direttoreSanitario_new'] ?? '';

          switch ($operation) 
          {
              case "select":
                  $query = selectFromOspedale($filtro_codice, $filtro_nome, $filtro_citta, $filtro_indirizzo, $filtro_direttoreSanitario);
                  break;
              case "insert":
                  $query = insertIntoOspedale($filtro_nome, $filtro_citta, $filtro_indirizzo, $filtro_direttoreSanitario);
                  break;
              case "update":
                  $query = updateOspedale($filtro_codice, $filtro_nome_new, $filtro_citta_new, $filtro_indirizzo_new, $direttoreSanitario_new);
                  break;
              case "delete":
                  $query = deleteFromOspedale($filtro_codice);
                  break;
              default:
                  $query = selectFromOspedale();
                  break;
          }

          try {
              $result = $conn->query($query);
              if (!$result) {
                  $error = true;
              }
          } catch (PDOException $e) {
              echo "<label id='label_risultato' class='esito_negativo'>Errore nel database durante la query: " . $e->getMessage() . "</label>";
          }
      }
        
      if (!$error && isset($result)) 
      {
          switch ($operation) 
          {
              case "select":
                  break;
              case "insert":
                  echo "<label id='label_risultato' class='esito_positivo'>Inserimento eseguito con successo</label>";

                  // Eseguo la query per ottenere il valore massimo della colonna 'Codice'
                  $sql = "SELECT MAX(Codice) AS max_codice FROM Ospedale";
                  $result = $conn->query($sql);

                  if ($result) {
                      $row = $result->fetch_assoc();
                      $max_codice = $row['max_codice'];

                      $sql = selectFromOspedale($max_codice, "", "", "", "");
                      $result = $conn->query($sql);
                  }

                  break;

              case "update":
                  echo "<label id='label_risultato' class='esito_positivo'>Modifica eseguita con successo</label>";
                  $sql = selectFromOspedale($filtro_codice, "", "", "", "");
                  $result = $conn->query($sql);
                  break;

              case "delete":
                  echo "<label id='label_risultato' class='esito_positivo'>Eliminazione eseguita con successo</label>";
                  break;

              default:
                  break;
          }

         if ($operation == "delete") 
         {
         	 $query = selectFromOspedale("","","","","");
             try 
             {
               $result = $conn->query($query);
               if (!$result) {
                 $error = true;
               }
              }catch (PDOException $e) {
            echo "<label id='label_risultato' class='esito_negativo'>Errore nel database durante la query: " . $e->getMessage() . "</label>";
        	}
         }
         ?>
        
               <div id="divTable" class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Codice ↕</th>
                            <th onclick="sortTable(1)">Nome ↕</th>
                            <th onclick="sortTable(2)">Città ↕</th>
                            <th onclick="sortTable(3)">Indirizzo ↕</th>
                            <th colspan="2">Direttore Sanitario</th>
                            <th id="actions">Modifica</th>
                            <th id="actions">Elimina</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th onclick="sortTable(4)">Codice ↕</th>
                            <th onclick="sortTable(5)">Nome ↕</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row) { ?>
                        <tr>
                            <td><?php echo $row['Codice']; ?></td>
                            <td><?php echo $row['Nome']; ?></td>
                            <td><?php echo $row['Citta']; ?></td>
                            <td><?php echo $row['Indirizzo']; ?></td>
                            <td><?php echo $row['DirettoreSanitario']; ?></td>
                            <td>
                                <?php
                                $query = "SELECT Nome, Cognome FROM Cittadino WHERE CSSN = ?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("s", $row['DirettoreSanitario']);
                                $stmt->execute();
                                $resultDirettore = $stmt->get_result();
                                $direttore = $resultDirettore->fetch_assoc();
                                echo $direttore['Nome'] . ' ' . $direttore['Cognome'];
                                ?>
                            </td>
                            <td><button onclick="showUpdate('<?php echo $row['Codice']; ?>')" id="actions"><span>&#128397</span></button></td>
                            <td><button onclick="showConfirmation('<?php echo $row['Codice']; ?>')" id="actions"><span>&#128465</span></button></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
          <?php 
      } ?>

      <div id="confirmationModal" class = "pop-up-div" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #EBEBD3; padding: 20px; border-radius: 10px;">
            <p>Sei sicuro di voler eliminare questo elemento?<br>Così facendo eliminerai anche i ricoveri associati a questo ospedale!</p>
            <form id="form_ospedale" action="Ospedale.php" method="post">
                <input type="hidden" name="operation" value="delete">
                <input type="hidden" id ="filtro_codice_delete" name="filtro_codice">
                <br>
                <button type="button" onclick="hideConfirmation()"><span>Annulla</span></button>
                <button type="submit"><span>Conferma</span></button>
            </form>
        </div>
      </div>

      <div id="updateDiv" class = "pop-up-div" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #EBEBD3 ; padding: 20px; border-radius: 10px;">
          <p>Inserisci i valori da modificare</p>
          <form id="form_ospedale" action="Ospedale.php" method="post">
            <input type="hidden" name="operation" value="update">
            <input type="hidden" id ="filtro_codice_update" name="filtro_codice">
            <input type="text" placeholder="Direttore sanitario" name="direttoreSanitario_new" pattern="CSSN\d+" class="code_input" title="Il codice deve essere nel formato CSSN seguito da almeno un numero.">
            <input type="text" placeholder="Nome" id ="filtro_nome_new" name="filtro_nome_new" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo lettere e spazi sono permessi">
            <input type="text" placeholder="Città" name="filtro_citta_new" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo lettere e spazi sono permessi">
            <input type="text" placeholder="Indirizzo" name="filtro_indirizzo_new" pattern="^[A-Za-z\s]+,\s\d+$" title="L'indirizzo deve essere nel formato 'Nome della Via, Numero Civico'.">
            <br><br>
            <button type="button" onclick="hideUpdate()"><span>Annulla</span></button>
            <button type="submit"><span>Esegui</span></button>
          </form>
         </div>
       </div> 
      </div> 

     </div>
	</div>

      <div id="footer">
          <?php include 'footer.html'; ?>
      </div>
	
      <br><br><br><br><br><br>
    </body>
</html>