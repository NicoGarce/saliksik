<?php

session_start();

include '../../includes/connection.php';

if (mysqli_connect_errno()) {
    exit("Failed to connect to the database: " . mysqli_connect_error());
}

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'super_admin') {
    header("location: ../../error.php");
    die();
}

require_once '../../includes/feature-settings.php';

try {
    $statement = $connection->prepare('UPDATE site_settings SET setting_value = ? WHERE setting_key = ?');

    foreach ($SITE_SETTINGS as $settingKey => $settingValue) {
        $newValue = isset($_POST['settings'][$settingKey]) && $_POST['settings'][$settingKey] == 'on' ? 1 : 0;
        $statement->bind_param('is', $newValue, $settingKey);
        $statement->execute();
    }
    $statement->close();

    $_SESSION['settingsSaved'] = true;
} catch (mysqli_sql_exception $exception) {
    $_SESSION['settingsError'] = true;
}

header("location: ../../admin/settings.php");
exit();
