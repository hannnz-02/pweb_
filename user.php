<?php
// users.php (diperbaiki)
// Letakkan file ini di folder htdocs (atau www) XAMPP/LAMP/MAMP kamu
// Konfigurasi database: sesuaikan nilai di bawah ini
$DB_HOST = 'localhost';
$DB_NAME = 'bimbel_db';    // nama database yang kamu buat di phpMyAdmin
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
    // Jika gagal koneksi, tampilkan pesan yang informatif
    echo '<h2>Koneksi ke database gagal</h2>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    exit;
}
// === TAMBAH USER ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_user'])) {

    $name     = $_POST['name'];
    $gender   = $_POST['gender'];
    $username = $_POST['username'];
    $kelas    = $_POST['kelas'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare(
        "INSERT INTO users 
        (name, gender, username, kelas, email, password)
        VALUES (?,?,?,?,?,?)"
    );

    $stmt->execute([
        $name,
        $gender,
        $username,
        $kelas,
        $email,
        password_hash($password, PASSWORD_DEFAULT)
    ]);

    header("Location: users.php");
    exit;
}

// === HAPUS USER ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_POST['hapus_id']]);

    header("Location: users.php");
    exit;
}


// === EDIT USER ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {

    $id       = $_POST['id'];
    $name     = $_POST['name'];
    $gender   = $_POST['gender'];
    $username = $_POST['username'];
    $kelas    = $_POST['kelas'];
    $email    = $_POST['email'];

    $stmt = $pdo->prepare(
        "UPDATE users 
         SET name=?, gender=?, username=?, kelas=?, email=? 
         WHERE id=?"
    );

    $stmt->execute([
        $name,
        $gender,
        $username,
        $kelas,
        $email,
        $id
    ]);

    header("Location: users.php");
    exit;
}


// Ambil data siswa (tabel 'siswa')
$sql = "SELECT id, name, gender, username, kelas, email, total_points, created_at FROM users ORDER BY id DESC";



$users = [];
try {
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll();
} catch (Exception $e) {
    // Jika query gagal, tetap lanjut dengan $users sebagai array kosong
    $users = [];
    $fetchError = $e->getMessage();
}

// Helper untuk escape output
function e($s){
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin — Users</title>

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
      <div class="navlink active" onclick="location.href='user.php'"><i class="bi bi-people-fill"></i>Students</div>
      <div class="navlink" onclick="location.href='instructors.php'"><i class="bi bi-people-fill"></i>Instructors</div>
      <div class="navlink" onclick="location.href='courses.php'"><i class="bi bi-journal-bookmark"></i>Courses</div>
      <div class="navlink" onclick="location.href='classes.php'"><i class="bi bi-calendar2-event"></i>Classes</div>
      <div class="navlink" onclick="location.href='reports.php'"><i class="bi bi-graph-up"></i>Reports</div>
      <div class="navlink" onclick="location.href='setting.php'"><i class="bi bi-gear-fill"></i>Settings</div>
    </div>

    <!-- MAIN -->
    <div class="main">
      <div class="topbar">
        <div class="searchbox"><input class="form-control" id="searchBox" placeholder="Cari user..." oninput="filterTable(this.value)"></div>
        <div class="top-actions d-flex gap-2">
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg"></i> Tambah
          </button>
          <button class="btn btn-light" onclick="location.reload()">Refresh</button>
        </div>
      </div>

      <div class="hero">
        <div>
          <h2 style="margin:0; font-family:'Poppins',sans-serif">Users</h2>
          <div style="opacity:0.9">Kelola akun pengguna</div>
        </div>
        <div style="text-align:right">
          <div style="font-weight:700">Total: <?php echo count($users); ?></div>
          <div style="font-size:13px">Database: <?php echo e($DB_NAME); ?></div>
        </div>
      </div>

      <div class="body-row">
        <div class="col-left">
          <div class="panel">
            <div class="body">
                <?php if (!empty($fetchError)): ?>
                  <div class="alert alert-warning">Terjadi error saat memuat data: <?php echo e($fetchError); ?></div>
                <?php endif; ?>

                <table id="usersTable" class="table table-striped users-table">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Gender</th>
                    <th>Username</th>
                    <th>Kelas</th>
                    <th>Email</th>
                    <th>Total Poin</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>

                <tbody>
<?php if (empty($users)): ?>
  <tr>
    <td colspan="7" class="text-center">Tidak ada data.</td>
  </tr>
<?php else: ?>
  <?php $no = 1; ?>
  <?php foreach ($users as $r): ?>
    <tr>
      <!-- NOMOR URUT TAMPILAN -->
      <td><?= e($r['id']) ?></td>
      <td><?= e($r['name']) ?></td>
      <td><?= e($r['gender']) ?></td>
      <td><?= e($r['username']) ?></td>
      <td><?= e($r['kelas']) ?></td>
      <td><?= e($r['email']) ?></td>
      <td><?= e($r['total_points']) ?></td>


      <!-- AKSI (INI YANG TADI HILANG) -->
      <td>
        <div class="d-flex gap-1">
          <!-- EDIT -->
        <button class="btn btn-sm btn-warning"
          data-bs-toggle="modal"
          data-bs-target="#modalEdit"
          data-id="<?= e($r['id']) ?>"
          data-name="<?= e($r['name']) ?>"
          data-gender="<?= e($r['gender']) ?>"
          data-username="<?= e($r['username']) ?>"
          data-kelas="<?= e($r['kelas']) ?>"
          data-email="<?= e($r['email']) ?>">
          <i class="bi bi-pencil-square"></i>
        </button>



          <!-- HAPUS -->
          <button class="btn btn-sm btn-danger"
                  data-bs-toggle="modal"
                  data-bs-target="#modalHapus"
                  data-id="<?= e($r['id']) ?>">
            <i class="bi bi-trash"></i>
          </button>
        </div>
      </td>
    </tr>
  <?php endforeach; ?>
<?php endif; ?>

</tbody>

                </table>
            </div>
          </div>
        </div>
    
    </div>

  </div>

<script>
function filterTable(q){
  q = q.toLowerCase();
  const rows = document.querySelectorAll('#usersTable tbody tr');
  rows.forEach(r=>{
    const text = r.innerText.toLowerCase();
    r.style.display = text.includes(q) ? '' : 'none';
  });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<div class="modal fade" id="modalTambah">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <input type="hidden" name="tambah_user" value="1">

        <div class="modal-body">
          <input name="nama" class="form-control mb-2" placeholder="Nama" required>
          <input name="email" type="email" class="form-control mb-2" placeholder="Email" required>
          <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>

          <select name="role" class="form-control">
            <option value="siswa">Siswa</option>
          </select>
        </div>

        <div class="modal-footer">
          <button class="btn btn-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form method="post">
        <input type="hidden" name="hapus_id" id="hapus_id">

        <div class="modal-header">
          <h5 class="modal-title text-danger">Hapus User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          Yakin ingin menghapus user ini?
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Hapus</button>
        </div>

      </form>

    </div>
  </div>
</div>
<script>
const modalHapus = document.getElementById('modalHapus');
modalHapus.addEventListener('show.bs.modal', function (event) {
  const button = event.relatedTarget;
  document.getElementById('hapus_id').value =
    button.getAttribute('data-id');
});
</script>
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form method="post">
        <input type="hidden" name="edit_user" value="1">
        <input type="hidden" name="id" id="edit_id">

        <div class="modal-header">
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input name="nama" id="edit_nama" class="form-control mb-2" required>
          <input name="email" id="edit_email" type="email" class="form-control mb-2" required>

          <select name="role" id="edit_role" class="form-control">
            <option value="siswa">Siswa</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-warning">Update</button>
        </div>

      </form>

    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modalEdit = document.getElementById('modalEdit');

  modalEdit.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;

    document.getElementById('edit_id').value       = btn.dataset.id;
    document.getElementById('edit_name').value     = btn.dataset.name;
    document.getElementById('edit_gender').value   = btn.dataset.gender;
    document.getElementById('edit_username').value = btn.dataset.username;
    document.getElementById('edit_kelas').value    = btn.dataset.kelas;
    document.getElementById('edit_email').value    = btn.dataset.email;
  });
});
</script>
</body>
</html>
