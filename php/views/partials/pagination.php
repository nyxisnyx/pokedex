<?php

// if (!isset($_SESSION['user'])) {
//     session_start();
// }

// Sets page to 1 per default, else set page to page clicked
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }
}

// function that transforms an int into a pokemon ID format
function formatPokeId($number)
{
    // Format the number to be four digits with leading zeros
    $formattedNumber = str_pad($number, 4, '0', STR_PAD_LEFT);

    // Add the hashtag at the end
    $pokeId = '#' . $formattedNumber;

    return $pokeId;
}

function generate_page_nav($page, $total_pages)
{
    $page = intval($page);
    echo "<div class='pagination'>";
    echo "<form action='' method='GET'>";
    echo "<input name='page' value='1' type='hidden'>";
    echo "<button type='submit'" . ($page == 1 ? "disabled" : "") . ">&laquo;</button>";
    echo "</form>";
    if ($page - 2 >= 1) {
        echo "<button disabled>...</button>";
        echo "<form action='' method='GET'>";
        echo "<input name='page' value='" . $page - 1 . "' type='hidden'>";
        echo "<button type='submit'>" . $page - 1 . "</button>";
        echo "</form>";
    }
    if ($page == 2) {
        echo "<form action='' method='GET'>";
        echo "<input name='page' value='" . $page - 1 . "' type='hidden'>";
        echo "<button type='submit'>" . $page - 1 . "</button>";
        echo "</form>";
    }
    echo "<form action='' method='GET'>";
    echo "<input name='page' value='" . $page . "' type='hidden'>";
    echo "<button class='active' type='submit'>$page</button>";
    echo "</form>";

    if ($page + 2 <= $total_pages) {
        echo "<form action='' method='GET'>";
        echo "<input name='page' value='" . $page + 1 . "' type='hidden'>";
        echo "<button type='submit'>" . $page + 1 . "</button>";
        echo "</form>";
        echo "<button disabled>...</button>";
    }

    if ($page == $total_pages - 1) {
        echo "<form action='' method='GET'>";
        echo "<input name='page' value='" . $page + 1 . "' type='hidden'>";
        echo "<button type='submit'>" . $page + 1 . "</button>";
        echo "</form>";
    }

    echo "<form action='' method='GET'>";
    echo "<input name='page' value='" . ($total_pages) . "' type='hidden'>";
    echo "<button type='submit'" . ($page == $total_pages ? "disabled" : "") . ">&raquo;</button>";
    echo "</form>";
    echo "</div>";
}


function generate_cards($sliced_results, $page)
{
    if (isset($_SESSION["user_id"])) {
        require ("../../queries/connect.php");
        $stmt = $pdo->prepare("SELECT pokemon_id FROM pokedex WHERE user_id=:user_id");
        $stmt->bindParam(':user_id', $_SESSION["user_id"]);
        $stmt->execute();
        $favourites = $stmt->fetchAll();
        $favourites = array_column($favourites, 'pokemon_id');
    }
    if (!empty($sliced_results)) {
        foreach ($sliced_results as $result) {
            echo "
            <div class='poke-card'>
            <a class='details-link' href='show.php?id=" . htmlspecialchars($result["ID"]) . "'>
            <img class='poke-thumbnail' src='" . $result["imageThumbnail"] . "'>
            </a>";
            if (isset($_SESSION["user_id"])) {
                if (in_array($result["ID"], $favourites)) {
                    echo "<img title='Add to favourites' class='fav favourite_" . $result['ID'] . "' src='../../../assets/images/star.svg'>";
                } else {
                    echo "<img title='Add to favourites' class='fav favourite_" . $result['ID'] . "' src='../../../assets/images/star_void.svg'>";
                }
            }
            echo "
                <div class='poke-details'>
                
                    <p class='poke-id'>" . formatPokeId($result["ID"]) . "</p>
                <a class='details-link' href='show.php?id=" . htmlspecialchars($result["ID"]) . "'>
                    <p class='poke-name'>" . $result["name"] . "</p>
                </a>
                    <div class='poke-types'>
                        <span class='" . $result["type1"] . "'>" . $result["type1"] . "</span>";
            if (!empty($result['type2'])) {
                echo "<span class='" . $result["type2"] . "'>" . $result["type2"] . "</span>";
            }
            ;
            echo "</div>
                </div>
        </div>";
        }
    } else {
        echo
            "<div class='no-result'>
            <p>You don't have any favourites yet!</p>
            <p>Go to <a href='../../index.php'>Pokedex</a> </p>
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

    generate_cards($sliced_results, $page);
    generate_page_nav($page, $total_pages);
}

?>