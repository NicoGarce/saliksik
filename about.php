<?php

session_start();

if (!isset($_SESSION['isLoggedIn'])) {
    header("location: ../index.php?location=" . urlencode($_SERVER['REQUEST_URI']));
    die();
}

$maincssVersion = filemtime('styles/custom/main-style.css');
$pagecssVersion = filemtime('styles/custom/pages/about-style.css');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <?php include_once 'assets/fonts/google-fonts.php' ?>

    <link rel="stylesheet" href="styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/pages/about-style.css?id=' . $pagecssVersion ?>" type="text/css">

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

    <section class="about-masthead">
        <div class="container">
            <div class="about-masthead-inner">
                <img src="android-chrome-256x256.png" alt="SALIKSIK" class="about-masthead-logo">
                <div>
                    <h1>SALIKSIK: UPHSL Research Repository</h1>
                    <p class="masthead-subtitle">Learn about our repository, policies, and the team behind it</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="container px-3">

            <div class="row g-4">

                <div class="col-lg-3 d-none d-lg-block">
                    <div class="about-sidebar">
                        <div class="about-sidebar-title">On this page</div>
                        <a class="about-nav-item active" data-panel="about" href="javascript:void(0)">
                            <i class="fas fa-info-circle"></i>About the Repository
                        </a>
                        <a class="about-nav-item" data-panel="copyright" href="javascript:void(0)">
                            <i class="fas fa-shield-alt"></i>Copyright & Policies
                        </a>
                        <a class="about-nav-item" data-panel="team" href="javascript:void(0)">
                            <i class="fas fa-users"></i>The Team Behind
                        </a>
                    </div>
                </div>

                <div class="col-lg-9 col-12">

                    <div class="d-lg-none mb-3">
                        <select class="form-select about-mobile-select" id="dropdownAboutOption">
                            <option value="about" selected>About the Repository</option>
                            <option value="copyright">Copyright & Policies</option>
                            <option value="team">The Team Behind</option>
                        </select>
                    </div>

                    <div class="about-panel" id="aboutRepositoryPanel">
                        <h2>What is the SALIKSIK: UPHSL Research Repository?</h2>
                        <p>UPHSL Research Repository is an online tool and database where you can view, upload and download materials pertaining to research outputs of the university. It allows you to have access to a wide array of research materials in terms of a given time period, particular college/department, or research areas. It also provides access to the annual reports, research competency development program, institutional research agenda and other relevant research documents of the Research Center.</p>
                        <hr class="about-section-divider">
                        <h2>What type of items are included on the Research Repository?</h2>
                        <p>The types of documents included in the Research Repository are full-length manuscripts of theses and dissertations, research reports in IMRD format, research journals of colleges and departments, annual reports, competency development program, and research agenda.</p>
                        <p>Please see the <a href="submit.php">Submit</a> page to learn the benefits of having your research on the Repository and how to submit/contribute your works.</p>
                        <div class="d-lg-none">
                            <hr class="about-section-divider">
                            <h2>Copyright ownership in a work</h2>
                            <p>While research outputs submitted become part of the UPHSL Research Repository, the authorship remains to a researcher or group of researchers as articulated in the Research and Development Manual of the University and its Intellectual Property Rights policy. It should be noted that the ownership and authorship of researches submitted remain to the author/s even if the materials are already uploaded to the UPHSL Research Repository unless the University Declaration of IP Assignment form is signed.</p>
                            <hr class="about-section-divider">
                            <h2>Copyright information for users</h2>
                            <p>The University reserves the right to use, publish and reproduce IP creation in whatever form i.e. electronic or otherwise for teaching, research and other academic purposes with appropriate notification and acknowledgment of whoever originally owns the materials uploaded, viewed and downloaded through the UPHSL Research Repository.</p>
                            <hr class="about-section-divider">
                            <h2>Privacy Policy</h2>
                            <p>UPHSL Research Repository follows a restricted access environment through username and password of all teaching, non-teaching, and administrative personnel of the institution as well as all its bona fide students who wish to use the tool for their research and academic purposes. No personal information of the researchers are provided except for those which are part of the prescribed format and expected contents of their manuscript like their names and email addresses. UPHSL Research Repository complies with the provisions of Data Privacy Act of 2012.</p>
                        </div>
                    </div>

                    <div class="about-panel" id="copyrightPoliciesPanel" hidden>
                        <h2>Copyright ownership in a work</h2>
                        <p>While research outputs submitted become part of the UPHSL Research Repository, the authorship remains to a researcher or group of researchers as articulated in the Research and Development Manual of the University and its Intellectual Property Rights policy. It should be noted that the ownership and authorship of researches submitted remain to the author/s even if the materials are already uploaded to the UPHSL Research Repository unless the University Declaration of IP Assignment form is signed.</p>
                        <hr class="about-section-divider">
                        <h2>Copyright information for users</h2>
                        <p>The University reserves the right to use, publish and reproduce IP creation in whatever form i.e. electronic or otherwise for teaching, research and other academic purposes with appropriate notification and acknowledgment of whoever originally owns the materials uploaded, viewed and downloaded through the UPHSL Research Repository.</p>
                        <hr class="about-section-divider">
                        <h2>Privacy Policy</h2>
                        <p>UPHSL Research Repository follows a restricted access environment through username and password of all teaching, non-teaching, and administrative personnel of the institution as well as all its bona fide students who wish to use the tool for their research and academic purposes. No personal information of the researchers are provided except for those which are part of the prescribed format and expected contents of their manuscript like their names and email addresses. UPHSL Research Repository complies with the provisions of Data Privacy Act of 2012.</p>
                    </div>

                    <div class="about-panel" id="aboutDevelopersPanel" hidden>
                        <h2>Acknowledgment</h2>
                        <p>The <strong>SALIKSIK: UPHSL Research Repository</strong> is a Capstone Project by the <strong>Group IT3</strong> of <strong>BS Information Technology Batch 2022</strong> of the <strong>College of Computer Studies</strong>.</p>
                        <p>It was developed through the collective efforts and support of the following people:</p>

                        <div class="mt-4">
                            <div class="about-team-group-title">Development Team</div>
                            <div class="about-team-grid">
                                <div class="about-member-card">
                                    <img src="assets/images/about/mico-sta-maria.jpg" alt="Mico Sta. Maria" class="about-member-photo">
                                    <div class="about-member-name">Mico Sta. Maria</div>
                                    <div class="about-member-role">Project Manager, Lead UI/UX Designer</div>
                                    <div class="about-member-socials">
                                        <a href="https://www.linkedin.com/in/mico-sta-maria/" target="_blank" class="about-social-linkedin"><i class="fab fa-linkedin-in"></i></a>
                                    </div>
                                </div>
                                <div class="about-member-card">
                                    <img src="assets/images/about/serking-de-orayom.jpg" alt="Serking de Orayom" class="about-member-photo">
                                    <div class="about-member-name">Serking de Orayom</div>
                                    <div class="about-member-role">Lead Front-End Developer, UI/UX Designer, Backend Developer</div>
                                    <div class="about-member-socials">
                                        <a href="https://www.linkedin.com/in/kingdeorayom/" target="_blank" class="about-social-linkedin"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="https://github.com/kingdeorayom" target="_blank" class="about-social-github"><i class="fab fa-github"></i></a>
                                    </div>
                                </div>
                                <div class="about-member-card">
                                    <img src="assets/images/about/marc-lloyd-menguito.jpg" alt="Marc Lloyd Menguito" class="about-member-photo">
                                    <div class="about-member-name">Marc Lloyd Menguito</div>
                                    <div class="about-member-role">Lead Backend Developer, Front-End Developer</div>
                                    <div class="about-member-socials">
                                        <a href="https://www.linkedin.com/in/lloyd-menguito-660617153/" target="_blank" class="about-social-linkedin"><i class="fab fa-linkedin-in"></i></a>
                                    </div>
                                </div>
                                <div class="about-member-card">
                                    <img src="assets/images/about/hazel-anne-datuin.jpg" alt="Hazel Anne Datuin" class="about-member-photo">
                                    <div class="about-member-name">Hazel Anne Datuin</div>
                                    <div class="about-member-role">Software Quality Assurance Analyst, Software Testing</div>
                                    <div class="about-member-socials">
                                        <a href="https://www.linkedin.com/in/hazel-anne-datuin-a10888242/" target="_blank" class="about-social-linkedin"><i class="fab fa-linkedin-in"></i></a>
                                    </div>
                                </div>
                                <div class="about-member-card">
                                    <img src="assets/images/about/arveey-nickole-almazan.jpg" alt="Arveey Nickole Almazan" class="about-member-photo">
                                    <div class="about-member-name">Arveey Nickole Almazan</div>
                                    <div class="about-member-role">Software Quality Assurance Analyst, Software Testing</div>
                                    <div class="about-member-socials">
                                        <a href="https://www.linkedin.com/in/arveey-nickole-almazan-5b1602207/" target="_blank" class="about-social-linkedin"><i class="fab fa-linkedin-in"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="about-team-group-title">Information Technology Services</div>
                            <div class="about-team-grid">
                                <div class="about-member-card">
                                    <div class="about-member-photo-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="about-member-name">Nico Roell Garce</div>
                                    <div class="about-member-role">Web Administrator, UI/UX Developer</div>
                                    <div class="about-member-socials"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="about-team-group-title">Research and Development Center</div>
                            <div class="about-team-grid">
                                <div class="about-member-card">
                                    <img src="assets/images/about/leomar-galicia.jpg" alt="Dr. Leomar Galicia" class="about-member-photo">
                                    <div class="about-member-name">Dr. Leomar Galicia, LPT</div>
                                    <div class="about-member-role">University Research Director</div>
                                    <div class="about-member-socials"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="about-team-group-title">College of Computer Studies</div>
                            <div class="about-team-grid">
                                <div class="about-member-card">
                                    <img src="assets/images/about/ma-eliza-mapanoo.jpg" alt="Dr. Ma. Eliza Mapanoo" class="about-member-photo">
                                    <div class="about-member-name">Dr. Ma. Eliza Mapanoo</div>
                                    <div class="about-member-role">Development Team Research Adviser<br>Faculty, College of Computer Studies</div>
                                    <div class="about-member-socials"></div>
                                </div>
                                <div class="about-member-card">
                                    <img src="assets/images/about/oliver-junio.jpg" alt="Oliver Junio" class="about-member-photo">
                                    <div class="about-member-name">Oliver Junio, MBA, MSIT</div>
                                    <div class="about-member-role">Dean, College of Computer Studies</div>
                                    <div class="about-member-socials"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

    </section>

    <!--Footer section-->

    <?php include_once 'includes/footer.php' ?>

    <script>
    (function(){
        const panels = {
            about: document.getElementById('aboutRepositoryPanel'),
            copyright: document.getElementById('copyrightPoliciesPanel'),
            team: document.getElementById('aboutDevelopersPanel')
        };
        const navItems = document.querySelectorAll('.about-nav-item');
        const mobileSelect = document.getElementById('dropdownAboutOption');

        function showPanel(key){
            Object.keys(panels).forEach(k => { panels[k].hidden = k !== key; });
            navItems.forEach(item => { item.classList.toggle('active', item.dataset.panel === key); });
            if(mobileSelect) mobileSelect.value = key;
        }

        navItems.forEach(item => {
            item.addEventListener('click', function(){ showPanel(this.dataset.panel); });
        });

        if(mobileSelect){
            mobileSelect.addEventListener('change', function(){ showPanel(this.value); });
        }
    })();
    </script>
    <script src="scripts/bootstrap/bootstrap.js"></script>

</body>

</html>