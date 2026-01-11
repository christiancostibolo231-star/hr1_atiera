<?php
$host = "localhost";
$user = "hratier1";
$pass = ""; // check this — usually NOT empty!
$db   = "hr1_atieraHr1";

$connections = new mysqli($host, $user, $pass, $db);

if ($connections->connect_error) {
    die("❌ Database connection failed: " . $connections->connect_error);
}

$connections->set_charset("utf8mb4");

?>