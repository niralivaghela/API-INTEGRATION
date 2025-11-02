<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'db_connect.php';

$conn->query("DELETE FROM search_history");
echo json_encode(["status" => "success", "message" => "History cleared"]);
?>
