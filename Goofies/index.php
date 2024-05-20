<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="script.js"></script>
    <title>Goofies Hospital</title>
    <link rel="shortcut icon" href="/img/GOOFY_HOSPITAL__1_.png" />
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <div id="header">
        <?php include 'header.html'; ?>
    </div>

    <div id="navigation">
        <?php include 'navigation.html'; ?>
    </div>
          <div class="text-home">
              	<div class = "container_title"> <h1>Gawrsh! Benvenuto nel Goofy Hospital</h1> </div>
                <p class = "text-home">GoofyCare è un software completo per la gestione ospedaliera 
                progettato su misura per ottimizzare le operazioni quotidiane del 
                divertente ma efficiente ospedale Goofy. Con una suite di strumenti 
                intuitivi e potenti, GoofyCare semplifica la pianificazione delle risorse, 
                la gestione dei pazienti, la compilazione dei report e la comunicazione interna. 
                Grazie alla sua interfaccia user-friendly e alle sue funzionalità personalizzabili, 
                GoofyCare è l'alleato perfetto per garantire che tutto vada alla perfezione nel mondo
                ospedaliero di Goofy.</p><br>
            
              <table class="info-table">

                <tr class = "column">
                    <td class="title"><h2>Cittadini</h2></td>
                    <td class="text">
                        <p class="text-home">
                            Nella sezione cittadini è possibile leggere i dati anagrafici dei cittadini presenti nel database ospedaliero, tra cui nome, cognome, data e luogo di nascita, e indirizzo. 
                            <br>Questa sezione fornisce una panoramica completa delle informazioni personali necessarie per identificare e gestire i 
                            profili dei cittadini negli ospedali.
                        </p>
                    </td>
                </tr>
                <tr class = "column">
                    <td class="title"><h2>Ospedali</h2></td>
                    <td class="text">
                        <p class="text-home">
                            Nella sezione Ospedali è possibile leggere tutte le informazioni riguardo gli ospedali. 
                            In particolar modo vengono riportati nome, indirizzo e città di ogni struttura, accompagnati da nome ed identificativo 
                            del responsabile di tale ospedale. <br>Per ogni record inserito si possono modificare i dati (tranne il codice univoco), 
                            o addirittura eliminare l'intera struttura dal database. <br>Il servizio <i>Goofy Hospital</i> è in grado 
                            anche di far inserire nuove strutture ospedaliere e semplifica la ricerca per campi inserendo nell'apposita 
                            sezione i dati desiderati.
                        </p>
                    </td>
                </tr>
                <tr class = "column">
                    <td class="title"><h2>Ricoveri</h2></td>
                    <td class="text">
                        <p class="text-home">
                            Nella sezione "Ricoveri" è possibile visualizzare i ricoveri effettuati in ogni ospedale per ciascun cittadino, 
                            insieme alle patologie da cui è affetto.
                        </p>
                    </td>
                </tr>
                <tr class = "column">
                    <td class="title"><h2>Patologie</h2></td>
                    <td class="text">
                        <p class="text-home">
                            Nella sezione "Patologie" è possibile visualizzare l'archivio delle patologie conosciute, suddivise in croniche e mortali, 
                            con il riferimento del codice univoco. 
                            <br>Le patologie sia croniche che mortali sono presenti in entrambe le sezioni. 
                            <br>Si precisa che nel database attuale non sono ancora incluse tutte le tipologie di patologie esistenti, 
                            poiché al momento sono presenti solo quelle croniche e mortali.
                        </p>
                    </td>
                </tr>
            </table>
            
          </div>
          <br>
          <br>
          <br>
          <br>
    
	
    <!-- Include Footer -->
    <div id="footer">
        <?php include 'footer.html'; ?>
    </div>
    

</body>
</html>
