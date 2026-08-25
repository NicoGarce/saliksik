<?php
// Self-hosted FontAwesome 5 stylesheet, loaded in <head> so icons render immediately
$faProjectRoot = realpath(__DIR__ . '/..');
$faDocRoot = realpath($_SERVER['DOCUMENT_ROOT']);
$faBaseUrl = str_replace('\\', '/', str_replace($faDocRoot, '', $faProjectRoot));
$faBaseUrl = $faBaseUrl === '' ? '' : '/' . ltrim($faBaseUrl, '/');
?>
<link rel="stylesheet" href="<?= $faBaseUrl ?>/assets/fontawesome/css/all.min.css">