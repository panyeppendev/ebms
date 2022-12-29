<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row mb-3">
            <div class="col-6"></div>
            <div class="col-2">
                <select onchange="loadPaymentReport()" id="change-step-payment" class="form-control form-control-sm">
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
        </div>
        <div class="row">
            <div class="col-12" id="show-payment-report"></div>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-3"></div>
            <div class="col-2">
                <select onchange="loadDisbursementReport()" id="change-step-disbursement" class="form-control form-control-sm">
                    <option value="0" <?= ($step[1] == 0) ? 'selected' : '' ?>>Pencairan</option>
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                    ?>
                        <option <?= ($step[1] == $i) ? 'selected' : '' ?> value="<?= $i ?>">Paket - <?= $i ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="reservation" placeholder="Semua waktu">
                    <input type="hidden" id="start-date" value="">
                    <input type="hidden" id="end-date" value="">
                </div>
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
            <div class="col-12" id="show-disbursement-report">

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
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('report/js-report'); ?>