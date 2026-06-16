<?php

// Check if 'ip' exists in the request first to avoid undefined index warnings
if (isset($_REQUEST['ip'])) {
    $target = $_REQUEST['ip'];
    
    // Validate IPv4 only
    if (!filter_var($target, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $html .= "<pre>Invalid IP address.</pre>";
    } else {
        // Execute the ping command based on the OS
        if( stristr( php_uname( 's' ), 'Windows NT' ) ) {
            $cmd = shell_exec( 'ping ' . escapeshellarg($target) );
        } else {
            $cmd = shell_exec( 'ping -c 4 ' . escapeshellarg($target) );
        }
        
        // FIX: Capture and display the command execution result inside DVWA's panel
        $html .= "<pre>{$cmd}</pre>";
    }
}

// Ensure the main template variable is outputted to the screen
echo htmlentities($html);

?>
