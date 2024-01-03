<?php
include('header.html');
include('connect.php');
include('giris_kontrol.php');
?>

<form method="post">
    <div class="d-flex mt-3">
        <div class="m-auto me-5 card" style="width: 18rem;">
            <img src="images/doktor.png" class="card-img-top" alt="...">
            <div class="card-body">
                <a href="doktor.php" class="btn btn-primary text-start w-100 text-white">Doktorlar</a>
            </div>
        </div>

        <div class="card ms-5 m-auto" style="width: 18rem;">
            <img src="images/hastane.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <a href="hastane.php" class="btn btn-primary text-start w-100 text-white">Hastaneler</a>

            </div>
        </div>
    </div>

    <div class="d-flex mt-3">
        <div class="m-auto card" style="width: 18rem;">
            <img src="images/hasta.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <a href="hasta.php" class="btn btn-primary text-start w-100 text-white">Hastalar</a>
            </div>
        </div>
    </div>
</form>


<?php

include('footer.html');
