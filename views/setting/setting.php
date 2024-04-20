<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row">
            <section class="col-lg-4 connectedSortable ui-sortable">
                <div class="card">
                    <div class="card-body">
                        <?php
                        $resultStudent = $this->session->flashdata('import-student');
                        if ($resultStudent) {
                            $classStudent = [1 => 'success', 'danger', 'danger'];
                            $textStudent = [
                                1 => 'Yeaaahh..! Data santri berhasil diimport',
                                'Oppsss..! Format kolom dalam file tidak valid',
                                'Oppsss..! Ekstensi file harus .xls atau .xlxs'
                            ];
                        ?>
                            <div class="alert alert-<?= $classStudent[$resultStudent] ?> alert-dismissible fade show" role="alert">
                                <?= $textStudent[$resultStudent] ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php
                        }
                        ?>
                        <form id="form-import-student" action="<?= base_url() ?>setting/importstudent" enctype="multipart/form-data" method="post">
                            <div class="form-group">
                                <label for="file">Import Data Santri</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="hidden" id="file-selected" value="">
                                        <input type="file" class="custom-file-input" name="file" id="file">
                                        <label class="custom-file-label" id="label-file" for="file">Pilih file</label>
                                    </div>
                                    <ul class="text-success mt-3">
                                        <li>Pastikan file ber-ekstensi .xls atau xlsx</li>
                                        <li>Pastikan Nomor NIS tidak duplikat baik dalam file-nya atau dalam database</li>
                                        <li>Pastikan format sesuai sample</li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url() ?>setting/sample/1">
                                <i class="fas fa-file-download"></i> Sample
                            </a>
                            <button id="import-file" class="btn btn-sm btn-primary btn-sm" type="submit">
                                <i class="fas fa-file-import"></i> Import
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <?php
                        $resultCalendar = $this->session->flashdata('import-calendar');
                        if ($resultCalendar) {
                            $classCalendar = [1 => 'success', 'danger', 'danger'];
                            $textCalendar = [
                                1 => 'Yeaaahh..! Data kalendar berhasil diimport',
                                'Oppsss..! Format kolom dalam file tidak valid',
                                'Oppsss..! Ekstensi file harus .xls atau .xlxs'
                            ];
                        ?>
                            <div class="alert alert-<?= $classCalendar[$resultCalendar] ?> alert-dismissible fade show" role="alert">
                                <?= $textCalendar[$resultCalendar] ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php
                        }
                        ?>
                        <form id="form-import-calendar" action="<?= base_url() ?>setting/import" enctype="multipart/form-data" method="post">
                            <input type="hidden" id="file-selected-calendar" value="">
                            <div class="form-group">
                                <label for="file_calendar">Import Data Kalender</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="file_calendar" id="file_calendar">
                                        <label class="custom-file-label" id="label-file-calendar" for="file_calendar">Pilih file</label>
                                    </div>
                                </div>
                                <ul class="text-success mt-3">
                                    <li>Pastikan file ber-ekstensi .xls atau .xlsx</li>
                                    <li>Pastikan format sesuai sample</li>
                                </ul>

                            </div>
                        </form>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url() ?>setting/sample/2" id="sample-file-calendar">
                                <i class="fas fa-file-download"></i> Sample
                            </a>
                            <button id="import-file-calendar" class="btn btn-sm btn-primary btn-sm" type="submit">
                                <i class="fas fa-file-import"></i> Import
                            </button>
                        </div>
                    </div>
                </div>

				<button class="btn btn-primary btn-block" onclick="resetAll()">Reset</button>
            </section>
            <section class="col-lg-8 connectedSortable ui-sortable">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <span>
                                    Periode saat ini:
                                    <span class="badge badge-success" id="current_period">
                                        <?= $period ?></span>
                                    <hr>
                                    <div class="form-group">
                                        <label for="period" class="font-weight-normal">Tahun Periode</label>
                                        <input type="text" name="period" data-inputmask="'mask' : '9999-9999'" data-mask="" class="form-control form-control-border" id="period">
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button id="save-period" class="btn btn-sm btn-primary" onclick="savePeriod()">Simpan</button>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 d-flex">
                            <span class="text-danger mr-2">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </span>
                            <h6 class="text-danger">
                                PERHATIAN
                            </h6>
                        </div>
                        <div class="p-2 d-flex">
                            <small class="text-danger">
                                Tahun periode ini diatur setahun sekali, yaitu setiap awal pelajaran
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h6 class="card-title">Data Kamar</h6>
                            <a href="" onclick="addRoom(event)">Tambah</a>
                        </div>
                    </div>
                    <div class="card-body" id="show-room"></div>
                </div>
            </section>
        </div>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="modal-room" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Form Tambah Kamar</h6>
            </div>
            <div class="modal-body">
                <form autocomplete="off" id="form-room">
                    <input type="hidden" name="id" id="id" value="0">
                    <div class="form-group row">
                        <label for="name" class="col-sm-4 col-form-label">Nama Kamar</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control text-capitalize" name="name" id="name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="head" class="col-sm-4 col-form-label">Kepala Kamar</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control text-uppercase" name="head" id="head">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between p-2">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm" id="save-room">Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('setting/js-setting'); ?>
