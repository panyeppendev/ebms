<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row mb-3">
            <div class="col-2">
                <select name="package" id="package" class="form-control form-control-sm">
                    <option <?= ($step[0] == 0) ? 'selected' : '' ?> value="0">Pembayaran</option>
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                    ?>
                        <option <?= ($step[0] == $i) ? 'selected' : '' ?> value="<?= $i ?>">Paket - <?= $i ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-default btn-sm btn-block">
                    <i class="fas fa-file-download"></i>
                    Unduh Rincian
                </button>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-default btn-sm btn-block">
                    <i class="fas fa-file-download"></i>
                    Unduh Akumulasi
                </button>
            </div>
            <div class="col-2">
                <select name="disbursement" id="disbursement" class="form-control form-control-sm">
                    <option <?= ($step[1] == 0) ? 'selected' : '' ?> value="0">Pencairan</option>
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                    ?>
                        <option <?= ($step[1] == $i) ? 'selected' : '' ?> value="<?= $i ?>">Paket - <?= $i ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-default btn-sm btn-block">
                    <i class="fas fa-file-download"></i>
                    Unduh Rincian
                </button>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-default btn-sm btn-block">
                    <i class="fas fa-file-download"></i>
                    Unduh Akumulasi
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body p-2">
                        <table class="table table-sm">
                            <thead>
                                <tr class="text-center">
                                    <th>NO</th>
                                    <th>URAIAN</th>
                                    <th>QTY</th>
                                    <th>SATUAN</th>
                                    <th>JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>Uang Saku Paket A</td>
                                    <td class="text-center">10</td>
                                    <td class="text-right">20.000</td>
                                    <td class="text-right">200.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>


<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" id="show-detail" style="max-height: 85vh; overflow: auto"></div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('report/js-report'); ?>