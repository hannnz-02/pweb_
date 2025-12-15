<?php
require __DIR__ . '/koneksi.php';

$stmt = $pdo->query("SELECT COUNT(*) FROM classes");
$total = $stmt->fetchColumn();

echo json_encode([
    'total' => (int)$total
]);
