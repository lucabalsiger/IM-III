<?php
require_once __DIR__ . '/config.php';

// -------------------------------------
// API für Pflanzengieß-Empfehlung
// -------------------------------------
// Optionaler GET-Parameter ?days=Zahl
// -> z.B. api.php?days=7 zeigt letzte 7 Tage
// Standard: 1 (nur heute)
// -------------------------------------

$days = isset($_GET['days']) ? (int)$_GET['days'] : 1;

// Wenn nur heutige Daten gewünscht sind
if ($days <= 1) {

  // 🔹 Nur Datensätze vom heutigen Tag abrufen
  $stmt = $pdo->query("
    SELECT * FROM plant_advice
    WHERE DATE(timestamp) = CURDATE()
    ORDER BY timestamp DESC
    LIMIT 1
  ");

} else {

  // 🔹 Datensätze der letzten X Tage abrufen
  $stmt = $pdo->prepare("
    SELECT * FROM plant_advice
    WHERE DATE(timestamp) >= CURDATE() - INTERVAL :days DAY
    ORDER BY timestamp DESC
  ");
  $stmt->bindValue(':days', $days, PDO::PARAM_INT);
  $stmt->execute();
}

// Ergebnis auslesen
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// -------------------------------------
// Ausgabe als JSON
// -------------------------------------
header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>