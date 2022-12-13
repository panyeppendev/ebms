<dl class="row">
    <dt class="col-sm-7 font-weight-normal">Biaya Paket</dt>
    <dd class="col-sm-5 mb-0 text-right"><?= number_format($data['package'], 0, ',', '.') ?></dd>
    <dt class="col-sm-7 font-weight-normal pb-1" style="border-bottom: 1px solid #eee">Transport</dt>
    <dd class="col-sm-5 mb-0 text-right pb-1" style="border-bottom: 1px solid #eee"><?= number_format($data['transport'], 0, ',', '.') ?></dd>
    <dt class="col-sm-7 font-weight-normal pt-1 text-success">Total</dt>
    <dd class="col-sm-5 mb-0 text-right pt-1 text-success"><?= number_format($data['total'], 0, ',', '.') ?></dd>
</dl>