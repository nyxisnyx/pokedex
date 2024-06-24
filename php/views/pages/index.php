<?php
$title = "Home";
require_once __DIR__ . '../../partials/head.php';
?>

<main>
    <h1>Pokedex - Homepage</h1>
    <p>Hello <strong><?php echo $user['name'] ?></p></strong>
    <a href="/pokemon?name=pikachu">Pikachu</a>
</main>

<?php
require_once __DIR__ . '../../partials/end.php';
?>