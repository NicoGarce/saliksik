<?php

session_start();

include '../../includes/connection.php';

if (mysqli_connect_errno()) {
    exit("Failed to connect to the database: " . mysqli_connect_error());
};

if (!isset($_SESSION['isLoggedIn'])) {
    header("location: ../../index.php?location=".urlencode($_SERVER['REQUEST_URI']));
    die();
}

if (isset($_SESSION['userType'])) {
    if ($_SESSION['userType'] === "user") {
        header("location: ../../error.php");
        die();
    }
}

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

    if ($file == null) {
        header("Location: ../submissions.php");
        exit();
    }

    if ($file['file_type'] === "thesis") {
        $statement = $connection->prepare("SELECT * FROM file_information AS fi JOIN research_information AS ri ON ri.file_ref_id=fi.file_id JOIN coauthors_information AS ci ON fi.coauthor_group_id=ci.group_id LEFT JOIN (SELECT ref_id, feedback, returned_on FROM feedback_log WHERE log_id IN (SELECT MAX(log_id) FROM feedback_log GROUP BY ref_id)) AS fl ON fi.file_id = fl.ref_id WHERE file_id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();

        $fileInfo = $result->fetch_assoc();
        $statement->close();
        $researchFieldsArray = array_map('trim', explode(",", $fileInfo['research_fields']));
    } else if ($file['file_type'] === "journal") {
        $statement = $connection->prepare("SELECT * FROM file_information AS fi JOIN journal_information AS ji ON ji.file_ref_id=fi.file_id LEFT JOIN (SELECT ref_id, feedback, MAX(returned_on) AS 'returned_on' FROM feedback_log GROUP BY ref_id) AS fl ON fi.file_id = fl.ref_id WHERE file_id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();

        $fileInfo = $result->fetch_assoc();
        $statement->close();
    } else if ($file['file_type'] === "infographic") {
        $statement = $connection->prepare("SELECT * FROM file_information AS fi JOIN infographic_information AS ii ON ii.file_ref_id=fi.file_id JOIN coauthors_information AS ci ON fi.coauthor_group_id=ci.group_id LEFT JOIN (SELECT ref_id, feedback, returned_on FROM feedback_log WHERE log_id IN (SELECT MAX(log_id) FROM feedback_log GROUP BY ref_id)) AS fl ON fi.file_id = fl.ref_id WHERE file_id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();

        $fileInfo = $result->fetch_assoc();
        $statement->close();
    } else if ($file['file_type'] === "report") {
        $statement = $connection->prepare("SELECT * FROM file_information AS fi JOIN reports_information AS rp ON rp.file_ref_id=fi.file_id LEFT JOIN (SELECT ref_id, feedback, returned_on FROM feedback_log WHERE log_id IN (SELECT MAX(log_id) FROM feedback_log GROUP BY ref_id)) AS fl ON fi.file_id = fl.ref_id WHERE file_id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();

        $fileInfo = $result->fetch_assoc();
        $statement->close();
    } else {
        header("Location: ../submissions.php");
        exit();
    }

    if ($fileInfo == null) {
        header("Location: ../submissions.php");
        exit();
    }
} else {
    die(); //GET['id'] is not defined;
}

$typeLabels = array(
    'thesis' => 'Thesis',
    'journal' => 'Journal',
    'infographic' => 'Infographic',
    'report' => 'Report'
);
$fileType = $fileInfo['file_type'];

/* Resolve the submission title per type */
if ($fileType === 'thesis') {
    $viewTitle = $fileInfo['research_title'];
} elseif ($fileType === 'journal') {
    $viewTitle = $fileInfo['journal_title'];
} elseif ($fileType === 'infographic') {
    $viewTitle = $fileInfo['infographic_title'];
} else {
    $viewTitle = $fileInfo['report_title'];
}
$viewTitle = $viewTitle ?: ('Untitled ' . $typeLabels[$fileType]);

$statusLabel = ucfirst($fileInfo['status']);
$statusClass = 'status-' . str_replace(' ', '-', strtolower($fileInfo['status']));
$submittedDate = date('M j, Y', strtotime($fileInfo['submitted_on']));
$publishedDate = !empty($fileInfo['published_on']) ? date('M j, Y', strtotime($fileInfo['published_on'])) : null;

$maincssVersion = filemtime('../../styles/custom/main-style.css');
$pagecssVersion = filemtime('../../styles/custom/pages/submission-forms-style.css');
$profilecssVersion = filemtime('../../styles/custom/pages/profile-style.css');
$feedbackControlJS = filemtime('../../scripts/custom/feedback-control.js');
$coauthorsDropdown = filemtime('../../scripts/custom/coauthors-dropdown.js');

?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($viewTitle); ?></title>
    <script src="../../scripts/jquery/jquery-3.6.0.min.js"></script>
    <script src="<?php echo '../../scripts/custom/coauthors-dropdown.js?id=' . $coauthorsDropdown ?>"></script>
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
                    <h1><?php echo htmlspecialchars($viewTitle); ?></h1>
                    <div class="view-meta">
                        <span><i class="fas fa-user me-1"></i><?php echo htmlspecialchars($fileInfo['file_uploader']); ?></span>
                        <span><i class="far fa-calendar me-1"></i>Submitted <?php echo $submittedDate; ?></span>
                        <?php if ($publishedDate): ?>
                        <span><i class="fas fa-check-circle me-1"></i>Published <?php echo $publishedDate; ?></span>
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
                if ($fileInfo['status'] == 'published') {
                    if ($fileType == 'thesis') {
                        include_once '../../includes/admin/view-published-forms/thesisDissertationPanel.php';
                    } else if ($fileType == 'journal') {
                        include_once '../../includes/admin/view-published-forms/researchJournalPanel.php';
                    } else if ($fileType == 'infographic') {
                        include_once '../../includes/admin/view-published-forms/infographicsPanel.php';
                    } else if ($fileType == 'report') {
                        include_once '../../includes/admin/view-published-forms/reportsPanel.php';
                    }
                } else if ($fileInfo['status'] == 'revised') {
                    include_once '../../includes/admin/view-revised-forms/thesisDissertationPanel.php';
                } else if ($fileInfo['status'] == 'for revision') {
                    include_once '../../includes/admin/view-revision-forms/thesisDissertationPanel.php';
                } else if ($fileInfo['status'] == 'pending') {
                    include_once '../../includes/admin/view-approval-forms/thesisDissertationPanel.php';
                }
                ?>
            </div>
        </div>
    </section>

    <!--Footer-->

    <?php include_once '../../includes/footer.php' ?>
    <script src="../../scripts/bootstrap/bootstrap.js"></script>
    <script src="<?php echo '../../scripts/custom/feedback-control.js?id=' . $feedbackControlJS ?>"></script>

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
