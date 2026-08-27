<?php

session_start();

include './includes/connection.php';
if (!isset($_SESSION['isLoggedIn'])) {
    header("location: index.php");
    die();
}

$maincssVersion = filemtime('styles/custom/main-style.css');
$pagecssVersion = filemtime('styles/custom/pages/home-style.css');

$statement = $connection->prepare("SELECT ri.resource_type, COUNT(ri.file_ref_id) AS count FROM research_information AS ri GROUP BY ri.resource_type");
$statement->execute();
$result = $statement->get_result();
$thesis_count = $result->fetch_all(MYSQLI_ASSOC);
$statement->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | SALIKSIK</title>
    <?php include_once 'assets/fonts/google-fonts.php' ?>

    <link rel="stylesheet" href="styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/pages/home-style.css?id=' . $pagecssVersion ?>" type="text/css">

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

    <!--Header and Navigation section-->

    <?php include_once 'includes/header.php' ?>

    <!--Masthead-->

    <section class="masthead p-5">
        <div class="container">
            <div class="jumbotron">
                <h1 id="masthead-title-text">Welcome to SALIKSIK: UPHSL Research Repository</h1>
                <p class="mt-3" id="masthead-content-text" style="text-align:start">SALIKSIK: UPHSL Research Repository is an online tool and database where you can view, upload and download materials pertaining to research outputs of the university. It allows you to have access to a wide array of research materials in terms of a given time period, particular college/department, or research areas. It also provides access to the annual reports, research competency development program, institutional research agenda and other relevant research documents of the Research Center.</p>
            </div>
        </div>
    </section>

    <!--Search section-->

    <section class="search-section p-5">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8">
                    <form class="search-bar-group" method="GET" action="./repository.php">
                        <div class="search-bar-icon"><i class="fas fa-search"></i></div>
                        <input type="search" class="search-bar-input" id="home-search-bar" placeholder="Search researches, theses, journals..." aria-label="Search the repository" name="title_query">
                        <button class="search-bar-button" id="button-search">Search</button>
                    </form>
                </div>
            </div>
            <div class="row justify-content-center g-3">
                <div class="col-auto">
                    <button class="search-option-card" data-bs-toggle="modal" data-bs-target="#search-modal" type="button"><i class="fas fa-sliders-h"></i>Advanced Search</button>
                </div>
                <div class="col-auto">
                    <a href="./research/browse-researches.php" class="search-option-card text-decoration-none"><i class="fas fa-book-open"></i>Browse Researches</a>
                </div>
            </div>

                                    <!-- Advanced Search Modal -->
            <div class="modal fade" id="search-modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header py-3">
                            <h5 class="modal-title"><i class="fas fa-sliders-h me-2"></i>Advanced Search</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="modal-body p-0" id="advanced-search" name="advanced-filter" method="GET" action="./repository.php">
                            <div class="p-3 pb-2">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="adv-label" for="advanced_word_search">with <strong>all</strong> of the words</label>
                                        <input class="form-control adv-input" id="advanced_word_search" type="text" name="word_search" placeholder="e.g. artificial intelligence education">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="adv-label" for="advanced_phrase_search">with the <strong>exact phrase</strong></label>
                                        <input class="form-control adv-input" id="advanced_phrase_search" type="text" name="phrase_search" placeholder="e.g. machine learning">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="adv-label" for="advanced_words_exist">with <strong>at least one</strong> of the words</label>
                                        <input class="form-control adv-input" id="advanced_words_exist" type="text" name="word_exists" placeholder="e.g. thesis dissertation">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="adv-label" for="advanced_words_not_exists"><strong>without</strong> the words</label>
                                        <input class="form-control adv-input" id="advanced_words_not_exists" type="text" name="word_not_exists" placeholder="e.g. deprecated">
                                    </div>
                                </div>
                            </div>
                            <div class="px-3 pb-2">
                                <div class="adv-group rounded-3 px-3 py-2">
                                    <label class="adv-label mb-1">Where do the words occur?</label>
                                    <div class="d-flex gap-4 flex-wrap">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="exists" value="anywhere" id="radio-button-anywhere" checked>
                                            <label class="form-check-label small" for="radio-button-anywhere">Anywhere in the article</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="exists" value="title" id="radio-button-title">
                                            <label class="form-check-label small" for="radio-button-title">In the title</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-3 pb-2">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="adv-label" for="advanced_author_search">Authored by</label>
                                        <input class="form-control adv-input" id="advanced_author_search" type="text" name="authored_by" placeholder='e.g. "Dela Cruz"'>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="adv-label">Dated between</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input class="form-control adv-input" type="text" name="advanced_from_year" id="advanced_from_year" placeholder="From">
                                            <span class="text-muted fw-bold">&mdash;</span>
                                            <input class="form-control adv-input" type="text" name="advanced_to_year" id="advanced_to_year" placeholder="To">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-3 pb-3 pt-1">
                                <button class="btn w-100 adv-search-btn py-2" type="submit"><i class="fas fa-search me-2"></i>Search Articles</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!--Research Fields Section-->

    <section class="research-fields p-5">
        <div class="container">
            <h1 id="research-field-title-text">Research Fields</h1>
            <hr class="hr-home">
            <div class="row g-3">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="research/research-field.php?q=Accountancy and Marketing" class="research-field-card">
                        <img src="assets/images/research-fields/accountancy-marketing.png" class="research-fields-logos" alt="Accountancy and Marketing">
                        <span>Accountancy and Marketing</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="research/research-field.php?q=Arts and Humanities" class="research-field-card">
                        <img src="assets/images/research-fields/arts-humanities.png" class="research-fields-logos" alt="Arts and Humanities">
                        <span>Arts and Humanities</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="research/research-field.php?q=Educational Management" class="research-field-card">
                        <img src="assets/images/research-fields/educational-management.png" class="research-fields-logos" alt="Educational Management">
                        <span>Educational Management</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="research/research-field.php?q=Education and Social Sciences" class="research-field-card">
                        <img src="assets/images/research-fields/education-social-sciences.png" class="research-fields-logos" alt="Education and Social Sciences">
                        <span>Education and Social Sciences</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="research/research-field.php?q=Business Management" class="research-field-card">
                        <img src="assets/images/research-fields/business-management.png" class="research-fields-logos" alt="Business Management">
                        <span>Business Management</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="research/research-field.php?q=Health and Sciences" class="research-field-card">
                        <img src="assets/images/research-fields/health-sciences.png" class="research-fields-logos" alt="Health and Sciences">
                        <span>Health and Sciences</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="research/research-field.php?q=Information Technology" class="research-field-card">
                        <img src="assets/images/research-fields/it.png" class="research-fields-logos" alt="Information Technology">
                        <span>Information Technology</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="research/research-field.php?q=Law and Justice System" class="research-field-card">
                        <img src="assets/images/research-fields/law-justice-system.png" class="research-fields-logos" alt="Law and Justice System">
                        <span>Law and Justice System</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="research/research-field.php?q=Tourism and Hospitality" class="research-field-card">
                        <img src="assets/images/research-fields/tourism-hospitality.png" class="research-fields-logos" alt="Tourism and Hospitality">
                        <span>Tourism and Hospitality</span>
                    </a>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col d-flex justify-content-center">
                    <a class="view-more-link" href="./research/browse-research-fields.php">View More <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!--Repository Metrics-->

    <section class="repository-metrics-wrapper py-5">
        <div class="container">
            <div class="repository-metrics-card">
                <h2 class="repository-metrics-title text-center mb-4">Repository Metrics</h2>
                <div class="row g-3 justify-content-center">
                    <?php foreach ($thesis_count as $key => $row) : ?>
                        <div class="col-lg-3 col-sm-6 repository-metrics-column-item text-center">
                            <div class="repository-metrics-icon-ring">
                                <img src="assets/images/repository-metrics/research-file.png" class="repository-metrics-logos">
                            </div>
                            <p class="repository-metrics-counter"><?php echo number_format($row['count']) ?></p>
                            <p class="repository-metrics-p-text"><?php echo $row['resource_type']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="row mt-4">
                    <div class="col d-flex justify-content-center">
                        <a class="view-more-statistics-link" href="./statistics.php">View Full Statistics <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Promotion-->

    <section class="promotion-section py-5">
        <div class="container">
            <div class="promotion-card">
                <div class="row align-items-center g-4">
                    <div class="col-lg-5">
                        <span class="promotion-eyebrow">Why SALIKSIK?</span>
                        <h2 class="promotion-title">Why submit your research?</h2>
                        <p class="promotion-desc">Submitting your work to UPHSL research repository will help the university in its improved document management system through which the tracking and storing electronic documents becomes more accessible and efficient.</p>
                        <div class="promotion-features">
                            <div class="promotion-feature">
                                <i class="fas fa-globe"></i>
                                <span>Wider visibility for your research</span>
                            </div>
                            <div class="promotion-feature">
                                <i class="fas fa-shield-alt"></i>
                                <span>Permanent, secure digital archiving</span>
                            </div>
                            <div class="promotion-feature">
                                <i class="fas fa-chart-line"></i>
                                <span>Track citations and downloads</span>
                            </div>
                        </div>
                        <a href="./about.php" class="btn promotion-cta mt-3">Learn More</a>
                    </div>
                    <div class="col-lg-7">
                        <img src="assets/images/promotion/promotion.jpg" class="promotion-image img-fluid" alt="Promotion">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Footer section-->

    <?php include_once 'includes/footer.php' ?>
    <script src="scripts/bootstrap/bootstrap.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        // code to cache search queries
        // var searchbarValue = sessionStorage.getItem("searchbarValue");
        // $("#home-search-bar").on("input", function() {
        //     searchbarValue = this.value;
        //     sessionStorage.setItem("searchbarValue", searchbarValue);
        // });
        // var modalRadio = JSON.parse(sessionStorage.getItem("modalRadio")) || {};
        // var $modalRadio = $("#advanced-search :radio");
        // $modalRadio.on("change", function() {
        //     $modalRadio.each(function() {
        //         modalRadio[this.id] = this.checked;
        //     });
        //     sessionStorage.setItem("modalRadio", JSON.stringify(modalRadio));
        // });

        // $.each(modalRadio, function(key, value) {
        //     $("#" + key).prop("checked", value);
        // });

        // var modalInputs = JSON.parse(sessionStorage.getItem("modalInputs")) || {};
        // var $modalInputs = $("#advanced-search :text");
        // $modalInputs.on("change", function() {
        //     $modalInputs.each(function() {
        //         modalInputs[this.id] = this.value;
        //     });
        //     sessionStorage.setItem("modalInputs", JSON.stringify(modalInputs));
        // });

        // $.each(modalInputs, function(key, value) {
        //     $("#" + key).prop("value", value);
        // });
    </script>
</body>

</html>