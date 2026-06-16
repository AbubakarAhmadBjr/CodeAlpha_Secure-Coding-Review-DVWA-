<?php

if( isset( $_POST[ 'Submit' ] ) ) {
	// Get input
	$target = trim($_REQUEST[ 'ip' ]);

	// FIX: Strict IP validation ensuring the input is strictly a valid IPv4 address
	if (!filter_var($target, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
		echo "<pre>Invalid IP address.</pre>";
		return;
	}

	// Determine OS and execute the ping command securely
	if( stristr( php_uname( 's' ), 'Windows NT' ) ) {
		// Windows
		$cmd = shell_exec( 'ping  ' . escapeshellarg($target) );
	}
	else {
		// *nix
		$cmd = shell_exec( 'ping  -c 4 ' . escapeshellarg($target) );
	}

	// Feedback for the end user
	echo htmlentities("<pre>{$cmd}</pre>");

}

?>
