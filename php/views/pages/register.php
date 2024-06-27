<?php
$title = "Register";
require_once __DIR__ . '../../partials/head.php';
?>

<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password-confirm"])) {
        $username = trim($_POST["username"]);
        $password = $_POST["password"];
        $password_confirm = $_POST["password-confirm"];
        // CHECK IF PW AND PW confirm match
        if ($password !== $password_confirm) {
            $message = "Password and password confirmation do not match!";
        }
        if ($username == "" or $password == "") {
            $message = "";
        }
        // CHECK if username already exists within DB return error (unique usernames)
        else {
            require_once ("../../queries/connect.php");
            try {
                $stmt = $pdo->prepare('SELECT username FROM users WHERE username=:username');
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    $message = "Username already in use";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare('INSERT INTO Users (username, password) VALUES
                    (:username, :password)
                    ');
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->execute();
                    session_start();
                    $_SESSION["user"] = $username;
                    header("location: index.php");
                    exit();
                }
            } catch (PDOException $e) {
                // Handle database errors
                $message = "Database error: " . $e->getMessage();
            }
        }
    } else {
        $message = "Please fill out all fields";
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
    <h2>Register</h2>
    <form method="POST" action="">
        <label for="username">Username*</label><br>
        <input type="text" name="username" placeholder="Username" required><br><br>
        <label for="password">Password*</label><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <label for="password-confirm">Password Verification*</label><br>
        <input type="password" name="password-confirm" placeholder="Password Verification" required><br><br>
        <button type="submit">New Account</button>
    </form>
    <p>Already have an account? Click <a href="login.php">here</a></p>
</div>

<?php
require_once __DIR__ . '../../partials/end.php';
?>



<?php

$user_id = 2;
$id = 5;

require_once ("../../queries/connect.php");
try {
    $stmt = $pdo->prepare("SELECT * FROM pokedex WHERE user_id =:user_id AND pokemon_id=:pokemon_id;");
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->bindParam(":pokemon_id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "";
}
if (empty($result)) {
    try {
        $stmt = $pdo->prepare("INSERT INTO pokedex (user_id, pokemon_id) VALUES (:user_id, :pokemon_id)");
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":pokemon_id", $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "";
    }


} else {
    try {
        // IS already in the favourites
        $stmt = $pdo->prepare("DELETE FROM pokedex WHERE user_id =:user_id AND pokemon_id=:pokemon_id");
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":pokemon_id", $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "";
    }
}


?>