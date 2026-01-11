<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../connections.php';

// ✅ Use application DB
if (!isset($hr1_application) || !($hr1_application instanceof mysqli)) {
    die("❌ Database connection error: hr1_application_db not available");
}
$connections = $hr1_application;

// =======================
// Ensure 'result' column exists
// =======================
$check_col = $connections->query("SHOW COLUMNS FROM interview_schedule LIKE 'result'");
if ($check_col->num_rows === 0) {
    $connections->query("ALTER TABLE interview_schedule ADD COLUMN result ENUM('Pending','Pass','Fail') DEFAULT 'Pending' AFTER schedule_date");
}

// =======================
// Redirect helper
// =======================
function redirect_here() {
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// =======================
// Handle POST CRUD
// =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['interview_id'] ?? 0);
    $application_id = intval($_POST['application_id'] ?? 0);
    $rawDate = trim($_POST['schedule_date'] ?? '');
    $result = trim($_POST['result'] ?? 'Pending');

    if (strpos($rawDate, 'T') !== false) $rawDate = str_replace('T', ' ', $rawDate);
    $dt = date_create($rawDate);
    $date = $dt ? $dt->format('Y-m-d H:i:s') : null;

    // DELETE
    if (isset($_POST['delete_interview']) && $id > 0) {
        $stmt = $connections->prepare("DELETE FROM interview_schedule WHERE interview_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        redirect_here();
    }

    // ADD
    if (isset($_POST['add_interview']) && $application_id > 0 && $date) {
        $stmt = $connections->prepare("INSERT INTO interview_schedule (application_id, schedule_date, result, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("iss", $application_id, $date, $result);
        $stmt->execute();
        $stmt->close();
        redirect_here();
    }

    // UPDATE
    if (isset($_POST['update_interview']) && $id > 0 && $application_id > 0 && $date) {
        $stmt = $connections->prepare("UPDATE interview_schedule SET application_id=?, schedule_date=?, result=?, updated_at=NOW() WHERE interview_id=?");
        $stmt->bind_param("issi", $application_id, $date, $result, $id);
        $stmt->execute();
        $stmt->close();
        redirect_here();
    }
}

// =======================
// Fetch Data
// =======================
$total_interviews = 0;
$results = ['Pass'=>0,'Fail'=>0,'Pending'=>0];
$upcoming = [];
$recent = [];
$calendar_events = [];
$applications = [];

// Total interviews
$res = $connections->query("SELECT COUNT(*) AS total FROM interview_schedule");
if ($res && $row = $res->fetch_assoc()) $total_interviews = (int)$row['total'];

// Result counts
$res = $connections->query("SELECT result, COUNT(*) AS cnt FROM interview_schedule GROUP BY result");
if ($res) while($row = $res->fetch_assoc()) $results[$row['result']] = (int)$row['cnt'];

// Applications dropdown
$sql = "SELECT ja.application_id, CONCAT(ap.first_name,' ',ap.last_name,' — ', j.job_title) AS label
        FROM job_applications ja
        JOIN hr1_recruitment_db.jobs j ON ja.job_id=j.job_id
        JOIN applicants ap ON ja.applicant_id=ap.applicant_id
        ORDER BY ja.application_date DESC";
$res = $connections->query($sql);
if ($res) while($row = $res->fetch_assoc()) $applications[] = $row;

// Upcoming (next 30 days)
$stmt = $connections->prepare("SELECT i.interview_id, ja.application_id, ap.first_name, ap.last_name, j.job_title, i.schedule_date, i.result
    FROM interview_schedule i
    JOIN job_applications ja ON i.application_id = ja.application_id
    JOIN applicants ap ON ja.applicant_id = ap.applicant_id
    JOIN hr1_recruitment_db.jobs j ON ja.job_id=j.job_id
    WHERE i.schedule_date >= NOW() AND i.schedule_date <= DATE_ADD(NOW(), INTERVAL 30 DAY)
    ORDER BY i.schedule_date ASC");
$stmt->execute();
$res = $stmt->get_result();
while($row = $res->fetch_assoc()) $upcoming[] = $row;
$stmt->close();

// Recent (last 7 days)
$stmt = $connections->prepare("SELECT i.interview_id, ja.application_id, ap.first_name, ap.last_name, j.job_title, i.schedule_date, i.result
    FROM interview_schedule i
    JOIN job_applications ja ON i.application_id = ja.application_id
    JOIN applicants ap ON ja.applicant_id = ap.applicant_id
    JOIN hr1_recruitment_db.jobs j ON ja.job_id=j.job_id
    WHERE i.schedule_date BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()
    ORDER BY i.schedule_date DESC");
$stmt->execute();
$res = $stmt->get_result();
while($row = $res->fetch_assoc()) $recent[] = $row;
$stmt->close();

// Calendar events
$res = $connections->query("SELECT i.interview_id, ja.application_id, CONCAT(ap.first_name,' ',ap.last_name,' — ', j.job_title) AS title, i.schedule_date AS start, i.result
    FROM interview_schedule i
    JOIN job_applications ja ON i.application_id = ja.application_id
    JOIN applicants ap ON ja.applicant_id = ap.applicant_id
    JOIN hr1_recruitment_db.jobs j ON ja.job_id=j.job_id");
if ($res) while($row = $res->fetch_assoc()) {
    $calendar_events[] = [
        'id' => (int)$row['interview_id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'extendedProps' => [
            'application_id' => (int)$row['application_id'],
            'result' => $row['result'] ?? 'Pending'
        ]
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Interview Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<style>
.icon-btn { display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:6px;border-radius:8px;border:1px solid transparent;transition:0.12s;}
.icon-btn:hover { transform:translateY(-1px);}
.icon-edit { background:#eef2ff;color:#4338ca;border-color:rgba(67,56,202,0.12);}
.icon-delete { background:#fff5f5;color:#dc2626;border-color:rgba(220,38,38,0.08);}
.icon-calendar { background:#ecfdf5;color:#059669;border-color:rgba(5,150,105,0.08);}
</style>
</head>
<body class="h-screen bg-gray-100 font-sans">
<div class="flex h-full">
  <?php include 'sidebar.php'; ?>
  <div class="flex-1 flex flex-col overflow-y-auto">
    <main class="p-6 space-y-6">
      <div class="flex items-center justify-between border-b border-gray-300 pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Interview Dashboard</h2>
        <?php include 'profile.php'; ?>
      </div>
      <div><?php include 'admin_navbar.php'; ?></div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
        <div class="bg-white p-4 rounded-xl shadow">
          <h2 class="text-gray-500">Total Interviews</h2>
          <p class="text-2xl font-bold"><?= htmlspecialchars($total_interviews) ?></p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow"><h2 class="text-green-600">Passed</h2><p class="text-2xl font-bold"><?= htmlspecialchars($results['Pass'] ?? 0) ?></p></div>
        <div class="bg-white p-4 rounded-xl shadow"><h2 class="text-red-600">Failed</h2><p class="text-2xl font-bold"><?= htmlspecialchars($results['Fail'] ?? 0) ?></p></div>
        <div class="bg-white p-4 rounded-xl shadow"><h2 class="text-yellow-600">Pending</h2><p class="text-2xl font-bold"><?= htmlspecialchars($results['Pending'] ?? 0) ?></p></div>
      </div>

      <!-- Calendar -->
      <div class="bg-white p-4 rounded-xl shadow mt-6">
        <div id="calendar"></div>
      </div>

      <!-- Upcoming Interviews -->
      <div class="bg-white p-4 rounded-xl shadow mt-6">
        <h3 class="text-lg font-bold mb-4">Upcoming Interviews</h3>
        <table class="w-full table-auto">
          <thead>
            <tr class="bg-gray-100">
              <th class="p-2 text-left">Applicant</th>
              <th class="p-2 text-left">Job</th>
              <th class="p-2 text-left">Date</th>
              <th class="p-2 text-left">Result</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($upcoming as $i): ?>
            <tr class="border-b">
              <td><?= htmlspecialchars($i['first_name'].' '.$i['last_name']) ?></td>
              <td><?= htmlspecialchars($i['job_title']) ?></td>
              <td><?= htmlspecialchars($i['schedule_date']) ?></td>
              <td><?= htmlspecialchars($i['result'] ?? 'Pending') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </main>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){
  lucide.createIcons();

  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    height: 600,
    events: <?= json_encode($calendar_events) ?>
  });
  calendar.render();
});
</script>
</body>
</html>
