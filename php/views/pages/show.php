<?php
require_once __DIR__ . '../../partials/head.php';

// Include database connection
require_once ("../../queries/connect.php");

// Function to get Pokemon details by ID
function getPokemonDetails($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE ID = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to recursively fetch previous evolutions
function fetchPreviousEvolutions($id, &$evolutions) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE evolutionNext = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $prevEvolutions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($prevEvolutions as $prevEvolution) {
        $evolutions[] = $prevEvolution;
        fetchPreviousEvolutions($prevEvolution['ID'], $evolutions);
    }
}

// Function to recursively fetch next evolutions
function fetchNextEvolutions($id, &$evolutions) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE evolutionPrev = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $nextEvolutions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($nextEvolutions as $nextEvolution) {
        $evolutions[] = $nextEvolution;
        fetchNextEvolutions($nextEvolution['ID'], $evolutions);
    }
}

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
            echo "<p>Pokemon not found.</p>";
        } else {
            // Fetch details for previous and next evolutions if IDs are available
            $prevEvolution = $pokemon['evolutionPrev'] ? getPokemonDetails($pokemon['evolutionPrev']) : null;
            $nextEvolution = $pokemon['evolutionNext'] ? getPokemonDetails($pokemon['evolutionNext']) : null;

            // Fetch all previous evolutions recursively
            $allPrevEvolutions = [];
            if ($prevEvolution) {
                fetchPreviousEvolutions($prevEvolution['ID'], $allPrevEvolutions);
            }

            // Fetch all next evolutions recursively
            $allNextEvolutions = [];
            if ($nextEvolution) {
                fetchNextEvolutions($nextEvolution['ID'], $allNextEvolutions);
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<p>Invalid Pokemon ID.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pokemon['name'] ?? 'Pokemon Details'); ?></title>
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
    <main>
        <?php if ($pokemon): ?>
            <div class="pokemon-details">
                <div class="detailsleft">
                    <h1><?php echo htmlspecialchars($pokemon['name']) ?? 'Pokemon Details'; ?></h1>
                    
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

                    <div class="evolution">
                
                        <?php if ($prevEvolution): ?>
                            <div class="evolution-card">
                                <a href="show.php?id=<?php echo htmlspecialchars($prevEvolution['ID']); ?>">
                                    <img src="<?php echo htmlspecialchars($prevEvolution['imageThumbnail']); ?>" alt="<?php echo htmlspecialchars($prevEvolution['name']); ?>">
                                    <p><?php echo htmlspecialchars($prevEvolution['name']); ?></p>
                                </a>
                            </div>
                            <?php // Display all previous evolutions of the previous evolution ?>
                        <?php if (!empty($allPrevEvolutions)): ?>
                            <?php foreach ($allPrevEvolutions as $prevEvolution): ?>
                                <div class="evolution-card">
                                    <a href="show.php?id=<?php echo htmlspecialchars($prevEvolution['ID']); ?>">
                                        <img src="<?php echo htmlspecialchars($prevEvolution['imageThumbnail']); ?>" alt="<?php echo htmlspecialchars($prevEvolution['name']); ?>">
                                        <p><?php echo htmlspecialchars($prevEvolution['name']); ?></p>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php else: ?>
                            <div class="evolution-card"></div>
                        <?php endif; ?>
                    
                        <div class="evolution-card">
                            <img src="<?php echo htmlspecialchars($pokemon['imageThumbnail']); ?>" alt="<?php echo htmlspecialchars($pokemon['name']); ?>">
                            <p><?php echo htmlspecialchars($pokemon['name']); ?></p>
                        </div>
                    
                        <?php if ($nextEvolution): ?>
                            <div class="evolution-card">
                                <a href="show.php?id=<?php echo htmlspecialchars($nextEvolution['ID']); ?>">
                                    <img src="<?php echo htmlspecialchars($nextEvolution['imageThumbnail']); ?>" alt="<?php echo htmlspecialchars($nextEvolution['name']); ?>">
                                    <p><?php echo htmlspecialchars($nextEvolution['name']); ?></p>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="evolution-card"></div>
                        <?php endif; ?>
                    
                    
                    
                        <?php // Display all next evolutions of the next evolution ?>
                        <?php if (!empty($allNextEvolutions)): ?>
                            <?php foreach ($allNextEvolutions as $nextEvolution): ?>
                                <div class="evolution-card">
                                    <a href="show.php?id=<?php echo htmlspecialchars($nextEvolution['ID']); ?>">
                                        <img src="<?php echo htmlspecialchars($nextEvolution['imageThumbnail']); ?>" alt="<?php echo htmlspecialchars($nextEvolution['name']); ?>">
                                        <p><?php echo htmlspecialchars($nextEvolution['name']); ?></p>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="detailsright">
                    <img class="poke-image" src="../../../assets/images/pokemon/<?php echo htmlspecialchars($pokemon['imageBig']); ?>" alt="<?php echo htmlspecialchars($pokemon['name']); ?> sprite">
                    <p><?php echo htmlspecialchars($pokemon["ID"]); ?></p>
                </div>
            </div>
        <?php else: ?>
            <p>No Pokemon found with ID <?php echo htmlspecialchars($pokemonId); ?></p>
        <?php endif; ?>
    </main>
</body>
</html>

<?php
require_once __DIR__ . '../../partials/end.php';
?>
