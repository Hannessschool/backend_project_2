<?php
//get_comment.php
require 'db_config.php'; // Databasconnection

if (isset($_GET["ad_id"])) {
    $ad_id = $_GET["ad_id"];
} else {
    
    echo json_encode(["error" => "Ad ID is missing"]);
    exit();
}


$stmt = $pdo->prepare("
    SELECT comments.*, users.username 
    FROM comments 
    JOIN users ON comments.user_id = users.id 
    WHERE comments.ad_id = ? 
    ORDER BY comments.id ASC
");


$stmt->execute([$ad_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);


header('Content-Type: application/json');
echo json_encode($comments);
?>
