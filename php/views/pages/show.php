<?php
$title = $_GET['name'];
require_once '../partials/head.php';
?>

<main>
    <h1>
        <?php echo $_GET['name']; ?>
    </h1>
    <a href="/">Homepage</a>
</main>

<?php
require_once '../partials/end.php';
?>