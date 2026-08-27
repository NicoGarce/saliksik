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

$usersStmt = $connection->prepare("SELECT user_id, first_name, last_name, email, department, user_type, is_suspended, created_at FROM users ORDER BY created_at DESC");
$usersStmt->execute();
$usersResult = $usersStmt->get_result();
$users = $usersResult->fetch_all(MYSQLI_ASSOC);
$usersStmt->close();

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
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h1>Account Preference</h1>
                    <p class="masthead-subtitle">Manage your account settings and user accounts</p>
                </div>
                <button class="admin-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#createAccountOffcanvas">
                    <i class="fas fa-plus me-1"></i> Create Account
                </button>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="container px-3">

            <!-- Flash messages -->
            <?php foreach (['editAccountSuccess', 'deleteAccountSuccess', 'suspendAccountSuccess'] as $msg): ?>
                <?php if (isset($_SESSION[$msg])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><?php echo htmlspecialchars($_SESSION[$msg]); ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php unset($_SESSION[$msg]); endif; ?>
            <?php endforeach; ?>
            <?php foreach (['editAccountError', 'deleteAccountError', 'suspendAccountError'] as $msg): ?>
                <?php if (isset($_SESSION[$msg])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><?php echo htmlspecialchars($_SESSION[$msg]); ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php unset($_SESSION[$msg]); endif; ?>
            <?php endforeach; ?>

            <div class="row g-4">

                <!-- Change Password -->
                <div class="col-12">
                    <div class="admin-panel">
                        <div class="admin-panel-title"><i class="fas fa-lock me-1"></i> Change Password</div>
                        <form action="../src/process/update-password.php" method="POST">
                            <?php if (isset($_SESSION['changedPassword'])) : ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Password changed successfully!</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php unset($_SESSION['changedPassword']); endif; ?>
                            <?php if (isset($_SESSION['wrongPassword'])) : ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Current password is incorrect.</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php unset($_SESSION['wrongPassword']); endif; ?>
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-3 col-md-6">
                                    <label class="admin-label">First Name</label>
                                    <input class="form-control admin-input" type="text" value="<?php echo htmlspecialchars($_SESSION['firstName']); ?>" disabled>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="admin-label">Last Name</label>
                                    <input class="form-control admin-input" type="text" value="<?php echo htmlspecialchars($_SESSION['lastName']); ?>" disabled>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="admin-label" for="textFieldCurrentPassword">Current Password <span class="text-danger">*</span></label>
                                    <input class="form-control admin-input" type="password" name="textFieldCurrentPassword" id="textFieldCurrentPassword" required>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="admin-label" for="textFieldNewPassword">New Password <span class="text-danger">*</span></label>
                                    <input class="form-control admin-input" type="password" name="textFieldNewPassword" id="textFieldNewPassword" required>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-3">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="checkboxShowHidePasswordAccountPreference">
                                    <label class="form-check-label" for="checkboxShowHidePasswordAccountPreference">Show Password</label>
                                </div>
                                <button type="submit" class="admin-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Users List -->
                <div class="col-12">
                    <div class="admin-panel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="admin-panel-title mb-0"><i class="fas fa-users me-1"></i> Users</div>
                            <span class="admin-badge" id="usersCount"><?php echo count($users); ?> total</span>
                        </div>
                        <div class="mb-3">
                            <div class="admin-search-group">
                                <input type="text" id="usersSearchInput" placeholder="Search by name, email, department, or role...">
                                <button type="button" id="usersSearchBtn"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="admin-table" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $u): ?>
                                    <tr data-userid="<?php echo $u['user_id']; ?>"
                                        data-firstname="<?php echo htmlspecialchars($u['first_name']); ?>"
                                        data-lastname="<?php echo htmlspecialchars($u['last_name']); ?>"
                                        data-email="<?php echo htmlspecialchars($u['email']); ?>">
                                        <td class="fw-semibold"><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                                        <td><?php echo htmlspecialchars($u['department']); ?></td>
                                        <td>
                                            <?php if ($u['user_type'] === 'super_admin'): ?>
                                                <span class="admin-badge admin-badge-super">Super Admin</span>
                                            <?php elseif ($u['user_type'] === 'admin'): ?>
                                                <span class="admin-badge admin-badge-admin">Admin</span>
                                            <?php else: ?>
                                                <span class="admin-badge admin-badge-user">User</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($u['is_suspended']): ?>
                                                <span class="admin-badge admin-badge-suspended">Suspended</span>
                                            <?php else: ?>
                                                <span class="admin-badge admin-badge-active">Active</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted"><?php echo date('M j, Y', strtotime($u['created_at'])); ?></td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-1">
                                                <button type="button" class="btn btn-sm action-btn action-edit"
                                                    data-bs-toggle="offcanvas" data-bs-target="#editAccountOffcanvas"
                                                    title="Edit account">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm action-btn action-suspend"
                                                    title="Toggle suspend">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                                <?php if ($u['user_type'] !== 'super_admin' || $_SESSION['userType'] === 'super_admin'): ?>
                                                <button type="button" class="btn btn-sm action-btn action-delete"
                                                    data-bs-toggle="modal" data-bs-target="#deleteAccountModal"
                                                    title="Delete account">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="usersNoResults" class="text-center py-4" style="display:none;">
                            <i class="fas fa-search fa-2x mb-2" style="color:var(--muted);opacity:.4;"></i>
                            <p class="text-muted mb-0">No users match your search.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Edit Account — offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editAccountOffcanvas" style="width: 420px;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold"><i class="fas fa-user-edit me-1"></i> Edit Account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form action="../src/process/edit-account.php" method="POST" id="editAccountForm">
                <input type="hidden" name="userId" id="editUserId">
                <div class="mb-3">
                    <label class="admin-label" for="editFirstName">First Name <span class="text-danger">*</span></label>
                    <input class="form-control admin-input" type="text" name="textFieldFirstName" id="editFirstName" required>
                </div>
                <div class="mb-3">
                    <label class="admin-label" for="editLastName">Last Name <span class="text-danger">*</span></label>
                    <input class="form-control admin-input" type="text" name="textFieldLastName" id="editLastName" required>
                </div>
                <div class="mb-3">
                    <label class="admin-label" for="editEmail">Email <span class="text-danger">*</span></label>
                    <input class="form-control admin-input" type="email" name="textFieldEmail" id="editEmail" required>
                </div>
                <div class="mb-3">
                    <label class="admin-label" for="editNewPassword">Reset Password <small class="text-muted fw-normal">(leave blank to keep current)</small></label>
                    <input class="form-control admin-input" type="password" name="textFieldNewPassword" id="editNewPassword" placeholder="New password">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="checkboxShowHidePasswordEdit">
                    <label class="form-check-label" for="checkboxShowHidePasswordEdit">Show Password</label>
                </div>
                <button class="admin-btn w-100">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Delete Account — confirmation modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: var(--radius-lg); border: none; overflow: hidden;">
                <div class="modal-body text-center p-4">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#c62828,#e53935);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                        <i class="fas fa-trash-alt" style="color:#fff;font-size:1.2rem;"></i>
                    </div>
                    <h5 style="font-weight:800;color:var(--navy-900);margin-bottom:.5rem;">Delete Account</h5>
                    <p style="color:var(--muted);font-size:.9rem;margin-bottom:0;">Are you sure you want to delete <strong id="deleteUserName"></strong>? This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4" style="gap:.5rem;">
                    <button type="button" class="btn btn-sm" style="border:1.5px solid var(--input-border);border-radius:50px;padding:.45rem 1.2rem;font-weight:600;color:var(--navy-700);" data-bs-dismiss="modal">Cancel</button>
                    <form action="../src/process/delete-account.php" method="POST" class="d-inline">
                        <input type="hidden" name="userId" id="deleteUserId">
                        <button type="submit" class="btn btn-sm" style="background:linear-gradient(135deg,#c62828,#e53935);color:#fff;border:none;border-radius:50px;padding:.45rem 1.2rem;font-weight:600;">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Account — offcanvas sidebar -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="createAccountOffcanvas" aria-labelledby="createAccountLabel" style="width: 420px;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="createAccountLabel"><i class="fas fa-user-plus me-1"></i> Create New Account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <?php if (isset($_SESSION['wrongPasswordAdmin'])) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Passwords do not match.</strong><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; unset($_SESSION['wrongPasswordAdmin']); ?>
            <?php if (isset($_SESSION['invalidEmailAdmin'])) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Invalid Email.</strong><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; unset($_SESSION['invalidEmailAdmin']); ?>
            <?php if (isset($_SESSION['notSchoolEmail'])) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Not School Email.</strong><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; unset($_SESSION['notSchoolEmail']); ?>
            <?php if (isset($_SESSION['emailExistsAdmin'])) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Email already exists.</strong><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; unset($_SESSION['emailExistsAdmin']); ?>
            <?php if (isset($_SESSION['createAccountSuccess'])) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Account created successfully.</strong><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; unset($_SESSION['createAccountSuccess']); ?>
            <?php if (isset($_SESSION['emptyField'])) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Please fill up all the fields.</strong><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; unset($_SESSION['emptyField']); ?>

            <form action="../src/process/create-new-account.php" method="POST">
                <div class="mb-3">
                    <label class="admin-label" for="dropdown-account-type">Account Type <span class="text-danger">*</span></label>
                    <select class="form-select admin-select" id="dropdown-account-type" name="dropdownAccountType">
                        <option value="admin" selected>Administrator</option>
                        <option value="user">Standard User</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="admin-label" for="textFieldFirstName">First Name <span class="text-danger">*</span></label>
                    <input class="form-control admin-input" type="text" placeholder="First name" name="textFieldFirstName" id="textFieldFirstName" required>
                </div>
                <div class="mb-3">
                    <label class="admin-label" for="textFieldLastName">Last Name <span class="text-danger">*</span></label>
                    <input class="form-control admin-input" type="text" placeholder="Last name" name="textFieldLastName" id="textFieldLastName" required>
                </div>
                <div class="mb-3">
                    <label class="admin-label" for="textFieldEmailAdmin">Email <span class="text-danger">*</span></label>
                    <input class="form-control admin-input" type="email" placeholder="Email" name="textFieldEmail" id="textFieldEmailAdmin" required>
                </div>
                <div class="mb-3">
                    <label class="admin-label" for="dropdown-department">Department</label>
                    <select class="form-select admin-select" id="dropdown-department" name="dropdownDepartment">
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
                </div>
                <div class="mb-3">
                    <label class="admin-label" for="textFieldPassword">Password <span class="text-danger">*</span></label>
                    <input class="form-control admin-input" type="password" name="textFieldPassword" id="textFieldPassword" required>
                </div>
                <div class="mb-3">
                    <label class="admin-label" for="textFieldPasswordConfirm">Confirm Password <span class="text-danger">*</span></label>
                    <input class="form-control admin-input" type="password" name="textFieldPasswordConfirm" id="textFieldPasswordConfirm" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="checkboxShowHidePasswordCreateAccount">
                    <label class="form-check-label" for="checkboxShowHidePasswordCreateAccount">Show Password</label>
                </div>
                <button class="admin-btn w-100">Create Account</button>
            </form>
        </div>
    </div>

    <?php include_once '../includes/footer.php' ?>
    <script src="../scripts/bootstrap/bootstrap.js"></script>

    <script>
    $(document).ready(function() {
        /* Show/hide password toggles */
        $("#checkboxShowHidePasswordAccountPreference").change(function() {
            var t = $(this).is(':checked') ? 'text' : 'password';
            $("#textFieldCurrentPassword, #textFieldNewPassword").attr("type", t);
        });
        $("#checkboxShowHidePasswordCreateAccount").change(function() {
            var t = $(this).is(':checked') ? 'text' : 'password';
            $("#textFieldPassword, #textFieldPasswordConfirm").attr("type", t);
        });
        $("#checkboxShowHidePasswordEdit").change(function() {
            $("#editNewPassword").attr("type", $(this).is(':checked') ? 'text' : 'password');
        });

        /* Edit: populate offcanvas from row data */
        $("#editAccountOffcanvas").on("show.bs.offcanvas", function(e) {
            var $row = $(e.relatedTarget).closest('tr');
            $("#editUserId").val($row.data('userid'));
            $("#editFirstName").val($row.data('firstname'));
            $("#editLastName").val($row.data('lastname'));
            $("#editEmail").val($row.data('email'));
            $("#editNewPassword").val('');
        });

        /* Delete: populate modal from row data */
        $("#deleteAccountModal").on("show.bs.modal", function(e) {
            var $row = $(e.relatedTarget).closest('tr');
            $("#deleteUserId").val($row.data('userid'));
            $("#deleteUserName").text($row.data('firstname') + ' ' + $row.data('lastname'));
        });

        /* Suspend: submit inline form */
        $(document).on("click", ".action-suspend", function() {
            var $row = $(this).closest('tr');
            var uid = $row.data('userid');
            if (confirm('Are you sure you want to toggle suspend for this account?')) {
                var $form = $('<form>', {method: 'POST', action: '../src/process/toggle-suspend-account.php'});
                $form.append($('<input>', {type: 'hidden', name: 'userId', value: uid}));
                $('body').append($form);
                $form.submit();
            }
        });

        /* Users table search with highlighting */
        var $rows = $("#usersTable tbody tr");
        var totalInitial = $rows.length;

        function escapeRegex(s) {
            return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }
        function highlightText(text, query) {
            if (!query) return text;
            return text.replace(new RegExp('(' + escapeRegex(query) + ')', 'gi'), '<mark class="search-highlight">$1</mark>');
        }
        function filterUsers() {
            var val = $.trim($("#usersSearchInput").val()).toLowerCase();
            var visible = 0;
            $rows.each(function() {
                var $tds = $(this).find('td:not(:last)');
                var originalTexts = [];
                $tds.each(function() { originalTexts.push($(this).text()); });
                if (!val) {
                    $tds.each(function(i) { $(this).html(originalTexts[i]); });
                    $(this).show(); visible++; return;
                }
                var combined = originalTexts.join(' ').toLowerCase();
                if (combined.indexOf(val) !== -1) {
                    $tds.each(function(i) { $(this).html(highlightText(originalTexts[i], val)); });
                    $(this).show(); visible++;
                } else {
                    $(this).hide();
                }
            });
            $("#usersNoResults").toggle(visible === 0);
            $("#usersCount").text(visible + ' of ' + totalInitial);
        }
        $("#usersSearchInput").on("input", filterUsers);
        $("#usersSearchBtn").on("click", filterUsers);
    });
    </script>

</body>

</html>
