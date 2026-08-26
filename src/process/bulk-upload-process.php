<?php
session_start();

include '../../includes/connection.php';
require_once '../../includes/feature-settings.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userType']) || !in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
    echo json_encode(array('response' => 'error', 'errorText' => 'Access denied'));
    exit();
}

if (!feature_enabled('feature_submissions')) {
    echo json_encode(array('response' => 'error', 'errorText' => 'Submissions feature is disabled'));
    exit();
}

if (!isset($_POST['type'], $_POST['csv_row'], $_FILES['pdf_file'])) {
    echo json_encode(array('response' => 'error', 'errorText' => 'Missing required data'));
    exit();
}

$type = $_POST['type'];
$row = json_decode($_POST['csv_row'], true);
$pdfFile = $_FILES['pdf_file'];

if (!isset($row) || !is_array($row)) {
    echo json_encode(array('response' => 'error', 'errorText' => 'Invalid CSV row data'));
    exit();
}

$validTypes = array('thesis', 'journal', 'infographic', 'report');
if (!in_array($type, $validTypes)) {
    echo json_encode(array('response' => 'error', 'errorText' => 'Invalid submission type'));
    exit();
}

$featureKey = 'feature_submission_' . $type;
if (!feature_enabled($featureKey)) {
    echo json_encode(array('response' => 'error', 'errorText' => ucfirst($type) . ' submissions are disabled'));
    exit();
}

$pdfName = $pdfFile['name'];
$pdfTmp = $pdfFile['tmp_name'];
$pdfSize = $pdfFile['size'];
$pdfError = $pdfFile['error'];

if ($pdfError !== 0) {
    echo json_encode(array('response' => 'error', 'errorText' => 'PDF upload error: ' . $pdfName));
    exit();
}

if ($pdfSize > 11000000) {
    echo json_encode(array('response' => 'error', 'errorText' => 'File too large: ' . $pdfName));
    exit();
}

$pdfExt = strtolower(pathinfo($pdfName, PATHINFO_EXTENSION));
$allowedPdf = array('pdf');
if (!in_array($pdfExt, $allowedPdf)) {
    echo json_encode(array('response' => 'error', 'errorText' => 'Invalid file type: ' . $pdfName));
    exit();
}

$dupCheck = $connection->prepare("SELECT file_id FROM file_information WHERE file_name = ?");
$dupCheck->bind_param("s", $pdfName);
$dupCheck->execute();
$dupResult = $dupCheck->get_result();
$dupCheck->close();
if ($dupResult->num_rows > 0) {
    echo json_encode(array('response' => 'error', 'errorText' => 'Duplicate filename: ' . $pdfName));
    exit();
}

$userId = $_SESSION['userid'];
$userName = $_SESSION['fullName'];
$submitted = date("Y-m-d H:i:s");
$fileStatus = "published";
$publishedOn = $submitted;

$uploadDir = 'uploads/' . ($type === 'thesis' ? 'theses' : ($type === 'journal' ? 'journals' : ($type === 'infographic' ? 'infographics' : 'reports'))) . '/';

$filenameUnique = uniqid('', true);
$newFileName = $filenameUnique . '.' . $pdfExt;
$fileDestination = $uploadDir . $newFileName;

$title = '';
try {
    $connection->begin_transaction();

    if ($type === 'thesis') {
        $title = $row['research_title'] ?? '';

        $coauthorFields = array();
        for ($c = 1; $c <= 4; $c++) {
            $coauthorFields[] = $row["coauthor{$c}_first_name"] ?? '';
            $coauthorFields[] = $row["coauthor{$c}_middle_initial"] ?? '';
            $coauthorFields[] = $row["coauthor{$c}_surname"] ?? '';
            $coauthorFields[] = $row["coauthor{$c}_name_ext"] ?? '';
            $coauthorFields[] = $row["coauthor{$c}_email"] ?? '';
        }

        $stmt = $connection->prepare("INSERT INTO coauthors_information(coauthor1_first_name,coauthor1_middle_initial,coauthor1_surname,coauthor1_name_ext,coauthor1_email,coauthor2_first_name,coauthor2_middle_initial,coauthor2_surname,coauthor2_name_ext,coauthor2_email,coauthor3_first_name,coauthor3_middle_initial,coauthor3_surname,coauthor3_name_ext,coauthor3_email,coauthor4_first_name,coauthor4_middle_initial,coauthor4_surname,coauthor4_name_ext,coauthor4_email) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssssssssssssssssss', ...$coauthorFields);
        $stmt->execute();
        $coauthorGroupId = $stmt->insert_id;
        $stmt->close();

        $stmt = $connection->prepare('INSERT INTO file_information(user_id, file_type, file_name, file_dir, file_uploader, status, coauthor_group_id, submitted_on, published_on) VALUES(?,?,?,?,?,?,?,?,?)');
        $stmt->bind_param('issssssssi', $userId, $type, $pdfName, $fileDestination, $userName, $fileStatus, $coauthorGroupId, $submitted, $publishedOn);
        $stmt->execute();
        $fileId = $stmt->insert_id;
        $stmt->close();

        $pubDate = $row['publication_date'] ?? date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $pubDate)) {
            $pubDate = date('Y-m-d');
        }

        $researchFields = $row['research_fields'] ?? '';
        $researchFields = str_replace(';', ',', $researchFields);

        $stmt = $connection->prepare("INSERT INTO research_information(file_ref_id, resource_type, researchers_category, research_unit, research_course, research_title, research_abstract, research_fields, keywords, publication_date, coauthors_count, author_first_name, author_middle_initial, author_surname, author_name_ext, author_email) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('isssssssssisssss',
            $fileId,
            $row['resource_type'] ?? 'Thesis',
            $row['researchers_category'] ?? 'Undergraduate',
            $row['research_unit'] ?? '',
            $row['research_course'] ?? '',
            $title,
            $row['research_abstract'] ?? '',
            $researchFields,
            $row['keywords'] ?? '',
            $pubDate,
            $row['coauthors_count'] ?? 0,
            $row['author_first_name'] ?? '',
            $row['author_middle_initial'] ?? '',
            $row['author_surname'] ?? '',
            $row['author_name_ext'] ?? '',
            $row['author_email'] ?? ''
        );
        $stmt->execute();
        $stmt->close();

    } elseif ($type === 'journal') {
        $title = $row['journal_title'] ?? '';

        $stmt = $connection->prepare('INSERT INTO file_information(user_id, file_type, file_name, file_dir, file_uploader, status, submitted_on, published_on) VALUES(?,?,?,?,?,?,?,?)');
        $stmt->bind_param('issssssi', $userId, $type, $pdfName, $fileDestination, $userName, $fileStatus, $submitted, $publishedOn);
        $stmt->execute();
        $fileId = $stmt->insert_id;
        $stmt->close();

        $stmt = $connection->prepare("INSERT INTO journal_information(file_ref_id, journal_title, journal_subtitle, department, volume_number, serial_issue_number, ISSN, journal_description, chief_editor_first_name, chief_editor_middle_initial, chief_editor_last_name, chief_editor_name_ext, chief_editor_email) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('isssssssssssss',
            $fileId,
            $title,
            $row['journal_subtitle'] ?? '',
            $row['department'] ?? '',
            $row['volume_number'] ?? '',
            $row['serial_issue_number'] ?? '',
            $row['ISSN'] ?? '',
            $row['journal_description'] ?? '',
            $row['chief_editor_first_name'] ?? '',
            $row['chief_editor_middle_initial'] ?? '',
            $row['chief_editor_last_name'] ?? '',
            $row['chief_editor_name_ext'] ?? '',
            $row['chief_editor_email'] ?? ''
        );
        $stmt->execute();
        $stmt->close();

    } elseif ($type === 'infographic') {
        $title = $row['infographic_title'] ?? '';

        $coauthorFields = array();
        for ($c = 1; $c <= 4; $c++) {
            $coauthorFields[] = $row["coauthor{$c}_first_name"] ?? '';
            $coauthorFields[] = $row["coauthor{$c}_middle_initial"] ?? '';
            $coauthorFields[] = $row["coauthor{$c}_surname"] ?? '';
            $coauthorFields[] = $row["coauthor{$c}_name_ext"] ?? '';
            $coauthorFields[] = $row["coauthor{$c}_email"] ?? '';
        }

        $stmt = $connection->prepare("INSERT INTO coauthors_information(coauthor1_first_name,coauthor1_middle_initial,coauthor1_surname,coauthor1_name_ext,coauthor1_email,coauthor2_first_name,coauthor2_middle_initial,coauthor2_surname,coauthor2_name_ext,coauthor2_email,coauthor3_first_name,coauthor3_middle_initial,coauthor3_surname,coauthor3_name_ext,coauthor3_email,coauthor4_first_name,coauthor4_middle_initial,coauthor4_surname,coauthor4_name_ext,coauthor4_email) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssssssssssssssssss', ...$coauthorFields);
        $stmt->execute();
        $coauthorGroupId = $stmt->insert_id;
        $stmt->close();

        $stmt = $connection->prepare('INSERT INTO file_information(user_id, file_type, file_name, file_dir, file_uploader, status, coauthor_group_id, submitted_on, published_on) VALUES(?,?,?,?,?,?,?,?,?)');
        $stmt->bind_param('issssssssi', $userId, $type, $pdfName, $fileDestination, $userName, $fileStatus, $coauthorGroupId, $submitted, $publishedOn);
        $stmt->execute();
        $fileId = $stmt->insert_id;
        $stmt->close();

        $pubDate = $row['infographic_publication_date'] ?? date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $pubDate)) {
            $pubDate = date('Y-m-d');
        }

        $stmt = $connection->prepare("INSERT INTO infographic_information(file_ref_id, infographic_title, infographic_publication_date, infographic_description, author_first_name, author_middle_initial, author_surname, author_ext, author_email, editor_first_name, editor_middle_initial, editor_surname, editor_ext, editor_email, coauthors_count) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('isssssssssssssi',
            $fileId,
            $title,
            $pubDate,
            $row['infographic_description'] ?? '',
            $row['author_first_name'] ?? '',
            $row['author_middle_initial'] ?? '',
            $row['author_surname'] ?? '',
            $row['author_ext'] ?? '',
            $row['author_email'] ?? '',
            $row['editor_first_name'] ?? '',
            $row['editor_middle_initial'] ?? '',
            $row['editor_surname'] ?? '',
            $row['editor_ext'] ?? '',
            $row['editor_email'] ?? '',
            $row['coauthors_count'] ?? 0
        );
        $stmt->execute();
        $stmt->close();

    } elseif ($type === 'report') {
        $title = $row['report_title'] ?? '';

        $stmt = $connection->prepare('INSERT INTO file_information(user_id, file_type, file_name, file_dir, file_uploader, status, submitted_on, published_on) VALUES(?,?,?,?,?,?,?,?)');
        $stmt->bind_param('issssssi', $userId, $type, $pdfName, $fileDestination, $userName, $fileStatus, $submitted, $publishedOn);
        $stmt->execute();
        $fileId = $stmt->insert_id;
        $stmt->close();

        $reportYear = $row['report_year'] ?? date('Y');
        if (!is_numeric($reportYear)) $reportYear = date('Y');

        $stmt = $connection->prepare("INSERT INTO reports_information(file_ref_id, report_type, report_title, report_year, report_description) VALUES (?,?,?,?,?)");
        $stmt->bind_param('issss',
            $fileId,
            $row['report_type'] ?? 'Annual Report',
            $title,
            $reportYear,
            $row['report_description'] ?? ''
        );
        $stmt->execute();
        $stmt->close();
    }

    $connection->commit();

    $moveDest = realpath(__DIR__ . '/../..') . '/' . $fileDestination;
    move_uploaded_file($pdfTmp, $moveDest);

    echo json_encode(array('response' => 'success', 'title' => $title));

} catch (mysqli_sql_exception $e) {
    $connection->rollback();
    echo json_encode(array('response' => 'error', 'errorText' => $e->getMessage()));
}

exit();
