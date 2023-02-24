<?php
if ($data) {
    foreach ($data as $d) {
?>
        <div class="row text-xs">
            <div class="col-2"><?= datetimeIDShirtFormat($d->created_at) ?></div>
            <div class="col-3"><?= $d->name ?></div>
            <div class="col-2"><?= $d->domicile ?></div>
            <div class="col-5"><?= $d->constitution ?></div>
        </div>
        <hr class="my-1">
    <?php
    }
} else {
    ?>
    <h6 class="text-center text-danger">
        Tidak ada data untuk ditampilkan
    </h6>
<?php
}
?>
