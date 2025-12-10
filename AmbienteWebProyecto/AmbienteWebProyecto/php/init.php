<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";

function require_login(): void {
    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php");
        exit;
    }
}
