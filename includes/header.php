<?php
// Compute base URL dynamically so asset and page links work on local and production
$projectRoot = realpath(__DIR__ . '/..');
$docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
$base_url = str_replace('\\', '/', str_replace($docRoot, '', $projectRoot));
$base_url = $base_url === '' ? '' : '/' . ltrim($base_url, '/');
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="forStickyTop sticky-top">
    <section class="header navbar navbar-dark bg-dark flex-nowrap">
        <div class="container-fluid px-4 p-2 flex-nowrap">
            <div class="d-flex align-items-center">
                <a href="<?= $base_url ?>/home.php"><img src="<?= $base_url ?>/assets/images/core/saliksik-logo.png" id="header-logo" alt="SALIKSIK: UPHSL Research Repository" class="img-fluid mx-2"></a>
            </div>

            <?php if (isset($_SESSION['isLoggedIn'])): ?>
            <div class="navbar navbar-expand-md">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 p-1 d-none d-lg-flex">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'home.php' ? 'active' : '' ?>" href="<?= $base_url ?>/home.php"><i class="fas fa-home nav-ico"></i>HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'repository.php' ? 'active' : '' ?>" href="<?= $base_url ?>/repository.php"><i class="fas fa-book-open nav-ico"></i>REPOSITORY</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'statistics.php' ? 'active' : '' ?>" href="<?= $base_url ?>/statistics.php"><i class="fas fa-chart-bar nav-ico"></i>STATISTICS</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'submit.php' ? 'active' : '' ?>" href="<?= $base_url ?>/submit.php"><i class="fas fa-paper-plane nav-ico"></i>SUBMIT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'researchers.php' ? 'active' : '' ?>" href="<?= $base_url ?>/researchers.php"><i class="fas fa-users nav-ico"></i>RESEARCHERS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>" href="<?= $base_url ?>/contact.php"><i class="fas fa-envelope nav-ico"></i>CONTACT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'about.php' ? 'active' : '' ?>" href="<?= $base_url ?>/about.php"><i class="fas fa-info-circle nav-ico"></i>ABOUT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'faqs.php' ? 'active' : '' ?>" href="<?= $base_url ?>/faqs.php"><i class="fas fa-question-circle nav-ico"></i>FAQs</a>
                    </li>
                </ul>
                <div class="user-dropdown d-none d-lg-block">
                    <button class="btn dropdown-toggle text-white user-dropdown-btn" type="button" id="dropdownMenuButton1" aria-expanded="false"><i class="fas fa-user-circle me-1 nav-profile-icon"></i></button>
                    <?php if (isset($_SESSION['userType']) && in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) { ?>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item my-1 d-flex align-items-center" href="<?= $base_url ?>/admin/profile.php"><i class="far fa-user me-2"></i>Account</a></li>
                            <li><a class="dropdown-item my-1 d-flex align-items-center" href="<?= $base_url ?>/admin/submissions.php"><i class="far fa-file-pdf me-2"></i>Submissions</a></li>
                            <li><a class="dropdown-item my-1 d-flex align-items-center" href="<?= $base_url ?>/admin/bulk-upload.php"><i class="fas fa-cloud-upload-alt me-2"></i>Bulk Upload</a></li>
                            <li><a class="dropdown-item my-1 d-flex align-items-center" href="<?= $base_url ?>/users/library.php"><i class="far fa-file-alt me-2"></i>Library</a></li>
                            <li><a class="dropdown-item my-1 d-flex align-items-center" href="<?= $base_url ?>/admin/backup.php"><i class="far fa-file-alt me-2"></i>Backup & Restore</a></li>
                            <li><a class="dropdown-item my-1 d-flex align-items-center" href="<?= $base_url ?>/admin/system-logs.php"><i class="far fa-clipboard me-2"></i>System Logs</a></li>
                            <?php if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'super_admin') { ?>
                                <li><a class="dropdown-item my-1 d-flex align-items-center" href="<?= $base_url ?>/admin/settings.php"><i class="fas fa-cog me-2"></i>Developer Settings</a></li>
                            <?php } ?>
                            <li><a class="dropdown-item my-1 d-flex align-items-center text-danger" href="<?= $base_url ?>/src/process/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sign out</a></li>
                        </ul>
                    <?php } else { ?>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item my-1 d-flex align-items-center" href="<?= $base_url ?>/users/profile.php"><i class="far fa-user me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item my-1 d-flex align-items-center" href="<?= $base_url ?>/users/library.php"><i class="far fa-bookmark me-2"></i>My Library</a></li>
                            <li><a class="dropdown-item my-1 d-flex align-items-center" href="<?= $base_url ?>/users/my-submissions.php"><i class="far fa-file-alt me-2"></i>My Submissions</a></li>
                            <li><a class="dropdown-item my-1 d-flex align-items-center text-danger" href="<?= $base_url ?>/src/process/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sign out</a></li>
                        </ul>
                    <?php } ?>

                </div>
                <button class="btn d-sm-block d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><span class="navbar-toggler-icon"></span></button>
            </div>
            </div>
            <?php else: ?>
            <div class="d-flex align-items-center ms-auto">
                <a href="<?= $base_url ?>/faqs.php" class="btn btn-outline-light btn-sm me-2"><i class="fas fa-question-circle me-1"></i>FAQs</a>
                <a href="<?= $base_url ?>/index.php" class="btn btn-sm" style="background: #FFDE00; color: #012265; font-weight: 600;"><i class="fas fa-sign-in-alt me-1"></i>Sign In</a>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    // Maintenance notice: shown to non-staff users when enabled in Developer Settings
    $showMaintenanceNotice = false;
    if (file_exists(__DIR__ . '/feature-settings.php')) {
        require_once __DIR__ . '/feature-settings.php';
        $showMaintenanceNotice = function_exists('user_is_staff') && !user_is_staff() && feature_enabled('maintenance_mode');
    }
    if ($showMaintenanceNotice) { ?>
    <div class="alert alert-warning text-center mb-0 rounded-0 fw-bold">
        <i class="fas fa-exclamation-triangle me-2"></i>The system is currently under maintenance. Some features may be unavailable.
    </div>
    <?php } ?>
    <div style="background-color: rgba(255, 222, 0, 1); height:15px"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var dd = document.querySelector('.user-dropdown');
    if (!dd || typeof bootstrap === 'undefined') return;
    var btn = dd.querySelector('.dropdown-toggle');
    var bsDropdown = new bootstrap.Dropdown(btn, { autoClose: false });
    var menu = dd.querySelector('.dropdown-menu');
    var hideTimer = null;

    function showMenu() {
        clearTimeout(hideTimer);
        if (!menu.classList.contains('show')) bsDropdown.show();
    }
    function hideMenu() {
        hideTimer = setTimeout(function() {
            if (menu.classList.contains('show')) bsDropdown.hide();
        }, 200);
    }
    function cancelHide() {
        clearTimeout(hideTimer);
    }

    dd.addEventListener('mouseenter', showMenu);
    dd.addEventListener('mouseleave', hideMenu);
    menu.addEventListener('mouseenter', cancelHide);
    menu.addEventListener('mouseleave', hideMenu);
    btn.addEventListener('click', function() {
        clearTimeout(hideTimer);
    });
});
</script>

<?php if (isset($_SESSION['isLoggedIn'])): ?>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
        <div class="d-flex align-items-center">
            <a href="<?= $base_url ?>/home.php"><img src="<?= $base_url ?>/assets/images/core/saliksik-logo.png" id="header-logo" alt="SALIKSIK: UPHSL Research Repository" class="img-fluid"></a>
        </div>
        <button type="button" class="btn-close btn-close-white text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div style="background-color: rgba(255, 222, 0, 1); height:15px"></div>
    <div class="offcanvas-body">

        <?php
        if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
            echo '<ul class="navbar-nav me-auto mb-2 mb-lg-0 p-1">';
            echo '<li class="nav-item"><h4> ' . $_SESSION["fullName"] . '</strong></h4> </li>';
            echo '
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center offcanvas-link-color" href="<?= $base_url ?>/admin/profile.php"><i class="far fa-user me-2"></i>Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center offcanvas-link-color" href="<?= $base_url ?>/admin/submissions.php"><i class="far fa-file-pdf me-2"></i>Submissions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center offcanvas-link-color" href="<?= $base_url ?>/admin/bulk-upload.php"><i class="fas fa-cloud-upload-alt me-2"></i>Bulk Upload</a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center offcanvas-link-color" href="<?= $base_url ?>/users/library.php"><i class="far fa-file-alt me-2"></i>Library</a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center offcanvas-link-color" href="<?= $base_url ?>/admin/backup.php"><i class="far fa-file-alt me-2"></i>Backup & Restore</a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center offcanvas-link-color" href="<?= $base_url ?>/admin/system-logs.php"><i class="far fa-clipboard me-2"></i>System Logs</a>
            </li>
            ';
            if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'super_admin') {
                echo '
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center offcanvas-link-color" href="<?= $base_url ?>/admin/settings.php"><i class="fas fa-cog me-2"></i>Developer Settings</a>
            </li>
            ';
            }
            echo '
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center offcanvas-signout-link-color" href="../src/process/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sign out</a>
            </li>
            <hr>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/home.php"><i class="fas fa-home me-2"></i>HOME</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/repository.php"><i class="fas fa-book-open me-2"></i>REPOSITORY</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/statistics.php"><i class="fas fa-chart-bar me-2"></i>STATISTICS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/submit.php"><i class="fas fa-paper-plane me-2"></i>SUBMIT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/researchers.php"><i class="fas fa-users me-2"></i>RESEARCHERS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/contact.php"><i class="fas fa-envelope me-2"></i>CONTACT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/about.php"><i class="fas fa-info-circle me-2"></i>ABOUT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/faqs.php"><i class="fas fa-question-circle me-2"></i>FAQs</a>
            </li>

        </ul>';
        } else {
            echo '<ul class="navbar-nav me-auto mb-2 mb-lg-0 p-1">';
            echo '<li class="nav-item"><h4> ' . $_SESSION["fullName"] . '</strong></h4> </li>';
            echo '<li class="nav-item">
                <a class="nav-link d-flex align-items-center offcanvas-link-color" href="<?= $base_url ?>/users/profile.php"><i class="far fa-user me-2"></i>My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center offcanvas-link-color" href="<?= $base_url ?>/users/library.php"><i class="far fa-bookmark me-2"></i>My Library</a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center offcanvas-link-color" href="<?= $base_url ?>/users/my-submissions.php"><i class="far fa-file-alt me-2"></i>My Submissions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center offcanvas-signout-link-color" href="../src/process/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sign out</a>
            </li>
            <hr>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/home.php"><i class="fas fa-home me-2"></i>HOME</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/repository.php"><i class="fas fa-book-open me-2"></i>REPOSITORY</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/statistics.php"><i class="fas fa-chart-bar me-2"></i>STATISTICS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/submit.php"><i class="fas fa-paper-plane me-2"></i>SUBMIT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/researchers.php"><i class="fas fa-users me-2"></i>RESEARCHERS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/contact.php"><i class="fas fa-envelope me-2"></i>CONTACT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/about.php"><i class="fas fa-info-circle me-2"></i>ABOUT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link offcanvas-link-color" href="<?= $base_url ?>/faqs.php"><i class="fas fa-question-circle me-2"></i>FAQs</a>
            </li>

        </ul>';
        }
        ?>
        <hr>
    </div>
</div>
<?php endif; ?>