<?php
/**
 * connections.php
 * Multi-database connection setup for HR1 System
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ===============================
// ✅ DATABASE CONFIGURATION
// ===============================

$db_host = "localhost";
$db_user = "root";
$db_pass = "";

// List of all module databases
$db_names = [
    'auth'         => 'hr1_auth_db',          // 🆕 Authentication & login system
    'recruitment'  => 'hr1_recruitment_db',
    'application'  => 'hr1_application_db',
    'onboarding'   => 'hr1_onboarding_db',
    'performance'  => 'hr1_performance_db',
    'recognition'  => 'hr1_recognition_db'
];

// ===============================
// ✅ CREATE CONNECTIONS
// ===============================

$hr1_auth         = new mysqli($db_host, $db_user, $db_pass, $db_names['auth']);
$hr1_recruitment  = new mysqli($db_host, $db_user, $db_pass, $db_names['recruitment']);
$hr1_application  = new mysqli($db_host, $db_user, $db_pass, $db_names['application']);
$hr1_onboarding   = new mysqli($db_host, $db_user, $db_pass, $db_names['onboarding']);
$hr1_performance  = new mysqli($db_host, $db_user, $db_pass, $db_names['performance']);
$hr1_recognition  = new mysqli($db_host, $db_user, $db_pass, $db_names['recognition']);

// ===============================
// ✅ MAIN / DEFAULT CONNECTION
// (Change this if another module should be default)
// ===============================
$connections = $hr1_auth; // Default = Authentication DB

// ===============================
// ✅ CONNECTION CHECK
// ===============================

$all_connections = [
    'auth'         => $hr1_auth,
    'recruitment'  => $hr1_recruitment,
    'application'  => $hr1_application,
    'onboarding'   => $hr1_onboarding,
    'performance'  => $hr1_performance,
    'recognition'  => $hr1_recognition
];

foreach ($all_connections as $name => $conn) {
    if ($conn->connect_errno) {
        die("❌ Database connection failed for '$name' (" . $db_names[$name] . "): " . $conn->connect_error);
    }
}

// ===============================
// ✅ OPTIONAL: CHARACTER SET
// ===============================
foreach ($all_connections as $conn) {
    $conn->set_charset("utf8mb4");
}

// ===============================
// ✅ READY FOR USE
// Example usage:
// $result = $hr1_onboarding->query("SELECT * FROM employees");
// ===============================
?>
