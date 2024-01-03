<?php

include('header.html');
include('giris_kontrol.php');
include('function.php');
if (isset($_GET['doktor_id'])) {

    $doktor_id = $_GET['doktor_id'];
    $sql_command = "SELECT * FROM doktorlar INNER JOIN hastane_bolumleri ON doktorlar.hastane_bolum_id = hastane_bolumleri.hastane_bolum_id WHERE doktorlar.doktor_id = $doktor_id";
    $doktor_query = $db->query($sql_command);
    $doktor_info = $doktor_query->fetch(PDO::FETCH_ASSOC);

    $hastane_id = $doktor_info['hastane_id'];
    $sql_command = "SELECT * FROM hastane WHERE hastane_id = $hastane_id";
    $hastane_query = $db->query($sql_command);
    $hastane_info = $hastane_query->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['iller']) && $_POST['iller'] != "") $selected_il = $_POST['iller'];
    else $selected_il = $hastane_info['il_adi'];
    if (isset($_POST['ilceler']) && $_POST['ilceler'] != "") $selected_ilce = $_POST['ilceler'];
    else $selected_ilce = $hastane_info['ilce_adi'];
    if (isset($_POST['hastane_adi']) && $_POST['hastane_adi'] != "") $selected_hastane = $_POST['hastane_adi'];
    else $selected_hastane = $doktor_info['hastane_adi'];
    if (isset($_POST['hastane_bolum']) && $_POST['hastane_bolum'] != "") $selected_hastane_bolum = $_POST['hastane_bolum'];
    else $selected_hastane_bolum = $doktor_info['hastane_bolum_adi'];


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
    } ?>

    <div class="vh-100">
        <nav class="navbar pe-3 d-flex" data-bs-theme="dark">
            <form class="w-100" role="search" method="post">
                <a href="hastane.php" class=" btn btn-danger ms-2">Geri Dön</a>
            </form>
        </nav>

        <div class="w-50 m-auto text-white">
            <h3 class="text-center">Doktor Düzenle</h3>
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
                        <input type="text" name="doktor_adi" value="<?php echo $doktor_info['doktor_adi_soyadi']; ?>" class="form-control">
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
                    <button name="doktor_duzenle_kayit" value="<?php echo $doktor_id; ?>" class="btn btn-secondary w-100">Düzenle</button>
                </div>
            </form>
        </div>
    </div>
<?php


    include('footer.html');
} else {
    echo "yanlış Yönlendirme";
    header('refresh:3, doktor.php');
}
