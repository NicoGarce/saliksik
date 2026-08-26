<?php

session_start();

include '../includes/connection.php';

if (!isset($_SESSION['isLoggedIn'])) {
    header("location: ../error.php");
    die();
}

if (mysqli_connect_errno()) {
    exit("Failed to connect to the database: " . mysqli_connect_error());
};

$query = "SELECT fi.*,`research_id`,ri.resource_type AS research_type,`researchers_category`,`research_unit`,`research_title`,`research_abstract`,`research_fields`,`research_course`,`keywords`,`publication_date`,ri.coauthors_count AS `research_coauthors_count`,ri.author_first_name AS researcher_first_name, ri.author_middle_initial AS researcher_middle_initial, ri.author_surname AS researcher_surname, ri.author_name_ext AS researcher_name_ext, ri.author_email AS researcher_email, ii.*, ji.*,rp.*, ci.* FROM file_information AS fi LEFT JOIN research_information as ri ON ri.file_ref_id=fi.file_id LEFT JOIN journal_information AS ji ON ji.file_ref_id=fi.file_id LEFT JOIN infographic_information AS ii ON ii.file_ref_id=fi.file_id LEFT JOIN reports_information AS rp ON rp.file_ref_id=fi.file_id LEFT JOIN coauthors_information AS ci on ci.group_id = fi.coauthor_group_id WHERE fi.status = 'published'";
$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->get_result();
$published = $result->fetch_all(MYSQLI_ASSOC);
$statement->close();

$maincssVersion = filemtime('../styles/custom/main-style.css');
$pagecssVersion = filemtime('../styles/custom/pages/browse-researches-style.css');
function filter(&$value)
{
    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
array_walk_recursive($published, "filter");

$panels = [
    'researches'         => 'Browse Researches',
    'journals'           => 'Browse Journals',
    'infographics'       => 'Browse Infographics',
    'researchCatalogs'   => 'Browse Research Catalogs',
    'annualReports'      => 'Browse Annual Reports',
    'researchAgenda'     => 'Browse Research Agenda',
    'rcdp'               => 'Browse RCDP',
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Researches</title>
    <?php include_once '../assets/fonts/google-fonts.php' ?>

    <script src="../scripts/jquery/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo '../styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo '../styles/custom/pages/browse-researches-style.css?id=' . $pagecssVersion ?>" type="text/css">

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

    <!--Masthead-->
    <section class="masthead p-5">
        <div class="container">
            <h1 id="masthead-title-text">Browse</h1>
            <p id="masthead-content-text">Explore researches, journals, infographics, and reports by category.</p>
        </div>
    </section>

    <!--Content-->
    <section class="search-section">
        <div class="container p-3">

            <!-- Mobile dropdown -->
            <div class="browse-mobile-nav d-lg-none">
                <select class="form-select" id="dropdownOnThisPage" aria-label="Navigate sections">
                    <?php foreach ($panels as $key => $label): ?>
                        <option value="<?php echo $key ?>" <?php echo $key === 'researches' ? 'selected' : '' ?>><?php echo $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">

                <!-- Desktop sidebar -->
                <div class="col-lg-2 d-none d-lg-block browse-sidebar-desktop">
                    <div class="browse-sidebar">
                        <p class="browse-sidebar-title">On this page</p>
                        <?php foreach ($panels as $key => $label): ?>
                            <a class="browse-nav-item <?php echo $key === 'researches' ? 'active' : '' ?>" data-panel="<?php echo $key ?>"><?php echo $label ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ========== RESEARCHES ========== -->
                <div class="col-lg-9 mx-auto col-12 main-column" id="panel-researches">
                    <h2 class="browse-panel-title">Browse Researches</h2>
                    <hr class="my-3">
                    <div class="browse-accordion">
                        <?php
                        $unit_array = array();
                        foreach ($published as $key => $result) {
                            if ($result['file_type'] == 'thesis' && (!empty($result['research_course'] || $result['researchers_category']=='Faculty' || $result['researchers_category']=='Department Head'))) {
                                array_push($unit_array, $result['research_unit']);
                            }
                        }
                        $unit_array = array_unique($unit_array);
                        foreach ($unit_array as $key => $result) {
                            echo "<div class='accordion-item'>
                                    <h2 class='accordion-header'>
                                    <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#field-{$key}-researches' aria-expanded='false'>
                                        {$result}
                                    </button>
                                    </h2>
                                        <div id='field-{$key}-researches' class='accordion-collapse collapse'>
                                            <div class='accordion-body'>";
                        $course_array = array();
                            foreach ($published as $key => $item) {
                                if ($item['file_type'] == 'thesis' && $item['research_unit'] == $result) {
                                    if(!empty($item['research_course'])){
                                        array_push($course_array, $item['research_course']);
                                    }
                                    else if($item['researchers_category']=='Faculty'){
                                        array_push($course_array, "Faculty");
                                    }
                                    else if($item['researchers_category']=='Department Head'){
                                        array_push($course_array, "Department Head");
                                    }
                                }
                            }
                            $course_array = array_unique($course_array);
                            foreach($course_array as $key => $course){
                                echo "<a href='../research/browse-course-researches.php?q={$course}' class='department-title-content'>{$course}</a>";
                            }
                                        echo "</div>
                                        </div>
                                    </div>";
                        }
                        ?>
                    </div>
                </div>

                <!-- ========== JOURNALS ========== -->
                <div class="col-lg-9 mx-auto col-12 main-column" id="panel-journals" hidden>
                    <h2 class="browse-panel-title">Browse Journals</h2>
                    <hr class="my-3">
                    <div>
                        <?php
                        $unit_array = array();
                        foreach ($published as $key => $result) {
                            if ($result['file_type'] == 'journal') {
                                array_push($unit_array, $result['department']);
                            }
                        }
                        $unit_array_count = array_count_values($unit_array);
                        $unit_array = array_unique($unit_array);
                        foreach ($unit_array as $key => $result) {
                            echo "<div class='accordion-item'>
                                    <h2 class='accordion-header'>
                                        <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#journal-field-{$key}' aria-expanded='false'>
                                            {$result} ({$unit_array_count[$result]})
                                        </button>
                                    </h2>
                                    <div id='journal-field-{$key}' class='accordion-collapse collapse'>
                                        <div class='accordion-body'>";
                            foreach ($published as $key => $item) {
                                if ($item['file_type'] == 'journal' && $item['department'] == $result) {
                                    if(strlen($item['journal_description'])>500){
                                        $stringCut = substr($item['journal_description'], 0, 500);
                                        $endPoint = strrpos($item['journal_description'], ' ');
                                        $item['journal_description']= $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                        $item['journal_description'] .= "... <a href='../repository/view-article.php?id={$item['file_id']}' class='read-more'>Read More</a>";
                                    }
                                    echo "<div class='browse-article-item'>
                                        <a href='../repository/view-article.php?id={$item['file_id']}' class='article-title'>
                                            <h5>{$item['journal_title']}</h5>
                                        </a>
                                        <p class='browse-article-meta'>{$item['serial_issue_number']}</p>
                                        <p class='browse-article-desc'>{$item['journal_description']}</p>
                                    </div>";
                                }
                            }
                            echo "</div></div></div>";
                        }
                        ?>
                    </div>
                </div>

                <!-- ========== INFOGRAPHICS ========== -->
                <div class="col-lg-9 mx-auto col-12 main-column" id="panel-infographics" hidden>
                    <h2 class="browse-panel-title">Browse Infographics</h2>
                    <hr class="my-3">
                    <div>
                        <?php
                        foreach ($published as $key => $item) {
                            if ($item['file_type'] == 'infographic') {
                                $date_time = date_create($item['infographic_publication_date']);
                                $date_time = date_format($date_time,"F Y");
                                if(strlen($item['infographic_description'])>500){
                                    $stringCut = substr($item['infographic_description'], 0, 500);
                                    $endPoint = strrpos($item['infographic_description'], ' ');
                                    $item['infographic_description']= $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                    $item['infographic_description'] .= "... <a href='../repository/view-article.php?id={$item['file_id']}' class='read-more'>Read More</a>";
                                }
                                echo "<div class='browse-article-item'>
                                    <a href='../repository/view-article.php?id={$item['file_id']}' class='article-title'>
                                        <h5>{$item['infographic_title']}</h5>
                                    </a>
                                    <p class='browse-article-meta'>{$date_time}</p>
                                    <p class='browse-article-desc'>{$item['infographic_description']}</p>
                                </div>";
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- ========== RESEARCH CATALOGS ========== -->
                <div class="col-lg-9 mx-auto col-12 main-column" id="panel-researchCatalogs" hidden>
                    <h2 class="browse-panel-title">Browse Research Catalogs</h2>
                    <hr class="my-3">
                    <div>
                        <?php
                        foreach ($published as $key => $item) {
                            if ($item['report_type'] == 'Research Catalog') {
                                if(strlen($item['report_description'])>500){
                                    $stringCut = substr($item['report_description'], 0, 500);
                                    $endPoint = strrpos($item['report_description'], ' ');
                                    $item['report_description']= $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                    $item['report_description'] .= "... <a href='../repository/view-article.php?id={$item['file_id']}' class='read-more'>Read More</a>";
                                }
                                echo "<div class='browse-article-item'>
                                    <a href='../repository/view-article.php?id={$item['file_id']}' class='article-title'>
                                        <h5>{$item['report_title']}</h5>
                                    </a>
                                    <p class='browse-article-meta'>{$item['report_year']}</p>
                                    <p class='browse-article-desc'>{$item['report_description']}</p>
                                </div>";
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- ========== ANNUAL REPORTS ========== -->
                <div class="col-lg-9 mx-auto col-12 main-column" id="panel-annualReports" hidden>
                    <h2 class="browse-panel-title">Browse Annual Reports</h2>
                    <hr class="my-3">
                    <div>
                        <?php
                        foreach ($published as $key => $item) {
                            if ($item['report_type'] == 'Annual Report') {
                                if(strlen($item['report_description'])>500){
                                    $stringCut = substr($item['report_description'], 0, 500);
                                    $endPoint = strrpos($item['report_description'], ' ');
                                    $item['report_description']= $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                    $item['report_description'] .= "... <a href='../repository/view-article.php?id={$item['file_id']}' class='read-more'>Read More</a>";
                                }
                                echo "<div class='browse-article-item'>
                                    <a href='../repository/view-article.php?id={$item['file_id']}' class='article-title'>
                                        <h5>{$item['report_title']}</h5>
                                    </a>
                                    <p class='browse-article-meta'>{$item['report_year']}</p>
                                    <p class='browse-article-desc'>{$item['report_description']}</p>
                                </div>";
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- ========== RESEARCH AGENDA ========== -->
                <div class="col-lg-9 mx-auto col-12 main-column" id="panel-researchAgenda" hidden>
                    <h2 class="browse-panel-title">Browse Research Agenda</h2>
                    <hr class="my-3">
                    <div>
                        <?php
                        foreach ($published as $key => $item) {
                            if ($item['report_type'] == 'Research Agenda') {
                                if(strlen($item['report_description'])>500){
                                    $stringCut = substr($item['report_description'], 0, 500);
                                    $endPoint = strrpos($item['report_description'], ' ');
                                    $item['report_description']= $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                    $item['report_description'] .= "... <a href='../repository/view-article.php?id={$item['file_id']}' class='read-more'>Read More</a>";
                                }
                                echo "<div class='browse-article-item'>
                                    <a href='../repository/view-article.php?id={$item['file_id']}' class='article-title'>
                                        <h5>{$item['report_title']}</h5>
                                    </a>
                                    <p class='browse-article-meta'>{$item['report_year']}</p>
                                    <p class='browse-article-desc'>{$item['report_description']}</p>
                                </div>";
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- ========== RCDP ========== -->
                <div class="col-lg-9 mx-auto col-12 main-column" id="panel-rcdp" hidden>
                    <h2 class="browse-panel-title">Browse Research Competency Development Program</h2>
                    <hr class="my-3">
                    <div>
                        <?php
                        foreach ($published as $key => $item) {
                            if ($item['report_type'] == 'Research Competency Development Program') {
                                if(strlen($item['report_description'])>500){
                                    $stringCut = substr($item['report_description'], 0, 500);
                                    $endPoint = strrpos($item['report_description'], ' ');
                                    $item['report_description']= $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                    $item['report_description'] .= "... <a href='../repository/view-article.php?id={$item['file_id']}' class='read-more'>Read More</a>";
                                }
                                echo "<div class='browse-article-item'>
                                    <a href='../repository/view-article.php?id={$item['file_id']}' class='article-title'>
                                        <h5>{$item['report_title']}</h5>
                                    </a>
                                    <p class='browse-article-meta'>{$item['report_year']}</p>
                                    <p class='browse-article-desc'>{$item['report_description']}</p>
                                </div>";
                            }
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php include_once '../includes/footer.php' ?>

    <script src="../scripts/bootstrap/bootstrap.js"></script>
    <script>
        $(document).ready(function() {

            var allPanels = [
                'researches', 'journals', 'infographics',
                'researchCatalogs', 'annualReports', 'researchAgenda', 'rcdp'
            ];

            // Empty state
            allPanels.forEach(function(id) {
                var $panel = $("#panel-" + id);
                if ($panel.find(".accordion-item, .browse-article-item").length === 0) {
                    $panel.find("> div, > .browse-accordion").first().append("<p class='browse-empty-state'>No results found.</p>");
                }
            });

            // Switch panel
            function switchPanel(panelId) {
                allPanels.forEach(function(id) {
                    $("#panel-" + id).prop("hidden", id !== panelId);
                });
                $(".browse-nav-item").removeClass("active");
                $(".browse-nav-item[data-panel='" + panelId + "']").addClass("active");
            }

            // Desktop sidebar clicks
            $(".browse-nav-item").on("click", function() {
                switchPanel($(this).data("panel"));
            });

            // Mobile dropdown
            $("#dropdownOnThisPage").on("change", function() {
                switchPanel(this.value);
            });
        });
    </script>

</body>

</html>
