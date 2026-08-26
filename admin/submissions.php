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
$profileadminjs = filemtime('../scripts/custom/profile-admin.js');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submissions</title>
    <script src="../scripts/jquery/jquery-3.6.0.min.js"></script>
    <script src="<?php echo '../scripts/custom/profile-admin.js?id=' . $profileadminjs ?>" type="module"></script>
    <?php include_once '../assets/fonts/google-fonts.php' ?>

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
            <h1>Submissions</h1>
            <p class="masthead-subtitle">Review, approve, and manage research submissions</p>
        </div>
    </section>

    <section class="py-4">
        <div class="container px-3">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <?php if (isset($_SESSION['deleteSuccess'])) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Record deleted successfully!</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; unset($_SESSION['deleteSuccess']); ?>

                    <div class="row g-3 mb-4">
                        <div class="col">
                            <div class="adminPageCountColumn" id="pending-container">
                                <p><i class="fas fa-hourglass-half me-1"></i>For Approval</p>
                                <h1 class="display-4">0</h1>
                            </div>
                        </div>
                        <div class="col">
                            <div class="adminPageCountColumn" id="revision-container">
                                <p><i class="fas fa-pen me-1"></i>For Revision</p>
                                <h1 class="display-4">0</h1>
                            </div>
                        </div>
                        <div class="col">
                            <div class="adminPageCountColumn" id="revised-container">
                                <p><i class="fas fa-check-circle me-1"></i>Revised</p>
                                <h1 class="display-4">0</h1>
                            </div>
                        </div>
                        <div class="col">
                            <div class="adminPageCountColumn" id="published-container">
                                <p><i class="fas fa-book-open me-1"></i>Published</p>
                                <h1 class="display-4">0</h1>
                            </div>
                        </div>
                        <div class="col">
                            <div class="adminPageCountColumn" id="submissions-container">
                                <p><i class="fas fa-folder-open me-1"></i>All Submissions</p>
                                <h1 class="display-4">0</h1>
                            </div>
                        </div>
                    </div>

                    <div class="admin-panel">
                        <div class="admin-panel-title">Search & Filter</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="admin-search-group">
                                    <input type="search" placeholder="Search submissions" id="search-submissions-admin" name="title_query">
                                    <button type="button" id="admin-search-button"><i class="fas fa-search me-1"></i>Search</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select admin-select" id="submission-status-dropdown" name="status_view">
                                    <option value="pending" selected>For Approval</option>
                                    <option value="for revision">For Revision</option>
                                    <option value="revised">Revised</option>
                                    <option value="published">Published</option>
                                    <option value="submissions">All Submissions</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select admin-select" id="submission-category-dropdown" name="sort_by">
                                    <option value="All Category" selected>All Category</option>
                                    <option value="Resource Type">Resource Type</option>
                                    <option value="Research Unit">Research Unit</option>
                                    <option value="Researcher's Category">Researcher's Category</option>
                                </select>
                            </div>
                        </div>

                        <div class="admin-panel-title mt-3">Results</div>
                        <div id="results-container">
                            <p class="empty-note mb-1"><i class="fas fa-inbox me-2"></i>Loading submissions...</p>
                        </div>
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