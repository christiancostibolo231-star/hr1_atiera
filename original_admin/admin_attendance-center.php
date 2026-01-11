<?php
date_default_timezone_set('Asia/Manila');
$admin_name = "James Kneechtel DL. Sabandal";
$current_month = date('Y-m');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Attendance Center — Atiera Admin Panel</title>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    :root {
      --gold: #FFD700;
      --blue: #004AAD;
      --dark-blue: #1f2d3d;
      --white: #FFFFFF;
      --light-gray: #f8f9fa;
      --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      --brand-red: #b91c1c;
      --brand-red-dark: #7f1d1d;
      --brand-green: #0f766e;
      --muted: #6b7280;
      --bg: #f8fafc;
      --card: #ffffff;
      --glass: rgba(0,0,0,0.03);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: var(--bg);
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
      color: #0f172a;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
      background: var(--dark-blue);
      color: var(--white);
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      transition: var(--transition);
      z-index: 1000;
      box-shadow: 4px 0 25px rgba(0, 0, 0, 0.35);
      width: 240px;
      display: flex;
      flex-direction: column;
    }
    .sidebar.collapsed { 
      width: 60px; 
    }

    .logo-header {
      padding: 18px;
      display: flex;
      align-items: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      cursor: pointer;
      justify-content: flex-start;
    }
    .sidebar.collapsed .logo-header { 
      justify-content: center; 
      padding: 18px 0; 
    }

    .logo-icon {
      width: 36px; height: 36px;
      background: linear-gradient(45deg, var(--blue), var(--gold));
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      color: var(--white);
      font-weight: 700;
      font-size: 16px;
      flex-shrink: 0;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    }
    .logo-text {
      font-family: 'Playfair Display', serif;
      font-size: 19px;
      font-weight: 700;
      margin-left: 12px;
      white-space: nowrap;
      transition: opacity 0.3s ease;
    }
    .sidebar.collapsed .logo-text { 
      display: none; 
    }

    .nav-menu {
      list-style: none;
      padding: 20px 0 15px;
      flex: 1;
      overflow-y: auto;
    }
    .nav-item { margin: 0 12px 6px; }

    .nav-link {
      display: flex;
      align-items: center;
      padding: 11px 18px;
      color: rgba(255, 255, 255, 0.92);
      text-decoration: none;
      border-radius: 8px;
      transition: var(--transition);
      font-size: 14.5px;
      cursor: pointer;
    }
    .nav-link:hover, .nav-link.active {
      background: rgba(255, 255, 255, 0.12);
      transform: translateX(3px);
    }
    .nav-icon {
      width: 26px;
      text-align: center;
      margin-right: 14px;
      font-size: 17px;
      color: var(--gold);
    }

/* ===== FIXED DOWN ARROW FOR ALL DROPDOWNS ===== */
.nav-link.has-dropdown::after {
  content: '\f107'; /* Down chevron (▼) — FIXED */
  font-family: 'Font Awesome 6 Free';
  font-weight: 900;
  margin-left: auto;
  font-size: 12px;
  color: rgba(255, 255, 255, 0.7);
  /* Walang transition, walang rotate — static lang */
}
    
    .submenu {
      list-style: none;
      padding-left: 40px;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease, padding 0.3s ease;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 0 0 8px 8px;
      margin-top: 4px;
    }
    .nav-item.active > .submenu {
      max-height: 300px;
      padding: 8px 0 12px;
    }
    .submenu li a {
      display: block;
      padding: 8px 18px;
      color: rgba(255, 255, 255, 0.85);
      text-decoration: none;
      font-size: 13.5px;
      transition: var(--transition);
      border-radius: 6px;
      margin: 0 10px;
    }
    .submenu li a:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateX(3px);
    }

    /* ===== COLLAPSED SIDEBAR CLEANUP ===== */
    .sidebar.collapsed .logo-text,
    .sidebar.collapsed .nav-menu {
      display: none !important;
    }

    .nav-menu::-webkit-scrollbar { width: 6px; }
    .nav-menu::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.25);
      border-radius: 3px;
    }

    /* ===== TOP NAVBAR ===== */
    .top-navbar {
      background: var(--white);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      height: 70px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 0 50px;
      gap: 42px;
      position: fixed;
      top: 0;
      left: 240px;
      right: 0;
      z-index: 1001;
      transition: var(--transition);
    }
    .sidebar.collapsed ~ .top-navbar { left: 60px; }

    .current-datetime {
      background: var(--light-gray);
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: 500;
      color: var(--dark-blue);
    }

    .profile-container {
      display: flex;
      align-items: center;
      gap: 12px;
      color: var(--dark-blue);
      position: relative;
    }
    .profile-pic {
      width: 42px; height: 42px;
      border-radius: 50%;
      overflow: hidden;
      border: 2px solid var(--gold);
    }
    .profile-pic img { width: 100%; height: 100%; object-fit: cover; }
    .profile-name {
      font-weight: 600;
      font-size: 15px;
      color: var(--dark-blue);
    }
    .profile-role {
      font-size: 12px;
      color: #888;
      font-weight: 500;
      margin-top: 2px;
    }
    .settings-icon {
      font-size: 18px;
      color: var(--blue);
      cursor: pointer;
      transition: color 0.3s ease;
    }
    .settings-icon:hover { color: var(--gold); }

    .dropdown-menu {
      position: absolute;
      top: 60px;
      right: 0;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      display: none;
      flex-direction: column;
      width: 170px;
      z-index: 999;
      overflow: hidden;
    }
    .dropdown-item {
      padding: 12px 16px;
      font-size: 14px;
      color: #333;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .dropdown-item:hover { background-color: var(--gold); color: #fff; }
    .dropdown-item.logout i { color: #d9534f; }

    /* ===== MAIN CONTENT ===== */
    .main-content {
      margin-left: 240px;
      margin-top: 70px;
      padding: 0;
      width: calc(100% - 240px);
      transition: var(--transition);
    }
    .sidebar.collapsed ~ .main-content {
      margin-left: 60px;
      width: calc(100% - 60px);
    }

    /* ===== ATTENDANCE CENTER STYLES ===== */
    .page-wrapper {
      max-width: 1300px;
      margin: 36px auto;
      padding: 0 20px 40px;
      display: flex;
      gap: 28px;
      align-items: flex-start;
      flex-wrap: wrap;
    }

    .main-content-inner { flex: 1 1 720px; min-width: 320px; max-width: 880px; }
    .right-panel { flex: 0 0 320px; min-width: 280px; }

    .page-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 18px;
    }
    .page-title { font-size: 22px; font-weight: 700; color: var(--brand-red-dark); }
    .page-sub { color: var(--muted); font-size: 13px; }

    .summary {
      display: flex; gap: 12px; margin-bottom: 18px; flex-wrap: wrap;
    }
    .card {
      background: var(--card);
      padding: 14px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
      min-width: 140px;
      flex: 1 1 140px;
      border-left: 6px solid var(--brand-red);
    }
    .card.green { border-left-color: var(--brand-green); }
    .card h4 { margin: 0; font-size: 13px; color: var(--muted); }
    .card p { margin-top: 6px; font-weight: 800; font-size: 20px; color: var(--brand-red-dark); }

    .controls {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      margin-bottom: 14px;
    }
    .input, .select {
      background: var(--card);
      border: 1px solid rgba(15, 23, 42, 0.06);
      padding: 8px 10px;
      border-radius: 8px;
      font-size: 14px;
    }
    .btn {
      padding: 8px 12px;
      border-radius: 8px;
      cursor: pointer;
      border: none;
      font-weight: 700;
    }
    .btn.primary { background: var(--brand-red); color: #fff; }
    .btn.secondary { background: var(--brand-green); color: #fff; }
    .btn.ghost { background: transparent; border: 1px solid rgba(15, 23, 42, 0.06); }

    .table-card {
      background: var(--card);
      padding: 18px;
      border-radius: 12px;
      box-shadow: 0 8px 26px rgba(15, 23, 42, 0.06);
      overflow: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }
    thead th {
      text-align: left;
      padding: 12px 10px;
      color: var(--brand-red-dark);
      border-bottom: 2px solid rgba(15, 23, 42, 0.04);
      font-weight: 700;
    }
    tbody td {
      padding: 10px;
      border-top: 1px solid rgba(15, 23, 42, 0.03);
    }
    tbody tr:hover { background: var(--glass); }
    .status {
      font-weight: 700;
      padding: 6px 10px;
      border-radius: 999px;
      display: inline-block;
      font-size: 12px;
      color: #fff;
    }
    .status.present { background: var(--brand-green); }
    .status.absent { background: #6b7280; }
    .status.late { background: #f59e0b; }
    .status.leave { background: #6d28d9; }

    .form-card {
      background: var(--card);
      padding: 18px;
      border-radius: 12px;
      box-shadow: 0 8px 26px rgba(15, 23, 42, 0.06);
    }
    .form-card label {
      display: block;
      font-size: 13px;
      color: var(--muted);
      margin-bottom: 6px;
    }
    .form-card input,
    .form-card select {
      width: 100%;
      padding: 8px 10px;
      margin-bottom: 12px;
      border-radius: 8px;
      border: 1px solid rgba(15, 23, 42, 0.05);
    }

    .chart-card {
      background: var(--card);
      padding: 14px;
      border-radius: 12px;
      margin-top: 14px;
    }
    .chart-card canvas {
      width: 100%;
      max-height: 260px;
    }

    footer {
      margin-top: 18px;
      color: var(--muted);
      font-size: 13px;
      text-align: center;
    }

    /* ===== MOBILE RESPONSIVE ===== */
    @media (max-width: 992px) {
      .sidebar {
        display: none !important;
        visibility: hidden;
        opacity: 0;
        pointer-events: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        z-index: 2000;
        transition: none;
      }

      .sidebar.expanded {
        display: flex !important;
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
      }

      .top-navbar,
      .main-content {
        margin-left: 0 !important;
        width: 100% !important;
        left: 0 !important;
      }

      .page-wrapper {
        padding: 0 16px;
      }

      .right-panel {
        flex: 1 1 100%;
      }
    }
  </style>
</head>

<body>
  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="logo-header" id="logoToggle">
      <div class="logo-icon">A</div>
      <div class="logo-text">Atiera</div>
    </div>
    <ul class="nav-menu">
      <li class="nav-item"><a href="admin_dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt nav-icon"></i><span>Dashboard</span></a></li>
      <li class="nav-item">
        <div class="nav-link has-dropdown"><i class="fas fa-user-plus nav-icon"></i><span>Recruitment <br> Management</span></div>
        <ul class="submenu">
          <li><a href="admin_job-posting.php">Job Postings</a></li>
          <li><a href="admin_recruitment-newly-applicant.php">Newly Applicants</a></li>
          <li><a href="admin_interview-scheduler.php">Interview Scheduler</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <div class="nav-link has-dropdown"><i class="fas fa-users nav-icon"></i><span>Applicant <br> Management</span></div>
        <ul class="submenu">
          <li><a href="admin_applicant-documents.php">Applicant Document</a></li>
          <li><a href="admin_status-tracker.php">Status Tracking</a></li>
          <li><a href="admin_rejection-templates.php">Rejection Templates</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <div class="nav-link has-dropdown"><i class="fas fa-users nav-icon"></i><span> New Hire <br> Onboarding</span></div>
        <ul class="submenu">
          <li><a href="admin_onboarding-checklist.php">Onboarding Checklist</a></li>
          <li><a href="admin_document-collection.php">Document Collection</a></li>
          <li><a href="admin_welcome-survey.php">Welcome Surveys</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <div class="nav-link has-dropdown active"><i class="fas fa-users nav-icon"></i><span>Performance <br> Management</span></div>
        <ul class="submenu">
          <li><a href="admin_attendance-center.php" class="active">Attendance Center</a></li>
          <li><a href="#">Review Cycles</a></li>
          <li><a href="#">Feedback Tools</a></li>
          <li><a href="#">Calibration Sessions</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <div class="nav-link has-dropdown"><i class="fas fa-users nav-icon"></i><span> Social <br> Recognition</span></div>
        <ul class="submenu">
          <li><a href="#">Peer Recognition</a></li>
          <li><a href="#">Reward Catalog</a></li>
          <li><a href="#">Leaderboard</a></li>
          <li><a href="#">Announcement Feed</a></li>
        </ul>
      </li>
    </ul>
  </aside>

  <!-- TOP NAVBAR -->
  <nav class="top-navbar">
    <div class="current-datetime" id="liveDateTime">Loading...</div>
    <div class="profile-container">
      <div class="profile-pic">
        <img src="https://via.placeholder.com/40" alt="Admin Photo">
      </div>
      <div class="profile-info">
        <span class="profile-name"><?= htmlspecialchars($admin_name) ?></span> <br>
        <span class="profile-role">Admin</span>
      </div>
      <i class="fas fa-cog settings-icon" id="settingsIcon"></i>
      <div class="dropdown-menu" id="dropdownMenu">
        <a href="#" class="dropdown-item"><i class="fas fa-user"></i> Profile</a>
        <a href="#" class="dropdown-item"><i class="fas fa-sliders-h"></i> Settings</a>
        <a href="../landing_page.php" class="dropdown-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>
  </nav>

  <!-- MAIN CONTENT -->
  <main class="main-content">
    <div class="page-wrapper">
      <div class="main-content-inner">
        <div class="page-header">
          <div>
            <div class="page-title">Attendance Center</div>
            <div class="page-sub">Monitor attendance & integrate with performance management</div>
          </div>
          <div style="display:flex;gap:8px;align-items:center;">
            <button class="btn ghost" id="btnExportCSV">Export CSV</button>
            <button class="btn ghost" id="btnExportPDF">Export PDF</button>
          </div>
        </div>

        <div class="summary">
          <div class="card"><h4>Present</h4><p id="presentCount">0</p></div>
          <div class="card"><h4>Absent</h4><p id="absentCount">0</p></div>
          <div class="card"><h4>Late</h4><p id="lateCount">0</p></div>
          <div class="card green"><h4>On Leave</h4><p id="leaveCount">0</p></div>
        </div>

        <div class="controls">
          <div>
            <input class="input" id="searchInput" placeholder="Search employee name, dept..." />
            <select id="deptFilter" class="select">
              <option value="">All Departments</option>
              <option>Front Desk</option>
              <option>Housekeeping</option>
              <option>Kitchen</option>
              <option>Sales</option>
              <option>HR</option>
              <option>IT</option>
            </select>
            <input type="month" id="monthFilter" class="select" value="<?= $current_month ?>" />
          </div>
          <div>
            <select id="statusFilter" class="select">
              <option value="">All Status</option>
              <option>Present</option>
              <option>Absent</option>
              <option>Late</option>
              <option>Leave</option>
            </select>
            <button class="btn primary" id="btnReset">Reset Filters</button>
          </div>
        </div>

        <div class="table-card">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Department</th>
                <th>Date</th>
                <th>Time-in</th>
                <th>Time-out</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="tableBody"></tbody>
          </table>
        </div>

        <div class="chart-card">
          <h4 style="margin-bottom:8px;color:var(--muted);font-size:14px">Attendance Trend</h4>
          <canvas id="trendChart"></canvas>
        </div>

        <footer>© <?= date('Y') ?> Atiera Hotel & Restaurant — Attendance Center</footer>
      </div>

      <!-- RIGHT PANEL -->
      <aside class="right-panel">
        <div class="form-card">
          <h3 style="margin:0 0 8px 0; color:var(--brand-red-dark)">Add Attendance</h3>
          <form id="addForm">
            <label>Employee Name</label>
            <input type="text" name="name" required />

            <label>Department</label>
            <select name="dept" required>
              <option>Front Desk</option>
              <option>Housekeeping</option>
              <option>Kitchen</option>
              <option>Sales</option>
              <option>HR</option>
              <option>IT</option>
            </select>

            <label>Date</label>
            <input type="date" name="date" value="<?= date('Y-m-d') ?>" required />

            <label>Time-in</label>
            <input type="time" name="tin" />

            <label>Time-out</label>
            <input type="time" name="tout" />

            <label>Status</label>
            <select name="status" required>
              <option value="Present">Present</option>
              <option value="Absent">Absent</option>
              <option value="Late">Late</option>
              <option value="Leave">Leave</option>
            </select>

            <button class="btn secondary" type="submit" style="width:100%;">Add Record</button>
          </form>
        </div>
      </aside>
    </div>
  </main>

  <script>
    // ===== UI LOGIC =====
    const sidebar = document.getElementById('sidebar');
    const logoToggle = document.getElementById('logoToggle');
    const navItems = document.querySelectorAll('.nav-item');
    const settingsIcon = document.getElementById('settingsIcon');
    const dropdownMenu = document.getElementById('dropdownMenu');

    logoToggle.addEventListener('click', () => {
      if (window.innerWidth <= 992) {
        sidebar.classList.toggle('expanded');
      } else {
        sidebar.classList.toggle('collapsed');
      }
    });

    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 992 && !sidebar.contains(e.target) && sidebar.classList.contains('expanded')) {
        sidebar.classList.remove('expanded');
      }
      if (!dropdownMenu.contains(e.target) && !settingsIcon.contains(e.target)) {
        dropdownMenu.style.display = 'none';
      }
    });

    navItems.forEach(item => {
      const link = item.querySelector('.nav-link');
      if (link.classList.contains('has-dropdown')) {
        link.addEventListener('click', (e) => {
          e.preventDefault();
          navItems.forEach(other => { if (other !== item) other.classList.remove('active'); });
          item.classList.toggle('active');
        });
      }
    });

    settingsIcon.addEventListener('click', (e) => {
      e.stopPropagation();
      dropdownMenu.style.display = dropdownMenu.style.display === 'flex' ? 'none' : 'flex';
    });

    function updateDateTime() {
      const now = new Date();
      const options = { year:'numeric', month:'long', day:'numeric', hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:false };
      document.getElementById('liveDateTime').textContent = now.toLocaleDateString('en-US', options).replace(',', ' |');
    }
    updateDateTime(); setInterval(updateDateTime, 1000);

    // ===== ATTENDANCE WITH PERSISTENCE =====
    const tbody = document.getElementById('tableBody');
    const presentCountEl = document.getElementById('presentCount');
    const absentCountEl = document.getElementById('absentCount');
    const lateCountEl = document.getElementById('lateCount');
    const leaveCountEl = document.getElementById('leaveCount');
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    let trendChart;

    let records = JSON.parse(localStorage.getItem('attendanceRecords')) || [];

    function renderTableRows(rows) {
      tbody.innerHTML = '';
      rows.forEach((r, i) => {
        tbody.innerHTML += `
        <tr>
          <td>${i + 1}</td>
          <td>${r.name}</td>
          <td>${r.dept}</td>
          <td>${r.date}</td>
          <td>${r.tin || '-'}</td>
          <td>${r.tout || '-'}</td>
          <td><span class="status ${r.status.toLowerCase()}">${r.status}</span></td>
          <td><button class="btn ghost">Edit</button></td>
        </tr>`;
      });
    }

    function updateSummary(rows) {
      presentCountEl.textContent = rows.filter(r => r.status === 'Present').length;
      absentCountEl.textContent = rows.filter(r => r.status === 'Absent').length;
      lateCountEl.textContent = rows.filter(r => r.status === 'Late').length;
      leaveCountEl.textContent = rows.filter(r => r.status === 'Leave').length;
    }

   function updateChart(rows) {
  const map = {};
  rows.forEach(r => {
    if (!map[r.date]) map[r.date] = { Present: 0, Absent: 0, Late: 0, Leave: 0 };
    map[r.date][r.status]++;
  });
  const labels = Object.keys(map);
  const datasets = [
    { label: 'Present', data: labels.map(l => map[l].Present || 0), backgroundColor: 'rgba(15,118,110,0.85)' },
    { label: 'Late', data: labels.map(l => map[l].Late || 0), backgroundColor: 'rgba(245,158,11,0.85)' },
    { label: 'Absent', data: labels.map(l => map[l].Absent || 0), backgroundColor: 'rgba(107,114,128,0.85)' }
  ];
  if (trendChart) trendChart.destroy();
  trendChart = new Chart(trendCtx, {
    type: 'bar',
    data: { labels, datasets },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } },
      scales: {
        x: {
          stacked: false // ✅ Not stacked
        },
        y: {
          stacked: false, // ✅ Not stacked
          beginAtZero: true
        }
      },
      // ✅ Control spacing between bars
      barPercentage: 0.9,     // width of bars within a group
      categoryPercentage: 0.8 // space between groups
    }
  });
}

    renderTableRows(records);
    updateSummary(records);
    updateChart(records);

    document.getElementById('addForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const form = e.target;
      const newRecord = {
        name: form.name.value.trim(),
        dept: form.dept.value,
        date: form.date.value,
        tin: form.tin.value || '',
        tout: form.tout.value || '',
        status: form.status.value
      };

      if (!newRecord.name) {
        alert('Please enter employee name.');
        return;
      }

      records.push(newRecord);
      localStorage.setItem('attendanceRecords', JSON.stringify(records));

      renderTableRows(records);
      updateSummary(records);
      updateChart(records);

      form.reset();
      form.date.value = new Date().toISOString().split('T')[0];
      alert('Attendance record added successfully!');
    });

    document.getElementById('btnReset').addEventListener('click', () => {
      document.getElementById('searchInput').value = '';
      document.getElementById('deptFilter').value = '';
      document.getElementById('statusFilter').value = '';
      document.getElementById('monthFilter').value = '<?= $current_month ?>';
      renderTableRows(records);
      updateSummary(records);
      updateChart(records);
    });

    document.getElementById('btnExportCSV').onclick = () => alert('Export CSV feature can be added later.');
    document.getElementById('btnExportPDF').onclick = () => alert('Export PDF feature can be added later.');
  </script>
</body>
</html>