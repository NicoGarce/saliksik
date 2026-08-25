<?php

session_start();

if (isset($_SESSION['userType'])) {
    if (!in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
        header("location: ../error.php");
        die();
    }
} else {
    header("location: ../error.php");
    die();
}

include '../includes/connection.php';

$sql = "SELECT * FROM login_history ORDER BY login_id DESC";
$result = $connection->query($sql);

$maincssVersion = filemtime('../styles/custom/main-style.css');
$pagecssVersion = filemtime('../styles/custom/pages/profile-style.css');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs</title>
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
            <h1>System Logs</h1>
            <p class="masthead-subtitle">View login history and system activity</p>
        </div>
    </section>

    <section class="py-4">
        <div class="container px-3">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <div class="admin-panel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="admin-panel-title mb-0"><i class="fas fa-history me-1"></i> Login History</div>
                            <span class="admin-badge" id="logsCount"><?php echo $result->num_rows; ?> total</span>
                        </div>
                        <div class="mb-3">
                            <div class="admin-search-group">
                                <input type="text" id="logsSearchInput" placeholder="Search by name, email, role, IP, date...">
                                <button type="button" id="logsSearchBtn"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="admin-table" id="logsTable">
                                <thead>
                                    <tr>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Email Address</th>
                                        <th>User Type</th>
                                        <th>IP Address</th>
                                        <th>Login Time</th>
                                        <th>Login Date</th>
                                        <th>Logout Time</th>
                                        <th>Logout Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row["last_name"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["first_name"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["email_address"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["user_type"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["ip_address"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["login_time"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["login_date"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["logout_time"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["logout_date"]) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo '<tr><td colspan="9" class="text-center text-muted py-4"><i class="fas fa-inbox d-block mb-2 opacity-50"></i>No records saved yet!</td></tr>';
                                    }
                                    $connection->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="logsNoResults" class="text-center py-4" style="display:none;">
                            <i class="fas fa-search fa-2x mb-2" style="color:var(--muted);opacity:.4;"></i>
                            <p class="text-muted mb-0">No logs match your search.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include_once '../includes/footer.php' ?>
    <script src="../scripts/bootstrap/bootstrap.js"></script>

    <script>
    $(document).ready(function() {
        var $rows = $("#logsTable tbody tr");
        var totalInitial = $rows.length;

        function escapeRegex(s) {
            return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        function highlightText(text, query) {
            if (!query) return text;
            var escaped = escapeRegex(query);
            return text.replace(new RegExp('(' + escaped + ')', 'gi'), '<mark class="search-highlight">$1</mark>');
        }

        function filterLogs() {
            var val = $.trim($("#logsSearchInput").val()).toLowerCase();
            var visible = 0;
            $rows.each(function() {
                var $tds = $(this).find('td');
                var originalTexts = [];
                $tds.each(function() { originalTexts.push($(this).text()); });

                if (!val) {
                    $tds.each(function(i) { $(this).html(originalTexts[i]); });
                    $(this).show();
                    visible++;
                    return;
                }

                var combined = originalTexts.join(' ').toLowerCase();
                if (combined.indexOf(val) !== -1) {
                    $tds.each(function(i) {
                        $(this).html(highlightText(originalTexts[i], val));
                    });
                    $(this).show();
                    visible++;
                } else {
                    $(this).hide();
                }
            });
            $("#logsNoResults").toggle(visible === 0);
            $("#logsCount").text(visible + ' of ' + totalInitial);
        }

        $("#logsSearchInput").on("input", filterLogs);
        $("#logsSearchBtn").on("click", filterLogs);
    });
    </script>

</body>

</html>
