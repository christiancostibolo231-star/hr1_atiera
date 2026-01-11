<?php
// compliance_dashboard.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../connections.php";
if (!isset($connections) || !($connections instanceof mysqli)) {
    die("Database connection error: check connections.php");
}

// ================= Sample Compliance Data =================
$orientationList = [
  ["id" => 1, "name" => "Juan Dela Cruz", "department" => "Front Office", "date" => "2025-09-20", "venue" => "Conference Room A", "status" => "Pending"],
  ["id" => 2, "name" => "Maria Santos", "department" => "Kitchen", "date" => "2025-09-22", "venue" => "Hotel Kitchen", "status" => "Scheduled"],
];

$contractsList = [
  ["id" => 1, "name" => "Juan Dela Cruz", "type" => "Full-time", "signed" => false],
  ["id" => 2, "name" => "Maria Santos", "type" => "Part-time", "signed" => true],
];

$policiesList = [
  ["id" => 1, "name" => "Juan Dela Cruz", "policy" => "Employee Handbook", "acknowledged" => false],
  ["id" => 2, "name" => "Maria Santos", "policy" => "Safety Guidelines", "acknowledged" => true],
];

$trainingsList = [
  ["id" => 1, "name" => "Juan Dela Cruz", "training" => "Basic Safety Training", "completed" => false],
  ["id" => 2, "name" => "Maria Santos", "training" => "Food Handling Training", "completed" => true],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Compliance Dashboard</title>
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
          <i data-lucide="shield-check" class="w-7 h-7 text-indigo-600"></i>
          Compliance Dashboard
        </h2>
        <?php include 'profile.php'; ?>
      </div>
      <div><?php include 'admin_navbar.php'; ?></div>

      <!-- Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Orientation Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 transition-transform hover:scale-[1.02]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
              <i data-lucide="users" class="w-5 h-5 text-indigo-600"></i> Orientation
            </h3>
            <button onclick="openTab('orientation-tab')" class="text-indigo-600 hover:bg-indigo-50 px-3 py-1 rounded-full text-sm font-medium transition">View All →</button>
          </div>
          <ul class="text-sm space-y-2">
            <?php foreach (array_slice($orientationList, 0, 2) as $emp): ?>
              <li class="flex justify-between">
                <span><?= $emp["name"] ?> (<?= $emp["department"] ?>)</span>
                <span class="px-2 py-0.5 rounded-full text-xs <?= $emp["status"] === 'Pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' ?>">
                  <?= $emp["status"] ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Contracts Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 transition-transform hover:scale-[1.02]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
              <i data-lucide="file-text" class="w-5 h-5 text-green-600"></i> Contracts
            </h3>
            <button onclick="openTab('contracts-tab')" class="text-green-600 hover:bg-green-50 px-3 py-1 rounded-full text-sm font-medium transition">View All →</button>
          </div>
          <ul class="text-sm space-y-2">
            <?php foreach (array_slice($contractsList, 0, 2) as $c): ?>
              <li class="flex justify-between">
                <span><?= $c["name"] ?> (<?= $c["type"] ?>)</span>
                <span class="px-2 py-0.5 rounded-full text-xs <?= $c["signed"] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                  <?= $c["signed"] ? "Signed" : "Pending" ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Policies Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 transition-transform hover:scale-[1.02]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
              <i data-lucide="book-open" class="w-5 h-5 text-blue-600"></i> Policies
            </h3>
            <button onclick="openTab('policies-tab')" class="text-blue-600 hover:bg-blue-50 px-3 py-1 rounded-full text-sm font-medium transition">View All →</button>
          </div>
          <ul class="text-sm space-y-2">
            <?php foreach (array_slice($policiesList, 0, 2) as $p): ?>
              <li class="flex justify-between">
                <span><?= $p["name"] ?> - <?= $p["policy"] ?></span>
                <span class="px-2 py-0.5 rounded-full text-xs <?= $p["acknowledged"] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                  <?= $p["acknowledged"] ? "Yes" : "No" ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Training Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 transition-transform hover:scale-[1.02]">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
              <i data-lucide="graduation-cap" class="w-5 h-5 text-purple-600"></i> Training
            </h3>
            <button onclick="openTab('training-tab')" class="text-purple-600 hover:bg-purple-50 px-3 py-1 rounded-full text-sm font-medium transition">View All →</button>
          </div>
          <ul class="text-sm space-y-2">
            <?php foreach (array_slice($trainingsList, 0, 2) as $t): ?>
              <li class="flex justify-between">
                <span><?= $t["name"] ?> - <?= $t["training"] ?></span>
                <span class="px-2 py-0.5 rounded-full text-xs <?= $t["completed"] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                  <?= $t["completed"] ? "Done" : "Pending" ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

      </div>

      <!-- Tab Section -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mt-8">
        <div class="flex border-b mb-4 space-x-6">
          <button id="orientation-btn" onclick="openTab('orientation-tab')" class="tab-btn text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-2">Orientation</button>
          <button id="contracts-btn" onclick="openTab('contracts-tab')" class="tab-btn text-gray-600 pb-2">Contracts</button>
          <button id="policies-btn" onclick="openTab('policies-tab')" class="tab-btn text-gray-600 pb-2">Policies</button>
          <button id="training-btn" onclick="openTab('training-tab')" class="tab-btn text-gray-600 pb-2">Training</button>
        </div>

        <!-- Orientation Tab -->
        <div id="orientation-tab" class="tab-content">
          <h3 class="text-lg font-semibold mb-3">Orientation List</h3>
          <table class="w-full text-sm border rounded-lg overflow-hidden">
            <thead>
              <tr class="bg-gray-100 text-gray-700">
                <th class="px-3 py-2 text-left">Name</th>
                <th class="px-3 py-2 text-left">Department</th>
                <th class="px-3 py-2 text-left">Date</th>
                <th class="px-3 py-2 text-left">Venue</th>
                <th class="px-3 py-2 text-left">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orientationList as $o): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-3 py-2"><?= $o["name"] ?></td>
                <td class="px-3 py-2"><?= $o["department"] ?></td>
                <td class="px-3 py-2"><?= $o["date"] ?></td>
                <td class="px-3 py-2"><?= $o["venue"] ?></td>
                <td class="px-3 py-2">
                  <span class="px-2 py-0.5 rounded-full text-xs <?= $o["status"] === 'Pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' ?>">
                    <?= $o["status"] ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Contracts Tab -->
        <div id="contracts-tab" class="tab-content hidden">
          <h3 class="text-lg font-semibold mb-3">Contracts List</h3>
          <table class="w-full text-sm border rounded-lg overflow-hidden">
            <thead>
              <tr class="bg-gray-100 text-gray-700">
                <th class="px-3 py-2 text-left">Name</th>
                <th class="px-3 py-2 text-left">Type</th>
                <th class="px-3 py-2 text-left">Signed</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($contractsList as $c): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-3 py-2"><?= $c["name"] ?></td>
                <td class="px-3 py-2"><?= $c["type"] ?></td>
                <td class="px-3 py-2">
                  <span class="px-2 py-0.5 rounded-full text-xs <?= $c["signed"] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                    <?= $c["signed"] ? "Yes" : "No" ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Policies Tab -->
        <div id="policies-tab" class="tab-content hidden">
          <h3 class="text-lg font-semibold mb-3">Policies List</h3>
          <table class="w-full text-sm border rounded-lg overflow-hidden">
            <thead>
              <tr class="bg-gray-100 text-gray-700">
                <th class="px-3 py-2 text-left">Name</th>
                <th class="px-3 py-2 text-left">Policy</th>
                <th class="px-3 py-2 text-left">Acknowledged</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($policiesList as $p): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-3 py-2"><?= $p["name"] ?></td>
                <td class="px-3 py-2"><?= $p["policy"] ?></td>
                <td class="px-3 py-2">
                  <span class="px-2 py-0.5 rounded-full text-xs <?= $p["acknowledged"] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                    <?= $p["acknowledged"] ? "Yes" : "No" ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Training Tab -->
        <div id="training-tab" class="tab-content hidden">
          <h3 class="text-lg font-semibold mb-3">Training List</h3>
          <table class="w-full text-sm border rounded-lg overflow-hidden">
            <thead>
              <tr class="bg-gray-100 text-gray-700">
                <th class="px-3 py-2 text-left">Name</th>
                <th class="px-3 py-2 text-left">Training</th>
                <th class="px-3 py-2 text-left">Completed</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($trainingsList as $t): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-3 py-2"><?= $t["name"] ?></td>
                <td class="px-3 py-2"><?= $t["training"] ?></td>
                <td class="px-3 py-2">
                  <span class="px-2 py-0.5 rounded-full text-xs <?= $t["completed"] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                    <?= $t["completed"] ? "Yes" : "No" ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
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
