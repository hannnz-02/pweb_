<?php
try {
    $pdo = new PDO(
        "mysql:host=sql110.infinityfree.com;dbname=if0_40695714_bimbel_db;charset=utf8mb4",
        "if0_40695714",
        "E30TiU75HGx"
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


} catch (PDOException $e) {
    die("Koneksi DB GAGAL ❌ : " . $e->getMessage());
}
?>
