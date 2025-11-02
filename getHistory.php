<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'db_connect.php';

$result = $conn->query("SELECT * FROM search_history ORDER BY id DESC LIMIT 5");
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["status" => "success", "history" => $data]);
?>
