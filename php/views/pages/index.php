<?php

// function that transforms an int into a pokemon ID format
function formatPokeId($number)
{
    $formattedNumber = str_pad($number, 4, '0', STR_PAD_LEFT);
    $pokeId = $formattedNumber . '#';
    return $pokeId;
}

// Connects to the DB 
require_once ("../../queries/connect.php");
require_once ("../partials/pagination.php");

$stmt = $pdo->prepare("SELECT * FROM pokemon;");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php
$title = "Home";
require_once __DIR__ . '../../partials/head.php';
?>



<main>
    <!-- <h1>Pokedex - Homepage</h1>
    <p>Hello <strong><?php echo $user['name'] ?></p></strong>
    <a href="/pokemon?name=pikachu">Pikachu</a> -->
    <?php
    paginate($results, $page);
    ?>
</main>

<?php
require_once __DIR__ . '../../partials/end.php';
?>