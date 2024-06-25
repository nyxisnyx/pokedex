<?php

require_once ("../../queries/connect.php");

// Defines the amount of pokemon displayed on one page
$results_per_page = 100;

// Sets page to 1 per default, else set page to page clicked
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }
}

$start_index = ($page - 1) * $results_per_page;

$stmt = $pdo->prepare("SELECT * FROM pokemon LIMIT $start_index, $results_per_page");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo count($results);

// function paginate($results)
// {
//     $total_pages = ceil((count($results)) / $results_per_page);
//     return $total_pages;
// }

$total_pages = paginate(range(1, 1000));

?>


<div class="pagination">
    <?php for ($i = 0; $i <= $total_pages; $i++): ?>
        <form action="" METHOD="GET">
            <input name="page" value="1" type="hidden">
            <a href=""><?php echo $i ?></a>
        </form>
    <?php endfor; ?>
</div>