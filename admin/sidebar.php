<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) session_start();
include __DIR__ . "/../connections.php";

// Default profile info
$fullName = "";
$roleName = "";
$profileImage = "/default-avatar.png";

// Only attempt to fetch from DB if user_id is set
if (!empty($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("
        SELECT u.username, u.reference_image, r.name AS role_name, p.first_name, p.last_name
        FROM users u
        LEFT JOIN user_roles ur ON u.user_id = ur.user_id
        LEFT JOIN roles r ON ur.role_id = r.role_id
        LEFT JOIN user_profiles p ON u.user_id = p.user_id
        WHERE u.user_id = ? LIMIT 1
    ");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $userRow = $result->fetch_assoc();
        $fullName = trim(($userRow['first_name'] ?? '') . ' ' . ($userRow['last_name'] ?? '')) ?: ($userRow['username'] ?? "");
        $roleName = $userRow['role_name'] ?? "";
        if (!empty($userRow['reference_image'])) {
            $profileImage = "/" . $userRow['reference_image'];
        }
    }
}

// Ensure variables always have safe defaults
$fullName = $fullName ?? "";
$roleName = $roleName ?? "";
$profileImage = $profileImage ?? "/default-avatar.png";

$currentPage = $_SERVER['PHP_SELF'];
?>

<div id="sidebar" class="fixed lg:relative bg-gray-800 text-white w-64 transition-all duration-300 h-screen flex flex-col z-50">

  <!-- Logo -->
  <div class="flex items-center justify-between px-4 py-4 border-b border-gray-700">
    <a href="/public_html/timesheet/dashboard.php">
      <img src="../img/logo.png" alt="Logo" class="h-20 sidebar-logo-expanded" />
    </a>
    <a href="/public_html/timesheet/dashboard.php">
      <img src="../img/logo2.png" alt="Logo" class="h-20 sidebar-logo-collapsed hidden" />
    </a>
  </div>

  <!-- User info -->
  <div class="flex items-center gap-3 px-4 py-4 border-b border-gray-700">
    <img src="/public_html/<?php echo htmlspecialchars($profileImage); ?>" class="w-10 h-10 rounded-full object-cover border" />
    <div class="flex flex-col">
      <span class="text-sm font-medium"><?php echo htmlspecialchars($fullName); ?></span>
      <span class="text-xs text-gray-400"><?php echo htmlspecialchars($roleName); ?></span>
    </div>
  </div>

  <!-- Search -->
  <div class="px-4 py-3 border-b border-gray-700">
    <input type="text" placeholder="Search..." class="w-full px-3 py-2 text-sm bg-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
  </div>

  <!-- Navigation -->
  <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto [&::-webkit-scrollbar]{display:none} [-ms-overflow-style:none] [scrollbar-width:none]">

    <!-- Dashboard -->
    <a href="/hr1_atiera/admin/dashboard.php" 
      class="flex items-center gap-3 px-3 py-2 rounded sidebar-link <?php echo (strpos($currentPage, 'dashboard.php') !== false) ? 'bg-gray-700 font-semibold' : 'hover:bg-gray-700'; ?>">
      <i data-lucide="home" class="w-5 h-5"></i>
      <span class="sidebar-text">Dashboard</span>
    </a>

    <!-- Recruitment -->
    <div>
      <button class="flex items-center gap-3 px-3 py-2 w-full rounded hover:bg-gray-700 submenu-toggle">
        <i data-lucide="briefcase" class="w-5 h-5"></i>
        <span class="sidebar-text">Recruitment</span>
        <i data-lucide="chevron-down" class="w-4 h-4 ml-auto"></i>
      </button>
      <div class="submenu pl-8 space-y-1 hidden">
        <a href="/hr1_atiera/admin/jobPosting.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Job Postings</a>
        <a href="/hr1_atiera/admin/interviews.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Interviews</a>
        <a href="reports.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Reports</a>
      </div>
    </div>

    <!-- Applicants -->
    <div>
      <button class="flex items-center gap-3 px-3 py-2 w-full rounded hover:bg-gray-700 submenu-toggle">
        <i data-lucide="users" class="w-5 h-5"></i>
        <span class="sidebar-text">Applicants</span>
        <i data-lucide="chevron-down" class="w-4 h-4 ml-auto"></i>
      </button>
      <div class="submenu pl-8 space-y-1 hidden">
        <a href="/hr1_atierahr1_atiera/admin/applicant_profile.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Profiles</a>
        <a href="/hr1_atiera/admin/tracking.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Tracking</a>
        <a href="/hr1_atiera/admin/documents.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Documents</a>
      </div>
    </div>

    <!-- Onboarding -->
    <div>
      <button class="flex items-center gap-3 px-3 py-2 w-full rounded hover:bg-gray-700 submenu-toggle">
        <i data-lucide="user-plus" class="w-5 h-5"></i>
        <span class="sidebar-text">Onboarding</span>
        <i data-lucide="chevron-down" class="w-4 h-4 ml-auto"></i>
      </button>
      <div class="submenu pl-8 space-y-1 hidden">
        <!-- <a href="/Atiera/admin/checklist.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Checklist</a>
        <a href="/Atiera/admin/orientation.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Orientation</a> -->
        <a href="/Atiera/admin/compliance.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Compliance</a>
      </div>
    </div>

    <!-- Performance -->
    <div>
      <button class="flex items-center gap-3 px-3 py-2 w-full rounded hover:bg-gray-700 submenu-toggle">
        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
        <span class="sidebar-text">Performance</span>
        <i data-lucide="chevron-down" class="w-4 h-4 ml-auto"></i>
      </button>
      <div class="submenu pl-8 space-y-1 hidden">
        <a href="/Atiera/admin/performance.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Performance report</a>
        <!-- <a href="/public_html/performance/reviews.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Reviews</a>
        <a href="/public_html/performance/analytics.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Reports</a> -->
      </div>
    </div>

    <!-- Recognition -->
    <div>
      <button class="flex items-center gap-3 px-3 py-2 w-full rounded hover:bg-gray-700 submenu-toggle">
        <i data-lucide="award" class="w-5 h-5"></i>
        <span class="sidebar-text">Recognition</span>
        <i data-lucide="chevron-down" class="w-4 h-4 ml-auto"></i>
      </button>
      <div class="submenu pl-8 space-y-1 hidden">
        <a href="/Atiera/admin/recognition.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Manager Recognition</a>
        <!-- <a href="/public_html/recognition/rewards.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Rewards</a>
        <a href="/public_html/recognition/analytics.php" class="block px-3 py-1 rounded hover:bg-gray-700 text-sm">Reports</a> -->
      </div>
    </div>

  </nav>
</div>

<div id="mobile-menu-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll('.submenu-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.nextElementSibling.classList.toggle('hidden');
    });
  });

  if (typeof lucide !== "undefined" && lucide.createIcons) lucide.createIcons();
});
</script>
