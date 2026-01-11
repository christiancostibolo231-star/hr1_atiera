<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . "/../connections.php";

if (!isset($connections) || !($connections instanceof mysqli)) {
    die("❌ Database connection error");
}

/* =============================
   FETCH JOB POSTINGS
============================= */
$jobs_query = "SELECT j.job_id, j.job_title, j.department_id, j.status, j.created_at, d.department_name 
               FROM jobs j
               LEFT JOIN departments d ON j.department_id = d.department_id
               ORDER BY j.created_at DESC";
$jobs_result = $connections->query($jobs_query);

/* =============================
   FETCH JOB REQUISITIONS
============================= */
$requests_query = "SELECT r.request_id, r.job_title, r.department_id, r.requester_name, 
                          r.vacancies, r.reason, r.status, r.created_at, d.department_name
                   FROM job_requisitions r
                   LEFT JOIN departments d ON r.department_id = d.department_id
                   ORDER BY r.created_at DESC";
$requests_result = $connections->query($requests_query);

/* =============================
   ACTION HANDLERS
============================= */
// Approve job requisition → move to jobs table
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $req = $connections->query("SELECT * FROM job_requisitions WHERE request_id = $id LIMIT 1")->fetch_assoc();

    if ($req) {
        $insert = $connections->prepare("INSERT INTO jobs (position_id, department_id, job_title, description, requirements, vacancies, filled_positions, status, employment_type, created_at)
                                         VALUES (0, ?, ?, ?, ?, ?, 0, 'Open', 'Full-Time', NOW())");
        $desc = $req['reason'] ?: 'No description provided';
        $reqs = 'Pending details from department';
        $insert->bind_param("isssi", $req['department_id'], $req['job_title'], $desc, $reqs, $req['vacancies']);
        $insert->execute();

        $connections->query("UPDATE job_requisitions SET status = 'Approved' WHERE request_id = $id");
    }
    header("Location: job_postings.php");
    exit;
}

// Decline job requisition
if (isset($_GET['decline'])) {
    $id = intval($_GET['decline']);
    $connections->query("UPDATE job_requisitions SET status = 'Declined' WHERE request_id = $id");
    header("Location: job_postings.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Postings | Admin</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }

        .job-postings-container {
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            width: 95%;
            max-width: 1150px;
            margin: 30px auto;
            animation: fadeIn 0.6s ease;
        }

        h2 {
            color: #004AAD;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        p.description {
            color: #555;
            font-size: 14.5px;
            margin-bottom: 16px;
        }

        .view-request-btn {
            background: #004AAD;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .view-request-btn:hover {
            background: #00317a;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .job-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14.5px;
        }

        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #004AAD;
            color: #fff;
            font-weight: 600;
        }

        tr:hover {
            background: #f9fafc;
        }

        .status {
            padding: 5px 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
        }

        .status.open {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status.close {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .btn-action {
            border: none;
            border-radius: 6px;
            padding: 5px 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            line-height: 1;
        }

        .btn-action.approve {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .btn-action.approve:hover {
            background: #c3e6cb;
        }

        .btn-action.decline {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            margin-left: 4px;
        }
        .btn-action.decline:hover {
            background: #f5c6cb;
        }

        .btn-action.view {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            margin-left: 4px;
        }
        .btn-action.view:hover {
            background: #bee5eb;
        }

        .tab-section { display: none; }
        .tab-section.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<?php ob_start(); ?>

<div class="job-postings-container">
    <h2>Job Hiring Requests</h2>
    <p class="description">Below are all job hiring requests submitted by various departments.</p>

    <button class="view-request-btn" id="viewRequestBtn">View Requested Jobs</button>

    <!-- Job Postings Table -->
    <div class="tab-section active" id="mainTable">
        <div class="table-wrapper">
            <table class="job-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Date Posted</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($jobs_result && $jobs_result->num_rows > 0): ?>
                        <?php $i=1; while($job = $jobs_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($job['job_title']) ?></td>
                                <td><?= htmlspecialchars($job['department_name'] ?? 'Unassigned') ?></td>
                                <td><?= date('F d, Y', strtotime($job['created_at'])) ?></td>
                                <td><span class="status <?= strtolower($job['status']) ?>"><?= htmlspecialchars($job['status']) ?></span></td>
                                <td><button class="btn-action view">View</button></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No job postings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Requested Jobs Table -->
    <div class="tab-section" id="requestedTable">
        <div class="table-wrapper">
            <table class="job-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Job Position</th>
                        <th>Department</th>
                        <th>Requester</th>
                        <th>Vacancies</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Filed</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($requests_result && $requests_result->num_rows > 0): ?>
                        <?php $i=1; while($req = $requests_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($req['job_title']) ?></td>
                                <td><?= htmlspecialchars($req['department_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($req['requester_name']) ?></td>
                                <td><?= htmlspecialchars($req['vacancies']) ?></td>
                                <td><?= htmlspecialchars($req['reason']) ?></td>
                                <td><span class="status <?= strtolower($req['status']) ?>"><?= htmlspecialchars($req['status']) ?></span></td>
                                <td><?= date('F d, Y', strtotime($req['created_at'])) ?></td>
                                <td>
                                    <a href="?approve=<?= $req['request_id'] ?>" class="btn-action approve">Approve</a>
                                    <a href="?decline=<?= $req['request_id'] ?>" class="btn-action decline">Decline</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9">No job requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const viewBtn = document.getElementById('viewRequestBtn');
const mainTable = document.getElementById('mainTable');
const requestedTable = document.getElementById('requestedTable');
let showingRequests = false;

viewBtn.addEventListener('click', () => {
    showingRequests = !showingRequests;
    if (showingRequests) {
        mainTable.classList.remove('active');
        requestedTable.classList.add('active');
        viewBtn.textContent = "Back to Job Postings";
    } else {
        requestedTable.classList.remove('active');
        mainTable.classList.add('active');
        viewBtn.textContent = "View Requested Jobs";
    }
});
</script>

<?php
$content = ob_get_clean();
include 'admin_navbar-sidebar.php';
?>
</body>
</html>
