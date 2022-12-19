<?php $this->load->view('partials/header'); ?>
<input type="hidden" id="current-step" value="<?= $step ?>">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row mb-3">
            <div class="col-4">
                <input id="change-name" type="text" class="form-control form-control-sm" onkeyup="loadData()">
            </div>
            <div class="col-3">
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
                <button type="button" class="btn btn-sm btn-default btn-block">
                    <b class="text-primary">
                        Rekap
                    </b>
                </button>
            </div>
            <div class="col-3">
                <button data-toggle="modal" data-target="#modal-deposit" type="button" class="btn btn-sm btn-primary btn-block" id="add-deposit">
                    <i class="fa fa-plus-circle"></i>
                    Tambah Tabungan
                </button>
            </div>
        </div>
        <div class="row" id="show-data"></div>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="modal-deposit" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Form Tabungan Paket <?= $step ?></h6>
                <div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
                    <i class="fas fa-times-circle text-danger"></i>
                </div>
            </div>
            <div class="modal-body" style="background-color: #f4f6f9;">
                <div class="row">
                    <div class="col-4">
                        <div class="card mb-0">
                            <div class="card-body">
                                <input data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" name="id" id="id">
                                <small class="text-info">Tekan ENTER</small>
                                <form id="form-deposit" class="mt-2" autocomplete="off">
                                    <input type="hidden" name="package" id="package" value="0">
                                    <input type="hidden" name="saldo" id="saldo" value="0">
                                    <input readonly type="text" class="form-control" id="nominal" name="nominal">
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary btn-block" onclick="saveButton()">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-8" id="show-check">

                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between p-2"></div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Detail Tabungan</h6>
                <div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
                    <i class="fas fa-times-circle text-danger"></i>
                </div>
            </div>
            <div class="modal-body pt-0">
                <div class="row" id="show-detail"></div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('deposit/js-deposit'); ?>