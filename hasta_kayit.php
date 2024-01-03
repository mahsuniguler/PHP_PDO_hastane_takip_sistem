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
            <h2 class="mt-5">Üye Ol</h2>

            <form action="function.php" method="post">
                <div class="mb-3">
                    <input type="text" class="form-control" name="hasta_adi_soyadi" placeholder="Ad Soyad">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="hasta_tc" maxlength="11" placeholder="Tc Kimlik No">
                </div>
                <div class="mb-5">
                    <input type="password" class="form-control" name="hasta_password" placeholder="Şifre">
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 mb-3" name="hasta_kaydet" value="hasta_kayit">Üye Ol</button>

            </form>
            <a href="index.php"><button type="submit" class="btn btn-danger w-100 py-2">Geri Çık</button></a>
        </div>
        <script src="./bootstrap_5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>