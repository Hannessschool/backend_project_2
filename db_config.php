<?php
//db_config.php
// Databasanslutning
$servername = "cgi.arcada.fi"; // Servername
$username = "eerolaha"; 
$password = "sCAMaM8K95"; 
$dbname = "eerolaha"; 

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


// Skapa anslutning
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrollera anslutningen
if ($conn->connect_error) {
    error_log("Fel vid anslutning: " . $conn->connect_error);
}
?>
