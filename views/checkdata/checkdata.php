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
        <?php if ($setting == 'CLOSE' && date('Y-m-d H:i:s') < $data[1]) { ?>
            <div class="row mt-3">
                <div class="error-page" style="margin-top: 100px;">
                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! ada masalah nih....</h3>
                        <p>
                            Check In liburan akan dibuka pada <b class="text-success"> <?= datetimeIDFormat($data[1]) ?></b> ~<br>
                            <br>
                            <a href="<?= base_url() ?>">Kilik untuk kembali ke Beranda</a>
                        </p>

                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($setting == 'CLOSE' && (date('Y-m-d H:i:s') >= $data[1])) { ?>
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
                        </div>
                    </div>
                    <div id="alert-no" class="alert alert-success d-none" role="alert">
                        Santri ini sudah melakukan check ini liburan
                    </div>
                    <div id="alert-yes" class="alert alert-danger d-none" role="alert">
                        Santri ini belum melakukan check ini liburan
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

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('checkdata/js-checkdata'); ?>