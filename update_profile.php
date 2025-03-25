<?php
// update_profile.php // Starta sessionen

include 'db_config.php';

// Kontrollera om användaren är inloggad
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Om inte, omdirigera till inloggningssidan
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $username = $_POST['username'];
    $realname = $_POST['realname'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $zipcode = $_POST['zipcode'];
    $bio = $_POST['bio'];
    $salary = $_POST['salary'];
    $preferences = $_POST['preferences'];

    try {
        // Anslut till databasen
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Om lösenord ges, uppdatera det
        if (!empty($password)) {
            $hashedpassword = password_hash($password, PASSWORD_DEFAULT); // Hasha lösenordet
            $sql = "UPDATE users SET username=?, realname=?, passhash=?, email=?, zipcode=?, bio=?, salary=?, preferences=? WHERE user_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$username, $realname, $hashedpassword, $email, $zipcode, $bio, $salary, $preferences, $user_id]);
        } else {
            // Uppdatera utan lösenord
            $sql = "UPDATE users SET username=?, realname=?, email=?, zipcode=?, bio=?, salary=?, preferences=? WHERE user_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$username, $realname, $email, $zipcode, $bio, $salary, $preferences, $user_id]);
        }

        // Efter en lyckad uppdatering, omdirigera tillbaka till profilsidan
        header("Location: profile.php?update=success");
        exit();

    } catch (PDOException $e) {
        echo "Fel: " . $e->getMessage(); // Visa felmeddelande om det går fel med databasanslutningen
    }

    $conn = null; // Stäng anslutningen
}
?>

