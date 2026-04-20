<?php
require_once __DIR__ . '/../app/Auth.php';

$auth = new Auth();
if ($auth->isLoggedIn()) {
    header("Location: users.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}
