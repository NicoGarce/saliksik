<?php

session_start();

include '../../includes/connection.php';

if (isset($_SESSION['userType'])) {
    if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
        header("Location: ../../admin/submissions.php");
    }
} else {
    header("location: ../../error.php");
    die();
}

if (mysqli_connect_errno()) {
    exit("Failed to connect to the database: " . mysqli_connect_error());
};

if (isset($_GET['id'])) {
    $statement = $connection->prepare("SELECT * FROM department_list");
    $statement->execute();
    $result = $statement->get_result();
    $department_list = $result->fetch_all(MYSQLI_ASSOC);
    $statement->close();

    $statement = $connection->prepare("SELECT * FROM research_fields");
    $statement->execute();
    $result = $statement->get_result();
    $research_fields_list = $result->fetch_all(MYSQLI_ASSOC);
    $statement->close();

    $id = (int)$_GET['id'];
    $statement = $connection->prepare("SELECT * FROM file_information WHERE file_id = ?");
    $statement->bind_param("i", $id);
    $statement->execute();
    $result = $statement->get_result();
    $file = $result->fetch_assoc();
    $statement->close();

    if ($file == null || $file['user_id'] != $_SESSION['userid'] || $file['file_type'] == 'revised') {
        // not owner or invalid record
        header("Location: ../../users/my-submissions.php");
        exit();
    }

    if ($file['file_type'] === "thesis") {
        $statement = $connection->prepare("SELECT * FROM file_information AS fi JOIN research_information as ri ON ri.file_ref_id=fi.file_id JOIN coauthors_information AS ci ON fi.coauthor_group_id=ci.group_id LEFT JOIN (SELECT ref_id, feedback, returned_on FROM feedback_log WHERE log_id IN (SELECT MAX(log_id) FROM feedback_log GROUP BY ref_id)) AS fl ON fi.file_id = fl.ref_id WHERE file_id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();

        $fileInfo = $result->fetch_assoc();
        $statement->close();
        $researchFieldsArray = array_map('trim', explode(",", $fileInfo['research_fields']));
    } else {
        header("Location: ../../users/my-submissions.php");
        exit();
    }
} else {
    header("Location: ../../users/my-submissions.php");
    exit();
}

$statusLabel = ucfirst($fileInfo['status']);
$statusClass = 'status-' . str_replace(' ', '-', strtolower($fileInfo['status']));
$submittedDate = date('M j, Y', strtotime($fileInfo['submitted_on']));

$maincssVersion = filemtime('../../styles/custom/main-style.css');
$pagecssVersion = filemtime('../../styles/custom/pages/submission-forms-style.css');
$profilecssVersion = filemtime('../../styles/custom/pages/profile-style.css');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($fileInfo['research_title']); ?> | SALIKSIK</title>
    <script src="../../scripts/jquery/jquery-3.6.0.min.js"></script>
    <?php include_once '../../assets/fonts/google-fonts.php' ?>

    <link rel="stylesheet" href="../../styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo '../../styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo '../../styles/custom/pages/profile-style.css?id=' . $profilecssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo '../../styles/custom/pages/submission-forms-style.css?id=' . $pagecssVersion ?>" type="text/css">

    <link rel="apple-touch-icon" sizes="180x180" href="../../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon-16x16.png">
    <link rel="manifest" href="../../site.webmanifest">
    <link rel="mask-icon" href="../../safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

<?php include '../../includes/fontawesome.php' ?>
</head>


<body class="d-flex flex-column min-vh-100">

    <!--Header and Navigation section-->

    <?php include_once '../../includes/header.php' ?>

    <section class="view-masthead">
        <div class="container">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                <div>
                    <h1><?php echo htmlspecialchars($fileInfo['research_title']); ?></h1>
                    <div class="view-meta">
                        <span><i class="fas fa-user me-1"></i><?php echo htmlspecialchars($fileInfo['file_uploader']); ?></span>
                        <span><i class="far fa-calendar me-1"></i>Submitted <?php echo $submittedDate; ?></span>
                        <?php if (!empty($fileInfo['feedback'])): ?>
                        <span><i class="fas fa-comment-dots me-1"></i>Has admin feedback</span>
                        <?php endif; ?>
                    </div>
                </div>
                <span class="status-badge <?php echo $statusClass; ?>">
                    <i class="fas fa-circle" style="font-size:.45rem;"></i><?php echo $statusLabel; ?>
                </span>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="container px-3">
            <div class="view-panel">
                <?php
                if ($fileInfo['status'] == 'pending') {
                    include_once '../../includes/view-submission-forms/thesisDissertationPanel.php';
                } else if ($fileInfo['status'] == 'for revision') {
                    include_once '../../includes/view-revision-forms/thesisDissertationPanel.php';
                }
                ?>
            </div>
        </div>
    </section>

    <!--Footer-->

    <?php include_once '../../includes/footer.php' ?>
    <script src="../../scripts/bootstrap/bootstrap.js"></script>

    <script>
        $(function() {
            /* Desktop "Submission Details" column becomes an always-visible summary card */
            $(".view-panel .row > .col-lg-2").addClass("view-side-details").removeClass("d-none d-md-none d-lg-block");
            /* Remove the duplicate mobile-only details block */
            $(".view-panel .row.d-lg-none").hide();
            /* Stop browser autofill from highlighting name fields */
            $(".view-panel form").attr("autocomplete", "off");
        });
    </script>

</body>

</html>
