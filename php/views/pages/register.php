<?php
$title = "Register";
require_once __DIR__ . '../../partials/head.php';
?>

<?php

// session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password-confirm"])) {
        $username = trim(htmlspecialchars($_POST["username"]));
        $password = trim(htmlspecialchars($_POST["password"]));
        $password_confirm = trim(htmlspecialchars($_POST["password-confirm"]));
        // CHECK IF PW AND PW confirm match
        if ($password !== $password_confirm) {
            $message_pw = "Password and password confirmation do not match!";
        }
        if ($username == "" or $password == "") {
            $message = "Please fill out all fields";
        } else {
            // CHECK if username already exists within DB return error (unique usernames)
            try {
                require_once ("../../queries/connect.php");
                $stmt = $pdo->prepare('SELECT username FROM users WHERE username=:username');
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    $message_user = "Username already in use";
                } else {
                    // saves user to DB
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare('INSERT INTO Users (username, password) VALUES
                    (:username, :password)
                    ');
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->execute();
                    // Login user
                    require_once ("../partials/login.php");
                    $message = login($username, $password);
                }
            } catch (PDOException $e) {
                // Handle database errors
                $message = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

<div class="registerForm">
    <h2>Register</h2>
    <h5>
        <?php if (isset($message)) {
            echo "<div class='error'>$message";
        }
        ?>
    </h5>
    <form method="POST" action="">
        <label for="username">Username*</label>
        <input type="text" name="username" placeholder="Username" required>
        <?php if (isset($message_user)) {
            echo "<div class='error'> $message_user</div>";
        } ?>
        <label for="password">Password*</label>
        <input type="password" name="password" placeholder="Password" required>
        <label for="password-confirm">Password Verification*</label>
        <input type="password" name="password-confirm" placeholder="Password Verification" required>
        <?php if (isset($message_pw)) {
            echo "<div class='error'> $message_pw</div>";
        } ?>
        <button type="submit">New Account</button>
    </form>
    <?php if (!isset($_SESSION["user"]))
        echo '<p>Already have an account? <a href="login.php">Log in</a></p>';
    ?>
</div>

<?php
require_once __DIR__ . '../../partials/end.php';
?>