<?php
// Define all Pokémon types
$allTypes = ["Normal", "Fire", "Fighting", "Water", "Flying", "Grass", "Poison", "Electric", "Ground", "Psychic", "Rock", "Ice", "Bug", "Dragon", "Ghost", "Dark", "Steel", "Fairy"];

$selectedTypes = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['type']) && is_array($_GET['type'])) {
    $selectedTypes = $_GET['type'];

    // Prepare the query to select Pokémon of selected types
    $placeholders = implode(',', array_fill(0, count($selectedTypes), '?'));
    $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE type1 IN ($placeholders) OR type2 IN ($placeholders)");
    $stmt->execute(array_merge($selectedTypes, $selectedTypes));
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<form class="filter-form" method="GET" action="">
    <div class="filter-div">
        <?php foreach ($allTypes as $type): ?>
            <label class="filter <?php echo htmlspecialchars($type); ?>">
                <input type="checkbox" name="type[]" value="<?php echo htmlspecialchars($type); ?>" <?php echo in_array($type, $selectedTypes) ? 'checked' : ''; ?>>
                <!-- <span class="checkbox"></span> -->
                <?php echo htmlspecialchars($type); ?>
                <span class="checkbox-value <?php echo htmlspecialchars($type); ?>"></span>
            </label>
        <?php endforeach; ?>
    </div>
    <button type="submit">Filter</button>
</form>