<dl class="row mb-0">
    <dt class="col-sm-6">Kamar</dt>
    <dd class="col-sm-6 font-weight-bold">Kepala Kamar</dd>
</dl>
<hr class="my-0">
<dl class="row mt-2">
    <?php
    if ($rooms) {
        foreach ($rooms as $room) {
    ?>
            <dt class="col-sm-6 font-weight-normal"><?= $room->name ?></dt>
            <dd class="col-sm-6 d-flex justify-content-between">
                <div>
                    <?= $room->head ?>
                </div>
                <div>
                    <a href="" onclick="editRoom('<?= $room->id ?>', event)">Edit</a>
                    <a href="" onclick="deleteRoom('<?= $room->id ?>', event)"><span class="text-danger">Hapus</span></a>
                </div>
            </dd>
    <?php
        }
    } else {
        echo '<dt class="col-sm-7 font-weight-normal">Belum ada data</dt>';
    }
    ?>
</dl>