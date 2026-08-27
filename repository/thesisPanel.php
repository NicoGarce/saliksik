<?php

if (!isset($_SESSION['isLoggedIn'])) {
    header("location: ../index.php?location=" . urlencode($_SERVER['REQUEST_URI']));
    die();
}

$date_time = date_create($fileInfo['publication_date']);
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
                            <span class="view-article-type-badge">Thesis</span>
                            <span class="view-article-title"><?php echo htmlspecialchars($fileInfo['research_title']); ?></span>
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
                    <p class="view-article-text"><?php echo htmlspecialchars($fileInfo['research_abstract']); ?></p>

                    <?php if (!empty($fileInfo['keywords'])) : ?>
                        <p class="view-article-keywords">Keywords: <?php echo htmlspecialchars($fileInfo['keywords']); ?></p>
                    <?php endif; ?>

                    <?php
                    if (in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
                        if (!empty($fileInfo['file_dir'])):
                    ?>
                        <div class="view-article-section-label">Attached Files</div>
                        <div class="view-article-files">
                            <?php
                            $fileExt = strtolower(pathinfo($fileInfo['file_dir'], PATHINFO_EXTENSION));
                            $icon = ($fileExt === 'pdf') ? 'fa-file-pdf' : 'fa-file-word';
                            $iconColor = ($fileExt === 'pdf') ? 'color:#dc3545' : 'color:#0d6efd';
                            ?>
                            <a href="../src/<?php echo $fileInfo['file_dir']; ?>" target="_blank" class="view-article-file-btn">
                                <i class="fas <?php echo $icon; ?>" style="<?php echo $iconColor; ?>"></i>Manuscript
                            </a>
                            <?php if (!empty($fileInfo['file_dir2'])):
                                $fileExt2 = strtolower(pathinfo($fileInfo['file_dir2'], PATHINFO_EXTENSION));
                                $icon2 = ($fileExt2 === 'pdf') ? 'fa-file-pdf' : 'fa-file-word';
                                $iconColor2 = ($fileExt2 === 'pdf') ? 'color:#dc3545' : 'color:#0d6efd';
                            ?>
                                <a href="../src/<?php echo $fileInfo['file_dir2']; ?>" target="_blank" class="view-article-file-btn">
                                    <i class="fas <?php echo $icon2; ?>" style="<?php echo $iconColor2; ?>"></i>Survey Questionnaire
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php
                        endif;
                    } else {
                        if ($fileInfo['file1_shown'] || $fileInfo['file2_shown']):
                    ?>
                        <div class="view-article-section-label">Attached Files</div>
                        <div class="view-article-files">
                            <?php if ($fileInfo['file1_shown'] && !empty($fileInfo['file_dir'])):
                                $fileExt = strtolower(pathinfo($fileInfo['file_dir'], PATHINFO_EXTENSION));
                                $icon = ($fileExt === 'pdf') ? 'fa-file-pdf' : 'fa-file-word';
                                $iconColor = ($fileExt === 'pdf') ? 'color:#dc3545' : 'color:#0d6efd';
                            ?>
                                <a href="../src/<?php echo $fileInfo['file_dir']; ?>" target="_blank" class="view-article-file-btn">
                                    <i class="fas <?php echo $icon; ?>" style="<?php echo $iconColor; ?>"></i>Manuscript
                                </a>
                            <?php endif; ?>
                            <?php if ($fileInfo['file2_shown'] && !empty($fileInfo['file_dir2'])):
                                $fileExt2 = strtolower(pathinfo($fileInfo['file_dir2'], PATHINFO_EXTENSION));
                                $icon2 = ($fileExt2 === 'pdf') ? 'fa-file-pdf' : 'fa-file-word';
                                $iconColor2 = ($fileExt2 === 'pdf') ? 'color:#dc3545' : 'color:#0d6efd';
                            ?>
                                <a href="../src/<?php echo $fileInfo['file_dir2']; ?>" target="_blank" class="view-article-file-btn">
                                    <i class="fas <?php echo $icon2; ?>" style="<?php echo $iconColor2; ?>"></i>Survey Questionnaire
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php
                        else:
                    ?>
                        <div class="view-article-section-label">Attached Files</div>
                        <p class="view-article-text" style="font-size:.82rem; color: var(--muted);">
                            To access the full manuscript and/or survey questionnaire, you may send a request through
                            <a href="mailto:research@uphsl.edu.ph" style="color: var(--navy-700); font-weight:600;">research@uphsl.edu.ph</a>
                        </p>
                    <?php
                        endif;
                    }
                    ?>

                    <div class="view-article-metadata">
                        <div class="view-article-meta-row">
                            <span class="view-article-meta-label">Resource Type</span>
                            <span class="view-article-meta-value"><?php echo htmlspecialchars($fileInfo['resource_type']); ?></span>
                        </div>
                        <div class="view-article-meta-row">
                            <span class="view-article-meta-label">Category</span>
                            <span class="view-article-meta-value"><?php echo htmlspecialchars($fileInfo['researchers_category']); ?></span>
                        </div>
                        <div class="view-article-meta-row">
                            <span class="view-article-meta-label">Research Unit</span>
                            <span class="view-article-meta-value"><?php echo htmlspecialchars($fileInfo['research_unit']); ?></span>
                        </div>
                        <?php if (!empty($fileInfo['research_course'])) : ?>
                            <div class="view-article-meta-row">
                                <span class="view-article-meta-label">Course</span>
                                <span class="view-article-meta-value"><?php echo htmlspecialchars($fileInfo['research_course']); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="view-article-meta-row">
                            <span class="view-article-meta-label">Research Field</span>
                            <span class="view-article-meta-value"><?php echo htmlspecialchars($fileInfo['research_fields']); ?></span>
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
                            <span class="view-article-sidebar-detail-label">Category</span>
                            <span class="view-article-sidebar-detail-value"><?php echo htmlspecialchars($fileInfo['researchers_category']); ?></span>
                        </div>
                        <div class="view-article-sidebar-detail">
                            <span class="view-article-sidebar-detail-label">Field</span>
                            <span class="view-article-sidebar-detail-value"><?php echo htmlspecialchars($fileInfo['research_fields']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
