<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_admin_logged_in(): bool {
    return !empty($_SESSION["admin_id"]);
}

function require_admin_login(): void {
    if (!is_admin_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

function admin_logout(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), "", time() - 42000,
            $params["path"], $params["domain"], $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}