<?php
// ================= KONEKSI DATABASE =================
$DB_HOST = 'localhost';
$DB_NAME = 'users';
$DB_USER = 'root';
$DB_PASS = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// ================= SIMPAN SETTING =================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        UPDATE setting 
        SET nama_aplikasi = ?, deskripsi = ?, tahun_ajaran = ?
        WHERE id = 1
    ");
    $stmt->execute([
        $_POST['nama_aplikasi'],
        $_POST['deskripsi'],
        $_POST['tahun_ajaran']
    ]);

    header("Location: settings.php");
    exit;
}

$setting = $pdo->query("SELECT * FROM setting WHERE id = 1")->fetch();

if (!$setting) {
    $pdo->exec("
        INSERT INTO setting (id, nama_aplikasi, deskripsi, tahun_ajaran)
        VALUES (1, 'Aplikasi Baru', 'Deskripsi aplikasi', '2024/2025')
    ");
    $setting = $pdo->query("SELECT * FROM setting WHERE id = 1")->fetch();
}
function e($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin — Courses</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root{ --green: #6D9773; --dark:  #0C3B2E; --brown: #BB8A52; --yellow:#FFBA00; --bg: #f6f6f7; --muted:#6b7280; --glass: rgba(255,255,255,0.85); }
    html,body{height:100%;margin:0;overflow:hidden;background:var(--dark);font-family:'Roboto',system-ui, -apple-system, 'Segoe UI', Roboto, Arial;}
    .cover { width:95vw; height:96vh; margin:2vh auto; background:var(--glass); border-radius:18px; padding:30px; box-shadow:0 18px 50px rgba(12,59,46,0.08); display:flex; gap:20px; box-sizing:border-box; }
    .sidebar { width:240px; display:flex; flex-direction:column; gap:14px; }
    .brand{ display:flex; align-items:center; gap:10px; }
    .brand .logo{ width:44px; height:44px; border-radius:10px; background:linear-gradient(135deg,var(--green),var(--brown)); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700 }
    .navlink{ display:flex; gap:12px; align-items:center; padding:10px 12px; border-radius:10px; color:var(--muted); cursor:pointer; user-select:none }
    .navlink i{ color:var(--green); font-size:18px }
    .navlink.active{ background: linear-gradient(90deg, rgba(109,151,115,0.10), rgba(187,138,82,0.03)); color:var(--dark); font-weight:600; }
    .sidebar .quick-card{ background:var(--bg); border-radius:12px; padding:10px; box-shadow:0 8px 26px rgba(12,59,46,0.03); text-align:center }
    .main { flex:1; display:flex; flex-direction:column; gap:14px; min-width:0; }
    .topbar { display:flex; justify-content:space-between; align-items:center; gap:12px; }
    .hero { height:140px; border-radius:12px; background: linear-gradient(90deg,var(--green), rgba(109,151,115,0.9)); color:#fff; padding:18px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 12px 30px rgba(12,59,46,0.06) }
    .body-row { display:flex; gap:14px; height: calc(100% - 140px - 56px); }
    .col-left { flex:1; display:flex; flex-direction:column; gap:14px; min-width:0; }
    .panel { background:#fff; border-radius:12px; padding:12px; box-shadow:0 8px 22px rgba(12,59,46,0.03); overflow:hidden; display:flex; flex-direction:column }
    .panel .body { overflow:auto; padding-right:8px }
    .col-right { width:360px; display:flex; flex-direction:column; gap:14px; min-width:0; }
    table.users-table{ width:100%; border-collapse:collapse }
    table.users-table th, table.users-table td{ padding:8px; border-bottom:1px solid #eee; text-align:left }
    @media (max-width:1100px){ .cover{ width:98vw; height:94vh; padding:12px; flex-direction:column; overflow:auto } html,body{ overflow:auto } .sidebar{ width:100%; flex-direction:row; overflow:auto } .col-right{ width:100%; order:3 } .body-row{ flex-direction:column; height:auto } }
  </style>
</head>
<body>
  <div class="cover" role="application" aria-label="Admin cover dashboard">

    <!-- SIDEBAR -->
    <div class="sidebar" role="navigation" aria-label="Sidebar">
      <div class="brand">
        <div class="logo">EL</div>
        <div>
          <div style="font-weight:700; font-family:'Poppins',sans-serif">E-Learning</div>
          <div style="font-size:13px; color:var(--muted)">Admin Panel</div>
        </div>
      </div>

      <div class="navlink" onclick="location.href='dasboard.php'"><i class="bi bi-gear-fill"></i>Dashboard</div>
      <div class="navlink" onclick="location.href='user.php'"><i class="bi bi-people-fill"></i>Students</div>
      <div class="navlink" onclick="location.href='instructors.php'"><i class="bi bi-people-fill"></i>Instructors</div>
      <div class="navlink" onclick="location.href='courses.php'"><i class="bi bi-journal-bookmark"></i>Courses</div>
      <div class="navlink" onclick="location.href='classes.php'"><i class="bi bi-calendar2-event"></i>Classes</div>
      <div class="navlink" onclick="location.href='reports.php'"><i class="bi bi-graph-up"></i>Reports</div>
      <div class="navlink active" onclick="location.href='setting.php'"><i class="bi bi-gear-fill"></i>Settings</div>
    </div>


    
    <div class="main">
      <div class="topbar">
        <div class="searchbox"><input class="form-control" id="searchBox" placeholder="Cari course..." oninput="filterTable(this.value)"></div>
        <div class="top-actions">
          <button class="btn btn-light" onclick="location.reload()">Refresh</button>
        </div>
      </div>

      
      <div class="hero">
  <div>
    <h2 style="margin:0; font-family:'Poppins',sans-serif">Settings</h2>
    <div style="opacity:0.9">Pembaruan Data Sistem</div>
  </div>
</div>
<div class="body-row">
  <div class="col-left">
    <div class="row g-3">
<!-- MAIN -->

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nama Aplikasi</label>
        <input class="form-control" name="nama_aplikasi"
               value="<?= e($setting['nama_aplikasi']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="deskripsi" rows="3" required><?= e($setting['deskripsi']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Tahun Ajaran</label>
        <input class="form-control" name="tahun_ajaran"
               value="<?= e($setting['tahun_ajaran']) ?>" required>
      </div>

      <button class="btn btn-success">
        <i class="bi bi-save"></i> Simpan Perubahan
      </button>
    </form>
  </div>
</div>

</div>
</body>
</html>
