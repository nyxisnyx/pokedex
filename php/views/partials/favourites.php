<?php


$json = file_get_contents('php://input');
$data = json_decode($json, true);
echo var_dump($data);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // if (isset($data['id'])) {
    //     $id = $data['id'];

    //     // Call PHP function here
    //     // add_favourite($id);
    //     echo $id;

    //     // Send a JSON response back to the JavaScript
    //     echo json_encode(['status' => 'success', 'message' => 'Received ID: ' . htmlspecialchars($id)]);
    // } else {
    //     echo json_encode(['status' => 'error', 'message' => 'ID not set']);
    // }
}

function handleId($id)
{
    // Your PHP function logic here
    // For example, save the ID to the database or perform other actions
    // Example:
    // $db = new PDO('mysql:host=localhost;dbname=test', 'username', 'password');
    // $stmt = $db->prepare('INSERT INTO favorites (id) VALUES (:id)');
    // $stmt->execute(['id' => $id]);

    // For now, we'll just log the ID for demonstration purposes
    error_log("Handled ID: " . $id);
}

?>