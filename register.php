<?php
session_start();
require 'koneksi.php';
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register — E-Learning</title>

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

.auth-card{
  width:100%;
  max-width:460px;
  background:var(--glass);
  border-radius:20px;
  padding:32px 28px;
  box-shadow:0 25px 60px rgba(0,0,0,.18);
  animation:fadeUp .6s ease;
}

@keyframes fadeUp{
  from{opacity:0; transform:translateY(16px);}
  to{opacity:1; transform:translateY(0);}
}

.brand{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:12px;
  margin-bottom:22px;
}

.logo{
  width:48px;height:48px;
  border-radius:14px;
  background:linear-gradient(135deg,var(--green),var(--brown));
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:700;
  font-size:18px;
}

.brand-title{
  font-family:'Poppins',sans-serif;
  font-weight:700;
}

.form-control, .form-select{
  border-radius:10px;
  padding:10px 12px;
}

.btn-main{
  background:var(--green);
  color:#fff;
  border:0;
  border-radius:12px;
  padding:10px;
}
.btn-main:hover{background:#5c8364}

.role-box{
  background:#f6f7f8;
  border-radius:12px;
  padding:14px;
  margin-bottom:16px;
}

.hidden{
  display:none;
}
</style>
</head>

<body>

<div class="auth-card">

  <!-- BRAND -->
  <div class="brand">
    <div class="logo">EL</div>
    <div>
      <div class="brand-title">E-Learning</div>
      <div class="text-muted small">Create Account</div>
    </div>
  </div>

  <!-- PILIH ROLE -->
  <div class="role-box">
    <label class="small text-muted mb-1">Daftar sebagai</label>
    <select id="role" class="form-select" onchange="switchForm()" required>
      <option value="">-- Pilih Role --</option>
      <option value="siswa">Siswa</option>
      <option value="pengajar">Mentor</option>
    </select>
  </div>

  <!-- FORM SISWA -->
<form method="post" action="register_process.php" id="formSiswa" class="hidden">
  <input type="hidden" name="role" value="siswa">

  <!-- STEP INDICATOR -->
  <div class="d-flex justify-content-between mb-3 small fw-semibold">
    <span id="siswaStep1Label" style="color:var(--green)">1. Akun</span>
    <span id="siswaStep2Label" class="text-muted">2. Sekolah</span>
  </div>

  <!-- STEP 1 -->
  <div id="siswaStep1">
    <input name="name" class="form-control mb-2" placeholder="Nama Lengkap" required>
    <input name="username" class="form-control mb-2" placeholder="Username" required>
    <input name="email" type="email" class="form-control mb-2" placeholder="Email" required>
    <input name="password" type="password" class="form-control mb-3" placeholder="Password" required>

    <button type="button" class="btn btn-main w-100" onclick="nextSiswa()">
      Lanjut
    </button>
  </div>

  <!-- STEP 2 -->
  <div id="siswaStep2" class="hidden">
    <select name="gender" class="form-select mb-2" required>
      <option value="">Pilih Gender</option>
      <option value="Laki-laki">Laki-laki</option>
      <option value="Perempuan">Perempuan</option>
    </select>

    <input name="kelas" class="form-control mb-3" placeholder="Kelas" required>

    <div class="d-flex gap-2">
      <button type="button" class="btn btn-light w-50" onclick="prevSiswa()">
        Kembali
      </button>
      <button class="btn btn-main w-50">
        Register Siswa
      </button>
    </div>
  </div>
</form>

<!-- FORM MENTOR -->
<form method="post" action="register_process.php" id="formMentor" class="hidden">
  <input type="hidden" name="role" value="pengajar">

  <!-- STEP INDICATOR -->
  <div class="d-flex justify-content-between mb-3 small fw-semibold">
    <span id="step1Label" style="color:var(--green)">1. Data Pribadi</span>
    <span id="step2Label" class="text-muted">2. Data Mengajar</span>
  </div>

  <!-- STEP 1 -->
  <div id="mentorStep1">
    <input name="nama" class="form-control mb-2" placeholder="Nama Lengkap" required>

    <div class="row g-2">
      <div class="col-6">
        <select name="jk" class="form-select mb-2" required>
          <option value="">Gender</option>
          <option value="Laki-laki">Laki-laki</option>
          <option value="Perempuan">Perempuan</option>
        </select>
      </div>
      <div class="col-6">
        <input name="tgl_lahir" type="date" class="form-control mb-2" required>
      </div>
    </div>

    <input name="email" type="email" class="form-control mb-2" placeholder="Email" required>
    <input name="telp" class="form-control mb-3" placeholder="No. Telepon" required>

    <button type="button" class="btn btn-main w-100" onclick="nextStep()">
      Lanjut
    </button>
  </div>

  <!-- STEP 2 -->
  <div id="mentorStep2" class="hidden">
    <input name="mapel" class="form-control mb-2" placeholder="Mata Pelajaran" required>

    <div class="row g-2">
      <div class="col-6">
        <input name="kelas" class="form-control mb-2" placeholder="Kelas" required>
      </div>
      <div class="col-6">
        <input name="jadwal" class="form-control mb-2" placeholder="Jadwal" required>
      </div>
    </div>

    <div class="d-flex gap-2">
      <button type="button" class="btn btn-light w-50" onclick="prevStep()">
        Kembali
      </button>
      <button class="btn btn-main w-50">
        Daftar Mentor
      </button>
    </div>
  </div>
</form>

</div>

<script>
function switchForm(){
  const role = document.getElementById('role').value;
  document.getElementById('formSiswa').classList.toggle('hidden', role !== 'siswa');
  document.getElementById('formMentor').classList.toggle('hidden', role !== 'pengajar');
}
</script>
<script>
function nextStep(){
  document.getElementById('mentorStep1').classList.add('hidden');
  document.getElementById('mentorStep2').classList.remove('hidden');

  document.getElementById('step1Label').classList.add('text-muted');
  document.getElementById('step2Label').classList.remove('text-muted');
  document.getElementById('step2Label').style.color = 'var(--green)';
}

function prevStep(){
  document.getElementById('mentorStep2').classList.add('hidden');
  document.getElementById('mentorStep1').classList.remove('hidden');

  document.getElementById('step2Label').classList.add('text-muted');
  document.getElementById('step1Label').classList.remove('text-muted');
  document.getElementById('step1Label').style.color = 'var(--green)';
}
</script>
<script>
function nextSiswa(){
  const step1 = document.getElementById('siswaStep1');
  if(!step1.querySelector('input:invalid')){
    step1.classList.add('hidden');
    document.getElementById('siswaStep2').classList.remove('hidden');

    document.getElementById('siswaStep1Label').classList.add('text-muted');
    document.getElementById('siswaStep2Label').style.color = 'var(--green)';
  } else {
    step1.reportValidity();
  }
}

function prevSiswa(){
  document.getElementById('siswaStep2').classList.add('hidden');
  document.getElementById('siswaStep1').classList.remove('hidden');

  document.getElementById('siswaStep2Label').classList.add('text-muted');
  document.getElementById('siswaStep1Label').style.color = 'var(--green)';
}
</script>

</body>
</html>
