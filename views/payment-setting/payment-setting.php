<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row">
            <div class="col-7">
                <div class="card">
                    <div class="card-header py-2 pr-2">
                        <h6 class="card-title mt-1">Tarif Sumbangan</h6>
                        <button <?= $setting ? 'disabled' : '' ?> type="button" class="btn btn-sm btn-primary float-right" id="set-rate">
                            <i class="fa fa-cog"></i>
                            Atur Tarif
                        </button>
                        <select id="change-type" onchange="loadData()" class="form-control form-control-sm float-right mr-2" style="width: 150px">
                            <option value="">..:Semua:..</option>
                            <option value="NEW">Biaya Pendaftaran</option>
                            <option value="OLD">Biaya Tahunan</option>
                        </select>
                    </div>
                    <div class="card-body table-responsive p-0" id="show-rate">

                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
            <div class="col-5">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header py-2 pr-2">
                                <h6 class="card-title mt-1">Tarif Paket</h6>
                                <button <?= $setting ? 'disabled' : '' ?> type="button" class="btn btn-sm btn-primary float-right" id="set-package">
                                    <i class="fa fa-cog"></i>
                                    Atur Paket
                                </button>
                                <select id="change-package" class="form-control form-control-sm float-right mr-2" style="width: 120px">
                                    <option value="">..:Semua:..</option>
                                    <option value="GENERAL">Umum</option>
                                    <option value="AB">Paket A-B</option>
                                    <option value="CD">Paket C-D</option>
                                </select>
                            </div>
                            <div class="card-body table-responsive p-0" id="show-package">

                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header py-2 pr-2">
                                <h6 class="card-title mt-1">Pembayaran :
                                    <?php
                                    if ($step[0]) {
                                    ?>
                                        <b class="text-success"><?= step($step[0]->step) ?></b>
                                    <?php
                                    } else {
                                        echo '<b class="text-success">Belum diatur</b>';
                                    }
                                    ?>
                                </h6>
                                <button type="button" class="btn btn-sm btn-primary float-right" id="set-step">
                                    <i class="fa fa-cog"></i>
                                    Atur
                                </button>
                                <select id="change-step" class="form-control form-control-sm float-right mr-2" style="width: 100px">
                                    <option value="">.:Pilih:.</option>
                                    <option value="1">Tahap I</option>
                                    <option value="2">Tahap II</option>
                                    <option value="3">Tahap III</option>
                                    <option value="4">Tahap IV</option>
                                    <option value="5">Tahap V</option>
                                    <option value="6">Tahap VI</option>
                                    <option value="7">Tahap VII</option>
                                    <option value="8">Tahap VIII</option>
                                    <option value="9">Tahap IX</option>
                                    <option value="10">Tahap X</option>
                                    <option value="11">Tahap XI</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header py-2 pr-2">
                                <h6 class="card-title mt-1">Pencairan :
                                    <?php
                                    if ($step[1]) {
                                    ?>
                                        <b class="text-success"><?= step($step[1]->step) ?></b>
                                    <?php
                                    } else {
                                        echo '<b class="text-success">Belum diatur</b>';
                                    }
                                    ?>
                                </h6>
                                <button type="button" class="btn btn-sm btn-primary float-right" id="set-step-disbursement">
                                    <i class="fa fa-cog"></i>
                                    Atur
                                </button>
                                <select id="change-step-disbursement" class="form-control form-control-sm float-right mr-2" style="width: 100px">
                                    <option value="">.:Pilih:.</option>
                                    <option value="1">Tahap I</option>
                                    <option value="2">Tahap II</option>
                                    <option value="3">Tahap III</option>
                                    <option value="4">Tahap IV</option>
                                    <option value="5">Tahap V</option>
                                    <option value="6">Tahap VI</option>
                                    <option value="7">Tahap VII</option>
                                    <option value="8">Tahap VIII</option>
                                    <option value="9">Tahap IX</option>
                                    <option value="10">Tahap X</option>
                                    <option value="11">Tahap XI</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-5">
                <div class="card">
                    <div class="card-header py-2 pr-2">
                        <div class="card-title m-1">
                            <h6>Daftar Tarif</h6>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-head-fixed text-nowrap table-sm">
                            <thead>
                                <tr class="text-center">
                                    <th>NO</th>
                                    <th>URAIAN</th>
                                    <th colspan="2" style="width: 20%">NOMINAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $order = 1;
                                if ($datas) {
                                    foreach ($datas as $data) {
                                        $type = $data->type;
                                        $types = [
                                            'NEW' => 'Biaya Pendaftaran',
                                            'OLD' => 'Biaya Tahunan',
                                            'A' => 'Paket A',
                                            'B' => 'Paket B',
                                            'C' => 'Paket C',
                                            'D' => 'Paket D',
                                            'SD' => 'Transport SD'
                                        ];
                                ?>
                                        <tr>
                                            <td class="text-center"><?= $order++ ?></td>
                                            <td><?= $types[$type] ?></td>
                                            <td>Rp.</td>
                                            <td class=text-right><?= number_format($data->amount, 0, ',', '.') ?></td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr class="text-center text-danger"><td colspan="4">Tidak ada data untuk ditampilkan</td> </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header py-2 pr-2">
                        <h6 class="card-title mt-1">Daftar Shift</h6>
                        <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#modal-set-shift">
                            <i class="fa fa-cog"></i>
                            Atur Shift
                        </button>
                    </div>
                    <div class="card-body table-responsive p-0" id="show-shift"></div>
                    <div class="card-footer"></div>
                </div>
            </div>
            <div class="col-3">
                <div class="row px-3 text-danger d-flex">
                    <div class="p-2 d-flex">
                        <span class="mr-3">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </span>
                        <h6>PERHATIAN</h6>
                    </div>
                    <small class="p-2 mb-5">
                        Tarif Pembayaran ini diatur setahun sekali, yaitu setiap awal pelajaran.
                        Sebelum menutup, pastikan sudah valid.
                    </small>
                    <div class="row px-3">
                        <button <?= $setting ? 'disabled' : '' ?> id="set-payment" class="btn btn-danger btn-sm btn-block" onclick="setPayment()">
                            <?= $setting ? 'Pengaturan Sudah Ditutup' : 'Tutup Pengaturan' ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<div class="modal fade" id="modal-set-rate" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Form Atur Tarif</h6>
                <div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
                    <i class="fas fa-times-circle text-danger"></i>
                </div>
            </div>
            <div class="modal-body">
                <div id="show-form-new" class="d-none">
                    <form id="form-set-rate-new" autocomplete="off">
                        <?php
                        $orderNew = 1;
                        if ($accountNew) {
                            foreach ($accountNew as $new) {
                        ?>
                                <div class="form-group row">
                                    <label for="amount-<?= $new->id ?>" class="col-sm-6 col-form-label"><?= $new->name ?></label>
                                    <div class="col-6">
                                        <input type="hidden" name="code[]" value="<?= $new->id ?>">
                                        <input value="<?= $new->amount ?>" tabindex="<?= $orderNew++ ?>" type="text" id="amount-<?= $new->id ?>" class="form-control indonesian-currency" name="amount[]">
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                </div>
                </form>
                <div id="show-form-old" class="d-none">
                    <form id="form-set-rate-old" autocomplete="off">
                        <?php
                        $orderOld = 1;
                        if ($accountOld) {
                            foreach ($accountOld as $old) {
                        ?>
                                <div class="form-group row">
                                    <label for="amount-<?= $old->id ?>" class="col-sm-6 col-form-label"><?= $old->name ?></label>
                                    <div class="col-6">
                                        <input type="hidden" name="code[]" value="<?= $old->id ?>">
                                        <input value="<?= $old->amount ?>" tabindex="<?= $orderOld++ ?>" type="text" id="amount-<?= $old->id ?>" class="form-control indonesian-currency" name="amount[]">
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </form>
                </div>
            </div>
            <div class="modal-footer justify-content-between p-2">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm d-none" id="save-form-rate-new">Simpan Tarif Pendaftran</button>
                <button type="button" class="btn btn-primary btn-sm d-none" id="save-form-rate-old">Simpan Tarif Tahunan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-set-package" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Form Atur Paket</h6>
                <div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
                    <i class="fas fa-times-circle text-danger"></i>
                </div>
            </div>
            <div class="modal-body">
                <div id="show-form-general" class="d-none">
                    <form id="form-set-general" autocomplete="off">
                        <?php
                        $orderGeneral = 1;
                        if ($packageGeneral) {
                            foreach ($packageGeneral as $general) {
                        ?>
                                <div class="form-group row">
                                    <label for="amount-<?= $general->id ?>" class="col-sm-6 col-form-label"><?= $general->name ?></label>
                                    <div class="col-6">
                                        <input type="hidden" name="code[]" value="<?= $general->id ?>">
                                        <input value="<?= $general->amount ?>" tabindex="<?= $orderGeneral++ ?>" type="text" id="amount-<?= $general->id ?>" class="form-control indonesian-currency" name="amount[]">
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                </div>
                <div id="show-form" class="d-none">
                    <form id="form-set" autocomplete="off">
                    </form>
                </div>
                <div id="show-form-ab" class="d-none">
                    <form id="form-set-ab" autocomplete="off">
                        <?php
                        if ($packageAB) {
                        ?>
                            <div class="form-group row">
                                <label for="amount-<?= $packageAB->id ?>" class="col-sm-6 col-form-label"><?= $packageAB->name ?></label>
                                <div class="col-6">
                                    <input type="hidden" name="code" value="<?= $packageAB->id ?>">
                                    <input value="<?= $packageAB->amount ?>" tabindex="1" type="text" id="amount-<?= $packageAB->id ?>" class="form-control indonesian-currency" name="amount">
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </form>
                </div>
                <div id="show-form-cd" class="d-none">
                    <form id="form-set-cd" autocomplete="off">
                        <?php
                        if ($packageCD) {
                        ?>
                            <div class="form-group row">
                                <label for="amount-<?= $packageCD->id ?>" class="col-sm-6 col-form-label"><?= $packageCD->name ?></label>
                                <div class="col-6">
                                    <input type="hidden" name="code" value="<?= $packageCD->id ?>">
                                    <input value="<?= $packageCD->amount ?>" tabindex="1" type="text" id="amount-<?= $packageCD->id ?>" class="form-control indonesian-currency" name="amount">
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </form>
                </div>
            </div>
            <div class="modal-footer justify-content-between p-2">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm d-none" id="save-form-general">Simpan</button>
                <button type="button" class="btn btn-primary btn-sm d-none" id="save-form-ab">Simpan</button>
                <button type="button" class="btn btn-primary btn-sm d-none" id="save-form-cd">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-set-shift" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Form Atur Shift</h6>
                <div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
                    <i class="fas fa-times-circle text-danger"></i>
                </div>
            </div>
            <div class="modal-body">
                <form id="form-set-shift" autocomplete="off">
                    <?php
                    $orderShift = 1;
                    if ($shifts) {
                        $name = [
                            'BREAKFAST' => 'SARAPAN',
                            'MORNING' => 'PAGI',
                            'AFTERNOON' => 'SORE',
                            'NIGHT' => 'MALAM'
                        ];
                        foreach ($shifts as $shift) {
                    ?>
                            <input type="hidden" name="id[]" value="<?= $shift->id ?>">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?= $name[$shift->name] ?></label>
                                <div class="col-8 row">
                                    <div class="col-6">
                                        <input value="<?= $shift->begin ?>" tabindex="<?= $orderShift++ ?>" type="text" class="form-control" name="begin[]" data-inputmask="'mask' : '99:99:99'" data-mask="">
                                    </div>
                                    <div class="col-6">
                                        <input value="<?= $shift->finish ?>" tabindex="<?= $orderShift++ ?>" type="text" class="form-control" name="finish[]" data-inputmask="'mask' : '99:99:99'" data-mask="">
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
            </div>
            <div class="modal-footer justify-content-between p-2">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm" id="save-shift">Simpan</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('payment-setting/js-payment-setting'); ?>