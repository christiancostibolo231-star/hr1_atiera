<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../connections.php';

/* =========================
   DATABASE CHECK
========================= */
if (!isset($connections) || !($connections instanceof mysqli)) {
    die("❌ Database connection error");
}

// Force correct DB
$connections->select_db('hr1');

/* =========================
   CREATE interview_schedule TABLE (SAFE)
========================= */
$connections->query("
CREATE TABLE IF NOT EXISTS interview_schedule (
    interview_id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    schedule_date DATETIME NOT NULL,
    result ENUM('Pending','Pass','Fail') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id)
        REFERENCES job_applications(application_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;
");

/* =========================
   REDIRECT HELPER
========================= */
function redirect_here() {
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

/* =========================
   HANDLE CRUD
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['interview_id'] ?? 0);
    $application_id = intval($_POST['application_id'] ?? 0);
    $rawDate = trim($_POST['schedule_date'] ?? '');
    $result = $_POST['result'] ?? 'Pending';

    if (strpos($rawDate, 'T') !== false) {
        $rawDate = str_replace('T', ' ', $rawDate);
    }
    $date = date('Y-m-d H:i:s', strtotime($rawDate));

    // ADD
    if (isset($_POST['add_interview'])) {
        $stmt = $connections->prepare("
            INSERT INTO interview_schedule (application_id, schedule_date, result)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iss", $application_id, $date, $result);
        $stmt->execute();
        $stmt->close();
        redirect_here();
    }

    // UPDATE
    if (isset($_POST['update_interview'])) {
        $stmt = $connections->prepare("
            UPDATE interview_schedule
            SET application_id=?, schedule_date=?, result=?
            WHERE interview_id=?
        ");
        $stmt->bind_param("issi", $application_id, $date, $result, $id);
        $stmt->execute();
        $stmt->close();
        redirect_here();
    }

    // DELETE
    if (isset($_POST['delete_interview'])) {
        $stmt = $connections->prepare("DELETE FROM interview_schedule WHERE interview_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        redirect_here();
    }
}

/* =========================
   DASHBOARD STATS
========================= */
$total_interviews = 0;
$results = ['Pass'=>0,'Fail'=>0,'Pending'=>0];

$res = $connections->query("SELECT COUNT(*) AS total FROM interview_schedule");
if ($row = $res->fetch_assoc()) $total_interviews = $row['total'];

$res = $connections->query("
    SELECT result, COUNT(*) cnt
    FROM interview_schedule
    GROUP BY result
");
while ($row = $res->fetch_assoc()) {
    $results[$row['result']] = $row['cnt'];
}

/* =========================
   APPLICATIONS DROPDOWN
========================= */
$applications = [];
$res = $connections->query("
    SELECT ja.application_id,
           CONCAT(ap.first_name,' ',ap.last_name,' — ',j.job_title) AS label
    FROM job_applications ja
    JOIN applicants ap ON ja.applicant_id = ap.applicant_id
    JOIN jobs j ON ja.job_id = j.job_id
    ORDER BY ja.application_date DESC
");
while ($row = $res->fetch_assoc()) $applications[] = $row;

/* =========================
   UPCOMING INTERVIEWS
========================= */
$upcoming = [];
$res = $connections->query("
    SELECT i.*, ap.first_name, ap.last_name, j.job_title
    FROM interview_schedule i
    JOIN job_applications ja ON i.application_id = ja.application_id
    JOIN applicants ap ON ja.applicant_id = ap.applicant_id
    JOIN jobs j ON ja.job_id = j.job_id
    WHERE i.schedule_date >= NOW()
    ORDER BY i.schedule_date ASC
");
while ($row = $res->fetch_assoc()) $upcoming[] = $row;

/* =========================
   CALENDAR EVENTS
========================= */
$calendar_events = [];
$res = $connections->query("
    SELECT i.interview_id,
           CONCAT(ap.first_name,' ',ap.last_name,' — ',j.job_title) AS title,
           i.schedule_date AS start,
           i.result
    FROM interview_schedule i
    JOIN job_applications ja ON i.application_id = ja.application_id
    JOIN applicants ap ON ja.applicant_id = ap.applicant_id
    JOIN jobs j ON ja.job_id = j.job_id
");
while ($row = $res->fetch_assoc()) {
    $calendar_events[] = [
        'id' => $row['interview_id'],
        'title' => $row['title'],
        'start' => $row['start']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Interview Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
</head>

<body class="bg-gray-100 h-screen font-sans">
<div class="flex h-full">

<?php include 'sidebar.php'; ?>

<div class="flex-1 p-6 space-y-6 overflow-y-auto">
<h2 class="text-2xl font-bold">Interview Dashboard</h2>

<!-- STATS -->
<div class="grid grid-cols-4 gap-4">
<div class="bg-white p-4 rounded shadow">Total: <?= $total_interviews ?></div>
<div class="bg-green-100 p-4 rounded">Passed: <?= $results['Pass'] ?></div>
<div class="bg-red-100 p-4 rounded">Failed: <?= $results['Fail'] ?></div>
<div class="bg-yellow-100 p-4 rounded">Pending: <?= $results['Pending'] ?></div>
</div>

<!-- CALENDAR -->
<div class="bg-white p-4 rounded shadow">
<div id="calendar"></div>
</div>

<!-- UPCOMING -->
<div class="bg-white p-4 rounded shadow">
<h3 class="font-bold mb-2">Upcoming Interviews</h3>
<table class="w-full text-sm">
<tr class="bg-gray-100">
<th>Applicant</th><th>Job</th><th>Date</th><th>Result</th>
</tr>
<?php foreach ($upcoming as $u): ?>
<tr class="border-b">
<td><?= htmlspecialchars($u['first_name'].' '.$u['last_name']) ?></td>
<td><?= htmlspecialchars($u['job_title']) ?></td>
<td><?= $u['schedule_date'] ?></td>
<td><?= $u['result'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>

</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){
  new FullCalendar.Calendar(document.getElementById('calendar'), {
    initialView: 'dayGridMonth',
    height: 600,
    events: <?= json_encode($calendar_events) ?>
  }).render();
});
</script>
</body>
</html>
