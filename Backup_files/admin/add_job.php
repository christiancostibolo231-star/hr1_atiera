<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../connections.php";
if (!isset($connections) || !($connections instanceof mysqli)) {
    die("Database connection error: check connections.php");
}

/* ---------------------------
   ADD JOB POSTING
---------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_job'])) {
    $title = trim($_POST['title'] ?? '');
    $about_us = trim($_POST['about_us'] ?? '');
    $responsibilities = trim($_POST['responsibilities'] ?? '');
    $qualifications = trim($_POST['qualifications'] ?? '');
    $benefits = trim($_POST['benefits'] ?? '');
    $how_to_apply = trim($_POST['how_to_apply'] ?? '');

    // Combine all sections into one description text
    $description = 
        "About Us:\n$about_us\n\n" .
        "Responsibilities:\n$responsibilities\n\n" .
        "Qualifications:\n$qualifications\n\n" .
        "Benefits:\n$benefits\n\n" .
        "How to Apply:\n$how_to_apply";

    $department_id = $_POST['department_id'] ?? null;
    $new_department = trim($_POST['new_department'] ?? '');
    $employment_type = $_POST['employment_type'] ?? 'Full-time';
    $status = $_POST['status'] ?? 'Open';
    $vacancies = intval($_POST['vacancies'] ?? 1);

    // If new department is provided
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
        VALUES (?, ?, ?, ?, ?, ?, 1)
    ");
    $stmt->bind_param("ssissi", $title, $description, $department_id, $employment_type, $status, $vacancies);
    $stmt->execute();

    header("Location: jobposting.php?success=1");
    exit;
}

/* ---------------------------
   FETCH DEPARTMENTS
---------------------------- */
$deptStmt = $connections->prepare("SELECT department_id, name FROM departments ORDER BY name ASC");
$deptStmt->execute();
$departments = $deptStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Job Posting</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
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
    <main class="p-8 space-y-6">

      <!-- Header -->
      <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">➕ Add Job Posting</h2>
        <?php include 'profile.php'; ?>
      </div>
      <div><?php include 'admin_navbar.php' ?></div>

      <!-- Form Card -->
      <div class="bg-white shadow-xl rounded-2xl p-8 max-w-3xl mx-auto border border-gray-100">
        <form method="POST" class="space-y-6">

          <!-- Job Title -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Job Title</label>
            <input type="text" name="title" required 
              class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
          </div>

          <!-- Job Description Sections -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">About Us</label>
            <textarea name="about_us" rows="3" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"></textarea>
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
            <label class="block text-sm font-semibold text-gray-700 mb-1">Benefits</label>
            <textarea name="benefits" rows="3" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"></textarea>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">How to Apply</label>
            <textarea name="how_to_apply" rows="3" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"></textarea>
          </div>

          <!-- Department -->
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

          <!-- Employment Type -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Employment Type</label>
            <select name="employment_type" 
              class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
              <option value="Full-time">Full-time</option>
              <option value="Part-time">Part-time</option>
              <option value="Contract">Contract</option>
            </select>
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
            <select name="status" 
              class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
              <option value="Open">Open</option>
              <option value="Closed">Closed</option>
            </select>
          </div>

          <!-- Vacancies -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Vacancies</label>
            <input type="number" name="vacancies" min="1" value="1" 
              class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
          </div>

          <!-- Buttons -->
          <div class="flex justify-end gap-3 pt-4">
            <a href="jobposting.php" 
              class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">Cancel</a>
            <button type="submit" name="add_job" 
              class="px-6 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium shadow hover:shadow-lg transform hover:-translate-y-0.5 transition">
              💾 Save Job
            </button>
          </div>

        </form>
      </div>

    </main>
  </div>
</div>

</body>
</html>
