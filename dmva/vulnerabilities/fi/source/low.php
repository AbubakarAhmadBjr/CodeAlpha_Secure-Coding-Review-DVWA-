<?php

// 1. Define the structural strict allowlist of permissible filenames
$allowed_pages = ['home.php', 'about.php', 'contact.php', 'include.php'];

// 2. Fetch parameter safely using null coalescing operator fallback
$file = $_GET['page'] ?? 'home.php';

// 3. Terminate execution instantly if the file name is unrecognized
if (!in_array($file, $allowed_pages, true)) {
    die("Invalid page requested.");
}

// 4. Resolve absolute paths to permanently block directory traversal attacks
$base_dir = realpath(__DIR__ . '/pages/');
$file_path = realpath($base_dir . '/' . $file);

// 5. Enforce deep directory path validation bounds
if ($file_path === false || strpos($file_path, $base_dir) !== 0) {
    die("Access denied.");
}

// 6. Execution point (Safely includes the fully verified canonical file target path)
include($file_path);

?>
