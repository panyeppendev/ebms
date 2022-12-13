<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row mb-3">
            <div class="col-2 text-center">
                <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#modal-holiday" <?= ($setting == 'CLOSE') ? 'disabled' : '' ?>>
                    <i class="fas fa-business-time"></i>
                    <?= $data[0] ?>
                </button>
            </div>
            <div class="col-8 row">
                <div class="col-6">
                    <div class="callout callout-success py-1">
                        <i class="fas fa-clock"></i>
                        Pulang : <span class="text-success"><?= $data[1] ?></span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="callout callout-warning py-1">
                        <i class="fas fa-history"></i>
                        Kembali : <span class="text-warning"><?= $data[2] ?></span>
                    </div>
                </div>
            </div>
            <div class="col-2 text-center">
                <?php
                if ($setting == 'OPEN') {
                ?>
                    <button type="button" class="btn btn-sm btn-danger" onclick="setSetting('CLOSE')">
                        <i class=" fas fa-lock"></i>
                        Tutup Pengaturan
                    </button>
                <?php } ?>
                <?php
                if ($setting == 'CLOSE') {
                ?>
                    <button type="button" class="btn btn-sm btn-success" onclick="setSetting('OPEN')">
                        <i class="fas fa-lock-open"></i>
                        Buka Pengaturan
                    </button>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pr-2">
                        <f6 class="card-title">Akun Persyaratan Liburan</f6>
                        <?php if ($setting == 'OPEN') { ?>
                            <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#modal-account">
                                <i class="fa fa-plus-circle"></i> Tambah Akun
                            </button>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div class="row" id="load-data"></div>
                    </div>
                    <div class="card-footer py-2"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="modal-account" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Form Tambah Akun</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" id="id" value="0">
                        <div class="form-group">
                            <label>Masukkan Nama Akun</label>
                            <textarea id="name" class="form-control" rows="3" placeholder=""></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between p-2">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
                <button onclick="saveAccount()" type="button" class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-holiday" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Form Atur Tanggal</h6>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="holiday">Liburan</label>
                    <select class="custom-select" id="holiday">
                        <option value="">.:Pilih:.</option>
                        <option value="Maulid">Maulid</option>
                        <option value="Ramadhan">Ramadhan</option>
                    </select>
                    <small class="text-danger messages" id="error_holiday"></small>
                </div>
                <div class="form-group">
                    <label for="date_of_entry">Tanggal Pulang</label>
                    <div class="row">
                        <input id="date_of_holiday" type="text" class="form-control" data-inputmask="'mask': ['9999-99-99 99:99:99']" data-mask="" inputmode="numeric">
                    </div>
                    <small class="text-danger messages" id="error_date_of_holiday"></small>
                </div>
                <div class="form-group">
                    <label for="date_of_entry">Tanggal Kembali</label>
                    <div class="row">
                        <input type="text" class="form-control" id="date_of_comeback" data-inputmask="'mask': ['9999-99-99 99:99:99']" data-mask="" inputmode="numeric">
                    </div>
                    <small class="text-danger messages" id="error_date_of_comeback"></small>
                </div>
            </div>
            <div class="modal-footer justify-content-between p-2">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
                <button onclick="saveSetting()" type="button" class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('security/js-security'); ?>