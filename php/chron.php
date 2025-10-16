<?php
require_once __DIR__ . '/config.php';

// 🌤 Wetter API
$weatherUrl = "https://api.open-meteo.com/v1/forecast?latitude=46.9481&longitude=7.4474&current=temperature_2m,relative_humidity_2m,rain,weather_code";
$weatherResponse = file_get_contents($weatherUrl);
$weatherData = json_decode($weatherResponse, true);

$temp = $weatherData['current']['temperature_2m'] ?? null;

// 🌞 UV API
$uvUrl = "https://currentuvindex.com/api/v1/uvi?latitude=40.6943&longitude=-73.9249";
$uvResponse = file_get_contents($uvUrl);
$uvData = json_decode($uvResponse, true);

$uvIndex = $uvData['uv_index'] ?? null;

// 💾 In Datenbank speichern
if ($temp !== null && $uvIndex !== null) {
    $stmt = $pdo->prepare("
        INSERT INTO plant_advice (timestamp, uv_index, temperature)
        VALUES (NOW(), :uv, :temp)
    ");
    $stmt->execute([
        ':uv' => $uvIndex,
        ':temp' => $temp
    ]);
    echo "✅ Daten erfolgreich gespeichert: UV={$uvIndex}, Temp={$temp}°C";
} else {
    echo "⚠️ Fehler: API-Daten unvollständig.";
}
?>
