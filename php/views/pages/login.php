<?php
$title = "Login";
require_once __DIR__ . '../../partials/head.php';
?>

<?php


if (!isset($_SESSION['user'])) {
    session_start();
}


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST["password"]) && isset($_POST["username"])) {
        $username = filter_var($_POST["username"]);
        $password = filter_var($_POST["password"]);

        require_once ("../../queries/connect.php");
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
                header("Location: index.php");
                exit();
            } else {
                $message = "Invalid credentials";
            }
        } catch (PDOException $e) {
            $message = $e->getMessage();
        }
    }
}

?>

<h5>
    <?php if (isset($message)) {
        echo "<div class='alert'> $message</div>";
    }
    ?>
</h5>
<div class="">
    <h2>My Account</h2>

    <?php if (!isset($_SESSION["user"])): ?>
        <form method="POST" action="">
            <label for="username">Username*</label><br>
            <input type="text" name="username" placeholder="Username" required><br><br>
            <label for="password">Password*</label><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">Connection</button>
        </form>
        <p>Not registered yet? Click <a href="register.php">here</a></p>
    <?php else: ?>
        Hello <b><?php echo $_SESSION["user"] ?></b>, we will probably display your favourite pokemon here.... eventually
        <form method="POST" action="../partials/logout.php">
            <button type="submit">Logout</button>
        </form>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '../../partials/end.php';
?>