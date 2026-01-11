<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../connections.php";
if (!isset($connections) || !($connections instanceof mysqli)) {
    die("Database connection error: check connections.php");
}

// ================= Sample Employee Data =================
$documents = [
  [
    "id" => 1,
    "name" => "Juan Dela Cruz",
    "position" => "Front Desk Officer",
    "sss" => true,
    "pagibig" => false,
    "philhealth" => true,
    "tin" => true,
    "resume" => true,
    "nbi" => false,
    "contract" => false
  ],
  [
    "id" => 2,
    "name" => "Maria Santos",
    "position" => "Chef",
    "sss" => true,
    "pagibig" => true,
    "philhealth" => false,
    "tin" => true,
    "resume" => true,
    "nbi" => true,
    "contract" => true
  ],
  [
    "id" => 3,
    "name" => "Pedro Ramirez",
    "position" => "Waiter",
    "sss" => false,
    "pagibig" => false,
    "philhealth" => false,
    "tin" => false,
    "resume" => true,
    "nbi" => false,
    "contract" => false
  ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Documents Tracking Dashboard</title>
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
        <h2 class="text-2xl font-bold text-gray-800">Documents Tracking</h2>
        <?php include 'profile.php'; ?>
      </div>
      <div><?php include 'admin_navbar.php'; ?></div>

      <!-- Button to open summary modal -->
      <button onclick="openSummaryModal()" class="mt-6 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        View Summary
      </button>

      <!-- Applicants Documents List -->
      <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Employee Document Status</h3>
        
        <div class="overflow-x-auto">
          <table class="w-full border-collapse border border-gray-200 text-sm">
            <thead class="bg-gray-100">
              <tr>
                <th class="border border-gray-200 px-4 py-2 text-left">Name</th>
                <th class="border border-gray-200 px-4 py-2 text-left">Position</th>
                <th class="border border-gray-200 px-4 py-2">SSS</th>
                <th class="border border-gray-200 px-4 py-2">Pag-IBIG</th>
                <th class="border border-gray-200 px-4 py-2">PhilHealth</th>
                <th class="border border-gray-200 px-4 py-2">TIN</th>
                <th class="border border-gray-200 px-4 py-2">Resume</th>
                <th class="border border-gray-200 px-4 py-2">NBI</th>
                <th class="border border-gray-200 px-4 py-2">Contract</th>
              </tr>
            </thead>
            <tbody id="docsTable">
              <?php foreach ($documents as $doc): ?>
              <tr data-id="<?= $doc['id'] ?>" class="hover:bg-gray-50">
                <td class="border border-gray-200 px-4 py-2 font-medium text-gray-800"><?= $doc["name"] ?></td>
                <td class="border border-gray-200 px-4 py-2 text-gray-600"><?= $doc["position"] ?></td>
                <?php foreach (["sss","pagibig","philhealth","tin","resume","nbi","contract"] as $field): ?>
                <td class="border border-gray-200 px-4 py-2 text-center">
                  <input type="checkbox" class="doc-checkbox" data-field="<?= $field ?>" <?= $doc[$field] ? "checked" : "" ?>>
                </td>
                <?php endforeach; ?>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>

<!-- Summary Modal -->
<div id="summaryModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
  <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-lg p-6 relative">
    <button onclick="closeSummaryModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
      <i data-lucide="x" class="w-6 h-6"></i>
    </button>
    <h3 class="text-xl font-bold text-gray-800 mb-4">Documents Summary</h3>
    <div id="summaryContent" class="space-y-2 text-gray-700 mb-4"></div>
    <div class="w-full h-64">
      <canvas id="summaryChart"></canvas>
    </div>
  </div>
</div>

<script>
let summaryChart = null;

document.addEventListener("DOMContentLoaded", () => {
  lucide.createIcons();

  // Update checkbox status (simulate CRUD)
  document.querySelectorAll(".doc-checkbox").forEach(cb => {
    cb.addEventListener("change", function() {
      const row = this.closest("tr");
      const name = row.querySelector("td").innerText;
      const field = this.dataset.field;
      const status = this.checked ? "Submitted ✅" : "Missing ❌";
      console.log(`${name} updated: ${field} -> ${status}`);
      // Later: send AJAX request to PHP/MySQL here
    });
  });
});

// Modal functions
function openSummaryModal() {
  const rows = document.querySelectorAll("#docsTable tr");
  let total = rows.length;
  let fullyComplete = 0;

  rows.forEach(row => {
    const checkboxes = row.querySelectorAll(".doc-checkbox");
    if ([...checkboxes].every(cb => cb.checked)) {
      fullyComplete++;
    }
  });

  let pending = total - fullyComplete;

  document.getElementById("summaryContent").innerHTML = `
    <p><strong>Total Employees:</strong> ${total}</p>
    <p><strong>Completed Documents:</strong> ${fullyComplete}</p>
    <p><strong>Pending:</strong> ${pending}</p>
  `;

  const ctx = document.getElementById("summaryChart").getContext("2d");
  if (summaryChart) summaryChart.destroy();

  summaryChart = new Chart(ctx, {
    type: "pie",
    data: {
      labels: ["Completed", "Pending"],
      datasets: [{
        data: [fullyComplete, pending],
        backgroundColor: ["#16a34a", "#ef4444"]
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: "bottom" }
      }
    }
  });

  document.getElementById("summaryModal").classList.remove("hidden");
}

function closeSummaryModal() {
  document.getElementById("summaryModal").classList.add("hidden");
}
</script>

</body>
</html>
