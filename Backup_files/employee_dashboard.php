<?php
include __DIR__ . '/connections.php';

// Count employees
$count_query = $connections->query("SELECT COUNT(*) AS total FROM employees");
$count_row = $count_query->fetch_assoc();
$total_employees = $count_row['total'] ?? 0;

// Fetch all employees
$employees = $connections->query("SELECT * FROM employees ORDER BY id DESC");

// Function to get next Employee ID
function getNextEmployeeID($conn) {
    $res = $conn->query("SELECT employee_id FROM employees ORDER BY id DESC LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $num = (int) str_replace('EIDN-', '', $row['employee_id']);
        $newNum = $num + 1;
    } else {
        $newNum = 1000000;
    }
    return "EIDN-" . str_pad($newNum, 7, "0", STR_PAD_LEFT);
}

$next_id = getNextEmployeeID($connections);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .dashboard-card { border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .table-container { max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body>
<div class="container py-5">
    <h2 class="text-center mb-4 fw-bold">Employee Dashboard</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card dashboard-card text-center p-4 bg-primary text-white">
                <h4>Total Employees</h4>
                <h2><?= $total_employees ?></h2>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card dashboard-card p-4">
                <h5>Add New Employee</h5>
                <form action="add_employee.php" method="POST" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Employee ID</label>
                        <input type="text" name="employee_id" class="form-control" value="<?= $next_id ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Position</label>
                        <input type="text" name="position" class="form-control" required>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-success">Add Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card dashboard-card p-4">
        <h5>Employee List</h5>
        <div class="table-container mt-3">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Position</th>
                        <th>Date Hired</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $employees->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['employee_id']) ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['position']) ?></td>
                        <td><?= htmlspecialchars($row['date_hired']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if ($employees->num_rows === 0): ?>
                    <tr><td colspan="4" class="text-center text-muted">No employees found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
