<?php
session_start();
require 'koneksi.php';

$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    die("Email dan password wajib diisi");
}

// ====================
// LOGIN SISWA
// ====================
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    die("Email tidak terdaftar");
}

if (!password_verify($password, $user['password'])) {
    die("Password salah");
}

// ====================
// LOGIN BERHASIL
// ====================
$_SESSION['login']   = true;
$_SESSION['user_id'] = $user['id'];
$_SESSION['role']    = 'siswa';

header("Location: dashboard.php");
exit;
