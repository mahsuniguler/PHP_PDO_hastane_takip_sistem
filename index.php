<?php
$oturum_durumu = session_status();

if ($oturum_durumu == PHP_SESSION_NONE) {
    ob_start();
    session_start();
}
if (isset($_SESSION['userhasta_tc'])) {
    if ($_SESSION['userhasta_tc'] != '') {
        header('location: anasayfa.php');
    } else {
        session_destroy();
        header('location: index.php');
    }
}

?>
<!DOCTYPE html>
<html lang="tr">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hastane Otomasyonu</title>
    <link href="./bootstrap_5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="pt-5 ">

    <div class="form-signin vh-75 w-25 m-auto">
        <h1 class="mt-5">Hastane Otomasyonu</h1>
        <div class="form-signin">
            <h2 class="mt-5">Giriş Yap</h2>

            <form action="function.php" method="post">
                <div class="mb-3">

                    <input type="text" class="form-control" name="hasta_tc" placeholder="Tc Kimlik No">
                </div>
                <div class="mb-5">

                    <input type="password" class="form-control" name="hasta_password" placeholder="Şifre">
                </div>
                <button class="btn btn-primary w-100 py-2 mb-3" type="submit" name="giris_yap">Giriş Yap</button>

            </form>
            <a href="hasta_kayit.php" class="btn btn-secondary w-100 py-2 mb-3">Üye Ol</a>
            <br>
            <a href="./Admin_Panel/" class="btn btn-info w-100 py-2">Admin Girişi</a>
        </div>
    </div>
    <script src="./bootstrap_5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>