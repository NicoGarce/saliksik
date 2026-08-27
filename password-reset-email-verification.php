<?php

session_start();

include 'sendmail-forgot-password.php';

$maincssVersion = filemtime('styles/custom/main-style.css');
$pagecssVersion = filemtime('styles/custom/pages/login-style.css');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify your email | SALIKSIK</title>
    <?php include_once 'assets/fonts/google-fonts.php' ?>

    <link rel="stylesheet" href="styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/pages/login-style.css?id=' . $pagecssVersion ?>" type="text/css">
    <link rel="stylesheet" href="plugins/sweetalert/package/dist/sweetalert2.css" type="text/css">

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

</head>

<body class="d-flex flex-column min-vh-100">

    <!--Main Section-->
    <main class="main">
        <div class="container mx-auto my-5 d-flex justify-content-center align-items-center h-auto">
            <div class="col-lg-6 p-5 bg-light">
                <div class="row py-2" id="alert-container-verification-code">
                    <!--Container for alert message-->
                </div>
                <div class="row py-2">
                    <h3>Please check your email to continue</h3>
                </div>
                <div class="row">
                    <form onsubmit="submitVerificationCode(event)" name="verification-code-form">

                        <!-- <form action="../../process/reset-password-redirect.php" method="POST"> -->
                        <label class="mb-3">We sent a one-time verification code to the email address you provided.<br>Please enter the code below to reset your password.</label>
                        <input class="form-control" type="text" name="textFieldVerificationCode" id="textFieldVerificationCode" autofocus maxlength="6">

                        <button class="btn text-white w-100 mt-4" type="submit" name="buttonSubmitVerificationCode" id="buttonSubmitVerificationCode">Submit</button>
                        <a href="src/process/logout.php"><button class="btn btn-secondary text-white w-100 mt-2 mb-2" type="button" name="buttonCancel" id="buttonCancel">Cancel</button></a>
                    </form>
                    <div class="text-center py-2 resend-verification-code">
                        <p onclick="fireSweetAlertResendVerificationCode();">Didn't receive a code? Click here to resend</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function fireSweetAlertResendVerificationCode() {
            Swal.fire({
                title: 'Request a new verification code?',
                text: 'A new code will be sent to your registered email address.',
                showDenyButton: true,
                confirmButtonText: 'Yes, send new code',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#012265',
                cancelButtonColor: '#64748b',
                denyButtonColor: '#64748b',
                customClass: {
                    popup: 'salik-swal-popup',
                    title: 'salik-swal-title',
                    htmlContainer: 'salik-swal-text'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Code Sent!',
                        text: 'A new verification code has been sent to your email.',
                        icon: 'success',
                        confirmButtonColor: '#012265',
                        customClass: {
                            popup: 'salik-swal-popup',
                            title: 'salik-swal-title'
                        }
                    }).then(() => { location.reload(); });
                }
            })
        }
    </script>

    <script>
        var alertVerificationCode = document.getElementById('alert-container-verification-code')

        function submitVerificationCode(event) {
            event.preventDefault();
            var loginForm = document.forms.namedItem('verification-code-form');
            var loginData = new FormData(loginForm)
            postVerificationCode(loginData).then(data => checkVerificationCodeResponse(JSON.parse(data)));
        }

        function postVerificationCode(data) {
            return new Promise((resolve, reject) => {
                var http = new XMLHttpRequest();
                http.open("POST", "src/process/reset-password-redirect.php");
                http.onload = () => http.status == 200 ? resolve(http.response) : reject(Error(http.statusText));
                http.onerror = (e) => reject(Error(`Networking error: ${e}`));
                http.send(data)
            })
        }

        function checkVerificationCodeResponse(data) {
            if (data.response === "verification_success") {
                window.location.href = "/reset-password.php";
            }
            if (data.response === "empty_fields") {
                alertVerificationCode.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Invalid input!</strong> Please fill up all the fields.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`
            }
            if (data.response === "incorrect_credentials") {
                alertVerificationCode.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Incorrect verification code!</strong> Please try again.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`
            }
        }
    </script>

    <script src="plugins/sweetalert/package/dist/sweetalert2.js"></script>
    <style>
        .salik-swal-popup { border-radius: 14px !important; font-family: 'Inter', Arial, sans-serif !important; }
        .salik-swal-title { color: #012265 !important; font-weight: 700 !important; font-size: 1.1rem !important; }
        .salik-swal-text { color: #64748b !important; font-size: .88rem !important; }
    </style>
    <script src="scripts/bootstrap/bootstrap.js"></script>
</body>

</html>