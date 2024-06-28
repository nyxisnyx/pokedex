<?php
// Function that transforms an int into a Pokémon ID format
function formatPokeId($number) {
    $formattedNumber = str_pad($number, 4, '0', STR_PAD_LEFT);
    return $formattedNumber . '#';
}

// Connect to the DB
require_once __DIR__ . '/../../queries/connect.php'; // Adjust path as needed

// Define all Pokémon types
$allTypes = ["Normal", "Fire", "Fighting", "Water", "Flying", "Grass", "Poison", "Electric", "Ground", "Psychic", "Rock", "Ice", "Bug", "Dragon", "Ghost", "Dark", "Steel", "Fairy"];

// Handle filter input
$selectedTypes = [];
$results = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['type']) && is_array($_GET['type'])) {
    $selectedTypes = $_GET['type'];

    // Prepare the query to select Pokémon of selected types
    $placeholders = implode(',', array_fill(0, count($selectedTypes), '?'));
    $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE type1 IN ($placeholders) OR type2 IN ($placeholders)");
    $stmt->execute(array_merge($selectedTypes, $selectedTypes));
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$title = "Filter Results";
require_once __DIR__ . '/../partials/head.php'; // Adjust path as needed
?>

<main>
    <form method="GET" action="filter.php">
        <label for="type">Filter by Type:</label>
        <div>
            <?php foreach ($allTypes as $type): ?>
                <label>
                    <input type="checkbox" name="type[]" value="<?php echo htmlspecialchars($type); ?>" <?php echo in_array($type, $selectedTypes) ? 'checked' : ''; ?>>
                    <?php echo htmlspecialchars($type); ?>
                </label>
            <?php endforeach; ?>
        </div>
        <button type="submit">Filter</button>
    </form>
    
    <?php if (!empty($results)): ?>
        <div class="pokemon-list">
            <?php foreach ($results as $pokemon): ?>
                <div class="poke-card">
                    <a href="show.php?id=<?php echo htmlspecialchars($pokemon['ID']); ?>">
                        <img src="<?php echo htmlspecialchars($pokemon['imageThumbnail']); ?>" alt="<?php echo htmlspecialchars($pokemon['name']); ?>">
                        <div class="poke-details">
                            <p class="poke-id"><?php echo formatPokeId($pokemon['ID']); ?></p>
                            <p class="poke-name"><?php echo htmlspecialchars($pokemon['name']); ?></p>
                            <div class="poke-types">
                                <span class="<?php echo htmlspecialchars($pokemon['type1']); ?>"><?php echo htmlspecialchars($pokemon['type1']); ?></span>
                                <?php if (!empty($pokemon['type2'])): ?>
                                    <span class="<?php echo htmlspecialchars($pokemon['type2']); ?>"><?php echo htmlspecialchars($pokemon['type2']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results found for the selected type(s).</p>
    <?php endif; ?>
</main>

<?php
require_once __DIR__ . '/../partials/end.php'; // Adjust path as needed
?>
