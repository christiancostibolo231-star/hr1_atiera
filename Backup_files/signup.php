<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/connections.php";

// ✅ Check if recruitment DB is connected
if (!isset($hr1_recruitment) || !($hr1_recruitment instanceof mysqli)) {
    die("Database connection error: recruitment DB not available");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ✅ Collect form inputs safely
    $first_name = trim($_POST['first_name'] ?? '');
    $middlename = trim($_POST['middlename'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        die("All required fields must be filled out.");
    }

    // ✅ Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // ✅ Check if email already exists
    $check = $hr1_recruitment->prepare("SELECT applicant_id FROM applicants WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered. Please login instead.'); window.location='login.php';</script>";
        exit;
    }
    $check->close();

    // ✅ Insert into applicants table
    $stmt = $hr1_recruitment->prepare("
        INSERT INTO applicants 
        (first_name, middlename, last_name, email, phone, password, application_date, status)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), 'Pending')
    ");
    if (!$stmt) {
        die("Prepare failed: " . $hr1_recruitment->error);
    }

    $stmt->bind_param("ssssss", $first_name, $middlename, $last_name, $email, $phone, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! You can now log in.'); window.location='login.php';</script>";
        exit;
    } else {
        die("Signup failed: " . $stmt->error);
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Applicant Signup | Atiera HR1</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center font-sans">

<!-- Signup Container -->
<div class="bg-white shadow-lg rounded-2xl w-full max-w-2xl flex overflow-hidden border border-gray-200">

  <!-- Left Side (Welcome Banner) -->
  <div class="hidden md:flex flex-col justify-center items-center bg-blue-600 text-white w-1/2 p-8">
    <i data-lucide="user-plus" class="w-16 h-16 mb-4"></i>
    <h2 class="text-2xl font-bold mb-2">Join Atiera Careers</h2>
    <p class="text-sm text-blue-100 text-center">Create your account to explore open positions and track your application status.</p>
  </div>

  <!-- Right Side (Signup Form) -->
  <div class="w-full md:w-1/2 p-8">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-xl font-bold text-gray-800">Applicant Signup</h2>
      <i data-lucide="briefcase" class="text-blue-600 w-6 h-6"></i>
    </div>

    <form method="POST" class="space-y-4">
      <div class="grid grid-cols-2 gap-4">
        <input type="text" name="first_name" placeholder="First Name" required class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 border rounded-lg p-2">
        <input type="text" name="middlename" placeholder="Middle Name" class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 border rounded-lg p-2">
        <input type="text" name="last_name" placeholder="Last Name" required class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 border rounded-lg p-2 col-span-2">
      </div>

      <input type="email" name="email" placeholder="Email Address" required class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 border rounded-lg p-2 w-full">
      <input type="text" name="phone" placeholder="Phone Number" class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 border rounded-lg p-2 w-full">
      <input type="password" name="password" placeholder="Password" required class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 border rounded-lg p-2 w-full">

      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
        Sign Up
      </button>

      <p class="text-center text-sm text-gray-600">
        Already have an account?
        <a href="login.php" class="text-blue-600 hover:underline">Login here</a>
      </p>
    </form>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  lucide.createIcons();
});
</script>

</body>
</html>
