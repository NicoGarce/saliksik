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
        $_SESSION['deleteAccountError'] = 'Invalid user.';
        header("location: ../../admin/profile.php");
        die();
    }

    /* prevent self-deletion */
    if ($userId == $_SESSION['userid']) {
        $_SESSION['deleteAccountError'] = 'You cannot delete your own account.';
        header("location: ../../admin/profile.php");
        die();
    }

    /* prevent deleting super_admin unless you are super_admin */
    $check = $connection->prepare("SELECT user_type FROM users WHERE user_id = ?");
    $check->bind_param("i", $userId);
    $check->execute();
    $row = $check->get_result()->fetch_assoc();
    $check->close();

    if ($row && $row['user_type'] === 'super_admin' && $_SESSION['userType'] !== 'super_admin') {
        $_SESSION['deleteAccountError'] = 'Only a Super Admin can delete another Super Admin.';
        header("location: ../../admin/profile.php");
        die();
    }

    $stmt = $connection->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    $_SESSION['deleteAccountSuccess'] = 'Account deleted successfully.';
}

header("location: ../../admin/profile.php");
die();
