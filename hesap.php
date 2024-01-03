<?php
include('header.php');
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hastane Otomasyonu</title>
</head>

<body>
    <table class="hesabim_content">
        <tr clas="label">
            <td>
                <label>ADI SOYADI</label>
            </td>
            <td>
                <input type="text" placeholder="<?php echo $hasta_veri['hasta_adi_soyadi']; ?>">
            </td>
        </tr>
        <tr clas="label">
            <td>
                <label>TC NO</label>
            </td>
            <td>
                <input type="text" placeholder="<?php echo $hasta_veri['hasta_tc']; ?>">
            </td>
        </tr>
    </table>
</body>

</html>