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
   APPROVE / REJECT JOB REQUEST
---------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $job_id = (int)$_POST['job_id'];
    $new_status = $_POST['new_status'];

    if (in_array($new_status, ['Open', 'Closed'])) {
        $stmt = $connections->prepare(
            "UPDATE jobs SET status = ? WHERE job_id = ?"
        );
        $stmt->bind_param("si", $new_status, $job_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* ---------------------------
   FETCH ALL JOB REQUESTS
---------------------------- */
$query = "
    SELECT
        job_id,
        job_title,
        employment_type,
        status,
        vacancies,
        filled_positions,
        created_at
    FROM jobs
    ORDER BY created_at DESC
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
<title>HR Job Requests Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen bg-gray-100 font-sans">

<div class="flex h-full">

<?php include 'sidebar.php'; ?>

<div class="flex-1 flex flex-col overflow-y-auto">
<main class="p-6 space-y-6">

<!-- HEADER -->
<div class="flex items-center justify-between border-b border-gray-300 pb-4">
    <h2 class="text-2xl font-bold text-gray-800">
        HR Job Requests Dashboard
    </h2>
    <?php include 'profile.php'; ?>
</div>

<?php include 'admin_navbar.php'; ?>

<!-- TABLE -->
<div class="overflow-x-auto bg-white shadow-md rounded-lg">
<table class="min-w-full border border-gray-200">

<thead class="bg-gray-800 text-white text-sm">
<tr>
    <th class="px-4 py-2">Job ID</th>
    <th class="px-4 py-2">Job Title</th>
    <th class="px-4 py-2">Type</th>
    <th class="px-4 py-2">Status</th>
    <th class="px-4 py-2">Vacancies</th>
    <th class="px-4 py-2">Available</th>
    <th class="px-4 py-2">Requested At</th>
    <th class="px-4 py-2">Actions</th>
</tr>
</thead>

<tbody class="text-sm divide-y divide-gray-200">

<?php if ($jobs->num_rows > 0): ?>
<?php while ($job = $jobs->fetch_assoc()):
    $available = max(0, $job['vacancies'] - $job['filled_positions']);
    $statusClass = match($job['status']) {
        'Open' => 'bg-green-100 text-green-700',
        'Closed' => 'bg-red-100 text-red-700',
        'Pending' => 'bg-yellow-100 text-yellow-700',
        default => 'bg-gray-100 text-gray-700'
    };
?>
<tr class="hover:bg-gray-50">
    <td class="px-4 py-2"><?= $job['job_id'] ?></td>
    <td class="px-4 py-2 font-medium"><?= htmlspecialchars($job['job_title']) ?></td>
    <td class="px-4 py-2"><?= htmlspecialchars($job['employment_type']) ?></td>
    <td class="px-4 py-2">
        <span class="px-2 py-1 rounded text-xs font-medium <?= $statusClass ?>">
            <?= htmlspecialchars($job['status']) ?>
        </span>
    </td>
    <td class="px-4 py-2"><?= $job['vacancies'] ?></td>
    <td class="px-4 py-2 font-semibold"><?= $available ?></td>
    <td class="px-4 py-2 text-xs text-gray-500"><?= $job['created_at'] ?></td>

    <!-- ACTIONS -->
    <td class="px-4 py-2">
        <?php if ($job['status'] === 'Pending'): ?>
            <form method="POST" class="flex gap-2">
                <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">
                <input type="hidden" name="new_status">

                <button type="submit"
                        name="update_status"
                        onclick="this.form.new_status.value='Open'"
                        class="px-3 py-1 bg-green-600 text-white text-xs rounded">
                    Approve
                </button>

                <button type="submit"
                        name="update_status"
                        onclick="this.form.new_status.value='Closed'"
                        class="px-3 py-1 bg-red-600 text-white text-xs rounded">
                    Reject
                </button>
            </form>
        <?php else: ?>
            <span class="text-xs text-gray-400">No action</span>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="8" class="px-4 py-6 text-center text-gray-500">
        No job requests found.
    </td>
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
