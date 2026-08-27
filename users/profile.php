<?php

session_start();

if (isset($_SESSION['userType'])) {
    if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
        header("Location: ../admin/profile.php");
    }
} else {
    header("location: ../error.php");
    die();
}

include '../includes/connection.php';

$userStmt = $connection->prepare("SELECT first_name, last_name, department, email, created_at FROM users WHERE user_id = ?");
$userId = (int)$_SESSION['userid'];
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();
$userStmt->close();

$initials = strtoupper(mb_substr($user['first_name'], 0, 1) . mb_substr($user['last_name'], 0, 1));

$maincssVersion = filemtime('../styles/custom/main-style.css');
$pagecssVersion = filemtime('../styles/custom/pages/profile-style.css');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | SALIKSIK</title>
    <?php include_once '../assets/fonts/google-fonts.php' ?>
    <script src="../scripts/jquery/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo '../styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo '../styles/custom/pages/profile-style.css?id=' . $pagecssVersion ?>" type="text/css">

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

    <section class="admin-masthead">
        <div class="container">
            <h1>My Profile</h1>
            <p class="masthead-subtitle">Manage your personal information and account security</p>
        </div>
    </section>

    <section class="py-4">
        <div class="container px-3">

            <?php if (isset($_SESSION['changedAbout'])) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Account details updated successfully!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php unset($_SESSION['changedAbout']); endif; ?>

            <?php if (isset($_SESSION['changedPassword'])) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Password changed successfully!</strong> In case of forgotten password, proceed to the <strong>Forgot Password</strong> section in the login page to reset your password.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php unset($_SESSION['changedPassword']); endif; ?>

            <?php if (isset($_SESSION['wrongPassword'])) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Password change unsuccessful!</strong> The current password you entered is incorrect.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php unset($_SESSION['wrongPassword']); endif; ?>

            <div class="row g-4">

                <!-- Summary card -->
                <div class="col-lg-4">
                    <div class="admin-panel text-center h-100 profile-summary">
                        <div class="profile-avatar"><?php echo htmlspecialchars($initials); ?></div>
                        <h5 class="profile-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h5>
                        <p class="profile-email mb-2"><?php echo htmlspecialchars($user['email']); ?></p>
                        <span class="admin-badge admin-badge-user">Standard User</span>
                        <div class="mt-4 text-start">
                            <div class="profile-meta">
                                <span>College/Department</span>
                                <span><?php echo htmlspecialchars($user['department']); ?></span>
                            </div>
                            <div class="profile-meta">
                                <span>Member since</span>
                                <span><?php echo date('M j, Y', strtotime($user['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Forms -->
                <div class="col-lg-8">

                    <!-- Profile Information -->
                    <div class="admin-panel">
                        <div class="admin-panel-title"><i class="fas fa-user me-1"></i> Profile Information</div>
                        <form action="../src/process/update-user-profile.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="admin-label" for="textFieldEmailAddress">Email Address</label>
                                    <input type="text" class="form-control admin-input" name="textFieldEmailAddress" id="textFieldEmailAddress" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" disabled>
                                    <small class="text-muted">For security purposes, your email address cannot be changed.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="admin-label" for="textFieldFirstName">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control admin-input" name="textFieldFirstName" id="textFieldFirstName" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="admin-label" for="textFieldLastName">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control admin-input" name="textFieldLastName" id="textFieldLastName" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="admin-label" for="dropdownDepartment">College/Department</label>
                                    <select class="form-select admin-select" id="dropdownDepartment" name="dropdownDepartment">
                                        <option value="Basic Education" <?= $user['department'] == "Basic Education" ? 'selected' : '' ?>>Basic Education</option>
                                        <option value="Senior High School" <?= $user['department'] == "Senior High School" ? 'selected' : '' ?>>Senior High School</option>
                                        <option value="Arts and Sciences" <?= $user['department'] == "Arts and Sciences" ? 'selected' : '' ?>>Arts and Sciences</option>
                                        <option value="Business and Accountancy" <?= $user['department'] == "Business and Accountancy" ? 'selected' : '' ?>>Business and Accountancy</option>
                                        <option value="Computer Studies" <?= $user['department'] == "Computer Studies" ? 'selected' : '' ?>>Computer Studies</option>
                                        <option value="Criminology" <?= $user['department'] == "Criminology" ? 'selected' : '' ?>>Criminology</option>
                                        <option value="Education" <?= $user['department'] == "Education" ? 'selected' : '' ?>>Education</option>
                                        <option value="Engineering, Architecture and Aviation" <?= $user['department'] == "Engineering, Architecture and Aviation" ? 'selected' : '' ?>>Engineering, Architecture and Aviation</option>
                                        <option value="Law" <?= $user['department'] == "Law" ? 'selected' : '' ?>>Law</option>
                                        <option value="Maritime Education" <?= $user['department'] == "Maritime Education" ? 'selected' : '' ?>>Maritime Education</option>
                                        <option value="International Hospitality Management" <?= $user['department'] == "International Hospitality Management" ? 'selected' : '' ?>>International Hospitality Management</option>
                                        <option value="Graduate School" <?= $user['department'] == "Graduate School" ? 'selected' : '' ?>>Graduate School</option>
                                        <option value="Support Services" <?= $user['department'] == "Support Services" ? 'selected' : '' ?>>Support Services</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-end mt-4">
                                <button type="submit" class="admin-btn"><i class="fas fa-save me-1"></i> Save Changes</button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password -->
                    <div class="admin-panel">
                        <div class="admin-panel-title"><i class="fas fa-lock me-1"></i> Change Password</div>
                        <form action="../src/process/update-user-password.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="admin-label" for="textFieldCurrentPassword">Current Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control admin-input" name="textFieldCurrentPassword" id="textFieldCurrentPassword" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="admin-label" for="textFieldNewPassword">New Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control admin-input" name="textFieldNewPassword" id="textFieldNewPassword" required>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-4">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="checkboxShowHidePassword">
                                    <label class="form-check-label" for="checkboxShowHidePassword">Show Password</label>
                                </div>
                                <button type="submit" class="admin-btn"><i class="fas fa-key me-1"></i> Update Password</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php include_once '../includes/footer.php' ?>
    <script src="../scripts/bootstrap/bootstrap.js"></script>

    <script>
        $(document).ready(function() {
            $("#checkboxShowHidePassword").change(function() {
                var t = $(this).is(':checked') ? 'text' : 'password';
                $("#textFieldCurrentPassword, #textFieldNewPassword").attr("type", t);
            });
        });
    </script>

</body>

</html>
