<?php


// Sets page to 1 per default, else set page to page clicked
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }
}

function generate_page_nav($page, $total_pages)
{
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<form action='' method='GET' style='display:inline;'>";
        echo "<input name='page' value='$i' type='hidden'>";
        echo "<button type='submit'>$i</button>";
        echo "</form>";
    }
    echo "</div>";
}

function generate_cards($sliced_results)
{
    foreach ($sliced_results as $result) {
        echo "
        <div class='poke-card'>
            <img src='" . $result["imageThumbnail"] . "'>
            <div class='poke-details'>
                <p class='poke-id'>" . formatPokeId($result["ID"]) . "</p>
                <p class='poke-name'>" . $result["name"] . "</p>
                <div class='poke-types'>
                    <span class='" . $result["type1"] . "'>" . $result["type1"] . "</span>
                    <span class='" . $result["type2"] . "'>" . $result["type2"] . "</span>
                </div>
            </div>
        </div>";
    }




}

function paginate($results, $page)
{
    echo count($results);
    echo $page;
    //  Defines the amount of pokemon displayed on one page
    $results_per_page = 100;
    $start_index = ($page - 1) * $results_per_page;
    $total_pages = ceil(count($results) / $results_per_page);
    $sliced_results = array_slice($results, $start_index, $results_per_page);

    generate_cards($sliced_results);
    generate_page_nav($page, $total_pages);
}

?>

<!-- <main> -->
<!-- <h1>Pokedex - Homepage</h1>
    <p>Hello <strong><?php echo $user['name'] ?></p></strong>
<a href="/pokemon?name=pikachu">Pikachu</a> -->
<!-- <?php foreach ($results as $result): ?> -->

    <!-- <?php echo "<img src='../../../assets/images/pokemon/" . $result["imageBig"] . "' alt='image not found'>"; ?> -->



<?php endforeach; ?>

</main> -->