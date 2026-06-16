<?php

if( isset( $_POST[ 'Submit' ] ) ) {
    $id = $_POST[ 'id' ];
    
    // 1. Strict Input Validation to block malicious strings completely
    if (!ctype_digit($id)) {
        echo "<pre>Invalid ID</pre>";
        return;
    }
    
    global $vulnerability;
    $db_backend = isset($vulnerability) && is_string($vulnerability) ? strtolower($vulnerability) : 'mysql';

    // 2. Added the missing switch statement to handle both database engines cleanly
    switch ($db_backend) {
        case 'mysql':
            $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], "SELECT first_name, last_name FROM users WHERE user_id = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                while( $row = mysqli_fetch_assoc( $result ) ) {
                    // Display values
                    $first = $row["first_name"];
                    $last  = $row["last_name"];

                    // Feedback for end user
                    $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
                }
                mysqli_stmt_close($stmt);
            }
            break;

        case 'sqlite':
            global $sqlite_db_connection;

            // FIXED: Rewrote the raw query into a secure SQLite prepared statement
            $query = "SELECT first_name, last_name FROM users WHERE user_id = ?";
            $stmt = $sqlite_db_connection->prepare($query);
            
            if ($stmt) {
                $stmt->bindValue(1, $id, SQLITE3_INTEGER);
                $results = $stmt->execute();

                if ($results) {
                    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                        // Get values
                        $first = $row["first_name"];
                        $last  = $row["last_name"];

                        // Feedback for end user
                        $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
                    }
                } else {
                    echo "Error in fetch " . $sqlite_db_connection->lastErrorMsg();
                }
            }
            break;
    }
}

// Global cleanup actions required by DVWA core script mechanics
$query  = "SELECT COUNT(*) FROM users;";
$result = mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );
$number_of_rows = mysqli_fetch_row( $result )[0];

mysqli_close($GLOBALS["___mysqli_ston"]);
?>
