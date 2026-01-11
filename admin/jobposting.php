<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../connections.php";

/* ---------------------------
   DB CHECK
---------------------------- */
if (!isset($connections) || !($connections instanceof mysqli)) {
    die("Database connection error");
}

/* ---------------------------
   EDIT JOB (UPDATE)
---------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_job'])) {
    $job_id = (int)$_POST['job_id'];
    $employment_type = $_POST['employment_type'];
    $status = $_POST['status'];
    $vacancies = (int)$_POST['vacancies'];
    $description = trim($_POST['description']);

    $stmt = $connections->prepare("
        UPDATE jobs 
        SET employment_type = ?, 
            status = ?, 
            vacancies = ?, 
            description = ?
        WHERE job_id = ?
    ");
    $stmt->bind_param("ssisi", $employment_type, $status, $vacancies, $description, $job_id);
    $stmt->execute();
    $stmt->close();

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* ---------------------------
   FETCH JOBS
---------------------------- */
$query = "
    SELECT 
        j.job_id,
        j.job_title,
        j.description,
        j.employment_type,
        d.department_name,
        j.status,
        j.vacancies,
        j.filled_positions,
        j.created_at
    FROM jobs j
    LEFT JOIN job_departments d ON j.department_id = d.department_id
    ORDER BY j.created_at DESC
";

$stmt = $connections->prepare($query);
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
<script>
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

<?php include 'admin_navbar.php'; ?>

<div class="overflow-x-auto bg-white shadow-md rounded-lg">
<table class="min-w-full border border-gray-200">

<thead class="bg-gray-800 text-white text-sm">
<tr>
<th class="px-4 py-2">ID</th>
<th class="px-4 py-2">Title</th>
<th class="px-4 py-2">Department</th>
<th class="px-4 py-2">Type</th>
<th class="px-4 py-2">Status</th>
<th class="px-4 py-2">Vacancies</th>
<th class="px-4 py-2">Filled</th>
<th class="px-4 py-2">Available</th>
<th class="px-4 py-2">Actions</th>
</tr>
</thead>

<tbody class="text-sm divide-y">

<?php while ($job = $jobs->fetch_assoc()): 
    $available = max(0, $job['vacancies'] - $job['filled_positions']);
    $statusClass = match($job['status']) {
        'Open' => 'bg-green-100 text-green-700',
        'Closed' => 'bg-red-100 text-red-700',
        'Pending' => 'bg-yellow-100 text-yellow-700',
        default => 'bg-gray-100 text-gray-700'
    };
?>

<tr>
<td class="px-4 py-2"><?= $job['job_id'] ?></td>
<td class="px-4 py-2"><?= htmlspecialchars($job['job_title']) ?></td>
<td class="px-4 py-2"><?= htmlspecialchars($job['department_name'] ?? 'N/A') ?></td>
<td class="px-4 py-2"><?= htmlspecialchars($job['employment_type']) ?></td>
<td class="px-4 py-2">
    <span class="px-2 py-1 rounded text-xs <?= $statusClass ?>">
        <?= htmlspecialchars($job['status']) ?>
    </span>
</td>
<td class="px-4 py-2"><?= $job['vacancies'] ?></td>
<td class="px-4 py-2"><?= $job['filled_positions'] ?></td>
<td class="px-4 py-2 font-semibold"><?= $available ?></td>
<td class="px-4 py-2">
    <button onclick="toggleModal('edit<?= $job['job_id'] ?>')"
        class="bg-yellow-500 text-white px-2 py-1 rounded">
        Edit
    </button>
</td>
</tr>

<!-- ================= EDIT MODAL ================= -->
<div id="edit<?= $job['job_id'] ?>"
     class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

<div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">

<h2 class="text-xl font-bold mb-4">Edit Job Posting</h2>

<form method="POST" class="space-y-4">

<input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">

<div>
<label class="block text-sm font-medium mb-1">Description</label>
<textarea name="description" rows="5"
    class="w-full p-2 border rounded"><?= htmlspecialchars($job['description']) ?></textarea>
</div>

<div>
<label class="block text-sm font-medium mb-1">Employment Type</label>
<select name="employment_type" class="w-full p-2 border rounded">
<option value="Full-time" <?= $job['employment_type']=='Full-time'?'selected':'' ?>>Full-time</option>
<option value="Part-time" <?= $job['employment_type']=='Part-time'?'selected':'' ?>>Part-time</option>
<option value="Contract" <?= $job['employment_type']=='Contract'?'selected':'' ?>>Contract</option>
</select>
</div>

<div>
<label class="block text-sm font-medium mb-1">Status</label>
<select name="status" class="w-full p-2 border rounded">
<option value="Open" <?= $job['status']=='Open'?'selected':'' ?>>Open</option>
<option value="Closed" <?= $job['status']=='Closed'?'selected':'' ?>>Closed</option>
<option value="Pending" <?= $job['status']=='Pending'?'selected':'' ?>>Pending</option>
</select>
</div>

<div>
<label class="block text-sm font-medium mb-1">Vacancies</label>
<input type="number" name="vacancies" min="1"
    value="<?= $job['vacancies'] ?>"
    class="w-full p-2 border rounded">
</div>

<div class="flex justify-end gap-2">
<button type="button"
    onclick="toggleModal('edit<?= $job['job_id'] ?>')"
    class="px-4 py-2 border rounded">
    Cancel
</button>

<button type="submit"
    name="edit_job"
    class="px-4 py-2 bg-yellow-600 text-white rounded">
    Save Changes
</button>
</div>

</form>
</div>
</div>
<!-- ================= END MODAL ================= -->

<?php endwhile; ?>

</tbody>
</table>
</div>

</main>
</div>
</div>

</body>
</html>
