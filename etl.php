<?php
include('config.php'); // Verbindung zur DB

// ----------------------------
// 1. Daten abrufen (Extract)
// ----------------------------

// API 1: UV-Index
$uv_url = "https://currentuvindex.com/api/v1/uvi?latitude=46.9481&longitude=7.4474";
$uv_data = json_decode(file_get_contents($uv_url), true);
$uv = $uv_data['uv'] ?? null;

// API 2: Open Meteo
$weather_url = "https://api.open-meteo.com/v1/forecast?latitude=46.9481&longitude=7.4474&current=temperature_2m,rain";
$weather_data = json_decode(file_get_contents($weather_url), true);
$temp = $weather_data['current']['temperature_2m'] ?? null;
$rain = $weather_data['current']['rain'] ?? 0;

// ----------------------------
// 2. Daten transformieren
// ----------------------------

// Funktion zur Berechnung der Gießmenge (ml)
function calculateWater($base, $temp, $uv, $rain) {
  $temp_factor = 1 + (($temp - 20) * 0.03);    // +3 % pro °C über 20
  $uv_factor   = 1 + ($uv * 0.05);             // +5 % pro UV-Punkt
  $rain_factor = max(0.3, 1 - ($rain * 0.1));  // -10 % pro mm Regen, min 30 %
  return round($base * $temp_factor * $uv_factor * $rain_factor);
}

// Berechnungen
$small  = calculateWater(100, $temp, $uv, $rain);
$medium = calculateWater(250, $temp, $uv, $rain);
$large  = calculateWater(500, $temp, $uv, $rain);

// ----------------------------
// 3. Daten speichern (Load)
// ----------------------------

$stmt = $pdo->prepare("
  INSERT INTO plant_advice 
  (timestamp, temperature, uv_index, rain, recommendation_small, recommendation_medium, recommendation_large)
  VALUES (NOW(), ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$temp, $uv, $rain, $small, $medium, $large]);

echo "🌿 Daten gespeichert!<br>";
echo "Temp: $temp °C, UV: $uv, Regen: $rain mm<br>";
echo "Empfehlung: klein=$small ml, mittel=$medium ml, gross=$large ml";
?>