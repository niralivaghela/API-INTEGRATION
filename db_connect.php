<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db   = "weather_dashboard";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB connection failed: " . $conn->connect_error]));
}
?>
