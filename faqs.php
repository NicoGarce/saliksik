<?php

session_start();

$maincssVersion = filemtime('styles/custom/main-style.css');
$pagecssVersion = filemtime('styles/custom/pages/faqs-style.css');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequently Asked Questions</title>
    <?php include_once 'assets/fonts/google-fonts.php' ?>

    <link rel="stylesheet" href="styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/pages/faqs-style.css?id=' . $pagecssVersion ?>" type="text/css">

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

    <?php include_once 'includes//header.php' ?>

    <section class="faqs-masthead">
        <div class="container">
            <h1>FAQS and Support</h1>
            <p class="masthead-subtitle">Answers to common questions about the UPHSL Research Repository</p>
        </div>
    </section>

    <section class="py-4">
        <div class="container px-3">

            <div class="row g-4">

                <div class="col-lg-3 d-none d-lg-block">
                    <div class="faqs-topic-nav">
                        <div class="faqs-topic-nav-title">Topics</div>
                        <a href="#faq1" class="faqs-topic-item active">
                            <i class="fas fa-user-lock"></i>Account Issues
                        </a>
                        <a href="#faq2" class="faqs-topic-item">
                            <i class="fas fa-upload"></i>Adding Works
                        </a>
                        <a href="#faq3" class="faqs-topic-item">
                            <i class="fas fa-book-open"></i>Viewing Works
                        </a>
                    </div>
                </div>

                <div class="col-lg-9 col-12">

                    <div class="d-lg-none mb-3">
                        <select class="form-select faqs-mobile-select" id="faqsMobileSelect">
                            <option value="faq1" selected>Account Issues</option>
                            <option value="faq2">Adding Works</option>
                            <option value="faq3">Viewing Works</option>
                        </select>
                    </div>

                    <div class="faqs-intro">
                        <p>Browse the topics below or use the sidebar to jump to a specific section.</p>
                    </div>

                    <!-- Account-related issues -->
                    <div class="faqs-topic-section" id="faq1">
                        <div class="faqs-topic-title">Account-related issues</div>
                        <div class="accordion" id="accordionAccount">
                            <div class="faqs-accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button faqs-accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accAccount1">
                                        I forgot my password. What should I do?
                                    </button>
                                </h2>
                                <div id="accAccount1" class="accordion-collapse collapse show" data-bs-parent="#accordionAccount">
                                    <div class="accordion-body faqs-accordion-body">
                                        <ul>
                                            <li>You can use the email address you used to register your account to reset your password.</li>
                                            <li>To do so, go to the login page and click on <strong>Forgot Password</strong>.</li>
                                            <li>Provide the email address you used to create your account. A verification code will be sent to the provided email.</li>
                                            <li>Upon successful verification, you will be able to reset your password.</li>
                                            <li>For any issues, you may email <a href="mailto:research@uphsl.edu.ph" target="_blank">research@uphsl.edu.ph</a> for assistance.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Adding works -->
                    <div class="faqs-topic-section" id="faq2">
                        <div class="faqs-topic-title">Adding works to SALIKSIK: UPHSL Research Repository</div>
                        <div class="accordion" id="accordionAdding">
                            <div class="faqs-accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button faqs-accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accAdding1">
                                        Who can submit scholarly work in the SALIKSIK: UPHSL Research Repository?
                                    </button>
                                </h2>
                                <div id="accAdding1" class="accordion-collapse collapse show" data-bs-parent="#accordionAdding">
                                    <div class="accordion-body faqs-accordion-body">
                                        <ul>
                                            <li>Students from all colleges including Graduate School who finished their final research subject required in their degree (e.g. Thesis, Capstone, Masteral Thesis, Dissertation) are all required to submit their work in this repository.</li>
                                            <li>Faculty, Department Heads, and Non-teaching Personnel from Basic Education Department, Senior High School, all Colleges, Graduate School, and Support Services Departments can submit their research works.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="faqs-accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button faqs-accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accAdding2">
                                        I have received an email notification that my work has been published. What do I do next?
                                    </button>
                                </h2>
                                <div id="accAdding2" class="accordion-collapse collapse" data-bs-parent="#accordionAdding">
                                    <div class="accordion-body faqs-accordion-body">
                                        <ul>
                                            <li>You can search or browse your research work inside the repository.</li>
                                            <li>If you are a graduating student, you may now proceed to the Research and Development Center for the signing of clearance.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="faqs-accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button faqs-accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accAdding3">
                                        I have received an email notification that my submission has been returned. What do I do next?
                                    </button>
                                </h2>
                                <div id="accAdding3" class="accordion-collapse collapse" data-bs-parent="#accordionAdding">
                                    <div class="accordion-body faqs-accordion-body">
                                        <ul>
                                            <li>Follow the provided feedback accordingly. After performing the feedback, update your submission located in &#8220;My Submission&#8221; under your profile, then resubmit your work.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Viewing works -->
                    <div class="faqs-topic-section" id="faq3">
                        <div class="faqs-topic-title">Viewing works in SALIKSIK: UPHSL Research Repository</div>
                        <div class="accordion" id="accordionViewing">
                            <div class="faqs-accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button faqs-accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accViewing1">
                                        Can I access the full-text of theses and dissertations?
                                    </button>
                                </h2>
                                <div id="accViewing1" class="accordion-collapse collapse show" data-bs-parent="#accordionViewing">
                                    <div class="accordion-body faqs-accordion-body">
                                        <ul>
                                            <li>Yes, full-text manuscripts are open-access, they can be viewed and downloaded.</li>
                                            <li>If there are no displayed manuscripts in an article, you can still access it by sending a request at <a href="mailto:research@uphsl.edu.ph" target="_blank">research@uphsl.edu.ph</a>.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="faqs-accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button faqs-accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accViewing2">
                                        Are all theses, dissertations, and research works in the library included in SALIKSIK?
                                    </button>
                                </h2>
                                <div id="accViewing2" class="accordion-collapse collapse" data-bs-parent="#accordionViewing">
                                    <div class="accordion-body faqs-accordion-body">
                                        <ul>
                                            <li>As of the moment, only recent works are available inside the repository. Old research works stored in the library are not yet available here.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

    </section>

    <?php include_once 'includes//footer.php' ?>
    <script src="scripts/bootstrap/bootstrap.js"></script>
    <script>
    (function(){
        const navItems = document.querySelectorAll('.faqs-topic-item');
        const mobileSelect = document.getElementById('faqsMobileSelect');
        const sections = document.querySelectorAll('.faqs-topic-section');

        navItems.forEach(item => {
            item.addEventListener('click', function(e){
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if(target){
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    navItems.forEach(n => n.classList.remove('active'));
                    this.classList.add('active');
                    if(mobileSelect) mobileSelect.value = this.getAttribute('href').replace('#','');
                }
            });
        });

        if(mobileSelect){
            mobileSelect.addEventListener('change', function(){
                const target = document.getElementById(this.value);
                if(target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if(entry.isIntersecting){
                    navItems.forEach(n => n.classList.remove('active'));
                    const active = document.querySelector('.faqs-topic-item[href="#' + entry.target.id + '"]');
                    if(active) active.classList.add('active');
                }
            });
        }, { rootMargin: '-80px 0px -60% 0px', threshold: 0 });

        sections.forEach(s => observer.observe(s));
    })();
    </script>
</body>

</html>