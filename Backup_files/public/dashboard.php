<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . "/../connections.php";

// ✅ Check login session
// if (!isset($_SESSION['applicant_id'])) {
//     die("You must be logged in to access this page.");
// }

$applicant_id = $_SESSION['applicant_id'];

// ✅ Connect to recruitment database
if (!isset($hr1_recruitment) || !($hr1_recruitment instanceof mysqli)) {
    die("Database connection error: recruitment DB not available");
}

// ✅ Fetch all open jobs
$jobs = [];
$stmt = $hr1_recruitment->prepare("SELECT job_id, job_title, department, status, date_posted FROM jobs WHERE status = 'open'");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
    $stmt->close();
} else {
    die("Failed to load jobs: " . $hr1_recruitment->error);
}

// ✅ Fetch applicant status
$status = "Not Applied";
$stmt = $hr1_recruitment->prepare("SELECT status FROM applicants WHERE applicant_id = ?");
$stmt->bind_param("i", $applicant_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $status = $row['status'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Applicant Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="min-h-screen flex flex-col">
  <!-- Header -->
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold text-gray-800">Applicant Dashboard</h1>
    <div class="flex items-center space-x-3">
      <span class="text-gray-700 font-semibold">Applicant ID: <?= htmlspecialchars($applicant_id) ?></span>
      <a href="logout.php" class="text-red-600 hover:underline">Logout</a>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-1 p-6">
    <!-- Status Card -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-lg font-semibold mb-2">Your Application Status</h2>
      <p class="text-gray-600">Current status: 
        <span class="font-bold <?= strtolower($status) === 'approved' ? 'text-green-600' : (strtolower($status) === 'pending' ? 'text-yellow-600' : 'text-red-600') ?>">
          <?= htmlspecialchars($status) ?>
        </span>
      </p>
    </div>

    <!-- Open Jobs -->
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold mb-4">Available Job Openings</h2>

      <?php if (empty($jobs)): ?>
        <p class="text-gray-500">No open jobs at the moment.</p>
      <?php else: ?>
        <div class="overflow-x-auto">
          <table class="min-w-full border border-gray-200 text-sm text-left">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="p-3 font-semibold text-gray-600">Job Title</th>
                <th class="p-3 font-semibold text-gray-600">Department</th>
                <th class="p-3 font-semibold text-gray-600">Date Posted</th>
                <th class="p-3 font-semibold text-gray-600 text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($jobs as $job): ?>
                <tr class="border-b hover:bg-gray-50">
                  <td class="p-3"><?= htmlspecialchars($job['job_title']) ?></td>
                  <td class="p-3"><?= htmlspecialchars($job['department']) ?></td>
                  <td class="p-3"><?= htmlspecialchars(date("M d, Y", strtotime($job['date_posted']))) ?></td>
                  <td class="p-3 text-center">
                    <a href="apply.php?job_id=<?= $job['job_id'] ?>" 
                       class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                      Apply
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  lucide.createIcons();
});
</script>

</body>
</html>
