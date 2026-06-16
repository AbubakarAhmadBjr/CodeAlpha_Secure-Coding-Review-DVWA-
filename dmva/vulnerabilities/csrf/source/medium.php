<?php

// 1. Generate CSRF token in the user's session if it does not already exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 2. Process password changes strictly via secure POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Change'])) {
    
    // Validate CSRF token using timing-attack resistant comparison
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $html .= "<pre>CSRF token validation failed.</pre>";
    } else {
        $pass_new  = $_POST['password_new'];
        $pass_conf = $_POST['password_conf'];

        // Verify passwords are populated and match exactly
        if (empty($pass_new) || $pass_new !== $pass_conf) {
            $html .= "<pre>Passwords do not match or are empty.</pre>";
        } else {
            // Upgrade legacy MD5 to modern, cryptographically strong Argon2id hashing
            $hash = password_hash($pass_new, PASSWORD_ARGON2ID, [
                'memory_cost' => 65536, 
                'time_cost'   => 4, 
                'threads'     => 3
            ]);

            // Use parameterized secure prepared statement to push update
            $conn = $GLOBALS["___mysqli_ston"];
            $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE user = ?");
            
            if ($stmt) {
                $current_user = dvwaCurrentUser();
                mysqli_stmt_bind_param($stmt, "ss", $hash, $current_user);
                mysqli_stmt_execute($stmt);
                
                $html .= "<pre>Password changed successfully.</pre>";
                mysqli_stmt_close($stmt);
            } else {
                $html .= "<pre>Database setup compilation error.</pre>";
            }
        }
    }

    // Clean up current active connection thread
    if (isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) {
        mysqli_close($GLOBALS["___mysqli_ston"]);
    }
}

?>
