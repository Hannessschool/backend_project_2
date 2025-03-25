<?php
//model_profiles.php
$sql = "SELECT * FROM profiles";
// ToDo: Kör SQL kod på databasen
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
print("Connected to DB");
$stmt = $conn->query($sql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
print_r($result);