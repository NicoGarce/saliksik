<?php

if (!isset($_SESSION['isLoggedIn'])) {
    header("location: ../index.php?location=" . urlencode($_SERVER['REQUEST_URI']));
    die();
}

?>

<section class="view-article-section">
    <div class="container py-4">
        <div class="row mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="../repository.php" class="text-decoration-none" style="color: var(--navy-700);">Repository</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View Article</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-lg-9">
                <div class="view-article-card">
                    <div class="view-article-accent-bar"></div>

                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <span class="view-article-type-badge"><?php echo htmlspecialchars($fileInfo['report_type']); ?></span>
                            <span class="view-article-title"><?php echo htmlspecialchars($fileInfo['report_title']); ?></span>
                        </div>
                        <?php if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) { ?>
                            <a target="_blank" class="view-article-edit-link" href="../admin/submissions/view.php?id=<?php echo $_GET['id']; ?>" title="Edit submission">Edit</a>
                        <?php } ?>
                    </div>

                    <div class="view-article-date mt-2"><?php echo htmlspecialchars($fileInfo['report_year']); ?></div>

                    <?php if (!empty($fileInfo['file_dir2'])): ?>
                        <div class="text-center mt-3 d-lg-none">
                            <img src="../src/<?php echo $fileInfo['file_dir2']; ?>" alt="Report Cover" class="view-article-cover-img">
                        </div>
                    <?php endif; ?>

                    <div class="view-article-section-label">Description</div>
                    <p class="view-article-text"><?php echo htmlspecialchars($fileInfo['report_description']); ?></p>

                    <?php if ($fileInfo['file1_shown'] || $fileInfo['file2_shown']): ?>
                        <div class="view-article-section-label">Attached Files</div>
                        <div class="view-article-files">
                            <?php if ($fileInfo['file1_shown'] && !empty($fileInfo['file_dir'])): ?>
                                <a href="../src/<?php echo $fileInfo['file_dir']; ?>" target="_blank" class="view-article-file-btn">
                                    <i class="fas fa-file-pdf" style="color:#dc3545"></i><?php echo htmlspecialchars($fileInfo['report_type']); ?>
                                </a>
                            <?php endif; ?>
                            <?php if ($fileInfo['file2_shown'] && !empty($fileInfo['file_dir2'])): ?>
                                <a href="../src/<?php echo $fileInfo['file_dir2']; ?>" target="_blank" class="view-article-file-btn">
                                    <i class="fas fa-image" style="color: var(--navy-700);"></i>Cover Image
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="view-article-metadata">
                        <div class="view-article-meta-row">
                            <span class="view-article-meta-label">Report Type</span>
                            <span class="view-article-meta-value"><?php echo htmlspecialchars($fileInfo['report_type']); ?></span>
                        </div>
                        <div class="view-article-meta-row">
                            <span class="view-article-meta-label">Year</span>
                            <span class="view-article-meta-value"><?php echo htmlspecialchars($fileInfo['report_year']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="view-article-sidebar">
                    <div class="view-article-sidebar-views">
                        <span class="view-article-sidebar-views-count"><?php echo $article_visits['hits']; ?></span>
                        <span class="view-article-sidebar-views-label"><?php echo $article_visits['hits'] == 1 ? 'view' : 'views'; ?></span>
                    </div>

                    <div class="view-article-sidebar-divider"></div>

                    <div class="view-article-sidebar-bookmark" data-id="<?php echo $fileInfo['file_id']; ?>">
                        <?php if (in_array($fileInfo['file_id'], array_column($bookmarks, 'ref_id'))) { ?>
                            <i class="fas fa-bookmark"></i> Saved
                        <?php } else { ?>
                            <i class="far fa-bookmark"></i> Bookmark
                        <?php } ?>
                    </div>

                    <div class="view-article-sidebar-divider"></div>

                    <div class="view-article-sidebar-details">
                        <div class="view-article-sidebar-detail">
                            <span class="view-article-sidebar-detail-label">Year</span>
                            <span class="view-article-sidebar-detail-value"><?php echo htmlspecialchars($fileInfo['report_year']); ?></span>
                        </div>
                        <div class="view-article-sidebar-detail">
                            <span class="view-article-sidebar-detail-label">Type</span>
                            <span class="view-article-sidebar-detail-value"><?php echo htmlspecialchars($fileInfo['report_type']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
