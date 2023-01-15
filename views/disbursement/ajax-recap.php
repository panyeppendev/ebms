<div class="row">
    <div class="col-6" style="cursor: pointer" onclick="loadData()">
        <div class="callout callout-success py-1">
            <i class="fas fa-receipt"></i>
            <span class="text-success"><?= $data[1] ?> Transaksi</span>
        </div>
    </div>
    <div class="col-6" style="cursor: pointer" onclick="loadData()">
        <div class="callout callout-success py-1">
            <i class="fas fa-money-bill"></i>
            <span class="text-success">Rp. <?= number_format($data[0], 0, ',', '.') ?></span>
        </div>
    </div>
</div>