<?php
// orientation_dashboard.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../connections.php";
if (!isset($connections) || !($connections instanceof mysqli)) {
    die("Database connection error: check connections.php");
}

// ================= Sample Hired Applicants for Orientation =================
$orientationList = [
  [
    "id" => 1,
    "name" => "Juan Dela Cruz",
    "department" => "Front Office",
    "orientation_date" => "2025-09-20",
    "venue" => "Conference Room A",
    "status" => "Pending"
  ],
  [
    "id" => 2,
    "name" => "Maria Santos",
    "department" => "Kitchen",
    "orientation_date" => "2025-09-22",
    "venue" => "Hotel Kitchen",
    "status" => "Scheduled"
  ],
  [
    "id" => 3,
    "name" => "Pedro Ramirez",
    "department" => "Food & Beverage",
    "orientation_date" => "2025-09-25",
    "venue" => "Banquet Hall",
    "status" => "Pending"
  ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Orientation Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
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
        <h2 class="text-2xl font-bold text-gray-800">Orientation Dashboard</h2>
        <?php include 'profile.php'; ?>
      </div>
      <div><?php include 'admin_navbar.php'; ?></div>

      <!-- Orientation List -->
      <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Hired Applicants for Orientation</h3>
        
        <div class="overflow-x-auto">
          <table class="w-full border-collapse border border-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
              <tr>
                <th class="border border-gray-200 px-4 py-2 text-left">Name</th>
                <th class="border border-gray-200 px-4 py-2 text-left">Department</th>
                <th class="border border-gray-200 px-4 py-2 text-left">Orientation Date</th>
                <th class="border border-gray-200 px-4 py-2 text-left">Venue</th>
                <th class="border border-gray-200 px-4 py-2 text-center">Status</th>
                <th class="border border-gray-200 px-4 py-2 text-center">Action</th>
              </tr>
            </thead>
            <tbody id="orientationTable">
              <?php foreach ($orientationList as $emp): ?>
              <tr data-id="<?= $emp['id'] ?>" class="hover:bg-gray-50">
                <td class="border border-gray-200 px-4 py-2 font-medium text-gray-800"><?= $emp["name"] ?></td>
                <td class="border border-gray-200 px-4 py-2"><?= $emp["department"] ?></td>
                <td class="border border-gray-200 px-4 py-2"><?= $emp["orientation_date"] ?></td>
                <td class="border border-gray-200 px-4 py-2"><?= $emp["venue"] ?></td>
                <td class="border border-gray-200 px-4 py-2 text-center status-cell">
                  <span class="<?= $emp["status"] === "Completed" ? 'text-green-600' : 'text-yellow-600' ?> font-medium">
                    <?= $emp["status"] ?>
                  </span>
                </td>
                <td class="border border-gray-200 px-4 py-2 text-center">
                  <?php if ($emp["status"] !== "Completed"): ?>
                    <button onclick="markCompleted(this)" 
                      class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm shadow">
                      Mark Completed
                    </button>
                  <?php else: ?>
                    <span class="text-green-600 font-medium">Done ✅</span>
                  <?php endif; ?>
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
function markCompleted(button) {
  const row = button.closest("tr");
  const statusCell = row.querySelector(".status-cell span");
  statusCell.textContent = "Completed";
  statusCell.className = "text-green-600 font-medium";
  button.parentElement.innerHTML = '<span class="text-green-600 font-medium">Done ✅</span>';
}
</script>

</body>
</html>
