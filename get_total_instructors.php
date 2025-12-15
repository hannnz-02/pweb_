<?php
require 'koneksi.php';

header('Content-Type: application/json');

// PASTIKAN nama tabel BENAR
$stmt = $pdo->query("SELECT COUNT(*) AS total FROM mentor");
$result = $stmt->fetch();

echo json_encode([
    'total' => (int)$result['total']
]);
