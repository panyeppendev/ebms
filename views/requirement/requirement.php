<?php $this->load->view('partials/header'); ?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <?php if ($setting == 'OPEN') { ?>
            <div class="row mt-3">
                <div class="error-page" style="margin-top: 100px;">
                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! ada masalah nih....</h3>
                        <p>
                            Liburan belum diatur. Segera hubungi bagian admin ~<br>
                            <br>
                            <a href="<?= base_url() ?>">Kilik untuk kembali ke Beranda</a>
                        </p>

                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($setting == 'CLOSE') { ?>
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row mb-2">
                                <label for="nis" class="col-sm-4 col-form-label">ID Card</label>
                                <div class="col-sm-8">
                                    <input data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" id="nis" name="nis" autofocus>
                                </div>
                            </div>
                            <div class="p-2 d-flex text-success">
                                <span class="mr-1">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                <small class="pt-1">Pastikan cursor fokus pada bidang inputan, lalu masukkan nomor kartu santri lalu tekan ENTER</small>
                            </div>
                            <div class="mt-4">
                                <button id="tombol-cek" onclick="checkIn()" data-toggle="modal" data-target="#modal-checkin" type="button" class="btn btn-primary btn-block d-none">
                                    <i class="fas fa-tasks"></i>
                                    Check in Sekarang
                                </button>
                                <button id="tombol-batal" data-dismiss="modal" type="button" class="btn btn-danger mt-4 btn-block d-none" onclick="hideButton()">
                                    <i class="fas fa-times-circle"></i>
                                    Batalkan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="row" id="show-data">
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="modal-checkin" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body pb-0">
                <form id="form-checkin">
                    <input type="hidden" id="kelas" value="0" name="kelas">
                    <input type="hidden" id="id-nis" value="0" name="id_nis">
                    <input type="hidden" id="id-requirement" value="0" name="id_requirement">
                    <div class="row" id="show-checkin"></div>
                </form>
            </div>
            <div class="modal-footer justify-content-between p-2">
                <button data-dismiss="modal" class="btn btn-danger btn-sm">Batal</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="saveCheckin()">Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('requirement/js-requirement'); ?>