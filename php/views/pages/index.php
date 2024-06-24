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

$stmt = $pdo->prepare("SELECT * FROM pokemon LIMIT 6;");
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
    <?php foreach ($results as $result): ?>
        <?php echo "<div class='poke-card'>" ?>
        <?php echo "<img src='" . $result["imageThumbnail"] . "'" ?>
        <?php echo "<div class='poke-details'>" ?>
        <?php echo "<p class='poke-id'>" . formatPokeId($result["id"]) . "</p>" ?>
        <?php echo "<p class='poke-name'>" . $result["name"] . "</p>" ?>

        <?php echo "<div class='poke-types'>" ?>
        <?php echo "<span class='" . $result["type1"] . "'>" . $result["type1"] . "</span>" ?>
        <?php echo "<span class='" . $result["type2"] . "'>" . $result["type2"] . "</span>" ?>
        <?php echo "</div>" ?>

        <?php echo "</div>" ?>
        <?php echo "</div>" ?>
    <?php endforeach; ?>

</main>

<?php
require_once __DIR__ . '../../partials/end.php';
?>