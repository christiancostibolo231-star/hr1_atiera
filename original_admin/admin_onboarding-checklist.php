<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Documents</title>
    
</head>
<body>
<?php ob_start(); ?>

<style>
    :root {
        --primary: #0b1e3d; /* Dark navy */
        --accent: #d4af37; /* Gold */
        --text-light: #ffffff;
        --text-dark: #1e293b;
        --bg: #f3f4f6;
        --card-bg: #ffffff;
        --success: #16a34a;
        --warning: #facc15;
        --info: #0ea5e9;
        --danger: #dc2626;
        --border: #e2e8f0;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg);
        margin: 0;
        padding: 0;
        color: var(--text-dark);
    }

    /* ===== FILTER BAR ===== */
    .filters {
        width: 90%;
        max-width: 1200px;
        margin: 40px auto 15px auto;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .filter-btn {
        background: var(--primary);
        color: var(--accent);
        border: 1px solid var(--accent);
        padding: 8px 18px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: 0.3s;
    }

    .filter-btn:hover, .filter-btn.active {
        background: var(--accent);
        color: var(--primary);
    }

    /* ===== TABLE CONTAINER ===== */
    .main {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto 40px auto;
        background-color: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        padding: 25px;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    thead th {
        background-color: var(--primary);
        color: var(--accent);
        padding: 14px 20px;
        font-weight: 600;
        text-align: left;
        font-size: 15px;
        border-bottom: 3px solid var(--accent);
    }

    tbody tr {
        background-color: var(--card-bg);
        border-radius: 12px;
        transition: all 0.25s ease;
    }

    tbody tr:hover {
        transform: scale(1.01);
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    }

    td {
        padding: 14px 20px;
        font-size: 15px;
        border-top: 1px solid var(--border);
        vertical-align: middle;
    }

    td:first-child {
        font-weight: 600;
        color: var(--primary);
    }

    .status {
        font-weight: 600;
        padding: 8px 14px;
        border-radius: 25px;
        text-align: center;
        display: inline-block;
        font-size: 13px;
    }

    .completed {
        background-color: rgba(22, 163, 74, 0.15);
        color: var(--success);
    }

    .pending {
        background-color: rgba(250, 204, 21, 0.15);
        color: var(--warning);
    }

    .in-progress {
        background-color: rgba(14, 165, 233, 0.15);
        color: var(--info);
    }

    .document-status {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .document-status span {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 8px;
        font-weight: 500;
    }

    .submitted {
        background-color: rgba(22, 163, 74, 0.15);
        color: var(--success);
    }

    .missing {
        background-color: rgba(220, 38, 38, 0.15);
        color: var(--danger);
    }

    .action-btn {
        background: var(--accent);
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        color: var(--primary);
        transition: 0.3s;
    }

    .action-btn:hover {
        background: #f2d16b;
    }

    footer {
        text-align: center;
        color: #64748b;
        font-size: 13px;
        margin-top: 40px;
        padding-bottom: 20px;
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
        .filters {
            flex-wrap: wrap;
            justify-content: center;
        }

        table, thead, tbody, tr, td, th {
            display: block;
        }

        thead {
            display: none;
        }

        tbody tr {
            margin-bottom: 15px;
            padding: 10px;
        }

        td {
            display: flex;
            justify-content: space-between;
            border: none;
            padding: 8px 0;
        }

        td::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--primary);
        }
    }
</style>
</head>
<body>

<div class="filters">
    <button class="filter-btn active">All</button>
    <button class="filter-btn">Completed</button>
    <button class="filter-btn">In Progress</button>
    <button class="filter-btn">Pending</button>
</div>

<div class="main">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee Name</th>
                <th>Position</th>
                <th>Documents</th>
                <th>Orientation Status</th>
                <th>Account Setup</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td data-label="#">1</td>
                <td data-label="Employee Name">Juan Dela Cruz</td>
                <td data-label="Position">Software Engineer</td>
                <td data-label="Documents" class="document-status">
                    <span class="submitted">Resume</span>
                    <span class="submitted">ID</span>
                    <span class="missing">Certificate</span>
                </td>
                <td data-label="Orientation Status"><span class="status in-progress">In Progress</span></td>
                <td data-label="Account Setup"><span class="status pending">Pending</span></td>
                <td data-label="Action"><button class="action-btn">Mark as Done</button></td>
            </tr>
            <tr>
                <td data-label="#">2</td>
                <td data-label="Employee Name">Maria Santos</td>
                <td data-label="Position">HR Staff</td>
                <td data-label="Documents" class="document-status">
                    <span class="submitted">Resume</span>
                    <span class="submitted">ID</span>
                    <span class="submitted">Certificate</span>
                </td>
                <td data-label="Orientation Status"><span class="status completed">Completed</span></td>
                <td data-label="Account Setup"><span class="status completed">Completed</span></td>
                <td data-label="Action"><button class="action-btn">Mark as Done</button></td>
            </tr>
            <tr>
                <td data-label="#">3</td>
                <td data-label="Employee Name">Carlos Reyes</td>
                <td data-label="Position">Admin Assistant</td>
                <td data-label="Documents" class="document-status">
                    <span class="submitted">Resume</span>
                    <span class="missing">ID</span>
                    <span class="missing">Certificate</span>
                </td>
                <td data-label="Orientation Status"><span class="status pending">Pending</span></td>
                <td data-label="Account Setup"><span class="status pending">Pending</span></td>
                <td data-label="Action"><button class="action-btn">Mark as Done</button></td>
            </tr>
        </tbody>
    </table>
</div>

<footer>
  © 2025 Company HR Portal — Admin Dashboard
</footer>

<?php
$content = ob_get_clean();
include 'admin_navbar-sidebar.php';
?>
</body>
</html>
