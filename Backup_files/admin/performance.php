<?php
// performance_dashboard.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../connections.php";
if (!isset($connections) || !($connections instanceof mysqli)) {
    die("Database connection error: check connections.php");
}

// ================= Sample Performance Data =================
$reviewsList = [
  ["id" => 1, "name" => "Juan Dela Cruz", "review" => "Q3 2025", "rating" => "Excellent"],
  ["id" => 2, "name" => "Maria Santos", "review" => "Q3 2025", "rating" => "Good"],
];

$attendanceList = [
  ["id" => 1, "name" => "Juan Dela Cruz", "late" => 2, "absent" => 0],
  ["id" => 2, "name" => "Maria Santos", "late" => 1, "absent" => 1],
];

$goalsList = [
  ["id" => 1, "name" => "Juan Dela Cruz", "goal" => "Increase customer satisfaction", "progress" => 80],
  ["id" => 2, "name" => "Maria Santos", "goal" => "Reduce kitchen waste", "progress" => 60],
];

$achievementsList = [
  ["id" => 1, "name" => "Juan Dela Cruz", "achievement" => "Employee of the Month - Aug 2025"],
  ["id" => 2, "name" => "Maria Santos", "achievement" => "Perfect Attendance - Q2 2025"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Performance Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
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
          <i data-lucide="bar-chart-3" class="w-7 h-7 text-indigo-600"></i>
          Performance Dashboard
        </h2>
        <?php include 'profile.php'; ?>
      </div>
      <div><?php include 'admin_navbar.php'; ?></div>

      <!-- Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Reviews Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 transition-transform hover:scale-[1.02]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
              <i data-lucide="star" class="w-5 h-5 text-yellow-500"></i> Reviews
            </h3>
            <button onclick="openTab('reviews-tab')" class="text-yellow-600 hover:bg-yellow-50 px-3 py-1 rounded-full text-sm font-medium transition">View All →</button>
          </div>
          <ul class="text-sm space-y-2">
            <?php foreach (array_slice($reviewsList, 0, 2) as $r): ?>
              <li class="flex justify-between">
                <span><?= $r["name"] ?> (<?= $r["review"] ?>)</span>
                <span class="px-2 py-0.5 rounded-full text-xs <?= $r["rating"] === 'Excellent' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' ?>">
                  <?= $r["rating"] ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Attendance Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 transition-transform hover:scale-[1.02]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
              <i data-lucide="clock" class="w-5 h-5 text-red-500"></i> Attendance
            </h3>
            <button onclick="openTab('attendance-tab')" class="text-red-600 hover:bg-red-50 px-3 py-1 rounded-full text-sm font-medium transition">View All →</button>
          </div>
          <ul class="text-sm space-y-2">
            <?php foreach (array_slice($attendanceList, 0, 2) as $a): ?>
              <li class="flex justify-between">
                <span><?= $a["name"] ?></span>
                <span class="text-gray-500">Late: <?= $a["late"] ?> | Absent: <?= $a["absent"] ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Goals Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 transition-transform hover:scale-[1.02]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
              <i data-lucide="target" class="w-5 h-5 text-indigo-600"></i> Goals
            </h3>
            <button onclick="openTab('goals-tab')" class="text-indigo-600 hover:bg-indigo-50 px-3 py-1 rounded-full text-sm font-medium transition">View All →</button>
          </div>
          <ul class="text-sm space-y-3">
            <?php foreach (array_slice($goalsList, 0, 2) as $g): ?>
              <li>
                <span class="font-medium"><?= $g["name"] ?>:</span> <?= $g["goal"] ?>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                  <div class="bg-indigo-600 h-2 rounded-full" style="width: <?= $g["progress"] ?>%"></div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Achievements Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 transition-transform hover:scale-[1.02]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
              <i data-lucide="award" class="w-5 h-5 text-green-600"></i> Achievements
            </h3>
            <button onclick="openTab('achievements-tab')" class="text-green-600 hover:bg-green-50 px-3 py-1 rounded-full text-sm font-medium transition">View All →</button>
          </div>
          <ul class="text-sm space-y-2">
            <?php foreach (array_slice($achievementsList, 0, 2) as $ach): ?>
              <li><?= $ach["name"] ?> - <span class="italic text-gray-600"><?= $ach["achievement"] ?></span></li>
            <?php endforeach; ?>
          </ul>
        </div>

      </div>

      <!-- Tab Section -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mt-8">
        <div class="flex border-b mb-4 space-x-6">
          <button id="reviews-btn" onclick="openTab('reviews-tab')" class="tab-btn text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-2">Reviews</button>
          <button id="attendance-btn" onclick="openTab('attendance-tab')" class="tab-btn text-gray-600 pb-2">Attendance</button>
          <button id="goals-btn" onclick="openTab('goals-tab')" class="tab-btn text-gray-600 pb-2">Goals</button>
          <button id="achievements-btn" onclick="openTab('achievements-tab')" class="tab-btn text-gray-600 pb-2">Achievements</button>
        </div>

        <!-- Reviews Tab -->
        <div id="reviews-tab" class="tab-content">
          <h3 class="text-lg font-semibold mb-3">Performance Reviews</h3>
          <table class="w-full text-sm border rounded-lg overflow-hidden">
            <thead>
              <tr class="bg-gray-100 text-gray-700">
                <th class="px-3 py-2 text-left">Name</th>
                <th class="px-3 py-2 text-left">Review Period</th>
                <th class="px-3 py-2 text-left">Rating</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reviewsList as $r): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-3 py-2"><?= $r["name"] ?></td>
                <td class="px-3 py-2"><?= $r["review"] ?></td>
                <td class="px-3 py-2">
                  <span class="px-2 py-0.5 rounded-full text-xs <?= $r["rating"] === 'Excellent' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' ?>">
                    <?= $r["rating"] ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Attendance Tab -->
        <div id="attendance-tab" class="tab-content hidden">
          <h3 class="text-lg font-semibold mb-3">Attendance Records</h3>
          <table class="w-full text-sm border rounded-lg overflow-hidden">
            <thead>
              <tr class="bg-gray-100 text-gray-700">
                <th class="px-3 py-2 text-left">Name</th>
                <th class="px-3 py-2 text-left">Late</th>
                <th class="px-3 py-2 text-left">Absent</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($attendanceList as $a): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-3 py-2"><?= $a["name"] ?></td>
                <td class="px-3 py-2"><?= $a["late"] ?></td>
                <td class="px-3 py-2"><?= $a["absent"] ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Goals Tab -->
        <div id="goals-tab" class="tab-content hidden">
          <h3 class="text-lg font-semibold mb-3">Employee Goals</h3>
          <div class="space-y-4">
            <?php foreach ($goalsList as $g): ?>
              <div>
                <p class="font-medium"><?= $g["name"] ?> - <?= $g["goal"] ?></p>
                <div class="w-full bg-gray-200 rounded-full h-3 mt-1">
                  <div class="bg-indigo-600 h-3 rounded-full" style="width: <?= $g["progress"] ?>%"></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Achievements Tab -->
        <div id="achievements-tab" class="tab-content hidden">
          <h3 class="text-lg font-semibold mb-3">Employee Achievements</h3>
          <ul class="space-y-2 text-sm">
            <?php foreach ($achievementsList as $ach): ?>
              <li class="p-3 border rounded-lg bg-gray-50 hover:bg-gray-100">
                <strong><?= $ach["name"] ?></strong> — <span class="italic text-gray-600"><?= $ach["achievement"] ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

      </div>

    </main>
  </div>
</div>

<script>
lucide.createIcons();

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
