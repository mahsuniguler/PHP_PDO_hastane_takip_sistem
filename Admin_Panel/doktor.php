<?php
include('header.html');
include('giris_kontrol.php');
include('function.php');
$pagerowcount = 500;

$selected_page = isset($_GET['page']) ? $_GET['page'] : 1;

$sql_command = "SELECT * FROM doktorlar INNER JOIN hastane_bolumleri ON doktorlar.hastane_bolum_id = hastane_bolumleri.hastane_bolum_id ORDER BY doktor_adi_soyadi";

if (isset($_POST['doktor_search_txt']) || (isset($_SESSION['doktor_search_txt']) && $_SESSION['doktor_search_txt'] != "")) {
    $_SESSION['doktor_search_txt'] = isset($_POST['doktor_search_txt']) ? $_POST['doktor_search_txt'] : $_SESSION['doktor_search_txt'];
    $search = preg_replace('/\s+/', ' ', $_SESSION['doktor_search_txt']);
    if ($search != "") {
        $sql_command = "SELECT * FROM doktorlar 
        INNER JOIN hastane_bolumleri ON doktorlar.hastane_bolum_id = hastane_bolumleri.hastane_bolum_id 
        WHERE doktorlar.doktor_adi_soyadi LIKE '%$search%' OR doktorlar.hastane_adi LIKE '%$search%' OR hastane_bolumleri.hastane_bolum_adi LIKE '%$search%'
        ORDER BY doktorlar.doktor_adi_soyadi";
    }
}

$doktorsayisiquery = $db->prepare($sql_command);
$doktorsayisiquery->execute();
$doktor_sayisi = $doktorsayisiquery->rowCount();

$sql_command = $sql_command . " LIMIT " . (($selected_page - 1) * $pagerowcount) . ", $pagerowcount";
$doktorquery = $db->prepare($sql_command);
$doktorquery->execute();

?>
<header class="p-3 text-light">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <form class="d-flex justify-content-between w-100" role="search" method="post">
            <ul class="nav col-12 col-lg-auto me-auto mb-2 justify-content-center mb-md-0">
                <a href="doktor_ekle.php" class="form-control btn btn-primary">Doktor Ekle</a>
            </ul>
            <div class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
                <input class="form-control form-control-dark text-bg-dark" name="doktor_search_txt" value="<?php echo isset($search) ? $search : ""; ?>" type="search" placeholder="Ara..." aria-label="Search">
            </div>
        </form>
    </div>
</header>

<table class="table table-striped table-hover p-0 m-0 opacity0_5">
    <thead>
        <tr>
            <th>Sıra</th>
            <th>Doktor Adı</th>
            <th>Hastane Adı</th>
            <th colspan="3">Bölümü</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sira = ($selected_page - 1) * $pagerowcount + 1;
        while ($vericek = $doktorquery->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo $sira; ?></td>
                <td><?php echo $vericek['doktor_adi_soyadi'] ?></td>
                <td><?php echo $vericek['hastane_adi'] ?></td>
                <td><?php echo $vericek['hastane_bolum_adi'] ?></td>
                <td>
                    <a href="<?php echo 'doktor_duzenle.php?doktor_id=' . $vericek['doktor_id'];  ?>" class="btn btn-secondary me-2">Düzenle</button>
                </td>
                <td>
                    <button type="button" class="btn btn-danger" onclick="showConfirmationModal(event, <?php echo $vericek['doktor_id']; ?>)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                        </svg>
                    </button>
                </td>
            </tr>
        <?php $sira++;
        } ?>
    </tbody>
</table>


<?php

$total_pages = (int)($doktor_sayisi / $pagerowcount) + 1;
$kalanrow = $doktor_sayisi - $pagerowcount * $total_pages;
?>

<form action="" method="post">
    <div class="modal" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Uyarı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Silmek istediğinizden emin misiniz?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-danger" name="doktor_sil" id="confirmDeleteButton" value="">Sil</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    function showConfirmationModal(event, doktor_id) {
        var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.show();
        document.getElementById('confirmDeleteButton').value = doktor_id;
    }

    function deleteItem() {
        alert("Öğe başarıyla silindi!");
    }
</script>


<div class="text-white">
    <div class="d-flex m-auto mt-3 mb-5 justify-content-center align-items-center">
        <a class="me-3 text-white text-decoration-none" <?php $previous = $selected_page != 1 ? ('href="doktor.php?page=' . $selected_page - 1 . '"') : ('aria-disabled="true"');
                                                        echo $previous;  ?>>
            <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 172 172" style="fill:white; border-radius:50%; background-color:rgba(0, 0, 0, 0); border:white solid; border-radius: 50%;">
                <g fill="none" fill-rule="nonzero">
                    <path d="M0,172v-172h172v172z" fill="none" />
                    <g fill="white">
                        <text x="50%" y="50%" font-size="64" dominant-baseline="middle" text-anchor="middle">&lt;</text>
                    </g>
                </g>
            </svg>
        </a>
        <a class="me-3 text-white text-decoration-none" href="doktor.php">1</a>
        <?php

        if (in_array($selected_page, array(1, 2, 3))) { ?>
            <a class="me-3 text-white text-decoration-none" href="doktor.php?page=2">2</a>
            <a class="me-3 text-white text-decoration-none" href="doktor.php?page=3">3</a>
            <div class="me-3 text-white text-decoration-none">...</div>
        <?php } elseif (in_array($selected_page, array($total_pages, $total_pages - 1, $total_pages - 2))) { ?>
            <div class="me-3 text-white text-decoration-none">...</div>
            <a class="me-3 text-white text-decoration-none" href="doktor.php?page=<?php echo $total_pages - 2 ?>"><?php echo $total_pages - 2 ?></a>
            <a class="me-3 text-white text-decoration-none" href="doktor.php?page=<?php echo $total_pages - 1 ?>"><?php echo $total_pages - 1 ?></a>
        <?php } else { ?>
            <div class="me-3 text-white text-decoration-none">...</div>

            <b><a class="me-3 text-white text-decoration-none" href="doktor.php?page=<?php echo $selected_page ?>"><?php echo $selected_page ?></a></b>
            <div class="me-3 text-white text-decoration-none">...</div>
        <?php } ?>

        <a class="me-3 text-white text-decoration-none" href="doktor.php?page=<?php echo $total_pages . '"' ?>"><?php echo $total_pages ?></a>
        <a class="me-3 text-white text-decoration-none" <?php $ileri = $selected_page != $total_pages ?  ('href="doktor.php?page=' . $selected_page + 1 . '"') : ('aria-disabled="true"');
                                                        echo $ileri; ?>>
            <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 172 172" style="fill:white; border-radius:50%; background-color:rgba(0, 0, 0, 0); border:white solid; border-radius: 50%;">
                <g fill="none" fill-rule="nonzero">
                    <path d="M0,172v-172h172v172z" fill="none" />
                    <g fill="white">
                        <text x="50%" y="50%" font-size="64" dominant-baseline="middle" text-anchor="middle">&gt;</text>
                    </g>
                </g>
            </svg>

        </a>
    </div>
</div>


<?php
include('footer.html');
