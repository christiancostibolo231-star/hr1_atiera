<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../connections.php";

// ✅ Use the recruitment database explicitly
if (!isset($hr1_recruitment) || !($hr1_recruitment instanceof mysqli)) {
    die("Database connection error: recruitment DB not available");
}

/* ---------------------------
   EDIT JOB
---------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_job'])) {
    $job_id = $_POST['job_id'];
    $employment_type = $_POST['employment_type'];
    $status = $_POST['status'];
    $vacancies = $_POST['vacancies'];
    $description = trim($_POST['description']);

    $stmt = $hr1_recruitment->prepare("
        UPDATE jobs 
        SET employment_type=?, status=?, vacancies=?, description=? 
        WHERE job_id=?
    ");
    $stmt->bind_param("ssisi", $employment_type, $status, $vacancies, $description, $job_id);
    $stmt->execute();
    $stmt->close();
}

/* ---------------------------
   DELETE JOB
---------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_job'])) {
    $job_id = $_POST['job_id'];
    $stmt = $hr1_recruitment->prepare("DELETE FROM jobs WHERE job_id=?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $stmt->close();
}

/* ---------------------------
   FETCH JOB DEPARTMENTS
---------------------------- */
$deptStmt = $hr1_recruitment->prepare("
    SELECT department_id, department_name 
    FROM job_departments 
    ORDER BY department_name ASC
");
$deptStmt->execute();
$departments = $deptStmt->get_result();
$deptStmt->close();

/* ---------------------------
   SORTING
---------------------------- */
$sort = $_GET['sort'] ?? 'created_at';
$allowedSort = ['job_title', 'department_name', 'status', 'created_at'];
if (!in_array($sort, $allowedSort)) $sort = 'created_at';

/* ---------------------------
   FETCH JOBS (JOIN UPDATED)
---------------------------- */
$query = "
    SELECT 
        j.job_id, 
        j.job_title, 
        j.description,
        j.employment_type,
        d.department_name AS department_name, 
        j.status, 
        j.vacancies, 
        j.filled_positions, 
        j.created_at 
    FROM jobs j
    LEFT JOIN job_departments d ON j.department_id = d.department_id
    ORDER BY $sort DESC
";

$stmt = $hr1_recruitment->prepare($query);
$stmt->execute();
$jobs = $stmt->get_result();
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Job Postings</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
document.addEventListener("DOMContentLoaded", () => lucide.createIcons());
function toggleModal(id) {
    document.getElementById(id).classList.toggle("hidden");
}
</script>
</head>
<body class="h-screen bg-gray-100 font-sans">

<div class="flex h-full">
  <?php include 'sidebar.php'; ?>

  <div class="flex-1 flex flex-col overflow-y-auto">
    <main class="p-6 space-y-6">

      <div class="flex items-center justify-between border-b border-gray-300 pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Job Postings</h2>
        <?php include 'profile.php'; ?>
      </div>
      <div><?php include 'admin_navbar.php'; ?></div>
      
      <div class="flex justify-between items-center mb-4">
        <a href="recruitement_request.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
          <i data-lucide="plus"></i> Add Recruitment Request
        </a>

        <form method="GET">
          <select name="sort" onchange="this.form.submit()" class="p-2 border rounded-lg">
            <option value="created_at" <?= $sort=='created_at'?'selected':'' ?>>Sort by Created At</option>
            <option value="job_title" <?= $sort=='job_title'?'selected':'' ?>>Sort by Title</option>
            <option value="department_name" <?= $sort=='department_name'?'selected':'' ?>>Sort by Department</option>
            <option value="status" <?= $sort=='status'?'selected':'' ?>>Sort by Status</option>
          </select>
        </form>
      </div>

      <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full border border-gray-200 rounded-lg">
          <thead class="bg-gray-800 text-white text-sm">
            <tr>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Title</th>
              <th class="px-4 py-2">Department</th>
              <th class="px-4 py-2">Type</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2">Vacancies</th>
              <th class="px-4 py-2">Filled</th>
              <th class="px-4 py-2">Created At</th>
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody class="text-sm divide-y divide-gray-200">
            <?php if ($jobs && $jobs->num_rows > 0): ?>
              <?php while ($job = $jobs->fetch_assoc()): ?>
                <?php
                  $statusClass = match($job['status']) {
                      'Open' => 'bg-green-100 text-green-700',
                      'Closed' => 'bg-red-100 text-red-700',
                      'Pending' => 'bg-yellow-100 text-yellow-700',
                      default => 'bg-gray-100 text-gray-700',
                  };
                ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-2"><?= htmlspecialchars($job['job_id']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($job['job_title']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($job['department_name'] ?? 'N/A') ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($job['employment_type']) ?></td>
                  <td class="px-4 py-2">
                    <span class="px-2 py-1 rounded text-xs font-medium <?= $statusClass ?>">
                      <?= htmlspecialchars($job['status']) ?>
                    </span>
                  </td>
                  <td class="px-4 py-2"><?= htmlspecialchars($job['vacancies']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($job['filled_positions']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($job['created_at']) ?></td>
                  <td class="px-4 py-2 flex gap-2">
                    <button onclick="toggleModal('editJob<?= $job['job_id'] ?>')" class="px-2 py-1 bg-yellow-500 text-white rounded">Edit</button>
                    <button onclick="toggleModal('deleteJob<?= $job['job_id'] ?>')" class="px-2 py-1 bg-red-600 text-white rounded">Delete</button>
                  </td>
                </tr>

                <!-- Edit Modal -->
                <div id="editJob<?= $job['job_id'] ?>" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                  <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 max-h-[80vh] overflow-y-auto">
                    <h2 class="text-xl font-semibold mb-4">Edit Job Posting</h2>
                    <form method="POST" class="space-y-4">
                      <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">

                      <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" rows="6" class="w-full p-2 border rounded-lg resize-y"><?= htmlspecialchars($job['description']) ?></textarea>
                      </div>

                      <div>
                        <label class="block text-sm font-medium">Employment Type</label>
                        <select name="employment_type" class="w-full p-2 border rounded-lg">
                          <option value="Full-time" <?= $job['employment_type']=='Full-time'?'selected':'' ?>>Full-time</option>
                          <option value="Part-time" <?= $job['employment_type']=='Part-time'?'selected':'' ?>>Part-time</option>
                          <option value="Contract" <?= $job['employment_type']=='Contract'?'selected':'' ?>>Contract</option>
                        </select>
                      </div>

                      <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select name="status" class="w-full p-2 border rounded-lg">
                          <option value="Pending" <?= $job['status']=='Pending'?'selected':'' ?>>Pending</option>
                          <option value="Open" <?= $job['status']=='Open'?'selected':'' ?>>Open</option>
                          <option value="Closed" <?= $job['status']=='Closed'?'selected':'' ?>>Closed</option>
                        </select>
                      </div>

                      <div>
                        <label class="block text-sm font-medium">Vacancies</label>
                        <input type="number" name="vacancies" min="1" value="<?= $job['vacancies'] ?>" class="w-full p-2 border rounded-lg">
                      </div>

                      <div class="flex justify-end gap-2">
                        <button type="button" onclick="toggleModal('editJob<?= $job['job_id'] ?>')" class="px-4 py-2 border rounded-lg">Cancel</button>
                        <button type="submit" name="edit_job" class="px-4 py-2 bg-yellow-600 text-white rounded-lg">Save</button>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Delete Modal -->
                <div id="deleteJob<?= $job['job_id'] ?>" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Delete Job Posting</h2>
                    <p>Are you sure you want to delete <strong><?= htmlspecialchars($job['job_title']) ?></strong>?</p>
                    <form method="POST" class="mt-4 flex justify-end gap-2">
                      <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">
                      <button type="button" onclick="toggleModal('deleteJob<?= $job['job_id'] ?>')" class="px-4 py-2 border rounded-lg">Cancel</button>
                      <button type="submit" name="delete_job" class="px-4 py-2 bg-red-600 text-white rounded-lg">Delete</button>
                    </form>
                  </div>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="9" class="px-4 py-4 text-center text-gray-500">No job postings found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>
</body>
</html>
