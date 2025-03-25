<!DOCTYPE html>
<!-- profile.php -->
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save The Dayte</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div id="container">
        <?php include "header.php"; ?>
        <section>
            <h2>Min profil</h2>

            <?php
            include "db_config.php";
            session_start();

            if (!isset($_SESSION['user_id'])) {
                echo "<p>Var vänlig och logga in för att se din profil.</p>";
                exit();
            }

            $user_id = $_SESSION['user_id'];

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Hämta användarens data
                $sql = "SELECT username, real_name, passhash, email, zipcode, bio, salary, preferences 
                        FROM users WHERE user_id = :user_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    echo "<p>Ingen profil hittad.</p>";
                } else {
            ?>

            <!-- Redigeringsformulär -->
            <form action="update_profile.php" method="post">
                <label for="username">Användarnamn:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

                <label for="realname">Riktigt namn:</label>
                <input type="text" id="real_name" name="real_name" value="<?= htmlspecialchars($user['realname']) ?>" required>

                <label for="password">Nytt lösenord (valfritt):</label>
                <input type="password" id="password" name="password">

                <label for="email">E-post:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                <label for="zipcode">Postkod:</label>
                <input type="text" id="zipcode" name="zipcode" value="<?= htmlspecialchars($user['zipcode']) ?>" required>

                <label for="bio">Berätta om dig själv:</label>
                <textarea id="bio" name="bio" required><?= htmlspecialchars($user['bio']) ?></textarea>

                <label for="salary">Årslön:</label>
                <input type="number" id="salary" name="salary" value="<?= htmlspecialchars($user['salary']) ?>" required>

                <label for="preferences">Jag söker:</label>
                <select id="preferences" name="preferences">
                    <option value="1" <?= $user['preferences'] == '1' ? 'selected' : '' ?>>Män</option>
                    <option value="2" <?= $user['preferences'] == '2' ? 'selected' : '' ?>>Kvinnor</option>
                    <option value="3" <?= $user['preferences'] == '3' ? 'selected' : '' ?>>Båda</option>
                    <option value="4" <?= $user['preferences'] == '4' ? 'selected' : '' ?>>Annat</option>
                    <option value="5" <?= $user['preferences'] == '5' ? 'selected' : '' ?>>Alla</option>
                </select>
                <div><button type="submit">Uppdatera profil</button></div>
            </form>

            <!-- Radera konto-knapp -->
            <form action="delete_profile.php" method="post">
                <button type="submit">Radera konto</button>
            </form>

            <?php
                }
            } catch (PDOException $e) {
                echo "Fel: " . $e->getMessage();
            }
            ?>

            <!-- Kommentarer -->
            <h2>Dina kommentarer</h2>

            <?php
            try {
                // Hämta kommentarer som användaren har fått
                $sqlComments = "SELECT c.comment, u.real_name, c.created_at 
                                FROM comments c 
                                JOIN users u ON c.sender_id = u.user_id 
                                WHERE c.receiver_identifier_id = :user_id";
                $stmtComments = $conn->prepare($sqlComments);
                $stmtComments->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmtComments->execute();
                $comments = $stmtComments->fetchAll(PDO::FETCH_ASSOC);

                if ($comments) {
                    foreach ($comments as $comment) {
                        echo '<p><strong>' . htmlspecialchars($comment['realname']) . ' (' . $comment['created_at'] . '):</strong> ' . htmlspecialchars($comment['comment']) . '</p>';
                        
                        // Kommentera möjlighet
                        echo '<form action="reply_comment.php" method="post">
                                <textarea name="reply" required placeholder="Skriv ditt svar här..."></textarea>
                                <input type="hidden" name="comment_id" value="' . $comment['user_id'] . '">
                                <button type="submit">Svara</button>
                              </form>';
                    }
                } else {
                    echo '<p>Inga kommentarer ännu.</p>';
                }
            } catch (PDOException $e) {
                echo "Fel: " . $e->getMessage();
            }
            ?>
        </section>
        <footer>
            Made by Hannes och Katinka
        </footer>
    </div>
</body>
</html>


