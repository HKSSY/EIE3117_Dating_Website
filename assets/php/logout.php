<?php
include('config.php');
session_start();
session_destroy();
if (isset($_COOKIE['rememberme_user_id'], $_COOKIE['rememberme_user_password'])) {
    $days = 7;
    setcookie("rememberme_user_id", "", time()-($days * 24 * 60 * 60 * 1000), '/');
    setcookie("rememberme_user_password", "", time()-($days * 24 * 60 * 60 * 1000), '/');
}
// Redirect to the login page:
header('Location: ../../index.php');
?>