<?php
session_start();
include('db_config.php');

// Kontrollera om användaren är inloggad
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Hämta kommentar och profil-id från formuläret
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && isset($_POST['profile_id'])) {
    $comment = $_POST['comment'];
    $profile_id = $_POST['profile_id'];
    $user_id = $_SESSION['user_id']; // Antas att användarens ID är lagrat i session-variabeln

    // Spara kommentaren i databasen
    $sql = "INSERT INTO comments (user_id, profile_id, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $profile_id, $comment);
    
    if ($stmt->execute()) {
        // Om kommentaren har sparats, omdirigera tillbaka till view_profiles.php och lägg till comment_status=success i URL:en
        header("Location: index.php?comment_status=success");
        exit;
    } else {
        echo "Kunde inte spara kommentaren: " . $conn->error;
    }
}
?>
