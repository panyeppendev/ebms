<div class="row">
    <div class="col-6" style="cursor: pointer" onclick="loadData()">
        <div class="card">
            <div class="card-body py-1">
                <i class="fas fa-receipt"></i>
                <span><?= $data[1] ?> Transaksi</span>
            </div>
        </div>
    </div>
    <div class="col-6" style="cursor: pointer" onclick="loadData()">
        <div class="card">
            <div class="card-body py-1">
                <i class="fas fa-money-bill"></i>
                <span>Rp. <?= number_format($data[0], 0, ',', '.') ?></span>
            </div>
        </div>
    </div>
</div>