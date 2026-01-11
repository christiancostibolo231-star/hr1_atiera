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

    /* ===== TOP BAR ===== */
    .top-bar {
        width: 90%;
        max-width: 1200px;
        margin: 40px auto 20px auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .top-bar h2 {
        color: var(--primary);
        font-size: 22px;
        font-weight: 700;
    }

    .top-bar input {
        padding: 10px 16px;
        border-radius: 10px;
        border: 1px solid var(--border);
        outline: none;
        width: 260px;
        font-size: 14px;
        transition: 0.3s;
    }

    .top-bar input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25);
    }

    /* ===== SUMMARY CARDS (LEFT SIDE) ===== */
    .summary-container {
        display: flex;
        justify-content: flex-start;
        width: 90%;
        max-width: 1200px;
        margin: 0 auto 25px auto;
    }

    .summary {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    .card {
        background-color: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        padding: 18px 22px;
        min-width: 180px;
        text-align: left;
        border-left: 4px solid var(--accent);
    }

    .card h3 {
        font-size: 15px;
        color: var(--primary);
        margin: 0;
        margin-bottom: 6px;
    }

    .card p {
        font-size: 20px;
        font-weight: 700;
        color: var(--accent);
        margin: 0;
    }

    .card span {
        font-size: 13px;
        color: var(--text-dark);
        font-weight: 500;
        margin-left: 4px;
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

    .approved { background-color: rgba(22, 163, 74, 0.15); color: var(--success); }
    .submitted { background-color: rgba(14, 165, 233, 0.15); color: #0ea5e9; }
    .missing { background-color: rgba(220, 38, 38, 0.15); color: var(--danger); }
    .pending { background-color: rgba(250, 204, 21, 0.15); color: var(--warning); }

    /* ===== ACTION BUTTONS ===== */
    .actions {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    .approve {
        background: var(--accent);
        color: var(--primary);
    }

    .reject {
        background: var(--primary);
        color: var(--accent);
    }

    .view {
        background: transparent;
        border: 1px solid var(--accent);
        color: var(--accent);
    }

    .approve:hover { background: #f2d16b; }
    .reject:hover { background: #132d58; }
    .view:hover { background: var(--accent); color: var(--primary); }

    footer {
        text-align: center;
        color: #64748b;
        font-size: 13px;
        margin-top: 40px;
        padding-bottom: 20px;
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
        .top-bar { flex-direction: column; gap: 10px; align-items: flex-start; }
        .summary-container { justify-content: center; }
        .summary { justify-content: center; }
        table, thead, tbody, tr, td, th { display: block; }
        thead { display: none; }
        tbody tr { margin-bottom: 15px; padding: 10px; }
        td { display: flex; justify-content: space-between; border: none; padding: 8px 0; }
        td::before { content: attr(data-label); font-weight: 600; color: var(--primary); }
        .actions { justify-content: flex-end; }
    }
</style>
</head>
<body>

<div class="top-bar">
    <h2>Document Collection</h2>
    <input type="text" placeholder="🔍 Search employee...">
</div>

<div class="summary-container">
    <div class="summary">
        <div class="card">
            <h3>Total Required</h3>
            <p>7<span>Total</span></p>
        </div>
        <div class="card">
            <h3>Submitted</h3>
            <p>5<span>Total</span></p>
        </div>
        <div class="card">
            <h3>Approved</h3>
            <p>4<span>Total</span></p>
        </div>
        <div class="card">
            <h3>Missing</h3>
            <p>2<span>Total</span></p>
        </div>
    </div>
</div>

<div class="main">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee Name</th>
                <th>Document Name</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td data-label="#">1</td>
                <td data-label="Employee Name">Juan Dela Cruz</td>
                <td data-label="Document Name">NBI Clearance</td>
                <td data-label="Status"><span class="status submitted">Submitted</span></td>
                <td data-label="Remarks">For review</td>
                <td data-label="Actions">
                    <div class="actions">
                        <button class="action-btn view">View</button>
                        <button class="action-btn approve">Approve</button>
                        <button class="action-btn reject">Reject</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td data-label="#">2</td>
                <td data-label="Employee Name">Maria Santos</td>
                <td data-label="Document Name">Birth Certificate</td>
                <td data-label="Status"><span class="status approved">Approved</span></td>
                <td data-label="Remarks">Verified</td>
                <td data-label="Actions">
                    <div class="actions">
                        <button class="action-btn view">View</button>
                        <button class="action-btn approve">Approve</button>
                        <button class="action-btn reject">Reject</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td data-label="#">3</td>
                <td data-label="Employee Name">Carlos Reyes</td>
                <td data-label="Document Name">Medical Certificate</td>
                <td data-label="Status"><span class="status missing">Missing</span></td>
                <td data-label="Remarks">Not uploaded yet</td>
                <td data-label="Actions">
                    <div class="actions">
                        <button class="action-btn view">View</button>
                        <button class="action-btn approve">Approve</button>
                        <button class="action-btn reject">Reject</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<footer>
  © 2025 Company HR Portal — Document Management
</footer>


<?php
$content = ob_get_clean();
include 'admin_navbar-sidebar.php';
?>
</body>
</html>
