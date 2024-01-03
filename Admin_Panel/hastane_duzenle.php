<?php
include('header.html');
include('giris_kontrol.php');
include('function.php');
if (isset($_GET['hastane_id'])) {
    $hastane_id = $_GET['hastane_id'];
    $sql_command = "SELECT * FROM hastane WHERE hastane_id = $hastane_id";
    $hastane_query = $db->query($sql_command);
    $hastane_info = $hastane_query->fetch(PDO::FETCH_ASSOC);


    
    if (isset($_POST['iller']) && $_POST['iller'] != "") $selected_il = $_POST['iller'];
    else $selected_il = $hastane_info['il_adi'];
    if (isset($_POST['ilceler']) && $_POST['ilceler'] != "") $selected_ilce = $_POST['ilceler'];
    else $selected_ilce = $hastane_info['ilce_adi'];
    if (isset($_POST['hastaneler']) && $_POST['hastaneler'] != "") $selected_hastane = $_POST['hastaneler'];
    else $selected_hastane = "";
    if (isset($_POST['hastane_bolumleri']) && $_POST['hastane_bolumleri'] != "") $selected_hastane_bolum = $_POST['hastane_bolumleri'];
    else $selected_hastane_bolum = "";
    if (isset($_POST['doktorlar']) && $_POST['doktorlar'] != "") $selected_doktor = $_POST['doktorlar'];
    else $selected_doktor = "";



    $queryy = $db->query("SELECT * FROM il ORDER BY il_adi");
    while ($sehir = $queryy->fetch(PDO::FETCH_ASSOC)) {
        $sehirler[] = $sehir;
    }


    $SQL = "SELECT ilce.ilce_adi FROM ilce
        INNER JOIN il ON ilce.IL_ID = il.IL_ID
        WHERE il.il_adi = '$selected_il'
        ORDER BY ilce.ilce_adi;";

    $queryy = $db->query($SQL);
    $ilceler = [];
    while ($ilce = $queryy->fetch(PDO::FETCH_ASSOC)) {
        $ilceler[] = $ilce;
    }

?>

    <div class="vh-100">
        <nav class="navbar pe-3 d-flex" data-bs-theme="dark">
            <form class="w-100" role="search" method="post">
                <a href="hastane.php" class=" btn btn-danger ms-2">Geri Dön</a>
            </form>
        </nav>

        <div class="w-50 m-auto text-white">
            <h3 class="text-center">Hastane Düzenle</h3>
            <form class="text-white" method="post">
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
                    <label class="form-label" for="hastane_adi">Hastane Adı:</label>
                    <input type="text" value="<?php echo $hastane_info['hastane_adi']?>" name="hastane_adi" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="hastane_tel">Hastane Telefon:</label>
                    <input type="tel" maxlength="10" value="<?php echo $hastane_info['telefon']?>" name="hastane_tel" class="form-control">
                </div>
                <button name="hastane_duzenle_kayit" value="<?php echo $hastane_id; ?>" class="btn btn-secondary w-100">Düzenle</button>
            </form>
        </div>
    </div>
<?php
    include('footer.html');
} else {
    echo "yanlış Yönlendirme";
    header('refresh:3, hastane.php');
}
