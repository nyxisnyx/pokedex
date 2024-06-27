<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - Pokedex</title>
    <link rel="stylesheet" href="../../../assets/css/styles.css">
    <script defer src="../../../JS/script.js"></script>
</head>

<body>
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
        </nav>
    </header>