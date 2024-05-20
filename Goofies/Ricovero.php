<!DOCTYPE HTML>
<html>
    <head>
        <title>Ricovero</title>
        <link rel="stylesheet" href="style.css">
        <script type="text/javascript" src="script.js"></script>
        <link rel="shortcut icon" href="/img/GOOFY_HOSPITAL__1_.png" />
    </head>

    <body>
      <?php
        include 'DatabaseManager.php';
        include 'conndb.php';
      ?>
     <div id="header">
            <?php include 'header.html'; ?>
        </div>

     <div id="navigation">
            <?php include 'navigation.html'; ?>
        </div>

    <div id="search-filter">     
        <form id="form_ricoveri" action="Ricovero.php" method="post">
            <input type="text" placeholder="Codice Ospedale" id="filtro_codOspedale" name="filtro_codOspedale" value="" pattern="OSP\d{2}" class="code_input" title="Il codice deve essere nel formato OSP seguito da due cifre.">
            <input type="text" placeholder="Codice Ricovero" id="filtro_codRicovero" name="filtro_codRicovero" value="" pattern="RIC\d{3}" class="code_input" title="Il codice deve essere nel formato OSP seguito da tre cifre.">
            <input type="text" placeholder="Paziente" id="filtro_paziente" name="filtro_paziente" value="" pattern="CSSN\d+" class="code_input" title="Il codice deve essere nel formato CSSN seguito da almeno una cifra.">
            <input type="date" placeholder="Data Ricovero" id="filtro_dataRicovero" name="filtro_dataRicovero" value="">
            <input type="text" placeholder="Durata" id="filtro_durata" name="filtro_durata" value="">
            <input type="text" placeholder="Motivo" id="filtro_motivo" name="filtro_motivo" value="">
            <input type="text" placeholder="Costo" id="filtro_costo" name="filtro_costo" value="">

            <button type="submit"><span>Cerca &#128269</span></button>
        </form>
    </div>
	
    <div id = "content">
    <?php
      // Verifica se il form è stato inviato
      if (count($_POST) > 0) {
          $filtro_codOspedale = $_POST["filtro_codOspedale"];
          $filtro_codRicovero = $_POST["filtro_codRicovero"];
          $filtro_paziente = $_POST["filtro_paziente"];
          $filtro_dataRicovero = $_POST["filtro_dataRicovero"];
          $filtro_durata = $_POST["filtro_durata"];
          $filtro_motivo = $_POST["filtro_motivo"];
          $filtro_costo = $_POST["filtro_costo"];
      } 

      // Costruzione della query
      $query = selectFromRicovero($filtro_codOspedale, $filtro_codRicovero, $filtro_paziente, $filtro_dataRicovero, $filtro_durata, $filtro_motivo, $filtro_costo);

      // Esecuzione della query
      try {   
          $result = $conn->query($query);
          ?>

          <script> resetFormR() </script>

          <?php
      } catch(PDOException $e) {
          echo "<p>Errore nel database durante la query: " . $e->getMessage() . "</p>";
          $error = true;
      }
      ?>
    
        <div class="table-container">
        <table class="table">
            <thead>
                <tr class="header">
                    <th>Codice Ospedale</th> 
                    <th>Codice Ricovero</th> 
                    <th>Paziente</th> 
                    <th onclick="sortTable(3)">Data Ricovero ↕</th> 
                    <th onclick="sortTable(4)">Durata ↕</th> 
                    <th>Motivo</th> 
                    <th onclick="sortTable(6)">Costo ↕</th>
                    <th onclick="sortTable(7)">Patologia ↕</th>  
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $riga) { ?>
                <tr> 
                    <td><?php echo $riga['CodOspedale']; ?></td> 
                    <td><?php echo $riga['CodRicovero']; ?></td> 
                    <td><?php echo $riga['Paziente']; ?></td> 
                    <td><?php echo $riga['Data']; ?></td> 
                    <td><?php echo $riga['Durata']; ?></td> 
                    <td><?php echo $riga['Motivo']; ?></td> 
                    <td><?php echo $riga['Costo']; ?></td> 
                    <td><?php 
                            $query = "SELECT Nome FROM Patologia WHERE Codice = '" . $riga['CodPatologia'] . "'"; 

                            // Esegui la query
                            $result = $conn->query($query);

                            // Ottieni il risultato come array
                            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                            // Stampa il valore di 'Nome'
                            echo $row['Nome'];
                    ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
   	  </div>

      <div id="footer">
        <?php include 'footer.html'; ?>
      </div>

    </body>
</html>