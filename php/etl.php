<?php
require_once __DIR__ . '/config.php';

// ------------------------------------------
// ETL-Skript für Pflanzengieß-Empfehlung
// ------------------------------------------
// 1️⃣ Extract: Daten von APIs holen (UV & Wetter)
// 2️⃣ Transform: Daten berechnen / Empfehlungen erstellen
// 3️⃣ Load: In Datenbank speichern
// ------------------------------------------

// ========== 1️⃣ EXTRACT ==========

// 🌤️ Open-Meteo API (Temperatur & Regen)
$meteo_url = "https://api.open-meteo.com/v1/forecast?latitude=46.9481&longitude=7.4474&current=temperature_2m,rain";

// cURL initialisieren
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $meteo_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

// Anfrage ausführen
$meteo_json = curl_exec($ch);
curl_close($ch);

// JSON dekodieren
$meteo_data = json_decode($meteo_json, true);

// aktuelle Temperatur & Regen extrahieren
$temperature = $meteo_data['current']['temperature_2m'] ?? null;
$rain = $meteo_data['current']['rain'] ?? 0;


// ☀️ UV-Index API (currentuvindex.com)
$uv_url = "https://currentuvindex.com/api/v1/uvi?latitude=46.9481&longitude=7.4474";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $uv_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$uv_json = curl_exec($ch);
curl_close($ch);

$uv_data = json_decode($uv_json, true);
$uv_index = $uv_data['now']['uvi'] ?? null;


// ========== 2️⃣ TRANSFORM ==========

// einfache Berechnungslogik für Gießempfehlung (ml)
$base = 50; // ml Basiswert
$recommendation_small  = round($base + ($temperature * 2) + ($uv_index * 5) - ($rain * 3));
$recommendation_medium = round($recommendation_small * 2.5);
$recommendation_large  = round($recommendation_small * 5);

// negative Werte vermeiden
$recommendation_small  = max(0, $recommendation_small);
$recommendation_medium = max(0, $recommendation_medium);
$recommendation_large  = max(0, $recommendation_large);


// ========== 3️⃣ LOAD ==========

// Daten in Datenbank speichern
try {
  $stmt = $pdo->prepare("
    INSERT INTO plant_advice (timestamp, temperature, uv_index, rain, recommendation_small, recommendation_medium, recommendation_large)
    VALUES (NOW(), :temperature, :uv_index, :rain, :rec_small, :rec_medium, :rec_large)
  ");

  $stmt->execute([
    ':temperature' => $temperature,
    ':uv_index' => $uv_index,
    ':rain' => $rain,
    ':rec_small' => $recommendation_small,
    ':rec_medium' => $recommendation_medium,
    ':rec_large' => $recommendation_large
  ]);

  echo "🌿 Daten gespeichert!<br>";
  echo "Temp: {$temperature} °C, UV: {$uv_index}, Regen: {$rain} mm<br>";
  echo "Empfehlung: klein={$recommendation_small} ml, mittel={$recommendation_medium} ml, gross={$recommendation_large} ml";

} catch (PDOException $e) {
  echo "❌ Fehler beim Speichern: " . $e->getMessage();
}
?>