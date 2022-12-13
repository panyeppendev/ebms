<?php $this->load->view('partials/header'); ?>
<?php
if ($step) {
    $currentStep = $step->step;
} else {
    $currentStep = 0;
}
?>
<form action="<?= base_url() ?>package/printone" method="post" target="_blank" id="form-print">
    <input type="hidden" name="invoice" id="invoice" value="0">
</form>
<input type="hidden" id="current-step" value="<?= $currentStep ?>">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row mb-3">
            <div class="col-3">
                <input id="change-name" type="text" class="form-control form-control-sm" onkeyup="loadData()">
            </div>
            <div class="col-2">
                <select onchange="loadData()" id="change-domicile" class="form-control form-control-sm">
                    <option value="">.:Semua Domisili:.</option>
                    <option value="Imam Ghazali">Imam Ghazali</option>
                    <option value="Imam Maliki">Imam Maliki</option>
                    <option value="Imam Hanafi">Imam Hanafi</option>
                    <option value="Imam Hambali">Imam Hambali</option>
                    <option value="Imam Sibaweh">Imam Sibaweh</option>
                    <option value="Imam Syafi'i">Imam Syafi'i</option>
                    <option value="Imam Ibnu Hajar Al-Haitami">Imam Ibnu Hajar Al-Haitami</option>
                    <option value="Imam An-Nawawi">Imam An-Nawawi</option>
                    <option value="Imam Ar-Rofi'i">Imam Ar-Rofi'i</option>
                    <option value="Imam Haramain">Imam Haramain</option>
                    <option value="Sayyidina Abu Bakar">Sayyidina Abu Bakar</option>
                    <option value="Sayyidina Umar">Sayyidina Umar</option>
                    <option value="Sayyidina Utsman">Sayyidina Utsman</option>
                    <option value="Sayyidina Ali">Sayyidina Ali</option>
                    <option value="Imam As-Suyuthi">Imam As-Suyuthi</option>
                </select>
            </div>
            <div class="col-2">
                <select onchange="loadData()" id="change-status" class="form-control form-control-sm">
                    <option value="">.:Semua Status:.</option>
                    <option value="ACTIVE">AKTIF</option>
                    <option value="INACTIVE">BELUM AKTIF</option>
                    <option value="EXPIRED">EXPIRED</option>
                    <option value="INORDER">BELUM BELI</option>
                </select>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-sm btn-default btn-block" onclick="packageActivation(this, <?= $currentStep ?>)">
                    <i class="fa fa-clipboard-check"></i>
                    Aktifkan Paket <?= $currentStep ?>
                </button>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-sm btn-default btn-block">
                    <b class="text-primary">
                        Rekap
                    </b>
                </button>
            </div>
            <div class="col-2">
                <button data-toggle="modal" data-target="#modal-package" type="button" class="btn btn-sm btn-primary btn-block" id="add-package">
                    <i class="fa fa-plus-circle"></i>
                    Beli Paket
                </button>
            </div>
        </div>
        <div class="row" id="show-data"></div>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="modal-package" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Form Pemabayaran Paket <?= step($currentStep) ?></h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group row">
                            <label for="id" class="col-sm-3 col-form-label">NIS</label>
                            <div class="col-sm-9">
                                <input data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" name="id" id="id">
                                <small class="text-danger">Tekan ENTER</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <form id="form-save-package">
                            <div class="form-group row">
                                <label for="package" class="col-sm-5 col-form-label">Pilih Paket</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="id_student" id="id-student" value="0">
                                    <input type="hidden" name="step" id="step" value="0">
                                    <input type="hidden" name="level_of_formal" id="level-of-formal" value="0">
                                    <select name="package" id="package" class="form-control" disabled>
                                        <option value="">.:Pilih:.</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-4 pl-4 pr-3" id="show-rate">

                    </div>
                </div>
                <div class="row px-3" id="show-check"></div>
            </div>
            <div class="modal-footer justify-content-between p-2">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm" style="display: none" id="save-package">Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('package/js-package'); ?>