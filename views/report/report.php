<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row mb-3">
            <div class="col-2">
                <select name="package" id="package" class="form-control form-control-sm">
                    <option value="0">Pembayaran</option>
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                    ?>
                        <option value="<?= $i ?>">Paket - <?= $i ?></option>
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
                    <option value="0">Pencairan</option>
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                    ?>
                        <option value="<?= $i ?>">Paket - <?= $i ?></option>
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
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nis">NIS</label>
                            <input autofocus data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" id="nis" name="nis">
                        </div>
                        <div class="form-group">
                            <form id="form-disbursement" autocomplete="off">
                                <input type="hidden" name="package_save" id="package-save">
                                <input type="hidden" name="pocket_save" id="pocket-save">
                                <input type="hidden" name="total_save" id="total-save">
                                <label for="nominal">Nominal</label>
                                <input readonly type="text" class="form-control indonesian-currency" id="nominal" name="nominal">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8" id="show-data"></div>
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