<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
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
  max-width:380px;
  background:var(--glass);
  border-radius:20px;
  padding:32px 28px;
  box-shadow:0 25px 60px rgba(0,0,0,.18);
}

.brand{
  display:flex;
  justify-content:center;
  align-items:center;
  gap:12px;
  margin-bottom:24px;
}

.logo{
  width:46px;
  height:46px;
  border-radius:14px;
  background:linear-gradient(135deg,var(--green),var(--brown));
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:700;
  font-size:18px;
}

.form-control{
  border-radius:12px;
  padding:10px 12px;
}

.btn-admin{
  background:var(--green);
  color:#fff;
  border:0;
  border-radius:12px;
  padding:10px;
  font-weight:500;
}

.btn-admin:hover{
  background:#5c8364;
}
</style>
</head>

<body>

<div class="login-card">

  <!-- BRAND -->
  <div class="brand">
    <div class="logo">EL</div>
    <div>
      <div style="font-family:'Poppins';font-weight:700">E-Learning</div>
      <div class="text-muted small">Admin Login</div>
    </div>
  </div>

  <!-- FORM LOGIN -->
  <form method="post" action="login_process.php">
    <div class="mb-3">
      <input name="username" class="form-control" placeholder="Username admin" required>
    </div>

    <div class="mb-3">
      <input name="password" type="password" class="form-control" placeholder="Password" required>
    </div>

    <button class="btn btn-admin w-100">
      Login Admin
    </button>
  </form>

</div>

</body>
</html>
