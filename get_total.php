<?php
require 'koneksi.php';

try {
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM users");
    $row = $stmt->fetch();

    echo json_encode([
        'total' => (int)$row['total']
    ]);
} catch (Exception $e) {
    echo json_encode([
        'total' => 0
    ]);
}
