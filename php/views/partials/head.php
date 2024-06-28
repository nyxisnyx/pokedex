<?php
if (!isset($_SESSION["user_id"])) {
    session_start();
    if (!isset($_SESSION["user_id"])) {
        $darkMode = 0;
    } else {
        require ("../../queries/connect.php");
        try {
            $stmt = $pdo->prepare("SELECT darkMode from users WHERE ID=:user_id");
            $stmt->bindParam(":user_id", $_SESSION["user_id"]);
            $stmt->execute();
            $darkMode = $stmt->fetch();
            $darkMode = $darkMode[0];
        } catch (PDOException $e) {
            echo "ERROR:" . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - Pokedex</title>
    <link rel="stylesheet" href="../../../assets/css/styles.css">
    <script defer src="../../../JS/script.js"></script>
</head>
<?php if ($darkMode == 1) {
    echo "<body class='dark-mode'>";
} else {
    echo "<body>";
}
?>
<header>
    <img src="../../../assets/images/pokemon-logo.png" alt="The traditional Pokemon logo" id="pkmLogo">
    <nav id="navbar">
        <a href="index.php" class='navbarLink <?php if (isset($title) && $title == "Home") {
            echo "active";
        } ?>'>Pokedex</a>
        <a href="login.php" class='navbarLink <?php if (isset($title) && $title == "Login") {
            echo "active";
        } ?>'>My
            account</a>
        <a href="register.php" class='navbarLink <?php if (isset($title) && $title == "Register") {
            echo "active";
        } ?>'>Register</a>
        <button type="submit" id="dark_mode">Toggle DM</button>
    </nav>
</header>