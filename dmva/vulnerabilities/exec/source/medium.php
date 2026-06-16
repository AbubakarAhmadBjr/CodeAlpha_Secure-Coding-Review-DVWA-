<?php

// Check if 'ip' exists in the request first to avoid undefined index warnings
if (isset($_REQUEST['ip'])) {
    $target = trim($_REQUEST['ip']);

    // 1. Strict Validation: Terminate immediately if input is not a pure IPv4 address
    if (!filter_var($target, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $html .= "<pre>Invalid IP address.</pre>";
    } else {
        // 2. Determine OS and execute the ping command securely using escapeshellarg()
        if (stristr(php_uname('s'), 'Windows NT')) {
            // Windows Execution Environment
            $cmd = shell_exec('ping ' . escapeshellarg($target));
        } else {
            // *nix Execution Environment
            $cmd = shell_exec('ping -c 4 ' . escapeshellarg($target));
        }

        // 3. Capture the command execution result cleanly inside DVWA's panel
        $html .= "<pre>{$cmd}</pre>";
    }
}

// 4. Output the result buffer back to the core template engine wrapper
echo $html;

?>
