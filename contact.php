<?php

session_start();

if (!isset($_SESSION['isLoggedIn'])) {
    header("location: ../index.php?location=" . urlencode($_SERVER['REQUEST_URI']));
    die();
}

$maincssVersion = filemtime('styles/custom/main-style.css');
$pagecssVersion = filemtime('styles/custom/pages/contact-style.css');

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | SALIKSIK</title>
    <?php include_once 'assets/fonts/google-fonts.php' ?>

    <link rel="stylesheet" href="styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/pages/contact-style.css?id=' . $pagecssVersion ?>" type="text/css">

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

    <section class="contact-masthead">
        <div class="container">
            <div class="contact-masthead-inner">
                <img src="assets/images/contact/research-development-logo.png" alt="R&D Center" class="contact-masthead-logo">
                <div>
                    <h1>Research and Development Center</h1>
                    <p class="masthead-subtitle">University of Perpetual Help System Laguna</p>
                </div>
            </div>
        </div>
    </section>

    <section class="contacts">
        <div class="container py-4 px-3">

            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="contact-card">
                        <h2 class="contact-section-title">Get in Touch</h2>
                        <p class="mb-3" style="color: var(--muted); font-size: .9rem;">For further information or assistance in submitting research, please contact:</p>

                        <div class="contact-detail">
                            <div class="contact-detail-icon"><i class="fas fa-envelope"></i></div>
                            <div class="contact-detail-text">
                                <a href="mailto:research@uphsl.edu.ph">research@uphsl.edu.ph</a>
                            </div>
                        </div>
                        <div class="contact-detail">
                            <div class="contact-detail-icon"><i class="fas fa-phone-alt"></i></div>
                            <div class="contact-detail-text">049-544-5162</div>
                        </div>
                        <div class="contact-detail">
                            <div class="contact-detail-icon"><i class="fab fa-facebook-f"></i></div>
                            <div class="contact-detail-text">
                                <a href="https://www.facebook.com/UPHSL-Research-and-Development-Center-100628592098500/" target="_blank">UPHSL Research and Development Center</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="contact-card">
                        <h2 class="contact-section-title">Send Us a Message</h2>
                        <form id="contactForm" name="contact-form">
                            <div class="mb-3">
                                <label for="contactName" class="contact-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control contact-input" id="contactName" name="name" placeholder="Your full name" required>
                            </div>
                            <div class="mb-3">
                                <label for="contactEmail" class="contact-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control contact-input" id="contactEmail" name="email" placeholder="you@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="contactSubject" class="contact-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control contact-input" id="contactSubject" name="subject" placeholder="How can we help?" required>
                            </div>
                            <div class="mb-3">
                                <label for="contactMessage" class="contact-label">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control contact-input" id="contactMessage" name="message" rows="5" placeholder="Write your message here..." required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="contact-submit-btn" id="contactSubmitBtn">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                            <div id="contactSuccess" class="contact-alert contact-alert-success mt-3" hidden>
                                <i class="fas fa-check-circle me-2"></i>Your message has been sent successfully. We will get back to you soon.
                            </div>
                            <div id="contactError" class="contact-alert contact-alert-error mt-3" hidden>
                                <i class="fas fa-exclamation-circle me-2"></i>Something went wrong. Please try again later.
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    </section>

    <?php include_once 'includes/footer.php' ?>
    <script src="scripts/bootstrap/bootstrap.js"></script>
    <script>
    (function(){
        const form = document.getElementById('contactForm');
        const btn = document.getElementById('contactSubmitBtn');
        const success = document.getElementById('contactSuccess');
        const error = document.getElementById('contactError');

        form.addEventListener('submit', function(e){
            e.preventDefault();
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            success.hidden = true;
            error.hidden = true;

            const data = new FormData(form);

            fetch('src/process/submit-contact.php', {
                method: 'POST',
                body: data
            })
            .then(r => r.json())
            .then(res => {
                if(res.status === 'success'){
                    form.reset();
                    success.hidden = false;
                } else {
                    error.hidden = false;
                }
            })
            .catch(() => { error.hidden = false; })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Message';
            });
        });
    })();
    </script>

</body>

</html>