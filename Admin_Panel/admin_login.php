<?php
$oturum_durumu = session_status();

if ($oturum_durumu == PHP_SESSION_NONE) {
    ob_start();
    session_start();
}
if (isset($_SESSION['adminUsername'])) {
    if ($_SESSION['adminUsername'] != '') {
        header('location: index.php');
    } else {
        session_destroy();
        header('location: admin_login.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="../bootstrap_5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="container text-white" style="height: 700px;">
    <form class="mt-5 pt-5" action="function.php" method="post">
        <div class="m-auto col-md-3">
            <div>
                <img src="images/AdminLogin.png" alt="admin" width=100%>
            </div>
            <div class="my-3">
                <label for="username" class="form-label">Kullanıcı Adı</label>
                <input type="text" name="adminUsername" class="form-control" id="inputUsername">
            </div>
            <div class="my-3">
                <label for="inputPassword" class="form-label">Şifre</label>
                <input type="password" name="adminPass" class="form-control" id="inputPassword">
            </div>
            <div class="col-12 my-2">
                <button type="submit" name="adminLogin" class="btn btn-primary">Giriş Yap</button>
            </div>
        </div>
    </form>

    <script src="../bootstrap_5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>