<?php
// Renders a friendly "feature unavailable" screen. Caller must exit() after including.
$maincssVersion = filemtime(__DIR__ . '/../styles/custom/main-style.css');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feature Unavailable</title>
    <?php include_once __DIR__ . '/../assets/fonts/google-fonts.php' ?>
    <link rel="stylesheet" href="../styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo '../styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include_once __DIR__ . '/header.php' ?>

    <section class="submit-research profile flex-grow-1">
        <div class="container p-5">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8 main-column p-5 my-5">
                    <h1 class="display-5 fw-bold">Feature Currently Unavailable</h1>
                    <hr class="my-4">
                    <p class="lead"><?php echo htmlspecialchars($disabledFeatureMessage ?? 'This feature has been temporarily disabled by the site administrator.'); ?></p>
                    <a href="../home.php" class="btn btn-primary btn-lg rounded-0 my-3">Back to Home</a>
                </div>
            </div>
        </div>
    </section>

    <?php include_once __DIR__ . '/footer.php' ?>
</body>

</html>
