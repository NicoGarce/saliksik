<?php

session_start();

include 'includes/connection.php';

if (!isset($_SESSION['isLoggedIn'])) {
    header("location: ../index.php?location=" . urlencode($_SERVER['REQUEST_URI']));
    die();
}

require_once 'includes/feature-settings.php';
if (!feature_enabled('feature_researchers') && !user_is_staff()) {
    $disabledFeatureMessage = 'The researchers directory is temporarily unavailable. Please check back later.';
    require_once 'includes/feature-disabled.php';
    die();
}

if (mysqli_connect_errno()) {
    exit("Failed to connect to the database: " . mysqli_connect_error());
};

$researcher_query = "SELECT * FROM researcher_profile WHERE archived = 0";

if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
    $researcher_query = "SELECT * FROM researcher_profile";
}

$statement = $connection->prepare($researcher_query);
$statement->execute();
$result = $statement->get_result();
$researchers = $result->fetch_all(MYSQLI_ASSOC);
$statement->close();

$maincssVersion = filemtime('styles/custom/main-style.css');
$pagecssVersion = filemtime('styles/custom/pages/researchers-style.css');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Researchers</title>
    <?php include_once 'assets/fonts/google-fonts.php' ?>

    <script src="./scripts/jquery/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/pages/researchers-style.css?id=' . $pagecssVersion ?>" type="text/css">

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


    <section class="researchers-masthead">
        <div class="container">
            <h1>Our Researchers</h1>
            <p class="masthead-subtitle">Meet the research community of UPHSL</p>
        </div>
    </section>

    <section class="researchers">
        <div class="container py-4 px-3">

            <div class="row g-4">

                <div class="col-lg-3 d-none d-lg-block">
                    <div class="researchers-sidebar">
                        <div class="researchers-sidebar-title">On This Page</div>
                        <a class="researcher-nav-item active" id="seniorResearchersText">
                            Senior Researchers
                        </a>
                        <a class="researcher-nav-item" id="juniorResearchersText">
                            Junior Researchers
                        </a>
                        <a class="researcher-nav-item" id="juniorAssociateText">
                            Junior Associate Researchers
                        </a>
                        <a class="researcher-nav-item" id="noviceText">
                            Novice Researchers
                        </a>
                        <?php if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) : ?>
                        <a class="researcher-nav-item" id="archivedText">
                            Archived Researchers
                        </a>
                        <button class="researchers-add-btn" id="buttonAddProfile">
                            <i class="fas fa-plus"></i>Add Profile
                        </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-9 col-md-12">
                    <div class="row my-3 d-lg-none">
                        <div class="col-12 mb-3">
                            <select class="form-select researchers-mobile-select" aria-label="Select researcher category" id="dropdownShowResearchersOption">
                                <option value="sr" selected>Senior Researchers</option>
                                <option value="jr">Junior Researchers</option>
                                <option value="jra">Junior Associate Researchers</option>
                                <option value="nr">Novice Researchers</option>
                                <?php if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
                                    echo '<option value="archived">Archived Researchers</option>';
                                    echo '<option value="add">Add New Profile</option>';
                                } ?>
                            </select>
                        </div>
                    </div>

                <div class="researchers-panel" id="seniorResearchersPanel">
                    <h2>Senior Researchers</h2>

                    <div class="researcher-container">
                        <?php foreach ($researchers as $key => $researcher) {
                            if ($researcher['type'] == "Senior Researcher" && $researcher['archived'] == 0) {
                                echo "<a href='researchers/view.php?id={$researcher['researcher_id']}' class='researcher-card'>
                                    <img src='src/{$researcher['researcher_image']}' alt='' class='researcher-avatar'>
                                    <div class='researcher-info'>
                                        <div class='researcher-name'>{$researcher['name']}</div>
                                        <span class='researcher-type-badge'>{$researcher['type']}</span>
                                    </div>
                                    <i class='fas fa-chevron-right researcher-arrow'></i>
                                </a>";
                            }
                        }
                        ?>
                    </div>

                </div>

                <div class="researchers-panel" id="juniorResearchersPanel" hidden>
                    <h2>Junior Researchers</h2>

                    <div class="researcher-container">
                        <?php foreach ($researchers as $key => $researcher) {
                            if ($researcher['type'] == "Junior Researcher" && $researcher['archived'] == 0) {
                                echo "<a href='researchers/view.php?id={$researcher['researcher_id']}' class='researcher-card'>
                                    <img src='../src/{$researcher['researcher_image']}' alt='' class='researcher-avatar'>
                                    <div class='researcher-info'>
                                        <div class='researcher-name'>{$researcher['name']}</div>
                                        <span class='researcher-type-badge'>{$researcher['type']}</span>
                                    </div>
                                    <i class='fas fa-chevron-right researcher-arrow'></i>
                                </a>";
                            }
                        }
                        ?>
                    </div>

                </div>

                <div class="researchers-panel" id="juniorAssociatePanel" hidden>
                    <h2>Junior Associate Researchers</h2>

                    <div class="researcher-container">
                        <?php foreach ($researchers as $key => $researcher) {
                            if ($researcher['type'] == "Junior Associate Researcher"  && $researcher['archived'] == 0) {
                                echo "<a href='researchers/view.php?id={$researcher['researcher_id']}' class='researcher-card'>
                                    <img src='../src/{$researcher['researcher_image']}' alt='' class='researcher-avatar'>
                                    <div class='researcher-info'>
                                        <div class='researcher-name'>{$researcher['name']}</div>
                                        <span class='researcher-type-badge'>{$researcher['type']}</span>
                                    </div>
                                    <i class='fas fa-chevron-right researcher-arrow'></i>
                                </a>";
                            }
                        }
                        ?>
                    </div>

                </div>

                <div class="researchers-panel" id="novicePanel" hidden>
                    <h2>Novice Researchers</h2>

                    <div class="researcher-container">
                        <?php foreach ($researchers as $key => $researcher) {
                            if ($researcher['type'] == "Novice Researcher"  && $researcher['archived'] == 0) {
                                echo "<a href='researchers/view.php?id={$researcher['researcher_id']}' class='researcher-card'>
                                    <img src='../src/{$researcher['researcher_image']}' alt='' class='researcher-avatar'>
                                    <div class='researcher-info'>
                                        <div class='researcher-name'>{$researcher['name']}</div>
                                        <span class='researcher-type-badge'>{$researcher['type']}</span>
                                    </div>
                                    <i class='fas fa-chevron-right researcher-arrow'></i>
                                </a>";
                            }
                        }
                        ?>
                    </div>

                </div>

                <?php

                if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
                    echo '<div class="researchers-panel" id="addNewResearcherPanel" hidden>
                    <h2>Add New Profile</h2>

                    <div class="row mx-auto">
                        <div class="col-sm-12">

                            <form name="add-researcher-form">
                                <div class="row my-3">
                                    <div class="col-sm-12">
                                        <div class="text-start my-2">
                                            <label class="fw-bold">Select Profile Photo</label>
                                        </div>
                                        <div class="d-flex justify-content-start">
                                            <div id="display_image" class="my-2">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-start">
                                            <input class="my-3" type="file" id="image_input" accept=".png, .jpg, .jpeg, .svg" name="researcherImage" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-6">
                                        <label class="py-2 fw-bold">Researcher Type<span class="text-danger"> *</span></label>
                                        <select class="form-select my-2" aria-label="Default select example" id="dropdownResearcherType" name="researcherType">
                                            <option value="Senior Researcher" selected>Senior Researcher</option>
                                            <option value="Junior Researcher">Junior Researcher</option>
                                            <option value="Junior Associate Researcher">Junior Associate Researcher</option>
                                            <option value="Novice Researcher">Novice Researcher</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-6 my-2">
                                        <label class="py-2 fw-bold">College/Department<span class="text-danger"> *</span></label>
                                        <select class="form-select" aria-label="Default select example" id="dropdownResearcherDepartment" name="researcherDepartment">
                                            <option value="Basic Education Department" selected>Basic Education Department</option>
                                            <option value="Senior High School Department">Senior High School Department</option>
                                            <option value="College of Arts and Sciences">College of Arts and Sciences</option>
                                            <option value="College of Business and Accountancy">College of Business and Accountancy</option>
                                            <option value="College of Computer Studies">College of Computer Studies</option>
                                            <option value="College of Criminology">College of Criminology</option>
                                            <option value="College of Education">College of Education</option>
                                            <option value="College of Engineering, Architecture, and Aviation">College of Engineering, Architecture, and Aviation</option>
                                            <option value="College of International Hospitality Management">College of International Hospitality Management</option>
                                            <option value="College of Maritime Education">College of Maritime Education</option>
                                            <option value="Graduate School">Graduate School</option>
                                            <option value="Community Outreach Department">Community Outreach Department</option>
                                            <option value="Human Resource Department">Human Resource Department</option>
                                            <option value="Information Technology Services">Information Technology Services</option>
                                            <option value="International and External Affairs">International and External Affairs</option>
                                            <option value="Library">Library</option>
                                            <option value="Marketing Department">Marketing Department</option>
                                            <option value="Quality Assurance Office">Quality Assurance Office</option>
                                            <option value="Research and Development Center">Research and Development Center</option>
                                            <option value="Student Personnel Services">Student Personnel Services</option>
                                            <option value="University Registrar">University Registrar</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <label class="py-2 fw-bold">Name<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control" name="textFieldResearcherName" id="textFieldResearcherName" required>
                                        <label class="py-2 fw-bold">Highest Educational Attainment<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control" name="textFieldEducationalAttainment" id="textFieldEducationalAttainment" required>
                                        <label class="py-2 fw-bold">Research Interest<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control" name="textFieldResearchInterest" id="textFieldResearchInterest" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col" id="published-works-container">
                                        <label class="py-2 my-2 fw-bold">Published Works</label>
                                        <div class="publishedWork border p-3 mt-0 mb-3">
                                            <label class="fw-bold">Title</label>
                                            <input type="text" class="form-control" name="researchTitle[]" required>
                                            <label class="py-2 fw-bold">Link</label>
                                            <input type="url" class="form-control" placeholder="http://example.com" name="researchLink[]" required>
                                            <div class="text-end remove">
                                                <button type= "button" class="btn btn-link my-2 remove-button" onclick=removeWork(event)><i class="fas fa-trash-alt"></i>
                                                Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="text-end">
                                            <button type = "button" class="btn btn-link rounded-0 button-add-work" id="buttonAddWork" onclick="addWork()">Add a Published Work</button>
                                            <button class="button-save" id="buttonSave"><i class="fas fa-save me-2"></i> Save Record</button>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>';
                }

                ?>

                <?php if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) : ?>
                    <div class="researchers-panel" id="archivedPanel" hidden>
                        <h2>Archived Researchers</h2>

                        <div class="researcher-container">
                            <?php foreach ($researchers as $key => $researcher) {
                                if ($researcher['archived'] == 1) {
                                    echo "<a href='researchers/view.php?id={$researcher['researcher_id']}' class='researcher-card archived'>
                                        <img src='../src/{$researcher['researcher_image']}' alt='' class='researcher-avatar'>
                                        <div class='researcher-info'>
                                            <div class='researcher-name'>{$researcher['name']}</div>
                                            <span class='researcher-type-badge'>{$researcher['type']}</span>
                                        </div>
                                        <button class='restore-btn'>Restore</button>
                                    </a>";
                                }
                            }
                            ?>
                        </div>

                    </div>

                <?php endif ?>

            </div>
        </div>
    </section>

    <?php include_once 'includes/footer.php' ?>


    <script>
        const image_input = document.querySelector("#image_input");
        image_input.addEventListener("change", function() {
            const reader = new FileReader();
            reader.addEventListener("load", () => {
                const uploaded_image = reader.result;
                document.querySelector("#display_image").style.backgroundImage = `url(${uploaded_image})`;
            });
            reader.readAsDataURL(this.files[0]);
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".researcher-container").each(function() {
                if ($(this).find(".researcher-card").length == 0) {
                    $(this).append("<p class='empty-state'>No researcher profile found.</p>")
                }
            })

            var allPanels = '#seniorResearchersPanel, #juniorResearchersPanel, #juniorAssociatePanel, #novicePanel, #addNewResearcherPanel, #archivedPanel';
            var allNavItems = '.researcher-nav-item';

            function setActivePanel(panelId, navId) {
                $(allPanels).prop('hidden', true);
                $('#' + panelId).prop('hidden', false);
                $(allNavItems).removeClass('active');
                if (navId) $('#' + navId).addClass('active');
            }

            /* on load */
            $('#seniorResearchersText').addClass('active');

            $('#seniorResearchersText').click(function() { setActivePanel('seniorResearchersPanel', 'seniorResearchersText'); });
            $('#juniorResearchersText').click(function() { setActivePanel('juniorResearchersPanel', 'juniorResearchersText'); });
            $('#juniorAssociateText').click(function() { setActivePanel('juniorAssociatePanel', 'juniorAssociateText'); });
            $('#noviceText').click(function() { setActivePanel('novicePanel', 'noviceText'); });
            $('#archivedText').click(function() { setActivePanel('archivedPanel', 'archivedText'); });
            $('#buttonAddProfile').click(function() { setActivePanel('addNewResearcherPanel', null); });

            $('#dropdownShowResearchersOption').on('change', function() {
                var map = { sr: ['seniorResearchersPanel', 'seniorResearchersText'], jr: ['juniorResearchersPanel', 'juniorResearchersText'], jra: ['juniorAssociatePanel', 'juniorAssociateText'], nr: ['novicePanel', 'noviceText'], add: ['addNewResearcherPanel', null], archived: ['archivedPanel', 'archivedText'] };
                var val = this.value;
                if (map[val]) setActivePanel(map[val][0], map[val][1]);
            });
        });
    </script>
    <script src="scripts/bootstrap/bootstrap.js"></script>
    <script type="text/javascript">
        $("form[name='add-researcher-form']").on("submit", function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                    method: "POST",
                    url: "src/process/add-researcher.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                })
                .done(function(data) {
                    if (data.response === 'success') {
                        window.location.reload();
                    }
                })
        })

        function removeWork(event) {
            event.target.parentElement.parentElement.remove();
        }

        function addWork(event) {
            $("#published-works-container").append(`<div class="publishedWork border p-3 mt-0 mb-3">
                                            <label class="fw-bold">Title</label>
                                            <input type="text" class="form-control" name="researchTitle[]" required>
                                            <label class="py-2 fw-bold">Link</label>
                                            <input type="url" class="form-control" placeholder="http://example.com" name="researchLink[]" required>
                                            <div class="text-end remove">
                                                <button type= "button" class="btn btn-link my-2 remove-button" onclick=removeWork(event)><i class="fas fa-trash-alt"></i>
                                                Remove
                                                </button>
                                            </div>
                                        </div>`)
        }
    </script>
</body>

</html>