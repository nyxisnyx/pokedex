<?php

header('Content-Type: application/json');
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['id'])) {
        $id = $data['id'];
        $user_id = $_SESSION["user_id"];
        require ("../../queries/connect.php");
        // Checks if pokemon was already in users' favourites
        try {
            $stmt = $pdo->prepare("SELECT * FROM pokedex WHERE user_id =:user_id AND pokemon_id=:pokemon_id;");
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":pokemon_id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
        } catch (Exception $e) {
            echo json_encode(['status' => 'success', 'message' => 'Query failed at level fetch entry']);
            return;
        }
        if (empty($result)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO pokedex (user_id, pokemon_id) VALUES (:user_id, :pokemon_id)");
                $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                $stmt->bindParam(":pokemon_id", $id, PDO::PARAM_INT);
                $stmt->execute();
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert to DB']);
            }
        } else {
            // Was already in favourites, delete the relation
            try {
                // IS already in the favourites
                $stmt = $pdo->prepare("DELETE FROM pokedex WHERE user_id =:user_id AND pokemon_id=:pokemon_id");
                $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                $stmt->bindParam(":pokemon_id", $id, PDO::PARAM_INT);
                $stmt->execute();
                echo json_encode(['status' => 'success', 'message' => 'Entry deleted from DB']);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete from DB']);

            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID not set in request']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}


?>