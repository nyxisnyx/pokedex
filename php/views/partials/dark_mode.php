<?php

header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'success', 'message' => 'Guest User']);
    } else {
        require_once ("../../queries/connect.php");
        try {
            $stmt = $pdo->prepare("SELECT darkMode from users WHERE ID=:user_id");
            $stmt->bindParam(":user_id", $_SESSION["user_id"]);
            $stmt->execute();
            $darkMode = $stmt->fetch();
            if ($darkMode == 1) {
                $darkMode = 0;
            } else {
                $darkMode = 1;
            }
            $stmt = $pdo-> prepare("UPDATE darkMode FROM users SET  WHERE user_id =:user_id")

            echo json_encode(['status' => 'success', 'message' => 'Preference registered']);
        } catch (PDOException $e) {
            echo "ERROR:" . $e->getMessage();
        }
    }
}

?>