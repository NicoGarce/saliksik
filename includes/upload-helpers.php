<?php

/**
 * Ensure an upload directory exists under the project's upload root
 * (src/uploads). Creates it recursively if missing. Throws on failure
 * so callers can surface a clear error instead of silently losing files.
 *
 * $relativeDir examples: 'uploads/theses', 'uploads/journals',
 * 'uploads/theses/questionnaires'
 */
function ensure_upload_dir(string $relativeDir): string
{
    // Strip a leading '../' if callers pass handler-style destinations.
    $relativeDir = preg_replace('#^(\.\./)+#', '', $relativeDir);

    $base = realpath(__DIR__ . '/../src');
    if ($base === false) {
        throw new RuntimeException('Upload root not found');
    }
    $dir = $base . '/' . $relativeDir;
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException('Could not create upload directory: ' . basename($relativeDir));
    }
    return $dir;
}
