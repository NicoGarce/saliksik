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

include '../includes/connection.php';

if (mysqli_connect_errno()) {
    exit("Failed to connect to the database: " . mysqli_connect_error());
};

/* Fetch all of this user's submissions in one query */
$stmt = $connection->prepare("SELECT
        fi.file_id, fi.file_type, fi.status, fi.submitted_on, fi.published_on,
        fl.feedback, fl.returned_on,
        ri.resource_type, ri.researchers_category, ri.research_unit,
        ri.research_title, ri.research_abstract,
        ii.infographic_title, ii.infographic_publication_date, ii.infographic_description,
        ji.journal_title, ji.journal_subtitle, ji.department, ji.journal_description
    FROM file_information AS fi
    LEFT JOIN research_information AS ri ON ri.file_ref_id = fi.file_id
    LEFT JOIN journal_information AS ji ON ji.file_ref_id = fi.file_id
    LEFT JOIN infographic_information AS ii ON ii.file_ref_id = fi.file_id
    LEFT JOIN reports_information AS rp ON rp.file_ref_id = fi.file_id
    LEFT JOIN (SELECT ref_id, feedback, returned_on FROM feedback_log WHERE log_id IN (SELECT MAX(log_id) FROM feedback_log GROUP BY ref_id)) AS fl ON fi.file_id = fl.ref_id
    WHERE fi.user_id = ? AND (fi.file_type != 'revised' OR fi.status = 'revised')
    ORDER BY FIELD(fi.status, 'pending', 'for revision', 'revised', 'published'), fi.submitted_on DESC");

$userid = (int)$_SESSION['userid'];
$stmt->bind_param("i", $userid);
$stmt->execute();
$allSubmissions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$submissions = array('pending' => array(), 'for revision' => array(), 'revised' => array(), 'published' => array());
foreach ($allSubmissions as $s) {
    if (isset($submissions[$s['status']])) {
        $submissions[$s['status']][] = $s;
    }
}

function submission_card(array $s): string
{
    $fileId = (int)$s['file_id'];
    $type = $s['file_type'];
    $title = '';
    $kickerParts = array();

    if ($type === 'thesis') {
        $title = $s['research_title'];
        foreach (array('resource_type', 'researchers_category', 'research_unit') as $k) {
            if (!empty($s[$k])) $kickerParts[] = htmlspecialchars($s[$k]);
        }
    } elseif ($type === 'journal') {
        $title = $s['journal_title'];
        if (!empty($s['department'])) $kickerParts[] = htmlspecialchars($s['department']);
    } elseif ($type === 'infographic') {
        $title = $s['infographic_title'];
        if (!empty($s['infographic_publication_date'])) $kickerParts[] = htmlspecialchars(date('Y', strtotime($s['infographic_publication_date'])));
    } else {
        $title = $title ?: ucfirst($type);
    }
    $title = $title ?: 'Untitled';
    $kicker = implode(' &middot; ', $kickerParts);

    $status = $s['status'];

    ob_start();
    ?>
    <div class="submission-card">
        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
            <span class="card-kicker"><?= $kicker ?></span>
            <?php if ($status === 'pending'): ?>
                <a href="../users/submissions/view.php?id=<?= $fileId ?>" class="editReviseButton"><i class="fas fa-edit me-1"></i>Edit</a>
            <?php elseif ($status === 'for revision'): ?>
                <a href="../users/submissions/view.php?id=<?= $fileId ?>" class="editReviseButton"><i class="fas fa-pen me-1"></i>Revise</a>
            <?php endif; ?>
        </div>

        <?php if ($status === 'published'): ?>
            <a href="../repository/view-article.php?id=<?= $fileId ?>" class="card-title-link d-block mb-2"><?= htmlspecialchars($title) ?></a>
        <?php else: ?>
            <h4 style="font-size:1.05rem; font-weight:700; color:var(--navy-900); margin-bottom:.5rem;"><?= htmlspecialchars($title) ?></h4>
        <?php endif; ?>

        <?php if (!empty($s['research_abstract']) && $status === 'published'): ?>
            <p style="font-size:.86rem;" class="mb-2"><?= nl2br(htmlspecialchars(mb_strimwidth($s['research_abstract'], 0, 220, '...'))) ?></p>
        <?php elseif (!empty($s['infographic_description']) && $status === 'published'): ?>
            <p style="font-size:.86rem;" class="mb-2"><?= nl2br(htmlspecialchars(mb_strimwidth($s['infographic_description'], 0, 220, '...'))) ?></p>
        <?php elseif (!empty($s['journal_description']) && $status === 'published'): ?>
            <p style="font-size:.86rem;" class="mb-2"><?= nl2br(htmlspecialchars(mb_strimwidth($s['journal_description'], 0, 220, '...'))) ?></p>
        <?php endif; ?>

        <?php if ($status === 'pending'): ?>
            <p class="card-date mb-0"><i class="far fa-clock me-1"></i>Submitted on <?= date('M j, Y, g:i a', strtotime($s['submitted_on'])) ?></p>
        <?php elseif ($status === 'for revision'): ?>
            <p class="card-date mb-0"><i class="far fa-clock me-1"></i>Returned on <?= date('M j, Y, g:i a', strtotime($s['returned_on'] ?? $s['submitted_on'])) ?></p>
        <?php elseif ($status === 'published'): ?>
            <p class="card-date mb-0"><i class="fas fa-check-circle me-1" style="color:#16a34a;"></i>Published on <?= date('M j, Y', strtotime($s['published_on'] ?? $s['submitted_on'])) ?></p>
        <?php endif; ?>

        <?php if (($status === 'for revision' || $status === 'revised') && !empty($s['feedback'])): ?>
            <div class="feedback-box">
                <p class="feedback-label mb-1"><i class="fas fa-comment-dots me-1"></i>Admin Feedback</p>
                <p class="mb-0"><?= nl2br(htmlspecialchars($s['feedback'])) ?></p>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
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
    <title>My Submissions | SALIKSIK</title>
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
                <strong>File submission successful!</strong> Wait for your submission to be approved by the administration. You can view its status below.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); endif;?>

            <div class="row g-4">

                <?php if (count($submissions['pending']) + count($submissions['for revision']) + count($submissions['revised']) > 0): ?>
                <div class="col-12">
                    <div class="admin-panel" style="padding: 1.5rem 1.75rem;">
                        <?php if (count($submissions['pending']) > 0): ?>
                        <div class="mb-3">
                            <h4 class="submissions-section-title"><i class="fas fa-hourglass-half"></i> Pending Approval <span class="admin-badge ms-1"><?= count($submissions['pending']) ?></span></h4>
                            <?php foreach ($submissions['pending'] as $s) echo submission_card($s); ?>
                        </div>
                        <?php endif; ?>

                        <?php if (count($submissions['for revision']) > 0): ?>
                        <div class="<?= count($submissions['pending']) > 0 ? 'mt-4 ' : '' ?>mb-3">
                            <h4 class="submissions-section-title"><i class="fas fa-rotate-left"></i> For Revision <span class="admin-badge ms-1"><?= count($submissions['for revision']) ?></span></h4>
                            <?php foreach ($submissions['for revision'] as $s) echo submission_card($s); ?>
                        </div>
                        <?php endif; ?>

                        <?php if (count($submissions['revised']) > 0): ?>
                        <div class="<?= count($submissions['pending']) + count($submissions['for revision']) > 0 ? 'mt-4' : '' ?>">
                            <h4 class="submissions-section-title"><i class="fas fa-file-circle-check"></i> Revised <span class="admin-badge ms-1"><?= count($submissions['revised']) ?></span></h4>
                            <?php foreach ($submissions['revised'] as $s) echo submission_card($s); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="col-12">
                    <div class="admin-panel text-center" style="padding: 3rem 2rem;">
                        <i class="far fa-folder-open fa-2x mb-3" style="color: var(--muted); opacity:.45;"></i>
                        <h5 style="font-weight:800;">No active submissions</h5>
                        <p class="text-muted mb-0" style="font-size:.9rem;">Submit your research using the <strong>New Submission</strong> button above.</p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="col-12">
                    <div class="admin-panel" style="padding: 1.5rem 1.75rem;">
                        <h4 class="submissions-section-title"><i class="fas fa-book-open"></i> Published Works <span class="admin-badge ms-1"><?= count($submissions['published']) ?></span></h4>
                        <?php if (count($submissions['published']) > 0): ?>
                            <?php foreach ($submissions['published'] as $s) echo submission_card($s); ?>
                        <?php else: ?>
                            <p class="empty-note mb-1"><i class="far fa-bookmark me-2"></i>Nothing published yet.</p>
                        <?php endif; ?>
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
