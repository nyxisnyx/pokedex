<?php


// Connects to the DB
require_once ("../../queries/connect.php");
require_once ("../partials/pagination.php");

$stmt = $pdo->prepare("SELECT * FROM pokemon;");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$title = "Home";
require_once __DIR__ . '../../partials/head.php';
?>

<main>
    <?php
    paginate($results, $page);
    ?>
</main>

<?php
require_once __DIR__ . '../../partials/end.php';
?>