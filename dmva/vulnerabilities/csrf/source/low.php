<?php
session_start();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Change'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token.");
    }

    $pass_new = $_POST['password_new'];
    $pass_conf = $_POST['password_conf'];

    if ($pass_new !== $pass_conf) {
        echo "<pre>Passwords do not match.</pre>";
        return;
    }

    // Secure password hashing
    $hash = password_hash($pass_new, PASSWORD_ARGON2ID);

    $conn = $GLOBALS["___mysqli_ston"];
    $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE user = ?");
    mysqli_stmt_bind_param($stmt, "ss", $hash, dvwaCurrentUser());
    mysqli_stmt_execute($stmt);

    echo "<pre>Password changed successfully.</pre>";
}
?>

<form method="POST">
    New Password: <input type="password" name="password_new"><br>
    Confirm: <input type="password" name="password_conf"><br>
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="submit" name="Change" value="Change">
</form>