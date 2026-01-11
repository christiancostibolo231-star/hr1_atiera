<?php
date_default_timezone_set('Asia/Manila');
$admin_name = "Admin Atiera";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Postings | Atiera Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --gold: #FFD700;
            --blue: #004AAD;
            --dark-blue: #1f2d3d; /* darker navy blue */
            --white: #FFFFFF;
            --light-gray: #f8f9fa;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

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

        /* Sidebar */
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

        .sidebar.collapsed {
            width: 60px;
        }

        /* Logo Header */
        .logo-header {
            padding: 18px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: var(--transition);
            justify-content: flex-start;
        }

        .sidebar.collapsed .logo-header {
            justify-content: center;
            padding: 18px 0;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(45deg, var(--blue), var(--gold));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
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
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .logo-text {
            display: none;
        }

        /* Nav Menu */
        .nav-menu {
            list-style: none;
            padding: 20px 0 15px;
            flex: 1;
            overflow-y: auto;
        }

        .nav-item {
            margin: 0 12px 6px;
        }

        /* Regular Link */
        .nav-link {
            display: flex;
            align-items: center;
            padding: 11px 18px;
            color: rgba(255, 255, 255, 0.92);
            text-decoration: none;
            border-radius: 8px;
            transition: var(--transition);
            font-size: 14.5px;
            white-space: nowrap;
            cursor: pointer;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255, 255, 255, 0.12);
            color: var(--white);
            transform: translateX(3px);
        }

        .nav-icon {
            width: 26px;
            text-align: center;
            margin-right: 14px;
            font-size: 17px;
            color: var(--gold);
        }

        /* Dropdown Arrow */
        .nav-link.has-dropdown::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            transition: transform 0.3s ease;
        }

        .nav-link.has-dropdown.active::after {
            transform: rotate(180deg);
        }

        /* Submenu */
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
            color: var(--white);
            transform: translateX(3px);
        }

        /* Collapsed Mode */
        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link.has-dropdown::after {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px 0;
        }

        .sidebar.collapsed .nav-link .nav-icon {
            margin-right: 0;
        }

        .sidebar.collapsed .submenu {
            display: none !important;
        }

        /* Scrollbar */
        .nav-menu::-webkit-scrollbar {
            width: 6px;
        }

        .nav-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 3px;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding: 30px;
            width: 100%;
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: 60px;
        }

        h2 {
            font-size: 24px;
            color: var(--dark-blue);
            margin-bottom: 10px;
        }

        p {
            color: #333;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                left: -240px;
            }

            .sidebar.expanded {
                left: 0;
            }

            .sidebar.collapsed {
                left: -60px;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="logo-header" id="logoToggle">
            <div class="logo-icon">A</div>
            <div class="logo-text">Atiera</div>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="admin_dashboard.php" class="nav-link">
                    <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <div class="nav-link has-dropdown">
                    <span class="nav-icon"><i class="fas fa-user-plus"></i></span>
                    <span>Recruitment <br> Management</span>
                </div>
                <ul class="submenu">
                    <li><a href="admin_job-posting.php">Job Postings</a></li>
                    <li><a href="#">Sourcing Tools</a></li>
                    <li><a href="#">Interview Scheduler</a></li>
                    <li><a href="#">Offer Management</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <div class="nav-link has-dropdown">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <span>Applicant <br> Management</span>
                </div>
                <ul class="submenu">
                    <li><a href="#">Applicant Pipeline</a></li>
                    <li><a href="#">Resume Review</a></li>
                    <li><a href="#">Status Tracking</a></li>
                    <li><a href="#">Rejection Templates</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <div class="nav-link has-dropdown">
                    <span class="nav-icon"><i class="fas fa-file-signature"></i></span>
                    <span>New Hire <br> Onboarding</span>
                </div>
                <ul class="submenu">
                    <li><a href="#">Onboarding Checklist</a></li>
                    <li><a href="#">Document Collection</a></li>
                    <li><a href="#">Training Modules</a></li>
                    <li><a href="#">Welcome Surveys</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <div class="nav-link has-dropdown">
                    <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                    <span>Performance <br> Management</span>
                </div>
                <ul class="submenu">
                    <li><a href="#">Goal Setting</a></li>
                    <li><a href="#">Review Cycles</a></li>
                    <li><a href="#">Feedback Tools</a></li>
                    <li><a href="#">Calibration Sessions</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <div class="nav-link has-dropdown">
                    <span class="nav-icon"><i class="fas fa-medal"></i></span>
                    <span>Social <br> Recognition</span>
                </div>
                <ul class="submenu">
                    <li><a href="#">Peer Recognition</a></li>
                    <li><a href="#">Reward Catalog</a></li>
                    <li><a href="#">Leaderboard</a></li>
                    <li><a href="#">Announcement Feed</a></li>
                </ul>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <!-- <main class="main-content">
        <h2>Job Postings</h2>
    </main> -->

    <script>
        const sidebar = document.getElementById('sidebar');
        const logoToggle = document.getElementById('logoToggle');
        const navItems = document.querySelectorAll('.nav-item');

        logoToggle.addEventListener('click', () => {
            if (window.innerWidth <= 992) {
                sidebar.classList.toggle('expanded');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        });

        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992 && 
                !sidebar.contains(e.target) && 
                sidebar.classList.contains('expanded')) {
                sidebar.classList.remove('expanded');
            }
        });

        navItems.forEach(item => {
            const link = item.querySelector('.nav-link');
            if (link.classList.contains('has-dropdown')) {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    navItems.forEach(other => {
                        if (other !== item) other.classList.remove('active');
                    });
                    item.classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html>
