<?php

include('header.html');
include('giris_kontrol.php');
include('function.php');

if (isset($_POST['tarih']) && $_POST['tarih'] != "") $selected_tarih = $_POST['tarih'];
else $selected_tarih = "";
if (isset($_POST['iller']) && $_POST['iller'] != "") $selected_il = $_POST['iller'];
else $selected_il = "";
if (isset($_POST['ilceler']) && $_POST['ilceler'] != "") $selected_ilce = $_POST['ilceler'];
else $selected_ilce = "";
if (isset($_POST['hastane_adi']) && $_POST['hastane_adi'] != "") $selected_hastane = $_POST['hastane_adi'];
else $selected_hastane = "";
if (isset($_POST['hastane_bolum']) && $_POST['hastane_bolum'] != "") $selected_hastane_bolum = $_POST['hastane_bolum'];
else $selected_hastane_bolum = "";
if (isset($_POST['doktorlar']) && $_POST['doktorlar'] != "") $selected_doktor = $_POST['doktorlar'];
else $selected_doktor = "";


//hastane_bolum sorgusu
$queryy = $db->query("SELECT * FROM hastane_bolumleri ORDER BY hastane_bolum_adi");
while ($hastane_bolum = $queryy->fetch(PDO::FETCH_ASSOC)) {
    $hastane_bolumleri[] = $hastane_bolum;
}

//şehir sorgusu
$queryy = $db->query("SELECT DISTINCT(il_adi) FROM hastane ORDER BY il_adi");
while ($sehir = $queryy->fetch(PDO::FETCH_ASSOC)) {
    $sehirler[] = $sehir;
}

//ilce sorgusu
$SQL = "SELECT DISTINCT(ilce_adi) FROM hastane WHERE il_adi = '$selected_il' ORDER BY ilce_adi";


$queryy = $db->query($SQL);
$ilceler = [];
while ($ilce = $queryy->fetch(PDO::FETCH_ASSOC)) {
    $ilceler[] = $ilce;
}

//hastane sorgusu
if ($selected_il != "" && $selected_ilce == "") {
    $SQL = "SELECT hastane_adi FROM hastane
WHERE il_adi = '$selected_il'
ORDER BY hastane_adi;";
} else {
    $SQL = "SELECT hastane_adi FROM hastane
WHERE il_adi = '$selected_il' AND ilce_adi= '$selected_ilce'
ORDER BY hastane_adi;";
}


$queryy = $db->query($SQL);
$hastaneler = [];
while ($hastane = $queryy->fetch(PDO::FETCH_ASSOC)) {
    $hastaneler[] = $hastane;
}

?>

<div class="vh-100">
    <nav class="navbar pe-3 d-flex" data-bs-theme="dark">
        <form class="w-100" role="search" method="post">
            <a href="hastane.php" class=" btn btn-danger ms-2">Geri Dön</a>
        </form>
    </nav>

    <div class="w-50 m-auto text-white">
        <h3 class="text-center">Doktor Ekle</h3>
        <form class="text-white" method="post">
            <div class="mb-3">
                <div class="mb-3">
                    <label class="form-label" for="iller">İl Seçin:</label>

                    <select class="form-control" name="iller" class="hastane" onchange="this.form.submit()">
                        <option value="">-- Şehir Seçin --</option>

                        <?php
                        foreach ($sehirler as $sehir) {
                            $selected = ($sehir['il_adi'] == $selected_il) ? 'selected' : ''; ?>
                            <?php echo '<option value="' . $sehir['il_adi'] . '" ' . $selected . '>' . $sehir['il_adi'] . '</option>'; ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ilceler">İlçe Seçin:</label>
                    <select class="form-control " name="ilceler" onchange="this.form.submit()">
                        <option value="">-- İlçe Seçin --</option>
                        <?php
                        foreach ($ilceler as $ilce) {
                            $selected = ($ilce['ilce_adi'] == $selected_ilce) ? 'selected' : ''; ?>
                            <?php echo '<option value="' . $ilce['ilce_adi'] . '" ' . $selected . '>' . $ilce['ilce_adi'] . '</option>'; ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="hastane_adi">Hastane Seçin:</label>
                    <select class="form-control " name="hastane_adi" onchange="this.form.submit()">
                        <option value="">-- Hastane Seçin --</option>
                        <?php
                        foreach ($hastaneler as $hastane) {
                            $selected = ($hastane['hastane_adi'] == $selected_hastane) ? 'selected' : ''; ?>
                            <?php echo '<option value="' . $hastane['hastane_adi'] . '" ' . $selected . '>' . $hastane['hastane_adi'] . '</option>'; ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="doktor_adi">Doktor Adı Soyadı:</label>
                    <input type="text" name="doktor_adi" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="hastane_bolum">Branş Seç:</label>

                    <select class="form-control" name="hastane_bolum">
                        <option value="">-- Branş Seçin --</option>
                        <?php
                        foreach ($hastane_bolumleri as $hastane_bolum) {
                            $selected = ($hastane_bolum['hastane_bolum_adi'] == $selected_hastane_bolum) ? 'selected' : ''; ?>
                            <?php echo '<option value="' . $hastane_bolum['hastane_bolum_adi'] . '" ' . $selected . '>' . $hastane_bolum['hastane_bolum_adi'] . '</option>'; ?>
                        <?php } ?>
                    </select>
                </div>
                <button name="doktor_ekle" value="doktor_ekle" class="btn btn-primary">Ekle</button>
            </div>
        </form>
    </div>
</div>
<?php


include('footer.html');
