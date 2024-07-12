<!DOCTYPE HTML>
<html>
    <head>
        <title>Ospedale</title>
        <link rel="stylesheet" href="style.css">
        <script type="text/javascript" src="script.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="shortcut icon" href="/img/GOOFY_HOSPITAL__1_.png" />
    </head>
    <body id="wholePage">
	<div>
	<?php
		include 'DatabaseManager.php';
      	include 'conndb.php';
      
	if (count($_POST) > 0) {
		  $filtro_codice = $_POST['filtro_codice'];
          $filtro_nome = $_POST['filtro_nome'];
          $filtro_citta = $_POST['filtro_citta'];
          $filtro_indirizzo = $_POST['filtro_indirizzo'];
          $filtro_direttoreSanitario = $_POST['filtro_direttoreSanitario'];

          $filtro_nome_new = $_POST['filtro_nome_new'] ;
          $filtro_citta_new = $_POST['filtro_citta_new'] ;
          $filtro_indirizzo_new = $_POST['filtro_indirizzo_new'] ;
		  $direttoreSanitario_new = $_POST['direttoreSanitario_new'];
	}
	else
	{
		$filtro_codice = "";
		$filtro_nome = "";
		$filtro_citta = "";
		$filtro_indirizzo = "";
		$filtro_direttoreSanitario = "";

		$filtro_nome_new = "";
		$filtro_citta_new = "";
		$filtro_indirizzo_new = "";
		$direttoreSanitario_new = "";
	}

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
              <button onclick="selectOperation('select');"><span>Cerca &#128269</span></button>
              <button onclick="selectOperation('insert'); resettaContent()"><span>Inserisci ➕</span></button>
          </div>
          
		  <br>
          
          <div id="filters">
          
              <!-- Filtro per la Select -->
              <div class="filter select" style="display: none;">
                  <form id="form_ospedale" class="form-inline" action="Ospedale.php" method="post">
                      <button type="button" class="reset" onclick="resetFormO()"><span class="reset">Reset &#128260</span></button>
                      <input type="hidden" name="operation" value="select">
                      <input type="text" id="filtro_codice" value ="<?php echo $filtro_codice ?>" placeholder="Codice Ospedale" name="filtro_codice" pattern="OSP\d{2}" class="code_input" title="Il codice deve essere nel formato OSP seguito da due cifre.">
                      <input type="text" id="filtro_nome" value ="<?php echo $filtro_nome ?>" placeholder="Nome" name="filtro_nome" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo lettere e spazi sono permessi">
                      <input type="text" id="filtro_citta" value ="<?php echo $filtro_citta ?>" placeholder="Città" name="filtro_citta" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo lettere e spazi sono permessi">
                      <input type="text" id="filtro_indirizzo" value ="<?php echo $filtro_indirizzo ?>" placeholder="Indirizzo" name="filtro_indirizzo" pattern="^[A-Za-z\s]+,\s\d+$" title="L'indirizzo deve essere nel formato 'Nome della Via, Numero Civico'.">
                      <div id = "union_button_input_direttore_select" class="union_button_input_direttore">
                          <button type="button" onclick="showAllChoiceDirettore('select')"><span id="span_cerca_direttore">Direttore</span></button>
                          <input type="text" style="display:none" id="filtro_direttoreSanitario" value ="<?php echo $filtro_direttoreSanitario ?>" placeholder="Direttore Sanitario" name="filtro_direttoreSanitario" readonly>
                      </div>
                      <button type="submit"><span>Esegui</span></button>
                  </form>
              </div>



             <!-- Filtro per l'Insert -->
              <div class="filter insert" style="display: none;">
                  <form id="form_ospedale" class = "form-inline" action="Ospedale.php" method="post" onsubmit="return validateForm()">
                      <button type="button" class="reset" onclick = "resetFormO()"><span class="reset">Reset &#128260</span></button>
                      <input type="hidden" name="operation" value="insert">
                      <input type="text" id="filtro_nome_new" value ="<?php echo $filtro_nome_new ?>" placeholder="Nome" name="filtro_nome" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo lettere e spazi sono permessi" required>
                      <input type="text" id="filtro_citta_new" value ="<?php echo $filtro_citta_new ?>" placeholder="Città" name="filtro_citta" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo lettere e spazi sono permessi" required>
                      <input type="text" id="filtro_indirizzo_new" value ="<?php echo $filtro_indirizzo_new ?>" placeholder="Indirizzo" name="filtro_indirizzo" pattern="^[A-Za-z\s]+,\s\d+$" title="L'indirizzo deve essere nel formato 'Nome della Via, Numero Civico'." required>
                      <div id = "union_button_input_direttore_insert" class="union_button_input_direttore">
                      	<button type="button" class ="bottone_cerca_direttore" onclick= "showChoiceDirettore('insert')"><span id = "span_cerca_direttore">Direttore</span></button>
                        <input style="display:none" type="text" id="filtro_direttoreSanitario_new" value ="<?php echo $filtro_direttoreSanitario_new ?>" placeholder="Scegli il direttore" name="filtro_direttoreSanitario" required readonly>
 	 	  			  </div>
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

          $filtro_codice = $_POST['filtro_codice'];
          $filtro_nome = $_POST['filtro_nome'];
          $filtro_citta = $_POST['filtro_citta'];
          $filtro_indirizzo = $_POST['filtro_indirizzo'];
          $filtro_direttoreSanitario = $_POST['filtro_direttoreSanitario'];

          $filtro_codice_update = $_POST['filtro_codice'];
          $filtro_nome_new = $_POST['filtro_nome_new'];
          $filtro_citta_new = $_POST['filtro_citta_new'];
          $filtro_indirizzo_new = $_POST['filtro_indirizzo_new'];
          $direttoreSanitario_new = $_POST['filtro_direttoreSanitario_new'];

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
      else if (count($_GET) > 0) 
      {
        $filtro_codice = $_GET['filtro_codice'];
        $query = selectFromOspedale($_GET['filtro_codice'],"","","","");
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
				  ?> <script> resetFormO(); </script> <?php
                  break;

              case "update":
                  echo "<label id='label_risultato' class='esito_positivo'> Modifica eseguita con successo</label>";
                  $sql = selectFromOspedale($filtro_codice, "", "", "", "");
                  $result = $conn->query($sql);
                  ?> <script> resetFormO(); </script> <?php
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
                            <th>Modifica</th>
                            <th>Elimina</th>
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
                            <td><a href="Cittadino.php?filtro_cssn=<?php echo $row['DirettoreSanitario']; ?>"><?php echo $row['DirettoreSanitario']; ?></a></td>
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

      <div id="confirmationModal" class = "pop-up-div">
        <div class="button-2">
         <div class="button-chiusura">
                <!-- Bottone di chiusura -->
                <button type="button" id="closePopupBtn" onclick="hideConfirmation()">&times;</button>
            </div>
            <p>Sei sicuro di voler eliminare questo elemento?<br>Così facendo eliminerai anche i ricoveri associati a questo ospedale!</p>
            <form id="form_ospedale" action="Ospedale.php" method="post">
                <input type="hidden" name="operation" value="delete">
                <input type="hidden" id ="filtro_codice_delete" name="filtro_codice">
                <br>
                <button type="submit"><span>Conferma</span></button>
            </form>
        </div>
      </div>

      <div id="updateDiv" class = "pop-up-div">
        <div class="button-2">
          <div class="button-chiusura">
              <button type="button" id="closePopupBtn" onclick="hideUpdate()">&times;</button>
          </div>
          <p>Inserisci i valori da modificare</p>
          <form id="form_ospedale" class = "form-inline" action="Ospedale.php" method="post" onsubmit="return validateFormUpdate()">
              <input type="hidden" name="operation" value="update">
              <input type="hidden" id="filtro_codice_update" name="filtro_codice">
              <input type="text" placeholder="Nome" id="filtro_nome_new_up" name="filtro_nome_new" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo lettere e spazi sono permessi">
              <input type="text" placeholder="Città" id="filtro_citta_new_up" name="filtro_citta_new" pattern="[A-Za-zÀ-ÿ\s]+" title="Solo lettere e spazi sono permessi">
              <input type="text" placeholder="Indirizzo" id="filtro_indirizzo_new_up" name="filtro_indirizzo_new" pattern="^[A-Za-z\s]+,\s\d+$" title="L'indirizzo deve essere nel formato 'Nome della Via, Numero Civico'.">
              <div id = "union_button_input_direttore_update" class="union_button_input_direttore">
                  <button type="button" class = "bottone_cerca_direttore" onclick="showChoiceDirettore('update')"><span>Direttore</span></button>
                  <input type="text" style="display:none" id="filtro_direttoreSanitario_new_update" value="<?php echo $filtro_direttoreSanitario_new ?>" placeholder="Scegli il direttore" name="filtro_direttoreSanitario_new" readonly>    
              </div>
              <br><br> <br><br>
              <button type="submit"><span>Esegui</span></button>
      		</form>
         </div>
       </div> 
      </div>
        
       
        <div id="direttoreDiv" class="pop-up-div">
          <div class="button-2">
           <!-- Wrapper per il bottone di chiusura -->
            <div class="button-chiusura">
                <!-- Bottone di chiusura -->
                <button type="button" id="closePopupBtn" onclick="hideChoiceDirettore('')">&times;</button>
            </div>
            <input type ="hidden" id = "utilizzatore">
           <div class="table-container"> 
           <h4>Questa è la lista dei direttori Sanitari non ancora asseganti</h4>
            <table>
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Codice</th>
                  <th>Seleziona</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Esegui la query per ottenere i direttori sanitari liberi
                $sql = selectDirettoreLibero();
                $result = $conn->query($sql);

                // Controlla se ci sono risultati
                if ($result->num_rows > 0) {
                  // Output dei dati di ogni riga
                  while($row = $result->fetch_assoc()) {
                    ?><tr>
                            <td><?php echo $row['Nome']; ?>
                            <?php echo $row['Cognome']; ?></td>
                            <td><?php echo $row['CSSN']; ?></td>
                            <td><button type="button" class="selectDirettoreBtn" onclick="hideChoiceDirettore('<?php echo $row['CSSN']; ?>')" id="actions">Seleziona</button></td>
                          </tr>

					<?php
                  }
                } else {
                  echo "<tr><td colspan='3'>Nessun direttore sanitario trovato</td></tr>";
                }
                ?>
              </tbody>
            </table>     
          </div>
        </div>
      </div> 
      </div> 
	</div>
    
     <div id="All_direttoreDiv" class="pop-up-div">
          <div class="button-2">
           <!-- Wrapper per il bottone di chiusura -->
            <div class="button-chiusura">
                <!-- Bottone di chiusura -->
                <button type="button" id="closePopupBtn" onclick="hideChoiceDirettore('')">&times;</button>
            </div>
            <input type ="hidden" id = "utilizzatore">
           <div class="table-container"> 
           <h4>Questa è la lista di tutti i direttori Sanitari </h4>
            <table>
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Codice</th>
                  <th>Seleziona</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Esegui la query per ottenere i direttori sanitari liberi
                $sql = selectDirettore();
                $result = $conn->query($sql);

                // Controlla se ci sono risultati
                if ($result->num_rows > 0) {
                  // Output dei dati di ogni riga
                  while($row = $result->fetch_assoc()) {
                    ?><tr>
                            <td><?php echo $row['Nome']; ?>
                            <?php echo $row['Cognome']; ?></td>
                            <td><?php echo $row['CSSN']; ?></td>
                            <td><button type="button" class="selectDirettoreBtn" onclick="hideChoiceDirettore('<?php echo $row['CSSN']; ?>')" id="actions">Seleziona</button></td>
                          </tr>

					<?php
                  }
                } else {
                  echo "<tr><td colspan='3'>Nessun direttore sanitario trovato</td></tr>";
                }
                ?>
              </tbody>
            </table>     
          </div>
        </div>
      </div> 
  

      <div id="footer">
          <?php include 'footer.html'; ?>
      </div>
	
      <br><br><br><br><br><br>
    </body>
</html>