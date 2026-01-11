<?php
session_start();
include("connections.php");

$client_id = "YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com";
$client_secret = "YOUR_GOOGLE_CLIENT_SECRET";
$redirect_uri = "http://localhost/google_login_callback.php"; // match same as in login.php

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Exchange code for token
    $token_response = file_get_contents("https://oauth2.googleapis.com/token", false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query([
                'code' => $code,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri' => $redirect_uri,
                'grant_type' => 'authorization_code'
            ])
        ]
    ]));

    $token_data = json_decode($token_response, true);
    $access_token = $token_data['access_token'];

    // Get user info
    $user_info = file_get_contents("https://www.googleapis.com/oauth2/v2/userinfo", false, stream_context_create([
        'http' => ['header' => "Authorization: Bearer $access_token"]
    ]));

    $user = json_decode($user_info, true);
    $email = $user['email'];
    $name = $user['name'];

    // Check if user exists
    $stmt = $connections->prepare("SELECT * FROM login_accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Register automatically as applicant
        $account_type = 'applicant';
        $stmt_insert = $connections->prepare("INSERT INTO login_accounts (email, full_name, account_type) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("sss", $email, $name, $account_type);
        $stmt_insert->execute();
    }

    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = 'applicant';
    $_SESSION['login-status'] = 'Valid';

    header("Location: applicant/dashboard.php");
    exit;
}
?>
