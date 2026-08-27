<?php

session_start();

include './includes/connection.php';

require_once 'includes/feature-settings.php';
if (!feature_enabled('feature_statistics') && !user_is_staff()) {
    $disabledFeatureMessage = 'Repository statistics are temporarily unavailable. Please check back later.';
    require_once 'includes/feature-disabled.php';
    die();
}

$statement = $connection->prepare("SELECT fi.status AS status, COUNT(fi.file_id) AS count FROM file_information AS fi WHERE fi.status = 'published'");
$statement->execute();
$result = $statement->get_result();
$total_published = $result->fetch_assoc();
$statement->close();

$statement = $connection->prepare("SELECT ri.resource_type, COUNT(ri.file_ref_id) AS count FROM research_information AS ri GROUP BY ri.resource_type");
$statement->execute();
$result = $statement->get_result();
$thesis_count = $result->fetch_all(MYSQLI_ASSOC);
$statement->close();

$statement = $connection->prepare("SELECT fi.file_id, fi.file_type, av.hits,ri.research_title,ji.journal_title, ii.infographic_title, rp.report_title FROM article_visits AS av LEFT JOIN file_information AS fi ON fi.file_id = av.article_id LEFT JOIN research_information AS ri ON ri.file_ref_id = av.article_id LEFT JOIN journal_information AS ji ON ji.file_ref_id = av.article_id LEFT JOIN infographic_information AS ii ON ii.file_ref_id = av.article_id LEFT JOIN reports_information AS rp ON rp.file_ref_id = av.article_id ORDER BY hits DESC LIMIT 10 ");
$statement->execute();
$result = $statement->get_result();
$page_hits = $result->fetch_all(MYSQLI_ASSOC);
$statement->close();

$statement = $connection->prepare("SELECT SUM(hits) AS total_hits FROM article_visits LIMIT 10");
$statement->execute();
$result = $statement->get_result();
$article = $result->fetch_assoc();
$statement->close();


if (!isset($_SESSION['isLoggedIn'])) {
    header("location: ../index.php?location=" . urlencode($_SERVER['REQUEST_URI']));
    die();
}

$maincssVersion = filemtime('styles/custom/main-style.css');
$pagecssVersion = filemtime('styles/custom/pages/statistics-style.css');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics | SALIKSIK</title>
    <?php include_once 'assets/fonts/google-fonts.php' ?>

    <script src="./scripts/jquery/jquery-3.6.0.min.js"></script>
    <script src="./scripts/chartjs/chart.min.js"></script>
    <link rel="stylesheet" href="styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/pages/statistics-style.css?id=' . $pagecssVersion ?>" type="text/css">

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

    <?php include_once 'includes//header.php' ?>

    <section class="statistics-masthead">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h1>Research Statistics</h1>
                    <p class="masthead-subtitle">Explore publication trends and research activity across UPHSL</p>
                </div>
                <div class="text-end">
                    <div class="d-inline-block bg-white bg-opacity-10 rounded-3 px-3 py-2">
                        <div style="font-size: .7rem; text-transform: uppercase; letter-spacing: .06em; opacity: .8;">Total Publications</div>
                        <div style="font-size: 1.8rem; font-weight: 800; line-height: 1;"><?php echo number_format($total_published['count']) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="statistics">
        <div class="container py-4 px-3">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12" id="statisticsPanel">

                    <!-- Chart Section -->
                    <div class="stats-card-section mb-4">
                        <h5 class="stats-section-title">Research Outputs Over Time</h5>
                        <div class="chart-container">
                            <canvas id="myChart" height="90"></canvas>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <!-- Activity Overview -->
                        <div class="col-lg-4">
                            <div class="stats-card-section h-100">
                                <h5 class="stats-section-title">Activity Overview</h5>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="stat-card">
                                            <div class="stat-icon-ring"><i class="fas fa-file-alt"></i></div>
                                            <div class="stat-number"><?php echo number_format($total_published['count']) ?></div>
                                            <div class="stat-label">Research Outputs</div>
                                        </div>
                                    </div>
                                    <?php
                                    $statIcons = array(
                                        'thesis' => 'fa-book',
                                        'journal' => 'fa-newspaper',
                                        'infographic' => 'fa-image',
                                        'capstone' => 'fa-project-diagram',
                                        'dissertation' => 'fa-graduation-cap',
                                        'report' => 'fa-clipboard-list',
                                        'research_agenda' => 'fa-list-alt',
                                        'research_catalog' => 'fa-folder-open',
                                        'annual_report' => 'fa-calendar-alt',
                                        'research_competency_development_program' => 'fa-cogs',
                                    );
                                    foreach ($thesis_count as $key => $row) :
                                        $icon = isset($statIcons[$row['resource_type']]) ? $statIcons[$row['resource_type']] : 'fa-file';
                                    ?>
                                        <div class="col-12">
                                            <div class="stat-card">
                                                <div class="stat-icon-ring"><i class="fas <?php echo $icon ?>"></i></div>
                                                <div class="stat-number"><?php echo number_format($row['count']) ?></div>
                                                <div class="stat-label"><?php echo $row['resource_type']; ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Most Viewed Items -->
                        <div class="col-lg-8">
                            <div class="stats-card-section h-100">
                                <h5 class="stats-section-title">Most Viewed Items</h5>
                                <?php foreach ($page_hits as $key => $page) :
                                    $page_percent = $page['hits'] / $article['total_hits'] * 100;
                                    $rank = $key + 1;
                                    $rankClass = ($rank <= 3) ? 'rank-' . $rank : '';
                                ?>
                                    <?php if ($page['file_type'] == 'thesis') : $title = $page['research_title']; ?>
                                    <?php elseif ($page['file_type'] == 'journal') : $title = $page['journal_title']; ?>
                                    <?php elseif ($page['file_type'] == 'infographic') : $title = $page['infographic_title']; ?>
                                    <?php elseif ($page['file_type'] == 'report') : $title = $page['report_title']; ?>
                                    <?php else : $title = 'Untitled'; ?>
                                    <?php endif; ?>
                                    <div class="viewed-item-row <?php echo $rankClass ?>">
                                        <div class="viewed-item-rank"><?php echo $rank ?></div>
                                        <div class="viewed-item-body">
                                            <a href="repository/view-article.php?id=<?php echo $page['file_id']; ?>" class="viewed-item-title">
                                                <?php echo htmlspecialchars($title) ?>
                                            </a>
                                            <div class="viewed-item-meta"><?php echo ucfirst($page['file_type']) ?></div>
                                        </div>
                                        <div class="viewed-item-bar">
                                            <span class="viewed-item-hits"><?php echo number_format($page['hits']) ?></span>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: <?php echo $page_percent ?>%;" aria-valuenow="<?php echo $page_percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!--Footer section-->

    <?php include_once 'includes//footer.php' ?>
    <script src="scripts/bootstrap/bootstrap.js"></script>
    <script>
        var labels = [];
        var data = [];
        $("document").ready(function() {
            $.ajax({
                method: "GET",
                url: "src/process/get-statistics.php",
                async: false
            }).done(function(result) {
                result.forEach(function(val, key) {
                    labels.push(val.year)
                    data.push(val.count)
                })
                const ctx = document.getElementById('myChart').getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 350);
                gradient.addColorStop(0, 'rgba(1, 34, 101, 1)');
                gradient.addColorStop(1, 'rgba(14, 64, 141, .6)');
                const myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Published Content',
                            data: data,
                            backgroundColor: gradient,
                            borderColor: 'rgba(1, 34, 101, 1)',
                            borderWidth: 0,
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(1, 34, 101, .92)',
                                titleFont: { weight: '700', size: 13 },
                                bodyFont: { size: 12 },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    label: function(ctx) { return ctx.parsed.y + ' publications'; }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { weight: '600', size: 12 }, color: '#4a5568' }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(226, 232, 240, .6)', drawBorder: false },
                                ticks: { font: { weight: '600', size: 11 }, color: '#718096', stepSize: 1 }
                            }
                        }
                    }
                });

            })
        })
    </script>
</body>

</html>