<!DOCTYPE HTML>
<html>
    <head>
        <title>Patologie</title>
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
     
     <div id = "search-filter">
        <div id="filtro">      
            <form id="form_patologie" action="Patologie.php" method="post">
                <input type="text" placeholder="Codice" id="filtro_codice" name="filtro_codice" value="" pattern="OSP\d{2}" class="code_input" title="Il codice deve essere nel formato OSP seguito da due cifre.">
                <input type="text" placeholder="Nome" id="filtro_nome" name="filtro_nome" value="">
                <input type="text" placeholder="Criticità" id="filtro_criticita" name="filtro_criticita" value="">
                <button type="submit"><span>Cerca &#128269</span></botton>
            </form>
        </div>
    </div>

      <?php
      // Verifica se il form è stato inviato
      if (count($_POST) > 0) 
      {
          $filtro_codice = $_POST["filtro_codice"];
          $filtro_nome = $_POST["filtro_nome"];
          $filtro_criticita = $_POST["filtro_criticita"];
      } 

      // Costruzione della query per patologie croniche
      $query_croniche = selectFromPatologieCroniche($filtro_codice, $filtro_nome, $filtro_criticita);
      // Costruzione della query per patologie mortali
      $query_mortali = selectFromPatologieMortali($filtro_codice, $filtro_nome, $filtro_criticita);

      // Esecuzione delle query
      try {   
          $result_croniche = $conn->query($query_croniche);
          $result_mortali = $conn->query($query_mortali);
      } catch(PDOException $e) {
          echo "<p>Errore nel database durante la query: " . $e->getMessage() . "</p>";
          $error = true;
      }
      ?>

	<div id = "content">
        <?php         
        if (!$error && isset($result_croniche) && isset($result_mortali)) 
        {
        ?>
            
       <div class="table-container">
        <h2>Patologie Croniche</h2>
        <table class="table" id="tableCroniche">
            <thead>
                <tr class="header">
                    <th onclick="sortTablePatologie('tableCroniche', 0)">Codice ↕</th> 
                    <th onclick="sortTablePatologie('tableCroniche', 1)">Nome ↕</th> 
                    <th onclick="sortTablePatologie('tableCroniche', 2)">Criticità ↕</th> 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result_croniche as $riga) { ?>
                <tr> 
                    <td><?php echo $riga['Codice']; ?></td> 
                    <td><?php echo $riga['Nome']; ?></td> 
                    <td><?php echo $riga['Criticità']; ?></td> 
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <h2>Patologie Mortali</h2>
    <div class="table-container">
        <table class="table" id="tableMortali">
            <thead>
                <tr class="header">
                    <th onclick="sortTablePatologie('tableMortali', 0)">Codice ↕</th> 
                    <th onclick="sortTablePatologie('tableMortali', 1)">Nome ↕</th> 
                    <th onclick="sortTablePatologie('tableMortali', 2)">Criticità ↕</th> 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result_mortali as $riga) { ?>
                <tr> 
                    <td><?php echo $riga['Codice']; ?></td> 
                    <td><?php echo $riga['Nome']; ?></td> 
                    <td><?php echo $riga['Criticità']; ?></td> 
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

            <?php
        }
        ?>
    </div>
	  <br><br><br><br><br><br>

  	  <div id="footer">
          <?php include 'footer.html'; ?>
      </div>
	</body>
</html>