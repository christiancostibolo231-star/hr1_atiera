<?php
// recognition_dashboard.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../connections.php";
if (!isset($connections) || !($connections instanceof mysqli)) {
    die("Database connection error: check connections.php");
}

// ========== Sample Recognition Data ==========
$awardsList = [
  ["id" => 1, "name" => "Juan Dela Cruz", "award" => "Employee of the Month", "date" => "August 2025"],
  ["id" => 2, "name" => "Maria Santos", "award" => "Best Customer Service", "date" => "July 2025"],
];

$certificatesList = [
  ["id" => 1, "name" => "Juan Dela Cruz", "certificate" => "Food Safety Training", "date" => "July 2025"],
  ["id" => 2, "name" => "Maria Santos", "certificate" => "Leadership Workshop", "date" => "August 2025"],
];

$kudosList = [
  ["id" => 1, "from" => "Manager", "to" => "Juan Dela Cruz", "message" => "Great teamwork during the busy season!"],
  ["id" => 2, "from" => "Teammates", "to" => "Maria Santos", "message" => "Always helping new staff adjust quickly."],
];

$milestonesList = [
  ["id" => 1, "name" => "Juan Dela Cruz", "milestone" => "5-Year Anniversary", "date" => "Sept 2025"],
  ["id" => 2, "name" => "Maria Santos", "milestone" => "Promotion to Supervisor", "date" => "Aug 2025"],
];

// Count totals for chart
$awardsCount = count($awardsList);
$certificatesCount = count($certificatesList);
$kudosCount = count($kudosList);
$milestonesCount = count($milestonesList);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Recognition Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    /* Keep chart responsive and smaller */
    #chart-container {
      max-width: 350px;
      margin: 0 auto;
    }
  </style>
</head>
<body class="h-screen bg-gray-50 font-sans">

<div class="flex h-full">
  <!-- Sidebar -->
  <?php include 'sidebar.php'; ?>

  <!-- Main Content -->
  <div class="flex-1 flex flex-col overflow-y-auto">
    <main class="p-6 space-y-8">

      <!-- Header -->
      <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
          <i data-lucide="medal" class="w-7 h-7 text-yellow-600"></i>
          Recognition Dashboard
        </h2>
        <?php include 'profile.php'; ?>
      </div>
      <div><?php include 'admin_navbar.php'; ?></div>

      <!-- Analytics Chart -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
          <i data-lucide="pie-chart" class="w-5 h-5 text-indigo-600"></i> Recognition Breakdown
        </h3>
        <div id="chart-container">
          <canvas id="recognitionChart"></canvas>
        </div>
      </div>

      <!-- Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Awards Card -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
          <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
            <i data-lucide="award" class="w-5 h-5 text-yellow-600"></i> Awards
          </h3>
          <p class="text-gray-500 mt-2">Recognitions like employee of the month, best service, etc.</p>
          <p class="text-3xl font-bold text-indigo-600 mt-4"><?= $awardsCount ?></p>
          <button onclick="openTab('awards-tab')" 
            class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">
            View All
          </button>
        </div>

        <!-- Certificates Card -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
          <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
            <i data-lucide="file-check" class="w-5 h-5 text-green-600"></i> Certificates
          </h3>
          <p class="text-gray-500 mt-2">Completed trainings and certifications by employees.</p>
          <p class="text-3xl font-bold text-indigo-600 mt-4"><?= $certificatesCount ?></p>
          <button onclick="openTab('certificates-tab')" 
            class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">
            View All
          </button>
        </div>

        <!-- Kudos Card -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
          <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
            <i data-lucide="thumbs-up" class="w-5 h-5 text-blue-600"></i> Kudos
          </h3>
          <p class="text-gray-500 mt-2">Peer-to-peer or manager recognition messages.</p>
          <p class="text-3xl font-bold text-indigo-600 mt-4"><?= $kudosCount ?></p>
          <button onclick="openTab('kudos-tab')" 
            class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">
            View All
          </button>
        </div>

        <!-- Milestones Card -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
          <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
            <i data-lucide="calendar" class="w-5 h-5 text-red-600"></i> Milestones
          </h3>
          <p class="text-gray-500 mt-2">Work anniversaries, promotions, or other milestones.</p>
          <p class="text-3xl font-bold text-indigo-600 mt-4"><?= $milestonesCount ?></p>
          <button onclick="openTab('milestones-tab')" 
            class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">
            View All
          </button>
        </div>
      </div>

      <!-- Tab Section -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mt-8">
        <div class="flex space-x-6 border-b pb-2 mb-4 overflow-x-auto">
          <button id="awards-btn" onclick="openTab('awards-tab')" class="tab-btn text-indigo-600 font-semibold border-b-2 border-indigo-600">Awards</button>
          <button id="certificates-btn" onclick="openTab('certificates-tab')" class="tab-btn text-gray-600">Certificates</button>
          <button id="kudos-btn" onclick="openTab('kudos-tab')" class="tab-btn text-gray-600">Kudos</button>
          <button id="milestones-btn" onclick="openTab('milestones-tab')" class="tab-btn text-gray-600">Milestones</button>
        </div>

        <!-- Awards Tab -->
        <div id="awards-tab" class="tab-content">
          <h4 class="text-lg font-bold text-gray-700 mb-2">Awards List</h4>
          <ul class="list-disc ml-6 text-gray-600">
            <?php foreach ($awardsList as $a): ?>
              <li><span class="font-semibold"><?= $a["name"] ?></span> - <?= $a["award"] ?> (<?= $a["date"] ?>)</li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Certificates Tab -->
        <div id="certificates-tab" class="tab-content hidden">
          <h4 class="text-lg font-bold text-gray-700 mb-2">Certificates List</h4>
          <ul class="list-disc ml-6 text-gray-600">
            <?php foreach ($certificatesList as $c): ?>
              <li><span class="font-semibold"><?= $c["name"] ?></span> - <?= $c["certificate"] ?> (<?= $c["date"] ?>)</li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Kudos Tab -->
        <div id="kudos-tab" class="tab-content hidden">
          <h4 class="text-lg font-bold text-gray-700 mb-2">Kudos Messages</h4>
          <ul class="list-disc ml-6 text-gray-600">
            <?php foreach ($kudosList as $k): ?>
              <li><span class="font-semibold"><?= $k["from"] ?></span> ➝ <?= $k["to"] ?> : "<?= $k["message"] ?>"</li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Milestones Tab -->
        <div id="milestones-tab" class="tab-content hidden">
          <h4 class="text-lg font-bold text-gray-700 mb-2">Milestones</h4>
          <ul class="list-disc ml-6 text-gray-600">
            <?php foreach ($milestonesList as $m): ?>
              <li><span class="font-semibold"><?= $m["name"] ?></span> - <?= $m["milestone"] ?> (<?= $m["date"] ?>)</li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>

    </main>
  </div>
</div>

<script>
lucide.createIcons();

// Chart.js - Recognition Breakdown
new Chart(document.getElementById('recognitionChart'), {
  type: 'doughnut',
  data: {
    labels: ['Awards', 'Certificates', 'Kudos', 'Milestones'],
    datasets: [{
      data: [<?= $awardsCount ?>, <?= $certificatesCount ?>, <?= $kudosCount ?>, <?= $milestonesCount ?>],
      backgroundColor: ['#facc15','#22c55e','#3b82f6','#ef4444'],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    aspectRatio: 1.5,
    plugins: {
      legend: { position: 'bottom', labels: { color: '#374151' } }
    }
  }
});

// Tab switching
function openTab(tabId) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
  document.getElementById(tabId).classList.remove('hidden');

  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('text-indigo-600','font-semibold','border-b-2','border-indigo-600');
    btn.classList.add('text-gray-600');
  });
  document.querySelector(`#${tabId.replace('-tab','')}-btn`)
    .classList.add('text-indigo-600','font-semibold','border-b-2','border-indigo-600');

  window.scrollTo({ top: document.querySelector(`#${tabId}`).offsetTop - 80, behavior: 'smooth' });
}
</script>

</body>
</html>
