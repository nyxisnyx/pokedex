<?php
// Function that transforms an int into a Pokémon ID format
function formatPokeId($number) {
    $formattedNumber = str_pad($number, 4, '0', STR_PAD_LEFT);
    return $formattedNumber . '#';
}

// Connect to the DB
require_once __DIR__ . '/../../queries/connect.php'; // Adjust path as needed
require_once __DIR__ . '/../partials/pagination.php'; // Adjust path as needed

// Initialize page variable
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20; // Adjust as needed

// Handle search and filter inputs
$searchQuery = '';
$selectedTypes = [];
$results = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
    $selectedTypes = isset($_GET['type']) && is_array($_GET['type']) ? $_GET['type'] : [];

    // Build the query based on search and filter inputs
    $query = "SELECT * FROM pokemon WHERE 1=1";
    $params = [];

    if ($searchQuery !== '') {
        $query .= " AND (name LIKE :search OR ID = :searchExact)";
        $params['search'] = $searchQuery . '%';
        $params['searchExact'] = $searchQuery;
    }

    if (!empty($selectedTypes)) {
        $placeholders = implode(',', array_fill(0, count($selectedTypes), '?'));
        $query .= " AND (type1 IN ($placeholders) OR type2 IN ($placeholders))";
        $params = array_merge($params, $selectedTypes, $selectedTypes);
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Define all Pokémon types
$allTypes = ["Normal", "Fire", "Fighting", "Water", "Flying", "Grass", "Poison", "Electric", "Ground", "Psychic", "Rock", "Ice", "Bug", "Dragon", "Ghost", "Dark", "Steel", "Fairy"];

$title = "Home";
require_once __DIR__ . '/../partials/head.php'; // Adjust path as needed
?>

<main>
    <form method="GET" action="index.php">
        <input type="text" name="search" placeholder="Search by name or ID" value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit">Search</button>
        
        <div class="filter-section">
            <label for="type">Filter by Type:</label>
            <div class="type-checkboxes">
                <?php foreach ($allTypes as $type): ?>
                    <label class="type-checkbox">
                        <input type="checkbox" name="type[]" value="<?php echo htmlspecialchars($type); ?>" <?php echo in_array($type, $selectedTypes) ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($type); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        
        <button type="submit">Filter</button>
    </form>
    
    <?php if (!empty($results)): ?>
        <div class="pokemon-list">
            <?php paginate($results, $page, $per_page); ?>
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
        <p>No results found for "<?php echo htmlspecialchars($searchQuery); ?>"</p>
    <?php endif; ?>
</main>

<?php
require_once __DIR__ . '/../partials/end.php'; // Adjust path as needed
?>
