<?php

session_start();

if (isset($_SESSION['userType'])) {
    if (!in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
        header("location: ../error.php");
        die();
    }
} else {
    header("location: ../error.php");
    die();
}

$maincssVersion = filemtime('../styles/custom/main-style.css');
$pagecssVersion = filemtime('../styles/custom/pages/profile-style.css');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup and Restore | SALIKSIK</title>
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

    <?php include_once '../includes/header.php' ?>

    <section class="admin-masthead">
        <div class="container">
            <h1>Backup and Restore</h1>
            <p class="masthead-subtitle">Create database backups or restore from a previous backup</p>
        </div>
    </section>

    <section class="py-4">
        <div class="container px-3">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="admin-panel">
                        <div class="admin-panel-title">Backup Database</div>
                        <p style="color: var(--muted); font-size: .9rem;">Click the button below to create a backup of the system database. The backup file will be stored as a <strong>.sql</strong> file.</p>
                        <form action="../src/process/dbbackup.php">
                            <button type="submit" class="admin-btn"><i class="fas fa-download me-2"></i>Backup</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="admin-panel">
                        <div class="admin-panel-title">Restore Database</div>
                        <p style="color: var(--muted); font-size: .9rem;">Click the button below to restore a backup of the system database from a <strong>.sql</strong> file.</p>
                        <form action="../src/process/dbrestore.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <input class="form-control admin-input" type="file" name="backupFile" accept=".sql" required>
                            </div>
                            <button type="submit" class="admin-btn-warning"><i class="fas fa-upload me-2"></i>Restore</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Footer section-->

    <?php include_once '../includes/footer.php' ?>


    <script src="../scripts/bootstrap/bootstrap.js"></script>

</body>

</html>