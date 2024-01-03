<?php
header('Content-Type: text/html; charset=utf-8');

$oturum_durumu = session_status();

if ($oturum_durumu == PHP_SESSION_NONE) {
    ob_start();
    session_start();
}

if ($_SESSION['adminUsername'] == '' || !isset($_SESSION['adminUsername'])) {
    header('location: admin_login.php');
}
