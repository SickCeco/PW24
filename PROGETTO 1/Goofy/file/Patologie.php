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

    if (count($_POST) > 0) {
        $filtro_codice = $_POST["filtro_codice"];
        $filtro_nome = $_POST["filtro_nome"];
        $filtro_criticita = $_POST["filtro_criticita"];
    } else {
        $filtro_codice = "";
        $filtro_nome = "";
        $filtro_criticita = "";
    }
    ?>

    <div id="header">
        <?php include 'header.html'; ?>
    </div>

    <div id="navigation">
        <?php include 'navigation.html'; ?>
    </div>

    <div id="search-filter">
        <div id="filtro">
            <form id="form_patologie" action="Patologie.php" method="post">
                <button type="button" class="reset" onClick="resetFormP()"><span class="reset">Reset &#128260</span></button>
                <input type="text" value="<?php echo $filtro_codice; ?>" placeholder="Codice" id="filtro_codice" name="filtro_codice" pattern="PAT\d{3}" class="code_input" title="Il codice deve essere nel formato OSP seguito da due cifre.">
                <input type="text" value="<?php echo $filtro_nome; ?>" placeholder="Nome" id="filtro_nome" name="filtro_nome">
                <input type="text" value="<?php echo $filtro_criticita; ?>" placeholder="Criticità" id="filtro_criticita" name="filtro_criticita">
                <button type="submit"><span>Cerca &#128269</span></button>
            </form>
        </div>
    </div>

    <?php
    // Verifica se il form è stato inviato
    if (count($_POST) > 0) {
        $filtro_codice = $_POST["filtro_codice"];
        $filtro_nome = $_POST["filtro_nome"];
        $filtro_criticita = $_POST["filtro_criticita"];
    } else if (count($_GET) > 0) {
        $filtro_codice = $_GET["filtro_codice"];
        $filtro_nome = $_GET["filtro_nome"];
        $filtro_criticita = $_GET["filtro_criticita"];
    }
    // Costruzione della query per patologie croniche
    $query_croniche = selectFromPatologieCroniche($filtro_codice, $filtro_nome, $filtro_criticita);
    // Costruzione della query per patologie mortali
    $query_mortali = selectFromPatologieMortali($filtro_codice, $filtro_nome, $filtro_criticita);

    // Esecuzione delle query
    try {
        $result_croniche = $conn->query($query_croniche);
        $result_mortali = $conn->query($query_mortali);
    } catch (PDOException $e) {
        echo "<p>Errore nel database durante la query: " . $e->getMessage() . "</p>";
        $error = true;
    }

    // Unione delle tipologie per le righe con lo stesso codice
    $merged_results = [];

    // Aggiunge risultati dalle patologie croniche
    while ($row = $result_croniche->fetch_assoc()) {
        $codice = $row['Codice'];
        if (!isset($merged_results[$codice])) {
            $merged_results[$codice] = $row;
            $merged_results[$codice]['Tipi'] = [];
        }
        $merged_results[$codice]['Tipi'][] = 'Cronica';
    }

    // Aggiunge risultati dalle patologie mortali
    while ($row = $result_mortali->fetch_assoc()) {
        $codice = $row['Codice'];
        if (!isset($merged_results[$codice])) {
            $merged_results[$codice] = $row;
            $merged_results[$codice]['Tipi'] = [];
        }
        $merged_results[$codice]['Tipi'][] = 'Mortale';
    }

    // Query per ottenere tutti i dettagli dei ricoveri
    $dettagli_ricoveri_query = "SELECT PR.CodPatologia, R.Data, R.CodOspedale, R.CodRicovero, R.Paziente, R.Motivo, R.Costo
                                FROM Ricovero R 
                                JOIN PatologiaRicovero PR ON R.CodRicovero = PR.CodRicovero";
    $dettagli_ricoveri_result = $conn->query($dettagli_ricoveri_query);

    // Crea un array per mappare i dettagli dei ricoveri per ciascuna patologia
    $dettagli_ricoveri = [];
    while ($row = $dettagli_ricoveri_result->fetch_assoc()) {
        $dettagli_ricoveri[$row['CodPatologia']][] = $row;
    }
    ?>

    <div id="content">
        <?php if (!$error && isset($merged_results)) { ?>
            <div class="table-container">
                <table class="table" id="tablePatologie">
                    <thead>
                        <tr class="header">
                            <th onclick="sortTable(0)">Codice ↕</th>
                            <th onclick="sortTable(1)">Nome ↕</th>
                            <th onclick="sortTable(2)">Criticità ↕</th>
                            <th onclick="sortTable(3)">Tipo ↕</th>
                            <th onclick="sortTable(4)">Ricoveri ↕</th>
                            <th>Dettagli</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query per ottenere il conteggio dei ricoveri per ogni patologia
                        $count_query = "SELECT CodPatologia, COUNT(*) as NumeroRicoveri FROM PatologiaRicovero GROUP BY CodPatologia";
                        $count_result = $conn->query($count_query);

                        // Crea un array associativo per mappare il conteggio dei ricoveri per ciascuna patologia
                        $conteggi_ricoveri = [];
                        while ($row = $count_result->fetch_assoc()) {
                            $conteggi_ricoveri[$row['CodPatologia']] = $row['NumeroRicoveri'];
                        }

                        // Unisci le patologie croniche e mortali con il conteggio dei ricoveri
                        $patologie = [];
                        foreach ($merged_results as $patologia) {
                            $riga = $patologia; // Ottieni la riga corrente
                            $riga['ConteggioRicoveri'] = isset($conteggi_ricoveri[$riga['Codice']]) ? $conteggi_ricoveri[$riga['Codice']] : 0;
                            $patologie[] = $riga;
                        }
                        ?>

                        <?php foreach ($patologie as $patologia) { ?>
                            <tr>
                                <td><?php echo $patologia['Codice']; ?></td>
                                <td><?php echo $patologia['Nome']; ?></td>
                                <td><?php echo $patologia['Criticità']; ?></td>
                                <td><?php echo implode(', ', $patologia['Tipi']); ?></td>
                                <td><?php echo $patologia['ConteggioRicoveri']; ?></td>
                                <td><button type="button" onclick="showPopup('<?php echo $patologia['Codice']; ?>', <?php echo htmlspecialchars(json_encode($dettagli_ricoveri)); ?>)">Dettagli</button></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>

    <div id="popupDettagli" class="pop-up-div-2">
        <div class="button-2-1">
            <div class="button-chiusura">
                <button type="button" id="closePopupBtn" onclick="hidePopup()">&times;</button>
            </div>
            <div class="table-container">
                <label id="ricoveri_null"></label>
                <table>
                    <thead id="dettagliRicoveriHead">
                    </thead>
                    <tbody id="dettagliRicoveriBody">
                        <!-- i dettagli dei ricoveri sono caricati qui tramite JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="footer">
        <?php include 'footer.html'; ?>
    </div>
</body>

</html>
