<?php

session_start();

include '../includes/connection.php';

if (!isset($_SESSION['isLoggedIn'])) {
    header("location: ../error.php");
    die();
}

if(!isset($_GET['q'])){
    die();
}
$statement = $connection->prepare("SELECT name FROM research_fields");
$statement->execute();
$result = $statement->get_result();
$research_field_list = $result->fetch_all(MYSQLI_ASSOC);
$statement->close();

$research_field_list_values = array();
foreach ($research_field_list as $key => $value) {  
    array_push($research_field_list_values,$value['name']);
}

if(!in_array($_GET['q'],$research_field_list_values)){
    die();
};
$field = "%".$_GET['q']."%";
$query = "SELECT fi.*,`research_id`,ri.resource_type AS research_type,`researchers_category`,`research_unit`,`research_title`,`research_abstract`,`research_fields`,`keywords`,`publication_date`,ri.coauthors_count AS `research_coauthors_count`,ri.author_first_name AS researcher_first_name, ri.author_middle_initial AS researcher_middle_initial, ri.author_surname AS researcher_surname, ri.author_name_ext AS researcher_name_ext, ri.author_email AS researcher_email, ci.* FROM file_information AS fi LEFT JOIN research_information as ri ON ri.file_ref_id=fi.file_id LEFT JOIN coauthors_information AS ci on ci.group_id = fi.coauthor_group_id WHERE fi.file_type = 'thesis' && fi.status = 'published' && research_fields LIKE ?";
$statement = $connection->prepare($query);
$statement->bind_param("s",$field);
$statement->execute();
$result = $statement->get_result();
$published = $result->fetch_all(MYSQLI_ASSOC);

$statement->close();

$maincssVersion = filemtime('../styles/custom/main-style.css');
$pagecssVersion = filemtime('../styles/custom/pages/home-style.css');
function filter(&$value)
{
    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
array_walk_recursive($published, "filter");
function browse_is_image($p) { return !empty($p) && preg_match('/\.(jpe?g|png|webp|gif)$/i', $p); }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Research Fields | SALIKSIK</title>
    <?php include_once '../assets/fonts/google-fonts.php' ?>

    <link rel="stylesheet" href="../styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo '../styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo '../styles/custom/pages/home-style.css?id=' . $pagecssVersion ?>" type="text/css">

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

    <section class="masthead p-5">
        <div class="container">
            <div class="col d-flex align-items-center">
                <!-- <img src="../assets/images/research-fields/accountancy-marketing.png" class="research-fields-logos"> -->
                <h1 id="masthead-title-text"><?php echo htmlspecialchars($_GET['q']);?></h1>
            </div>
        </div>
    </section>

    <section class="research-fields">
        <div class="container p-5">
            <div class="accordion accordion-flush">
            <?php
            if(count($published)){
                $unit_array = array();
                        foreach ($published as $key => $result) {
                            $date_time = date_create($result['publication_date']);
                            $yearOnly  = date_format($date_time,"Y");
                            if ($result['file_type'] == 'thesis') {
                                array_push($unit_array, $yearOnly);
                            }
                        }
                        $unit_array_count = array_count_values($unit_array);
                        $unit_array = array_unique($unit_array);
                        foreach ($unit_array as $key => $result) {
                            echo "<div class='accordion-item my-2'>
                    <h2 class='accordion-header'>
                        <button class='accordion-button collapsed fw-bold' type='button' data-bs-toggle='collapse' data-bs-target='#field-{$key}-researches' aria-expanded='false'>
                            {$result} ({$unit_array_count[$result]})
                        </button>
                    </h2>
                    <div id='field-{$key}-researches' class='accordion-collapse collapse'>
                        <div class='accordion-body'><div class='browse-book-grid'>";
                            foreach ($published as $key => $item) {
                                $date_time = date_create($item['publication_date']);
                                $thisYear  = date_format($date_time,"Y");
                                if ($item['file_type'] == 'thesis' && $thisYear == $result) {
                                    $bTitle = $item['research_title'];
                                    $bYear = $thisYear;
                                    $bUrl = "../repository/view-article.php?id={$item['file_id']}";
                                    $bCover = $item['file_dir2'] ?? '';
                                    $bTitleEsc = htmlspecialchars($bTitle, ENT_QUOTES, 'UTF-8');
                                    $bYearEsc = htmlspecialchars($bYear, ENT_QUOTES, 'UTF-8');
                                    if (browse_is_image($bCover)) {
                                        echo "<a href='{$bUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--image'><img src='../src/{$bCover}' alt='Cover'><span class='browse-book-type'>Thesis</span></div><div class='browse-book-title'>{$bTitleEsc}</div><div class='browse-book-year'>{$bYearEsc}</div></a>";
                                    } else {
                                        echo "<a href='{$bUrl}' class='browse-book-card'><div class='browse-book-cover browse-book-cover--thesis'><span class='browse-book-type'>Thesis</span><h6 class='browse-book-cover-title'>{$bTitleEsc}</h6><span class='browse-book-cover-year'>{$bYearEsc}</span></div><div class='browse-book-title'>{$bTitleEsc}</div><div class='browse-book-year'>{$bYearEsc}</div></a>";
                                    }
                                }
                            }
                            echo "</div></div>
                    </div>
                </div>";
                        }
            }
            else{
                echo '<h5 style="color: grey; text-align: center;"><br>No results found.</h5>';//TODO
            }
            ?>
            </div>

        </div>
    </section>

    <?php include_once '../includes/footer.php' ?>
    <script src="../scripts/bootstrap/bootstrap.js"></script>

</body>

</html>