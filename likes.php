<?php
session_start();
include "db_config.php";  // Ensure DB connection


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kolla ifall POST parametrar är tillgängliga
    if (isset($_POST['liked_profile_id']) && isset($_POST['receiver_identifier_id']) && isset($_SESSION['username'])) {
        $liked_profile_id = $_POST['liked_profile_id'];
        $receiver_identifier_id = $_POST['receiver_identifier_id'];
        $user = $_SESSION['username'];  // Säkerställ att användare är inloggade

        // Kolla ifall profilen ifråga existerar
        $sqlCheck = "SELECT profile_id FROM profiles WHERE profile_id = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("i", $liked_profile_id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            // Lägg in liken i databasen
            $sqlInsert = "INSERT INTO likes (user, liked_profile_id, receiver_identifier_id, timestamp) 
                          VALUES (?, ?, ?, NOW())";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("sii", $user, $liked_profile_id, $receiver_identifier_id);

            if ($stmtInsert->execute()) {
                // Få uppdaterad vy av likes vid profilen
                $sqlLikes = "SELECT COUNT(*) AS like_count FROM likes WHERE liked_profile_id = ?";
                $stmtLikes = $conn->prepare($sqlLikes);
                $stmtLikes->bind_param("i", $liked_profile_id);
                $stmtLikes->execute();
                $resultLikes = $stmtLikes->get_result();
                $newLikeCount = $resultLikes->fetch_assoc()['like_count'];

                // Returnera uppdaterade likes counten i JSON format
                echo json_encode([
                    'success' => true,
                    'new_like_count' => $newLikeCount
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to insert like. Please try again.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'The profile you are trying to like does not exist.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Required parameters are missing or user is not logged in.'
        ]);
    }
}
?>

