<?php
if ($data['status'] == 200) {
    $avatarPath = FCPATH . 'assets/avatars/' . $data['student']->id . '.jpg';

    if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
        $avatar = base_url('assets/avatars/default.jpg');
    } else {
        $avatar = base_url('assets/avatars/' . $data['student']->id . '.jpg');
    }

    $city = str_replace(['Kabupaten', 'Kota'], '', $data['student']->city);
?>
    <div class="row">
        <div class="col-4">
            <div class="box-profile">
                <div class="text-center">
                    <img src="<?= $avatar ?>" alt="IMAGE OF <?= $data['student']->name ?>" style="width: 100%; border-radius: 3px;">
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="row">
                <?php if ($data['status_send'] == 400) { ?>
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <?= $data['message'] ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-12">
                    <dl class="row">
                        <dt class="col-sm-3 font-weight-normal mb-1">Nama</dt>
                        <dd class="col-sm-9 mb-1 font-weight-bold"><?= $data['student']->name ?></dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">NIS</dt>
                        <dd class="col-sm-9 mb-1">
                            <?= $data['student']->id ?>
                        </dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Tetala</dt>
                        <dd class="col-sm-9 mb-1">
                            <?= $data['student']->place_of_birth . ', ' . dateIDFormat($data['student']->date_of_birth) ?>
                        </dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Alamat</dt>
                        <dd class="col-sm-9 mb-1">
                            <?= $data['student']->village ?>, <?= $city ?>
                        </dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Domisili</dt>
                        <dd class="col-sm-9 mb-1">
                            <?= $data['student']->domicile ?>
                        </dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Diniyah</dt>
                        <dd class="col-sm-9 mb-1"><?= $data['student']->class ?> - <?= $data['student']->level ?></dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Formal</dt>
                        <dd class="col-sm-9 mb-1"><?= $data['student']->class_of_formal ?> - <?= $data['student']->level_of_formal ?></dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Wali</dt>
                        <dd class="col-sm-9 mb-1 font-weight-bold"><?= $data['student']->father ?></dd>
                    </dl>
                </div>
                <div class="col-12">
                    <div class="callout callout-success py-2">
                        <span class="text-success font-weight-bold">
                            <?= $data['package'] ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    echo 'Gagal memuat data. Segera hubungi Developer';
}
?>