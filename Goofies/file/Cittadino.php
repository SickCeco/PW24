<!DOCTYPE HTML>
<html>
  <head>
      <title>Cittadino</title>
      <link rel="shortcut icon" href="/img/GOOFY_HOSPITAL__1_.png" />
      <link rel="stylesheet" href="style.css">
      <script type="text/javascript" src="script.js"></script>
  </head>

  <body>
	<?php
	if (count($_POST) > 0) {
		$filtro_cssn = $_POST["filtro_cssn"];
		$filtro_nome = $_POST["filtro_nome"];
		$filtro_cognome = $_POST["filtro_cognome"];
		$filtro_dataNascita = $_POST["filtro_dataNascita"];
		$filtro_luogoNascita = $_POST["filtro_luogoNascita"];
		$filtro_indirizzo = $_POST["filtro_indirizzo"];
	  }
	  else{
		$filtro_cssn = '';
		$filtro_nome = '';
		$filtro_cognome ='';
		$filtro_dataNascita = '';
		$filtro_luogoNascita = '';
		$filtro_indirizzo = '';
	  }
	?>
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
      <form id="form_cittadini" action="Cittadino.php" method="post">
      	<button type= "button" class = "reset" onClick="resetFormC()"><span class = "reset">Reset &#128260</span></button>
        <input type="text" value="<?php echo $filtro_cssn; ?>" placeholder="CSSN" id="filtro_cssn" name="filtro_cssn" pattern="CSSN\d+" class="code_input" title="Il codice deve essere nel formato CSSN seguito da almeno un numero.">
        <input type="text" value="<?php echo $filtro_nome; ?>" placeholder="Nome" id="filtro_nome" name="filtro_nome" >
        <input type="text" value="<?php echo $filtro_cognome; ?>" placeholder="Cognome" id="filtro_cognome" name="filtro_cognome">
        <input type="date" value="<?php echo $filtro_dataNascita; ?>" placeholder="Data di Nascita" id="filtro_dataNascita" name="filtro_dataNascita">
        <input type="text" value="<?php echo $filtro_luogoNascita; ?>" placeholder="Luogo di Nascita" id="filtro_luogoNascita" name="filtro_luogoNascita">
        <input type="text" value="<?php echo $filtro_indirizzo; ?>" placeholder="Indirizzo" id="filtro_indirizzo" name="filtro_indirizzo" pattern="^[A-Za-z\s]+,\s\d+$" title="L'indirizzo deve essere nel formato 'Nome della Via, Numero Civico'.">

        <button type="submit"><span>Cerca &#128269</span></button>
		
      </form>
    </div>

    <?php
      
      if (count($_POST) > 0) {
        $filtro_cssn = $_POST["filtro_cssn"];
        $filtro_nome = $_POST["filtro_nome"];
        $filtro_cognome = $_POST["filtro_cognome"];
        $filtro_dataNascita = $_POST["filtro_dataNascita"];
        $filtro_luogoNascita = $_POST["filtro_luogoNascita"];
        $filtro_indirizzo = $_POST["filtro_indirizzo"];
      }
      
      else if(count($_GET) > 0)
      {
        $filtro_cssn = $_GET["filtro_cssn"];
        $filtro_nome = $_GET["filtro_nome"];
        $filtro_cognome = $_GET["filtro_cognome"];
        $filtro_dataNascita = $_GET["filtro_dataNascita"];
        $filtro_luogoNascita = $_GET["filtro_luogoNascita"];
        $filtro_indirizzo = $_GET["filtro_indirizzo"];
      }
      
      $query = selectFromCittadino($filtro_cssn, $filtro_nome, $filtro_cognome, $filtro_dataNascita, $filtro_luogoNascita, $filtro_indirizzo);
     
      try {
        $result = $conn->query($query);
      } catch (PDOException $e) {
        echo "<p>Errore nel database durante la query: " . $e->getMessage() . "</p>";
        $error = true;
      }
    ?>
	<div id = "content">
      <div class="table-container">
          <table>
              <thead>
                  <tr>
                      <th onclick="sortTable(0)">CSSN ↕</th>
                      <th onclick="sortTable(1)">Nome ↕</th>
                      <th onclick="sortTable(2)">Cognome ↕</th>
                      <th onclick="sortTable(3)">Data di Nascita ↕</th>
                      <th onclick="sortTable(4)">Luogo di Nascita ↕</th>
                      <th onclick="sortTable(5)">Indirizzo ↕</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($result as $riga) { ?>
                  <tr>
                      <td><?php echo $riga['CSSN']; ?></td>
                      <td><?php echo $riga['Nome']; ?></td>
                      <td><?php echo $riga['Cognome']; ?></td>
                      <td><?php echo $riga['DataNascita']; ?></td>
                      <td><?php echo $riga['LuogoNascita']; ?></td>
                      <td><?php echo $riga['Indirizzo']; ?></td>
                  </tr>
                  <?php } ?>
              </tbody>
          </table>
      </div>
          </div>
     <br><br><br>
    <div id="footer">
      <?php include 'footer.html'; ?>
    </div>

  </body>
</html>