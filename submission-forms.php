<?php

session_start();

include 'includes/connection.php';

require_once 'includes/feature-settings.php';
if (!feature_enabled('feature_submissions') && !user_is_staff()) {
    $disabledFeatureMessage = 'Research submissions are temporarily unavailable. Please check back later.';
    require_once 'includes/feature-disabled.php';
    die();
}

$statement = $connection->prepare("SELECT * FROM department_list");
$statement->execute();
$result = $statement->get_result();
$department_list = $result->fetch_all(MYSQLI_ASSOC);
$statement->close();


if (!isset($_SESSION['isLoggedIn'])) {
    header("location: ../index.php?location=" . urlencode($_SERVER['REQUEST_URI']));
    die();
}

$maincssVersion = filemtime('styles/custom/main-style.css');
$pagecssVersion = filemtime('styles/custom/pages/submission-forms-style.css');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Form</title>
    <?php include_once 'assets/fonts/google-fonts.php' ?>

    <script src="./scripts/jquery/jquery-3.6.0.min.js"></script>
    <script src="./scripts/custom/submission-helpers.js?id=<?php echo filemtime('./scripts/custom/submission-helpers.js') ?>"></script>
    <link rel="stylesheet" href="styles/bootstrap/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/main-style.css?id=' . $maincssVersion ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo 'styles/custom/pages/submission-forms-style.css?id=' . $pagecssVersion ?>" type="text/css">

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

    <!--Header and Navigation section-->

    <?php include_once 'includes/header.php' ?>

    <?php if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))): ?>
    <section class="subform-masthead">
        <div class="container">
            <h1>Submission Forms</h1>
            <p class="masthead-subtitle">Submit research work directly to the UPHSL repository</p>
        </div>
    </section>
    <?php else: ?>
    <section class="subform-masthead">
        <div class="container">
            <h1>Thesis &amp; Dissertation Submission</h1>
            <p class="masthead-subtitle">Submit your capstone, thesis, or dissertation for review</p>
        </div>
    </section>
    <?php endif; ?>

    <!-- Unsaved changes warning modal -->
    <div class="modal fade" id="unsavedModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: var(--radius-lg); border: none; overflow: hidden;">
                <div class="modal-body text-center p-4">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#e65100,#ff8f00);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                        <i class="fas fa-exclamation-triangle" style="color:#fff;font-size:1.3rem;"></i>
                    </div>
                    <h5 style="font-weight:800;color:var(--navy-900);margin-bottom:.5rem;">Unsaved Changes</h5>
                    <p style="color:var(--muted);font-size:.9rem;margin-bottom:0;">Switching tabs will discard any unsaved form details. Do you want to proceed?</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4" style="gap:.5rem;">
                    <button type="button" class="btn btn-sm" style="border:1.5px solid var(--input-border);border-radius:50px;padding:.45rem 1.2rem;font-weight:600;color:var(--navy-700);" data-bs-dismiss="modal">Stay</button>
                    <button type="button" class="btn btn-sm" id="proceedTabBtn" style="background:linear-gradient(135deg,#c62828,#e53935);color:#fff;border:none;border-radius:50px;padding:.45rem 1.2rem;font-weight:600;">Proceed</button>
                </div>
            </div>
        </div>
    </div>

    <section class="py-4">
        <div class="container-fluid px-4 px-lg-5">

                    <?php if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))): ?>
                    <div class="subform-tabs-wrap">
                    <ul class="subform-tabs" role="tablist">
                        <li class="subform-tab active" data-tab="thesis" role="tab">
                            <i class="fas fa-graduation-cap"></i>Thesis & Dissertation
                        </li>
                        <li class="subform-tab" data-tab="journal" role="tab">
                            <i class="fas fa-book"></i>Research Journal
                        </li>
                        <li class="subform-tab" data-tab="infographic" role="tab">
                            <i class="fas fa-image"></i>Infographics
                        </li>
                        <li class="subform-tab" data-tab="reports" role="tab">
                            <i class="fas fa-file-alt"></i>Research Reports
                        </li>
                    </ul>
                    </div>
                    <?php endif; ?>

                    <div id="thesisDissertationPanel-wrap">
                        <?php include_once 'includes/submission-forms/thesisDissertationPanel.php'; ?>
                    </div>

                    <?php if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))): ?>
                    <div id="researchJournalPanel-wrap" hidden>
                        <?php include_once 'includes/submission-forms/researchJournalPanel.php'; ?>
                    </div>
                    <div id="infographicsPanel-wrap" hidden>
                        <?php include_once 'includes/submission-forms/infographicsPanel.php'; ?>
                    </div>
                    <div id="reportsPanel-wrap" hidden>
                        <?php include_once 'includes/submission-forms/reportsPanel.php'; ?>
                    </div>
                    <?php endif; ?>

        </div>
    </section>

    <!--Footer-->

    <?php include_once 'includes/footer.php' ?>

    <script src="scripts/bootstrap/bootstrap.js"></script>
    <script>
    (function(){
        var wraps = {
            thesis: document.getElementById('thesisDissertationPanel-wrap'),
            journal: document.getElementById('researchJournalPanel-wrap'),
            infographic: document.getElementById('infographicsPanel-wrap'),
            reports: document.getElementById('reportsPanel-wrap')
        };
        var innerPanels = {
            thesis: document.getElementById('thesisDissertationPanel'),
            journal: document.getElementById('researchJournalPanel'),
            infographic: document.getElementById('infographicsPanel'),
            reports: document.getElementById('reportsPanel')
        };
        var tabs = document.querySelectorAll('.subform-tab');
        var pendingTab = null;
        var currentTab = 'thesis';
        var unsavedModal = new bootstrap.Modal(document.getElementById('unsavedModal'));
        var proceedBtn = document.getElementById('proceedTabBtn');

        /* snapshot form state at page load */
        var snapshots = {};
        function snapshot(wrap){
            var s = {};
            if(!wrap) return s;
            wrap.querySelectorAll('input[type="text"], input[type="email"], textarea').forEach(function(el, i){
                s['t' + i] = el.value;
            });
            wrap.querySelectorAll('select').forEach(function(el, i){
                s['s' + i] = el.selectedIndex;
            });
            wrap.querySelectorAll('input[type="file"]').forEach(function(el, i){
                s['f' + i] = el.value;
            });
            wrap.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(function(el, i){
                s['c' + i] = el.checked;
            });
            return s;
        }
        Object.keys(wraps).forEach(function(k){ snapshots[k] = snapshot(wraps[k]); });

        function doSwitch(key){
            Object.keys(wraps).forEach(function(k){
                var show = k === key;
                if(wraps[k]) wraps[k].hidden = !show;
                if(innerPanels[k]){
                    if(show) innerPanels[k].removeAttribute('hidden');
                    else innerPanels[k].setAttribute('hidden', '');
                }
            });
            tabs.forEach(function(t){ t.classList.toggle('active', t.dataset.tab === key); });
            currentTab = key;
        }

        function hasFormChanges(){
            var wrap = wraps[currentTab];
            if(!wrap) return false;
            var snap = snapshots[currentTab];
            if(!snap) return false;
            var idx = 0;
            var changed = false;
            wrap.querySelectorAll('input[type="text"], input[type="email"], textarea').forEach(function(el){
                if(snap['t' + idx] !== el.value) changed = true;
                idx++;
            });
            idx = 0;
            wrap.querySelectorAll('select').forEach(function(el){
                if(snap['s' + idx] !== el.selectedIndex) changed = true;
                idx++;
            });
            idx = 0;
            wrap.querySelectorAll('input[type="file"]').forEach(function(el){
                if(snap['f' + idx] !== el.value) changed = true;
                idx++;
            });
            idx = 0;
            wrap.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(function(el){
                if(snap['c' + idx] !== el.checked) changed = true;
                idx++;
            });
            return changed;
        }

        tabs.forEach(function(tab){
            tab.addEventListener('click', function(){
                var target = this.dataset.tab;
                if(target === currentTab) return;
                if(hasFormChanges()){
                    pendingTab = target;
                    unsavedModal.show();
                } else {
                    doSwitch(target);
                }
            });
        });

        proceedBtn.addEventListener('click', function(){
            unsavedModal.hide();
            if(pendingTab){ doSwitch(pendingTab); pendingTab = null; }
        });

        document.getElementById('unsavedModal').addEventListener('hidden.bs.modal', function(){
            pendingTab = null;
        });

        /* co-author panels start hidden */
        $("#co-author-1-td-panel, #co-author-2-td-panel, #co-author-3-td-panel, #co-author-4-td-panel, #co-author-1-info-panel, #co-author-2-info-panel, #co-author-3-info-panel, #co-author-4-info-panel").css('display', 'none');

        $("#checkBoxAgreeThesis").change(function() {
            $("#submitResearchDissertationButton").prop('disabled', !$(this).is(':checked'));
        });
        $("#checkBoxAgreeJournal").change(function() {
            $("#submitJournalButton").prop('disabled', !$(this).is(':checked'));
        });
        $("#checkBoxAgreeInfographics").change(function() {
            $("#submitInfographicsButton").prop('disabled', !$(this).is(':checked'));
        });
        $("#checkBoxAgreeReports").change(function() {
            $("#submitReportsButton").prop('disabled', !$(this).is(':checked'));
        });

        $('#dropdownThesisDissertationCoAuthors').on('change', function() {
            var v = parseInt(this.value);
            for(var i = 1; i <= 4; i++){
                $("#co-author-" + i + "-td-panel").css('display', i <= v ? 'flex' : 'none');
            }
            for(var i = 1; i <= 4; i++){
                $("#textFieldFirstNameCoAuthor" + i + ", #textFieldLastNameCoAuthor" + i + ", #textFieldEmailAuthor" + i).prop('required', i <= v);
            }
        });

        $('#dropdownInfographicsCoAuthors').on('change', function() {
            var v = parseInt(this.value);
            for(var i = 1; i <= 4; i++){
                $("#co-author-" + i + "-info-panel").css('display', i <= v ? 'flex' : 'none');
            }
        });
    })();
    </script>
</body>

</html>