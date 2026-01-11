<?php
session_start();
include __DIR__ . '/connections.php';

// ===============================
// VALIDATE REQUEST
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Invalid request method.');
}

// Collect applicant data
$first_name = trim($_POST['first_name'] ?? '');
$middle_name = trim($_POST['middle_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$birthdate = $_POST['birthdate'] ?? null;
$gender = $_POST['gender'] ?? '';
$job_id = intval($_POST['job_id'] ?? 0);

if (empty($first_name) || empty($last_name) || empty($email) || $job_id === 0) {
    exit('Missing required fields.');
}

// ===============================
// FILE UPLOAD HANDLING (RESUME)
// ===============================
$resume_path = null;
if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/uploads/resumes/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = time() . '_' . basename($_FILES['resume']['name']);
    $targetPath = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES['resume']['tmp_name'], $targetPath)) {
        $resume_path = 'uploads/resumes/' . $fileName;
    }
}

// ===============================
// INSERT / GET APPLICANT RECORD
// ===============================
$applicant_id = null;
$stmt = $connections->prepare("SELECT applicant_id FROM applicants WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($existing_id);
if ($stmt->fetch()) {
    $applicant_id = $existing_id; // already exists
}
$stmt->close();

if (!$applicant_id) {
    $stmt = $connections->prepare("
        INSERT INTO applicants 
        (first_name, middle_name, last_name, email, phone, address, birthdate, gender, resume_path)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssssssss", 
        $first_name, $middle_name, $last_name, $email, $phone, $address, $birthdate, $gender, $resume_path
    );
    $stmt->execute();
    $applicant_id = $stmt->insert_id;
    $stmt->close();
}

// ===============================
// INSERT APPLICATION RECORD
// ===============================
$stmt = $connections->prepare("
    INSERT INTO applications (applicant_id, job_id, stage, status)
    VALUES (?, ?, 'Screening', 'Active')
");
$stmt->bind_param("ii", $applicant_id, $job_id);
if ($stmt->execute()) {
    echo "Application submitted successfully!";
} else {
    echo "Error submitting application: " . $stmt->error;
}
$stmt->close();
?>
