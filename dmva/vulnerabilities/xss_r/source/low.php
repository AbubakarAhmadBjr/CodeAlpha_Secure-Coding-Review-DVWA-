<?php

header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self'");

if (!empty($_GET['name'])) {
    echo '<pre>Hello ' . htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8') . '</pre>';
}

?>
