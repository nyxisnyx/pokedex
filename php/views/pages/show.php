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
        $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE ID = :id");
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
<head>
    <style>
        .stat-bar {
            display: flex;
            align-items: center;
            margin: 5px 0;
            width: 80%;
        }

        .stat-bar span {
            width: 100px;
            text-align: left;
        }

        .bar {
            width: 100%;
            background-color: #eee;
            border-radius: 5px;
            margin-left: 10px;
            position: relative;
        }

        .bar-fill {
            height: 20px;
            background-color: #4caf50;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php if ($pokemon): ?>
        <div class="pokemon-details">
            <h1><?php echo htmlspecialchars($pokemon['name']) ?? 'Pokemon Details'; ?></h1>
            <img class="poke-image" src="../../../assets/images/pokemon/<?php echo htmlspecialchars($pokemon['imageBig']); ?>" alt="<?php echo htmlspecialchars($pokemon['name']); ?> sprite">
            <p><?php echo htmlspecialchars($pokemon["ID"]); ?></p>
            <p><?php echo htmlspecialchars($pokemon['type1']); ?><?php if (!empty($pokemon['type2'])) echo '   ' . htmlspecialchars($pokemon['type2']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($pokemon['description']); ?></p>

            <div class="stats">
                <div class="stat-bar">
                    <span>HP</span>
                    <div class="bar">
                        <div class="bar-fill" style="width: <?php echo htmlspecialchars($pokemon['HP']); ?>%;"></div>
                    </div>
                </div>
                <div class="stat-bar">
                    <span>Attack</span>
                    <div class="bar">
                        <div class="bar-fill" style="width: <?php echo htmlspecialchars($pokemon['Attack']); ?>%;"></div>
                    </div>
                </div>
                <div class="stat-bar">
                    <span>Defense</span>
                    <div class="bar">
                        <div class="bar-fill" style="width: <?php echo htmlspecialchars($pokemon['Defense']); ?>%;"></div>
                    </div>
                </div>
                <div class="stat-bar">
                    <span>Specific Attack</span>
                    <div class="bar">
                        <div class="bar-fill" style="width: <?php echo htmlspecialchars($pokemon['Sp. Attack']); ?>%;"></div>
                    </div>
                </div>
                <div class="stat-bar">
                    <span>Specific Defense</span>
                    <div class="bar">
                        <div class="bar-fill" style="width: <?php echo htmlspecialchars($pokemon['Sp. Defense']); ?>%;"></div>
                    </div>
                </div>
                <div class="stat-bar">
                    <span>Speed</span>
                    <div class="bar">
                        <div class="bar-fill" style="width: <?php echo htmlspecialchars($pokemon['Speed']); ?>%;"></div>
                    </div>
                </div>
            </div>

            <div class="loading-bar">
                <?php if ($prevEvolution): ?>
                    <div class="evolution-card">
                        <h2>Previous Evolution</h2>
                        <a href="show.php?id=<?php echo htmlspecialchars($prevEvolution['ID']); ?>">
                            <img src="<?php echo htmlspecialchars($prevEvolution['imageThumbnail']); ?>" alt="<?php echo htmlspecialchars($prevEvolution['name']); ?>">
                            <p><?php echo htmlspecialchars($prevEvolution['name']); ?></p>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="evolution-card"></div>
                <?php endif; ?>
                
                <div class="evolution-card">
                    <h2>Current Pokemon</h2>
                    <img src="<?php echo htmlspecialchars($pokemon['imageThumbnail']); ?>" alt="<?php echo htmlspecialchars($pokemon['name']); ?>">
                    <p><?php echo htmlspecialchars($pokemon['name']); ?></p>
                </div>
                
                <?php if ($nextEvolution): ?>
                    <div class="evolution-card">
                        <h2>Next Evolution</h2>
                        <a href="show.php?id=<?php echo htmlspecialchars($nextEvolution['ID']); ?>">
                            <img src="<?php echo htmlspecialchars($nextEvolution['imageThumbnail']); ?>" alt="<?php echo htmlspecialchars($nextEvolution['name']); ?>">
                            <p><?php echo htmlspecialchars($nextEvolution['name']); ?></p>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="evolution-card"></div>
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
    $stmt = $pdo->prepare("SELECT ID, name, imageThumbnail FROM pokemon WHERE ID = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

</main>

<?php
require_once __DIR__ . '../../partials/end.php';
?>
