<?php
// function that transforms an int into a pokemon ID format
function formatPokeId($number)
{
    // Format the number to be four digits with leading zeros
    $formattedNumber = str_pad($number, 4, '0', STR_PAD_LEFT);

    // Add the hashtag at the end
    $pokeId = $formattedNumber . '#';

    return $pokeId;
}

// Connects to the DB
require_once ("../../queries/connect.php");
require_once ("../partials/pagination.php");

$stmt = $pdo->prepare("SELECT * FROM pokemon;");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$title = "Home";
require_once __DIR__ . '../../partials/head.php';
?>

<main>
    <?php
    paginate($results, $page);
    ?>
</main>

<?php
require_once __DIR__ . '../../partials/end.php';
?>