<?php

session_start();

if (!isset($_SESSION['isLoggedIn'])) {
    header("location: error.php");
    die();
}

require_once 'includes/feature-settings.php';
if (!feature_enabled('feature_submissions') && !user_is_staff()) {
    $disabledFeatureMessage = 'Research submissions are temporarily unavailable. Please check back later.';
    require_once 'includes/feature-disabled.php';
    die();
}

$maincssVersion = filemtime('styles/custom/main-style.css');
$pagecssVersion = filemtime('styles/custom/pages/submit-style.css');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit</title>
    <?php include_once 'assets/fonts/google-fonts.php' ?>

    <script src="./scripts/jquery/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/pages/submit-style.css?id=' . $pagecssVersion ?>" type="text/css">

    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#5bbad5">

    <!-- Primary Meta Tags -->
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <meta name="title" content="SALIKSIK: UPHSL Research Repository">
    <meta name="description" content="SALIKSIK: UPHSL Research Repository is an online tool and database where you can view, upload and download materials pertaining to research outputs of the university. It allows you to have access to a wide array of research materials in terms of a given time period, particular college/department, or research areas. It also provides access to the annual reports, research competency development program, institutional research agenda and other relevant research documents of the Research Center." />
    <meta name="keywords" content="repository, research, researches, research repository, perpetual help, uphsl, perpetual help system, perpetual binan, university of perpetual help system laguna, institutional repository, journals, theses, thesis, dissertations, uphsl thesis, saliksik uphsl, saliksik, perpetual help system, serking de orayom, mico sta maria, hazel anne datuin, arveey nickole almazan, marc lloyd menguito" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.saliksikuphsl.org/">
    <meta property="og:title" content="SALIKSIK: UPHSL Research Repository">
    <meta property="og:description" content="SALIKSIK: UPHSL Research Repository is an online tool and database where you can view, upload and download materials pertaining to research outputs of the university. It allows you to have access to a wide array of research materials in terms of a given time period, particular college/department, or research areas. It also provides access to the annual reports, research competency development program, institutional research agenda and other relevant research documents of the Research Center.">
    <meta property="og:image" content="./assets/images/core/saliksik-meta-preview.png" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://www.saliksikuphsl.org/">
    <meta property="twitter:title" content="SALIKSIK: UPHSL Research Repository">
    <meta property="twitter:description" content="SALIKSIK: UPHSL Research Repository is an online tool and database where you can view, upload and download materials pertaining to research outputs of the university. It allows you to have access to a wide array of research materials in terms of a given time period, particular college/department, or research areas. It also provides access to the annual reports, research competency development program, institutional research agenda and other relevant research documents of the Research Center.">
    <meta property="twitter:image" content="./assets/images/core/saliksik-meta-preview.png">

<?php include 'includes/fontawesome.php' ?>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include_once 'includes/header.php' ?>

    <section class="submit-masthead">
        <div class="container">
            <h1>Submit your research</h1>
            <p class="masthead-subtitle">Share your work with the UPHSL research community</p>
        </div>
    </section>

    <section class="submit-research">
        <div class="container py-4 px-3">

            <div class="row g-4">

                <div class="col-lg-3 d-none d-lg-block">
                    <div class="submit-sidebar">
                        <div class="submit-sidebar-title">On This Page</div>
                        <a class="submit-nav-item active" id="submitText">
                            <i class="fas fa-file-upload"></i>Submit
                        </a>
                        <a class="submit-nav-item" id="submissionGuidelinesText">
                            <i class="fas fa-clipboard-list"></i>Submission Form and Guidelines
                        </a>
                    </div>
                </div>

                <div class="col-lg-9 col-md-12" id="submitPanel">
                    <div class="submit-panel">
                        <h2 class="my-2">Submit</h2>
                        <hr class="submit-divider">
                        <h2 class="my-4">Why should I submit my work?</h2>
                        <p>Submitting your work to UPHSL research repository will help the university in its improved document management system through which the tracking and storing electronic documents such as PDFs, word processing files and digital images of paper-based content becomes more accessible and efficient.</p>
                        <hr class="submit-divider">
                        <h2 class="my-4">What are the benefits of having my research on the Repository?</h2>
                        <p>The benefits include:</p>
                        <ol>
                            <li>Ease of submitting research as terminal requirement making you secure your graduation clearance from the Research Center.</li>
                            <li>Quick access to your research works in case you need it for academic purposes.</li>
                            <li>Facilitation of review of related literature and studies which are produced by the student and faculty researchers of the university.</li>
                            <li>Ease of accessing university research materials online wherever you are.</li>
                        </ol>
                        <hr class="submit-divider">
                        <h2 class="my-4">How do I submit my research?</h2>
                        <p>To submit a copy of your work you are required to complete the Submission Form. Please read the submission guidelines first by clicking the button below.</p>
                        <div class="text-center mt-4">
                            <button type="button" class="submit-cta" id="buttonToSubmission">
                                <i class="fas fa-arrow-right"></i>Submit Research
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 col-md-12" id="submissionGuidelinesPanel" hidden>
                    <div class="submit-panel">
                        <h1 class="my-2">Submission Form and Guidelines</h1>
                        <p class="py-3">The following guidelines shall be observed before submitting your work:</p>
                        <ol>
                            <li>Ensure that you have followed the research format of your college.</li>
                            <li>Check the completeness of contents, correctness of contents in terms of grammar and punctuation as well as compliance with your department/college research requirements.</li>
                            <li>Upload your document only in the assigned folder or link for your college/department.</li>
                            <li>The filename format should be:
                                <div class="filename-example">
                                    researcher/group leader's name (surname and first name)_title_academic year<br><br>
                                    <strong>Example:</strong> Dela Cruz Mark_UPHSL Research Repository_AY2021-2022
                                </div>
                            </li>
                        </ol>
                        <hr class="submit-divider">
                        <div class="col-lg-12">
                            <h2 class="my-4 fw-bold">Submit your research</h2>
                            <p>To submit a copy of your research, click the button below.</p>
                            <div class="text-center">
                                <a href="submission-forms.php" class="submit-cta text-decoration-none my-3">
                                    <i class="fas fa-file-alt"></i>Submission Form
                                </a>
                            </div>
                            <p class="contact-note my-2">Please contact <a href="mailto:research@uphsl.edu.ph" target="_blank">research@uphsl.edu.ph</a> if you have any further queries regarding thesis submission.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php include_once 'includes/footer.php' ?>

    <script>
        $(document).ready(function() {

            function setActivePanel(panelId) {
                $('#submitPanel').prop('hidden', true);
                $('#submissionGuidelinesPanel').prop('hidden', true);
                $('#' + panelId).prop('hidden', false);
                $('.submit-nav-item').removeClass('active');
                if (panelId === 'submitPanel') {
                    $('#submitText').addClass('active');
                } else {
                    $('#submissionGuidelinesText').addClass('active');
                }
            }

            /* on load */
            $('#submitText').addClass('active');

            $('#submitText').click(function() {
                setActivePanel('submitPanel');
            });

            $('#submissionGuidelinesText').click(function() {
                setActivePanel('submissionGuidelinesPanel');
            });

            $('#buttonToSubmission').click(function() {
                setActivePanel('submissionGuidelinesPanel');
                window.scrollTo(0, 0);
            });
        });
    </script>
    <script src="scripts/bootstrap/bootstrap.js"></script>

</body>

</html>