<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$apiKey = "ebc4e2e6de91310d1b0fef805f18083d";
$city = $_GET['city'] ?? '';

if (empty($city)) {
    echo json_encode(["status" => "error", "message" => "City not provided"]);
    exit;
}

$url = "https://api.openweathermap.org/data/2.5/forecast?q=" . urlencode($city) . "&appid=$apiKey&units=metric";

$response = file_get_contents($url);
if (!$response) {
    echo json_encode(["status" => "error", "message" => "Failed to fetch forecast"]);
    exit;
}

$data = json_decode($response, true);
$forecast = [];

foreach ($data['list'] as $item) {
    if (strpos($item['dt_txt'], "12:00:00") !== false) {
        $forecast[] = [
            "date" => substr($item['dt_txt'], 0, 10),
            "temp" => $item['main']['temp'],
            "desc" => $item['weather'][0]['description']
        ];
    }
}

echo json_encode(["status" => "success", "forecast" => $forecast]);
?>
