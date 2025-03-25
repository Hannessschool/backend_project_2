<?php
include 'db_config.php';

// fånga filterparametrar
$gender_filter = isset($_GET['gender']) ? $_GET['gender'] : 'both';
$min_likes = isset($_GET['min_likes']) ? (int)$_GET['min_likes'] : 0;

// Gör SQL query
$sql = "SELECT p.profile_id, p.realname, p.bio, p.gender, 
               (SELECT COUNT(*) FROM likes WHERE liked_profile_id = p.profile_id) as like_count
        FROM profiles p
        WHERE 1";  // 

// Könsfilter
if ($gender_filter == 'male') {
    $sql .= " AND p.gender = 1";
} elseif ($gender_filter == 'female') {
    $sql .= " AND p.gender = 2";
}

// Filtrering med like count
$sql .= " HAVING like_count >= ?";

// Framställ och förverkliga en query
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $min_likes);
$stmt->execute();
$result = $stmt->get_result();

// resultat av queryn
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<h3>" . htmlspecialchars($row['realname']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['bio']) . "</p>";
        echo "<p><strong>Antalet likes:</strong> " . $row['like_count'] . "</p>";
        echo "<hr>";
    }
} else {
    echo "<p>Inga annonser matchar dina filter.</p>";  
}

// stäng statement och SQL kopplingen
$stmt->close();
$conn->close();
?>

