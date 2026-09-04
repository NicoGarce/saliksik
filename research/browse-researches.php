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

function browse_is_image($p) { return !empty($p) && preg_match('/\.(jpe?g|png|webp|gif)$/i', $p); }
function browse_year($d) { $x = date_create($d); return $x ? date_format($x, "Y") : ''; }

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
    <title>Browse Researches | SALIKSIK</title>
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
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item prev-dir-breadcrumb"><a href="../repository.php" style="color: var(--navy-700); text-decoration: none;">Repository</a></li>
                    <li class="breadcrumb-item active active-dir-breadcrumb" aria-current="page">Browse Researches</li>
                </ol>
            </nav>
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
                            if ($result['file_type'] == 'thesis' && (!empty($result['research_course']) || $result['researchers_category']=='Faculty' || $result['researchers_category']=='Department Head')) {
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
                            echo "<div class='browse-book-grid'>";
                            foreach ($published as $key => $item) {
                                if ($item['file_type'] == 'journal' && $item['department'] == $result) {
                                    $jTitle = $item['journal_title'];
                                    $jYear = browse_year($item['published_on'] ?? '') ?: $item['serial_issue_number'];
                                    $jUrl = "../repository/view-article.php?id={$item['file_id']}";
                                    $jCover = $item['file_dir2'] ?? '';
                                    $jTitleEsc = htmlspecialchars($jTitle, ENT_QUOTES, 'UTF-8');
                                    $jYearEsc = htmlspecialchars($jYear, ENT_QUOTES, 'UTF-8');
                                    if (browse_is_image($jCover)) {
                                        echo "<a href='{$jUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--image'><img src='../src/{$jCover}' alt='Cover'><span class='browse-book-type'>Journal</span></div><div class='browse-book-title'>{$jTitleEsc}</div><div class='browse-book-year'>{$jYearEsc}</div></a>";
                                    } else {
                                        echo "<a href='{$jUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--journal'><span class='browse-book-type'>Journal</span><h6 class='browse-book-cover-title'>{$jTitleEsc}</h6><span class='browse-book-cover-year'>{$jYearEsc}</span></div><div class='browse-book-title'>{$jTitleEsc}</div><div class='browse-book-year'>{$jYearEsc}</div></a>";
                                    }
                                }
                            }
                            echo "</div>";
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
                        echo "<div class='browse-book-grid'>";
                        foreach ($published as $key => $item) {
                            if ($item['file_type'] == 'infographic') {
                                $igTitle = $item['infographic_title'];
                                $igYear = browse_year($item['infographic_publication_date'] ?? '');
                                $igUrl = "../repository/view-article.php?id={$item['file_id']}";
                                $igTitleEsc = htmlspecialchars($igTitle, ENT_QUOTES, 'UTF-8');
                                $igYearEsc = htmlspecialchars($igYear, ENT_QUOTES, 'UTF-8');
                                echo "<a href='{$igUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--infographic'><span class='browse-book-type'>Infographic</span><h6 class='browse-book-cover-title'>{$igTitleEsc}</h6><span class='browse-book-cover-year'>{$igYearEsc}</span></div><div class='browse-book-title'>{$igTitleEsc}</div><div class='browse-book-year'>{$igYearEsc}</div></a>";
                            }
                        }
                        echo "</div>";
                        ?>
                    </div>
                </div>

                <!-- ========== RESEARCH CATALOGS ========== -->
                <div class="col-lg-9 mx-auto col-12 main-column" id="panel-researchCatalogs" hidden>
                    <h2 class="browse-panel-title">Browse Research Catalogs</h2>
                    <hr class="my-3">
                    <div>
                        <?php
                        echo "<div class='browse-book-grid'>";
                        foreach ($published as $key => $item) {
                            if ($item['report_type'] == 'Research Catalog') {
                                $rcTitle = $item['report_title'];
                                $rcYear = $item['report_year'];
                                $rcUrl = "../repository/view-article.php?id={$item['file_id']}";
                                $rcCover = $item['file_dir2'] ?? '';
                                $rcTitleEsc = htmlspecialchars($rcTitle, ENT_QUOTES, 'UTF-8');
                                $rcYearEsc = htmlspecialchars($rcYear, ENT_QUOTES, 'UTF-8');
                                if (browse_is_image($rcCover)) {
                                    echo "<a href='{$rcUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--image'><img src='../src/{$rcCover}' alt='Cover'><span class='browse-book-type'>Catalog</span></div><div class='browse-book-title'>{$rcTitleEsc}</div><div class='browse-book-year'>{$rcYearEsc}</div></a>";
                                } else {
                                    echo "<a href='{$rcUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--report'><span class='browse-book-type'>Catalog</span><h6 class='browse-book-cover-title'>{$rcTitleEsc}</h6><span class='browse-book-cover-year'>{$rcYearEsc}</span></div><div class='browse-book-title'>{$rcTitleEsc}</div><div class='browse-book-year'>{$rcYearEsc}</div></a>";
                                }
                            }
                        }
                        echo "</div>";
                        ?>
                    </div>
                </div>

                <!-- ========== ANNUAL REPORTS ========== -->
                <div class="col-lg-9 mx-auto col-12 main-column" id="panel-annualReports" hidden>
                    <h2 class="browse-panel-title">Browse Annual Reports</h2>
                    <hr class="my-3">
                    <div>
                        <?php
                        echo "<div class='browse-book-grid'>";
                        foreach ($published as $key => $item) {
                            if ($item['report_type'] == 'Annual Report') {
                                $arTitle = $item['report_title'];
                                $arYear = $item['report_year'];
                                $arUrl = "../repository/view-article.php?id={$item['file_id']}";
                                $arCover = $item['file_dir2'] ?? '';
                                $arTitleEsc = htmlspecialchars($arTitle, ENT_QUOTES, 'UTF-8');
                                $arYearEsc = htmlspecialchars($arYear, ENT_QUOTES, 'UTF-8');
                                if (browse_is_image($arCover)) {
                                    echo "<a href='{$arUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--image'><img src='../src/{$arCover}' alt='Cover'><span class='browse-book-type'>Annual Report</span></div><div class='browse-book-title'>{$arTitleEsc}</div><div class='browse-book-year'>{$arYearEsc}</div></a>";
                                } else {
                                    echo "<a href='{$arUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--report'><span class='browse-book-type'>Annual Report</span><h6 class='browse-book-cover-title'>{$arTitleEsc}</h6><span class='browse-book-cover-year'>{$arYearEsc}</span></div><div class='browse-book-title'>{$arTitleEsc}</div><div class='browse-book-year'>{$arYearEsc}</div></a>";
                                }
                            }
                        }
                        echo "</div>";
                        ?>
                    </div>
                </div>

                <!-- ========== RESEARCH AGENDA ========== -->
                <div class="col-lg-9 mx-auto col-12 main-column" id="panel-researchAgenda" hidden>
                    <h2 class="browse-panel-title">Browse Research Agenda</h2>
                    <hr class="my-3">
                    <div>
                        <?php
                        echo "<div class='browse-book-grid'>";
                        foreach ($published as $key => $item) {
                            if ($item['report_type'] == 'Research Agenda') {
                                $raTitle = $item['report_title'];
                                $raYear = $item['report_year'];
                                $raUrl = "../repository/view-article.php?id={$item['file_id']}";
                                $raCover = $item['file_dir2'] ?? '';
                                $raTitleEsc = htmlspecialchars($raTitle, ENT_QUOTES, 'UTF-8');
                                $raYearEsc = htmlspecialchars($raYear, ENT_QUOTES, 'UTF-8');
                                if (browse_is_image($raCover)) {
                                    echo "<a href='{$raUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--image'><img src='../src/{$raCover}' alt='Cover'><span class='browse-book-type'>Agenda</span></div><div class='browse-book-title'>{$raTitleEsc}</div><div class='browse-book-year'>{$raYearEsc}</div></a>";
                                } else {
                                    echo "<a href='{$raUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--report'><span class='browse-book-type'>Research Agenda</span><h6 class='browse-book-cover-title'>{$raTitleEsc}</h6><span class='browse-book-cover-year'>{$raYearEsc}</span></div><div class='browse-book-title'>{$raTitleEsc}</div><div class='browse-book-year'>{$raYearEsc}</div></a>";
                                }
                            }
                        }
                        echo "</div>";
                        ?>
                    </div>
                </div>

                <!-- ========== RCDP ========== -->
                <div class="col-lg-9 mx-auto col-12 main-column" id="panel-rcdp" hidden>
                    <h2 class="browse-panel-title">Browse Research Competency Development Program</h2>
                    <hr class="my-3">
                    <div>
                        <?php
                        echo "<div class='browse-book-grid'>";
                        foreach ($published as $key => $item) {
                            if ($item['report_type'] == 'Research Competency Development Program') {
                                $rpTitle = $item['report_title'];
                                $rpYear = $item['report_year'];
                                $rpUrl = "../repository/view-article.php?id={$item['file_id']}";
                                $rpCover = $item['file_dir2'] ?? '';
                                $rpTitleEsc = htmlspecialchars($rpTitle, ENT_QUOTES, 'UTF-8');
                                $rpYearEsc = htmlspecialchars($rpYear, ENT_QUOTES, 'UTF-8');
                                if (browse_is_image($rpCover)) {
                                    echo "<a href='{$rpUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--image'><img src='../src/{$rpCover}' alt='Cover'><span class='browse-book-type'>RCDP</span></div><div class='browse-book-title'>{$rpTitleEsc}</div><div class='browse-book-year'>{$rpYearEsc}</div></a>";
                                } else {
                                    echo "<a href='{$rpUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--report'><span class='browse-book-type'>RCDP</span><h6 class='browse-book-cover-title'>{$rpTitleEsc}</h6><span class='browse-book-cover-year'>{$rpYearEsc}</span></div><div class='browse-book-title'>{$rpTitleEsc}</div><div class='browse-book-year'>{$rpYearEsc}</div></a>";
                                }
                            }
                        }
                        echo "</div>";
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
                if ($panel.find(".accordion-item, .browse-article-item, .browse-book-card").length === 0) {
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
