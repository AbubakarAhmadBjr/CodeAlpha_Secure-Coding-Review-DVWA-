<?php

if( isset( $_POST[ 'Upload' ] ) ) {
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
    $allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2 MB

    $file = $_FILES['uploaded'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $mime = mime_content_type($file['tmp_name']);

    if (!in_array($ext, $allowed_ext) || !in_array($mime, $allowed_mime)) {
        $html .= "<pre>Invalid file type (only images allowed).</pre>";
    } elseif ($file['size'] > $max_size) {
        $html .= "<pre>File too large (max 2MB).</pre>";
    } else {
        $new_name = bin2hex(random_bytes(16)) . '.' . $ext;
        $target_path = DVWA_WEB_PAGE_TO_ROOT . "hackable/uploads/" . $new_name;
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            $html .= "<pre>{$target_path} successfully uploaded!</pre>";
        } else {
            $html .= '<pre>Your image was not uploaded.</pre>';
        }
    }
}
?>
