<?php
include('header.php');
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hastane Otomasyonu</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <table class="table table-striped opacity0_5 table-hover">
        <tr>
            <th>Hastane</th>
            <th>Hastane Bölümü</th>
            <th>Doktor</th>
            <th>İl</th>
            <th>Tarih</th>
        </tr>

        <?php
        $randevu_sor = $db->prepare("SELECT * FROM randevu INNER JOIN hastalar ON randevu.randevu_hasta_id = hastalar.hasta_id WHERE hasta_tc=:hasta_tc");
        $randevu_sor->execute([
            'hasta_tc' => $_SESSION['userhasta_tc']
        ]);
        while ($randevu_cek = $randevu_sor->fetch(PDO::FETCH_ASSOC)) { ?>

            <tr>
                <td><?php echo $randevu_cek['randevu_hastane']; ?></td>
                <td><?php echo $randevu_cek['randevu_hastane_bolum']; ?></td>
                <td><?php echo $randevu_cek['randevu_doktor']; ?></td>
                <td><?php echo $randevu_cek['randevu_sehir']; ?></td>
                <td><?php echo $randevu_cek['randevu_tarih']; ?></td>
            </tr>
        <?php } ?>
    </table>


</body>

</html>
<?php
include('footer.php');
