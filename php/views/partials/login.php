<?php

function login($username, $password)
{
    require ("../../queries/connect.php");
    // SEARCH DB to check credentials validity
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username=:username;');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['Password'])) {
            // Initiates session and redirects to homepage
            session_start();
            $_SESSION["user"] = $user["Username"];
            $_SESSION["user_id"] = $user["ID"];
            header("Location: login.php");
            exit();
        } else {
            return $message = "Invalid credentials";
        }
    } catch (PDOException $e) {
        return $message = $e->getMessage();
    }
}
?>