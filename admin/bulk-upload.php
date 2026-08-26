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
$pagecssVersion = filemtime('../styles/custom/pages/bulk-upload-style.css');
$profilecssVersion = filemtime('../styles/custom/pages/profile-style.css');
$bulkJsVersion = filemtime('../scripts/custom/bulk-upload.js');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Upload</title>
    <?php include_once '../assets/fonts/google-fonts.php' ?>
    <script src="../scripts/jquery/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo '../styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo '../styles/custom/pages/profile-style.css?id=' . $profilecssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo '../styles/custom/pages/bulk-upload-style.css?id=' . $pagecssVersion ?>" type="text/css">
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

    <section class="bulk-masthead">
        <div class="container">
            <h1>Bulk Upload</h1>
            <p class="masthead-subtitle">Upload multiple research submissions at once using CSV and PDF files</p>
        </div>
    </section>

    <section class="py-4">
        <div class="container px-3">
            <div class="row justify-content-center">
                <div class="col-lg-11">

                    <!-- Step Indicator -->
                    <div class="bulk-steps" id="bulk-steps">
                        <div class="bulk-step active" data-step="1">
                            <div class="bulk-step-number">1</div>
                            <span class="bulk-step-label">Choose Type</span>
                        </div>
                        <div class="bulk-step-connector"></div>
                        <div class="bulk-step" data-step="2">
                            <div class="bulk-step-number">2</div>
                            <span class="bulk-step-label">Upload CSV</span>
                        </div>
                        <div class="bulk-step-connector"></div>
                        <div class="bulk-step" data-step="3">
                            <div class="bulk-step-number">3</div>
                            <span class="bulk-step-label">Upload PDFs</span>
                        </div>
                        <div class="bulk-step-connector"></div>
                        <div class="bulk-step" data-step="4">
                            <div class="bulk-step-number">4</div>
                            <span class="bulk-step-label">Assign & Review</span>
                        </div>
                        <div class="bulk-step-connector"></div>
                        <div class="bulk-step" data-step="5">
                            <div class="bulk-step-number">5</div>
                            <span class="bulk-step-label">Process</span>
                        </div>
                    </div>

                    <!-- ==================== STEP 1: Choose Type ==================== -->
                    <div class="bulk-step-content active" id="bulk-step-1">
                        <div class="admin-panel">
                            <div class="admin-panel-title">Select Submission Type</div>
                            <p style="color: var(--muted); font-size: .88rem; margin-bottom: 1.25rem;">Choose the type of research submission you want to bulk upload. Each type has its own CSV template with specific fields.</p>
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-3">
                                    <div class="bulk-type-card" data-type="thesis">
                                        <i class="fas fa-book"></i>
                                        <h6>Thesis / Dissertation</h6>
                                        <p>Capstone projects, theses, and dissertations with co-authors</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="bulk-type-card" data-type="journal">
                                        <i class="fas fa-newspaper"></i>
                                        <h6>Research Journal</h6>
                                        <p>Journal publications with volume and issue details</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="bulk-type-card" data-type="infographic">
                                        <i class="fas fa-image"></i>
                                        <h6>Infographics</h6>
                                        <p>Visual research outputs with editor information</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="bulk-type-card" data-type="report">
                                        <i class="fas fa-clipboard-list"></i>
                                        <h6>Reports</h6>
                                        <p>Annual reports, research agendas, and catalogs</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bulk-btn-nav">
                                <div></div>
                                <button class="btn btn-primary" id="btn-step1-next" disabled>
                                    Continue <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== STEP 2: Upload CSV ==================== -->
                    <div class="bulk-step-content" id="bulk-step-2">
                        <div class="admin-panel">
                            <div class="admin-panel-title">
                                <i class="fas fa-file-csv me-2" style="color: #16a34a;"></i>Upload CSV File
                            </div>

                            <div style="background: var(--navy-tint); border-radius: var(--radius-sm); padding: 1rem 1.25rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: .75rem;">
                                <i class="fas fa-info-circle" style="color: var(--navy-700); flex-shrink: 0;"></i>
                                <div style="font-size: .84rem; color: var(--ink);">
                                    Download the template first, fill in your data, then upload the completed CSV file. Each row represents one submission. The <strong>filename</strong> column should match your PDF file names.
                                </div>
                            </div>

                            <div style="margin-bottom: 1.25rem;">
                                <a href="#" class="bulk-btn-download" id="btn-download-template">
                                    <i class="fas fa-download"></i>
                                    Download <span id="template-type-label">Thesis</span> Template
                                </a>
                            </div>

                            <div class="bulk-dropzone" id="csv-dropzone">
                                <i class="fas fa-file-csv"></i>
                                <h6>Drop your CSV file here</h6>
                                <p>or <span class="browse-link">browse</span> to select a file</p>
                                <input type="file" id="csv-file-input" accept=".csv">
                            </div>

                            <div id="csv-file-info" style="display: none; margin-top: 1rem;">
                                <div class="bulk-file-item">
                                    <i class="fas fa-file-csv"></i>
                                    <span class="file-name" id="csv-file-name"></span>
                                    <span class="file-size" id="csv-file-size"></span>
                                    <button type="button" class="file-remove" id="csv-file-remove" title="Remove file"><i class="fas fa-times"></i></button>
                                </div>
                            </div>

                            <div id="csv-preview-container" style="display: none; margin-top: 1.25rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: .75rem;">
                                    <span style="font-weight: 700; font-size: .9rem; color: var(--navy-900);">
                                        Preview — <span id="csv-row-count">0</span> rows found
                                    </span>
                                    <span id="csv-error-count" style="font-size: .82rem; font-weight: 600; color: #dc2626; display: none;">
                                        <i class="fas fa-exclamation-triangle me-1"></i><span>0</span> rows with errors
                                    </span>
                                </div>
                                <div class="bulk-preview-wrap" id="csv-preview-table-wrap" style="max-height: 320px; overflow-y: auto;">
                                </div>
                            </div>

                            <div class="bulk-btn-nav">
                                <button class="btn btn-secondary" id="btn-step2-back">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </button>
                                <button class="btn btn-primary" id="btn-step2-next" disabled>
                                    Continue <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== STEP 3: Upload PDFs ==================== -->
                    <div class="bulk-step-content" id="bulk-step-3">
                        <div class="admin-panel">
                            <div class="admin-panel-title">
                                <i class="fas fa-file-pdf me-2" style="color: #dc2626;"></i>Upload PDF Files
                            </div>

                            <div style="background: var(--navy-tint); border-radius: var(--radius-sm); padding: 1rem 1.25rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: .75rem;">
                                <i class="fas fa-info-circle" style="color: var(--navy-700); flex-shrink: 0;"></i>
                                <div style="font-size: .84rem; color: var(--ink);">
                                    Upload the PDF files for your submissions. File names must match the <strong>filename</strong> column in your CSV (including the .pdf extension).
                                </div>
                            </div>

                            <div class="bulk-dropzone" id="pdf-dropzone">
                                <i class="fas fa-file-pdf"></i>
                                <h6>Drop your PDF files here</h6>
                                <p>or <span class="browse-link">browse</span> to select files — you can select multiple</p>
                                <input type="file" id="pdf-file-input" accept=".pdf" multiple>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 1rem; margin-bottom: .5rem;">
                                <span style="font-weight: 700; font-size: .9rem; color: var(--navy-900);">
                                    <span id="pdf-count">0</span> files uploaded
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="btn-clear-pdfs" style="display: none; font-size: .78rem; padding: .3rem .75rem;">
                                    <i class="fas fa-trash me-1"></i>Clear All
                                </button>
                            </div>
                            <ul class="bulk-file-list" id="pdf-file-list"></ul>

                            <div class="bulk-btn-nav">
                                <button class="btn btn-secondary" id="btn-step3-back">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </button>
                                <button class="btn btn-primary" id="btn-step3-next">
                                    Continue <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== STEP 4: Assign & Review ==================== -->
                    <div class="bulk-step-content" id="bulk-step-4">
                        <div class="admin-panel">
                            <div class="admin-panel-title">
                                <i class="fas fa-link me-2" style="color: var(--navy-700);"></i>Assign PDFs & Review
                            </div>

                            <div id="review-summary" class="row g-3 mb-3">
                                <div class="col-sm-4">
                                    <div class="bulk-summary-card">
                                        <div class="summary-number" id="summary-total">0</div>
                                        <div class="summary-label">Total Rows</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="bulk-summary-card">
                                        <div class="summary-number" id="summary-matched" style="color: #16a34a;">0</div>
                                        <div class="summary-label">PDF Matched</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="bulk-summary-card">
                                        <div class="summary-number" id="summary-unmatched" style="color: #dc2626;">0</div>
                                        <div class="summary-label">Unmatched</div>
                                    </div>
                                </div>
                            </div>

                            <p style="font-size: .84rem; color: var(--muted); margin-bottom: 1rem;">
                                Assign a PDF file to each row. Rows without a matched PDF will be skipped during processing.
                            </p>

                            <div id="assign-rows-container" style="max-height: 400px; overflow-y: auto;"></div>

                            <div class="bulk-btn-nav">
                                <button class="btn btn-secondary" id="btn-step4-back">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </button>
                                <button class="btn btn-primary" id="btn-step4-next">
                                    <i class="fas fa-paper-plane me-1"></i> Process Upload
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== STEP 5: Process ==================== -->
                    <div class="bulk-step-content" id="bulk-step-5">
                        <div class="admin-panel">
                            <div class="admin-panel-title">
                                <i class="fas fa-cog fa-spin me-2" style="color: var(--navy-700);"></i>Processing Upload
                            </div>

                            <div id="processing-active">
                                <div class="bulk-progress">
                                    <div class="bulk-progress-bar" id="process-progress-bar"></div>
                                </div>
                                <p class="bulk-progress-text" id="process-progress-text">Preparing...</p>

                                <div id="process-results" style="max-height: 350px; overflow-y: auto; margin-top: 1rem;"></div>
                            </div>

                            <div id="processing-done" style="display: none; text-align: center; padding: 2rem 0;">
                                <div style="width: 64px; height: 64px; border-radius: 50%; background: #dcfce7; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                    <i class="fas fa-check" style="color: #16a34a; font-size: 1.5rem;"></i>
                                </div>
                                <h5 style="color: var(--navy-900); font-weight: 700; margin-bottom: .5rem;">Upload Complete</h5>
                                <p style="color: var(--muted); font-size: .88rem; margin-bottom: 1.25rem;" id="done-summary-text"></p>
                                <div style="display: flex; gap: .75rem; justify-content: center;">
                                    <a href="../admin/submissions.php" class="btn btn-primary">
                                        <i class="fas fa-folder-open me-1"></i>View Submissions
                                    </a>
                                    <button class="btn btn-secondary" id="btn-start-over">
                                        <i class="fas fa-redo me-1"></i>Start Over
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php include_once '../includes/footer.php' ?>
    <script src="../scripts/bootstrap/bootstrap.js"></script>
    <script src="<?php echo '../scripts/custom/bulk-upload.js?id=' . $bulkJsVersion ?>"></script>
</body>
</html>
