<?php
// Include database connection
require_once ("../../queries/connect.php");
require_once ("../partials/pagination.php");

// Function to get Pokemon details by ID
function getPokemonDetails($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE ID = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to recursively fetch previous evolutions
function fetchPreviousEvolutions($id, &$evolutions)
{
    $pokemon = getPokemonDetails($id);
    $prevEvolution = $pokemon['evolutionPrev'] ? getPokemonDetails($pokemon['evolutionPrev']) : null;
    if ($prevEvolution) {
        $evolutions[] = $prevEvolution;
        if ($prevEvolution["evolutionPrev"]) {
            fetchPreviousEvolutions($prevEvolution['ID'], $evolutions);
        }
    }

}

// Function to recursively fetch next evolutions
function fetchNextEvolutions($id, &$evolutions)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE evolutionPrev = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $nextEvolutions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($nextEvolutions)) {
        foreach ($nextEvolutions as $nextEvolution) {
            $evolutions[] = $nextEvolution;
            if ($nextEvolution["evolutionNext"]) {
                fetchNextEvolutions($nextEvolution['ID'], $evolutions);
            }
        }
    }
}

// Retrieve Pokemon ID from query parameter
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $pokemonId = $_GET['id'];
    $pokemon = getPokemonDetails($pokemonId);

    // Fetch all previous evolutions recursively
    $allPrevEvolutions = [];
    fetchPreviousEvolutions($pokemonId, $allPrevEvolutions);
    $allPrevEvolutions = array_reverse($allPrevEvolutions);

    // Fetch all next evolutions recursively
    $allNextEvolutions = [];
    fetchNextEvolutions($pokemonId, $allNextEvolutions);

} else {
    echo "<p>Invalid Pokemon ID.</p>";
}
?>

<?php
$title = "Details";
require_once __DIR__ . '/../partials/head.php';
$title = "Show Details"; ?>
<main>
    <?php

    if ($pokemon): ?>
        <div class="pokemon-details">
            <div class="detailsleft">
                <div class="infosBlock">
                    <h1><?php echo htmlspecialchars($pokemon['name']) ?? 'Pokemon Details'; ?></h1>
                    <div class="details-types">
                        <p class='<?php echo $pokemon["type1"] ?>'><?php echo ($pokemon['type1']); ?>
                        </p>
                        <?php if (!empty($pokemon['type2'])) {
                            echo "<p class='" . $pokemon["type2"] . "'>" .
                                $pokemon['type2'] . "</p>";
                        }
                        ?>
                    </div>
                    <div class="stats">
                        <div class="stat-bar">
                            <span>HP</span>
                            <div class="bar">
                                <div class="bar-fill" style="width: <?php echo htmlspecialchars($pokemon['HP'] / 2); ?>%;">
                                </div>
                            </div>
                        </div>
                        <div class="stat-bar">
                            <span>Attack</span>
                            <div class="bar">
                                <div class="bar-fill"
                                    style="width: <?php echo htmlspecialchars($pokemon['Attack'] / 2); ?>%;">
                                </div>
                            </div>
                        </div>
                        <div class="stat-bar">
                            <span>Defense</span>
                            <div class="bar">
                                <div class="bar-fill"
                                    style="width: <?php echo htmlspecialchars($pokemon['Defense'] / 2); ?>%;">
                                </div>
                            </div>
                        </div>
                        <div class="stat-bar">
                            <span>Sp. Attack</span>
                            <div class="bar">
                                <div class="bar-fill"
                                    style="width: <?php echo htmlspecialchars($pokemon['Sp. Attack'] / 2); ?>%;"></div>
                            </div>
                        </div>
                        <div class="stat-bar">
                            <span>Sp. Defense</span>
                            <div class="bar">
                                <div class="bar-fill"
                                    style="width: <?php echo htmlspecialchars($pokemon['Sp. Defense'] / 2); ?>%;"></div>
                            </div>
                        </div>
                        <div class="stat-bar">
                            <span>Speed</span>
                            <div class="bar">
                                <div class="bar-fill"
                                    style="width: <?php echo htmlspecialchars($pokemon['Speed'] / 2); ?>%;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="evolution">
                    <?php if (!empty($allPrevEvolutions)): ?>
                        <?php
                        foreach ($allPrevEvolutions as $prevEvolution): ?>
                            <div class="evolution-card">
                                <a href="show.php?id=<?php echo htmlspecialchars($prevEvolution['ID']); ?>">
                                    <div class="evolPic"><img
                                            src="<?php echo htmlspecialchars($prevEvolution['imageThumbnail']); ?>"
                                            alt="<?php echo htmlspecialchars($prevEvolution['name']); ?>"></div>
                                    <p><?php echo htmlspecialchars($prevEvolution['name']); ?></p>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="evolution-card">
                        <div class="evolPic"><img class="current"
                                src="<?php echo htmlspecialchars($pokemon['imageThumbnail']); ?>"
                                alt="<?php echo htmlspecialchars($pokemon['name']); ?>"></div>
                        <p><?php echo htmlspecialchars($pokemon['name']); ?></p>
                    </div>
                    <?php if (!empty($allNextEvolutions)): ?>
                        <?php foreach ($allNextEvolutions as $nextEvolution): ?>
                            <div class="evolution-card">
                                <a href="show.php?id=<?php echo htmlspecialchars($nextEvolution['ID']); ?>">
                                    <div class="evolPic"><img
                                            src="<?php echo htmlspecialchars($nextEvolution['imageThumbnail']); ?>"
                                            alt="<?php echo htmlspecialchars($nextEvolution['name']); ?>"></div>
                                    <p><?php echo htmlspecialchars($nextEvolution['name']); ?></p>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="detailsright">
                <img class="poke-image"
                    src="../../../assets/images/pokemon/<?php echo htmlspecialchars($pokemon['imageBig']); ?>"
                    alt="<?php echo htmlspecialchars($pokemon['name']); ?> sprite">
                <p class="pokeId"><?php echo formatPokeId(htmlspecialchars($pokemon["ID"])); ?></p>
                <p class="pokeDescri"><strong>Description:</strong> <?php echo htmlspecialchars($pokemon['description']); ?>
                </p>
            </div>
        </div>
    <?php else: ?>
        <p>No Pokemon found with ID <?php echo htmlspecialchars($pokemonId); ?></p>
    <?php endif; ?>
</main>

<?php
require_once __DIR__ . '../../partials/end.php';
?>