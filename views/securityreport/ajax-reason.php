<?php
if ($reasons) {
    $text = [
        'SHORT' => 'Dekat',
        'LONG' => 'Jauh'
    ];
    foreach ($reasons as $reason) {
?>
    <div class="row">
        <div class="col-9">
            - <?= $reason->name ?>
        </div>
        <div class="col-2 text-center">
            <?= $text[$reason->type] ?>
        </div>
        <div class="col-1 text-center text-danger" style="cursor: pointer;" onclick="deleteReason(<?= $reason->id ?>)">
            <i class="fas fa-trash"></i>
        </div>
    </div>
    <hr class="my-1">
<?php
    }
}else {
?>
    <div class="row">
        <div class="col-12 text-center font-italic text-danger">
            Belum ada data
        </div>
    </div>
<?php
}
?>