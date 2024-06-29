<?php

$page_results = $results;

//Search functions
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
    if (is_numeric($searchQuery)) {
        $results = array_filter($page_results, function ($item) use ($searchQuery) {
            return $item['ID'] == $searchQuery;
        });
    } else {
        $searchQuery = strtolower($searchQuery);
        if (strlen($searchQuery) === 1) {
            $results = array_filter(
                $page_results,
                function ($item) use ($searchQuery) {
                    if ((strtolower($item['name'][0]) == $searchQuery)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            );
        } else {
            $results = array_filter(
                $page_results,
                function ($item) use ($searchQuery) {
                    if (str_contains(strtolower($item['name']), $searchQuery)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            );
        }
    }
}

// Define all Pokémon types
$allTypes = ["Normal", "Fire", "Fighting", "Water", "Flying", "Grass", "Poison", "Electric", "Ground", "Psychic", "Rock", "Ice", "Bug", "Dragon", "Ghost", "Dark", "Steel", "Fairy"];

$selectedTypes = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['type']) && is_array($_GET['type'])) {
    $selectedTypes = $_GET['type'];
    // Prepare the query to select Pokémon of selected types
    // $placeholders = implode(',', array_fill(0, count($selectedTypes), '?'));
    // $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE type1 IN ($placeholders) OR type2 IN ($placeholders)");
    // $stmt->execute(array_merge($selectedTypes, $selectedTypes));
    // $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results = array_filter($page_results, function ($item) use ($selectedTypes) {
        if (in_array($item["type1"], $selectedTypes) || in_array($item["type2"], $selectedTypes)) {
            return true;
        } else {
            return false;
        }
    });
}

?>

<div class="filter-search">
    <form class="filter-form" method="GET" action="">
        <div class="filter-div">
            <?php foreach ($allTypes as $type): ?>
                <label class="filter <?php echo htmlspecialchars($type); ?>">
                    <input type="checkbox" name="type[]" value="<?php echo htmlspecialchars($type); ?>" <?php echo in_array($type, $selectedTypes) ? 'checked' : ''; ?>>
                    <?php echo htmlspecialchars($type); ?>
                </label>
            <?php endforeach; ?>
        </div>
        <button type="submit">Filter</button>
    </form>
    <div class="search">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by name or ID">
            <button type="submit">Search</button>
        </form>
    </div>
</div>