<?php

$maincssVersion = filemtime('styles/custom/main-style.css');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error | SALIKSIK</title>
    <?php include_once 'assets/fonts/google-fonts.php' ?>

    <link rel="stylesheet" href="styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">

    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

<?php include 'includes/fontawesome.php' ?>
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="d-flex flex-grow-1">
        <div class="container py-5">
            <div class="row justify-content-center align-items-center min-vh-75">
                <div class="col-lg-6 text-center">
                    <img src="assets/images/core/error-image.png" alt="Error" class="img-fluid mb-4" style="max-width: 280px;">
                    <h1 style="font-size: 4rem; font-weight: 800; color: var(--navy-900); margin-bottom: .5rem;">Oops!</h1>
                    <p style="color: var(--muted); font-size: .95rem; margin-bottom: 2rem;">Something went wrong. You may be accessing a page the wrong way, not logged in, or lacking the correct permissions.</p>
                    <a href="index.php" class="admin-btn" style="text-decoration: none; display: inline-flex; align-items: center; gap: .5rem;">
                        <i class="fas fa-arrow-left"></i>Return to Login
                    </a>
                    <p class="mt-4" style="color: var(--muted); font-size: .78rem;">SALIKSIK: UPHSL Research Repository</p>
                </div>
            </div>
        </div>
    </div>
    <script src="scripts/bootstrap/bootstrap.js"></script>
</body>

</html>
