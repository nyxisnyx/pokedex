<?php
function formatPokeId($number) {
    $formattedNumber = str_pad($number, 4, '0', STR_PAD_LEFT);
    return $formattedNumber . '#';
}


require_once __DIR__ . '/../../queries/connect.php'; 
require_once __DIR__ . '/../partials/pagination.php'; 


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 30; 


$searchQuery = '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
    
    
    if (is_numeric($searchQuery)) {
        $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE ID = :id");
        $stmt->execute(['id' => $searchQuery]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        
        if (strlen($searchQuery) === 1) {
            
            $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE name LIKE :search");
            $stmt->execute(['search' => $searchQuery . '%']);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            
            $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE name LIKE :search");
            $stmt->execute(['search' => '%' . $searchQuery . '%']);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}

$title = "Search Results";
require_once __DIR__ . '/../partials/head.php'; 
?>

<main>
    <form method="GET" action="search.php">
        <input type="text" name="search" placeholder="Search by name or ID" value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit">Search</button>
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
        <p>No results found for "<?php echo htmlspecialchars($searchQuery); ?>"</p>
    <?php endif; ?>
</main>

<?php
require_once __DIR__ . '/../partials/end.php'; 
?>
