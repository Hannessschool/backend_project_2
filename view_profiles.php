<?php
echo '<h1>Välkommen till Save The Daytes Annonstorg!</h1>';
echo '<h2>Annonser</h2>';
echo '<p>Följande annonser finns på dejtingsajten</p>';

include('db_config.php');
session_start();

// Kolla ifall vi behöver visa meddelande om lyckad kommentar
if (isset($_GET['comment_status'], $_GET['profile_id']) && 
    $_GET['comment_status'] == 'success' && 
    $_GET['profile_id']) {
    $successMessage = "Kommentar skickades framgångsrikt!";
} else {
    $successMessage = '';
}

// Hämtar profilinformation från databasen
$sql = "SELECT profile_id, realname, bio, salary, email, likes, gender FROM profiles WHERE profile_id BETWEEN 2 and 7";
$result = $conn->query($sql);

// Om frågan misslyckas, avsluta skriptet
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Om vi får resultat, visa profilerna
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $profileId = $row['profile_id'];

        // Hämtar antal gilla för den här profilen
        $sqlLikes = "SELECT COUNT(*) as like_count FROM likes WHERE liked_profile_id = ?";
        $stmtLikes = $conn->prepare($sqlLikes);
        $stmtLikes->bind_param("i", $profileId);
        $stmtLikes->execute();
        $resultLikes = $stmtLikes->get_result();
        $likesCount = $resultLikes->fetch_assoc()['like_count'];

        // Visar profilens information
        echo "<h3>" . $row["realname"] . "</h3>";
        echo "<p>" . $row["bio"] . "</p>";

        // Mappa koder för kön till text
        $genderMap = [
            1 => 'Man',
            2 => 'Kvinna',
            3 => 'Annan'
        ];
        $genderText = isset($genderMap[$row['gender']]) ? $genderMap[$row['gender']] : 'Unknown';
        echo "<p>" . htmlspecialchars($genderText) . "</p>";

        // Om användaren är inloggad, visa mer information
        if (isset($_SESSION['username'])) {
            echo "<p><strong>Email:</strong> " . $row["email"] . "</p>";
            echo "<p><strong>Årslön:</strong> " . $row["salary"] . " €</p>";

            // Visa like count och gilla-knappen
            echo '<div class="likesCountSection">';
            echo '<p><strong>Antalet likes:</strong> <span id="likeCount_' . $profileId . '">' . $likesCount . '</span></p>';
            echo '</div>';

            echo '<div class="likeSection">';
            echo '<button class="likeButton" data-liked-profile-id="' . $profileId . '">Like</button>';
            echo '</div>';

            // Kommentera sektion
            echo '<h4>Lämna en kommentar:</h4>';
            echo '<form class="commentForm" method="post" action="submit_comment.php">';
            echo '<textarea name="comment" required></textarea><br>';
            echo '<input type="hidden" name="profile_id" value="' . $profileId . '">';
            echo '<input type="submit" value="Skicka kommentar">';
            echo '</form>';

            // Visa bekräftelsemeddelande om kommentar skickades
            if ($successMessage && $_GET['profile_id'] == $profileId) {
                echo '<p class="successMessage" id="successMessage' . $profileId . '">' . $successMessage . '</p>';
            }
        } else {
            echo "<p><em>Logga in för att gilla, skicka ett meddelande samt för att lämna en kommentar.</em></p>";
        }

        echo "<hr>";
    }
} else {
    echo "Inga annonser.";
}

echo '<h2>Bläddra bland annonser</h2>';
?>

