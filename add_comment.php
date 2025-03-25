<?php
//add_comment.php
include "db_config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<p>Var vänlig och logga in för att lämna en kommentar.</p>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && isset($_POST['announcement_id'])) {
    $user_id = $_SESSION['user_id'];
    $announcement_id = $_POST['announcement_id'];
    $comment = $_POST['comment'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO comments (user_id, announcement_id, comment) VALUES (:user_id, :announcement_id, :comment)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':announcement_id', $announcement_id);
        $stmt->bindParam(':comment', $comment);

        if ($stmt->execute()) {
            echo "<p>Kommentar tillagd!</p>";
        } else {
            echo "<p>Fel vid tillägg av kommentar.</p>";
        }
    } catch (PDOException $e) {
        echo "Fel: " . $e->getMessage();
    }
}
?>
<form action="add_comment.php" method="post">
    <textarea name="comment" required placeholder="Skriv din kommentar här..."></textarea>
    <input type="hidden" name="announcement_id" value="1"> 
    <button type="submit">Lägg till kommentar</button>
</form>

