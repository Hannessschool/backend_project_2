<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "handy_methods.php";
include "db_config.php";

$error_message = ""; // Definiera en variabel för felmeddelande

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);

    if (!isset($conn)) {
        die("Database connection error.");
    }

    // Förbered SQL statement
    $stmt = $conn->prepare("SELECT user_id, username, passhash, real_name, email, bio, age, salary, location, looking_for FROM users WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) { 
        $stmt->bind_result($user_id, $db_username, $passhash, $real_name, $email, $bio, $age, $salary, $location, $looking_for);
        $stmt->fetch(); // Fånga endast ena gången


        if (password_verify($password, $passhash)) { 
            // Sätt sessionsvariabler
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['real_name'] = $real_name;
            $_SESSION['email'] = $email;
            $_SESSION['bio'] = $bio;
            $_SESSION['age'] = $age;
            $_SESSION['salary'] = $salary;
            $_SESSION['location'] = $location;
            $_SESSION['looking_for'] = $looking_for;

            // Omdirigera till profilsidan
            header("Location: profile.php");
            exit(); // säkerställ att man inte lämnar kvar på sidan
        } else {
            $error_message = "Fel användarnamn eller lösenord.";
        }
    } else {
        $error_message = "Fel användarnamn eller lösenord.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save The Dayte - Logga in</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div id="container">
        <?php include "header.php"; ?>
        <section>
            <h1>Logga in</h1>
            <form method="post" action="login.php">
                Användarnamn: <input type="text" name="username" required><br>
                Lösenord: <input type="password" name="password" required><br>
                <input type="submit" value="Logga in">
            </form>
            <?php if (!empty($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
        </section>
    </div>
</body>
<footer>
    Made by Hannes och Katinka<sup>&#169;</sup>
</footer>
</html>