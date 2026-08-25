<?php

session_start();

if (!isset($_SESSION['isLoggedIn']) || !in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
    header("location: ../../error.php");
    die();
}

include '../../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['userId'] ?? 0);
    $firstName = trim($_POST['textFieldFirstName'] ?? '');
    $lastName = trim($_POST['textFieldLastName'] ?? '');
    $email = trim($_POST['textFieldEmail'] ?? '');
    $newPassword = trim($_POST['textFieldNewPassword'] ?? '');

    if ($userId <= 0 || $firstName === '' || $lastName === '' || $email === '') {
        $_SESSION['editAccountError'] = 'Please fill in all required fields.';
        header("location: ../../admin/profile.php");
        die();
    }

    /* check email uniqueness for other users */
    $check = $connection->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $check->bind_param("si", $email, $userId);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $check->close();
        $_SESSION['editAccountError'] = 'Email already exists for another account.';
        header("location: ../../admin/profile.php");
        die();
    }
    $check->close();

    if ($newPassword !== '') {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $connection->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ? WHERE user_id = ?");
        $stmt->bind_param("ssssi", $firstName, $lastName, $email, $hashed, $userId);
    } else {
        $stmt = $connection->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $firstName, $lastName, $email, $userId);
    }

    $stmt->execute();
    $stmt->close();

    $_SESSION['editAccountSuccess'] = 'Account updated successfully.';
}

header("location: ../../admin/profile.php");
die();
