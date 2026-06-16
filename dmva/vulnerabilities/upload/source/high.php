<?php

if( isset( $_POST[ 'Upload' ] ) ) {
	// Where are we going to be writing to?
	$target_path  = DVWA_WEB_PAGE_TO_ROOT . "hackable/uploads/";

	// File information
	$uploaded_name = $_FILES[ 'uploaded' ][ 'name' ];
	$uploaded_size = $_FILES[ 'uploaded' ][ 'size' ];

	// 1. FIX: Verify the file is an actual image using getimagesize()
	// 2. Keep size constraint: Ensure the file size is under 100,000 bytes (approx. 100KB)
	if( ( getimagesize( $_FILES[ 'uploaded' ][ 'tmp_name' ] ) !== false ) && ( $uploaded_size < 100000 ) ) {

		// Construct the final file storage path safely
		$target_path .= basename( $uploaded_name );

		// Can we move the file to the upload folder?
		if( !move_uploaded_file( $_FILES[ 'uploaded' ][ 'tmp_name' ], $target_path ) ) {
			// No
			$html .= '<pre>Your image was not uploaded.</pre>';
		}
		else {
			// Yes!
			$html .= "<pre>{$target_path} succesfully uploaded!</pre>";
		}
	}
	else {
		// Invalid file
		$html .= '<pre>Your image was not uploaded. We can only accept valid JPEG or PNG images under 100KB.</pre>';
	}
}

?>
