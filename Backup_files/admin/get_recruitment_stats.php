<?php
require_once __DIR__ . "/../connections.php";

$response = ["open_jobs" => 0, "closed_jobs" => 0];

$stmt = $connections->prepare("SELECT status, COUNT(*) as count FROM jobs GROUP BY status");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $status = strtolower($row['status']);
    if ($status === 'open') {
        $response["open_jobs"] = $row['count'];
    } elseif ($status === 'closed') {
        $response["closed_jobs"] = $row['count'];
    }
}
$stmt->close();

header("Content-Type: application/json");
echo json_encode($response);
