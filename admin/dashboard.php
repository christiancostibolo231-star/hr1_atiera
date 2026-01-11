<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../connections.php";

/*
|--------------------------------------------------------------------------
| Recruitment Stats (USING hr1 DATABASE ONLY)
|--------------------------------------------------------------------------
*/
$total_jobs  = 0;
$open_jobs   = 0;
$closed_jobs = 0;

$stmt = $connections->prepare("
    SELECT status, COUNT(*) AS count
    FROM jobs
    GROUP BY status
");

if (!$stmt) {
    die("Query preparation failed: " . $connections->error);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $status = strtolower($row['status']);

    if ($status === 'open') {
        $open_jobs = (int)$row['count'];
    } elseif ($status === 'closed') {
        $closed_jobs = (int)$row['count'];
    }

    $total_jobs += (int)$row['count'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="h-screen bg-gray-100 font-sans">

<div class="flex h-full">

  <!-- Sidebar -->
  <?php include 'sidebar.php'; ?>

  <!-- Main Content -->
  <div class="flex-1 flex flex-col overflow-y-auto">

    <main class="p-6 space-y-6">

      <!-- Header -->
      <div class="flex items-center justify-between border-b border-gray-300 pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
        <?php include 'profile.php'; ?>
      </div>

      <!-- Admin Navbar -->
      <div><?php include 'admin_navbar.php'; ?></div>

      <!-- Dashboard Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- Recruitment Card -->
        <div onclick="openModal()"
             class="cursor-pointer bg-white p-6 rounded-lg shadow-md border border-gray-200
                    hover:shadow-lg hover:scale-105 transition-transform duration-300">

          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Recruitment</h3>
            <i data-lucide="briefcase" class="w-6 h-6 text-blue-600"></i>
          </div>

          <p class="text-3xl font-bold text-gray-900"><?= $total_jobs ?></p>
          <p class="text-sm text-gray-500">Total Job Postings</p>

          <div class="mt-4 flex space-x-6 text-sm">
            <span class="text-green-600">Open: <?= $open_jobs ?></span>
            <span class="text-red-600">Closed: <?= $closed_jobs ?></span>
          </div>
        </div>

      </div>
    </main>
  </div>
</div>

<!-- Recruitment Modal -->
<div id="recruitmentModal"
     class="hidden fixed inset-0 flex items-center justify-center
            bg-black bg-opacity-50 z-50">

  <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-lg p-6 relative">

    <button onclick="closeModal()"
            class="absolute top-3 right-3 text-gray-600 hover:text-black">
      <i data-lucide="x" class="w-6 h-6"></i>
    </button>

    <h3 class="text-xl font-bold text-gray-800 mb-4">
      Recruitment Status
    </h3>

    <canvas id="recruitmentChart" width="400" height="400"></canvas>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  lucide.createIcons();

  const ctx = document.getElementById("recruitmentChart").getContext("2d");

  const recruitmentChart = new Chart(ctx, {
    type: "pie",
    data: {
      labels: ["Open Jobs", "Closed Jobs"],
      datasets: [{
        data: [<?= $open_jobs ?>, <?= $closed_jobs ?>],
        backgroundColor: ["#22c55e", "#ef4444"]
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: "bottom" }
      }
    }
  });

  function updateRecruitmentChart(openJobs, closedJobs) {
    recruitmentChart.data.datasets[0].data = [openJobs, closedJobs];
    recruitmentChart.update();
  }

  window.openModal = () => {
    document.getElementById("recruitmentModal").classList.remove("hidden");

    fetch("get_recruitment_stats.php")
      .then(res => res.json())
      .then(data => {
        updateRecruitmentChart(data.open_jobs, data.closed_jobs);
      })
      .catch(err => console.error(err));
  };

  window.closeModal = () => {
    document.getElementById("recruitmentModal").classList.add("hidden");
  };
});
</script>

</body>
</html>
