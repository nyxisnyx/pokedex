<?php
// Connect to the DB
require_once __DIR__ . '/../../queries/connect.php'; // Adjust path as needed
require_once __DIR__ . '/../partials/pagination.php'; // Adjust path as needed

try {
    $query = "SELECT * FROM pokemon";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}

$title = "Home";
require_once __DIR__ . '/../partials/head.php'; // Adjust path as needed
?>

<main>
    <?php require_once '../partials/search&filter.php'; ?>

    <?php paginate($results, $page) ?>
</main>

<?php
require_once __DIR__ . '/../partials/end.php'; // Adjust path as needed
?>