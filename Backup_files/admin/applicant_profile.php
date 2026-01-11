<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../connections.php";

// Use the recruitment database
if (!isset($hr1_recruitment) || !($hr1_recruitment instanceof mysqli)) {
    die("Database connection error: recruitment DB not available");
}

$flash = "";

// =====================
// ADD Applicant
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_applicant'])) {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if ($first === '' || $last === '' || $email === '') {
        $flash = "Please fill required fields for new applicant.";
    } else {
        $hr1_recruitment->begin_transaction();
        try {
            $stmt = $hr1_recruitment->prepare("
                INSERT INTO applicants 
                (first_name, middle_name, last_name, email, phone, address, birthdate, gender, resume_path, photo_path, status, application_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'In Progress', NOW())
            ");
            $stmt->bind_param(
                "ssssssssss",
                $_POST['first_name'], $_POST['middlename'], $_POST['last_name'],
                $_POST['email'], $_POST['phone'], $_POST['address'],
                $_POST['birthdate'], $_POST['gender'], $_POST['resume_path'],
                $_POST['photo_path']
            );
            $stmt->execute();
            $hr1_recruitment->commit();
            header("Location: " . basename(__FILE__) . "?added=1");
            exit;
        } catch (Exception $e) {
            $hr1_recruitment->rollback();
            $flash = "Error adding applicant: " . $e->getMessage();
        }
    }
}

// =====================
// FETCH Applicants
// =====================
$stmt = $hr1_recruitment->prepare("
    SELECT applicant_id, first_name, middle_name, last_name, email, phone, address, birthdate, gender, resume_path, photo_path, status, application_date
    FROM applicants
    ORDER BY applicant_id DESC
");
$stmt->execute();
$applicants = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Applicants Dashboard</title>
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
        <h2 class="text-2xl font-bold text-gray-800">Applicants Dashboard</h2>
        <?php include 'profile.php'; ?>
      </div>

      <div><?php include 'admin_navbar.php'; ?></div>

      <?php if (!empty($_GET['added'])): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded">Applicant added.</div>
      <?php endif; ?>
      <?php if ($flash): ?>
        <div class="bg-red-100 text-red-800 p-3 rounded"><?= htmlspecialchars($flash) ?></div>
      <?php endif; ?>

      <!-- Add Applicant Button -->
      <div class="flex justify-between items-center mb-4 mt-4">
        <button onclick="toggleModal('addApplicant')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
          <i data-lucide="plus"></i> Add Applicant
        </button>
      </div>

      <!-- Applicants Table -->
      <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full border border-gray-200 rounded-lg">
          <thead class="bg-gray-800 text-white text-sm">
            <tr>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Name</th>
              <th class="px-4 py-2">Email</th>
              <th class="px-4 py-2">Phone</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2">Application Date</th>
            </tr>
          </thead>
          <tbody class="text-sm divide-y divide-gray-200">
            <?php if ($applicants->num_rows > 0): ?>
              <?php while ($row = $applicants->fetch_assoc()): ?>
                <?php
                  $photo = !empty($row['photo_path']) ? $row['photo_path'] : 'https://cdn-icons-png.flaticon.com/512/847/847969.png';
                  $statusClass = match($row['status']) {
                      'In Progress' => 'bg-yellow-100 text-yellow-700',
                      'Hired' => 'bg-green-100 text-green-700',
                      'Rejected' => 'bg-red-100 text-red-700',
                      default => 'bg-gray-100 text-gray-700',
                  };
                ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-2"><?= $row['applicant_id'] ?></td>
                  <td class="px-4 py-2 flex items-center gap-2">
                    <img src="<?= htmlspecialchars($photo) ?>" class="w-8 h-8 rounded-full border">
                    <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?>
                  </td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['phone']) ?></td>
                  <td class="px-4 py-2">
                    <span class="px-2 py-1 rounded text-xs font-medium <?= $statusClass ?>">
                      <?= htmlspecialchars($row['status']) ?>
                    </span>
                  </td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['application_date']) ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">No applicants found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<!-- Add Applicant Modal -->
<div id="addApplicant" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
    <button type="button" onclick="toggleModal('addApplicant')" class="absolute top-3 right-3">✕</button>
    <h2 class="text-xl font-semibold mb-4">Add Applicant</h2>
    <form method="POST" class="space-y-3">
      <input name="first_name" class="w-full p-2 border rounded" placeholder="First Name" required>
      <input name="middlename" class="w-full p-2 border rounded" placeholder="Middle Name">
      <input name="last_name" class="w-full p-2 border rounded" placeholder="Last Name" required>
      <input name="email" class="w-full p-2 border rounded" placeholder="Email" required>
      <input name="phone" class="w-full p-2 border rounded" placeholder="Phone">
      <input name="address" class="w-full p-2 border rounded" placeholder="Address">
      <input type="date" name="birthdate" class="w-full p-2 border rounded">
      <input name="gender" class="w-full p-2 border rounded" placeholder="Gender">
      <input name="resume_path" class="w-full p-2 border rounded" placeholder="Resume Path">
      <input name="photo_path" class="w-full p-2 border rounded" placeholder="Photo Path">
      <div class="flex justify-end gap-2">
        <button type="button" onclick="toggleModal('addApplicant')" class="px-4 py-2 border rounded">Cancel</button>
        <button type="submit" name="add_applicant" class="px-4 py-2 bg-blue-600 text-white rounded">Add</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => lucide.createIcons());
function toggleModal(id){
  const modal = document.getElementById(id);
  if(!modal) return;
  modal.classList.toggle("hidden");
}
</script>

<style>
.modal { display:none; }
.modal:not(.hidden) { display:flex; }
</style>

</body>
</html>
