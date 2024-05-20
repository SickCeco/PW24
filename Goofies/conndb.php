<?php
  $servername = "localhost";  // L'indirizzo del server di database (di solito localhost)
  $username = "fraatmz6";  // Il nome utente del database
  $password = "Progetto2024";  // La password del database
  $dbname = "my_fraatmz6";  // Il nome del database

  // Connessione al database
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  // Verifica della connessione
  if (!$conn) {
      die("Connessione al database fallita: " . mysqli_connect_error());
  }

?>
