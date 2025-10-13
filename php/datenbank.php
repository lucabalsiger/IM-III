<?php

require_once 'config.php'; // Bindet die Datenbankkonfiguration ein

try {
    // Erstellt eine neue PDO-Instanz mit der Konfiguration aus config.php
    $pdo = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * FROM `test`";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $results = $stmt->fetchAll();

    echo "<pre>";
    print_r($results);
    echo "</pre>";

} catch (PDOException $e) {
    // Behandelt Verbindungsfehler
    echo "Datenbankverbindungsfehler: " . $e->getMessage();
    exit;
}
