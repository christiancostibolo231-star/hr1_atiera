<?php
// onboarding_dashboard.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../connections.php";
if (!isset($connections) || !($connections instanceof mysqli)) {
    die("Database connection error: check connections.php");
}

// ================= Sample Hired Employees =================
$hiredEmployees = [
  [
    "id" => 1,
    "name" => "Juan Dela Cruz",
    "position" => "Front Desk Officer",
    "contract_type" => "Full-time",
    "contract_signed" => false
  ],
  [
    "id" => 2,
    "name" => "Maria Santos",
    "position" => "Chef",
    "contract_type" => "Part-time",
    "contract_signed" => true
  ],
  [
    "id" => 3,
    "name" => "Pedro Ramirez",
    "position" => "Waiter",
    "contract_type" => "Contractual",
    "contract_signed" => false
  ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Onboarding Dashboard</title>
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
        <h2 class="text-2xl font-bold text-gray-800">Onboarding Dashboard</h2>
        <?php include 'profile.php'; ?>
      </div>
      <div><?php include 'admin_navbar.php'; ?></div>

      <!-- Checklist Submodule -->
      <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Checklist – Hired Employees</h3>
        
        <div class="overflow-x-auto">
          <table class="w-full border-collapse border border-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
              <tr>
                <th class="border border-gray-200 px-4 py-2 text-left">Name</th>
                <th class="border border-gray-200 px-4 py-2 text-left">Position</th>
                <th class="border border-gray-200 px-4 py-2 text-left">Contract Type</th>
                <th class="border border-gray-200 px-4 py-2 text-center">Contract Signing</th>
              </tr>
            </thead>
            <tbody id="checklistTable">
              <?php foreach ($hiredEmployees as $emp): ?>
              <tr data-id="<?= $emp['id'] ?>" class="hover:bg-gray-50">
                <td class="border border-gray-200 px-4 py-2 font-medium text-gray-800"><?= $emp["name"] ?></td>
                <td class="border border-gray-200 px-4 py-2"><?= $emp["position"] ?></td>
                <td class="border border-gray-200 px-4 py-2"><?= $emp["contract_type"] ?></td>
                <td class="border border-gray-200 px-4 py-2 text-center">
                  <?php if ($emp["contract_signed"]): ?>
                    <span class="text-green-600 font-medium">Signed ✅</span>
                  <?php else: ?>
                    <button onclick="openContractModal('<?= $emp['name'] ?>','<?= $emp['contract_type'] ?>')" 
                      class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm shadow">
                      Sign Contract
                    </button>
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

<!-- Contract Signing Modal -->
<div id="contractModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative max-h-[90vh] overflow-y-auto">
    <button onclick="closeContractModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black">
      <i data-lucide="x" class="w-6 h-6"></i>
    </button>

    <h3 id="contractTitle" class="text-xl font-bold text-gray-800 mb-4">Contract Agreement</h3>
    <p id="contractBody" class="text-gray-700 whitespace-pre-line text-sm leading-relaxed"></p>

    <!-- Action Buttons -->
    <div class="flex justify-end gap-3 mt-6">
      <button onclick="signContract()" 
        class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow">
        Sign Contract
      </button>
      <button onclick="closeContractModal()" 
        class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium px-4 py-2 rounded-lg shadow">
        Cancel
      </button>
    </div>
  </div>
</div>

<script>
  let currentEmployee = null;

  const contractTemplates = {
    "Full-time": `
This FULL-TIME EMPLOYMENT CONTRACT is entered into by and between:

[Company Name] (the "Employer") and [Employee Name] (the "Employee").

1. Position: Full-time employee with standard working hours.
2. Benefits: Includes full government benefits (SSS, Pag-IBIG, PhilHealth, etc.).
3. Compensation: Monthly salary based on company standards.
4. Duration: Continuous until terminated by either party.

Signed this day under company policy.
    `,
    "Part-time": `
This PART-TIME EMPLOYMENT CONTRACT is entered into by and between:

[Company Name] (the "Employer") and [Employee Name] (the "Employee").

1. Position: Part-time employee with reduced working hours.
2. Benefits: Limited benefits in accordance with company policy.
3. Compensation: Hourly or fixed-rate based on agreement.
4. Duration: Flexible, subject to schedule availability.

Signed this day under company policy.
    `,
    "Contractual": `
This CONTRACTUAL EMPLOYMENT AGREEMENT is entered into by and between:

[Company Name] (the "Employer") and [Employee Name] (the "Employee").

1. Position: Contract-based with defined project/task scope.
2. Benefits: Only those mandated by law.
3. Compensation: Fixed contract rate.
4. Duration: Contract valid until project completion or set term.

Signed this day under company policy.
    `
  };

  function openContractModal(name, type) {
    currentEmployee = { name, type };
    document.getElementById("contractTitle").textContent = `${type} Contract for ${name}`;
    document.getElementById("contractBody").textContent = contractTemplates[type].replace("[Employee Name]", name);
    document.getElementById("contractModal").classList.remove("hidden");
    lucide.createIcons();
  }

  function closeContractModal() {
    document.getElementById("contractModal").classList.add("hidden");
  }

  function signContract() {
    if (!currentEmployee) return;
    alert(`${currentEmployee.name} has signed the ${currentEmployee.type} contract!`);
    closeContractModal();

    // Update UI (mark as signed)
    const rows = document.querySelectorAll("#checklistTable tr");
    rows.forEach(row => {
      if (row.querySelector("td").innerText === currentEmployee.name) {
        row.querySelector("td:last-child").innerHTML = '<span class="text-green-600 font-medium">Signed ✅</span>';
      }
    });
  }
</script>

</body>
</html>
