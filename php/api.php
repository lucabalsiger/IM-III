<?php
require_once __DIR__ . '/config.php';
header('Content-Type: application/json');

$pdo = new PDO($dsn, $username, $password, $options);

$days = isset($_GET['days']) ? (int)$_GET['days'] : 1;

if ($days <= 1) {
    // Nur heute, 1 Datensatz pro Stunde
    $stmt = $pdo->query("
        SELECT uv_index, temperature, timestamp
        FROM plant_advice pa
        INNER JOIN (
            SELECT DATE_FORMAT(timestamp, '%Y-%m-%d %H') AS hour, MAX(timestamp) AS max_ts
            FROM plant_advice
            WHERE DATE(timestamp) = CURDATE()
            GROUP BY hour
        ) grouped
        ON pa.timestamp = grouped.max_ts
        ORDER BY timestamp DESC
    ");
} else {
    // Letzte X Tage, 1 Datensatz pro Stunde
    $stmt = $pdo->prepare("
        SELECT uv_index, temperature, timestamp
        FROM plant_advice pa
        INNER JOIN (
            SELECT DATE_FORMAT(timestamp, '%Y-%m-%d %H') AS hour, MAX(timestamp) AS max_ts
            FROM plant_advice
            WHERE timestamp >= NOW() - INTERVAL :days DAY
            GROUP BY hour
        ) grouped
        ON pa.timestamp = grouped.max_ts
        ORDER BY timestamp DESC
    ");
    $stmt->bindValue(':days', $days, PDO::PARAM_INT);
    $stmt->execute();
}

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
