<?php
// users.php (diperbaiki & disambungkan ke tabel pengajar di DB `users`)
// Letakkan file ini di folder htdocs (atau www) XAMPP/LAMP/MAMP kamu
// Konfigurasi database: sesuaikan nilai di bawah ini
$DB_HOST = 'sql110.infinityfree.com';
$DB_NAME = 'if0_40695714_bimbel_db';
$DB_USER = 'if0_40695714';
$DB_PASS = 'E30TiU75HGx';
$charset = 'utf8mb4';

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    die("Koneksi DB gagal: " . $e->getMessage());
}


$mapelList = $pdo->query("
  SELECT id, name
  FROM subjects
  ORDER BY name
")->fetchAll();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
    $id = $_POST['hapus_id'];

    if (ctype_digit($id)) {
        $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
        $stmt->execute([$id]);
    }

    header("Location: classes.php");
    exit;
}


if (isset($_POST['edit_kelas'])) {
    $stmt = $pdo->prepare("
      UPDATE classes SET
        subject_id=?,
        mentor=?,
        description=?,
        start_date=?,
        end_date=?,
        time=?
      WHERE id=?
    ");
    $stmt->execute([
      $_POST['subject_id'],
      $_POST['mentor'],
      $_POST['description'],
      $_POST['start_date'],
      $_POST['end_date'],
      $_POST['time'],
      $_POST['id']
    ]);
    header("Location: classes.php");
    exit;
}


if (isset($_POST['tambah_kelas'])) {
    $stmt = $pdo->prepare("
      INSERT INTO classes (subject_id, mentor, description, start_date, end_date, time)
      VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
      $_POST['subject_id'],
      $_POST['mentor'],
      $_POST['description'],
      $_POST['start_date'],
      $_POST['end_date'],
      $_POST['time']
    ]);
    header("Location: classes.php");
    exit;
}



// Ambil data mata_pelajaran + nama pengajar dari database users.pengajar

$sql = "
SELECT
  c.id,
  c.subject_id,
  s.name AS nama_mapel,
  c.mentor,
  c.description,
  c.start_date,
  c.end_date,
  c.time
FROM classes c
JOIN subjects s ON s.id = c.subject_id
ORDER BY c.id DESC
";
$fetchError = null;
$courses = [];

try {
    $stmt = $pdo->query($sql);
    $courses = $stmt->fetchAll();
} catch (Exception $e) {
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
    table.users-table th, table.users-table td{ padding:8px; border-bottom:1px solid #eee; text-align:center }
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
      <div class="navlink" onclick="location.href='subjects.php'"><i class="bi bi-journal-bookmark"></i>Courses</div>
      <div class="navlink active" onclick="location.href='classes.php'"><i class="bi bi-calendar2-event"></i>Classes</div>
      <div class="navlink" onclick="location.href='reports.php'"><i class="bi bi-graph-up"></i>Reports</div>
      <div class="navlink" onclick="location.href='setting.php'"><i class="bi bi-gear-fill"></i>Settings</div>
    </div>

    <!-- MAIN -->
    <div class="main">
      <div class="topbar">
        <div class="searchbox"><input class="form-control" id="searchBox" placeholder="Cari course..." oninput="filterTable(this.value)"></div>
        <div class="top-actions">
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg"></i> Tambah
          </button>
          <button class="btn btn-light" onclick="location.reload()">Refresh</button>
        </div>
      </div>

      <div class="hero">
        <div>
          <h2 style="margin:0; font-family:'Poppins',sans-serif">Clases</h2>
          <div style="opacity:0.9">Kelola data kelas</div>
        </div>
        <div style="text-align:right">
          <div style="font-weight:700">Total: <?php echo count($courses); ?></div>
          
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
  <th>Mapel</th>
  <th>Mentor</th>
  <th>Waktu</th>
  <th>Periode</th>
  <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php foreach ($courses as $r): ?>
<tr>
  <td><?= e($r['id']) ?></td>
  <td><?= e($r['nama_mapel']) ?></td>
  <td><?= e($r['mentor']) ?></td>
  <td><?= e($r['time']) ?></td>
  <td><?= e($r['start_date']) ?> s/d <?= e($r['end_date']) ?></td>

  <td>
    <button class="btn btn-sm btn-warning"
  data-bs-toggle="modal"
  data-bs-target="#modalEdit"
  data-id="<?= e($r['id']) ?>"
  data-subject="<?= e($r['subject_id']) ?>"
  data-mentor="<?= e($r['mentor']) ?>"
  data-desc="<?= e($r['description']) ?>"
  data-start="<?= e($r['start_date']) ?>"
  data-end="<?= e($r['end_date']) ?>"
  data-time="<?= e($r['time']) ?>">
  <i class="bi bi-pencil"></i>
</button>


    <button class="btn btn-sm btn-danger"
      data-bs-toggle="modal"
      data-bs-target="#modalHapus"
      data-id="<?= e($r['id']) ?>">
      <i class="bi bi-trash"></i>
    </button>
  </td>
</tr>
<?php endforeach; ?>
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
<div class="modal fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post">
        <input type="hidden" name="hapus_id" id="hapus_id">

        <div class="modal-header">
          <h5 class="modal-title text-danger">Hapus Kelas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          Yakin ingin menghapus kelas ini?
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
document.getElementById('modalHapus')
  .addEventListener('show.bs.modal', function (e) {
    document.getElementById('hapus_id').value =
      e.relatedTarget.dataset.id;
});
</script>
<div class="modal fade" id="modalEdit">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post">
        <input type="hidden" name="edit_kelas" value="1">
        <input type="hidden" name="id" id="edit_id">

        <!-- ✅ HEADER -->
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditTitle">Edit Classes</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- BODY -->
        <div class="modal-body">
          <label>Mapel</label>
          <select name="subject_id" id="edit_subject" class="form-control mb-2" required>
            <?php foreach($mapelList as $m): ?>
              <option value="<?= $m['id'] ?>"><?= e($m['name']) ?></option>
            <?php endforeach; ?>
          </select>

          <label>Mentor</label>
          <input name="mentor" id="edit_mentor" class="form-control mb-2" required>

          <label>Deskripsi</label>
          <input name="description" id="edit_desc" class="form-control mb-2">

          <label>Mulai</label>
          <input type="date" name="start_date" id="edit_start" class="form-control mb-2">

          <label>Selesai</label>
          <input type="date" name="end_date" id="edit_end" class="form-control mb-2">

          <label>Jam</label>
          <input name="time" id="edit_time" class="form-control mb-2">
        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-warning">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('modalEdit')
  .addEventListener('show.bs.modal', e => {
    const b = e.relatedTarget;

    // ✅ SET JUDUL
    document.getElementById('modalEditTitle').innerText = 'Edit Classes';

    edit_id.value      = b.dataset.id;
    edit_subject.value = b.dataset.subject;
    edit_mentor.value  = b.dataset.mentor;
    edit_desc.value    = b.dataset.desc;
    edit_start.value   = b.dataset.start;
    edit_end.value     = b.dataset.end;
    edit_time.value    = b.dataset.time;
});
</script>
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form method="post">
        <input type="hidden" name="tambah_kelas" value="1">

        <!-- HEADER -->
        <div class="modal-header">
          <h5 class="modal-title">Tambah Classes</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- BODY -->
        <div class="modal-body">

          <label>Mapel</label>
          <select name="subject_id" class="form-control mb-2" required>
            <?php foreach($mapelList as $m): ?>
              <option value="<?= $m['id'] ?>"><?= e($m['name']) ?></option>
            <?php endforeach; ?>
          </select>

          <label>Mentor</label>
          <input name="mentor" class="form-control mb-2" required>

          <label>Deskripsi</label>
          <input name="description" class="form-control mb-2">

          <label>Mulai</label>
          <input type="date" name="start_date" class="form-control mb-2">

          <label>Selesai</label>
          <input type="date" name="end_date" class="form-control mb-2">

          <label>Jam</label>
          <input name="time" class="form-control mb-2">

        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>

      </form>

    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
