<?php
//delete_profile.php
session_start();
include 'db_config.php';

// Kontrollera om användaren är inloggad
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Om formuläret skickas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $password = $_POST['password'];

    // Hämta lösenordet från databasen
    $stmt = $conn->prepare("SELECT passhash FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($passhash);
    $stmt->fetch();
    $stmt->close();

    // Kontrollera om lösenordet är korrekt
    if (password_verify($password, $passhash)) {
        // Ta bort alla annonser som användaren har skapat
        $stmt = $conn->prepare("DELETE FROM profiles WHERE profile_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Ta bort användaren från databasen
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Förstör sessionen och omdirigera till login-sidan
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
        $error_message = "Fel lösenord! Försök igen.";
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radera konto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="container">
        <h2>Radera ditt konto</h2>
        <p> Ange ditt lösenord för att bekräfta.</p>

        <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>

        <form method="post">
            <label for="password">Lösenord:</label>
            <input type="password" name="password" required>
            <button type="submit">Bekräfta radering</button>
        </form>

        <p><a href="profile.php">Tillbaka till profilen</a></p>
    </div>
</body>
</html>

