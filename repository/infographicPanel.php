<?php

if (!isset($_SESSION['isLoggedIn'])) {
    header("location: ../index.php?location=" . urlencode($_SERVER['REQUEST_URI']));
    die();
}

$date_time = date_create($fileInfo['infographic_publication_date']);
$date_time = date_format($date_time, "F Y");
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
                            <span class="view-article-type-badge">Infographic</span>
                            <span class="view-article-title"><?php echo htmlspecialchars($fileInfo['infographic_title']); ?></span>
                        </div>
                        <?php if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) { ?>
                            <a target="_blank" class="view-article-edit-link" href="../admin/submissions/view.php?id=<?php echo $_GET['id']; ?>" title="Edit submission">Edit</a>
                        <?php } ?>
                    </div>

                    <div class="view-article-authors mt-2">
                        <?php
                        echo htmlspecialchars($fileInfo['author_first_name'] . " " . $fileInfo['author_surname']);
                        for ($i = 1; $i <= $fileInfo['coauthors_count']; $i++) {
                            echo ", " . htmlspecialchars($fileInfo["coauthor{$i}_first_name"] . " " . $fileInfo["coauthor{$i}_surname"]);
                        }
                        ?>
                    </div>
                    <div class="view-article-date mt-1"><?php echo $date_time; ?></div>

                    <div class="view-article-section-label">Abstract</div>
                    <p class="view-article-text"><?php echo htmlspecialchars($fileInfo['infographic_description']); ?></p>

                    <?php if ($fileInfo['file1_shown'] && !empty($fileInfo['file_dir'])): ?>
                        <div class="view-article-section-label">Attached Files</div>
                        <div class="view-article-files">
                            <a href="../src/<?php echo $fileInfo['file_dir']; ?>" target="_blank" class="view-article-file-btn">
                                <i class="fas fa-file-pdf" style="color:#dc3545"></i>Infographic
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="view-article-metadata">
                        <div class="view-article-meta-row">
                            <span class="view-article-meta-label">Resource Type</span>
                            <span class="view-article-meta-value">Infographic</span>
                        </div>
                        <div class="view-article-meta-row">
                            <span class="view-article-meta-label">Author</span>
                            <span class="view-article-meta-value"><?php echo htmlspecialchars($fileInfo['author_first_name'] . " " . $fileInfo['author_surname']); ?></span>
                        </div>
                        <?php if (!empty($fileInfo['coauthor1_first_name'])): ?>
                            <div class="view-article-meta-row">
                                <span class="view-article-meta-label">Contributors</span>
                                <span class="view-article-meta-value">
                                    <?php
                                    $coauthors = [];
                                    for ($i = 1; $i <= 4; $i++) {
                                        if (!empty($fileInfo["coauthor{$i}_first_name"])) {
                                            $coauthors[] = htmlspecialchars($fileInfo["coauthor{$i}_first_name"] . " " . $fileInfo["coauthor{$i}_surname"]);
                                        }
                                    }
                                    echo implode(', ', $coauthors);
                                    ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <div class="view-article-meta-row">
                            <span class="view-article-meta-label">Graphics Editor</span>
                            <span class="view-article-meta-value"><?php echo htmlspecialchars($fileInfo['editor_first_name'] . " " . $fileInfo['editor_surname']); ?></span>
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
                            <span class="view-article-sidebar-detail-label">Date</span>
                            <span class="view-article-sidebar-detail-value"><?php echo $date_time; ?></span>
                        </div>
                        <div class="view-article-sidebar-detail">
                            <span class="view-article-sidebar-detail-label">Author</span>
                            <span class="view-article-sidebar-detail-value"><?php echo htmlspecialchars($fileInfo['author_first_name'] . " " . $fileInfo['author_surname']); ?></span>
                        </div>
                        <div class="view-article-sidebar-detail">
                            <span class="view-article-sidebar-detail-label">Editor</span>
                            <span class="view-article-sidebar-detail-value"><?php echo htmlspecialchars($fileInfo['editor_first_name'] . " " . $fileInfo['editor_surname']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
