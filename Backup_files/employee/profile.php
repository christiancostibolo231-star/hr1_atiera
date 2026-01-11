<?php
// ====================================
// profile.php
// Safe profile header with fallback
// ====================================

// Include DB connection if available
@include __DIR__ . "/../connections.php";

// Default values (for guest / no session)
$fullName     = "James Ryan Vergara";
$roleName     = "Admin";
$profileImage = "/public_html/picture/logo2.png"; // fallback avatar

// Check session (if you want login support later)
if (!empty($_SESSION['user_id']) && isset($conn)) {
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        SELECT u.username, u.reference_image, r.name AS role_name, p.first_name, p.last_name
        FROM users u
        LEFT JOIN user_roles ur ON u.user_id = ur.user_id
        LEFT JOIN roles r ON ur.role_id = r.role_id
        LEFT JOIN user_profiles p ON u.user_id = p.user_id
        WHERE u.user_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $userRow = $result->fetch_assoc();

        $fullName = trim(($userRow['first_name'] ?? '') . ' ' . ($userRow['last_name'] ?? ''));
        if ($fullName === "") {
            $fullName = $userRow['username'] ?? "User";
        }

        $roleName = $userRow['role_name'] ?? "Employee";

        if (!empty($userRow['reference_image'])) {
            $profileImage = "/public_html/" . ltrim($userRow['reference_image'], "/");
        }
    }
}
?>

<!-- Right: User Info -->
<div class="relative flex items-center gap-4" id="headerContainer">

  <!-- Clock + SVG -->
  <div class="flex items-center gap-2">
    <span id="clock" class="text-sm text-gray-600 font-mono"></span>

    <!-- Zoomable SVG with tooltip -->
    <div class="relative group">
      <svg id="clockIcon" xmlns="http://www.w3.org/2000/svg"
           fill="none" viewBox="0 0 24 24" stroke="currentColor"
           class="w-6 h-6 text-gray-600 cursor-pointer">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
      </svg>
      <span class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 w-max bg-gray-700 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
        Toggle Fullscreen
      </span>
    </div>
  </div>

  <!-- User Dropdown Toggle -->
  <button id="userDropdownToggle" class="flex items-center gap-2 focus:outline-none">
    <img src="<?php echo htmlspecialchars($profileImage); ?>" 
         alt="profile picture" 
         class="w-8 h-8 rounded-full border object-cover" />
    <div class="flex flex-col items-start">
        <span class="text-sm text-gray-800 font-medium">
            <?php echo htmlspecialchars($fullName); ?>
        </span>
        <span class="text-xs text-gray-500">
            <?php echo htmlspecialchars($roleName); ?>
        </span>
    </div>
    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-600"></i>
  </button>

  <!-- Dropdown -->
  <div id="userDropdown" class="absolute right-0 mt-2 w-40 bg-white rounded shadow-lg hidden z-20">
      <a href="/public_html/user/createUser.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
      <a href="/public_html/user/changePassword.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Change Password</a>
      <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
      <a href="../logout.php" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-600 hover:text-white transition-colors text-red-500">
          <i data-lucide="log-out" class="w-5 h-5"></i>
          <span class="sidebar-text">Logout</span>
      </a>
  </div>
</div>

<!-- Fullscreen Overlay -->
<div id="fullscreenOverlay" class="fixed inset-0 bg-white z-50 hidden flex items-center justify-center transition-all duration-300">
  <div class="flex items-center gap-6 text-4xl font-bold" id="overlayContent">
    <span id="overlayClock"></span>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" class="w-16 h-16 text-gray-800">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
    </svg>
  </div>
</div>

<script>
// ================== CLOCK ==================
function updateClock() {
  const now = new Date();
  let hours   = now.getHours().toString().padStart(2,'0');
  let minutes = now.getMinutes().toString().padStart(2,'0');
  let seconds = now.getSeconds().toString().padStart(2,'0');
  document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
  document.getElementById('overlayClock').textContent = `${hours}:${minutes}:${seconds}`;
}
setInterval(updateClock, 1000);
updateClock();

// ================== FULLSCREEN TOGGLE ==================
const clockIcon = document.getElementById("clockIcon");
let isFullscreen = false;

clockIcon.addEventListener("click", () => {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen().catch(err => console.error(err));
    isFullscreen = true;
  } else {
    document.exitFullscreen();
    isFullscreen = false;
  }
});

document.addEventListener('fullscreenchange', () => {
  isFullscreen = !!document.fullscreenElement;
});

// ================== DROPDOWN ==================
document.addEventListener("DOMContentLoaded", function () {
  const userDropdownToggle = document.getElementById("userDropdownToggle");
  const userDropdown = document.getElementById("userDropdown");

  if(userDropdownToggle && userDropdown) {
      userDropdownToggle.addEventListener("click", function (event) {
          event.stopPropagation();
          userDropdown.classList.toggle("hidden");
      });

      document.addEventListener("click", function (event) {
          if (!userDropdown.contains(event.target) && !userDropdownToggle.contains(event.target)) {
              userDropdown.classList.add("hidden");
          }
      });
  }
});
</script>
