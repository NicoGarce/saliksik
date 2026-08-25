<?php if (isset($_SESSION['isLoggedIn'])): ?>
<!--Footer-->

<section class="footer px-5 py-3 mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 my-1">
                <a href="<?= $base_url ?>/home.php"><img src="<?= $base_url ?>/assets/images/core/saliksik-logo.png" id="footer-logo" alt="" class="img-fluid saliksik-logo mb-2"></a>
                <p class="footer-column-title fw-bold">University of Perpetual Help System Laguna</p>
                <ul class="footer-list">
                    <li><a class="footer-list-link">UPH Compound, National Highway,</a></li>
                    <li><a class="footer-list-link">Sto. Niño, City of Biñan, Laguna</a></li>
                </ul>
            </div>
            <div class="col-lg-4 my-1">
                <p class="footer-column-title">External Links</p>
                <ul class="footer-list">
                    <li><a class="footer-list-link ext-link" href="https://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm" target="_blank">School Automate</a></li>
                    <li><a class="footer-list-link ext-link" href="https://lmsbinan.uphsl.edu.ph/" target="_blank">Moodle for College/Graduate School</a></li>
                    <li><a class="footer-list-link ext-link" href="https://lmsbed.uphsl.edu.ph/" target="_blank">Moodle for SHS/Basic Education</a></li>
                    <li><a class="footer-list-link ext-link" href="https://uphsl.edu.ph" target="_blank">University Website</a></li>
                </ul>
            </div>
            <div class="col-lg-3 my-1">
                <p class="footer-column-title fw-bold">CONTACT US</p>
                <ul class="footer-list">
                    <li><a class="footer-list-link fw-bold">Research and Development Center</a></li>
                    <li><a class="footer-list-link">Room 247 College Building, UPH Compound</a></li>
                    <li><a class="footer-list-link">research@uphsl.edu.ph</a></li>
                    <li><a class="footer-list-link">049-544-5162</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>



<section class="footer footer-banner pt-3">
    <div class="container">
        <div class="row px-5">
            <div class="col-sm-12 col-md-3">
                <p class="text-white"><a href="../about.php" target="_blank" class="footer-link">Copyright and Disclaimer</a></p>
            </div>
            <div class="col-sm-12 col-md-3">
                <p class="text-white"><a href="../about.php" target="_blank" class="footer-link">Privacy Policy</a></p>
            </div>
            <div class="col-sm-12 col-md-3">
                <p class="text-white"><?php echo  "Copyright &copy; " . date("Y") . " UPHSL" ?></p>
            </div>
            <div class="col-sm-12 col-md-3">
                <p class="text-white fst-italic">Last Updated: <?php echo date("M j, Y, h:i:s"); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Floating Research Menu -->
<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<div class="fab-container">
    <?php if ($currentPage !== 'submit.php') : ?>
    <a href="<?= $base_url ?>/submit.php" class="fab-item fab-action" style="--delay: 0;">
        <i class="fas fa-paper-plane"></i>
        <span>Submit Research</span>
    </a>
    <?php endif; ?>
    <?php if ($currentPage !== 'repository.php') : ?>
    <a href="<?= $base_url ?>/repository.php" class="fab-item fab-action" style="--delay: 1;">
        <i class="fas fa-book-open"></i>
        <span>Browse Repository</span>
    </a>
    <?php endif; ?>
    <button class="fab-main" type="button" aria-label="Quick access">
        <img src="<?= $base_url ?>/android-chrome-192x192.png" alt="SALIKSIK" class="fab-logo">
        <i class="fas fa-times fab-close-icon"></i>
    </button>
</div>
<script>
var fabContainer = document.querySelector('.fab-container');
if (fabContainer) {
    var fabMain = fabContainer.querySelector('.fab-main');
    var fabTimer = null;

    fabMain.addEventListener('mouseenter', function() {
        clearTimeout(fabTimer);
        fabContainer.classList.add('open');
    });
    fabContainer.addEventListener('mouseleave', function() {
        fabTimer = setTimeout(function() {
            fabContainer.classList.remove('open');
        }, 300);
    });
    fabContainer.addEventListener('mouseenter', function() {
        clearTimeout(fabTimer);
    });
}
</script>
<?php endif; ?>