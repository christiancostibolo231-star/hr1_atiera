<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . "/../connections.php";
session_start();

if (!isset($connections) || !($connections instanceof mysqli)) {
    die("Database connection error: check connections.php");
}

// Replace this with your session user id or manager name
$manager_id = $_SESSION['user_id'] ?? 1;

/* ---------------------------
   ADD NEW JOB REQUEST
---------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_job'])) {
    $title = trim($_POST['title'] ?? '');
    $about_us = trim($_POST['about_us'] ?? '');
    $responsibilities = trim($_POST['responsibilities'] ?? '');
    $qualifications = trim($_POST['qualifications'] ?? '');
    $benefits = trim($_POST['benefits'] ?? '');
    $department_id = $_POST['department_id'] ?? null;
    $new_department = trim($_POST['new_department'] ?? '');
    $employment_type = $_POST['employment_type'] ?? 'Full-time';
    $vacancies = intval($_POST['vacancies'] ?? 1);

    // Combine sections into one description
    $description = 
        "About Us:\n$about_us\n\n" .
        "Responsibilities:\n$responsibilities\n\n" .
        "Qualifications:\n$qualifications\n\n" .
        "Benefits:\n$benefits";

    // Handle new department
    if ($new_department !== '') {
        $checkDept = $connections->prepare("SELECT department_id FROM departments WHERE name=? LIMIT 1");
        $checkDept->bind_param("s", $new_department);
        $checkDept->execute();
        $res = $checkDept->get_result();

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $department_id = $row['department_id'];
        } else {
            $insDept = $connections->prepare("INSERT INTO departments (name) VALUES (?)");
            $insDept->bind_param("s", $new_department);
            $insDept->execute();
            $department_id = $connections->insert_id;
        }
    }

    // Insert into jobs
    $stmt = $connections->prepare("
        INSERT INTO jobs (title, description, department_id, employment_type, status, vacancies, created_by) 
        VALUES (?, ?, ?, ?, 'Pending HR Approval', ?, ?)
    ");
    $stmt->bind_param("ssisis", $title, $description, $department_id, $employment_type, $vacancies, $manager_id);
    $stmt->execute();
}

/* ---------------------------
   ADD NEW REQUISITION
---------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_request'])) {
    $job_id = $_POST['job_id'];
    $open_date = $_POST['open_date'] ?: date('Y-m-d');
    $stmt = $connections->prepare("
        INSERT INTO job_requisitions (job_id, requested_by, approval_status, open_date)
        VALUES (?, ?, 'Pending', ?)
    ");
    $stmt->bind_param("iis", $job_id, $manager_id, $open_date);
    $stmt->execute();
}

/* ---------------------------
   FETCH JOBS & DEPARTMENTS
---------------------------- */
$jobStmt = $connections->prepare("SELECT job_id, title FROM jobs ORDER BY title ASC");
$jobStmt->execute();
$jobsList = $jobStmt->get_result();

$deptStmt = $connections->prepare("SELECT department_id, name FROM departments ORDER BY name ASC");
$deptStmt->execute();
$departments = $deptStmt->get_result();

/* ---------------------------
   FETCH MANAGER REQUESTS
---------------------------- */
$stmt = $connections->prepare("
    SELECT 
        r.requisition_id,
        j.title AS job_title,
        r.approval_status,
        r.open_date,
        r.close_date
    FROM job_requisitions r
    LEFT JOIN jobs j ON r.job_id = j.job_id
    WHERE r.requested_by = ?
    ORDER BY r.requisition_id DESC
");
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$requests = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Recruitment Requests</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
document.addEventListener("DOMContentLoaded", () => lucide.createIcons());
function toggleModal(id){ document.getElementById(id).classList.toggle("hidden"); }
function openJobModal(){ document.getElementById('jobModal').classList.remove('hidden'); }
function closeJobModal(){ document.getElementById('jobModal').classList.add('hidden'); }
function toggleNewDepartmentInput() {
  const deptSelect = document.getElementById("departmentSelect");
  const newDeptInput = document.getElementById("newDepartmentInput");
  if (deptSelect.value === "new") {
      newDeptInput.classList.remove("hidden");
      newDeptInput.querySelector("input").required = true;
  } else {
      newDeptInput.classList.add("hidden");
      newDeptInput.querySelector("input").required = false;
  }
}
</script>
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
        <h2 class="text-2xl font-bold text-gray-800">Recruitment Requests</h2>
        <?php include 'profile.php'; ?>
      </div>
      <div><?php include 'admin_navbar.php'; ?></div>

      <!-- Controls -->
      <div class="flex justify-between items-center mb-4">
        <div class="flex gap-3">
          <button onclick="toggleModal('addRequest')" 
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
            <i data-lucide='plus'></i> New Recruitment Request
          </button>
          <button onclick="openJobModal()" 
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
            <i data-lucide='file-plus'></i> Create New Job
          </button>
        </div>
      </div>

      <!-- Requests Table -->
      <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full border border-gray-200 rounded-lg">
          <thead class="bg-gray-800 text-white text-sm">
            <tr>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Job Title</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2">Open Date</th>
              <th class="px-4 py-2">Close Date</th>
            </tr>
          </thead>
          <tbody class="text-sm divide-y divide-gray-200">
            <?php if ($requests && $requests->num_rows > 0): ?>
              <?php while ($req = $requests->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-2"><?= htmlspecialchars($req['requisition_id']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($req['job_title'] ?? 'N/A') ?></td>
                  <td class="px-4 py-2">
                    <span class="px-2 py-1 rounded text-xs font-medium 
                      <?= $req['approval_status'] === 'Approved' ? 'bg-green-100 text-green-700' : 
                          ($req['approval_status'] === 'Rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') ?>">
                      <?= htmlspecialchars($req['approval_status']) ?>
                    </span>
                  </td>
                  <td class="px-4 py-2"><?= htmlspecialchars($req['open_date'] ?? '-') ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($req['close_date'] ?? '-') ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="px-4 py-4 text-center text-gray-500">No recruitment requests found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Add Request Modal -->
      <div id="addRequest" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
          <h2 class="text-xl font-semibold mb-4">New Recruitment Request</h2>
          <form method="POST" class="space-y-4">
            <div>
              <label class="block text-sm font-medium">Job Title</label>
              <select name="job_id" required class="w-full p-2 border rounded-lg">
                <option value="">Select Job</option>
                <?php mysqli_data_seek($jobsList, 0); while ($job = $jobsList->fetch_assoc()): ?>
                  <option value="<?= $job['job_id'] ?>"><?= htmlspecialchars($job['title']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium">Open Date</label>
              <input type="date" name="open_date" value="<?= date('Y-m-d') ?>" class="w-full p-2 border rounded-lg">
            </div>

            <div class="flex justify-end gap-2">
              <button type="button" onclick="toggleModal('addRequest')" class="px-4 py-2 border rounded-lg">Cancel</button>
              <button type="submit" name="add_request" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Submit</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Job Creation Modal -->
      <div id="jobModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8 relative overflow-y-auto max-h-[90vh] border border-gray-200">
          <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="text-xl font-bold text-gray-800">🧾 Job Request Form</h2>
            <button onclick="closeJobModal()" class="text-gray-500 hover:text-red-600 text-2xl">&times;</button>
          </div>

          <form method="POST" class="space-y-6">
            <input type="hidden" name="add_job" value="1">

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Job Title</label>
              <input type="text" name="title" required class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">About Department / Purpose</label>
              <textarea name="about_us" rows="2" placeholder="Describe department or why this role is needed" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"></textarea>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Responsibilities</label>
              <textarea name="responsibilities" rows="3" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"></textarea>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Qualifications</label>
              <textarea name="qualifications" rows="3" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"></textarea>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Benefits (Optional)</label>
              <textarea name="benefits" rows="2" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"></textarea>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Department</label>
              <select name="department_id" id="departmentSelect" onchange="toggleNewDepartmentInput()" 
                class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                <option value="">-- Select Department --</option>
                <?php while ($dept = $departments->fetch_assoc()): ?>
                  <option value="<?= $dept['department_id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                <?php endwhile; ?>
                <option value="new">+ Add New Department</option>
              </select>
              <div id="newDepartmentInput" class="hidden mt-3">
                <input type="text" name="new_department" placeholder="Enter new department name" 
                  class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Employment Type</label>
              <select name="employment_type" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                <option value="Full-time">Full-time</option>
                <option value="Part-time">Part-time</option>
                <option value="Contract">Contract</option>
                <option value="Seasonal">Seasonal</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1">Number of Vacancies</label>
              <input type="number" name="vacancies" min="1" value="1" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
              <button type="button" onclick="closeJobModal()" class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">Cancel</button>
              <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium shadow">📤 Submit Request</button>
            </div>
          </form>
        </div>
      </div>

    </main>
  </div>
</div>

</body>
</html>
