<?php
//load_ads.php
include 'db_config.php';
include 'view_ads.php'; // Detta inkluderar filtreringsformuläret

$offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
$limit = isset($_POST['limit']) ? intval($_POST['limit']) : 5;
$gender_filter = isset($_POST['gender']) ? intval($_POST['gender']) : 0;  // Standardvärde 0 betyder ingen filtrering
$min_likes = isset($_POST['min_likes']) ? intval($_POST['min_likes']) : 0;

// Grundläggande SQL-fråga
$sql = "SELECT id, realname, bio, salary, preferences, likes FROM profiles WHERE likes >= ?";

$params = [$min_likes];
$types = "i";

// Om könsfiltret är satt och är giltigt (1 eller 2), lägg till det i SQL
if ($gender_filter === 1 || $gender_filter === 2) {
    $sql .= " AND preferences = ?";
    $params[] = $gender_filter;
    $types .= "i";
}

// Lägg till sortering och begränsning
$sql .= " ORDER BY id DESC LIMIT ?, ?";
$params[] = $offset;
$params[] = $limit;
$types .= "ii";

// Förbered SQL och exekvera
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Visa annonserna
while ($row = $result->fetch_assoc()) {
    echo "<div class='ad'>";
    echo "<h3>" . htmlspecialchars($row['realname']) . "</h3>";
    echo "<p>" . htmlspecialchars($row['bio']) . "</p>";
    echo "<p><strong>Gillningar:</strong> " . $row['likes'] . "</p>";
    echo "<p><strong>Lön:</strong> " . ($row['salary'] ? $row['salary'] . " EUR" : "Okänd") . "</p>";

    // Visa könspreferens baserat på nummer
    echo "<p><strong>Könspreferens:</strong> " . 
        ($row['preferences'] == 1 ? "Man" : 
        ($row['preferences'] == 2 ? "Kvinna" : "Okänd")) . 
        "</p>";

    echo "</div>";
}

$stmt->close();
$conn->close();
?>

