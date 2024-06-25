<?php
$title = "Login";
require_once __DIR__ . '../../partials/head.php';
?>

<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST["password"]) && isset($_POST["username"])) {
        // SEARCH DB to check credentials validity
        $username = $_POST["username"];
        $password = $_POST["password"];
        require_once ("../../queries/connect.php");
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username=:username;');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['Password'])) {
                // Initiates session and redirects to homepage
                session_start();
                $_SESSION["username"] = $user["username"];
                header("Location: index.php");
                exit();
            } else {
                echo "<h6>Invalid credentials</h6>";
            }
        } catch (PDOException $e) {
            echo '' . $e->getMessage();
        }
    }
}

?>


<h5>
    <?php if (isset($message)) {
        echo $message;
    }
    ?>
</h5>
<div class="">
    <h2>My Account</h2>
    <form method="POST" action="">
        <label for="username">Username*</label><br>
        <input type="text" name="username" placeholder="Username"><br><br>
        <label for="password">Password*</label><br>
        <input type="password" name="password" placeholder="Password"><br><br>
        <button type="submit">Connection</button>
    </form>
    <p>Not registered yet? Click <a href="register.php">here</a></p>
</div>

<?php
require_once __DIR__ . '../../partials/end.php';
?>