<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json; charset=UTF-8");

require_once "connections.php";

// ✅ Include the PDF parser library
require __DIR__ . '/vendor/autoload.php';
use Smalot\PdfParser\Parser;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit;
}

// --- Get current date ---
$today = date("Y-m-d");
$year  = date("Y");
$month = date("m");
$day   = date("d");

// --- Find the last applicant number (total applicants so far) ---
$result = $connections->query("SELECT COUNT(*) AS total FROM applicants");
$row = $result->fetch_assoc();
$totalApplicants = intval($row['total']) + 1; // next applicant number

// --- Build applicant code ---
$applicant_code = sprintf("AIDN%s%s%s%03d", $year, $month, $day, $totalApplicants);

// --- Gather POST data ---
$firstName = $_POST['First_Name'] ?? '';
$middleInitial = $_POST['Middle_Initial'] ?? '';
$lastName = $_POST['Last_Name'] ?? '';
$gender = $_POST['Gender'] ?? '';
$birthday = $_POST['Birthday'] ?? '';
$email = $_POST['Email'] ?? '';
$password = $_POST['Password'] ?? '';
$phone = $_POST['Phone_Number'] ?? '';
$contact = $_POST['Contact_Information'] ?? '';
$address = $_POST['Address'] ?? '';
$jobPosition = $_POST['Job_Position'] ?? '';
$createdAt = date("Y-m-d H:i:s");

// --- Handle Resume Upload ---
if (!isset($_FILES['resume']) || $_FILES['resume']['error'] != UPLOAD_ERR_OK) {
    echo json_encode(["status" => "error", "message" => "Please upload a valid PDF resume."]);
    exit;
}

$uploadDir = __DIR__ . "/uploads/resumes/";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$fileExt = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
if ($fileExt !== "pdf") {
    echo json_encode(["status" => "error", "message" => "Only PDF files are allowed."]);
    exit;
}

$fileName = "resume_" . uniqid() . ".pdf";
$targetFile = $uploadDir . $fileName;

if (!move_uploaded_file($_FILES['resume']['tmp_name'], $targetFile)) {
    echo json_encode(["status" => "error", "message" => "Failed to upload resume."]);
    exit;
}

// ✅ Parse the uploaded PDF resume and extract text
try {
    $parser = new Parser();
    $pdf = $parser->parseFile($targetFile);
    $resume_text = strtolower($pdf->getText());

    // Optional: save extracted resume text for screening later
    $resumeExtractPath = __DIR__ . "/uploads/resumes/extracted/";
    if (!is_dir($resumeExtractPath)) mkdir($resumeExtractPath, 0777, true);
    file_put_contents($resumeExtractPath . $fileName . ".txt", $resume_text);

    // ✅ --- Simple Keyword Matching Logic ---
    $jobKeywords = [
        'Front Desk Receptionist' => ['customer service', 'front desk', 'communication', 'receptionist', 'hotel'],
        'Housekeeping' => ['cleaning', 'laundry', 'sanitation', 'housekeeping', 'attention to detail'],
        'Cook' => ['cooking', 'kitchen', 'food preparation', 'menu', 'culinary'],
        'Security Guard' => ['security', 'safety', 'surveillance', 'guard', 'report'],
        'Waiter' => ['customer service', 'serving', 'restaurant', 'order taking', 'table'],
    ];

    $keywords = $jobKeywords[$jobPosition] ?? [];
    $matched = 0;

    foreach ($keywords as $word) {
        if (str_contains($resume_text, $word)) {
            $matched++;
        }
    }

    // If 50%+ of keywords matched, mark as Qualified
    if (count($keywords) > 0) {
        $matchRate = $matched / count($keywords);
        $status = $matchRate >= 0.5 ? 'Qualified' : 'Not Qualified';
    } else {
        $status = 'Pending'; // no rule found
    }

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error reading resume: " . $e->getMessage()]);
    exit;
}

// --- Insert applicant record ---
$stmt = $connections->prepare("INSERT INTO applicants 
    (applicant_code, first_name, middle_initial, last_name, gender, birthday, email, password, phone_number, contact_information, address, job_position, resume_file, status, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("sssssssssssssss", 
    $applicant_code, $firstName, $middleInitial, $lastName, $gender, $birthday, 
    $email, $password, $phone, $contact, $address, $jobPosition, $fileName, $status, $createdAt
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Application submitted successfully!",
        "applicant_code" => $applicant_code,
        "qualification_status" => $status
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$connections->close();
?>
