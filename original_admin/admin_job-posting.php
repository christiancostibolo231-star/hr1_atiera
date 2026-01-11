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

        /* View button above table */
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

        /* Table */
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
            text-align: left;
        }

        th {
            background: #004AAD;
            color: #fff;
            font-weight: 600;
        }

        tr:hover {
            background: #f9fafc;
        }

        /* Status Badges */
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
/* ===== ICON ACTION BUTTONS ===== */
.btn-action {
    border: none;
    border-radius: 6px;
    padding: 4px 7px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    line-height: 1;
    white-space: nowrap;
    vertical-align: middle;
}

/* Green Approve */
.btn-action.approve {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.btn-action.approve:hover {
    background: #c3e6cb;
}

/* Red Decline */
.btn-action.decline {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    margin-left: 4px;
}
.btn-action.decline:hover {
    background: #f5c6cb;
}

/* Blue View */
.btn-action.view {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
    margin-left: 4px;
}
.btn-action.view:hover {
    background: #bee5eb;
}

/* Table button alignment */
/* Center align lahat ng laman ng table */
.job-table th,
.job-table td {
    text-align: center;
    vertical-align: middle;
}



        /* View switch (tab-like behavior) */
        .tab-section {
            display: none;
        }

        .tab-section.active {
            display: block;
        }

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

    <!-- Default Table (Main Job Postings) -->
    <div class="tab-section active" id="mainTable">
        <div class="table-wrapper">
            <table class="job-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Date Requested</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>001</td>
                        <td>Software Engineer</td>
                        <td>IT Department</td>
                        <td>October 20, 2025</td>
                        <td><span class="status open">Open</span></td>
                        <td>
                            <button class="btn-action post">Post</button>
                            <button class="btn-action view">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>002</td>
                        <td>HR Specialist</td>
                        <td>Human Resources</td>
                        <td>October 21, 2025</td>
                        <td><span class="status close">Closed</span></td>
                        <td>
                            <button class="btn-action post">Post</button>
                            <button class="btn-action view">View</button>
                        </td>
                    </tr>
                    <tr>
                        <td>003</td>
                        <td>Marketing Assistant</td>
                        <td>Marketing</td>
                        <td>October 25, 2025</td>
                        <td><span class="status open">Open</span></td>
                        <td>
                            <button class="btn-action post">Post</button>
                            <button class="btn-action view">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Requested Jobs Table (Appears after clicking "View Requested Jobs") -->
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
                    <tr>
                        <td>001</td>
                        <td>Event Coordinator</td>
                        <td>Marketing</td>
                        <td>Maria Lopez</td>
                        <td>2</td>
                        <td>Needed for Q4 Events</td>
                        <td><span class="status open">Pending</span></td>
                        <td>October 22, 2025</td>
                        <td>
                            <button class="btn-action approve">Approve</button>
                            <button class="btn-action decline">Decline</button>
                        </td>
                    </tr>
                    <tr>
                        <td>002</td>
                        <td>Barista</td>
                        <td>Food & Beverage</td>
                        <td>John Cruz</td>
                        <td>3</td>
                        <td>Additional staff for weekend operations</td>
                        <td><span class="status open">Pending</span></td>
                        <td>October 23, 2025</td>
                        <td>
                            <button class="btn-action approve">Approve</button>
                            <button class="btn-action decline">Decline</button>
                        </td>
                    </tr>
                    <tr>
                        <td>003</td>
                        <td>Maintenance Staff</td>
                        <td>Engineering</td>
                        <td>Carla Dizon</td>
                        <td>5</td>
                        <td>Urgent need for facility expansion</td>
                        <td><span class="status close">Declined</span></td>
                        <td>October 24, 2025</td>
                        <td>
                            <button class="btn-action approve">Approve</button>
                            <button class="btn-action decline">Decline</button>
                        </td>
                    </tr>
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
