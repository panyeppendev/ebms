<?php
if ($data) {
    foreach ($data as $d) {
        $status = $d->status;
        if ($status == 'POCKET') {
            $status = '<span class="text-primary">Uang Saku</span>';
        } elseif ($status == 'CASH') {
            $status = '<span>Tunai</span>';
        } else {
            $status = '<span class="text-danger">Unknown</span>';
        }
?>
        <div class="row text-xs">
            <div class="col-3"><?= datetimeIDShirtFormat($d->created_at) ?></div>
            <div class="col-4"><?= $d->name ?></div>
            <div class="col-2"><?= $d->domicile ?></div>
            <div class="col-1 text-right"><?= number_format($d->amount, 0, ',', '.') ?></div>
            <div class="col-2"><?= $status ?></div>
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