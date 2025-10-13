<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Schritt 1: Daten holen (für Bern)

$uv_url = "https://currentuvindex.com/api/v1/uvi?latitude=46.9481&longitude=7.4474";
$weather_url = "https://api.open-meteo.com/v1/forecast?latitude=46.9481&longitude=7.4474&current=temperature_2m,relative_humidity_2m,rain,weather_code";

// Daten abrufen
$uv_json = file_get_contents($uv_url);
$weather_json = file_get_contents($weather_url);

// Prüfen, ob etwas zurückkam
if (!$uv_json || !$weather_json) {
    die("Fehler beim Abrufen der API-Daten!");
}

// In PHP-Arrays umwandeln
$uv_data = json_decode($uv_json, true);
$weather_data = json_decode($weather_json, true);

// Beides zusammen speichern
$result = [
    "uv" => $uv_data,
    "weather" => $weather_data
];

file_put_contents("../data_raw.json", json_encode($result, JSON_PRETTY_PRINT));

echo "✅ Daten erfolgreich geholt (Bern) und in data_raw.json gespeichert!";
?>
