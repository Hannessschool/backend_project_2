<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// register.php
session_start();
include "db_config.php"; // säkerställ att databas connection finns

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($conn)) {
        die("Database connection error.");
    }
    $username = test_input($_POST['username']);
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);
    $real_name = test_input($_POST['realname']);
    $bio = test_input($_POST['bio']);
    $age = test_input($_POST['age']);
    $salary = test_input($_POST['salary']);
    $location = test_input($_POST['zipcode']);
    $looking_for = test_input($_POST['looking_for']);

    if (!empty($username) && !empty($email) && !empty($password)) {
        // Kolla ifall användarnamn och email existerar
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['register_message'] = "Användarnamnet eller e-postadressen är redan registrerad.";
        } else {
            $stmt->close();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO users (username, real_name, email, passhash, bio, age, salary, location, looking_for) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("ssssssiis", $username, $real_name, $email, $hashed_password, $bio, $age, $salary, $location, $looking_for);
            
            if ($stmt->execute()) {
                $_SESSION['register_message'] = "Registreringen lyckades! Du kan nu logga in.";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['register_message'] = "Fel vid registrering: " . $stmt->error;
            }
        }
        $stmt->close();
    } else {
        $_SESSION['register_message'] = "Vänligen fyll i alla fälten.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="eng">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save The Dayte</title>
    <link rel="stylesheet" href="./style.css">
    <script type="text/javascript" src="./script.js" defer></script>
</head>
<body>
    <div id="container">
        <?php include "header.php"; ?>
        <section>
        <h2>Registreringsvyn</h2>
        <p>Här kan du registrera dig</p>
        <form action="register.php" method="post">
            Användarnamn: <input type="text" name="username" required><br>
            Epost: <input type="email" name="email" required><br>
            Lösenord: <input type="password" name="password" autocomplete="off" required><br>
            Namn: <input type="text" name="realname" required><br> 
            Bio: <textarea name="bio" required></textarea><br> 
            Ålder: <input type="number" name="age" required><br> 
            Lön: <input type="number" name="salary" required><br> 
            Plats (postkod): <input type="text" name="zipcode" required><br> 
            Letar efter:<select name="looking_for" required>
            <option value="man">Man</option>
            <option value="kvinna">Kvinna</option>
            <option value="annat">Annat</option>
            <option value="alla">Alla</option>
            <input type="submit" value="Registrera dig"><br>
        </form>
        <?php
        if (isset($_SESSION['register_message'])) {
            echo "<p>" . $_SESSION['register_message'] . "</p>";
            unset($_SESSION['register_message']);
        }
        ?>
        
        Har du redan ett konto? <a href="login.php">Logga in här</a>
        </section>
    </div>
    <footer>
        Made by Hannes och Katinka<sup>&#169;</sup>
    </footer>
</body>
</html>