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

        if (count($_POST) > 0) {
            $filtro_codOspedale = $_POST["filtro_codOspedale"];
            $filtro_codRicovero = $_POST["filtro_codRicovero"];
            $filtro_paziente = $_POST["filtro_paziente"];
            $filtro_dataRicovero = $_POST["filtro_dataRicovero"];
            $filtro_durata = $_POST["filtro_durata"];
            $filtro_motivo = $_POST["filtro_motivo"];
            $filtro_costo = $_POST["filtro_costo"];
        } else {
            $filtro_codOspedale = "";
            $filtro_codRicovero = "";
            $filtro_paziente = "";
            $filtro_dataRicovero = "";
            $filtro_durata = "";
            $filtro_motivo = "";
            $filtro_costo = "";
        }
        ?>
        
        <div id="header">
            <?php include 'header.html'; ?>
        </div>

        <div id="navigation">
            <?php include 'navigation.html'; ?>
        </div>

        <div id="search-filter">     
            <form id="form_ricoveri" action="Ricovero.php" method="post">
                <button type="button" class="reset" onClick="resetFormR()"><span class="reset">Reset &#128260</span></button>
                <input type="text" value="<?php echo $filtro_codOspedale; ?>" placeholder="Codice Ospedale" id="filtro_codOspedale" name="filtro_codOspedale" pattern="OSP\d{2}" class="code_input_ricovero" title="Il codice deve essere nel formato OSP seguito da due cifre.">
                <input type="text" value="<?php echo $filtro_codRicovero; ?>" placeholder="Codice Ricovero" id="filtro_codRicovero" name="filtro_codRicovero" pattern="RIC\d{3}" class="code_input_ricovero" title="Il codice deve essere nel formato OSP seguito da tre cifre.">
                <input type="text" value="<?php echo $filtro_paziente; ?>" placeholder="Paziente" id="filtro_paziente" name="filtro_paziente" pattern="CSSN\d+" class="code_input_ricovero" title="Il codice deve essere nel formato CSSN seguito da almeno una cifra.">
                <input type="date" value="<?php echo $filtro_dataRicovero; ?>" placeholder="Data Ricovero" id="filtro_dataRicovero" name="filtro_dataRicovero" class="code_input_ricovero">
                <input type="text" value="<?php echo $filtro_durata; ?>" placeholder="Durata" id="filtro_durata" name="filtro_durata" class="code_input_ricovero">
                <input type="text" value="<?php echo $filtro_motivo; ?>" placeholder="Motivo" id="filtro_motivo" name="filtro_motivo" class="code_input_ricovero">
                <input type="text" value="<?php echo $filtro_costo; ?>" placeholder="Costo" id="filtro_costo" name="filtro_costo" class="code_input_ricovero">
                <button type="submit"><span>Cerca &#128269</span></button>
            </form>
        </div>
        
        <div id="content">
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
                            <th onclick="sortTable(3)">Data↕</th> 
                            <th onclick="sortTable(4)">Durata↕</th> 
                            <th>Motivo</th> 
                            <th onclick="sortTable(6)">Costo↕</th>
                            <th onclick="sortTable(7)">Patologia↕</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $riga) { ?>
                        <tr> 
                            <td><a href="Ospedale.php?filtro_codice=<?php echo $riga['CodOspedale']; ?>"><?php echo $riga['CodOspedale']; ?></a></td> 
                            <td><?php echo $riga['CodRicovero']; ?></td> 
                            <td><a href="Cittadino.php?filtro_cssn=<?php echo $riga['Paziente']; ?>"><?php echo $riga['Paziente']; ?></a></td> 
                            <td><?php echo $riga['Data']; ?></td> 
                            <td><?php echo $riga['Durata']; ?></td> 
                            <td><?php echo $riga['Motivo']; ?></td> 
                            <td><?php echo $riga['Costo']; ?></td> 
                            <td>
                                <a href="Patologie.php?filtro_codice=<?php echo $riga['CodPatologia']; ?>">
                                    <?php 
                                    $query = "SELECT Nome FROM Patologia WHERE Codice = '" . $riga['CodPatologia'] . "'"; 
                                    $result = $conn->query($query);
                                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                    echo $row['Nome'];
                                    ?>
                                </a>
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
