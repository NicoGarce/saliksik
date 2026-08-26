<?php

session_start();

if (isset($_SESSION['userType'])) {
    if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
        header("Location: ../admin/profile.php");
    }
} else {
    header("location: ../error.php");
    die();
}

include '../includes/connection.php'; // covers profilePanel.php, libraryPanel.php, submissionsPanel.php

if (mysqli_connect_errno()) {
    exit("Failed to connect to the database: " . mysqli_connect_error());
};

$maincssVersion = filemtime('../styles/custom/main-style.css');
$pagecssVersion = filemtime('../styles/custom/pages/profile-style.css');
$userSubmissionsJSVersion = filemtime('../scripts/custom/user-submissions.js');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Submissions</title>
    <?php include_once '../assets/fonts/google-fonts.php' ?>
    <!-- jquery CDN -->
    <script src="../scripts/jquery/jquery-3.6.0.min.js"></script>
    <script src="<?php echo '../scripts/custom/user-submissions.js?id=' . $userSubmissionsJSVersion ?>" type="module"></script>
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
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h1>My Submissions</h1>
                    <p class="masthead-subtitle">Track the status of your research submissions</p>
                </div>
                <a href="../submission-forms.php" class="admin-btn"><i class="fas fa-plus me-1"></i> New Submission</a>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="container px-3">

            <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>File submission successful!</strong> Wait for your submission to be approved by the administration.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); endif;?>

            <div class="row g-4">
                <div class="col-12">
                    <div class="submissions admin-panel" style="padding: 1.5rem 1.75rem;">
                        <div class="pendingApproval mb-3" id="pending-container-wrap">
                            <h4 class="submissions-section-title"><i class="fas fa-hourglass-half"></i> Pending Approval</h4>
                            <div id="pending-container"></div>
                        </div>
                        <div class="forRevision mb-3" id="revision-container-wrap">
                            <h4 class="submissions-section-title"><i class="fas fa-rotate-left"></i> For Revision</h4>
                            <div id="revision-container"></div>
                        </div>
                        <div class="revised" id="revised-container-wrap">
                            <h4 class="submissions-section-title"><i class="fas fa-file-circle-check"></i> Revised</h4>
                            <div id="revised-container"></div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="published admin-panel" style="padding: 1.5rem 1.75rem;">
                        <h4 class="submissions-section-title"><i class="fas fa-book-open"></i> Published Works</h4>
                        <div class="publishedWorks" id="published-container"></div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!--Footer-->

    <?php include_once '../includes/footer.php' ?>
    <script src="../scripts/bootstrap/bootstrap.js"></script>

</body>

</html>