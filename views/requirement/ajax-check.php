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
    ?>
        <div class="col-8 px-4">
            <div class="callout callout-success py-1 px-3">
                <b>DATA DIRI</b>
            </div>
            <dl class="row">
                <dt class="col-sm-3 font-weight-normal mb-1">NIS</dt>
                <dd class="col-sm-9 mb-1">
                    <?= $data->id ?>
                </dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Nama</dt>
                <dd class="col-sm-9 mb-1 font-weight-bold"><?= $data->name ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Tetala</dt>
                <dd class="col-sm-9 mb-1"><?= $data->place_of_birth ?>, <?= dateIDFormat($data->date_of_birth) ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Alamat</dt>
                <dd class="col-sm-9 mb-1"><?= $data->village ?>, <?= $city ?>
                </dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Domisili</dt>
                <dd class="col-sm-9 mb-1">
                    <span class="badge badge-success"><?= $data->domicile ?></span>
                </dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Diniyah</dt>
                <dd class="col-sm-9 mb-1"><?= $data->class ?> - <?= $data->level ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Formal</dt>
                <dd class="col-sm-9 mb-1"><?= $data->class_of_formal ?> - <?= $data->level_of_formal ?></dd>
            </dl>
            <div class="callout callout-success py-1 px-3">
                <b>INFORMASI PERSYARATAN</b>
            </div>
            <dl class="row">
                <dt class="col-sm-12 font-weight-normal mb-1">
                    <i class="text-success">Menyelesaikan <?= $yes ?> dari <?= $total ?> item</i>
                </dt>
            </dl>
        </div>
        <div class="col-4">
            <div class="card mb-0">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img src="<?= $avatar ?>" alt="IMAGE OF <?= $data->name ?>" style="width: 100%; border-radius: 3px;">
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>