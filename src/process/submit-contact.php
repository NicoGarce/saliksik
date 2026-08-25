<?php

session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $subject === '' || $message === '') {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
    exit;
}

include '../../includes/connection.php';

if (mysqli_connect_errno()) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$stmt = mysqli_prepare($connection, 'INSERT INTO contact_submissions (name, email, subject, message) VALUES (?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $subject, $message);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => 'success', 'message' => 'Message sent successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save message']);
}

mysqli_stmt_close($stmt);
mysqli_close($connection);

?>
