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

<?php
paginate($results, $page);
?>

<!-- <main> -->
<!-- <h1>Pokedex - Homepage</h1>
    <p>Hello <strong><?php echo $user['name'] ?></p></strong>
    <a href="/pokemon?name=pikachu">Pikachu</a> -->
<!-- <?php foreach ($results as $result): ?> -->
    <!-- <?php echo "<img src='../../../assets/images/pokemon/" . $result["imageBig"] . "' alt='image not found'>"; ?> -->
    <!-- <?php echo "<div class='poke-card'>" ?>
        <?php echo "<img src='" . $result["imageThumbnail"] . "'" ?>
        <?php echo "<div class='poke-details'>" ?>
        <?php echo "<p class='poke-id'>" . formatPokeId($result["ID"]) . "</p>" ?>
        <?php echo "<p class='poke-name'>" . $result["name"] . "</p>" ?>
        <?php echo "<div class='poke-types'>" ?>
        <?php echo "<span class='" . $result["type1"] . "'>" . $result["type1"] . "</span>" ?>
        <?php echo "<span class='" . $result["type2"] . "'>" . $result["type2"] . "</span>" ?>
        <?php echo "</div>" ?>

        <?php echo "</div>" ?>
        <?php echo "</div>" ?>
    <?php endforeach; ?>

</main> -->

<?php
require_once __DIR__ . '../../partials/end.php';
?>