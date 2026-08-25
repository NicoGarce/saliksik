<?php

// Returns the URL prefix under which this app is deployed,
// e.g. '' when served from the web root, '/saliksik' when served
// from http://localhost/saliksik/. Works on any host/subfolder.

function base_url(): string
{
    static $baseUrlCache = null;

    if ($baseUrlCache !== null) {
        return $baseUrlCache;
    }

    $projectRoot = realpath(__DIR__ . '/..');
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;

    if ($projectRoot === false || $docRoot === false) {
        $baseUrlCache = '';
        return $baseUrlCache;
    }

    $relative = str_replace('\\', '/', str_replace($docRoot, '', $projectRoot));
    $baseUrlCache = $relative === '' ? '' : '/' . ltrim($relative, '/');
    return $baseUrlCache;
}
