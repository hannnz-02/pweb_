<?php
require 'koneksi.php';

$role = $_POST['role'];

if ($role === 'siswa') {

    // ======================
    // REGISTER SISWA → users
    // ======================
    $cek = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $cek->execute([$_POST['email']]);
    if ($cek->rowCount() > 0) {
        die("Email siswa sudah terdaftar");
    }

    $stmt = $pdo->prepare("
      INSERT INTO users
      (name, gender, username, kelas, email, password)
      VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
      $_POST['name'],
      $_POST['gender'],
      $_POST['username'],
      $_POST['kelas'],
      $_POST['email'],
      password_hash($_POST['password'], PASSWORD_DEFAULT)
    ]);

}
elseif ($role === 'pengajar') {

    // ======================
    // REGISTER MENTOR → mentor
    // ======================
    $cek = $pdo->prepare("SELECT id FROM mentor WHERE email=?");
    $cek->execute([$_POST['email']]);
    if ($cek->rowCount() > 0) {
        die("Email mentor sudah terdaftar");
    }

    $stmt = $pdo->prepare("
      INSERT INTO mentor
      (nama, jk, email, telp, tgl_lahir, jadwal, mapel, kelas)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
      $_POST['nama'],
      $_POST['jk'],
      $_POST['email'],
      $_POST['telp'],
      $_POST['tgl_lahir'],
      $_POST['jadwal'],
      $_POST['mapel'],
      $_POST['kelas']
    ]);
}

header("Location: login.php");
exit;
