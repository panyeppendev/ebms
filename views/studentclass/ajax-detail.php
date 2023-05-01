<div class="row">
    <?php
    if ($data) {
        $avatarPath = FCPATH . 'assets/avatars/' . $data->id . '.jpg';

        if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
            $avatar = base_url('assets/avatars/default.jpg');
        } else {
            $avatar = base_url('assets/avatars/' . $data->id . '.jpg');
        }

        $city = str_replace(['Kabupaten', 'Kota'], '', $data->city);
        $status = $data->status;
        if ($status == 'AKTIF') {
            $class = 'success';
        } elseif ($status == 'BERHENTI') {
            $class = 'danger';
        } else {
            $class = 'warning';
        }
    ?>

        <div class="col-12 col-md-12 col-sm-12 col-lg-5">
            <div class="callout callout-success py-1 px-3">
                <b>DATA DIRI</b>
            </div>
            <dl class="row">
                <dt class="col-sm-3 font-weight-normal mb-1">ID</dt>
                <dd class="col-sm-9 mb-1">
                    <?= $data->id ?>
                </dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Nama</dt>
                <dd class="col-sm-9 mb-1 font-weight-bold"><?= $data->name ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">NIK</dt>
                <dd class="col-sm-9 mb-1"><?= $data->nik ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">KK</dt>
                <dd class="col-sm-9 mb-1"><?= $data->kk ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Tetala</dt>
                <dd class="col-sm-9 mb-1"><?= $data->place_of_birth ?>, <?= dateIDFormat($data->date_of_birth) ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Pend. Akhir</dt>
                <dd class="col-sm-9 mb-1"><?= $data->last_education ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Alamat</dt>
                <dd class="col-sm-9 mb-1"><?= $data->address ?>, <?= $data->village ?>
                    <br><?= $data->district ?> <?= $city ?> <?= $data->province ?>, <?= $data->postal_code ?>
                </dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Domisili</dt>
                <dd class="col-sm-9 mb-1">
                    <span class="badge badge-success"><?= $data->status_of_domicile ?></span>
                    <?= $data->domicile ?>
                </dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Diniyah</dt>
                <dd class="col-sm-9 mb-1"><?= $data->class ?> - <?= $data->level ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Formal</dt>
                <dd class="col-sm-9 mb-1"><?= $data->class_of_formal ?> - <?= $data->level_of_formal ?></dd>
            </dl>
        </div>
        <div class="col-12 col-md-12 col-sm-12 col-lg-4">
            <div class="callout callout-success py-1 px-3">
                <b>DATA ORANG TUA</b>
            </div>
            <dl class="row mb-3">
                <dt class="col-sm-3 font-weight-normal mb-1">NIK Ayah</dt>
                <dd class="col-sm-9 mb-1"><?= $data->father_nik ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Nama Ayah</dt>
                <dd class="col-sm-9 mb-1 font-weight-bold"><?= $data->father ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">NIK Ibu</dt>
                <dd class="col-sm-9 mb-1"><?= $data->mother_nik ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Nama Ibu</dt>
                <dd class="col-sm-9 mb-1"><?= $data->mother ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">No. HP</dt>
                <dd class="col-sm-9 mb-1"><?= $data->phone ?></dd>
            </dl>
            <div class="mb-3">
                <form action="<?= base_url() ?>student/getlinkKTWS" method="post" target="_blank" id="form-print-card">
                    <input type="hidden" name="id" value="<?= $data->id ?>">
                    <button type="submit" class="btn btn-primary btn-block btn-sm"><i class="fa fa-print"></i> Print KTWS</button>
                </form>
            </div>
            <div class="callout callout-success py-1 px-3">
                <b>ADMINISTRASI</b>
            </div>
            <dl class="row">
                <dt class="col-sm-4 font-weight-normal mb-1">Tahun Periode</dt>
                <dd class="col-sm-8 mb-1"><?= $data->period ?></dd>
                <dt class="col-sm-4 font-weight-normal mb-1">Tanggal Masuk</dt>
                <dd class="col-sm-8 mb-1"><?= dateHijriFormat($data->date_of_entry_hijriah) ?></dd>
            </dl>
            <i class="fas fa-info-circle"></i> Santri ini tercatat : <span class="badge badge-<?= $class ?>"><?= $status ?></span>
        </div>
        <div class="col-12 col-md-12 col-sm-12 col-lg-3">
            <div class="card mb-0">
                <div class="card-body box-profile">
                    <div class="text-center mb-4">
                        <img src="<?= $avatar ?>" alt="IMAGE OF <?= $data->name ?>" style="width: 150px; border-radius: 3px;">
                    </div>
                    <hr>
                    <button onclick="editData(<?= $data->id ?>)" type="button" class="btn btn-default btn-block btn-sm mb-3"><i class="fa fa-edit"></i> Edit data</button>
                    <?php if ($card) { ?>
                        <?php if ($card->status == 'INACTIVE') { ?>
                            <form action="<?= base_url() ?>student/getlink" method="post" target="_blank" id="form-print-card">
                                <input type="hidden" name="id" value="<?= $data->id ?>">
                                <input type="hidden" name="card" value="<?= $card->id ?>">
                                <button type="submit" class="btn btn-primary btn-block btn-sm"><i class="fa fa-print"></i> Print Kartu</button>
                            </form>
                            <button type="button" onclick="activeCard(this, '<?= $card->id ?>', '<?= $data->id ?>')" class="btn btn-success btn-block btn-sm mt-3"><i class="fa fa-print"></i> Aktivasi Kartu</button>
                        <?php } elseif ($card->status == 'ACTIVE') { ?>
                            <button onclick="blockCard(this, '<?= $card->id ?>', '<?= $data->id ?>')" type="button" class="btn btn-danger btn-block btn-sm">
                                <i class="fas fa-ban"></i> Blokir Kartu
                            </button>
                        <?php } ?>
                    <?php } else { ?>
                        <button onclick="makeCard(this, '<?= $data->id ?>')" type="button" class="btn btn-default btn-block btn-sm">
                            <i class="fas fa-id-card"></i> Buat Kartu
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>