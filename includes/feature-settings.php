<?php

// Central feature-toggle loader. Include AFTER connection.php (it will
// lazily pull in the DB connection itself when included standalone).

if (!isset($connection)) {
    require_once __DIR__ . '/connection.php';
}

$SITE_SETTINGS = array();

$settingsResult = $connection->query('SELECT setting_key, setting_value FROM site_settings');
while ($settingRow = $settingsResult->fetch_assoc()) {
    $SITE_SETTINGS[$settingRow['setting_key']] = (int) $settingRow['setting_value'];
}
$settingsResult->free();

function feature_enabled(string $featureKey): bool
{
    global $SITE_SETTINGS;
    return isset($SITE_SETTINGS[$featureKey]) && $SITE_SETTINGS[$featureKey] === 1;
}

// True for any staff-level account (admin or super admin)
function user_is_staff(): bool
{
    return isset($_SESSION['userType']) && in_array($_SESSION['userType'], array('admin', 'super_admin'), true);
}

// True only for the developer super-admin account
function user_is_super_admin(): bool
{
    return isset($_SESSION['userType']) && $_SESSION['userType'] === 'super_admin';
}
