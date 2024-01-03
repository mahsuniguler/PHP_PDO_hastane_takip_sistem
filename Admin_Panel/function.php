<?php
include('connect.php');
include('giris_kontrol.php');


if (isset($_POST['adminLogin'])) {
    $adminUsername = $_POST['adminUsername'];
    $adminPassword = md5($_POST['adminPass']);
    $AdminDogrulama = $db->prepare("SELECT * FROM admin WHERE user_name=:adminUsername and 
    password=:adminPassword");
    $AdminDogrulama->execute([
        'adminUsername' => $adminUsername,
        'adminPassword' => $adminPassword,
    ]);
    $adminsayisi = $AdminDogrulama->rowCount();

    if ($adminsayisi > 0) {
        $_SESSION['adminUsername'] = $adminUsername;
        header('location: index.php');
        exit;
    } else {
        header('location: admin_login.php');
        exit;
    }
}

if (isset($_POST['hastane_ekle'])) {
    $iller = isset($_POST['iller']) ? $_POST['iller'] : null;
    $ilceler = isset($_POST['ilceler']) ? $_POST['ilceler'] : null;
    $hastane_adi = isset($_POST['hastane_adi']) ? $_POST['hastane_adi'] : null;
    $hastane_tel = isset($_POST['hastane_tel']) ? $_POST['hastane_tel'] : null;
    if ($iller != "" && $ilceler != "" && $hastane_adi != "" && $hastane_tel != "") {
        $sorgu = $db->prepare('INSERT INTO hastane SET il_adi = ?, ilce_adi = ?, hastane_adi = ?, telefon = ?');

        $ekle = $sorgu->execute([$iller, $ilceler, $hastane_adi, $hastane_tel]);

        if ($ekle) {
            header('location: hastane.php');
        }
    } else {
        echo "<script>alert('Boş Bırakmayınız.');</script>";
    }
}

if (isset($_POST['hastane_sil'])) {
    $hastane_sil_id = $_POST['hastane_sil'];
    $silinecek_komut = $db->prepare("DELETE FROM hastane WHERE hastane_id = ?");
    $silinenler = $silinecek_komut->execute([$hastane_sil_id]);
    if ($silinenler) {
        echo '<div class="alert alert-success text-center" role="alert" >
                <strong>SİLME İŞLEMİ BAŞARILI ANASAYFAYA YÖNLENDİRİLİYORSUNUZ</strong></div>';
        header('refresh:2; hastane.php');
    } else {
        echo "<script>alert('Silme işlemi başarısız!')</script>";
    }
    exit;
}



if (isset($_POST['doktor_sil'])) {
    $doktor_sil_id = $_POST['doktor_sil'];
    $silinecek_komut = $db->prepare("DELETE FROM doktorlar WHERE doktor_id = ?");
    $silinenler = $silinecek_komut->execute([$doktor_sil_id]);
    if ($silinenler) {
        echo '<div class="alert alert-success text-center" role="alert" >
                <strong>SİLME İŞLEMİ BAŞARILI ANASAYFAYA YÖNLENDİRİLİYORSUNUZ</strong></div>';
        header('refresh:2; doktor.php');
    } else {
        echo "<script>alert('Silme işlemi başarısız!')</script>";
    }
    exit;
}


if (isset($_POST['hasta_sil'])) {
    $hasta_sil_id = $_POST['hasta_sil'];
    $silinecek_komut = $db->prepare("DELETE FROM hastalar WHERE hasta_id = ?");
    $silinenler = $silinecek_komut->execute([$hasta_sil_id]);
    if ($silinenler) {
        echo '<div class="alert alert-success text-center" role="alert" >
                <strong>SİLME İŞLEMİ BAŞARILI ANASAYFAYA YÖNLENDİRİLİYORSUNUZ</strong></div>';
        header('refresh:2; hasta.php');
    } else {
        echo "<script>alert('Silme işlemi başarısız!')</script>";
    }
    exit;
}

if (isset($_POST['doktor_ekle'])) {
    $doktor_adi = $_POST['doktor_adi'];
    $hastane_adi = $_POST['hastane_adi'];
    $hastane_bolum_adi = $_POST['hastane_bolum'];
    $db->prepare("SELECT * FROM hastane");
    if ($doktor_adi != "" &&  $hastane_bolum_adi != "" && $hastane_adi != "") {
        $sql_command = "INSERT INTO doktorlar (doktor_adi_soyadi, hastane_id, hastane_adi, hastane_bolum_id) VALUES (:doktor_adi, (SELECT hastane_id FROM hastane WHERE hastane_adi= :hastane_adi), :hastane_adi, (SELECT hastane_bolum_id FROM hastane_bolumleri WHERE hastane_bolum_adi = :hastane_bolum_adi));";

        $doktor_ekle_sorgu = $db->prepare($sql_command);
        $ekle = $doktor_ekle_sorgu->execute([
            'doktor_adi' => $doktor_adi,
            'hastane_adi' => $hastane_adi,
            'hastane_adii' => $hastane_adi,
            'hastane_bolum_adi' => $hastane_bolum_adi
        ]);

        if ($ekle) {
            echo "<script>alert('Başarıyla Eklendidi')</script>";
            header('location: doktor.php');
        } else {
            echo "<script>alert('Ekleme işlemi başarısız!')</script>";
            header('location: doktor.php');
        }
    } else {
        echo "<script>alert('Boş Bırakmayınız.');</script>";
    }
}

if (isset($_POST['doktor_duzenle_kayit'])) {
    $doktor_id = $_POST['doktor_duzenle_kayit'];
    $doktor_adi = $_POST['doktor_adi'];
    $hastane_adi = $_POST['hastane_adi'];
    $hastane_bolum_adi = $_POST['hastane_bolum'];
    $db->prepare("SELECT * FROM hastane");
    if ($doktor_adi != "" &&  $hastane_bolum_adi != "" && $hastane_adi != "") {
        $sql_command = "UPDATE doktorlar SET doktor_adi_soyadi= :doktor_adi, 
                    hastane_id = (SELECT hastane_id FROM hastane WHERE hastane_adi = :hastane_adi), 
                    hastane_adi = :hastane_adi, hastane_bolum_id = (SELECT hastane_bolum_id FROM hastane_bolumleri 
                    WHERE hastane_bolum_adi = :hastane_bolum_adi) WHERE doktor_id = $doktor_id;";

        $doktor_duzenle_sorgu = $db->prepare($sql_command);
        $duzenle = $doktor_duzenle_sorgu->execute([
            'doktor_adi' => $doktor_adi,
            'hastane_adi' => $hastane_adi,
            'hastane_adii' => $hastane_adi,
            'hastane_bolum_adi' => $hastane_bolum_adi
        ]);

        if ($duzenle) {
            echo "<script>alert('Başarıyla Düzenlendi')</script>";
            header('location: doktor.php');
        } else {
            echo "<script>alert('Düzenleme işlemi başarısız!')</script>";
            header('location: doktor.php');
        }
    } else {
        echo "<script>alert('Boş Bırakmayınız.');</script>";
    }
}

if (isset($_POST['hastane_duzenle_kayit'])) {
    $hastane_id = $_POST['hastane_duzenle_kayit'];
    $iller = isset($_POST['iller']) ? $_POST['iller'] : null;
    $ilceler = isset($_POST['ilceler']) ? $_POST['ilceler'] : null;
    $hastane_adi = isset($_POST['hastane_adi']) ? $_POST['hastane_adi'] : null;
    $hastane_tel = isset($_POST['hastane_tel']) ? $_POST['hastane_tel'] : null;
    if ($iller != "" && $ilceler != "" && $hastane_adi != "" && $hastane_tel != "") {
        $sql_command = "UPDATE hastane SET il_adi = ?, ilce_adi = ?, hastane_adi = ?, telefon = ? WHERE hastane_id = ?;";
        $hastane_duzenle_sorgu = $db->prepare($sql_command);
        $duzenle = $hastane_duzenle_sorgu->execute([$iller, $ilceler, $hastane_adi, $hastane_tel, $hastane_id]);

        if ($duzenle) {
            echo '<div class="alert alert-success text-center" role="alert" >
                <strong>İŞLEM BAŞARILI ANASAYFAYA YÖNLENDİRİLİYORSUNUZ</strong></div>';
            header('refresh:2; hastane.php');
        } else {
            echo '<div class="alert alert-danger text-center" role="alert" >
                <strong>Hatalı!!</strong></div>';
        }
    } else {
        echo '<div class="alert alert-danger text-center" role="alert" >
                <strong>LÜTFEN BOŞ ALANLARI DOLDURUNUZ!</strong></div>';
        header('refresh:2');
    }
}
