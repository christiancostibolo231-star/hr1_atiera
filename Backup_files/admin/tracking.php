<?php
// applicant_progress_dashboard.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Applicant Progress Dashboard</title>
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
        <h2 class="text-2xl font-bold text-gray-800">Applicant Progress</h2>
        <?php include 'profile.php'; ?>
      </div>

      <?php include 'admin_navbar.php'; ?>

      <!-- Top Actions -->
      <div class="flex justify-end mt-6">
        <button onclick="openSummaryModal()" 
          class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow text-sm font-medium flex items-center space-x-2">
          <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
          <span>View Summary</span>
        </button>
      </div>

      <!-- Applicant Progress List -->
      <div class="mt-6">
        <div class="overflow-x-auto bg-white shadow rounded-lg border border-gray-200">
          <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-gray-700 text-xs uppercase font-semibold">
              <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Position</th>
                <th class="px-4 py-3">Progress</th>
                <th class="px-4 py-3 text-center">Action</th>
              </tr>
            </thead>
            <tbody id="progressTable" class="divide-y divide-gray-200">
              <!-- Filled by JS -->
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Applicant Progress Modal -->
<div id="progressModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-xl p-6 relative max-h-[90vh] overflow-y-auto">
    <button onclick="closeProgressModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
      <i data-lucide="x" class="w-6 h-6"></i>
    </button>

    <h3 id="modalApplicantName" class="text-xl font-bold text-gray-800 mb-4">Applicant Progress</h3>

    <!-- Timeline -->
    <div class="flex justify-between items-center mb-6 flex-wrap gap-2">
      <div id="timelineSteps" class="flex justify-between w-full text-xs font-medium text-gray-600"></div>
    </div>

    <!-- Progress Bar -->
    <div class="space-y-4 mb-6">
      <div class="flex items-center justify-between text-sm font-medium">
        <span id="progressLabel">Current Step: Applied</span>
        <span id="progressPercent">0%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-3">
        <div id="progressBar" class="bg-indigo-600 h-3 rounded-full" style="width: 0%;"></div>
      </div>
    </div>

    <!-- Update Dropdown -->
    <div class="mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">Update Step</label>
      <select id="updateStep" 
        class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        <option value="0">Applied</option>
        <option value="1">Reviewed</option>
        <option value="2">Interview</option>
        <option value="3">Offer</option>
        <option value="4">Hired</option>
      </select>
    </div>

    <!-- Buttons -->
    <div class="flex justify-end gap-3">
      <button onclick="saveProgress()" 
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow">
        Save Progress
      </button>
      <button onclick="closeProgressModal()" 
        class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium px-4 py-2 rounded-lg shadow">
        Close
      </button>
    </div>
  </div>
</div>

<!-- Summary Modal -->
<div id="summaryModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative max-h-[90vh] overflow-y-auto">
    <button onclick="closeSummaryModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
      <i data-lucide="x" class="w-6 h-6"></i>
    </button>

    <h3 class="text-xl font-bold text-gray-800 mb-6">Applicant Summary</h3>

    <div class="space-y-4 mb-6">
      <p class="text-gray-700"><strong>Total Applicants:</strong> <span id="totalApplicants"></span></p>
      <p class="text-green-700"><strong>Hired:</strong> <span id="totalHired"></span></p>
      <p class="text-indigo-700"><strong>In Progress:</strong> <span id="totalProgress"></span></p>
    </div>

    <!-- Chart -->
    <div class="w-full">
      <canvas id="summaryChart" class="max-h-[250px] w-full"></canvas>
    </div>
  </div>
</div>

<script>
  const steps = ["Applied", "Reviewed", "Interview", "Offer", "Hired"];

  // Only first 3 applicants
  const applicants = [
    { id: 1, name: "Juan Dela Cruz", position: "Front Desk", step: 0 },
    { id: 2, name: "Maria Santos", position: "Chef", step: 2 },
    { id: 3, name: "Pedro Reyes", position: "Waiter", step: 4 }
  ];

  let currentApplicant = null;
  let summaryChart = null;
  const progressTable = document.getElementById("progressTable");

  function renderProgressList() {
    progressTable.innerHTML = "";
    applicants.forEach(applicant => {
      const percent = (applicant.step / (steps.length - 1)) * 100;

      const row = document.createElement("tr");
      row.className = "hover:bg-gray-50";
      row.innerHTML = `
        <td class="px-4 py-3 font-medium text-gray-800">${applicant.name}</td>
        <td class="px-4 py-3">${applicant.position}</td>
        <td class="px-4 py-3">
          <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
            <div class="h-2 rounded-full ${percent === 100 ? "bg-green-600" : "bg-indigo-600"}" style="width:${percent}%;"></div>
          </div>
          <span class="text-xs text-gray-600">${steps[applicant.step]}</span>
        </td>
        <td class="px-4 py-3 text-center">
          <button onclick="openProgressModal(${applicant.id})" class="text-indigo-600 hover:underline text-sm">Update</button>
        </td>
      `;
      progressTable.appendChild(row);
    });
  }

  function openProgressModal(id) {
    currentApplicant = applicants.find(a => a.id === id);
    if (!currentApplicant) return;

    document.getElementById("modalApplicantName").textContent = currentApplicant.name;
    document.getElementById("updateStep").value = currentApplicant.step;
    updateModalProgress(currentApplicant.step);
    renderTimeline(currentApplicant.step);
    document.getElementById("progressModal").classList.remove("hidden");
  }

  function renderTimeline(step) {
    const timeline = document.getElementById("timelineSteps");
    timeline.innerHTML = steps.map((s, i) => `
      <div class="flex-1 text-center ${i <= step ? 'text-indigo-600 font-bold' : 'text-gray-400'}">
        <div class="w-6 h-6 mx-auto rounded-full border-2 ${i <= step ? 'border-indigo-600 bg-indigo-100' : 'border-gray-300'} flex items-center justify-center">
          ${i < step ? '<i data-lucide="check" class="w-3 h-3"></i>' : i === step ? i+1 : ''}
        </div>
        <div class="mt-1">${s}</div>
      </div>
    `).join("");
    lucide.createIcons();
  }

  function updateModalProgress(step) {
    const percent = (step / (steps.length - 1)) * 100;
    document.getElementById("progressBar").style.width = percent + "%";
    document.getElementById("progressPercent").textContent = percent + "%";
    document.getElementById("progressLabel").textContent = "Current Step: " + steps[step];
  }

  function saveProgress() {
    if (!currentApplicant) return;
    const newStep = parseInt(document.getElementById("updateStep").value);
    currentApplicant.step = newStep;
    updateModalProgress(newStep);
    renderTimeline(newStep);
    renderProgressList();
    closeProgressModal();
  }

  function openSummaryModal() {
    const total = applicants.length;
    const hired = applicants.filter(a => a.step === steps.length - 1).length;
    const progress = total - hired;

    document.getElementById("totalApplicants").textContent = total;
    document.getElementById("totalHired").textContent = hired;
    document.getElementById("totalProgress").textContent = progress;

    const ctx = document.getElementById("summaryChart").getContext("2d");
    if (summaryChart) summaryChart.destroy();
    summaryChart = new Chart(ctx, {
      type: "pie",
      data: {
        labels: ["Hired", "In Progress"],
        datasets: [{
          data: [hired, progress],
          backgroundColor: ["#16a34a", "#4f46e5"]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: "bottom" }
        }
      }
    });

    document.getElementById("summaryModal").classList.remove("hidden");
  }

  function closeProgressModal() {
    document.getElementById("progressModal").classList.add("hidden");
  }

  function closeSummaryModal() {
    document.getElementById("summaryModal").classList.add("hidden");
  }

  document.addEventListener("DOMContentLoaded", () => {
    renderProgressList();
    lucide.createIcons();
    document.getElementById("updateStep").addEventListener("change", (e) => {
      updateModalProgress(parseInt(e.target.value));
      renderTimeline(parseInt(e.target.value));
    });
  });
</script>

</body>
</html>
