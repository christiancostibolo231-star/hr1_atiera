<?php
date_default_timezone_set('Asia/Manila');
$admin_name = "James Kneechtel DL. Sabandal";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Atiera Admin Panel</title>

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --gold: #FFD700;
    --blue: #004AAD;
    --dark-blue: #1f2d3d;
    --white: #FFFFFF;
    --light-gray: #f8f9fa;
    --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background-color: #f8f9fa;
    display: flex;
    min-height: 100vh;
    overflow-x: hidden;
}

/* ===== SIDEBAR ===== */
.sidebar {
    background: var(--dark-blue);
    color: var(--white);
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    transition: var(--transition);
    z-index: 1000;
    box-shadow: 4px 0 25px rgba(0, 0, 0, 0.35);
    width: 240px;
    display: flex;
    flex-direction: column;
}
.sidebar.collapsed { width: 60px; }

.logo-header {
    padding: 18px;
    display: flex;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    cursor: pointer;
    justify-content: flex-start;
}
.sidebar.collapsed .logo-header { justify-content: center; padding: 18px 0; }

.logo-icon {
    width: 36px; height: 36px;
    background: linear-gradient(45deg, var(--blue), var(--gold));
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: var(--white);
    font-weight: 700;
    font-size: 16px;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
}
.logo-text {
    font-family: 'Playfair Display', serif;
    font-size: 19px;
    font-weight: 700;
    margin-left: 12px;
    white-space: nowrap;
    transition: opacity 0.3s ease;
}
.sidebar.collapsed .logo-text { display: none; }

.nav-menu {
    list-style: none;
    padding: 20px 0 15px;
    flex: 1;
    overflow-y: auto;
}
.nav-item { margin: 0 12px 6px; }

.nav-link {
    display: flex;
    align-items: center;
    padding: 11px 18px;
    color: rgba(255, 255, 255, 0.92);
    text-decoration: none;
    border-radius: 8px;
    transition: var(--transition);
    font-size: 14.5px;
    cursor: pointer;
}
.nav-link:hover, .nav-link.active {
    background: rgba(255, 255, 255, 0.12);
    transform: translateX(3px);
}
.nav-icon {
    width: 26px;
    text-align: center;
    margin-right: 14px;
    font-size: 17px;
    color: var(--gold);
}
.nav-link.has-dropdown::after {
    content: '\f107';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    margin-left: auto;
    font-size: 12px;
    color: rgba(255, 255, 255, 0.7);
    transition: transform 0.3s ease;
}
.nav-link.has-dropdown.active::after { transform: rotate(180deg); }

.submenu {
    list-style: none;
    padding-left: 40px;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, padding 0.3s ease;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 0 0 8px 8px;
    margin-top: 4px;
}
.nav-item.active > .submenu {
    max-height: 300px;
    padding: 8px 0 12px;
}
.submenu li a {
    display: block;
    padding: 8px 18px;
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    font-size: 13.5px;
    transition: var(--transition);
    border-radius: 6px;
    margin: 0 10px;
}
.submenu li a:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(3px);
}
.sidebar.collapsed .nav-link span,
.sidebar.collapsed .nav-link.has-dropdown::after {
    display: none;
}
.sidebar.collapsed .nav-link { justify-content: center; padding: 12px 0; }
.sidebar.collapsed .nav-link .nav-icon { margin-right: 0; }
.sidebar.collapsed .submenu { display: none !important; }

/* Scrollbar */
.nav-menu::-webkit-scrollbar { width: 6px; }
.nav-menu::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.25);
    border-radius: 3px;
}

/* ===== TOP NAVBAR ===== */
.top-navbar {
    background: var(--white);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    height: 70px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 0 50px;
    gap: 42px; /* 👉 this is the magic line – same spacing as your sample */
    position: fixed;
    top: 0;
    left: 240px;
    right: 0;
    z-index: 1001;
    transition: var(--transition);
}


.sidebar.collapsed ~ .top-navbar { left: 60px; }

.current-datetime {
    background: var(--light-gray);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    color: var(--dark-blue);
}
.profile-container {
    display: flex;
    align-items: center;
    gap: 12px;
    color: var(--dark-blue);
    position: relative;
}
.profile-pic {
    width: 42px; height: 42px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid var(--gold);
}
.profile-pic img { width: 100%; height: 100%; object-fit: cover; }
.profile-name {
    font-weight: 600;
    font-size: 15px;
    color: var(--dark-blue);
}
.profile-info {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.profile-role {
    font-size: 12px;
    color: #888;
    font-weight: 500;
    margin-top: 2px;
}

.settings-icon {
    font-size: 18px;
    color: var(--blue);
    cursor: pointer;
    transition: color 0.3s ease;
}
.settings-icon:hover { color: var(--gold); }

.dropdown-menu {
    position: absolute;
    top: 60px;
    right: 0;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    display: none;
    flex-direction: column;
    width: 170px;
    z-index: 999;
    overflow: hidden;
}
.dropdown-item {
    padding: 12px 16px;
    font-size: 14px;
    color: #333;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
}
.dropdown-item:hover { background-color: var(--gold); color: #fff; }
.dropdown-item.logout i { color: #d9534f; }

/* ===== MAIN CONTENT ===== */
.main-content {
    margin-left: 240px;
    margin-top: 70px;
    padding: 30px;
    width: calc(100% - 240px);
    transition: var(--transition);
}
.sidebar.collapsed ~ .main-content {
    margin-left: 60px;
    width: calc(100% - 60px);
}
h2 { font-size: 24px; color: var(--dark-blue); margin-bottom: 10px; }

/* Responsive */
@media (max-width: 992px) {
    .sidebar { left: -240px; }
    .sidebar.expanded { left: 0; }
    .sidebar.collapsed { left: -60px; }
    .top-navbar { left: 0 !important; }
    .main-content { margin-left: 0 !important; width: 100%; }
}
</style>
</head>

<body>
<!-- ===== SIDEBAR ===== -->
<aside class="sidebar" id="sidebar">
    <div class="logo-header" id="logoToggle">
        <div class="logo-icon">A</div>
        <div class="logo-text">Atiera</div>
    </div>

    <ul class="nav-menu">
        <li class="nav-item"><a href="admin_dashboard.php" class="nav-link"><span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span><span>Dashboard</span></a></li>
        <li class="nav-item">
            <div class="nav-link has-dropdown"><span class="nav-icon"><i class="fas fa-user-plus"></i></span><span>Recruitment <br> Management</span></div>
            <ul class="submenu">
                <li><a href="admin_job-posting.php">Job Postings</a></li>
                <li><a href="admin_recruitment-newly-applicant.php">Newly Applicants</a></li>
                <li><a href="admin_interview-scheduler.php">Interview Scheduler</a></li>
            </ul>
        </li>

        <li class="nav-item">
            <div class="nav-link has-dropdown"><span class="nav-icon"><i class="fas fa-users"></i></span><span>Applicant <br> Management</span></div>
            <ul class="submenu">
                <li><a href="admin_applicant-documents.php">Applicant Document</a></li>
                <li><a href="admin_status-tracker.php">Status Tracking</a></li>
                <li><a href="admin_rejection-templates.php">Rejection Templates</a></li>
            </ul>
        </li>

         <li class="nav-item">
            <div class="nav-link has-dropdown"><span class="nav-icon"><i class="fas fa-users"></i></span><span> New Hire <br> Onboarding</span></div>
            <ul class="submenu">
                <li><a href="admin_onboarding-checklist.php">Onboarding Checklist</a></li>
                <li><a href="admin_document-collection.php">Document Collection</a></li>
                <li><a href="admin_welcome-survey.php">Welcome Surveys</a></li>
            </ul>
        </li>

         <li class="nav-item">
            <div class="nav-link has-dropdown"><span class="nav-icon"><i class="fas fa-users"></i></span><span>Performance <br> Management</span></div>
            <ul class="submenu">
                <li><a href="admin_attendance-center.php">Attendance Center</a></li>
                <li><a href="#">Review Cycles</a></li>
                <li><a href="#">Feedback Tools</a></li>
                <li><a href="#">Calibration Sessions</a></li>
            </ul>
        </li>

         <li class="nav-item">
            <div class="nav-link has-dropdown"><span class="nav-icon"><i class="fas fa-users"></i></span><span> Social <br> Recognition</span></div>
            <ul class="submenu">
                    <li><a href="#">Peer Recognition</a></li>
                    <li><a href="#">Reward Catalog</a></li>
                    <li><a href="#">Leaderboard</a></li>
                    <li><a href="#">Announcement Feed</a></li>
            </ul>
        </li>
    </ul>
</aside>

<!-- ===== TOP NAVBAR ===== -->
<nav class="top-navbar">
    <div class="current-datetime" id="liveDateTime">Loading...</div>
    <div class="profile-container">
    <div class="profile-pic">
        <img src="https://via.placeholder.com/40" alt="Admin Photo">
    </div>
    <div class="profile-info">
        <span class="profile-name"><?= htmlspecialchars($admin_name) ?></span>
        <span class="profile-role">Admin</span>
    </div>
    <i class="fas fa-cog settings-icon" id="settingsIcon"></i>
    <div class="dropdown-menu" id="dropdownMenu">
        <a href="#" class="dropdown-item"><i class="fas fa-user"></i> Profile</a>
        <a href="#" class="dropdown-item"><i class="fas fa-sliders-h"></i> Settings</a>
        <a href="../landing_page.php" class="dropdown-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

</nav>


<!-- ===== MAIN CONTENT ===== -->
<main class="main-content">
    <?php if (isset($content)) echo $content; ?>
</main>


<script>
// Sidebar collapse / expand
const sidebar = document.getElementById('sidebar');
const logoToggle = document.getElementById('logoToggle');
const navItems = document.querySelectorAll('.nav-item');

logoToggle.addEventListener('click', () => {
    if (window.innerWidth <= 992) sidebar.classList.toggle('expanded');
    else sidebar.classList.toggle('collapsed');
});
document.addEventListener('click', (e) => {
    if (window.innerWidth <= 992 && !sidebar.contains(e.target) && sidebar.classList.contains('expanded')) {
        sidebar.classList.remove('expanded');
    }
});
navItems.forEach(item => {
    const link = item.querySelector('.nav-link');
    if (link.classList.contains('has-dropdown')) {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            navItems.forEach(other => { if (other !== item) other.classList.remove('active'); });
            item.classList.toggle('active');
        });
    }
});

// Live date/time
function updateDateTime() {
    const now = new Date();
    const options = { year:'numeric', month:'long', day:'numeric', hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:false };
    document.getElementById('liveDateTime').textContent = now.toLocaleDateString('en-US', options).replace(',', ' |');
}
updateDateTime(); setInterval(updateDateTime, 1000);

// Dropdown toggle
document.addEventListener('DOMContentLoaded', () => {
    const settingsIcon = document.getElementById('settingsIcon');
    const dropdownMenu = document.getElementById('dropdownMenu');
    settingsIcon.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.style.display = dropdownMenu.style.display === 'flex' ? 'none' : 'flex';
    });
    document.addEventListener('click', (e) => {
        if (!dropdownMenu.contains(e.target) && !settingsIcon.contains(e.target)) dropdownMenu.style.display = 'none';
    });
});
</script>
</body>
</html>
