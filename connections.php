<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$host = "localhost";
$user = "root";           // change if using cPanel (often "atiera_user" or similar)
$pass = "";               // your MySQL password
$dbname = "hr1";       // your database name

// Create connection
$connections = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($connections->connect_error) {
    die("❌ Connection failed: " . $connections->connect_error);
}

// Optional: set charset to UTF-8
$connections->set_charset("utf8mb4");
?>
