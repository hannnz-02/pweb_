<?php
session_start();
require 'koneksi.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    die("Username dan password wajib diisi");
}

$stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    die("Admin tidak ditemukan");
}

if (!password_verify($password, $admin['password'])) {
    die("Password admin salah");
}

// ✅ WAJIB: SET SESSION ROLE
$_SESSION['role'] = 'admin';
$_SESSION['admin_id'] = $admin['id'];
$_SESSION['username'] = $admin['username'];

// ✅ REDIRECT KE DASHBOARD
header("Location: dasboard.php");
exit;
