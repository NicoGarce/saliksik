<?php
session_start();

if (!isset($_SESSION['userType']) || !in_array($_SESSION['userType'] ?? '', array('admin', 'super_admin'))) {
    http_response_code(403);
    exit('Access denied');
}

$type = isset($_GET['type']) ? $_GET['type'] : '';

$templates = array(
    'thesis' => array(
        'resource_type',
        'researchers_category',
        'research_unit',
        'research_course',
        'research_title',
        'research_abstract',
        'research_fields',
        'keywords',
        'publication_date',
        'author_first_name',
        'author_middle_initial',
        'author_surname',
        'author_name_ext',
        'author_email',
        'coauthors_count',
        'coauthor1_first_name',
        'coauthor1_middle_initial',
        'coauthor1_surname',
        'coauthor1_name_ext',
        'coauthor1_email',
        'coauthor2_first_name',
        'coauthor2_middle_initial',
        'coauthor2_surname',
        'coauthor2_name_ext',
        'coauthor2_email',
        'coauthor3_first_name',
        'coauthor3_middle_initial',
        'coauthor3_surname',
        'coauthor3_name_ext',
        'coauthor3_email',
        'coauthor4_first_name',
        'coauthor4_middle_initial',
        'coauthor4_surname',
        'coauthor4_name_ext',
        'coauthor4_email'
    ),
    'journal' => array(
        'journal_title',
        'journal_subtitle',
        'department',
        'volume_number',
        'serial_issue_number',
        'ISSN',
        'chief_editor_first_name',
        'chief_editor_middle_initial',
        'chief_editor_last_name',
        'chief_editor_name_ext',
        'chief_editor_email',
        'journal_description'
    ),
    'infographic' => array(
        'infographic_title',
        'infographic_publication_date',
        'author_first_name',
        'author_middle_initial',
        'author_surname',
        'author_ext',
        'author_email',
        'editor_first_name',
        'editor_middle_initial',
        'editor_surname',
        'editor_ext',
        'editor_email',
        'infographic_description',
        'coauthors_count',
        'coauthor1_first_name',
        'coauthor1_middle_initial',
        'coauthor1_surname',
        'coauthor1_name_ext',
        'coauthor1_email',
        'coauthor2_first_name',
        'coauthor2_middle_initial',
        'coauthor2_surname',
        'coauthor2_name_ext',
        'coauthor2_email',
        'coauthor3_first_name',
        'coauthor3_middle_initial',
        'coauthor3_surname',
        'coauthor3_name_ext',
        'coauthor3_email',
        'coauthor4_first_name',
        'coauthor4_middle_initial',
        'coauthor4_surname',
        'coauthor4_name_ext',
        'coauthor4_email'
    ),
    'report' => array(
        'report_type',
        'report_title',
        'report_year',
        'report_description'
    )
);

$samples = array(
    'thesis' => array(
        'resource_type' => 'Thesis',
        'researchers_category' => 'Undergraduate',
        'research_unit' => 'College of Computer Studies',
        'research_course' => 'BS Information Technology',
        'research_title' => 'Sample Thesis Title',
        'research_abstract' => 'This is a sample abstract...',
        'research_fields' => 'Information Technology',
        'keywords' => 'sample, thesis, IT',
        'publication_date' => '2024-01-15',
        'author_first_name' => 'Juan',
        'author_middle_initial' => 'A',
        'author_surname' => 'Dela Cruz',
        'author_name_ext' => '',
        'author_email' => 'juan@example.com',
        'coauthors_count' => '1',
        'coauthor1_first_name' => 'Maria',
        'coauthor1_middle_initial' => 'B',
        'coauthor1_surname' => 'Santos',
        'coauthor1_name_ext' => '',
        'coauthor1_email' => 'maria@example.com',
        'coauthor2_first_name' => '',
        'coauthor2_middle_initial' => '',
        'coauthor2_surname' => '',
        'coauthor2_name_ext' => '',
        'coauthor2_email' => '',
        'coauthor3_first_name' => '',
        'coauthor3_middle_initial' => '',
        'coauthor3_surname' => '',
        'coauthor3_name_ext' => '',
        'coauthor3_email' => '',
        'coauthor4_first_name' => '',
        'coauthor4_middle_initial' => '',
        'coauthor4_surname' => '',
        'coauthor4_name_ext' => '',
        'coauthor4_email' => ''
    ),
    'journal' => array(
        'journal_title' => 'SALIKSIK Research Journal',
        'journal_subtitle' => 'Volume 1 Issue 1',
        'department' => 'College of Computer Studies',
        'volume_number' => '1',
        'serial_issue_number' => '1',
        'ISSN' => '1234-5678',
        'chief_editor_first_name' => 'John',
        'chief_editor_middle_initial' => 'M',
        'chief_editor_last_name' => 'Reyes',
        'chief_editor_name_ext' => '',
        'chief_editor_email' => 'john@example.com',
        'journal_description' => 'A sample journal description...'
    ),
    'infographic' => array(
        'infographic_title' => 'Sample Infographic Title',
        'infographic_publication_date' => '2024-03-20',
        'author_first_name' => 'Ana',
        'author_middle_initial' => 'C',
        'author_surname' => 'Garcia',
        'author_ext' => '',
        'author_email' => 'ana@example.com',
        'editor_first_name' => 'Pedro',
        'editor_middle_initial' => 'D',
        'editor_surname' => 'Lopez',
        'editor_ext' => '',
        'editor_email' => 'pedro@example.com',
        'infographic_description' => 'A sample infographic description...',
        'coauthors_count' => '0',
        'coauthor1_first_name' => '',
        'coauthor1_middle_initial' => '',
        'coauthor1_surname' => '',
        'coauthor1_name_ext' => '',
        'coauthor1_email' => '',
        'coauthor2_first_name' => '',
        'coauthor2_middle_initial' => '',
        'coauthor2_surname' => '',
        'coauthor2_name_ext' => '',
        'coauthor2_email' => '',
        'coauthor3_first_name' => '',
        'coauthor3_middle_initial' => '',
        'coauthor3_surname' => '',
        'coauthor3_name_ext' => '',
        'coauthor3_email' => '',
        'coauthor4_first_name' => '',
        'coauthor4_middle_initial' => '',
        'coauthor4_surname' => '',
        'coauthor4_name_ext' => '',
        'coauthor4_email' => ''
    ),
    'report' => array(
        'report_type' => 'Annual Report',
        'report_title' => 'Annual Research Report 2024',
        'report_year' => '2024',
        'report_description' => 'A sample report description...'
    )
);

if (!isset($templates[$type])) {
    http_response_code(400);
    exit('Invalid type');
}

$columns = $templates[$type];
$sample = $samples[$type];

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="saliksik-' . $type . '-template.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, $columns);

$row = array();
foreach ($columns as $col) {
    $row[] = isset($sample[$col]) ? $sample[$col] : '';
}
fputcsv($output, $row);

fclose($output);
exit();
