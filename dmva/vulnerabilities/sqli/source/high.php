<?php

if( isset( $_SESSION [ 'dvwa' ][ 'previous_page' ] ) && $_SESSION[ 'dvwa' ][ 'previous_page' ] == 'include/high.inc.php' ) {
    $id = $_GET[ 'id' ];
    
    // 1. Strict Input Validation to block malicious characters completely
    if (!ctype_digit($id)) {
        echo "<pre>Invalid ID</pre>";
        return;
    }

    // FIX: Declare $html as global so DVWA's parent template engine can display it
    global $vulnerability, $html;
    
    if (!isset($html)) {
        $html = '';
    }

    $db_backend = isset($vulnerability) && is_string($vulnerability) ? strtolower($vulnerability) : 'mysql';

    // 2. Clear switch framework to safely parse DB engine types
    switch ($db_backend) {
        case 'mysql':
            $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], "SELECT first_name, last_name FROM users WHERE user_id = ? LIMIT 1");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    while( $row = mysqli_fetch_assoc( $result ) ) {
                        // Get values
                        $first = $row["first_name"];
                        $last  = $row["last_name"];

                        // Feedback for end user mapped to the global template string
                        $html .= "<pre>ID: " . htmlspecialchars($id) . "<br />First name: " . htmlspecialchars($first) . "<br />Surname: " . htmlspecialchars($last) . "</pre>";
                    }
                } else {
                    $html .= "<pre>User ID not found.</pre>";
                }
                mysqli_stmt_close($stmt);
            }
            
            ((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);     
            break;

        case 'sqlite':
            global $sqlite_db_connection;

            // Secure parameterized SQLite prepared statement
            $query  = "SELECT first_name, last_name FROM users WHERE user_id = ? LIMIT 1;";
            $stmt = $sqlite_db_connection->prepare($query);
            
            if ($stmt) {
                $stmt->bindValue(1, $id, SQLITE3_INTEGER);
                $results = $stmt->execute();

                if ($results) {
                    $has_rows = false;
                    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                        $has_rows = true;
                        // Get values
                        $first = $row["first_name"];
                        $last  = $row["last_name"];

                        // Feedback for end user mapped to the global template string
                        $html .= "<pre>ID: " . htmlspecialchars($id) . "<br />First name: " . htmlspecialchars($first) . "<br />Surname: " . htmlspecialchars($last) . "</pre>";
                    }
                    if (!$has_rows) {
                        $html .= "<pre>User ID not found.</pre>";
                    }
                } else {
                    echo "Error in fetch " . $sqlite_db_connection->lastErrorMsg();
                }
            }
            break;
    }
} else {
    //die("Please access this page through the DVWA sidebar.");
}

?>
