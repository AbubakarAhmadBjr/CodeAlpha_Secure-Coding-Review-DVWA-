<?php
$html = '';

// Global DVWA variables
global $vulnerability, $conn;

// Input validation
if (!isset($_REQUEST['id'])) {
    die("Missing ID parameter.");
}
$id = $_REQUEST['id'];
if (!ctype_digit($id)) {
    die("Invalid ID (only numbers allowed).");
}

// Determine database backend
$db_backend = isset($vulnerability) && is_string($vulnerability) && !empty($vulnerability) 
    ? strtolower($vulnerability) 
    : 'mysql';

switch ($db_backend) {
    case 'mysql':
        $db_link = $GLOBALS["___mysqli_ston"] ?? $conn ?? null;
        if (!$db_link) {
            die("Database connection missing. Please access through DVWA sidebar.");
        }

        $stmt = mysqli_prepare($db_link, "SELECT first_name, last_name FROM users WHERE user_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Encode each field to prevent stored XSS
                    $first = htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8');
                    $last  = htmlspecialchars($row['last_name'],  ENT_QUOTES, 'UTF-8');
                    $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
                }
            } else {
                $html .= "<pre>User ID not found in database.</pre>";
            }
            mysqli_stmt_close($stmt);
        } else {
            die("Failed to prepare MySQL statement.");
        }
        break;

    case 'sqlite':
        global $sqlite_db_connection;
        if (!$sqlite_db_connection) {
            die("SQLite database connection missing.");
        }

        $stmt = $sqlite_db_connection->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");
        if ($stmt) {
            $stmt->bindValue(1, $id, SQLITE3_INTEGER);
            $result = $stmt->execute();

            if ($result) {
                $found = false;
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $found = true;
                    $first = htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8');
                    $last  = htmlspecialchars($row['last_name'],  ENT_QUOTES, 'UTF-8');
                    $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
                }
                if (!$found) {
                    $html .= "<pre>User ID not found in database.</pre>";
                }
            } else {
                die("SQLite query error: " . $sqlite_db_connection->lastErrorMsg());
            }
        } else {
            die("Failed to prepare SQLite statement.");
        }
        break;

    default:
        die("Unsupported database backend.");
}

// Final output – no need to encode again because we already encoded each field
echo htmlentities($html);

?>