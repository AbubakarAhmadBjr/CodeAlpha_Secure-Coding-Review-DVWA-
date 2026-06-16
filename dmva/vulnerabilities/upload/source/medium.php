<?php

if( isset( $_POST[ 'Upload' ] ) ) {
    // Where are we going to be writing to?
    $target_path  = DVWA_WEB_PAGE_TO_ROOT . "hackable/uploads/";

    // File information
    $uploaded_name = $_FILES[ 'uploaded' ][ 'name' ];
    $uploaded_size = $_FILES[ 'uploaded' ][ 'size' ];
    $uploaded_tmp  = $_FILES[ 'uploaded' ][ 'tmp_name' ];

    // SECURE FIX: Check the real server-side MIME type, ignoring client headers
    $real_mime_type = mime_content_type($uploaded_tmp);

    // Enforce strict file size (under 100KB) and correct image/jpeg MIME type
    if ( $real_mime_type == 'image/jpeg' && $uploaded_size < 100000 ) {
        $target_path .= basename( $uploaded_name );
        
        // Can we move the file to the upload folder?
        if( !move_uploaded_file( $uploaded_tmp, $target_path ) ) {
            // No
            $html .= '<pre>Your image was not uploaded.</pre>';
        }
        else {
            // Yes!
            $html .= "<pre>{$target_path} successfully uploaded!</pre>";
        }
    }
    else {
        // Invalid file
        $html .= '<pre>Your image was not uploaded. We can only accept real JPEG images under 100KB.</pre>';
    }
}

?>
