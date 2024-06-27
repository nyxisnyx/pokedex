<?php

// "<img src='../../../assets/images/pokemon/" . $result["imageBig"] . "' alt='image not found'>"


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
    if ($page > 1) {
        echo '<a href="?page=' . ($page - 1) . '">&laquo; Previous</a>';
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        echo '<a href="?page=' . $i . '"' . ($i == $page ? ' class="active"' : '') . '>' . $i . '</a>';
    }
    if ($page < $total_pages) {
        echo '<a href="?page=' . ($page + 1) . '">Next &raquo;</a>';
    }
    echo "</div>";
}


function generate_cards($sliced_results)
{
    foreach ($sliced_results as $result) {
        echo "
        <div class='poke-card'>
            <a href='show.php?id=" . htmlspecialchars($result["ID"]) . "'>
                <img src='" . $result["imageThumbnail"] . "'>
                <div class='poke-details'>
                    <p class='poke-id'>" . formatPokeId($result["ID"]) . "</p>
                    <p class='poke-name'>" . $result["name"] . "</p>
                    <div class='poke-types'>
                        <span class='" . $result["type1"] . "'>" . $result["type1"] . "</span>";
                        if (!empty($result['type2'])) {
                            echo "<span class='" . $result["type2"] . "'>" . $result["type2"] . "</span>";
                        }
                    echo "</div>
                </div>
            </a>
        </div>";
    }
}


function paginate($results, $page)
{
    $page = intval($page);
    //  Defines the amount of pokemon displayed on one page
    $results_per_page = 100;
    $start_index = ($page - 1) * $results_per_page;
    $total_pages = ceil(count($results) / $results_per_page);
    $sliced_results = array_slice($results, $start_index, $results_per_page);

    generate_cards($sliced_results);
    generate_page_nav($page, $total_pages);
}

?>