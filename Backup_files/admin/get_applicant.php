<?php
require_once __DIR__ . "/../connections.php";
if (!isset($connections)) die("Database connection missing.");

$id = intval($_GET['id'] ?? 0);

$sql = "
SELECT a.*, j.title AS job_title, j.employment_type, j.status AS job_status, d.name AS department_name
FROM applicants a
LEFT JOIN applications app ON a.applicant_id = app.applicant_id
LEFT JOIN jobs j ON app.job_id = j.job_id
LEFT JOIN departments d ON j.department_id = d.department_id
WHERE a.applicant_id = ?
LIMIT 1
";
$stmt = $connections->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<p class='text-center text-gray-500'>Applicant not found.</p>";
    exit;
}
$row = $res->fetch_assoc();
?>

<div class="space-y-4">
  <div class="flex items-center gap-4">
    <img src="<?= htmlspecialchars($row['photo_path'] ?: 'https://cdn-icons-png.flaticon.com/512/847/847969.png') ?>" 
         class="w-20 h-20 rounded-full border">
    <div>
      <h3 class="text-xl font-bold"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></h3>
      <p class="text-gray-600"><?= htmlspecialchars($row['email']) ?> | <?= htmlspecialchars($row['phone']) ?></p>
      <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700"><?= htmlspecialchars($row['status']) ?></span>
    </div>
  </div>

  <div class="grid grid-cols-2 gap-4 text-sm">
    <div>
      <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>
      <p><strong>Birthdate:</strong> <?= htmlspecialchars($row['birthdate']) ?></p>
      <p><strong>Gender:</strong> <?= htmlspecialchars($row['gender']) ?></p>
    </div>
    <div>
      <p><strong>Applied Job:</strong> <?= htmlspecialchars($row['job_title'] ?? 'N/A') ?></p>
      <p><strong>Department:</strong> <?= htmlspecialchars($row['department_name'] ?? 'N/A') ?></p>
      <p><strong>Job Type:</strong> <?= htmlspecialchars($row['employment_type'] ?? 'N/A') ?></p>
    </div>
  </div>

  <div class="mt-4">
    <strong>Resume:</strong>
    <?php if (!empty($row['resume_path'])): ?>
      <a href="<?= htmlspecialchars($row['resume_path']) ?>" target="_blank" class="text-blue-600 hover:underline">View Resume</a>
    <?php else: ?>
      <span class="text-gray-500">No resume uploaded</span>
    <?php endif; ?>
  </div>
</div>
