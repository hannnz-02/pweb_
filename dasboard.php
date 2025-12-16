<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: login_admin.php");
  exit;
}
?>


<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin — Cover Dashboard (Single Page)</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    :root{
      --green: #6D9773;
      --dark:  #0C3B2E;
      --brown: #BB8A52;
      --yellow:#FFBA00;
      --bg: #f6f6f7;
      --muted:#6b7280;
      --glass: rgba(255,255,255,0.85);
    }

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
    .searchbox{ width:48%; min-width:200px }
    .top-actions{ display:flex; gap:8px; align-items:center }
    .top-actions .avatar{ width:40px; height:40px; border-radius:10px; overflow:hidden; }

    .hero { height:140px; border-radius:12px; background: linear-gradient(90deg,var(--green), rgba(109,151,115,0.9)); color:#fff; padding:18px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 12px 30px rgba(12,59,46,0.06) }
    .hero h2{ font-family:'Poppins',sans-serif; margin:0; font-size:20px }
      .body-row { display:flex; gap:14px; height: calc(100% - 140px - 56px); }

    .col-left { flex:1; display:flex; flex-direction:column; gap:14px; min-width:0; }
    .kpi-row{ display:flex; gap:10px; }
    .kpi { flex:1; background:#fff; border-radius:12px; padding:12px; box-shadow:0 8px 22px rgba(12,59,46,0.03); display:flex; flex-direction:column; justify-content:center; gap:6px; min-width:0 }
    .kpi .label{ color:var(--muted); font-size:13px }
    .kpi .value{ font-weight:700; font-size:20px; color:var(--dark) }

    .panel { background:#fff; border-radius:12px; padding:12px; box-shadow:0 8px 22px rgba(12,59,46,0.03); overflow:hidden; display:flex; flex-direction:column }
    .panel .body { overflow:auto; padding-right:8px } 

    .col-right { width:360px; display:flex; flex-direction:column; gap:14px; min-width:0; }
    .widget { background:#fff; border-radius:12px; padding:12px; box-shadow:0 8px 22px rgba(12,59,46,0.03); display:flex; flex-direction:column }
    .widget .header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:8px }

    .course-item{ display:flex; align-items:center; gap:10px; padding:8px; border-radius:10px; background:#fbfbfb; border:1px solid #f2f2f2; margin-bottom:8px }
    .badge-thumb{ width:52px; height:52px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700 }

    .chart-box{ height:110px; border-radius:8px; overflow:hidden }
    .chart-box canvas{ height:100% !important; width:100% !important; display:block; }

    .cover-footer { height:36px; display:flex; align-items:center; justify-content:center; color:var(--muted); font-size:13px }

    @media (max-width:1100px){
      .cover{ width:98vw; height:94vh; padding:12px; flex-direction:column; overflow:auto }
      html,body{ overflow:auto } 
      .sidebar{ width:100%; flex-direction:row; overflow:auto }
      .col-right{ width:100%; order:3 }
      .body-row{ flex-direction:column; height:auto }
    }
    
  </style>
</head>
<body>
  <div class="cover" role="application" aria-label="Admin cover dashboard">
    <div class="sidebar" role="navigation" aria-label="Sidebar">
      <div class="brand">
        <div class="logo">EL</div>
        <div>
          <div style="font-weight:700; font-family:'Poppins',sans-serif;">E-Learning</div>
          <div style="font-size:13px; color:var(--muted)">Admin Panel</div>
        </div>
      </div>
      <div class="navlink active" onclick="location.href='dasboard.php'"><i class="bi bi-gear-fill"></i>Dashboard</div>
      <div class="navlink" onclick="location.href='user.php'"><i class="bi bi-people-fill"></i>Students</div>
      <div class="navlink" onclick="location.href='instructors.php'"><i class="bi bi-people-fill"></i>Instructors</div>
      <div class="navlink" onclick="location.href='subjects.php'"><i class="bi bi-journal-bookmark"></i>Courses</div>
      <div class="navlink" onclick="location.href='classes.php'"><i class="bi bi-calendar2-event"></i>Classes</div>
      <div class="navlink" onclick="location.href='reports.php'"><i class="bi bi-graph-up"></i>Reports</div>
      <div class="navlink" onclick="location.href='setting.php'"><i class="bi bi-gear-fill"></i>Settings</div>
      <div class="sidebar-spacer"></div>
      <div style="margin-top:auto"></div>
      </div>
    <div class="main" role="main">
      <div class="topbar">
        <div class="searchbox">
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input class="form-control" placeholder="Search users, courses, transaksi...">
          </div>
        </div>
        <div class="top-actions">
          <button class="btn btn-light" title="Messages"><i class="bi bi-envelope"></i></button>
          <button class="btn btn-light position-relative" title="Notifications"><i class="bi bi-bell"></i><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background:var(--yellow); color:#0C3B2E">3</span></button>
          <div class="avatar"><img src="https://tse2.mm.bing.net/th/id/OIP.Wb-rNKmADPns03uPU_4nvgHaHa?pid=Api&P=0&h=220" style="width:35px;height: 35px;border-radius:8px" alt="admin"></div>
        </div>
      </div>
      <div class="hero" id="overview">
        <div>
          <div style="font-size:13px; font-weight:600; background:rgba(255,255,255,0.12); padding:6px 10px; border-radius:999px; display:inline-block">Admin • Overview</div>
          <h2 style="margin-top:10px">ADMIN</h2>
          <div style="opacity:0.95; margin-top:6px"></div>
        </div>
      </div>
      <div class="body-row">
        <div class="col-left">
          <div class="kpi-row">
            <div class="kpi">
              <div class="label">Total Students</div>
              <div class="value" id="totalStudents"></div>
            </div>
            <div class="kpi">
              <div class="label">Active Courses</div>
              <div class="value" id="totalCourses"></div>
            </div>
            <div class="kpi">
              <div class="label">Instructors</div>
              <div class="value" id="totalInstructors"></div>
            </div>
            <div class="kpi">
              <div class="label">Total Class</div>
              <div class="value" id="totalClass"></div>
            </div>
          </div>
          <div class="panel">
            <div style="display:flex; justify-content:space-between; align-items:center">
            <div style="font-weight:700">Popular Courses</div>
            <div class="small text-muted">Top 4</div>
          </div>
          <div id="popularCourses" class="body"></div>
        </div>
          <div class="panel" id="users" style="height:100%">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px">
              <div style="font-weight:700; font-family:'Poppins',sans-serif">Recent Students</div>
              <div class="small" style="color:var(--muted)">Active now</div>
            </div>
            <div class="body" style="max-height:230px;">
              <table class="table table-sm mb-0">
                <thead class="table-light small-muted">
                  <tr><th>Name</th><th class="text-end">Progress</th></tr>
                </thead>
                <tbody id="recentStudentsBody"></tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-right">
          <div class="widget" id="activity">
            <div class="header">
              <div style="font-weight:700; font-family:'Poppins',sans-serif">Current Activity</div>
              <div class="small muted">Monthly</div>
            </div>
            <div class="chart-box">
              <canvas id="chartActivity"></canvas>
            </div>
            <div style="margin-top:8px; display:flex; gap:8px;">
              <button class="btn btn-sm" style="background:var(--green);color:#fff;border:0">View Details</button>
              <button class="btn btn-sm" style="background:var(--brown);color:#fff;border:0">Export</button>
            </div>
          </div>
          <div class="widget" id="instructors">
            <div class="header">
              <div style="font-weight:700; font-family:'Poppins',sans-serif">Top Instructors</div>
              <div class="small muted">Performance</div>
            </div>
            <div style="display:flex;flex-direction:column;gap:8px">
              <div style="display:flex;gap:8px;align-items:center">
                <img src="https://i.pinimg.com/originals/33/f8/71/33f871978f930dd0c29214c7011bb378.jpg" style="width:40px;height:40px;border-radius:8px">
                <div style="flex:1">
                  <div style="font-weight:600">Faisal</div>
                  <div class="small-muted">UI • 5 courses</div>
                </div>
                <div style="font-weight:700;color:var(--green)">4.9</div>
              </div>
              <div style="display:flex;gap:8px;align-items:center">
                <img src="https://i.pinimg.com/736x/87/9e/9e/879e9ebfc5b00255284dc1a855d39c4f.jpg" style="width:40px;height:40px;border-radius:8px">
                <div style="flex:1">
                  <div style="font-weight:600">Putri</div>
                  <div class="small-muted">Design • 6 courses</div>
                </div>
                <div style="font-weight:700;color:var(--green)">4.8</div>
              </div>
            </div>
          </div>
      <div class="cover-footer">© 2025 E-Learn • Admin Cover Dashboard</div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
async function fetchTotal(url, elementId) {
  try {
    const res = await fetch(url);
    const data = await res.json();
    const el = document.getElementById(elementId);

    if (el && data.total !== undefined) {
      el.innerText = data.total.toLocaleString('id-ID');
    }
  } catch (err) {
    console.error(err);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  fetchTotal('get_total.php', 'totalStudents');
  fetchTotal('get_total_courses.php', 'totalCourses');
  fetchTotal('get_total_instructors.php', 'totalInstructors');
  fetchTotal('get_total_classes.php', 'totalClass');
});
</script>

</body>
</html>