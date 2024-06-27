<?php


require_once ("../../queries/connect.php");
require_once ("../partials/pagination.php");

$stmt = $pdo->prepare("SELECT * FROM pokemon;");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$title = "Home";
require_once __DIR__ . '../../partials/head.php';
?>

<main>

    <form method="GET" action="search.php">
        <input type="text" name="search" placeholder="Search by name or ID">
        <button type="submit">Search</button>
    </form>

    <?php paginate($results, $page); ?>
</main>

<?php
require_once __DIR__ . '../../partials/end.php';
?>