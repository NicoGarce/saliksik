<?php

session_start();

if (isset($_SESSION['isLoggedIn'])) {
    header("Location: ../../../home.php");
    die();
}

require_once '../../../includes/feature-settings.php';
if (!feature_enabled('feature_registration')) {
    header("Location: ../../../index.php");
    die();
}

$maincssVersion = filemtime('../../../styles/custom/main-style.css');
$pagecssVersion = filemtime('../../../styles/custom/pages/login-style.css');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create your account</title>
    <?php include_once '../../../assets/fonts/google-fonts.php' ?>

    <script src="../../../scripts/jquery/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../../../styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo '../../../styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo '../../../styles/custom/pages/login-style.css?id=' . $pagecssVersion ?>" type="text/css">

    <link rel="apple-touch-icon" sizes="180x180" href="../../../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../../favicon-16x16.png">
    <link rel="manifest" href="../../../site.webmanifest">
    <link rel="mask-icon" href="../../../safari-pinned-tab.svg" color="#5bbad5">

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
    <main class=" main">
        <div class="container mx-auto my-5">
            <div class="row mx-auto">
                <div class="col-lg-5 mx-auto d-flex justify-content-center align-items-center">
                    <div class="text-center text-white">
                        <img src="../../../assets/images/core/saliksik-logo.png" id="saliksik-logo" alt="SALIKSIK: UPHSL Research Repository" class="img-fluid">
                        <p class="h4 d-none d-lg-block">The Official Institutional Repository of University of Perpetual Help System Laguna</p>
                    </div>
                </div>
                <div class="col-lg-5 mx-auto p-5 bg-light my-3">
                    <div class="row py-2" id="alert-container-register">
                        <!--  -->
                    </div>
                    <div class="row py-2">
                        <h5>Create your account</h5>
                    </div>
                    <div class="row">
                        <form onsubmit="submitRegister(event)" name="register-form">

                            <div class="form-floating my-2">
                                <input class="form-control" type="text" name="textFieldFirstName" id="textFieldFirstName" placeholder=" " autofocus>
                                <label for="textFieldFirstName">First Name</label>
                            </div>
                            <div class="form-floating my-2">
                                <input class="form-control" type="text" name="textFieldLastName" id="textFieldLastName" placeholder=" ">
                                <label for="textFieldLastName">Last Name</label>
                            </div>
                            <div class="form-floating my-2">
                                <select class="form-select" aria-label="College/Department" name="dropdownDeparment" id="dropdownDeparment">
                                    <option value="" disabled>Choose department</option>
                                    <option value="Basic Education" selected>Basic Education</option>
                                    <option value="Senior High School">Senior High School</option>
                                    <option value="Arts and Sciences">Arts and Sciences</option>
                                    <option value="Business and Accountancy">Business and Accountancy</option>
                                    <option value="Computer Studies">Computer Studies</option>
                                    <option value="Criminology">Criminology</option>
                                    <option value="Education">Education</option>
                                    <option value="Engineering, Architecture and Aviation">Engineering, Architecture and Aviation</option>
                                    <option value="Law">Law</option>
                                    <option value="Maritime Education">Maritime Education</option>
                                    <option value="International Hospitality Management">International Hospitality Management</option>
                                    <option value="Graduate School">Graduate School</option>
                                    <option value="Support Services">Support Services</option>
                                </select>
                                <label for="dropdownDeparment">College/Department</label>
                            </div>
                            <div class="form-floating my-2">
                                <input class="form-control" type="text" name="textFieldEmail" id="textFieldEmail" placeholder=" ">
                                <label for="textFieldEmail">School Email</label>
                            </div>
                            <div class="form-floating my-2 position-relative">
                                <input class="form-control" type="password" name="textFieldPassword" id="textFieldPassword" placeholder=" ">
                                <label for="textFieldPassword">Password</label>
                                <span class="toggle-password fas fa-eye" data-target="textFieldPassword"></span>
                            </div>
                            <div class="form-floating my-2 position-relative">
                                <input class="form-control" type="password" name="textFieldConfirmPassword" id="textFieldConfirmPassword" placeholder=" ">
                                <label for="textFieldConfirmPassword">Confirm Password</label>
                                <span class="toggle-password fas fa-eye" data-target="textFieldConfirmPassword"></span>
                            </div>
                            <button class="btn text-white w-100 mt-2 mb-1" type="submit" name="buttonCreateAccount" id="buttonCreateAccount">Create account</button>
                        </form>
                        <div class="text-center pt-2">
                            <p>Have an account? <a href="../../../index.php" class="to-login">Click here to login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        var alertRegister = document.getElementById('alert-container-register');

        function submitRegister(event) {
            event.preventDefault();
            var registerForm = document.forms.namedItem('register-form');
            var registerData = new FormData(registerForm);
            postRegister(registerData)
                .then(responseText => {
                    try {
                        const data = JSON.parse(responseText);
                        checkResponseRegister(data);
                    } catch (err) {
                        console.error('Failed to parse JSON response from server:', err);
                        console.groupCollapsed('Server response (invalid JSON)');
                        console.log(responseText);
                        console.groupEnd();
                        // Show server response to user for debugging (escaped)
                        alertRegister.innerHTML = `<div class="alert alert-danger" role="alert"><strong>Server error:</strong> See console for details.</div>`;
                    }
                })
                .catch(err => {
                    console.error('Network or server error during registration request:', err);
                    alertRegister.innerHTML = `<div class="alert alert-danger" role="alert"><strong>Network error:</strong> Could not reach the server.</div>`;
                });
        }

        function postRegister(data) {
            return new Promise((resolve, reject) => {
                var http = new XMLHttpRequest();
                http.open("POST", "../../process/register.php");
                http.onload = () => http.status == 200 ? resolve(http.response) : reject(Error(http.statusText));
                http.onerror = (e) => reject(Error(`Networking error: ${e}`));
                http.send(data)
            })
        }

        function checkResponseRegister(data) {
            if (data.response === "empty_fields") {
                alertRegister.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Invalid input!</strong> Please fill up all the fields.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`
            }
            if (data.response === "passwords_mismatch") {
                alertRegister.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Invalid input!</strong> Password and Confirm Password do not match.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`
            }
            if (data.response === "not_school_email") {
                alertRegister.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Invalid email!</strong> Please use your school email.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`
            }
            if (data.response === "email_exists") {
                alertRegister.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>An account with this email already exists.</strong> Try another one.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`
            }
            if (data.response === "invalid_email") {
                alertRegister.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Invalid input!</strong> Please enter a valid e-mail.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`
            }
            if (data.response === "success") {
                window.location = "account-verification.php";
            }
        }
    </script>
    <script>
        $(document).on('click', '.toggle-password', function() {
            $(this).toggleClass("fas fa-eye fas fa-eye-slash");
            var target = $("#" + $(this).data("target"));
            target.attr('type', target.attr('type') === 'password' ? 'text' : 'password');
        });
    </script>
    <script src="../../../scripts/bootstrap/bootstrap.js"></script>
</body>

</html>