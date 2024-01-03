<?php
$oturum_durumu = session_status();

if ($oturum_durumu == PHP_SESSION_NONE) {
    ob_start();
    session_start();
}
include('giris_kontrol.php');
include('Admin_Panel/connect.php');

if (isset($_POST['hasta_kaydet']) && $_POST['hasta_kaydet'] == "hasta_kayit") {
    $hasta_tc = isset($_POST['hasta_tc']) ? $_POST['hasta_tc'] : null;
    $hasta_adi_soyadi = isset($_POST['hasta_adi_soyadi']) ? $_POST['hasta_adi_soyadi'] : null;
    $hasta_password = isset($_POST['hasta_password']) ? md5($_POST['hasta_password']) : null;

    $sorgu = $db->prepare('INSERT INTO hastalar SET
        hasta_tc = ?,
        hasta_adi_soyadi = ?,
        hasta_password = ?');

    $ekle = $sorgu->execute([
        $hasta_tc, $hasta_adi_soyadi, $hasta_password
    ]);
    if ($ekle) {
        header('location: index.php');
    } else {
        $hata = $sorgu->errorInfo();
        echo 'mysql hatası' . $hata[2];
    }
}

if (isset($_POST['giris_yap'])) {
    $hasta_tc = $_POST['hasta_tc'];
    $hasta_password = md5($_POST['hasta_password']);

    $hasta_sor = $db->prepare("SELECT * FROM hastalar WHERE hasta_tc=:hasta_tc and 
    hasta_password=:hasta_password");
    $hasta_sor->execute([
        'hasta_tc' => $hasta_tc,
        'hasta_password' => $hasta_password
    ]);

    $say = $hasta_sor->rowCount();

    if ($say == 1) {
        $_SESSION['userhasta_tc'] = $hasta_tc;
        header('location: anasayfa.php');
        exit;
    } else {
        header('location: index.php');
        exit;
    }
}
