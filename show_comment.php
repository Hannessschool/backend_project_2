<?php
//show_comments
include "db_config.php";

$announcement_id = 1; 

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Hämta kommentar
    $sql = "SELECT comments.id, comments.comment, comments.created_at, users.username, comments.conversation_id 
            FROM comments 
            JOIN users ON comments.user_id = users.id 
            WHERE comments.announcement_id = :announcement_id 
            ORDER BY comments.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':announcement_id', $announcement_id);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($comments) {
        $conversation_id = null;
        foreach ($comments as $comment) {
            if ($conversation_id != $comment['conversation_id']) {
                echo "<hr><h3>Chat: " . $comment['conversation_id'] . "</h3>";
                $conversation_id = $comment['conversation_id'];
            }
            echo "<div class='comment'>";
            echo "<p><strong>" . htmlspecialchars($comment['username']) . "</strong> (" . $comment['created_at'] . ")</p>";
            echo "<p>" . htmlspecialchars($comment['comment']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>Inga kommentarer än.</p>";
    }
} catch (PDOException $e) {
    echo "Fel: " . $e->getMessage();
}
?>
