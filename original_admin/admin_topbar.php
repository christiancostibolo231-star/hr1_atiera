<?php
date_default_timezone_set('Asia/Manila');
$admin_name = "James Kneechtel DL. Sabandal";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Atiera Admin Navbar</title>

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --gold: #FFD700;
    --blue: #004AAD;
    --dark-blue: #003366;
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
    overflow-x: hidden;
}

/* ==== TOP NAVBAR ==== */
.top-navbar {
    background: var(--white);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    height: 70px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 0 30px;
    position: sticky;
    top: 0;
    z-index: 1001;
    gap: 20px;
}

/* ==== DATE TIME ==== */
.current-datetime {
    background: var(--light-gray);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    color: var(--dark-blue);
}

/* ==== PROFILE SECTION ==== */
.profile-container {
    display: flex;
    align-items: center;
    gap: 12px;
    color: var(--dark-blue);
    position: relative;
}

.profile-pic {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid var(--gold);
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-pic img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-name {
    font-weight: 600;
    font-size: 15px;
    color: var(--dark-blue);
}

.settings-icon {
    font-size: 18px;
    color: var(--blue);
    cursor: pointer;
    transition: color 0.3s ease;
}
.settings-icon:hover {
    color: var(--gold);
}

/* ==== DROPDOWN MENU ==== */
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
    animation: fadeIn 0.3s ease;
}

.dropdown-item {
    padding: 12px 16px;
    font-size: 14px;
    color: #333;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: 0.2s;
}

.dropdown-item i {
    font-size: 14px;
    color: var(--blue);
}

.dropdown-item:hover {
    background-color: var(--gold);
    color: #fff;
}

.dropdown-item.logout i {
    color: #d9534f;
}

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-10px);}
    to {opacity: 1; transform: translateY(0);}
}
</style>
</head>

<body>
<!-- ======= Top Navbar ======= -->
<nav class="top-navbar">
    <div class="current-datetime" id="liveDateTime">Loading...</div>

    <div class="profile-container">
        <div class="profile-pic">
            <img src="https://via.placeholder.com/40" alt="Admin Photo">
        </div>
        <span class="profile-name"><?= htmlspecialchars($admin_name) ?></span>
        <i class="fas fa-cog settings-icon" id="settingsIcon"></i>

        <!-- Dropdown Menu -->
        <div class="dropdown-menu" id="dropdownMenu">
            <a href="#" class="dropdown-item"><i class="fas fa-user"></i> Profile</a>
            <a href="#" class="dropdown-item"><i class="fas fa-sliders-h"></i> Settings</a>
            <a href="#" class="dropdown-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</nav>

<script>
// Live time updater
function updateDateTime() {
    const now = new Date();
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    };
    const formatted = now.toLocaleDateString('en-US', options)
        .replace(',', ' |')
        .replace(/\s+/g, ' ');
    document.getElementById('liveDateTime').textContent = formatted;
}
updateDateTime();
setInterval(updateDateTime, 1000);

// Dropdown toggle
document.addEventListener('DOMContentLoaded', () => {
    const settingsIcon = document.getElementById('settingsIcon');
    const dropdownMenu = document.getElementById('dropdownMenu');

    settingsIcon.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.style.display = dropdownMenu.style.display === 'flex' ? 'none' : 'flex';
    });

    document.addEventListener('click', (e) => {
        if (!dropdownMenu.contains(e.target) && !settingsIcon.contains(e.target)) {
            dropdownMenu.style.display = 'none';
        }
    });
});
</script>
</body>
</html>
