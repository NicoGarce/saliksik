<?php

session_start();

if (!isset($_SESSION['isLoggedIn']) || !in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
    header("location: ../../error.php");
    die();
}

include '../../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['userId'] ?? 0);

    if ($userId <= 0) {
        header("location: ../../admin/profile.php");
        die();
    }

    /* prevent self-suspension */
    if ($userId == $_SESSION['userid']) {
        $_SESSION['suspendAccountError'] = 'You cannot suspend your own account.';
        header("location: ../../admin/profile.php");
        die();
    }

    /* toggle is_suspended */
    $stmt = $connection->prepare("UPDATE users SET is_suspended = NOT is_suspended WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    $_SESSION['suspendAccountSuccess'] = 'Account status updated.';
}

header("location: ../../admin/profile.php");
die();
