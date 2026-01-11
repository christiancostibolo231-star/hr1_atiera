<?php
session_start();
require_once __DIR__ . '/connections.php'; // ✅ Uses multi-database connections

// ===============================
// ✅ Use Recruitment DB
// ===============================
$conn = $hr1_recruitment;

// ===============================
// ✅ Fetch all open jobs
// ===============================
$jobs = [];
$query = "
    SELECT job_id, job_title, description, vacancies, filled_positions, created_at 
    FROM jobs 
    WHERE status = 'Open' 
      AND vacancies > filled_positions 
    ORDER BY created_at DESC
";

if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
    $result->free();
} else {
    die("❌ Query error on Recruitment DB: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HR1 — Careers</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1920&q=80');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    .overlay { background: rgba(255,255,255,0.6); }
    .modal { display: none; }
    .modal.active { display: flex; }
    .fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from {opacity: 0;} to {opacity: 1;} }
  </style>
</head>
<body class="text-gray-900 font-sans relative">
<div class="overlay min-h-screen">

  <!-- Header -->
  <header class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <a href="index.php" class="text-xl font-bold text-blue-600">HR1 Careers</a>
      <nav class="space-x-6 hidden md:flex">
        <a href="#" class="text-gray-600 hover:text-blue-600">Home</a>
        <a href="#explore" class="text-gray-600 hover:text-blue-600">Jobs</a>
        <a href="profile.php" class="text-gray-600 hover:text-blue-600">Profile</a>
        <?php if (isset($_SESSION["login-status"]) && $_SESSION["login-status"] === "Valid"): ?>
          <a href="logout.php" class="text-gray-600 hover:text-blue-600">Logout</a>
        <?php else: ?>
          <a href="login.php" class="text-gray-600 hover:text-blue-600">Login</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- Hero -->
  <section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
    <div class="max-w-7xl mx-auto px-6 py-24 text-center">
      <h1 class="text-4xl md:text-5xl font-bold mb-4">Join Our Hotel & Restaurant Team</h1>
      <p class="text-lg md:text-xl mb-6">Be part of excellence in hospitality — explore roles that shape guest experiences.</p>
      <a href="#explore" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition">
        Explore Careers
      </a>
    </div>
  </section>

  <!-- Job Cards -->
  <main id="explore" class="max-w-7xl mx-auto px-6 py-16">
    <h2 class="text-2xl font-bold mb-8 text-center">Available Roles</h2>

    <div class="grid gap-8 md:grid-cols-3">
      <?php if (!empty($jobs)): ?>
        <?php foreach ($jobs as $job): ?>
          <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
            <h3 class="text-lg font-semibold mb-2"><?= htmlspecialchars($job['job_title']) ?></h3>
            <p class="text-gray-600 mb-4"><?= htmlspecialchars(substr($job['description'], 0, 100)) ?>...</p>
            <p class="font-semibold text-gray-700 mb-4">
              Vacancies Left: <span class="font-normal"><?= $job['vacancies'] - $job['filled_positions'] ?></span>
            </p>
            <button onclick="openModal(<?= $job['job_id'] ?>)" class="text-blue-600 font-medium hover:underline">Learn More →</button>
          </div>

          <!-- Modal -->
          <div id="modal-<?= $job['job_id'] ?>" class="modal fixed inset-0 bg-black bg-opacity-50 justify-center items-center z-50 fade-in">
            <div class="flex flex-col md:flex-row gap-0 items-center justify-center w-full max-w-5xl px-4">
              <div class="bg-white w-full max-w-xl max-h-[90vh] overflow-y-auto p-6 rounded-2xl shadow-lg relative fade-in">
                <button onclick="closeModal(<?= $job['job_id'] ?>)" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">✕</button>
                <h3 class="text-2xl font-bold mb-3"><?= htmlspecialchars($job['job_title']) ?></h3>
                <p class="font-semibold text-gray-900 mb-1">Job Description:</p>
                <p class="text-gray-700 mb-4 whitespace-pre-line"><?= htmlspecialchars($job['description']) ?></p>
                <p class="font-semibold text-gray-900 mb-1">Vacancies Left:</p>
                <p class="text-sm text-gray-600 mb-4"><?= $job['vacancies'] - $job['filled_positions'] ?></p>

                <!-- ✅ Apply Now button -->
                <?php if (isset($_SESSION["login-status"]) && $_SESSION["login-status"] === "Valid"): ?>
                  <button onclick="showApplyConfirm(<?= $job['job_id'] ?>)" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Apply Now</button>
                <?php else: ?>
                  <button onclick="window.location.href='login.php?redirect=profile.php?apply=<?= $job['job_id'] ?>'" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Apply Now</button>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="col-span-3 text-center text-gray-600">No open roles available at this time.</p>
      <?php endif; ?>
    </div>
  </main>
</div>

<!-- ✅ Apply Confirmation Modal -->
<div id="applyConfirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-[9999]">
  <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md text-center fade-in">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Confirm Application</h2>
    <p class="text-gray-600 mb-6">Do you want to apply for this job?</p>
    <div class="flex justify-center gap-4">
      <button id="confirmYes" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Yes, Apply</button>
      <button onclick="closeApplyConfirm()" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition">Cancel</button>
    </div>
  </div>
</div>

<script>
let selectedJobId = null;

function openModal(id){document.getElementById("modal-"+id).classList.add("active");}
function closeModal(id){document.getElementById("modal-"+id).classList.remove("active");}

function showApplyConfirm(jobId){
  selectedJobId = jobId;
  document.getElementById("applyConfirmModal").classList.remove("hidden");
}

function closeApplyConfirm(){
  selectedJobId = null;
  document.getElementById("applyConfirmModal").classList.add("hidden");
}

document.getElementById("confirmYes").addEventListener("click", function(){
  if(selectedJobId){
    window.location.href = "profile.php?apply=" + selectedJobId;
  }
});
</script>
</body>
</html>
