<?php
$DB_HOST = 'localhost';
$DB_NAME = 'bimbel_db';
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
    die($e->getMessage());
}

/* =========================
   TAMBAH DATA
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_user'])) {

    $data = [
        $_POST['nama'] ?? null,
        $_POST['jk'] ?? null,
        $_POST['email'] ?? null,
        $_POST['telp'] ?? null,
        $_POST['tgl_lahir'] ?? null,
        $_POST['jadwal'] ?? null,
        $_POST['mapel'] ?? null,
        $_POST['kelas'] ?? null
    ];

    // CEK ADA YANG KOSONG ATAU TIDAK
    if (in_array(null, $data, true) || in_array('', $data, true)) {
    header("Location: instructors.php?error=kosong");
    exit;
}


    $stmt = $pdo->prepare("
        INSERT INTO mentor
        (nama, jk, email, telp, tgl_lahir, jadwal, mapel, kelas)
        VALUES (?,?,?,?,?,?,?,?)
    ");

    $stmt->execute($data);

    header("Location: instructors.php");
    exit;
}

/* =========================
   HAPUS DATA
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {

    if (ctype_digit($_POST['hapus_id'])) {
        $stmt = $pdo->prepare("DELETE FROM mentor WHERE id = ?");
        $stmt->execute([$_POST['hapus_id']]);
    }

    header("Location: instructors.php");
    exit;
}

/* =========================
   EDIT DATA
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {

    $stmt = $pdo->prepare("
        UPDATE mentor SET
            nama = ?,
            jk = ?,
            tgl_lahir = ?,
            mapel = ?,
            kelas = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $_POST['nama'],
        $_POST['jk'],
        $_POST['tgl_lahir'],
        $_POST['mapel'],
        $_POST['kelas'],
        $_POST['id']
    ]);

    header("Location: instructors.php");
    exit;
}

/* =========================
   AMBIL DATA
========================= */
$users = $pdo->query("
    SELECT 
        id,
        nama,
        jk,
        email,
        telp,
        tgl_lahir,
        jadwal,
        mapel,
        kelas
    FROM mentor
    ORDER BY id DESC
")->fetchAll();


function e($s){
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
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
      <div class="navlink" onclick="location.href='user.php'"><i class="bi bi-people-fill"></i>Students</div>
      <div class="navlink active" onclick="location.href='instructors.php'"><i class="bi bi-people-fill"></i>Instructors</div>
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
          <h2 style="margin:0; font-family:'Poppins',sans-serif">Instructors</h2>
          <div style="opacity:0.9">Kelola akun pengajar</div>
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
                <!-- ALERT -->
<?php if (isset($_GET['error']) && $_GET['error'] === 'terhubung'): ?>
  <div class="alert alert-warning mx-3">
    ❗ Pengajar tidak bisa dihapus karena masih terhubung dengan mata pelajaran.
    <br>Silakan hapus atau pindahkan mata pelajarannya terlebih dahulu.
  </div>
<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] === 'hapus'): ?>
  <div class="alert alert-success mx-3">
    ✅ Pengajar berhasil dihapus.
  </div>
<?php endif; ?>

<!-- AUTO CLEAR URL -->
<?php if (isset($_GET['error']) || isset($_GET['success'])): ?>
<script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.pathname);
  }
</script>
<?php endif; ?>


                <?php if (!empty($fetchError)): ?>
                  <div class="alert alert-warning">Terjadi error saat memuat data: <?php echo e($fetchError); ?></div>
                <?php endif; ?>

                <table id="usersTable" class="table table-striped users-table">
                  <thead>
                  <tr>
  <th>ID</th>
  <th>Nama</th>
  <th>JK</th>
  <th>Email</th>
  <th>Telp</th>
  <th>Tgl Lahir</th>
  <th>Jadwal</th>
  <th>Mapel</th>
  <th>Kelas</th>
  <th>Aksi</th>
</tr>

                  </thead>

                <tbody>
<?php if (empty($users)): ?>
  <tr>
    <td colspan="10" class="text-center">Tidak ada data.</td>
  </tr>
<?php else: ?>
  <?php $no = 1; ?>
  <?php foreach ($users as $r): ?>
    <tr>
      <td><?= e($r['id']) ?></td>
<td><?= e($r['nama']) ?></td>
<td><?= e($r['jk']) ?></td>
<td><?= e($r['email']) ?></td>
<td><?= e($r['telp']) ?></td>
<td><?= e($r['tgl_lahir']) ?></td>
<td><?= e($r['jadwal']) ?></td>
<td><?= e($r['mapel']) ?></td>
<td><?= e($r['kelas']) ?></td>
<td>
  <div class="d-flex gap-1">

    <!-- EDIT -->
    <button 
  class="btn btn-sm btn-warning"
  data-bs-toggle="modal"
  data-bs-target="#modalEdit"
  data-id="<?= e($r['id']) ?>"
  data-nama="<?= e($r['nama']) ?>"
  data-jk="<?= e($r['jk']) ?>"
  data-tgl="<?= e($r['tgl_lahir']) ?>"
  data-email="<?= e($r['email']) ?>"
  data-telp="<?= e($r['telp']) ?>"
  data-jadwal="<?= e($r['jadwal']) ?>"
  data-mapel="<?= e($r['mapel']) ?>"
  data-kelas="<?= e($r['kelas']) ?>"
>
  <i class="bi bi-pencil-square"></i>
</button>


    <!-- HAPUS -->
    <button 
      class="btn btn-sm btn-danger"
      data-bs-toggle="modal"
      data-bs-target="#modalHapus"
      data-id="<?= e($r['id']) ?>"
    >
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
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form method="post" action="instructors.php">
        <input type="hidden" name="tambah_user" value="1">


        <div class="modal-header">
          <h5 class="modal-title">Tambah User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

  <label>Nama</label>
  <input name="nama" class="form-control mb-2" required>

  <label>Jenis Kelamin</label>
  <select name="jk" class="form-control mb-2" required>
    <option value="">-- Pilih --</option>
    <option value="L">Laki-laki</option>
    <option value="P">Perempuan</option>
  </select>

  <label>Tanggal Lahir</label>
  <input type="date" name="tgl_lahir" class="form-control mb-2" required>

  <label>Email</label>
  <input name="email" class="form-control mb-2" required>

  <label>No Telp</label>
  <input name="telp" class="form-control mb-2" required>

  <label>Jadwal</label>
  <input name="jadwal" class="form-control mb-2" required>

  <label>Mata Pelajaran</label>
  <textarea name="mapel" class="form-control mb-2"></textarea>

  <label>Kelas</label>
  <input name="kelas" class="form-control mb-2">

</div>



        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-save"></i> Simpan
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form method="post">
        <input type="hidden" name="edit_user" value="1">
        <input type="hidden" name="id" id="edit_id">

        <div class="modal-header">
          <h5 class="modal-title">Edit Instructors</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- ✅ INI YANG TADI KURANG -->
        <div class="modal-body">

  <label>Nama</label>
  <input name="nama" id="edit_nama" class="form-control mb-2">

  <label>Jenis Kelamin</label>
  <select name="jk" id="edit_jk" class="form-control mb-2">
  <option value="">-- Pilih --</option>  
  <option value="L">Laki-laki</option>
    <option value="P">Perempuan</option>
  </select>

  <label>Tanggal Lahir</label>
  <input type="date" name="tgl_lahir" id="edit_tgl" class="form-control mb-2">

  <label>Email</label>
  <input name="email" id="edit_email" class="form-control mb-2">

  <label>No Telp</label>
  <input name="telp" id="edit_telp" class="form-control mb-2">

  <label>Jadwal</label>
  <input name="jadwal" id="edit_jadwal" class="form-control mb-2">

  <label>Mata Pelajaran</label>
  <textarea name="mapel" id="edit_mapel" class="form-control mb-2"></textarea>

  <label>Kelas</label>
  <input name="kelas" id="edit_kelas" class="form-control mb-2">

</div>


        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">
            <i class="bi bi-save"></i> Update
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

  // MODAL HAPUS
  const modalHapus = document.getElementById('modalHapus');
  modalHapus.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    document.getElementById('hapus_id').value = btn.dataset.id;
  });

  // MODAL EDIT
  const modalEdit = document.getElementById('modalEdit');
  modalEdit.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;

    document.getElementById('edit_id').value     = btn.dataset.id;
    document.getElementById('edit_nama').value   = btn.dataset.nama;
    document.getElementById('edit_jk').value     = btn.dataset.jk;
    document.getElementById('edit_email').value  = btn.dataset.email;
    document.getElementById('edit_telp').value   = btn.dataset.telp;
    document.getElementById('edit_jadwal').value = btn.dataset.jadwal;
    document.getElementById('edit_mapel').value  = btn.dataset.mapel;
    document.getElementById('edit_kelas').value  = btn.dataset.kelas;

    // FIX FORMAT TANGGAL
    let tgl = btn.dataset.tgl;
    if (tgl.includes('/')) {
      const p = tgl.split('/');
      tgl = `${p[2]}-${p[1]}-${p[0]}`;
    }
    document.getElementById('edit_tgl').value = tgl;
  });

});
</script>



</body>
</html>
