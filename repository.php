<?php

session_start();

include 'includes/connection.php';

if (!isset($_SESSION['isLoggedIn'])) {
    header("location: index.php");
    die();
}

require_once 'includes/feature-settings.php';
if (!feature_enabled('feature_repository') && !user_is_staff()) {
    $disabledFeatureMessage = 'Repository browsing is temporarily unavailable. Please check back later.';
    require_once 'includes/feature-disabled.php';
    die();
}

// if (isset($_GET['page'])) {
//     $page = $_GET['page'];
// } else {
//     $page = 1;
// }
// $results_per_page = 5;
// $offset = ($page-1) * $results_per_page;  

// if (mysqli_connect_errno()) {
//     exit("Failed to connect to the database: " . mysqli_connect_error());
// };

// $query = "SELECT `file_id`,`file_type`,`file_name`,`file_dir`,`file_dir2`,`file_uploader`,`status`,`research_id`,ri.resource_type AS research_type,`researchers_category`,`research_unit`,`research_title`,`research_abstract`,`research_fields`,`keywords`,`publication_month`,`publication_day`,`publication_year`,ri.coauthors_count AS `research_coauthors_count`,ri.author_first_name AS researcher_first_name, ri.author_middle_initial AS researcher_middle_initial, ri.author_surname AS researcher_surname, ri.author_name_ext AS researcher_name_ext, ri.author_email AS researcher_email, `infographic_research_unit`, `infographic_researcher_category`,`infographic_publication_month`, `infographic_publication_year`, `infographic_title`, `infographic_description`, ii.author_first_name, ii.author_middle_initial, ii.author_surname, ii.author_ext, ii.author_email, ii.editor_first_name, ii.editor_middle_initial, ii.editor_surname, ii.editor_ext, ii.editor_email, ji.journal_title, ji.journal_subtitle, ji.department, ji.volume_number, ji.serial_issue_number, ji.ISSN, ji.journal_description, ji.chief_editor_first_name, ji.chief_editor_middle_initial, ji.chief_editor_last_name, ji.chief_editor_name_ext, ji.chief_editor_email, ci.coauthor1_first_name, ci.coauthor1_middle_initial, ci.coauthor1_surname, ci.coauthor1_name_ext, ci.coauthor1_email, ci.coauthor2_first_name, ci.coauthor2_middle_initial, ci.coauthor2_surname, ci.coauthor2_name_ext, ci.coauthor2_email, ci.coauthor3_first_name, ci.coauthor3_middle_initial, ci.coauthor3_surname, ci.coauthor3_name_ext, ci.coauthor3_email, ci.coauthor4_first_name, ci.coauthor4_middle_initial, ci.coauthor4_surname, ci.coauthor4_name_ext, ci.coauthor4_email FROM file_information AS fi LEFT JOIN research_information as ri ON ri.file_ref_id=fi.file_id LEFT JOIN journal_information AS ji ON ji.file_ref_id=fi.file_id LEFT JOIN infographic_information AS ii ON ii.file_ref_id=fi.file_id LEFT JOIN coauthors_information AS ci on ci.group_id = fi.coauthor_group_id WHERE fi.status = 'published'";

// if(isset($_POST['title_query']) && $_POST['title_query']!=''){
//     $search = " AND (ri.research_title LIKE '%{$_POST["title_query"]}%' OR ji.journal_title LIKE '%{$_POST["title_query"]}%' OR ii.infographic_title LIKE '%{$_POST["title_query"]}%')";
//     $query .= $search;
// }
// if(isset($_POST['publication_year'])){
//     $year = " AND (";
//     foreach($_POST['publication_year'] as $key => $value){
//         $year .= "ri.publication_year = $value OR ii.infographic_publication_year = $value";
//         if ($key < count($_POST['publication_year'])-1){
//             $year .= " OR ";
//         }
//     }
//     $year .= ") ";
//     $query .= $year;
// }
// if(isset($_POST['resource_type'])){
//     $resource_type = " AND (";
//     foreach($_POST['resource_type'] as $key => $value){
//         $resource_type .= "fi.file_type LIKE '$value' OR ri.resource_type LIKE '$value'";
//         if ($key < count($_POST['resource_type'])-1){
//             $resource_type .= " OR ";
//         }
//     }
//     $resource_type .= ") ";
//     $query .= $resource_type;
// }
// if(isset($_POST['research_field'])){
//     $research_field = " AND (";
//     foreach($_POST['research_field'] as $key => $value){
//         $research_field .= "ri.research_fields LIKE '%$value%'";
//         if ($key < count($_POST['research_field'])-1){
//             $research_field .= " OR ";
//         }
//     }
//     $research_field .= ") ";
//     $query .= $research_field;
// }
// if(isset($_POST['resource_unit'])){
//     $resource_unit = " AND (";
//     foreach($_POST['resource_unit'] as $key => $value){
//         $resource_unit .= "ri.research_unit LIKE '%$value%' OR ji.department LIKE '%$value%' OR ii.infographic_research_unit LIKE '%$value%'";
//         if ($key < count($_POST['resource_unit'])-1){
//             $resource_unit .= " OR ";
//         }
//     }
//     $resource_unit .= ") ";
//     $query .= $resource_unit;
// }

// $statement = $connection->prepare($query);
// $statement->execute();
// $result = $statement->get_result();
// $total_rows = mysqli_num_rows($result);
// $statement->close();

// $total_pages = ceil($total_rows/$results_per_page);

// $statement = $connection->prepare($query." ORDER BY fi.file_id ASC LIMIT ?, ?");
// $statement->bind_param("ii",$offset,$results_per_page);
// $statement->execute();
// $result = $statement->get_result();
// $published = $result->fetch_all(MYSQLI_ASSOC);
// $statement->close();

$maincssVersion = filemtime('styles/custom/main-style.css');
$pagecssVersion = filemtime('styles/custom/pages/repository-style.css');
$repositoryjs = filemtime('scripts/custom/repository.js');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repository</title>

    <?php include_once 'assets/fonts/google-fonts.php' ?>
    <script>
        // hides query parameters 
        if (typeof window.history.pushState == 'function') {
            window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF']; ?>');
        }
    </script>
    <script src="./scripts/jquery/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/pages/repository-style.css?id=' . $pagecssVersion ?>" type="text/css">

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

        <!-- Search Section -->
    <section class="search-section p-5">
        <div class="container">
            <div class="row justify-content-center mb-3">
                <div class="col-lg-8">
                    <h2 id="masthead-title-text" class="text-center mb-3">Search the Repository</h2>
                    <form class="search-bar-group" method="GET" action="/repository.php">
                        <div class="search-bar-icon"><i class="fas fa-search"></i></div>
                        <input type="search" class="search-bar-input" id="repository-search-bar" placeholder="Search researches, theses, journals..." aria-label="Search the repository" name="title_query" <?php echo (isset($_GET['title_query'])) ? "value='{$_GET['title_query']}'" : "" ?>>
                        <button type="button" class="search-bar-button" id="repository-search-button">Search</button>
                    </form>
                </div>
            </div>
            <div class="row justify-content-center g-3">
                <div class="col-auto">
                    <button class="search-option-card" data-bs-toggle="modal" data-bs-target="#search-modal" type="button"><i class="fas fa-sliders-h"></i>Advanced Search</button>
                </div>
                <div class="col-auto">
                    <a href="research/browse-researches.php" class="search-option-card text-decoration-none"><i class="fas fa-book-open"></i>Browse Researches</a>
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
                        <form class="modal-body p-0" id="advanced-search" name="advanced-filter" method="GET" action="/repository.php">
                            <div class="p-3 pb-2">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="adv-label" for="advanced_word_search">with <strong>all</strong> of the words</label>
                                        <input class="form-control adv-input" id="advanced_word_search" type="text" name="word_search" placeholder="e.g. artificial intelligence education" <?php echo (isset($_GET['word_search'])) ? "value='{$_GET['word_search']}'" : "" ?>>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="adv-label" for="advanced_phrase_search">with the <strong>exact phrase</strong></label>
                                        <input class="form-control adv-input" id="advanced_phrase_search" type="text" name="phrase_search" placeholder="e.g. machine learning" <?php echo (isset($_GET['phrase_search'])) ? "value='{$_GET['phrase_search']}'" : "" ?>>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="adv-label" for="advanced_words_exist">with <strong>at least one</strong> of the words</label>
                                        <input class="form-control adv-input" id="advanced_words_exist" type="text" name="word_exists" placeholder="e.g. thesis dissertation" <?php echo (isset($_GET['word_exists'])) ? "value='{$_GET['word_exists']}'" : "" ?>>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="adv-label" for="advanced_words_not_exists"><strong>without</strong> the words</label>
                                        <input class="form-control adv-input" id="advanced_words_not_exists" type="text" name="word_not_exists" placeholder="e.g. deprecated" <?php echo (isset($_GET['word_not_exists'])) ? "value='{$_GET['word_not_exists']}'" : "" ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="px-3 pb-2">
                                <div class="adv-group rounded-3 px-3 py-2">
                                    <label class="adv-label mb-1">Where do the words occur?</label>
                                    <div class="d-flex gap-4 flex-wrap">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="exists" value="anywhere" id="radio-button-anywhere" checked <?php echo (isset($_GET['exists']) && $_GET['exists'] == 'anywhere') ? "checked" : "" ?>>
                                            <label class="form-check-label small" for="radio-button-anywhere">Anywhere in the article</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="exists" value="title" id="radio-button-title" <?php echo (isset($_GET['exists']) && $_GET['exists'] == 'title') ? "checked" : "" ?>>
                                            <label class="form-check-label small" for="radio-button-title">In the title</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-3 pb-2">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="adv-label" for="advanced_author_search">Authored by</label>
                                        <input class="form-control adv-input" id="advanced_author_search" type="text" name="authored_by" placeholder='e.g. "Dela Cruz"' <?php echo (isset($_GET['authored_by'])) ? "value='{$_GET['authored_by']}'" : "" ?>>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="adv-label">Dated between</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input class="form-control adv-input" type="text" name="advanced_from_year" id="advanced_from_year" placeholder="From" <?php echo (isset($_GET['advanced_from_year'])) ? "value='{$_GET['advanced_from_year']}'" : "" ?>>
                                            <span class="text-muted fw-bold">&mdash;</span>
                                            <input class="form-control adv-input" type="text" name="advanced_to_year" id="advanced_to_year" placeholder="To" <?php echo (isset($_GET['advanced_to_year'])) ? "value='{$_GET['advanced_to_year']}'" : "" ?>>
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
<section class="search-section bg-white">
        <div class="container p-3">

            <div class="row">
                <form class="col-lg-2 d-none d-md-none d-lg-block" id="sidebar-search-filters" name="sidebar-filters">
                    <p class="fw-bold"><i class="fas fa-filter"></i> SEARCH FILTERS</p>
                    <hr>
                    <p class="side-menu-text fw-bold">Publication Year</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="2022" id="checkBox2022" name="publication_year[]">
                        <label class="form-check-label" for="checkBox2022">2022</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="2021" id="checkBox2021" name="publication_year[]">
                        <label class="form-check-label" for="checkBox2021">2021</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="2020" id="checkBox2020" name="publication_year[]">
                        <label class="form-check-label" for="checkBox2020">2020</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="2019" id="checkBox2019" name="publication_year[]">
                        <label class="form-check-label" for="checkBox2019">2019</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="2018" id="checkBox2018" name="publication_year[]">
                        <label class="form-check-label" for="checkBox2018">2018</label>
                    </div>
                    <a class="my-3 text-dark" data-bs-toggle="collapse" href="#customRangeCollapse" role="button" aria-expanded="false" aria-controls="collapseExample">Custom Range</a>
                    <div class="collapse" id="customRangeCollapse">
                        <div class="input-group my-3">
                            <input type="text" class="form-control" name="from_year" id="sidebar-from-year">
                            <input type="text" class="form-control" name="to_year" id="sidebar-to-year">
                        </div>
                        <button type="button" class="btn rounded-0" style="background-color: #012265; color:white; border-color:#012265">OK</button>

                    </div>
                    <hr>
                    <p class="side-menu-text fw-bold">Resource Type</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="dissertation" id="checkBoxDissertation" name="resource_type[]">
                        <label class="form-check-label" for="checkBoxDissertation">Dissertation</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="thesis" id="checkBoxThesis" name="resource_type[]">
                        <label class="form-check-label" for="checkBoxThesis">Thesis</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="capstone" id="checkBoxCapstone" name="resource_type[]">
                        <label class="form-check-label" for="checkBoxCapstone">Capstone</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="journal" id="checkBoxJournal" name="resource_type[]">
                        <label class="form-check-label" for="checkBoxJournal">Journal</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="infographics" id="checkBoxInfographics" name="resource_type[]">
                        <label class="form-check-label" for="checkBoxInfographics">Infographics</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="research_catalog" id="checkBoxResearchCatalog" name="resource_type[]">
                        <label class="form-check-label" for="checkBoxResearchCatalog">Research Catalog</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="annual_report" id="checkBoxAnnualReport" name="resource_type[]">
                        <label class="form-check-label" for="checkBoxAnnualReport">Annual Report</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="research_agenda" id="checkBoxResearchAgenda" name="resource_type[]">
                        <label class="form-check-label" for="checkBoxResearchAgenda">Research Agenda</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="research_competency_development_program" id="checkBoxRCDP" name="resource_type[]">
                        <label class="form-check-label" for="checkBoxRCDP">Research Competency Development Program</label>
                    </div>
                    <hr>
                    <p class="side-menu-text fw-bold">Research Field</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Accountancy and Marketing" id="checkBoxAccountancyMarketing" name="research_field[]">
                        <label class="form-check-label" for="checkBoxAccountancyMarketing">Accountancy and Marketing</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Arts and Humanities" id="checkBoxArtsHumanities" name="research_field[]">
                        <label class="form-check-label" for="checkBoxArtsHumanities">Arts and Humanities</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Aviation" id="checkBoxAviation" name="research_field[]">
                        <label class="form-check-label" for="checkBoxAviation">Aviation</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Educational Management" id="checkBoxEducationalManagement" name="research_field[]">
                        <label class="form-check-label" for="checkBoxEducationalManagement">Educational Management</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Education and Social Sciences" id="checkBoxEducationSocialSciences" name="research_field[]">
                        <label class="form-check-label" for="checkBoxEducationSocialSciences">Education and Social Sciences</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Engineering" id="checkBoxEngineering" name="research_field[]">
                        <label class="form-check-label" for="checkBoxEngineering">Engineering</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Business Management" id="checkBoxBusinessManagement" name="research_field[]">
                        <label class="form-check-label" for="checkBoxBusinessManagement">Business Management</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Health and Sciences" id="checkBoxHealthSciences" name="research_field[]">
                        <label class="form-check-label" for="checkBoxHealthSciences">Health and Sciences</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Information Technology" id="checkBoxIT" name="research_field[]">
                        <label class="form-check-label" for="checkBoxIT">Information Technology</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Law and Justice System" id="checkBoxLawJusticeSystem" name="research_field[]">
                        <label class="form-check-label" for="checkBoxLawJusticeSystem">Law and Justice System</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Maritime" id="checkBoxMaritime" name="research_field[]">
                        <label class="form-check-label" for="checkBoxMaritime">Maritime</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Tourism and Hospitality" id="checkBoxTourismHospitality" name="research_field[]">
                        <label class="form-check-label" for="checkBoxTourismHospitality">Tourism and Hospitality</label>
                    </div>
                    <hr>
                    <p class="side-menu-text fw-bold">Research Unit</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Basic Education" id="checkBoxBasicEducation" name="resource_unit[]">
                        <label class="form-check-label" for="checkBoxBasicEducation">Basic Education</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Senior High School" id="checkBoxSeniorHighSchool" name="resource_unit[]">
                        <label class="form-check-label" for="checkBoxSeniorHighSchool">Senior High School</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Arts and Sciences" id="checkBoxArtsSciences" name="resource_unit[]">
                        <label class="form-check-label" for="checkBoxArtsSciences">Arts and Sciences</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Business and Accountancy" id="checkBoxBusinessAccountancy" name="resource_unit[]">
                        <label class="form-check-label" for="checkBoxBusinessAccountancy">Business and Accountancy</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Criminology" id="checkBoxCriminology" name="resource_unit[]">
                        <label class="form-check-label" for="checkBoxCriminology">Criminology</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Computer Studies" id="checkBoxComputerStudies" name="resource_unit[]">
                        <label class="form-check-label" for="checkBoxComputerStudies">Computer Studies</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Education" id="checkBoxEducation" name="resource_unit[]">
                        <label class="form-check-label" for="checkBoxEducation">Education</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Engineering" id="checkBoxEngineering" name="resource_unit[]">
                        <label class="form-check-label" for="checkBoxEngineering">Engineering</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="International Hospitality Management" id="checkBoxHospitalityManagement" name="resource_unit[]">
                        <label class="form-check-label" for="checkBoxHospitalityManagement">International Hospitality Management</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Maritime" id="checkBoxMaritime" name="resource_unit[]">
                        <label class="form-check-label" for="checkBoxMaritime">Maritime</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Graduate School" id="checkBoxGraduateSchool" name="resource_unit[]">
                        <label class="form-check-label" for="checkBoxGraduateSchool">Graduate School</label>
                    </div>
                    <hr>
                    <p class="side-menu-text fw-bold">Researcher's Category</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Undergraduate" id="checkBoxUndergraduate" name="researcher_category[]">
                        <label class="form-check-label" for="checkBoxUndergraduate">Undergraduate</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Postgraduate" id="checkBoxPostgraduate" name="researcher_category[]">
                        <label class="form-check-label" for="checkBoxPostgraduate">Postgraduate</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Faculty" id="checkBoxFaculty" name="researcher_category[]">
                        <label class="form-check-label" for="checkBoxFaculty">Faculty</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Non-teaching Staff" id="checkBoxNonTeachingStaff" name="researcher_category[]">
                        <label class="form-check-label" for="checkBoxNonTeachingStaff">Non-teaching Staff</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Department Head" id="checkBoxDepartmentHead" name="researcher_category[]">
                        <label class="form-check-label" for="checkBoxDepartmentHead">Department Head</label>
                    </div>

                    <div class="row my-0">
                        <hr class="my-3">
                        <button type="button" class="btn w-100 rounded-0 button-clear-side-menu my-3" id="sidebar-clear-filter-button">Clear all</button>
                    </div>
                </form>

                <div class="col-sm-12 d-sm-block d-lg-none">
                    <div class="text-end my-3">
                        <p class="fw-bold filter-results" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">FILTER RESULTS <i class="fas fa-filter"></i></p>
                    </div>
                    <div class="offcanvas offcanvas-top h-auto" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
                        <div class="offcanvas-header">
                            <h6 class="text-white my-1"><i class="fas fa-filter"></i> SEARCH FILTERS</h6>
                            <button type="button" class="btn-close btn-close-white text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <form class="offcanvas-body" id="modal-search-filters" name="modal-filters">
                            <p class="side-menu-text fw-bold">Publication Year</p>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="2022" id="checkBox2022offcanvas" name="publication_year[]">
                                <label class="form-check-label" for="checkBox2022offcanvas">2022</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="2021" id="checkBox2021offcanvas" name="publication_year[]">
                                <label class="form-check-label" for="checkBox2021offcanvas">2021</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="2020" id="checkBox2020offcanvas" name="publication_year[]">
                                <label class="form-check-label" for="checkBox2020offcanvas">2020</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="2019" id="checkBox2019offcanvas" name="publication_year[]">
                                <label class="form-check-label" for="checkBox2019offcanvas">2019</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="2018" id="checkBox2018offcanvas" name="publication_year[]">
                                <label class="form-check-label" for="checkBox2018offcanvas">2018</label>
                            </div>
                            <a class="my-3 text-dark" data-bs-toggle="collapse" href="#customRangeCollapse" role="button" aria-expanded="false" aria-controls="collapseExample">Custom Range</a>
                            <div class="collapse" id="customRangeCollapse">
                                <div class="input-group my-3">
                                    <input type="text" class="form-control" name="from_year" id="modal-from-year">
                                    <input type="text" class="form-control" name="to_year" id="modal-to-year">
                                </div>
                                <button type="button" class="btn rounded-0" style="background-color: #012265; color:white; border-color:#012265">OK</button>
                            </div>
                            <hr>
                            <p class="side-menu-text fw-bold">Resource Type</p>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="dissertation" id="checkBoxDissertationoffcanvas" name="resource_type[]">
                                <label class="form-check-label" for="checkBoxDissertationoffcanvas">Dissertation</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="thesis" id="checkBoxThesisoffcanvas" name="resource_type[]">
                                <label class="form-check-label" for="checkBoxThesisoffcanvas">Thesis</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="capstone" id="checkBoxCapstoneoffcanvas" name="resource_type[]">
                                <label class="form-check-label" for="checkBoxCapstoneoffcanvas">Capstone</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="journal" id="checkBoxJournaloffcanvas" name="resource_type[]">
                                <label class="form-check-label" for="checkBoxJournaloffcanvas">Journal</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="infographics" id="checkBoxInfographicsoffcanvas" name="resource_type[]">
                                <label class="form-check-label" for="checkBoxInfographicsoffcanvas">Infographics</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="research_catalog" id="checkBoxResearchCatalogoffcanvas" name="resource_type[]">
                                <label class="form-check-label" for="checkBoxResearchCatalogoffcanvas">Research Catalog</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="annual_report" id="checkBoxAnnualReportoffcanvas" name="resource_type[]">
                                <label class="form-check-label" for="checkBoxAnnualReportoffcanvas">Annual Report</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="research_agenda" id="checkBoxResearchAgendaoffcanvas" name="resource_type[]">
                                <label class="form-check-label" for="checkBoxResearchAgendaoffcanvas">Research Agenda</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="research_competency_development_program" id="checkBoxRCDPoffcanvas" name="resource_type[]">
                                <label class="form-check-label" for="checkBoxRCDPoffcanvas">Research Competency Development Program</label>
                            </div>
                            <hr>
                            <p class="side-menu-text fw-bold">Research Field</p>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Accountancy and Marketing" id="checkBoxAccountancyMarketingoffcanvas" name="research_field[]">
                                <label class="form-check-label" for="checkBoxAccountancyMarketingoffcanvas">Accountancy and Marketing</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Arts and Humanities" id="checkBoxArtsHumanitiesoffcanvas" name="research_field[]">
                                <label class="form-check-label" for="checkBoxArtsHumanitiesoffcanvas">Arts and Humanities</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Aviation" id="checkBoxAviation" name="research_field[]">
                                <label class="form-check-label" for="checkBoxAviation">Aviation</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Educational Management" id="checkBoxEducationalManagementoffcanvas" name="research_field[]">
                                <label class="form-check-label" for="checkBoxEducationalManagementoffcanvas">Educational Management</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Education and Social Sciences" id="checkBoxEducationSocialSciencesoffcanvas" name="research_field[]">
                                <label class="form-check-label" for="checkBoxEducationSocialSciencesoffcanvas">Education and Social Sciences</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Engineering" id="checkBoxEngineering" name="resource_unit[]">
                                <label class="form-check-label" for="checkBoxEngineering">Engineering</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Business Management" id="checkBoxBusinessManagementoffcanvas" name="research_field[]">
                                <label class="form-check-label" for="checkBoxBusinessManagementoffcanvas">Business Management</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Health and Sciences" id="checkBoxHealthSciencesoffcanvas" name="research_field[]">
                                <label class="form-check-label" for="checkBoxHealthSciencesoffcanvas">Health and Sciences</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="IT" id="checkBoxIT" name="research_field[]">
                                <label class="form-check-label" for="checkBoxIT">Information Technology</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Law and Justice System" id="checkBoxLawJusticeSystemoffcanvas" name="research_field[]">
                                <label class="form-check-label" for="checkBoxLawJusticeSystemoffcanvas">Law and Justice System</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Maritime" id="checkBoxMaritime" name="research_field[]">
                                <label class="form-check-label" for="checkBoxMaritime">Maritime</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Tourism and Hospitality" id="checkBoxTourismHospitalityoffcanvas" name="research_field[]">
                                <label class="form-check-label" for="checkBoxTourismHospitalityoffcanvas">Tourism and Hospitality</label>
                            </div>
                            <hr>
                            <p class="side-menu-text fw-bold">Research Unit</p>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Basic Education" id="checkBoxBasicEducationoffcanvas" name="resource_unit[]">
                                <label class="form-check-label" for="checkBoxBasicEducationoffcanvas">Basic Education</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Senior High School" id="checkBoxSeniorHighSchooloffcanvas" name="resource_unit[]">
                                <label class="form-check-label" for="checkBoxSeniorHighSchooloffcanvas">Senior High School</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Arts and Sciences" id="checkBoxArtsSciencesoffcanvas" name="resource_unit[]">
                                <label class="form-check-label" for="checkBoxArtsSciencesoffcanvas">Arts and Sciences</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Business and Accountancy" id="checkBoxBusinessAccountancyoffcanvas" name="resource_unit[]">
                                <label class="form-check-label" for="checkBoxBusinessAccountancyoffcanvas">Business and Accountancy</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Criminology" id="checkBoxCriminologyoffcanvas" name="resource_unit[]">
                                <label class="form-check-label" for="checkBoxCriminologyoffcanvas">Criminology</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Computer Studies" id="checkBoxComputerStudiesoffcanvas" name="resource_unit[]">
                                <label class="form-check-label" for="checkBoxComputerStudiesoffcanvas">Computer Studies</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Education" id="checkBoxEducationoffcanvas" name="resource_unit[]">
                                <label class="form-check-label" for="checkBoxEducationoffcanvas">Education</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Engineering" id="checkBoxEngineeringoffcanvas" name="resource_unit[]">
                                <label class="form-check-label" for="checkBoxEngineeringoffcanvas">Engineering</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="International Hospitality Management" id="checkBoxHospitalityManagementoffcanvas" name="resource_unit[]">
                                <label class="form-check-label" for="checkBoxHospitalityManagementoffcanvas">International Hospitality Management</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Maritime" id="checkBoxMaritimeoffcanvas" name="resource_unit[]">
                                <label class="form-check-label" for="checkBoxMaritimeoffcanvas">Maritime</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Graduate School" id="checkBoxGraduateSchooloffcanvas" name="resource_unit[]">
                                <label class="form-check-label" for="checkBoxGraduateSchooloffcanvas">Graduate School</label>
                            </div>
                            <hr>
                            <p class="side-menu-text fw-bold">Researcher's Category</p>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Undergraduate" id="checkBoxUndergraduateoffcanvas" name="researcher_category[]">
                                <label class="form-check-label" for="checkBoxUndergraduateoffcanvas">Undergraduate</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Postgraduate" id="checkBoxPostgraduateoffcanvas" name="researcher_category[]">
                                <label class="form-check-label" for="checkBoxPostgraduateoffcanvas">Postgraduate</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Faculty" id="checkBoxFacultyoffcanvas" name="researcher_category[]">
                                <label class="form-check-label" for="checkBoxFacultyoffcanvas">Faculty</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Non-teaching Staff" id="checkBoxNonTeachingStaffoffcanvas" name="researcher_category[]">
                                <label class="form-check-label" for="checkBoxNonTeachingStaffoffcanvas">Non-teaching Staff</label>
                            </div>
                            <div class="row my-0">
                                <hr class="my-3">
                                <div class="text-center">
                                    <button type="button" class="btn rounded-0 button-clear-offcanvas my-3 w-50 mx-auto" id="modal-clear-filter-button">Clear all</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-9 mx-auto col-md-12 col-xs-12 main-column">
                    <h1>Recently Added</h1>
                    <hr class="my-4">
                    <div id="repository-results-container">
                    </div>
                </div>

            </div>

        </div>
    </section>


    <!--Footer section-->

    <?php include_once 'includes/footer.php' ?>
    <script src="scripts/bootstrap/bootstrap.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?php echo 'scripts/custom/repository.js?id=' . $repositoryjs ?>"></script>
</body>

</html>