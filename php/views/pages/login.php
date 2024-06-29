<?php
$title = "Login";
require_once __DIR__ . '../../partials/head.php';
?>

<?php

// Handles the login form submission
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST["password"]) && isset($_POST["username"])) {
        $username = trim(strip_tags($_POST["username"]));
        $password = trim(strip_tags($_POST["password"]));
        require_once ("../partials/login.php");
        $message = login($username, $password);
    }
}
?>
<main>
    <?php if (!isset($_SESSION["user"])): ?>
        <div class="loginPage">
            <h2>My Account</h2>
            <?php if (isset($message)) {
                echo "<div class='error'> $message</div>";
            }
            ?>
            <form method="POST" action="">
                <label for="username">Username*</label>
                <input type="text" name="username" placeholder="Username" required>
                <label for="password">Password*</label>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Connection</button>
            </form>
            <p>Not registered yet? <a href="register.php">Register</a></p>
        <?php else: ?>
            <section class="greet">
                <p>Logged as <?php echo $_SESSION["user"] ?></p>
                <form method="POST" action="../partials/logout.php">
                    <button type="submit">
                        <img title="Log Out" class="logout" src="../../../assets/images/logout.svg" alt="Log Out">
                    </button>
                </form>
            </section>
            <?php
            if (isset($_SESSION['user'])) {
                //retrieve favourite PKM
                require_once ("../../queries/connect.php");
                $user_id = $_SESSION["user_id"];
                try {
                    $stmt = $pdo->prepare("SELECT pokemon.* FROM pokedex 
                    JOIN pokemon ON pokedex.pokemon_id = pokemon.ID 
                    WHERE user_id =:user_id ORDER BY pokemon.ID");
                    $stmt->bindParam(":user_id", $user_id);
                    $stmt->execute();
                    $results = $stmt->fetchAll();
                    require_once ("../partials/pagination.php");
                    echo "<h5 class='section-title'>Your favourite Pokemon</h5>";
                    require ("../partials/search&filter.php");
                    paginate($results, $page);
                } catch (PDOException $e) {
                    $message = "Database error: " . $e->getMessage();
                }
            } ?>
        <?php endif; ?>
    </div>
</main>
<?php
require_once __DIR__ . '../../partials/end.php';
?>