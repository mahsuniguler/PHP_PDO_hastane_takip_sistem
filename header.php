<?php
$oturum_durumu = session_status();

if ($oturum_durumu == PHP_SESSION_NONE) {
    ob_start();
    session_start();
}
include('giris_kontrol.php');

include 'Admin_Panel/connect.php';

$hasta_sor = $db->prepare("SELECT * FROM hastalar WHERE hasta_tc=:hasta_tc");
$hasta_sor->execute([
    'hasta_tc' => $_SESSION['userhasta_tc']
]);
$say = $hasta_sor->rowCount();
$hasta_veri = $hasta_sor->fetch(PDO::FETCH_ASSOC);

if ($say == 0) {
    header("location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./bootstrap_5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Hastane Otomasyonu</title>
    <style>
        .custom-hover:hover {
            background-color: #dc3545;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg  m-0" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="anasayfa.php">
                <h1>Hastane Otomasyonu</h1>
            </a>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav ms-auto mb-lg-0">
                    <li class="my-auto nav-item">
                        <a class="nav-link active" aria-current="page" href="hesap.php">
                            <h5>Hesap Bilgileri</h5>
                        </a>
                    </li>
                    <li class="my-auto nav-item ms-5 me-5">
                        <a class="nav-link" href="randevu.php">
                            <h5>Randevu Bilgileri</h5>
                        </a>
                    </li>
                    <li class="nav-item custom-hover">
                        <a class="nav-link" href="logout.php">
                            <div class="">
                                <h4>Çıkış Yap</h4>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
