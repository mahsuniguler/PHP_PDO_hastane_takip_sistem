<?php
include('header.php');
include('function.php');

if (isset($_POST['tarih']) && $_POST['tarih'] != "") $selected_tarih = $_POST['tarih'];
else $selected_tarih = "";
if (isset($_POST['iller']) && $_POST['iller'] != "") $selected_il = $_POST['iller'];
else $selected_il = "";
if (isset($_POST['ilceler']) && $_POST['ilceler'] != "") $selected_ilce = $_POST['ilceler'];
else $selected_ilce = "";
if (isset($_POST['hastaneler']) && $_POST['hastaneler'] != "") $selected_hastane = $_POST['hastaneler'];
else $selected_hastane = "";
if (isset($_POST['hastane_bolumleri']) && $_POST['hastane_bolumleri'] != "") $selected_hastane_bolum = $_POST['hastane_bolumleri'];
else $selected_hastane_bolum = "";

if (isset($_POST['doktorlar']) && $_POST['doktorlar'] != "") $selected_doktor = $_POST['doktorlar'];
else $selected_doktor = "";

$sql_command = "SELECT * FROM il ORDER BY il_adi";

$il_queryy = $db->query("SELECT DISTINCT(il_adi) FROM hastane ORDER BY il_adi");
while ($il = $il_queryy->fetch(PDO::FETCH_ASSOC)) {
    $iller[] = $il;
}

$queryy = $db->query("SELECT DISTINCT(ilce_adi) FROM hastane WHERE il_adi = '$selected_il' ORDER BY ilce_adi;");
$ilceler = [];
while ($ilce = $queryy->fetch(PDO::FETCH_ASSOC)) {
    $ilceler[] = $ilce;
}

$queryy = $db->query("SELECT hastane_adi FROM hastane WHERE il_adi = '$selected_il' AND ilce_adi = '$selected_ilce' ORDER BY hastane_adi;");
$hastaneler = [];
while ($hastane = $queryy->fetch(PDO::FETCH_ASSOC)) {
    $hastaneler[] = $hastane;
}

$hastane_bolum_queryy = $db->query("SELECT * FROM hastane_bolumleri ORDER BY hastane_bolum_adi;");
$hastane_bolumleri = [];
while ($hastane_bolum = $hastane_bolum_queryy->fetch(PDO::FETCH_ASSOC)) {
    $hastane_bolumleri[] = $hastane_bolum;
}

if ($selected_hastane != "" && $selected_hastane_bolum != "") {
    $sql_command = "SELECT * FROM doktorlar 
        INNER JOIN hastane_bolumleri ON doktorlar.hastane_bolum_id = hastane_bolumleri.hastane_bolum_id
        WHERE doktorlar.hastane_adi = '$selected_hastane' AND hastane_bolumleri.hastane_bolum_adi = '$selected_hastane_bolum'
        ORDER BY doktorlar.doktor_adi_soyadi";
    $doktor_queryy = $db->query($sql_command);
    $doktorlar = [];
    while ($doktor = $doktor_queryy->fetch(PDO::FETCH_ASSOC)) {
        $doktorlar[] = $doktor;
    }
}

header('Content-Type: text/html; charset=UTF-8');

if (isset($_POST['randevu_kaydet'])) {
    $selected_tarih = isset($_POST['tarih']) ? $_POST['tarih'] : "";
    $selected_il = isset($_POST['iller']) ? $_POST['iller'] : "";
    $selected_ilce = isset($_POST['ilceler']) ? $_POST['ilceler'] : "";
    $selected_hastane = isset($_POST['hastaneler']) ? $_POST['hastaneler'] : "";
    $selected_hastane_bolum = isset($_POST['hastane_bolumleri']) ? $_POST['hastane_bolumleri'] : "";
    $selected_doktor = isset($_POST['doktorlar']) ? $_POST['doktorlar'] : "";
    $hasta_id = isset($_POST['hasta_id']) ? $_POST['hasta_id'] : "";
    if ($selected_il != "" && $selected_tarih != "" && $selected_hastane != "" && $selected_doktor != "" && $selected_hastane_bolum != "" && $hasta_id != "") {
        $sql_command = "INSERT INTO randevu (randevu_sehir, randevu_tarih, randevu_hastane, randevu_doktor, randevu_hastane_bolum, randevu_hasta_id) VALUES (?, ?, ?, ?, ?, ?)";
        $kaydet = $db->prepare($sql_command);

        $insert = $kaydet->execute([$selected_il, $selected_tarih, $selected_hastane, $selected_doktor, $selected_hastane_bolum, $hasta_id]);
        $_POST['tarih'] = "";
        $_POST['iller'] = "";
        $_POST['ilceler'] = "";
        $_POST['hastaneler'] = "";
        $_POST['hastane_bolumleri'] = "";
        $_POST['doktorlar'] = "";
        $_POST = [];
        if ($insert) {
            echo '<div class="alert alert-success text-center" role="alert" >
                <strong>RANDEVU EKLEME İŞLEMİ BAŞARILI ANASAYFAYA YÖNLENDİRİLİYORSUNUZ</strong></div>';
            header('refresh:2; randevu.php');
        }
    } else {
        echo '<div class="alert alert-danger text-center" role="alert" >
                <strong>LÜTFEN BOŞ ALANLARI DOLDURUNUZ!</strong></div>';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hastane Otomasyonu</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="bgimage">
    <div class="adsoyad">
        <h4>Sayın <?php echo $hasta_veri['hasta_adi_soyadi']; ?></h4>
    </div>
    <div class="opacity0_5">
        <form method="post" class="form-control w-50 m-auto opacity0_5">
            <label class="form-label" for="date">Tarih: </label>
            <input class="form-control mb-4" type="date" value="<?php echo $selected_tarih; ?>" name="tarih" id="tarih">
            <label class="form-label" for="date">Şehir Seçin: </label>

            <select class="form-control mb-4" name="iller" id="iller" class="hastane" onchange="this.form.submit()">
                <option value="">-- Şehir Seçin --</option>

                <?php
                foreach ($iller as $il) {
                    $selected = ($il['il_adi'] == $selected_il) ? 'selected' : ''; ?>
                    <?php echo '<option value="' . $il['il_adi'] . '" ' . $selected . '>' . $il['il_adi'] . '</option>'; ?>
                <?php } ?>
            </select>
            <label class="form-label" for="date">İlçe Seçin: </label>
            <select class="form-control mb-4" name="ilceler" id="ilceler" onchange="this.form.submit()">
                <option value="">-- İlçe Seçin --</option>
                <?php
                foreach ($ilceler as $ilce) {
                    $selected = ($ilce['ilce_adi'] == $selected_ilce) ? 'selected' : ''; ?>
                    <?php echo '<option value="' . $ilce['ilce_adi'] . '" ' . $selected . '>' . $ilce['ilce_adi'] . '</option>'; ?>
                <?php } ?>
            </select>
            <label class="form-label" for="date">Hastane Seçin: </label>
            <select class="form-control mb-4" name="hastaneler" class="hastane" id="hastaneler" onchange="this.form.submit()">
                <option value="">-- Hastane Seçin --</option>
                <?php
                foreach ($hastaneler as $hastane) {
                    $selected = ($hastane['hastane_adi'] == $selected_hastane) ? 'selected' : '';
                    echo '<option value="' . $hastane['hastane_adi'] . '" ' . $selected . '>' . $hastane['hastane_adi'] . '</option>'; ?>
                <?php } ?>
            </select>
            <label class="form-label" for="date">Bölüm Seçin: </label>
            <select class="form-control mb-4" name="hastane_bolumleri" class="hastane_bolum" id="hastane_bolumleri" onchange="this.form.submit()">
                <option value="">-- Bölüm Seçin --</option>
                <?php
                foreach ($hastane_bolumleri as $hastane_bolum) {
                    $selected = ($hastane_bolum['hastane_bolum_adi'] == $selected_hastane_bolum) ? 'selected' : '';
                    echo '<option value"' . $hastane_bolum['hastane_bolum_adi'] . '" ' . $selected . '>' . $hastane_bolum['hastane_bolum_adi'] . '</option>'; ?>

                <?php } ?>

            </select>
            <label class="form-label" for="doktorlar">Doktor Seçin: </label>
            <select class="form-control mb-4" name="doktorlar" class="doktor" id="doktorlar" onchange="this.form.submit()">
                <option value="">-- Doktor Seçin --</option>
                <?php
                if ($selected_hastane != "" && $selected_hastane_bolum != "") {
                    foreach ($doktorlar as $doktor) {
                        $selected = ($doktor['doktor_adi_soyadi'] == $selected_doktor) ? 'selected' : '';
                        echo '<option value"' . $doktor['doktor_adi_soyadi'] . '" ' . $selected . '>' . $doktor['doktor_adi_soyadi'] . '</option>'; ?>

                <?php }
                } ?>
            </select>
            <input type="hidden" name="hasta_id" value="<?php echo $hasta_veri['hasta_id']; ?>">


            <button class="form-control btn btn-primary" name="randevu_kaydet">Randevu Kaydet</button>
        </form>

    </div>

</body>

</html>