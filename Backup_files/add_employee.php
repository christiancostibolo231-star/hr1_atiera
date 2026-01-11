<?php
include __DIR__ . '/connections.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $full_name = trim($_POST['full_name']);
    $position = trim($_POST['position']);

    // Check for duplicates
    $check = $connections->prepare("SELECT * FROM employees WHERE employee_id = ?");
    $check->bind_param("s", $employee_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Employee ID already exists!'); window.location.href='employee_dashboard.php';</script>";
        exit;
    }

    $stmt = $connections->prepare("INSERT INTO employees (employee_id, full_name, position) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $employee_id, $full_name, $position);

    if ($stmt->execute()) {
        echo "<script>alert('Employee added successfully!'); window.location.href='employee_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error adding employee: " . addslashes($stmt->error) . "'); window.location.href='employee_dashboard.php';</script>";
    }

    $stmt->close();
    $connections->close();
}
?>
