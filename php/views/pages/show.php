<?php
require_once __DIR__ . '../../partials/head.php';
?>

<main>
<?php
// Include database connection
require_once ("../../queries/connect.php");

// Retrieve Pokemon ID from query parameter
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $pokemonId = $_GET['id'];

    try {
        // Fetch Pokemon details based on ID
        $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE id = :id");
        $stmt->bindParam(':id', $pokemonId, PDO::PARAM_INT);
        $stmt->execute();
        $pokemon = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pokemon) {
            echo "Pokemon not found.";
        } else {
            // Fetch details for previous and next evolutions
            $prevEvolution = $pokemon['evolutionPrev'] ? getPokemonDetails($pokemon['evolutionPrev']) : null;
            $nextEvolution = $pokemon['evolutionNext'] ? getPokemonDetails($pokemon['evolutionNext']) : null;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid Pokemon ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<body>
    <p class="poke-id"><?php echo htmlspecialchars($pokemon["id"]); ?></p>
    <img class="poke-image" src="<?php echo htmlspecialchars($pokemon['imageSprite']); ?>" alt="<?php echo htmlspecialchars($pokemon['name']); ?> sprite">
    <?php if ($pokemon): ?>
        <div class="pokemon-details">
            <h1><?php echo htmlspecialchars($pokemon['name']) ?? 'Pokemon Details'; ?></h1>
            <p><?php echo htmlspecialchars($pokemon['type1']); ?><?php if (!empty($pokemon['type2'])) echo ' ' . htmlspecialchars($pokemon['type2']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($pokemon['description']); ?></p>
            <div class="evolutions">
                <?php if ($prevEvolution): ?>
                    <div class="evolution-card">
                        <a href="show.php?id=<?php echo htmlspecialchars($prevEvolution['id']); ?>">
                            <img src="<?php echo htmlspecialchars($prevEvolution['imageThumbnail']); ?>" alt="<?php echo htmlspecialchars($prevEvolution['name']); ?>">
                            <p><?php echo htmlspecialchars($prevEvolution['name']); ?></p>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="evolution-card">
                    <a href="show.php?id=<?php echo htmlspecialchars($pokemon['id']); ?>">
                        <img src="<?php echo htmlspecialchars($pokemon['imageThumbnail']); ?>" alt="<?php echo htmlspecialchars($pokemon['name']); ?>">
                        <p><?php echo htmlspecialchars($pokemon['name']); ?></p>
                    </a>
                </div>
                <?php if ($nextEvolution): ?>
                    <div class="evolution-card">
                        <a href="show.php?id=<?php echo htmlspecialchars($nextEvolution['id']); ?>">
                            <img src="<?php echo htmlspecialchars($nextEvolution['imageThumbnail']); ?>" alt="<?php echo htmlspecialchars($nextEvolution['name']); ?>">
                            <p><?php echo htmlspecialchars($nextEvolution['name']); ?></p>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <p>No Pokemon found with ID <?php echo htmlspecialchars($pokemonId); ?></p>
    <?php endif; ?>
</body>
</html>

<?php
// Function to get Pokemon details by ID
function getPokemonDetails($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, name, imageThumbnail FROM pokemon WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

</main>

<?php
require_once __DIR__ . '../../partials/end.php';
?>
