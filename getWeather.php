<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'db_connect.php';

$apiKey = "ebc4e2e6de91310d1b0fef805f18083d"; // Your OpenWeatherMap key
$city = $_GET['city'] ?? '';

if (empty($city)) {
    echo json_encode(["status" => "error", "message" => "City not provided"]);
    exit;
}

$url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=$apiKey&units=metric";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode != 200) {
    echo json_encode(["status" => "error", "message" => "Failed to fetch API"]);
    exit;
}

$data = json_decode($response, true);

if (isset($data['main'])) {
    $temp = $data['main']['temp'];
    $desc = $data['weather'][0]['description'];
    $icon = $data['weather'][0]['icon'];

    $stmt = $conn->prepare("INSERT INTO search_history (city, temperature, description, icon) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $city, $temp, $desc, $icon);
    $stmt->execute();

    echo json_encode([
        "status" => "success",
        "city" => $city,
        "temperature" => $temp,
        "description" => ucfirst($desc),
        "icon" => $icon
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid response"]);
}
?>
