<?php

session_start();

if (isset($_SESSION['userType'])) {
    if ($_SESSION['userType'] !== "super_admin") {
        header("location: ../error.php");
        die();
    }
} else {
    header("location: ../error.php");
    die();
}

require_once '../includes/feature-settings.php';

$maincssVersion = filemtime('../styles/custom/main-style.css');
$pagecssVersion = filemtime('../styles/custom/pages/profile-style.css');

$settingsResult = $connection->query('SELECT setting_key, setting_value, setting_label, setting_description FROM site_settings ORDER BY setting_key');
$allSettings = $settingsResult->fetch_all(MYSQLI_ASSOC);
$settingsResult->free();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Settings | SALIKSIK</title>
    <?php include_once '../assets/fonts/google-fonts.php' ?>
    <script src="../scripts/jquery/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo '../styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo '../styles/custom/pages/profile-style.css?id=' . $pagecssVersion ?>" type="text/css">

    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
    <link rel="mask-icon" href="../safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

<?php include '../includes/fontawesome.php' ?>
</head>

<body class="d-flex flex-column min-vh-100">

    <!--Header and Navigation section-->

    <?php include_once '../includes/header.php' ?>

    <section class="admin-masthead">
        <div class="container">
            <h1>Developer Settings</h1>
            <p class="masthead-subtitle">Configure platform features and system behavior</p>
        </div>
    </section>

    <section class="py-4">
        <div class="container px-3">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <?php if (isset($_SESSION['settingsSaved'])) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Settings saved successfully!</strong> Feature changes take effect immediately.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; unset($_SESSION['settingsSaved']); ?>

                    <?php if (isset($_SESSION['settingsError'])) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Could not save settings.</strong> Please try again.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; unset($_SESSION['settingsError']); ?>

                    <div class="admin-panel">
                        <div class="admin-panel-title">Platform Features</div>
                        <p style="color: var(--muted); font-size: .9rem;">Toggle platform features on or off. Changes apply instantly for everyone except administrators and super administrators.</p>

                        <form action="../src/process/update-feature-settings.php" method="POST">
                            <?php foreach ($allSettings as $setting) : ?>
                                <div class="admin-toggle-row">
                                    <div class="admin-toggle-label">
                                        <h6><?php echo htmlspecialchars($setting['setting_label'] ?? $setting['setting_key']); ?></h6>
                                        <small><?php echo htmlspecialchars($setting['setting_description'] ?? ''); ?></small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="toggle-<?php echo htmlspecialchars($setting['setting_key']); ?>"
                                            name="settings[<?php echo htmlspecialchars($setting['setting_key']); ?>]"
                                            <?php echo ((int) $setting['setting_value'] === 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="toggle-<?php echo htmlspecialchars($setting['setting_key']); ?>">
                                            <?php echo ((int) $setting['setting_value'] === 1) ? 'Enabled' : 'Disabled'; ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="text-end mt-4">
                                <button type="submit" class="admin-btn">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Footer-->

    <?php include_once '../includes/footer.php' ?>
    <script src="../scripts/bootstrap/bootstrap.js"></script>

    <script>
        $(document).ready(function() {
            $(".feature-toggle-row .form-check-input").change(function() {
                $(this).siblings(".form-check-label").text($(this).is(":checked") ? "Enabled" : "Disabled");
            });
        });
    </script>

</body>

</html>
