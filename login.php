<?php
session_start();
require 'koneksi.php';
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login — E-Learning Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
  --green:#6D9773;
  --dark:#0C3B2E;
  --brown:#BB8A52;
  --glass:rgba(255,255,255,.9);
}
body{
  min-height:100vh;
  background:linear-gradient(135deg,var(--dark),var(--green));
  display:flex;
  align-items:center;
  justify-content:center;
  font-family:'Roboto',sans-serif;
}
.login-card{
  width:100%;
  max-width:420px;
  background:var(--glass);
  border-radius:18px;
  padding:32px;
  box-shadow:0 20px 50px rgba(0,0,0,.15);
}
.brand{
  display:flex;
  align-items:center;
  gap:10px;
  justify-content:center;
  margin-bottom:24px;
}
.logo{
  width:46px;height:46px;
  border-radius:12px;
  background:linear-gradient(135deg,var(--green),var(--brown));
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:700;
}
.btn-main{
  background:var(--green);
  color:#fff;
  border:0;
}
.btn-main:hover{background:#5c8364}
</style>
</head>

<body>
<div class="login-card">
  <div class="brand">
    <div class="logo">EL</div>
    <div>
      <div style="font-weight:700;font-family:'Poppins'">E-Learning</div>
      <div class="text-muted small">Login</div>
    </div>
  </div>

  <form method="post" action="login_process.php">
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <button class="btn btn-main w-100 py-2">Login</button>
  </form>

  <div class="text-center mt-3 small">
    Belum punya akun? <a href="register.php" class="text-decoration-none fw-semibold" style="color:var(--dark)">Daftar</a>
  </div>
</div>
</body>
</html>
