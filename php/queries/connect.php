<?php

$db = "mysql:host=localhost;dbname=pokedex;charset=utf8";

try {
    $pdo = new PDO($db, "root", "");
    // echo "connection successful!";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "";
}

?>